<?php
require_once '../../config/config_db.php'; // Suppose que ce fichier crée $pdo (PDO)

header('Content-Type: application/json');

$code = $_GET['code'] ?? '';
$result = ['found' => false];

if ($code) {
    // Démarrer une transaction
    $pdo->beginTransaction();
    
    try {
        // Vérifier si la personne existe avec le code de badge
        $stmt = $pdo->prepare(
            "SELECT p.id_personnel, COALESCE(p.nom, '') AS nom, COALESCE(p.prenom, '') AS prenom, 
                    COALESCE(s.libelle, '') AS service, COALESCE(po.libelle, '') AS poste
             FROM badge b
             JOIN personnel p ON b.id_personnel = p.id_personnel
             JOIN service s ON p.id_service = s.id_service
             JOIN poste po ON p.id_poste = po.id_poste
             WHERE b.code_badge = :code_badge"
        );
        $stmt->bindValue(':code_badge', $code, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Préparer la réponse
            $result = [
                'found' => true,
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'poste' => $row['poste'],
                'service' => $row['service']
            ];

            // Obtenir la date et l'heure actuelles
            $today = date('Y-m-d');
            $current_time = date('H:i:s');

            // Vérifier s'il existe un enregistrement de pointage pour aujourd'hui
            $checkStmt = $pdo->prepare(
                "SELECT id, heure_entrer, heure_sorti 
                 FROM pointage 
                 WHERE id_personnel = :id_personnel 
                 AND date_pointage = :date_pointage
                 ORDER BY heure_entrer DESC
                 LIMIT 1"
            );
            $checkStmt->bindValue(':id_personnel', $row['id_personnel'], PDO::PARAM_INT);
            $checkStmt->bindValue(':date_pointage', $today, PDO::PARAM_STR);
            $checkStmt->execute();
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing) {
                // Premier badgeage du jour : créer un nouvel enregistrement avec heure_entrer et heure_sorti à NULL
                $insertStmt = $pdo->prepare(
                    "INSERT INTO pointage (date_pointage, heure_entrer, id_personnel)
                     VALUES (:date_pointage, :heure_entrer, :id_personnel)"
                );
                $insertStmt->bindValue(':date_pointage', $today, PDO::PARAM_STR);
                $insertStmt->bindValue(':heure_entrer', $current_time, PDO::PARAM_STR);
                $insertStmt->bindValue(':id_personnel', $row['id_personnel'], PDO::PARAM_INT);
                $insertStmt->execute();
            } else {
                // Vérifier si heure_sorti est NULL dans le dernier enregistrement
                if ($existing['heure_entrer'] !== null && $existing['heure_sorti'] === null) {
                    // Mettre à jour l'heure_sorti de l'enregistrement existant
                    $updateStmt = $pdo->prepare(
                        "UPDATE pointage 
                         SET heure_sorti = :heure_sorti
                         WHERE id = :id"
                    );
                    $updateStmt->bindValue(':heure_sorti', $current_time, PDO::PARAM_STR);
                    $updateStmt->bindValue(':id', $existing['id'], PDO::PARAM_INT);
                    $updateStmt->execute();
                } else {
                    // Si heure_sorti est non NULL, créer un nouvel enregistrement pour un nouveau cycle
                    $insertStmt = $pdo->prepare(
                        "INSERT INTO pointage (date_pointage, heure_entrer, id_personnel)
                         VALUES (:date_pointage, :heure_entrer, :id_personnel)"
                    );
                    $insertStmt->bindValue(':date_pointage', $today, PDO::PARAM_STR);
                    $insertStmt->bindValue(':heure_entrer', $current_time, PDO::PARAM_STR);
                    $insertStmt->bindValue(':id_personnel', $row['id_personnel'], PDO::PARAM_INT);
                    $insertStmt->execute();
                }
            }

            // Valider la transaction
            $pdo->commit();
        } else {
            // Annuler si aucune personne n'est trouvée
            $pdo->rollBack();
        }
    } catch (Exception $e) {
        // Annuler en cas d'erreur
        $pdo->rollBack();
        $result = [
            'found' => false,
            'error' => 'Une erreur est survenue : ' . $e->getMessage()
        ];
    }
}

echo json_encode($result);
exit;