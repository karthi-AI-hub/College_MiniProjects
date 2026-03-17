<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM donors WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $d = $stmt->get_result()->fetch_assoc();
    if (!$d) die("Donor record not found.");
} else {
    header("Location: view_donors.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $blood_group = $_POST['blood_group'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $city = trim($_POST['city']);
    $last_date = !empty($_POST['last_donation_date']) ? $_POST['last_donation_date'] : NULL;

    if (empty($name) || empty($phone) || empty($city)) {
        $error = "Vital fields missing. Updates postponed.";
    } else {
        $stmt = $conn->prepare("UPDATE donors SET name=?, blood_group=?, gender=?, age=?, phone=?, email=?, city=?, last_donation_date=? WHERE id=?");
        $stmt->bind_param("sssissssi", $name, $blood_group, $gender, $age, $phone, $email, $city, $last_date, $id);
        
        if ($stmt->execute()) {
            $message = "Donor record for $name updated.";
            // Refresh data
            $d = $_POST;
            $d['id'] = $id;
        } else {
            $error = "Update failed: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Modify Donor Record</h1>
    <a href="view_donors.php" class="btn btn-outline-danger shadow-sm"><i class="fas fa-arrow-left me-2"></i>Registry</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4"><?php echo $message; ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger border-0 shadow-sm mb-4"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="" method="post">
            <h5 class="text-danger fw-bold border-bottom pb-2 mb-4">RECORD ID: #<?php echo $id; ?></h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="name" class="form-control form-control-lg" value="<?php echo htmlspecialchars($d['name']); ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Blood Group</label>
                    <select name="blood_group" class="form-select form-select-lg fw-bold text-danger">
                        <?php 
                        $groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                        foreach($groups as $g): ?>
                            <option value="<?php echo $g; ?>" <?php echo ($d['blood_group'] == $g) ? 'selected' : ''; ?>><?php echo $g; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Age</label>
                    <input type="number" name="age" class="form-control form-control-lg" value="<?php echo $d['age']; ?>" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="Male" <?php echo ($d['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($d['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($d['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">City Location</label>
                    <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($d['city']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Last Donation Recorded</label>
                    <input type="date" name="last_donation_date" class="form-control" value="<?php echo $d['last_donation_date']; ?>">
                </div>
            </div>

            <h5 class="text-danger fw-bold border-bottom pb-2 mt-4 mb-4">CONTACT CHANNELS</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Emergency Phone</label>
                    <input type="text" name="phone" class="form-control form-control-lg emergency-phone" value="<?php echo htmlspecialchars($d['phone']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" value="<?php echo htmlspecialchars($d['email']); ?>">
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-danger btn-lg px-5 shadow">SAVE CLINICAL UPDATES</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
