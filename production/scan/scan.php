<?php
require_once '../../config/config_db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Scanner un badge</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        .scan-container {
            text-align: center;
            margin: 20px auto;
            padding: 20px;
            max-width: 500px;
        }
        #reader {
            width: 300px;
            height: 300px;
            margin: 0 auto;
            border: 2px dashed #ccc;
        }
        #scanResult {
            display: block;
            margin: 10px 0;
            font-size: 1.2em;
            font-weight: bold;
            min-height: 24px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="scan-container">
    <h2><i class="fas fa-camera"></i> Scanner un badge</h2>
    <div id="reader"></div>
    <input type="text" id="champBadge" placeholder="Code badge scanné" readonly>
    <span id="scanResult"></span>
</div>

<button onclick="window.location.href='/TEMPLATE/production/index.php'" 
        style="margin-top: 20px; padding: 10px 20px; background-color: #3a7bd5; color: white; border: none; border-radius: 5px; cursor: pointer;">
    <i class="fas fa-arrow-left"></i> Retour au dashboard
</button>

<script>
// Configuration du scanner
const html5QrCode = new Html5Qrcode("reader");
const qrCodeSuccessCallback = (decodedText, decodedResult) => {
    // Arrêter le scanner une fois le code lu
    html5QrCode.stop().then((ignore) => {
        console.log("QR Code scanning stopped");
    }).catch((err) => {
        console.error("Failed to stop scanner", err);
    });
    
    document.getElementById('champBadge').value = decodedText;
    document.getElementById('scanResult').innerHTML = "✅ Code scanné: " + decodedText;
    
    // Réinitialiser après 3 secondes
    setTimeout(() => {
        document.getElementById('scanResult').innerHTML = "";
        startScanner();
    }, 3000);
};

const config = { 
    fps: 10, 
    qrbox: { width: 250, height: 250 },
    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
    formatsToSupport: [
        Html5QrcodeSupportedFormats.QR_CODE,
        Html5QrcodeSupportedFormats.UPC_A,
        Html5QrcodeSupportedFormats.UPC_E,
        Html5QrcodeSupportedFormats.UPC_EAN_EXTENSION
    ]
};

function startScanner() {
    document.getElementById('champBadge').value = "";
    
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        qrCodeSuccessCallback,
        (errorMessage) => {
            // Essayez avec la caméra avant si la caméra arrière échoue
            if (errorMessage.includes("environment")) {
                html5QrCode.start(
                    { facingMode: "user" }, // Caméra avant
                    config,
                    qrCodeSuccessCallback,
                    (error) => {
                        document.getElementById('scanResult').innerHTML = 
                            `<span class="error">Erreur: ${error}</span>`;
                        console.error("Scan error", error);
                    }
                );
            } 
        }
    ).catch((err) => {
        document.getElementById('scanResult').innerHTML = 
            `<span class="error">Erreur initialisation: ${err}</span>`;
        console.error("Initialization error", err);
    });
}

// Démarrer le scanner au chargement
window.onload = () => {
    // Vérifier la compatibilité
    if (!Html5Qrcode.getCameras().then(() => true).catch(() => false)) {
        document.getElementById('scanResult').innerHTML = 
            '<span class="error">Votre navigateur ne supporte pas le scan de QR code</span>';
        return;
    }
    
    startScanner();
};

// Permettre aussi la saisie manuelle
document.getElementById('champBadge').addEventListener('change', (e) => {
    if (e.target.value.trim()) {
        document.getElementById('scanResult').innerHTML = "✅ Code saisi: " + e.target.value;
    }
});
</script>
</body>
</html>