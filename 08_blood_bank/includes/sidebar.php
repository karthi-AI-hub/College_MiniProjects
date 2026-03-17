        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="fas fa-hand-holding-medical me-2"></i>BloodLife
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i> Command Center
                </a>
                <a href="view_donors.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'view_donors.php' || basename($_SERVER['PHP_SELF']) == 'edit.php') ? 'active' : ''; ?>">
                    <i class="fas fa-users-cog"></i> Donor Registry
                </a>
                <a href="add.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'add.php') ? 'active' : ''; ?>">
                    <i class="fas fa-user-plus"></i> Register Donor
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-sign-out-alt"></i> Logout Engine
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-danger text-white" id="menu-toggle"><i class="fas fa-bars"></i></button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <span class="nav-link fw-bold text-danger">
                                    <i class="fas fa-clinic-medical me-1"></i> Hospital Admin Node
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid py-4 px-4">
