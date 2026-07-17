<?php
// 1. Protection de la page

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports / Statistiques - ISM ADONAÏ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #9b0000; color: #334155; }
        .container { display: flex; min-height: 100vh; }

        /* --- SIDEBAR --- */
        .sidebar { 
            width: 260px; 
            background: linear-gradient(180deg, #9b0000 0%, #9b0000 50%, #03142c 100%); 
            color: #cbd5e1; 
            flex-shrink: 0;
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
        }
        .logo { padding: 25px 20px; display: flex; align-items: center; gap: 12px; }
        .logo h2 { font-size: 16px; color: white; font-weight: 700; letter-spacing: 0.5px; }
        
        .profile { padding: 15px 20px 25px 20px; display: flex; align-items: center; gap: 15px; }
        .profile img { width: 52px; height: 52px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.2); object-fit: cover; }
        .profile h4 { color: white; font-size: 14px; font-weight: 600; margin-bottom: 2px; }
        .profile p { font-size: 11px; color: #64748b; }

        .menu { list-style: none; padding: 0 12px; }
        .menu li { margin-bottom: 4px; border-radius: 8px; transition: all 0.2s ease; }
        .menu li a { color: #94a3b8; text-decoration: none; display: flex; align-items: center; gap: 12px; font-size: 13.5px; padding: 12px 15px; width: 100%; }
        .menu li a i { width: 18px; font-size: 15px; text-align: center; }
        .menu li:hover { background: rgba(255, 255, 255, 0.04); }
        .menu li:hover a { color: white; }
        
        /* Onglet Rapports Actif */
        .menu li.active { background: #1e3a8a; position: relative; }
        .menu li.active a { color: white; font-weight: 500; }
        .menu li.active::after { content: ''; position: absolute; right: -12px; top: 25%; width: 4px; height: 50%; background: #3b82f6; border-radius: 4px 0 0 4px; }

        /* --- MAIN CONTENT --- */
        .main { flex: 1; padding: 35px; background: #f8fafc; overflow-y: auto; }
        
        /* Header */
        .content-header { margin-bottom: 25px; }
        .content-header h2 { font-size: 22px; color: #0d2342; font-weight: 700; margin-bottom: 4px; }
        
        /* Fil d'ariane */
        .breadcrumb { font-size: 13px; color: #94a3b8; font-weight: 500; }
        .breadcrumb a { color: #3b82f6; text-decoration: none; }
        .breadcrumb span { margin: 0 6px; color: #cbd5e1; }

        /* --- BARRE DE FILTRES --- */
        .filters-bar { display: flex; gap: 12px; margin-bottom: 25px; align-items: center; flex-wrap: wrap; }
        .filters-bar select, .filters-bar .date-box { background: white; border: 1px solid #e2e8f0; padding: 11px 16px; border-radius: 8px; font-size: 13.5px; color: #334155; outline: none; }
        .filters-bar select { width: 160px; cursor: pointer; }
        
        .date-box { display: flex; align-items: center; padding-left: 14px; }
        .date-box label { font-size: 13px; color: #94a3b8; margin-right: 8px; font-weight: 500; }
        .date-box input[type="date"] { border: none; font-size: 13.5px; color: #334155; background: transparent; outline: none; cursor: pointer; }
        
        .btn-generate { background: #4f46e5; color: white; border: none; padding: 11px 28px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; letter-spacing: 0.5px; text-transform: uppercase; transition: background 0.2s; }
        .btn-generate:hover { background: #4338ca; }

        /* --- VISUALS / CHARTS GRID --- */
        .charts-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 20px; }
        .chart-card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.01), 0 2px 4px -1px rgba(0,0,0,0.01); border: 1px solid #e2e8f0; }
        .chart-card h3 { font-size: 15px; color: #0d2342; font-weight: 700; margin-bottom: 25px; }
        
        /* Conteneurs internes pour les graphiques */
        .chart-container-bar { height: 240px; position: relative; width: 100%; }
        .chart-container-donut { display: flex; align-items: center; justify-content: space-between; height: 240px; }
        .donut-box { width: 200px; height: 200px; position: relative; }

        /* Légende personnalisée pour le Donut */
        .custom-legend { list-style: none; display: flex; flex-direction: column; gap: 10px; min-width: 160px; }
        .custom-legend li { display: flex; align-items: center; justify-content: space-between; font-size: 13px; color: #475569; }
        .legend-label { display: flex; align-items: center; gap: 10px; }
        .legend-color { width: 10px; height: 10px; border-radius: 2px; display: inline-block; }
        .legend-pct { font-weight: 600; color: #1e293b; margin-left: 5px; }
    </style>
</head>
<body>

<div class="container">
    
    <div class="sidebar">
        <div class="logo">
            <div style="width:30px; height:30px; background:#f59e0b; border-radius:6px; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:12px;">ISM</div>
            <h2>ISM ADONAÏ</h2>
        </div>
        
        <div class="profile">
            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=256&auto=format&fit=crop" alt="Admin">
            <div>
                <h4>Admin ISM</h4>
                <p>Administrateur</p>
            </div>
        </div>
        
        <ul class="menu">
            <li><a href="#"><i class="fas fa-th-large"></i> Tableau de bord</a></li>
            <li><a href="#"><i class="fas fa-user-gradient"></i> Étudiants</a></li>
            <li><a href="#"><i class="far fa-file-alt"></i> Demandes</a></li>
            <li><a href="#"><i class="far fa-folder"></i> Documents</a></li>
            <li><a href="#"><i class="fas fa-users-cog"></i> Utilisateurs</a></li>
            <li class="active"><a href="#"><i class="fas fa-chart-bar"></i> Rapports</a></li>
            <li style="margin-top: 30px;"><a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main">
        
        <div class="content-header">
            <h2>Rapports / Statistiques</h2>
            <div class="breadcrumb">
                <a href="#">Accueil</a> <span>/</span> Rapports
            </div>
        </div>

        <div class="filters-bar">
            <select>
                <option>Demandes</option>
            </select>
            
            <select>
                <option>Tous les types</option>
            </select>
            
            <div class="date-box">
                <label>Du</label>
                <input type="date" value="2024-05-01">
            </div>
            
            <div class="date-box">
                <label>Au</label>
                <input type="date" value="2024-05-31">
            </div>
            
            <button class="btn-generate">GÉNÉRER</button>
        </div>

        <div class="charts-grid">
            
            <div class="chart-card">
                <h3>Demandes par statut</h3>
                <div class="chart-container-bar">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <h3>Demandes par type</h3>
                <div class="chart-container-donut">
                    <div class="donut-box">
                        <canvas id="donutChart"></canvas>
                    </div>
                    
                    <ul class="custom-legend">
                        <li>
                            <div class="legend-label">
                                <span class="legend-color" style="background: #3b82f6;"></span>
                                <span>Attestation</span>
                            </div>
                            <span class="legend-pct">(40%)</span>
                        </li>
                        <li>
                            <div class="legend-label">
                                <span class="legend-color" style="background: #a855f7;"></span>
                                <span>Relevé de notes</span>
                            </div>
                            <span class="legend-pct">(30%)</span>
                        </li>
                        <li>
                            <div class="legend-label">
                                <span class="legend-color" style="background: #14b8a6;"></span>
                                <span>Certificat</span>
                            </div>
                            <span class="legend-pct">(10%)</span>
                        </li>
                        <li>
                            <div class="legend-label">
                                <span class="legend-color" style="background: #f59e0b;"></span>
                                <span>Stage</span>
                            </div>
                            <span class="legend-pct">(10%)</span>
                        </li>
                        <li>
                            <div class="legend-label">
                                <span class="legend-color" style="background: #ef4444;"></span>
                                <span>Autres</span>
                            </div>
                            <span class="legend-pct">(10%)</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Configuration du graphique en barres (Demandes par statut)
    const ctxBar = document.getElementById('barChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['En attente', 'Validées', 'Rejetées'],
            datasets: [{
                data: [28, 42, 8],
                backgroundColor: ['#f59e0b', '#22c55e', '#ef4444'],
                borderRadius: 4,
                barThickness: 35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 50,
                    ticks: { stepSize: 10, color: '#94a3b8', font: { size: 11 } },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    ticks: { color: '#475569', font: { size: 12, weight: '500' } },
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Configuration du graphique en anneau (Demandes par type)
    const ctxDonut = document.getElementById('donutChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Attestation', 'Relevé de notes', 'Certificat', 'Stage', 'Autres'],
            datasets: [{
                data: [40, 30, 10, 10, 10],
                backgroundColor: ['#3b82f6', '#a855f7', '#14b8a6', '#f59e0b', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            cutout: '72%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false } // Géré par notre liste HTML customisée
            }
        }
    });
</script>
</body>
</html>