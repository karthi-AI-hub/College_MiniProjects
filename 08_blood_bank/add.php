<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

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
        $error = "Name, Phone, and City are critical for emergency operations.";
    } else {
        $stmt = $conn->prepare("INSERT INTO donors (name, blood_group, gender, age, phone, email, city, last_donation_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssissss", $name, $blood_group, $gender, $age, $phone, $email, $city, $last_date);
        
        if ($stmt->execute()) {
            $message = "Donor $name registered successfully.";
        } else {
            $error = "Database Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Register Lifesaver</h1>
    <a href="view_donors.php" class="btn btn-outline-danger shadow-sm"><i class="fas fa-arrow-left me-2"></i>Back to Registry</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="fas fa-check-circle me-2"></i> <?php echo $message; ?>
    </div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="" method="post">
            <h5 class="text-danger fw-bold border-bottom pb-2 mb-4">PERSONAL & CLINICAL DATA</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-lg" placeholder="Donor Full Name" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Blood Group <span class="text-danger">*</span></label>
                    <select name="blood_group" class="form-select form-select-lg fw-bold text-danger" required>
                        <option value="">Select...</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Age</label>
                    <input type="number" name="age" class="form-control form-control-lg" min="18" max="65" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">City <span class="text-danger">*</span></label>
                    <input type="text" name="city" class="form-control" required placeholder="Current Location">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Last Donation Date</label>
                    <input type="date" name="last_donation_date" class="form-control">
                    <small class="text-muted">Leave blank if this is the first donation.</small>
                </div>
            </div>

            <h5 class="text-danger fw-bold border-bottom pb-2 mt-4 mb-4">EMERGENCY CONTACT</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control form-control-lg emergency-phone" placeholder="+00 0000 0000" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="email@address.com">
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-danger btn-lg px-5 shadow">COMMIT REGISTRATION</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
