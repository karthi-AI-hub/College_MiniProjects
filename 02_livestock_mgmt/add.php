<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Fetch Categories for Dropdown
$categories = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tag_id = trim($_POST['tag_id']);
    $category_id = $_POST['category_id'];
    $breed = trim($_POST['breed']);
    $age = trim($_POST['age']);
    $weight = trim($_POST['weight']);
    $health_status = $_POST['health_status'];
    $last_checkup_date = $_POST['last_checkup_date'];

    if (empty($tag_id) || empty($category_id) || empty($health_status)) {
        $error = "Tag ID, Category, and Health Status are mandatory.";
    } else {
        // Check Duplicate Tag ID
        $check = $conn->prepare("SELECT id FROM livestock WHERE tag_id = ?");
        $check->bind_param("s", $tag_id);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "Animal with this Tag ID already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO livestock (tag_id, category_id, breed, age, weight, health_status, last_checkup_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sisssss", $tag_id, $category_id, $breed, $age, $weight, $health_status, $last_checkup_date);
            
            if ($stmt->execute()) {
                $message = "New livestock added successfully.";
                $tag_id = $breed = $age = $weight = ""; // Reset fields
            } else {
                $error = "Error adding record: " . $conn->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<div class="executive-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">New Registration</h1>
            <p class="text-muted small mb-0">Expand the institutional fleet with a new livestock profile.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="view_animals.php" class="btn btn-light btn-sm px-3 border">
                <i class="fas fa-arrow-left me-1"></i> Back to Registry
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">

<?php if($message): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 outfit fw-600">Livestock Intelligence Profile</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-600 text-muted mb-1">TAG IDENTIFICATION</label>
                            <input type="text" name="tag_id" class="form-control" placeholder="e.g. CATTLE-001" value="<?php echo isset($tag_id) ? htmlspecialchars($tag_id) : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-600 text-muted mb-1">TAXONOMY CATEGORY</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php 
                                if ($categories->num_rows > 0) {
                                    $categories->data_seek(0);
                                    while($row = $categories->fetch_assoc()) {
                                        $selected = (isset($category_id) && $category_id == $row['id']) ? 'selected' : '';
                                        echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['category_name']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-600 text-muted mb-1">SPECIFIC BREED</label>
                            <input type="text" name="breed" class="form-control" placeholder="e.g. Holstein" value="<?php echo isset($breed) ? htmlspecialchars($breed) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-600 text-muted mb-1">CHRONOLOGICAL AGE</label>
                            <input type="text" name="age" class="form-control" placeholder="e.g. 2 Years" value="<?php echo isset($age) ? htmlspecialchars($age) : ''; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label small fw-600 text-muted mb-1">WEIGHT (KG)</label>
                            <input type="number" step="0.01" name="weight" class="form-control text-center" placeholder="0.00" value="<?php echo isset($weight) ? htmlspecialchars($weight) : ''; ?>">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label small fw-600 text-muted mb-1">HEALTH STATUS</label>
                            <select name="health_status" class="form-select" required>
                                <option value="Healthy" <?php echo (isset($health_status) && $health_status == 'Healthy') ? 'selected' : ''; ?>>Healthy</option>
                                <option value="Sick" <?php echo (isset($health_status) && $health_status == 'Sick') ? 'selected' : ''; ?>>Sick</option>
                                <option value="Under Observation" <?php echo (isset($health_status) && $health_status == 'Under Observation') ? 'selected' : ''; ?>>Under Observation</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label small fw-600 text-muted mb-1">LAST VETERINARY CHECK</label>
                            <input type="date" name="last_checkup_date" class="form-control" value="<?php echo isset($last_checkup_date) ? htmlspecialchars($last_checkup_date) : date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 fw-600 py-3 text-uppercase tracking-widest" style="font-size: 0.75rem;">
                            <i class="fas fa-save me-2"></i> Commit Record to Oracle
                        </button>
                        <a href="view_animals.php" class="btn btn-light border px-4 fw-600 py-3 text-uppercase tracking-widest" style="font-size: 0.75rem;">Abort</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div> <!-- End container-fluid -->

<?php
include 'includes/footer.php';
?>
