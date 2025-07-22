<?php
// Compter les employés ayant une disponibilité "Disponible"
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM personnel p
    JOIN disponibilite d ON p.id_disponibilite = d.id_disponibilite
    WHERE d.libelle = 'Disponible'
");
$stmt->execute();
$disponibles_total = $stmt->fetchColumn();

$today = date('Y-m-d');

// Nombre total de retard parmi les disponibles (pointage entre 07:31 et 08:00)
$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT p.id_personnel)
    FROM personnel p
    JOIN disponibilite d ON p.id_disponibilite = d.id_disponibilite
    JOIN pointage pt ON p.id_personnel = pt.id_personnel
    WHERE d.libelle = 'Disponible'
      AND pt.date_pointage = ?
      AND pt.heure_entrer >= '07:31'
      AND pt.heure_entrer <= '08:00'
");
$stmt->execute([$today]);
$retards_total = $stmt->fetchColumn();

// Nombre total d'absents parmi les disponibles (pas de pointage avant ou à 08:01)
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM personnel p
    JOIN disponibilite d ON p.id_disponibilite = d.id_disponibilite
    WHERE d.libelle = 'Disponible'
      AND NOT EXISTS (
        SELECT 1 FROM pointage pt
        WHERE pt.id_personnel = p.id_personnel
          AND pt.date_pointage = ?
          AND pt.heure_entrer <= '08:01'
      )
");
$stmt->execute([$today]);
$absents_total = $stmt->fetchColumn();

