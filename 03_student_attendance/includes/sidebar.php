        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="no-print">
            <div class="sidebar-heading px-4">Attendance System</div>
            <div class="list-group list-group-flush px-3 mt-3">
                <a href="index.php" class="list-group-item list-group-item-action rounded-3 mb-1 <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-th-large me-2"></i>Dashboard
                </a>
                <a href="mark_attendance.php" class="list-group-item list-group-item-action rounded-3 mb-1 <?php echo (basename($_SERVER['PHP_SELF']) == 'mark_attendance.php') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check me-2"></i> Mark Attendance
                </a>
                <a href="view_students.php" class="list-group-item list-group-item-action rounded-3 mb-1 <?php echo (basename($_SERVER['PHP_SELF']) == 'view_students.php') ? 'active' : ''; ?>">
                    <i class="fas fa-users me-2"></i> Student Registry
                </a>
                <a href="view_history.php" class="list-group-item list-group-item-action rounded-3 mb-1 <?php echo (basename($_SERVER['PHP_SELF']) == 'view_history.php') ? 'active' : ''; ?>">
                    <i class="fas fa-clock-rotate-left me-2"></i> View History
                </a>
                <div class="mt-4 pt-4 border-top">
                    <a href="logout.php" class="list-group-item list-group-item-action rounded-3 text-danger">
                        <i class="fas fa-power-off me-2"></i>Sign Out
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg no-print">
                <div class="container-fluid px-1">
                    <span class="navbar-brand outfit fw-700 h5 mb-0 tracking-widest text-muted">Attendance System</span>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="text-end me-3 d-none d-md-block">
                            <div class="small fw-600 text-dark">Staff Admin</div>
                            <div class="small text-muted" style="font-size: 10px;">Institution</div>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Staff&background=4f46e5&color=fff" width="32" height="32" class="rounded-circle border">
                    </div>
                </div>
            </nav>
            <div class="container-fluid">
