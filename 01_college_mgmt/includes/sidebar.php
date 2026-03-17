        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-white">College Admin</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-fw fa-tachometer-alt me-2"></i>Dashboard</a> <!-- Added icon -->
                <a href="view_students.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'view_students.php' || basename($_SERVER['PHP_SELF']) == 'add.php' || basename($_SERVER['PHP_SELF']) == 'edit.php') ? 'active' : ''; ?> bg-dark text-white">
                    <i class="fas fa-fw fa-user-graduate me-2"></i> Students
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-fw fa-sign-out-alt me-2"></i>Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        
        <!-- Page Content -->
        <!-- Start of container-fluid for main content, to be closed in footer.php -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-4 shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="menu-toggle"><i class="fas fa-bars"></i></button>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li><span class="navbar-text">Welcome, Admin</span></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid">