// Calculer le nombre de présents (non en retard et non absents)
$presences_total = $disponibles_total - ($retards_total + $absents_total);
$taux_presence = ($disponibles_total > 0) ? ($presences_total / $disponibles_total) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord RH</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/daterangepicker/3.1.0/daterangepicker.css" />
    <style>
        /* Styles de base pour les cartes */
        .x_panel.tile {
            border: 1px solid #e6e9ed;
            border-radius: 5px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        /* Couleurs personnalisées pour chaque carte */
        .personnel-tile {
            background-color: #f0f4f7;
            border-left: 4px solid #3498db;
        }
        .presence-tile {
            background-color: #eef7ee;
            border-left: 4px solid #2ecc71;
        }
        .retard-tile {
            background-color: #fff3e0;
            border-left: 4px solid #ff9800;
        }
        .absent-tile {
            background-color: #fce4ec;
            border-left: 4px solid #e91e63;
        }
        .disponible-tile {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
        }

        /* Style du texte et des icônes */
        .count_top {
            font-size: 16px;
            color: #666;
            display: block;
            margin-bottom: 10px;
        }
        .count {
            font-size: 30px;
            font-weight: bold;
            color: #333;
            text-align: center;
            padding: 10px 0;
        }
        .green { color: #2ecc71; }
        .fa-spinner { margin-right: 5px; }

        /* Effet au survol */
        .x_panel.tile:hover {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="right_col" role="main">       
        <br/>
        
        <!-- Indicateurs statistiques -->
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="x_panel tile personnel-tile">
                    <div class="x_content">
                        <span class="count_top"><i class="fa fa-users"></i> Personnel Total</span>
                        <div class="count" id="total-personnel"><i class="fa fa-spinner fa-spin"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="x_panel tile disponible-tile">
                    <div class="x_content">
                        <span class="count_top"><i class="fa fa-user-check"></i> Nombre Total Disponible</span>
                        <div class="count" id="disponibles-total"><?php echo $disponibles_total; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="x_panel tile presence-tile">
                    <div class="x_content">
                        <span class="count_top"><i class="fa fa-check"></i> Présents Aujourd'hui</span>
                        <div class="count green" id="presences-today"><?php echo $presences_total; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="x_panel tile retard-tile">
                    <div class="x_content">
                        <span class="count_top"><i class="fa fa-clock-o"></i> Nombre Total de Retard</span>
                        <div class="count" id="retards-total"><?php echo $retards_total; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="x_panel tile absent-tile">
                    <div class="x_content">
                        <span class="count_top"><i class="fa fa-user-times"></i> Nombre Total d'Absent</span>
                        <div class="count" id="absents-total"><?php echo $absents_total; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="x_panel fixed_height_320">
                    <div class="x_title">
                        <h2>Répartition Services <small>Personnel par service</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Settings 1</a>
                                    <a class="dropdown-item" href="#">Settings 2</a>
                                </div>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <h4>Postes & Effectifs</h4>
                        <div id="postes-container">
                            <div class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement des données...</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="x_panel tile fixed_height_320">
                    <div class="x_title">
                        <h2>Statut Aujourd'hui <small>Présences, Absences, Retards</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Settings 1</a>
                                    <a class="dropdown-item" href="#">Settings 2</a>
                                </div>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="" style="width:100%">
                            <tr>
                                <th style="width:37%;">
                                    <p>Statut</p>
                                </th>
                                <th>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        <p class="">Statut</p>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <p class="">Pourcentage</p>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <canvas class="canvasDoughnut" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
                                </td>
                                <td>
                                    <table class="tile_info">
                                        <tr><td colspan="2" class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement...</td></tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="x_panel fixed_height_320">
                    <div class="x_title">
                        <h2>Statistiques RH <small>Indicateurs</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Settings 1</a>
                                    <a class="dropdown-item" href="#">Settings 2</a>
                                </div>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="dashboard-widget-content">
                            <ul class="quick-list">
                                <li><i class="fa fa-users"></i><a href="#">Personnel</a></li>
                                <li><i class="fa fa-clock-o"></i><a href="#">Pointages</a></li>
                                <li><i class="fa fa-credit-card"></i><a href="#">Badges</a></li>
                                <li><i class="fa fa-building"></i><a href="#">Services</a></li>
                                <li><i class="fa fa-briefcase"></i><a href="#">Postes</a></li>
                            </ul>
                            <div class="sidebar-widget">
                                <h4>Taux de Présence</h4>
                                <canvas width="150" height="80" id="chart_gauge_01" class="" style="width: 160px; height: 100px;"></canvas>
                                <div class="goal-wrapper">
                                    <span id="gauge-text" class="gauge-value gauge-chart pull-left"><?php echo number_format($taux_presence, 1); ?></span>
                                    <span class="gauge-value pull-left">%</span>
                                    <span id="goal-text" class="goal-value pull-right">100%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts nécessaires -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/daterangepicker/3.1.0/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gauge.js/1.3.7/gauge.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.flot/0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flot.curvedlines/1.1.1/curvedLines.min.js"></script>

    <script>
    $(document).ready(function() {
        // Fonction pour dessiner le graphique donut avec Chart.js
        function drawDonutChartJS(data) {
            var canvas = document.querySelector('.canvasDoughnut');
            if (!canvas || data.length === 0) return;

            if (typeof Chart === 'undefined') {
                console.error('Chart.js n\'est pas chargé');
                return;
            }

            if (window.servicesChart) {
                window.servicesChart.destroy();
            }

            var ctx = canvas.getContext('2d');
            var labels = data.map(item => item.label);
            var values = data.map(item => item.data);
            var colors = data.map(item => item.color);

            try {
                window.servicesChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: colors,
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        legend: { display: false },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var label = data.labels[tooltipItem.index];
                                    var value = data.datasets[0].data[tooltipItem.index];
                                    var total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    var percentage = Math.round((value / total) * 100);
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Erreur lors de la création du graphique donut:', error);
                drawSimpleDonut(data, canvas);
            }
        }

        // Fonction de secours pour dessiner un donut simple
        function drawSimpleDonut(data, canvas) {
            var ctx = canvas.getContext('2d');
            var centerX = canvas.width / 2;
            var centerY = canvas.height / 2;
            var radius = Math.min(centerX, centerY) - 20;
            var innerRadius = radius * 0.5;

            var total = data.reduce((sum, item) => sum + item.data, 0);
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            var currentAngle = -Math.PI / 2;
            data.forEach(function(item) {
                var sliceAngle = (item.data / total) * 2 * Math.PI;
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
                ctx.arc(centerX, centerY, innerRadius, currentAngle + sliceAngle, currentAngle, true);
                ctx.closePath();
                ctx.fillStyle = item.color;
                ctx.fill();
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 2;
                ctx.stroke();
                currentAngle += sliceAngle;
            });

            ctx.fillStyle = '#333';
            ctx.font = 'bold 12px Arial';
            ctx.textAlign = 'center';
            ctx.fillText(total, centerX, centerY - 3);
            ctx.font = '9px Arial';
            ctx.fillText('Total', centerX, centerY + 10);
        }

        // Configuration du sélecteur de dates
        $('#reportrange').daterangepicker({
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: '01/01/2010',
            maxDate: moment().add(5, 'years'),
            ranges: {
                'Aujourd\'hui': [moment(), moment()],
                'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 derniers jours': [moment().subtract(6, 'days'), moment()],
                '30 derniers jours': [moment().subtract(29, 'days'), moment()],
                'Ce mois': [moment().startOf('month'), moment().endOf('month')],
                'Mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
            $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        });

        // Initialiser l'affichage des dates
        $('#reportrange span').html(moment().subtract(29, 'days').format('DD/MM/YYYY') + ' - ' + moment().format('DD/MM/YYYY'));

        // Fonction principale de chargement du tableau de bord
        function loadDashboard(startDate, endDate) {
            console.log("Chargement du tableau de bord pour la période : " + startDate + " à " + endDate);

            $.ajax({
                url: 'get_dashboard_stats.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    start: startDate,
                    end: endDate
                },
                success: function(response) {
                    console.log("Données reçues :", response);

                    // 1. Charger le graphique principal
                    loadChart(response.chart_data || []);

                    // 2. Charger les statistiques des postes
                    loadPostesStats(response.postes_stats || []);

                    // 3. Charger les statistiques de statut (Présents, Absents, Retards)
                    loadStatusChart(response.summary || {});

                    // 4. Charger la jauge de présence
                    var tauxPresence = response.summary && response.summary.taux_presence ? parseFloat(response.summary.taux_presence) : 0;
                    loadPresenceGauge(tauxPresence);

                    // 5. Mettre à jour les statistiques générales
                    updateSummaryStats(response.summary || {});
                },
                error: function(xhr, status, error) {
                    console.log('Erreur lors du chargement des données du tableau de bord:', error);
                    $('#total-personnel').text('Erreur');
                    $('#presences-today').text('Erreur');
                    $('#retards-total').text('Erreur');
                    $('#absents-total').text('Erreur');
                    $('#disponibles-total').text('Erreur');
                    $('#gauge-text').text('N/A');
                    loadPresenceGauge(0);
                }
            });
        }

        // 1. Fonction graphique principal
        function loadChart(data) {
            if ($("#chart_plot_03").length) {
                $("#chart_plot_03").empty();

                if (data.length > 0) {
                    var customSettings = {
                        series: {
                            curvedLines: {
                                apply: true,
                                active: true,
                                monotonicFit: true
                            }
                        },
                        colors: ["#26B99A"],
                        grid: {
                            borderWidth: {
                                top: 0,
                                right: 0,
                                bottom: 1,
                                left: 1
                            },
                            borderColor: {
                                bottom: "#7F8790",
                                left: "#7F8790"
                            }
                        }
                    };

                    $.plot($("#chart_plot_03"), [{
                        label: "Personnes présentes",
                        data: data,
                        lines: {
                            fillColor: "rgba(150, 202, 89, 0.12)"
                        },
                        points: {
                            fillColor: "#fff"
                        }
                    }], customSettings);
                } else {
                    $("#chart_plot_03").html('<div class="text-center" style="padding-top: 100px;"><i class="fa fa-info-circle fa-2x text-muted"></i><br><span style="margin-top: 10px; display: inline-block; color: #999;">Aucune donnée disponible pour cette période</span></div>');
                }
            }
        }

        // 2. Fonction statistiques des postes
        function loadPostesStats(postesData) {
            var $container = $('#postes-container');
            $container.empty();

            if (postesData && postesData.length > 0) {
                var total = Math.max(...postesData.map(p => parseInt(p.nb_employes)));

                postesData.forEach(function(poste, index) {
                    var percentage = total > 0 ? (parseInt(poste.nb_employes) / total) * 100 : 0;
                    var widgetHtml = `
                        <div class="widget_summary">
                            <div class="w_left w_25">
                                <span>${poste.poste}</span>
                            </div>
                            <div class="w_center w_55">
                                <div class="progress">
                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100" style="width: ${Math.round(percentage)}%;">
                                        <span class="sr-only">${Math.round(percentage)}% Complete</span>
                                    </div>
                                </div>
                            </div>
                            <div class="w_right w_20">
                                <span>${poste.nb_employes}</span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    `;
                    $container.append(widgetHtml);
                });
            } else {
                $container.html('<p class="text-muted">Aucune donnée de poste disponible</p>');
            }
        }

        // 3. Fonction graphique circulaire des statuts
        function loadStatusChart(summary) {
            var $tableInfo = $('.tile_info');
            var colors = ['green', 'red', 'orange'];
            var colorCodes = ['#2ecc71', '#e91e63', '#ff9800'];

            $tableInfo.empty();

            var donutData = [
                { label: 'Présents', data: parseInt(summary.personnes_presentes_aujourd_hui) || 0, color: colorCodes[0] },
                { label: 'Absents', data: parseInt(summary.absents_total) || 0, color: colorCodes[1] },
                { label: 'En Retard', data: parseInt(summary.retards_total) || 0, color: colorCodes[2] }
            ];

            var total = donutData.reduce((sum, item) => sum + item.data, 0);

            if (total > 0) {
                donutData.forEach(function(item, index) {
                    var percentage = total > 0 ? Math.round((item.data / total) * 100) : 0;
                    var rowHtml = `
                        <tr>
                            <td>
                                <p><i class="fa fa-square ${colors[index]}"></i>${item.label}</p>
                            </td>
                            <td>${percentage}%</td>
                        </tr>
                    `;
                    $tableInfo.append(rowHtml);
                });
                drawDonutChartJS(donutData);
            } else {
                $tableInfo.html('<tr><td colspan="2" class="text-muted">Aucune donnée de statut disponible</td></tr>');
                if (window.servicesChart) {
                    window.servicesChart.destroy();
                    window.servicesChart = null;
                }
            }
        }

        // 4. Fonction jauge de présence
        function loadPresenceGauge(percentage) {
            console.log("Chargement de la jauge avec:", percentage + "%");

            var validPercentage = parseFloat(percentage) || 0;
            validPercentage = Math.max(0, Math.min(100, validPercentage));

            $('#gauge-text').text(validPercentage.toFixed(1));
            drawGaugeTemplate(validPercentage);
        }

        // Fonction pour dessiner la jauge
        function drawGaugeTemplate(percentage) {
            var canvas = document.getElementById('chart_gauge_01');
            if (!canvas) {
                console.error("Canvas chart_gauge_01 non trouvé !");
                return;
            }

            if (typeof Gauge !== 'undefined') {
                console.log("Utilisation de Gauge.js");

                if (window.gaugeInstance) {
                    window.gaugeInstance = null;
                }

                var opts = {
                    lines: 12,
                    angle: 0,
                    lineWidth: 0.4,
                    pointer: {
                        length: 0.75,
                        strokeWidth: 0.042,
                        color: '#1D212A'
                    },
                    limitMax: false,
                    colorStart: '#1ABC9C',
                    colorStop: '#1ABC9C',
                    strokeColor: '#F0F3F3',
                    generateGradient: true
                };

                try {
                    window.gaugeInstance = new Gauge(canvas).setOptions(opts);
                    window.gaugeInstance.maxValue = 100;
                    window.gaugeInstance.minValue = 0;
                    window.gaugeInstance.animationSpeed = 32;
                    window.gaugeInstance.setTextField(document.getElementById("gauge-text"));
                    window.gaugeInstance.set(percentage);
                    console.log("Jauge créée avec succès, valeur:", percentage + "%");
                } catch (error) {
                    console.error('Erreur lors de la création de la jauge Gauge.js:', error);
                    drawSimpleGaugeTemplate(percentage, canvas);
                }
            } else {
                console.warn('Gauge.js non disponible, utilisation du secours canvas');
                drawSimpleGaugeTemplate(percentage, canvas);
            }
        }

        // Fonction de secours pour la jauge
        function drawSimpleGaugeTemplate(percentage, canvas) {
            console.log("Utilisation du secours canvas pour:", percentage + "%");

            var ctx = canvas.getContext('2d');
            var centerX = canvas.width / 2;
            var centerY = canvas.height - 10;
            var radius = Math.min(centerX, centerY) - 15;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, Math.PI, 2 * Math.PI, false);
            ctx.strokeStyle = '#F0F3F3';
            ctx.lineWidth = 8;
            ctx.lineCap = 'round';
            ctx.stroke();

            if (percentage > 0) {
                var progressAngle = Math.PI * (percentage / 100);
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, Math.PI, Math.PI + progressAngle, false);
                ctx.strokeStyle = '#1ABC9C';
                ctx.lineWidth = 8;
                ctx.lineCap = 'round';
                ctx.stroke();
            }

            ctx.strokeStyle = '#1D212A';
            ctx.lineWidth = 1;
            for (var i = 0; i <= 12; i++) {
                var angle = Math.PI + (Math.PI * i / 12);
                var x1 = centerX + (radius - 12) * Math.cos(angle);
                var y1 = centerY + (radius - 12) * Math.sin(angle);
                var x2 = centerX + (radius - 4) * Math.cos(angle);
                var y2 = centerY + (radius - 4) * Math.sin(angle);
                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x2, y2);
                ctx.stroke();
            }

            var needleAngle = Math.PI + (Math.PI * percentage / 100);
            var needleLength = radius * 0.75;
            var needleX = centerX + needleLength * Math.cos(needleAngle);
            var needleY = centerY + needleLength * Math.sin(needleAngle);

            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.lineTo(needleX, needleY);
            ctx.strokeStyle = '#1D212A';
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(centerX, centerY, 4, 0, 2 * Math.PI);
            ctx.fillStyle = '#1D212A';
            ctx.fill();

            $('#gauge-text').text(percentage.toFixed(1));
            console.log("Jauge secours dessinée avec succès:", percentage + "%");
        }

        // 5. Fonction statistiques générales
        function updateSummaryStats(summary) {
            $('#total-personnel').text(summary.total_personnel || 0);
            $('#presences-today').text(summary.personnes_presentes_aujourd_hui || 0);
            $('#retards-total').text(summary.retards_total || 0);
            $('#absents-total').text(summary.absents_total || 0);
            $('#disponibles-total').text(summary.disponibles_total || 0);
        }

        // Chargement initial avec données PHP
        var initialSummary = {
            total_personnel: <?php echo json_encode($disponibles_total); ?>,
            personnes_presentes_aujourd_hui: <?php echo json_encode($presences_total); ?>,
            retards_total: <?php echo json_encode($retards_total); ?>,
            absents_total: <?php echo json_encode($absents_total); ?>,
            disponibles_total: <?php echo json_encode($disponibles_total); ?>,
            taux_presence: <?php echo json_encode($taux_presence); ?>
        };
        updateSummaryStats(initialSummary);
        loadStatusChart(initialSummary);
        loadPresenceGauge(initialSummary.taux_presence); // Initialiser la jauge avec la valeur PHP

        // Chargement initial
        var defaultStart = moment().subtract(29, 'days').format('YYYY-MM-DD');
        var defaultEnd = moment().format('YYYY-MM-DD');

        console.log("=== INITIALISATION TABLEAU DE BORD ===");
        console.log("Gauge.js disponible:", typeof Gauge !== 'undefined');
        console.log("Canvas jauge disponible:", !!document.getElementById('chart_gauge_01'));

        loadDashboard(defaultStart, defaultEnd);

        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('YYYY-MM-DD');
            var endDate = picker.endDate.format('YYYY-MM-DD');
            loadDashboard(startDate, endDate);
        });

        setInterval(function() {
            var currentStart = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var currentEnd = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
            loadDashboard(currentStart, currentEnd);
        }, 300000);
    });
    </script>
</body>
</html>