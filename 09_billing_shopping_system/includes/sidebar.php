        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="fas fa-shopping-bag me-1"></i>RetailPro
            </div>
            <div class="list-group list-group-flush mt-3">
                <a href="index.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="view_products.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'view_products.php' || basename($_SERVER['PHP_SELF']) == 'add.php' || basename($_SERVER['PHP_SELF']) == 'edit.php') ? 'active' : ''; ?>">
                    <i class="fas fa-boxes"></i> Inventory
                </a>
                <a href="create_bill.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'create_bill.php') ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice-dollar"></i> New Bill
                </a>
                <a href="view_bills.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'view_bills.php') ? 'active' : ''; ?>">
                    <i class="fas fa-history"></i> Bill History
                </a>
                <div class="mt-auto px-3 py-4">
                    <a href="logout.php" class="btn btn-outline-teal w-100 btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> End Session
                    </a>
                </div>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-teal rounded-circle" id="menu-toggle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <span class="nav-link fw-semibold text-dark">
                                    <i class="fas fa-user-circle me-1"></i> Checkout Manager
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid py-4 px-4">
