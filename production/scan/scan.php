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
body {
    background: #fff !important;
}
.scan-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}
#reader {
    background: #fff;
    border: 2px dashed #3a7bd5;
    border-radius: 8px;
}
        .scan-container { text-align: center; margin: 20px auto; padding: 20px; max-width: 500px; }
        #reader { width: 300px; height: 300px; margin: 0 auto; border: 2px dashed #ccc; }
        #scanResult { display: block; margin: 10px 0; font-size: 1.2em; font-weight: bold; min-height: 24px; }
        .error { color: red; }
        .profil-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px auto;
            font-size: 2.5em;
            color: #888;
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
    <button onclick="window.location.href='../dash.php'" style="margin-top: 20px; padding: 10px 20px; background-color: #3a7bd5; color: white; border: none; border-radius: 5px; cursor: pointer;">
        <i class="fas fa-arrow-left"></i> Retour au dashboard
    </button>
    <script>
    function startScanner() {
        const html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: 250 };
        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                html5QrCode.stop();
                document.getElementById('champBadge').value = decodedText;
                chercherBadge();
            },
            (errorMessage) => {}
        ).catch(err => {
            document.getElementById('scanResult').innerHTML = "Erreur lors du scan : " + err;
        });
    }
    function chercherBadge() {
        const code = document.getElementById('champBadge').value.trim();
        if (code) {
            fetch('scan2.php?code=' + encodeURIComponent(code))
                .then(response => response.json())
                .then(data => {
                    if (data.found) {
                        document.getElementById('scanResult').innerHTML = `
                            <div>✅ Badge trouvé</div>
                            <div class="profil-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div style="font-weight:bold; margin-top:8px;">${data.nom} - ${data.prenom}</div>
                            <div style="color:#555;">${data.poste} - ${data.service}</div>
                        `;
                    } else {
                        document.getElementById('scanResult').innerHTML = "❌ Badge inconnu";
                    }
                    setTimeout(() => {
                        document.getElementById('champBadge').value = "";
                        document.getElementById('scanResult').innerHTML = "";
                        startScanner();
                    }, 3000);
                });
        }
    }
    window.onload = startScanner;
    </script>
</body>
</html>