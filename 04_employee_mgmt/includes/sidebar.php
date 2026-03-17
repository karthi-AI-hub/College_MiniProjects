        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-white fw-bold py-4 text-center">
                <i class="fas fa-building fa-2x mb-2 text-primary"></i><br>
                HR Portal
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action bg-dark text-white-50 <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active text-white' : ''; ?>">
                    <i class="fas fa-fw fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="view_employees.php" class="list-group-item list-group-item-action bg-dark text-white-50 <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['view_employees.php', 'add.php', 'edit.php'])) ? 'active text-white' : ''; ?>">
                    <i class="fas fa-fw fa-users me-2"></i>Employees
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action bg-dark text-white-50">
                    <i class="fas fa-fw fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom mb-4 shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-outline-light" id="menu-toggle"><i class="fas fa-bars"></i></button>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <span class="nav-link text-white fw-bold">Welcome, Administrator</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid px-4">
