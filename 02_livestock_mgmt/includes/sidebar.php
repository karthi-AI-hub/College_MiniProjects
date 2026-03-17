        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" onclick="toggleSidebar()" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.4); z-index: 1045; display: none;"></div>
        <script>
            // Sidebar overlay control for mobile
            document.addEventListener('DOMContentLoaded', function() {
                const overlay = document.getElementById('sidebar-overlay');
                const wrapper = document.getElementById('wrapper');
                
                // Simple logic for overlay visibility
                const observer = new MutationObserver(function() {
                    if (wrapper.classList.contains('toggled') && window.innerWidth < 992) {
                        overlay.style.display = 'block';
                    } else {
                        overlay.style.display = 'none';
                    }
                });
                observer.observe(wrapper, { attributes: true, attributeFilter: ['class'] });
            });
        </script>

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <span>Livestock Management</span>
                <button class="btn btn-link text-dark d-lg-none p-0" onclick="toggleSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="list-group list-group-flush mt-3">
                <a href="index.php" class="list-group-item list-group-item-action <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i>Dashboard
                </a>
                <a href="view_animals.php" class="list-group-item list-group-item-action <?php echo (strpos(basename($_SERVER['PHP_SELF']), 'animal') !== false || basename($_SERVER['PHP_SELF']) == 'add.php' || basename($_SERVER['PHP_SELF']) == 'edit.php') ? 'active' : ''; ?>">
                    <i class="fas fa-paw"></i>Livestock Registry
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action mt-auto mb-4 text-danger-hover">
                    <i class="fas fa-power-off"></i>Logout
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <span class="navbar-brand d-none d-sm-block">Livestock Management System</span>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name=Farm&background=4f46e5&color=fff&bold=true" alt="admin" width="32" height="32" class="rounded-circle me-2">
                                <span class="small fw-600 d-none d-md-inline">Farm Admin</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="container-fluid py-4">
