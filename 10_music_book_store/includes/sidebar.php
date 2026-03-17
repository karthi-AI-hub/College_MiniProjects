        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="fas fa-infinite me-1 text-gold"></i>Infinity<span class="text-gold">Media</span>
            </div>
            <div class="list-group list-group-flush mt-4">
                <a href="index.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-gem"></i> Showcase Dashboard
                </a>
                <a href="view_catalog.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'view_catalog.php' || basename($_SERVER['PHP_SELF']) == 'edit.php') ? 'active' : ''; ?>">
                    <i class="fas fa-layer-group"></i> Media Catalog
                </a>
                <a href="add.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'add.php') ? 'active' : ''; ?>">
                    <i class="fas fa-plus-square"></i> Curate Content
                </a>
                <a href="store_front.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'store_front.php') ? 'active' : ''; ?>">
                    <i class="fas fa-store"></i> Buyer Storefront
                </a>
                
                <div class="mt-auto py-5 px-3">
                    <a href="logout.php" class="btn btn-outline-gold w-100 fw-bold">
                        <i class="fas fa-power-off me-2"></i>Sign Out
                    </a>
                </div>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark bg-purple border-bottom border-gold shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-gold py-1" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <span class="nav-link fw-bold text-gold">
                                    <i class="fas fa-shield-alt me-1"></i> Admin Command Node
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid py-4 px-4">
