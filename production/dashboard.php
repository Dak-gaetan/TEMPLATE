<div class="right_col" role="main">       
            <br/>
            
            <!-- Indicateurs statistiques -->
            <div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="x_panel tile personnel-tile">
            <div class="x_content">
                <span class="count_top"><i class="fa fa-users"></i> Personnel Total</span>
                <div class="count" id="total-personnel"><i class="fa fa-spinner fa-spin"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="x_panel tile presence-tile">
            <div class="x_content">
                <span class="count_top"><i class="fa fa-check"></i> Présents Aujourd'hui</span>
                <div class="count green" id="presences-today"><i class="fa fa-spinner fa-spin"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="x_panel tile duree-tile">
            <div class="x_content">
                <span class="count_top"><i class="fa fa-clock-o"></i> Durée Moyenne</span>
                <div class="count" id="duree-moyenne"><i class="fa fa-spinner fa-spin"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="x_panel tile badge-tile">
            <div class="x_content">
                <span class="count_top"><i class="fa fa-credit-card"></i> Badges Actifs</span>
                <div class="count" id="badges-actifs"><i class="fa fa-spinner fa-spin"></i></div>
            </div>
        </div>
    </div>
</div>

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
        background-color: #f0f4f7; /* Bleu clair */
        border-left: 4px solid #3498db; /* Bordure bleue */
    }
    .presence-tile {
        background-color: #eef7ee; /* Vert clair */
        border-left: 4px solid #2ecc71; /* Bordure verte */
    }
    .duree-tile {
        background-color: #f9f0f0; /* Rouge clair */
        border-left: 4px solid #e74c3c; /* Bordure rouge */
    }
    .badge-tile {
        background-color: #f7f4f0; /* Jaune clair */
        border-left: 4px solid #f1c40f; /* Bordure jaune */
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
    .green { color: #2ecc71; } /* Vert pour les présents */
    .fa-spinner { margin-right: 5px; }

    /* Effet au survol */
    .x_panel.tile:hover {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }
</style>
            
            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="dashboard_graph x_panel">
                  <div class="x_title">
                    <div class="col-md-6">
                      <h3>Pointages Personnel <small></small></h3>
                    </div>
                    <div class="col-md-6">
                      <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>Chargement des dates...</span> <b class="caret"></b>
                      </div>
                    </div>
                  </div>
                  <div class="x_content">
                    <div class="demo-container" style="height:250px">
                      <div id="chart_plot_03" class="demo-placeholder">
                        <div class="text-center" style="padding-top: 100px;">
                          <i class="fa fa-spinner fa-spin fa-2x"></i><br>
                          <span style="margin-top: 10px; display: inline-block;">Chargement du graphique...</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 col-sm-6 ">
                <div class="x_panel fixed_height_320">
                  <div class="x_title">
                    <h2>Répartition Services <small>Personnel par service</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                          </div>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
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

              <div class="col-md-4 col-sm-6 ">
                <div class="x_panel tile fixed_height_320">
                  <div class="x_title">
                    <h2>Présences Aujourd'hui <small>Personnel présent</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                          </div>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <table class="" style="width:100%">
                    <tr>
                      <th style="width:37%;">
                        <p>Services</p>
                      </th>
                      <th>
                        <div class="col-lg-7 col-md-7 col-sm-7 ">
                          <p class="">Service</p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 ">
                          <p class="">Répartition</p>
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

              <div class="col-md-4 col-sm-6 ">
                <div class="x_panel fixed_height_320">
                  <div class="x_title">
                    <h2>Statistiques RH <small>Indicateurs</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                          </div>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
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
                          <span id="gauge-text" class="gauge-value gauge-chart pull-left"><i class="fa fa-spinner fa-spin"></i></span>
                          <span class="gauge-value pull-left">%</span>
                          <span id="goal-text" class="goal-value pull-right">100%</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <!-- start of weather widget -->

              <!-- end of weather widget -->
            </div>
          </div>
          
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
    
    // Fonction pour dessiner le graphique donut avec Chart.js
    function drawDonutChartJS(data) {
        var canvas = document.querySelector('.canvasDoughnut');
        if (!canvas || data.length === 0) return;
        
        // Vérifier si Chart.js est disponible
        if (typeof Chart === 'undefined') {
            console.error('Chart.js n\'est pas chargé');
            return;
        }
        
        // Détruire le graphique existant s'il y en a un
        if (window.servicesChart) {
            window.servicesChart.destroy();
        }
        
        var ctx = canvas.getContext('2d');
        
        // Préparer les données pour Chart.js
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
                    legend: {
                        display: false // On utilise le tableau à côté
                    },
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
            // Fallback : dessiner un graphique simple avec canvas
            drawSimpleDonut(data, canvas);
        }
    }
    
    // Fonction de fallback pour dessiner un donut simple
    function drawSimpleDonut(data, canvas) {
        var ctx = canvas.getContext('2d');
        var centerX = canvas.width / 2;
        var centerY = canvas.height / 2;
        var radius = Math.min(centerX, centerY) - 20;
        var innerRadius = radius * 0.5;
        
        // Calculer le total
        var total = data.reduce((sum, item) => sum + item.data, 0);
        
        // Effacer le canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        var currentAngle = -Math.PI / 2; // Commencer en haut
        
        data.forEach(function(item) {
            var sliceAngle = (item.data / total) * 2 * Math.PI;
            
            // Dessiner la section
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
            ctx.arc(centerX, centerY, innerRadius, currentAngle + sliceAngle, currentAngle, true);
            ctx.closePath();
            
            ctx.fillStyle = item.color;
            ctx.fill();
            
            // Bordure
            ctx.strokeStyle = '#fff';
            ctx.lineWidth = 2;
            ctx.stroke();
            
            currentAngle += sliceAngle;
        });
        
        // Texte au centre
        ctx.fillStyle = '#333';
        ctx.font = 'bold 12px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(total, centerX, centerY - 3);
        ctx.font = '9px Arial';
        ctx.fillText('Total', centerX, centerY + 10);
    }
    
    // Configuration daterangepicker avec années étendues
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
    
    // Fonction principale de chargement du dashboard
    function loadDashboard(startDate, endDate) {
        console.log("Chargement du dashboard pour la période : " + startDate + " à " + endDate);
        
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
                loadChart(response.chart_data);
                
                // 2. Charger les statistiques des postes
                loadPostesStats(response.postes_stats);
                
                // 3. Charger les statistiques des services
                loadServicesChart(response.services_stats);
                
                // 4. Charger la jauge de présence
                var tauxPresence = response.summary ? (response.summary.taux_presence || 0) : 0;
                console.log("Taux de présence reçu:", tauxPresence + "%");
                loadPresenceGauge(tauxPresence);
                
                // 5. Mettre à jour les statistiques générales
                updateSummaryStats(response.summary);
            },
            error: function(xhr, status, error) {
                console.log('Erreur lors du chargement des données du dashboard:', error);
                // Afficher un message d'erreur à l'utilisateur
                $('#total-personnel').text('Erreur');
                $('#presences-today').text('Erreur');
                $('#duree-moyenne').text('Erreur');
                $('#badges-actifs').text('Erreur');
                $('#gauge-text').text('N/A');
            }
        });
    }
    
    // 1. Fonction graphique principal
    function loadChart(data) {
        if ($("#chart_plot_03").length) {
            // Effacer le message de chargement
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
    
    // 2. Fonction statistiques des postes - CRÉATION DYNAMIQUE
    function loadPostesStats(postesData) {
        var $container = $('#postes-container');
        
        // Vider le conteneur
        $container.empty();
        
        if (postesData && postesData.length > 0) {
            var total = Math.max(...postesData.map(p => parseInt(p.nb_employes)));
            
            postesData.forEach(function(poste, index) {
                var percentage = total > 0 ? (parseInt(poste.nb_employes) / total) * 100 : 0;
                
                // Créer dynamiquement chaque widget
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
            // Afficher un message si pas de données
            $container.html('<p class="text-muted">Aucune donnée de poste disponible</p>');
        }
    }
    
    // 3. Fonction graphique circulaire des services - CRÉATION DYNAMIQUE
    function loadServicesChart(servicesData) {
        var $tableInfo = $('.tile_info');
        var colors = ['blue', 'green', 'purple', 'aero', 'red'];
        var colorCodes = ['#3498db', '#26B99A', '#9b59b6', '#1ABC9C', '#e74c3c']; // Codes couleurs pour le donut
        
        // Vider le tableau
        $tableInfo.empty();
        
        if (servicesData && servicesData.length > 0) {
            var total = servicesData.reduce((sum, s) => sum + parseInt(s.nb_employes), 0);
            
            // Préparer les données pour le graphique donut
            var donutData = [];
            
            servicesData.forEach(function(service, index) {
                if (index < colors.length) {
                    var percentage = total > 0 ? Math.round((parseInt(service.nb_employes) / total) * 100) : 0;
                    
                    // Données pour le donut
                    donutData.push({
                        label: service.service,
                        data: parseInt(service.nb_employes),
                        color: colorCodes[index]
                    });
                    
                    // Créer dynamiquement chaque ligne du tableau
                    var rowHtml = `
                        <tr>
                            <td>
                                <p><i class="fa fa-square ${colors[index]}"></i>${service.service}</p>
                            </td>
                            <td>${percentage}%</td>
                        </tr>
                    `;
                    
                    $tableInfo.append(rowHtml);
                }
            });
            
            // Dessiner le graphique donut avec Chart.js (plus moderne)
            drawDonutChartJS(donutData);
            
        } else {
            // Afficher un message si pas de données
            $tableInfo.html('<tr><td colspan="2" class="text-muted">Aucune donnée de service disponible</td></tr>');
            
            // Vider le canvas ou détruire le graphique existant
            if (window.servicesChart) {
                window.servicesChart.destroy();
                window.servicesChart = null;
            }
        }
    }
    
    // 4. Fonction jauge de présence - Design original du template
    function loadPresenceGauge(percentage) {
        console.log("Chargement de la jauge avec:", percentage + "%");
        
        // S'assurer que percentage est un nombre valide entre 0 et 100
        var validPercentage = parseFloat(percentage) || 0;
        validPercentage = Math.max(0, Math.min(100, validPercentage));
        
        // Mettre à jour le texte affiché (1 décimale)
        $('#gauge-text').text(validPercentage.toFixed(1));
        
        // Dessiner la jauge avec le design original du template
        drawGaugeTemplate(validPercentage);
    }
    
    // Fonction pour dessiner la jauge avec le design original du template
    function drawGaugeTemplate(percentage) {
        var canvas = document.getElementById('chart_gauge_01');
        if (!canvas) {
            console.error("Canvas chart_gauge_01 non trouvé !");
            return;
        }
        
        // Vérifier si Gauge.js est disponible (la vraie bibliothèque du template)
        if (typeof Gauge !== 'undefined') {
            console.log("Utilisation de Gauge.js (bibliothèque originale du template)");
            
            // Détruire la jauge existante
            if (window.gaugeInstance) {
                window.gaugeInstance = null;
            }
            
            // Configuration identique au template original
            var opts = {
                lines: 12,              // Nombre de lignes (graduations)
                angle: 0,               // Angle des lignes
                lineWidth: 0.4,         // Épaisseur des lignes
                pointer: {
                    length: 0.75,       // Longueur de l'aiguille
                    strokeWidth: 0.042, // Épaisseur de l'aiguille
                    color: '#1D212A'    // Couleur noire de l'aiguille
                },
                limitMax: false,
                colorStart: '#1ABC9C',  // Couleur verte du template
                colorStop: '#1ABC9C',   // Couleur verte du template
                strokeColor: '#F0F3F3', // Couleur de fond gris clair
                generateGradient: true
            };
            
            try {
                // Créer la jauge comme dans le template original
                window.gaugeInstance = new Gauge(canvas).setOptions(opts);
                window.gaugeInstance.maxValue = 100;  // Maximum 100% (pas 6000 comme le template)
                window.gaugeInstance.minValue = 0;    // Minimum 0%
                window.gaugeInstance.animationSpeed = 32;
                
                // Utiliser setTextField pour la mise à jour automatique du texte comme le template
                window.gaugeInstance.setTextField(document.getElementById("gauge-text"));
                
                // Définir la valeur (cela mettra à jour automatiquement le texte)
                window.gaugeInstance.set(percentage);
                
                console.log("Jauge créée avec succès, valeur:", percentage + "%");
            } catch (error) {
                console.error('Erreur lors de la création de la jauge Gauge.js:', error);
                // Fallback vers canvas natif
                drawSimpleGaugeTemplate(percentage, canvas);
            }
        } else {
            console.warn('Gauge.js non disponible, utilisation du fallback canvas');
            drawSimpleGaugeTemplate(percentage, canvas);
        }
    }
    
    // Fonction de fallback qui reproduit le design du template
    function drawSimpleGaugeTemplate(percentage, canvas) {
        console.log("Utilisation du fallback canvas pour:", percentage + "%");
        
        var ctx = canvas.getContext('2d');
        var centerX = canvas.width / 2;
        var centerY = canvas.height - 10;
        var radius = Math.min(centerX, centerY) - 15;
        
        // Effacer le canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Dessiner l'arc de fond (gris clair)
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, Math.PI, 2 * Math.PI, false);
        ctx.strokeStyle = '#F0F3F3';
        ctx.lineWidth = 8;
        ctx.lineCap = 'round';
        ctx.stroke();
        
        // Dessiner l'arc de progression (vert) seulement si percentage > 0
        if (percentage > 0) {
            var progressAngle = Math.PI * (percentage / 100);
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, Math.PI, Math.PI + progressAngle, false);
            ctx.strokeStyle = '#1ABC9C';
            ctx.lineWidth = 8;
            ctx.lineCap = 'round';
            ctx.stroke();
        }
        
        // Dessiner les graduations (12 lignes comme dans le template)
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
        
        // Dessiner l'aiguille
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
        
        // Point central
        ctx.beginPath();
        ctx.arc(centerX, centerY, 4, 0, 2 * Math.PI);
        ctx.fillStyle = '#1D212A';
        ctx.fill();
        
        // Mettre à jour manuellement le texte pour le fallback
        $('#gauge-text').text(percentage.toFixed(1));
        
        console.log("Jauge fallback dessinée avec succès:", percentage + "%");
    }
    
    // 6. Fonction statistiques générales
    function updateSummaryStats(summary) {
        $('#total-personnel').text(summary.total_personnel || 0);
        $('#presences-today').text(summary.personnes_presentes_aujourd_hui || 0);
        $('#duree-moyenne').text(summary.duree_moyenne_heures ? Math.round(summary.duree_moyenne_heures * 10) / 10 + 'h' : '0h');
        
        // Mettre à jour les badges actifs
        $.ajax({
            url: 'get_dashboard_stats.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.badge_stats) {
                    $('#badges-actifs').text(response.badge_stats.badges_actifs || 0);
                }
            }
        });
    }
    
    // Chargement initial
    var defaultStart = moment().subtract(29, 'days').format('YYYY-MM-DD');
    var defaultEnd = moment().format('YYYY-MM-DD');
    
    // Vérifications initiales et test de la jauge
    console.log("=== INITIALISATION DASHBOARD ===");
    console.log("Gauge.js disponible:", typeof Gauge !== 'undefined');
    console.log("Canvas gauge disponible:", !!document.getElementById('chart_gauge_01'));
    
    // Test initial de la jauge avec une valeur par défaut
    setTimeout(function() {
        console.log("Test initial de la jauge...");
        loadPresenceGauge(75); // Valeur de test pour vérifier le fonctionnement
    }, 500);
    
    loadDashboard(defaultStart, defaultEnd);
    
    // Écouter les changements du sélecteur de dates
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        loadDashboard(startDate, endDate);
    });
    
    // Actualisation automatique toutes les 5 minutes
    setInterval(function() {
        var currentStart = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var currentEnd = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
        loadDashboard(currentStart, currentEnd);
    }, 300000); // 5 minutes
});
</script>
          
          <!-- Include les scripts nécessaires pour les graphiques -->
          <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/plugins/doughnutlabel.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/daterangepicker/3.1.0/daterangepicker.min.js"></script>
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/daterangepicker/3.1.0/daterangepicker.css" />