        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">Event Hub</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-fw fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="view_events.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'view_events.php') ? 'active' : ''; ?>">
                    <i class="fas fa-fw fa-calendar-alt me-2"></i> Event Registry
                </a>
                <a href="add_event.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'add_event.php') ? 'active' : ''; ?>">
                    <i class="fas fa-fw fa-plus-circle me-2"></i> Create Event
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action mt-auto border-top border-secondary">
                    <i class="fas fa-fw fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark mb-4">
                <div class="container-fluid">
                    <button class="btn btn-magenta me-3" id="menu-toggle"><i class="fas fa-bars"></i></button>
                    <span class="navbar-brand d-none d-lg-block">Event Management</span>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <span class="text-muted me-3">Welcome, <strong class="text-white">Admin</strong></span>
                        <div class="flex-shrink-0 dropdown">
                            <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name=Admin&background=f97316&color=fff" alt="mdo" width="32" height="32" class="rounded-circle">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="container-fluid py-4 px-4">
