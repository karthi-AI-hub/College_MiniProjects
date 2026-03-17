        <!-- Sidebar -->
        <div class="bg-success border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-white">Farm Manager <i class="fas fa-leaf"></i></div>
            <div class="list-group list-group-flush">
                <a href="view_animals.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'view_animals.php' || basename($_SERVER['PHP_SELF']) == 'add.php' || basename($_SERVER['PHP_SELF']) == 'edit.php') ? 'active' : ''; ?> bg-success text-white">
                    <i class="fas fa-paw me-2"></i> Livestock Registry
                </a>
                <!-- Future features: <a href="#" class="list-group-item list-group-item-action bg-success text-white"><i class="fas fa-fw fa-calendar-alt me-2"></i>Schedule</a> -->
                <a href="logout.php" class="list-group-item list-group-item-action bg-success text-white"><i class="fas fa-fw fa-sign-out-alt me-2"></i>Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-4 shadow-sm">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li><span class="navbar-text text-success fw-bold">Welcome, Farm Admin</span></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid">
