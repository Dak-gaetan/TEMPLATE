<?php
require_once '../../config/config_db.php'; // Assure-toi que ce fichier crée bien $pdo (PDO)

header('Content-Type: application/json');

$code = $_GET['code'] ?? '';
$result = ['found' => false];

if ($code) {
    $stmt = $pdo->prepare(
        "SELECT p.nom, p.prenom, s.libelle AS service, po.libelle AS poste
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
        $result = [
            'found' => true,
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'poste' => $row['poste'],
            'service' => $row['service']
        ];
        
    }
}
echo json_encode($result);
exit;