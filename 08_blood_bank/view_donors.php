<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Filter & Search Logic
$blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$conditions = [];
if ($blood_group) {
    $conditions[] = "blood_group = '" . $conn->real_escape_string($blood_group) . "'";
}
if ($search) {
    /* 
       SQL LIKE Operator Logic:
       We use % before and after the search term to find partial matches.
       This allows users to find 'London' even if they type 'Lon'.
    */
    $conditions[] = "city LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$where_sql = "";
if (count($conditions) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $conditions);
}

$query = "SELECT * FROM donors $where_sql ORDER BY name ASC";
$res = $conn->query($query);

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Donor Command Registry</h1>
    <a href="add.php" class="btn btn-danger shadow-sm"><i class="fas fa-plus me-2"></i>New Registration</a>
</div>

<!-- Extended Filter Form -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="" method="get" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Filter by City</label>
                <input type="text" name="search" class="form-control" placeholder="City name..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Blood Group</label>
                <select name="blood_group" class="form-select">
                    <option value="">All Groups</option>
                    <?php 
                    $groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                    foreach($groups as $g): ?>
                        <option value="<?php echo $g; ?>" <?php echo ($blood_group == $g) ? 'selected' : ''; ?>><?php echo $g; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-dark w-100"><i class="fas fa-filter me-1"></i> Apply Filter</button>
            </div>
            <div class="col-md-2">
                <a href="view_donors.php" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4">Full Name</th>
                        <th class="text-center">Group</th>
                        <th>Contact Number</th>
                        <th>Location</th>
                        <th>Age/Gender</th>
                        <th>Last Donated</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res->num_rows > 0): ?>
                        <?php while($d = $res->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold"><?php echo htmlspecialchars($d['name']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($d['email']); ?></small>
                                </td>
                                <td class="text-center">
                                    <div class="blood-group-badge mx-auto"><?php echo $d['blood_group']; ?></div>
                                </td>
                                <td class="emergency-phone"><?php echo htmlspecialchars($d['phone']); ?></td>
                                <td><i class="fas fa-map-marker-alt text-danger me-1"></i> <?php echo htmlspecialchars($d['city']); ?></td>
                                <td><?php echo $d['age']; ?> / <?php echo $d['gender']; ?></td>
                                <td><?php echo $d['last_donation_date'] ? date('d M Y', strtotime($d['last_donation_date'])) : '<span class="text-muted">No Record</span>'; ?></td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="edit.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-light border"><i class="fas fa-user-edit"></i></a>
                                        <a href="delete.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Purge this donor from registry?')"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">No matching donors found in registry for current parameters.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
