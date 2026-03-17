        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading" style="font-family: 'Playfair Display', serif;">
                <i class="fas fa-book-reader me-2"></i>ScholarLib
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="view_books.php" class="list-group-item list-group-item-action bg-navy text-white <?php echo (basename($_SERVER['PHP_SELF']) == 'view_books.php' || basename($_SERVER['PHP_SELF']) == 'add.php' || basename($_SERVER['PHP_SELF']) == 'edit.php') ? 'active border-start border-warning border-4' : ''; ?>">
                    <i class="fas fa-book me-2"></i> Inventory Registry
                </a>
                <a href="issue_book.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'issue_book.php') ? 'active' : ''; ?>">
                    <i class="fas fa-hand-holding-heart"></i> Issue Book
                </a>
                <a href="transactions.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'transactions.php') ? 'active' : ''; ?>">
                    <i class="fas fa-exchange-alt"></i> Transitions
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg border-bottom shadow-sm" style="background-color: #fff; border-bottom: 2px solid #0a2342 !important;">
                <div class="container-fluid">
                    <button class="btn btn-navy" id="menu-toggle"><i class="fas fa-bars"></i></button>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <span class="nav-link text-navy fw-bold">
                                    <i class="fas fa-user-graduate me-1"></i> Librarian Portal
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid py-4 px-4">
