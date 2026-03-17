        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="fas fa-truck-moving me-2"></i>FleetManager
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="view_vehicles.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'view_vehicles.php' || basename($_SERVER['PHP_SELF']) == 'add.php' || basename($_SERVER['PHP_SELF']) == 'edit.php') ? 'active' : ''; ?>">
                    <i class="fas fa-bus"></i> Fleet Registry
                </a>
                <a href="routes.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'routes.php') ? 'active' : ''; ?>">
                    <i class="fas fa-route"></i> Route Directory
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark-grey border-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-yellow" id="menu-toggle"><i class="fas fa-bars"></i></button>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <span class="nav-link text-yellow fw-bold">
                                    <i class="fas fa-user-shield me-1"></i> Fleet Admin
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid py-4 px-4">
