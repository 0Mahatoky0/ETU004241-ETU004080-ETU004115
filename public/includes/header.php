<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Gestion des Besoins et Dons</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #2c3e50;
            --primary-hover: #34495e;
            --accent-color: #3498db;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .card-header {
            font-weight: 600;
        }
        .badge {
            font-size: 0.8em;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .table th {
            border-top: none;
            font-weight: 600;
        }
        .alert {
            border: none;
            border-radius: 0.5rem;
        }
        .nav-tabs .nav-link {
            border: 1px solid #dee2e6;
            border-bottom: none;
        }
        .nav-tabs .nav-link.active {
            background-color: #fff;
            border-bottom: 1px solid #fff;
        }

        /* Sidebar moderne */
        .app-sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #1a252f 100%);
            overflow-y: auto;
            z-index: 1030;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .app-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        /* Brand */
        .sidebar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }

        .sidebar-brand:hover {
            color: var(--accent-color);
        }

        .sidebar-brand i {
            font-size: 1.8rem;
            margin-right: 0.75rem;
        }

        /* Navigation links */
        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .sidebar-link {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            position: relative;
        }

        .sidebar-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: var(--accent-color);
        }

        .sidebar-link.active {
            background-color: rgba(52, 152, 219, 0.2);
            color: #fff;
            border-left-color: var(--accent-color);
        }

        .sidebar-link i {
            width: 24px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        /* Dropdown */
        .sidebar-dropdown {
            position: relative;
        }

        .dropdown-toggle-sidebar {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dropdown-toggle-sidebar .arrow {
            transition: transform 0.3s ease;
            font-size: 0.8rem;
        }

        .dropdown-toggle-sidebar.active .arrow {
            transform: rotate(180deg);
        }

        .dropdown-menu-sidebar {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background-color: rgba(0,0,0,0.2);
        }

        .dropdown-menu-sidebar.show {
            max-height: 300px;
        }

        .dropdown-item-sidebar {
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.65rem 1.25rem 0.65rem 3.5rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .dropdown-item-sidebar:hover {
            background-color: rgba(255,255,255,0.05);
            color: #fff;
            padding-left: 3.75rem;
        }

        .dropdown-item-sidebar.active {
            background-color: rgba(52, 152, 219, 0.15);
            color: #fff;
        }

        .dropdown-item-sidebar i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 0.9rem;
        }

        /* Scrollbar personnalisé */
        .app-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .app-sidebar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
        }

        .app-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        .app-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Top bar */
        .top-bar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            color: #6c757d;
        }

        .user-info i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="app-sidebar">
        <a class="sidebar-brand" href="/dashboard">
            <i class="fas fa-shield-alt"></i>
            <span>BNGRC</span>
        </a>

        <nav class="sidebar-nav">
            <ul class="list-unstyled">
                <!-- Tableau de bord -->
                <li class="nav-item">
                    <a href="/dashboard" class="sidebar-link" data-page="dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Tableau de Bord</span>
                    </a>
                </li>

                <!-- Besoins avec dropdown -->
                <li class="nav-item sidebar-dropdown">
                    <a class="sidebar-link dropdown-toggle-sidebar" onclick="toggleDropdown('besoinsDropdown')">
                        <div style="display: flex; align-items: center;">
                            <i class="fas fa-hands-helping"></i>
                            <span>Besoins</span>
                        </div>
                        <i class="fas fa-chevron-down arrow"></i>
                    </a>
                    <div class="dropdown-menu-sidebar" id="besoinsDropdown">
                        <a href="/besoins/saisie" class="dropdown-item-sidebar" data-page="besoins-saisie">
                            <i class="fas fa-plus-circle"></i>
                            <span>Saisir un besoin</span>
                        </a>
                        <a href="/besoins/liste" class="dropdown-item-sidebar" data-page="besoins-liste">
                            <i class="fas fa-list-ul"></i>
                            <span>Liste des besoins</span>
                        </a>
                    </div>
                </li>

                <!-- Dons avec dropdown -->
                <li class="nav-item sidebar-dropdown">
                    <a class="sidebar-link dropdown-toggle-sidebar" onclick="toggleDropdown('donsDropdown')">
                        <div style="display: flex; align-items: center;">
                            <i class="fas fa-gift"></i>
                            <span>Dons</span>
                        </div>
                        <i class="fas fa-chevron-down arrow"></i>
                    </a>
                    <div class="dropdown-menu-sidebar" id="donsDropdown">
                        <a href="/dons/saisie" class="dropdown-item-sidebar" data-page="dons-saisie">
                            <i class="fas fa-plus-circle"></i>
                            <span>Saisir un don</span>
                        </a>
                        <a href="#" class="dropdown-item-sidebar" data-page="dons-achat">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Acheter avec dons</span>
                        </a>
                        <a href="/dons/liste" class="dropdown-item-sidebar" data-page="dons-liste">
                            <i class="fas fa-list-ul"></i>
                            <span>Liste des dons</span>
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="app-main">

        <!-- Messages flash -->
        <?php if (isset($_GET['success'])): ?>
            <div class="container-fluid px-4">
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> Opération réussie!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Contenu principal -->
        <main class="px-4">
            <!-- Votre contenu ici -->

    <script>
        // Fonction pour détecter la page active
        function setActivePage() {
            const currentPath = window.location.pathname;
            
            // Retire toutes les classes active
            document.querySelectorAll('.sidebar-link, .dropdown-item-sidebar').forEach(link => {
                link.classList.remove('active');
            });
            
            // Trouve et active le lien correspondant
            let activeLink = null;
            document.querySelectorAll('.sidebar-link[href], .dropdown-item-sidebar[href]').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                    activeLink = link;
                }
            });
            
            // Si un item de dropdown est actif, ouvre le dropdown parent
            if (activeLink && activeLink.classList.contains('dropdown-item-sidebar')) {
                const dropdown = activeLink.closest('.dropdown-menu-sidebar');
                const toggle = dropdown.previousElementSibling;
                dropdown.classList.add('show');
                toggle.classList.add('active');
            }
            
            // Si aucune page n'est active, active le dashboard par défaut
            if (!activeLink) {
                const dashboardLink = document.querySelector('[data-page="dashboard"]');
                if (dashboardLink) {
                    dashboardLink.classList.add('active');
                }
            }
        }

        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const toggle = dropdown.previousElementSibling;
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu-sidebar').forEach(menu => {
                if (menu.id !== dropdownId) {
                    menu.classList.remove('show');
                    menu.previousElementSibling.classList.remove('active');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('show');
            toggle.classList.toggle('active');
        }

        // Active la page au chargement
        document.addEventListener('DOMContentLoaded', setActivePage);
    </script>
