<?php
require_once '../../config/config_db.php';
require_once '../phpqrcode/qrlib.php';

$id_personnel = isset($_GET['id_personnel']) ? intval($_GET['id_personnel']) : 0;

if ($id_personnel > 0) {
    $stmt = $pdo->prepare("SELECT code_badge FROM badge WHERE id_personnel = :id_personnel ORDER BY id_badge DESC LIMIT 1");
    $stmt->bindValue(':id_personnel', $id_personnel, PDO::PARAM_INT);
    $stmt->execute();
    $badge = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($badge && !empty($badge['code_badge'])) {
        $code_badge = $badge['code_badge'];

        $qr_dir = __DIR__ . '/';
        $qr_filename = 'qr_' . $code_badge . '.png';
        $qr_path = $qr_dir . $qr_filename;
        $qr_code_rel = $qr_filename;

        // Correction ici : on passe une chaîne, pas un tableau
        QRcode::png($code_badge, $qr_path, 'H', 10, 2);

        $stmt = $pdo->prepare("UPDATE personnel SET qr_code = :qr_code WHERE id_personnel = :id_personnel");
        $stmt->bindValue(':qr_code', $qr_code_rel, PDO::PARAM_STR);
        $stmt->bindValue(':id_personnel', $id_personnel, PDO::PARAM_INT);
        $stmt->execute();

        echo "QR code généré et enregistré pour le personnel $id_personnel.";
    } else {
        echo "Aucun code_badge trouvé pour ce personnel.";
    }
} else {
    echo "ID personnel manquant ou invalide.";
}
?>