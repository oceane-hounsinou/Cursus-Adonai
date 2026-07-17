<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs - ISM ADONAÏ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #7a0000; color: #9b0000; }
        .container { display: flex; min-height: 100vh; }

        /* --- SIDEBAR (Identique aux autres pages) --- */
        .sidebar { 
            width: 260px; 
            background: linear-gradient(180deg, #7a0000 0%, #7a0000 50%, #9b0000 100%); 
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
        
        /* Onglet Utilisateurs Actif */
        .menu li.active { background: #1e3a8a; position: relative; }
        .menu li.active a { color: white; font-weight: 500; }
        .menu li.active::after { content: ''; position: absolute; right: -12px; top: 25%; width: 4px; height: 50%; background: #3b82f6; border-radius: 4px 0 0 4px; }

        /* --- MAIN CONTENT --- */
        .main { flex: 1; padding: 35px; background: #f8fafc; overflow-y: auto; }
        
        /* Header */
        .content-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .content-header h2 { font-size: 22px; color: #0d2342; font-weight: 700; margin-bottom: 4px; }
        .breadcrumb { font-size: 13px; color: #94a3b8; font-weight: 500; }
        .breadcrumb a { color: #3b82f6; text-decoration: none; }
        .breadcrumb span { margin: 0 6px; color: #cbd5e1; }

        /* Bouton Ajouter Utilisateur */
        .btn-add { background: #0d2342; color: white; border: none; padding: 12px 20px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 10px; text-transform: uppercase; transition: background 0.2s; }
        .btn-add:hover { background: #1e3a61; }

        /* --- FILTRES --- */
        .search-filters { display: flex; gap: 12px; margin-bottom: 25px; align-items: center; }
        .search-filters input, .search-filters select { padding: 11px 16px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 13.5px; color: #334155; background: white; outline: none; }
        .search-filters input[type="text"] { flex: 1; max-width: 300px; }
        .btn-search { background: #3b82f6; color: white; border: none; padding: 11px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; }

        /* --- TABLEAU --- */
        .panel { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding-bottom: 18px; color: #475569; font-size: 13px; font-weight: 600; border-bottom: 2px solid #edf2f7; text-transform: uppercase; }
        td { padding: 16px 10px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; vertical-align: middle; }
        
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #64748b; font-size: 12px; }
        .user-name { font-weight: 600; color: #0d2342; }

        /* Rôles et Statuts */
        .badge-role { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; background: #f1f5f9; color: #475569; }
        .status-pill { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .status-active { background: #d1fae5; color: #059669; }
        .status-inactive { background: #fee2e2; color: #dc2626; }

        /* Actions */
        .actions { display: flex; gap: 8px; }
        .btn-action { width: 32px; height: 32px; border-radius: 6px; display: flex; justify-content: center; align-items: center; border: 1px solid #e2e8f0; background: white; cursor: pointer; transition: 0.2s; font-size: 13px; }
        .btn-edit { color: #3b82f6; }
        .btn-edit:hover { background: #eff6ff; border-color: #bfdbfe; }
        .btn-delete { color: #ef4444; }
        .btn-delete:hover { background: #fef2f2; border-color: #fca5a5; }
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
            <li><a href="#"><i class="fas fa-user-graduate"></i> Étudiants</a></li>
            <li><a href="#"><i class="far fa-file-alt"></i> Demandes</a></li>
            <li><a href="#"><i class="far fa-folder"></i> Documents</a></li>
            <li class="active"><a href="#"><i class="fas fa-users-cog"></i> Utilisateurs</a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i> Rapports</a></li>
            <li style="margin-top: 30px;"><a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main">
        
        <div class="content-header">
            <div>
                <h2>Gestion des utilisateurs</h2>
                <div class="breadcrumb">
                    <a href="#">Accueil</a> <span>/</span> Utilisateurs
                </div>
            </div>
            <button class="btn-add">
                <i class="fas fa-user-plus"></i> Ajouter utilisateur
            </button>
        </div>

        <div class="search-filters">
            <input type="text" placeholder="Rechercher par nom ou email...">
            <select>
                <option value="">Tous les rôles</option>
                <option value="admin">Administrateur</option>
                <option value="scolarite">Scolarité</option>
                <option value="compta">Comptabilité</option>
            </select>
            <select>
                <option value="">Tous les statuts</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
            </select>
            <button class="btn-search">Filtrer</button>
        </div>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Dernière connexion</th>
                        <th>Statut</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">MK</div>
                                <span class="user-name">Michel KASSA</span>
                            </div>
                        </td>
                        <td>m.kassa@ism.cd</td>
                        <td><span class="badge-role">Scolarité</span></td>
                        <td style="color: #94a3b8;">Il y a 2 heures</td>
                        <td><span class="status-pill status-active">Actif</span></td>
                        <td>
                            <div class="actions">
                                <button class="btn-action btn-edit" title="Modifier"><i class="fas fa-pen"></i></button>
                                <button class="btn-action btn-delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar" style="background:#fef3c7; color:#d97706;">AL</div>
                                <span class="user-name">Alice LUVUE</span>
                            </div>
                        </td>
                        <td>a.luvue@ism.cd</td>
                        <td><span class="badge-role">Comptabilité</span></td>
                        <td style="color: #94a3b8;">Hier, 14:20</td>
                        <td><span class="status-pill status-active">Actif</span></td>
                        <td>
                            <div class="actions">
                                <button class="btn-action btn-edit" title="Modifier"><i class="fas fa-pen"></i></button>
                                <button class="btn-action btn-delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar" style="background:#e0e7ff; color:#4338ca;">JT</div>
                                <span class="user-name">Justin TSHIA</span>
                            </div>
                        </td>
                        <td>j.tshia@ism.cd</td>
                        <td><span class="badge-role">Secrétariat</span></td>
                        <td style="color: #94a3b8;">12/05/2024</td>
                        <td><span class="status-pill status-inactive">Inactif</span></td>
                        <td>
                            <div class="actions">
                                <button class="btn-action btn-edit" title="Modifier"><i class="fas fa-pen"></i></button>
                                <button class="btn-action btn-delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>