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
$badge_stats = $stmt_badges->fetch(PDO::FETCH_ASSOC);
$response['badge_stats'] = $badge_stats;

// 5. STATISTIQUES GÉNÉRALES
$query_summary = "SELECT 
                    (SELECT COUNT(*) FROM personnel) as total_personnel,
                    (SELECT COUNT(*) FROM pointage WHERE date_pointage = CURDATE()) as pointages_aujourd_hui,
                    (SELECT COUNT(DISTINCT id_personnel) FROM pointage WHERE date_pointage = CURDATE()) as personnes_presentes_aujourd_hui,
                    (SELECT AVG(TIME_TO_SEC(duree))/3600 FROM pointage WHERE heure_sorti IS NOT NULL AND date_pointage BETWEEN ? AND ?) as duree_moyenne_heures";

$stmt_summary = $pdo->prepare($query_summary);
$stmt_summary->execute([$start_date, $end_date]);
$response['summary'] = $stmt_summary->fetch(PDO::FETCH_ASSOC);

// Calculer le pourcentage de présence
$total_personnel = (int)$response['summary']['total_personnel'];
$presences_aujourd_hui = (int)$response['summary']['personnes_presentes_aujourd_hui'];
$response['summary']['taux_presence'] = $total_personnel > 0 ? round(($presences_aujourd_hui / $total_personnel) * 100, 1) : 0;

header('Content-Type: application/json');
echo json_encode($response);
?>