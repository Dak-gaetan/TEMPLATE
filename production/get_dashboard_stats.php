<?php
include '../config/config_db.php';

// Récupérer les dates depuis les paramètres GET
$start_date = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d');

$response = [
    'chart_data' => [],
    'services_stats' => [],
    'postes_stats' => [],
    'badge_stats' => [],
    'personnel_stats' => [],
    'summary' => []
];

// 1. DONNÉES DU GRAPHIQUE PRINCIPAL - Pointages par jour
$query_chart = "SELECT DATE(date_pointage) as jour, COUNT(id_personnel) as nb_personnes 
                FROM pointage 
                WHERE date_pointage BETWEEN ? AND ? 
                GROUP BY jour";

$stmt_chart = $pdo->prepare($query_chart);
$stmt_chart->execute([$start_date, $end_date]);
$chart_results = $stmt_chart->fetchAll(PDO::FETCH_ASSOC);

$index = 0;
foreach ($chart_results as $row) {
    $response['chart_data'][] = [$index, (int)$row['nb_personnes']];
    $index++;
}

// 2. STATISTIQUES PAR SERVICE
$query_services = "SELECT s.libelle as service, COUNT(DISTINCT p.id_personnel) as nb_employes
                   FROM service s
                   LEFT JOIN personnel p ON s.id_service = p.id_service
                   GROUP BY s.id_service, s.libelle";

$stmt_services = $pdo->prepare($query_services);
$stmt_services->execute();
$response['services_stats'] = $stmt_services->fetchAll(PDO::FETCH_ASSOC);

// 3. STATISTIQUES PAR POSTE
$query_postes = "SELECT pos.libelle as poste, COUNT(p.id_personnel) as nb_employes
                 FROM poste pos
                 LEFT JOIN personnel p ON pos.id_poste = p.id_poste
                 GROUP BY pos.id_poste, pos.libelle";

$stmt_postes = $pdo->prepare($query_postes);
$stmt_postes->execute();
$response['postes_stats'] = $stmt_postes->fetchAll(PDO::FETCH_ASSOC);

// 4. STATISTIQUES DES BADGES
$query_badges = "SELECT 
                    COUNT(*) as total_badges,
                    SUM(CASE WHEN actif = 'oui' THEN 1 ELSE 0 END) as badges_actifs,
                    SUM(CASE WHEN actif != 'oui' OR actif IS NULL THEN 1 ELSE 0 END) as badges_inactifs
                 FROM badge";

$stmt_badges = $pdo->prepare($query_badges);
$stmt_badges->execute();
$response['badge_stats'] = $stmt_badges->fetch(PDO::FETCH_ASSOC);

// 5. STATISTIQUES GÉNÉRALES
// Nombre total de personnel
$query_total_personnel = "SELECT COUNT(*) as total_personnel FROM personnel";
$stmt_total_personnel = $pdo->prepare($query_total_personnel);
$stmt_total_personnel->execute();
$total_personnel = $stmt_total_personnel->fetchColumn();

// Nombre total d'employés disponibles
$query_disponibles = "SELECT COUNT(*) 
                      FROM personnel p
                      JOIN disponibilite d ON p.id_disponibilite = d.id_disponibilite
                      WHERE d.libelle = 'Disponible'";
$stmt_disponibles = $pdo->prepare($query_disponibles);
$stmt_disponibles->execute();
$disponibles_total = $stmt_disponibles->fetchColumn();

// Nombre total de pointages aujourd'hui (remplace l'ancienne logique des présents)
$query_pointages = "SELECT COUNT(DISTINCT id_personnel) as nb_pointages
                    FROM pointage
                    WHERE date_pointage = CURDATE()";
$stmt_pointages = $pdo->prepare($query_pointages);
$stmt_pointages->execute();
$personnes_presentes_aujourd_hui = $stmt_pointages->fetchColumn();

// Nombre total de retards aujourd'hui (pointage entre 07:31 et 08:00)
$query_retards = "SELECT COUNT(DISTINCT p.id_personnel)
                  FROM personnel p
                  JOIN disponibilite d ON p.id_disponibilite = d.id_disponibilite
                  JOIN pointage pt ON p.id_personnel = pt.id_personnel
                  WHERE d.libelle = 'Disponible'
                    AND pt.date_pointage = CURDATE()
                    AND pt.heure_entrer >= '07:31:00'
                    AND pt.heure_entrer <= '08:00:00'";
$stmt_retards = $pdo->prepare($query_retards);
$stmt_retards->execute();
$retards_total = $stmt_retards->fetchColumn();

// Nombre total d'absents aujourd'hui (pas de pointage avant ou à l'heure actuelle)
$current_time = date('H:i:s'); // Par exemple, '12:10:00' à 12:10 PM GMT
$query_absents = "SELECT COUNT(*) 
                  FROM personnel p
                  JOIN disponibilite d ON p.id_disponibilite = d.id_disponibilite
                  WHERE d.libelle = 'Disponible'
                    AND NOT EXISTS (
                        SELECT 1 FROM pointage pt
                        WHERE pt.id_personnel = p.id_personnel
                          AND pt.date_pointage = CURDATE()
                          AND pt.heure_entrer <= :current_time
                    )";
$stmt_absents = $pdo->prepare($query_absents);
$stmt_absents->execute(['current_time' => $current_time]);
$absents_total = $stmt_absents->fetchColumn();

// Durée moyenne des pointages sur la période
$query_duree = "SELECT AVG(TIME_TO_SEC(duree))/3600 
                FROM pointage 
                WHERE heure_sorti IS NOT NULL 
                  AND date_pointage BETWEEN ? AND ?";
$stmt_duree = $pdo->prepare($query_duree);
$stmt_duree->execute([$start_date, $end_date]);
$duree_moyenne_heures = $stmt_duree->fetchColumn();

$response['summary'] = [
    'total_personnel' => (int)$total_personnel,
    'personnes_presentes_aujourd_hui' => (int)$personnes_presentes_aujourd_hui, // Nombre de pointages
    'retards_total' => (int)$retards_total,
    'absents_total' => (int)$absents_total,
    'disponibles_total' => (int)$disponibles_total,
    'duree_moyenne_heures' => $duree_moyenne_heures ? round($duree_moyenne_heures, 1) : 0,
    'taux_presence' => $disponibles_total > 0 ? round(($personnes_presentes_aujourd_hui / $disponibles_total) * 100, 1) : 0
];

header('Content-Type: application/json');
echo json_encode($response);
?>