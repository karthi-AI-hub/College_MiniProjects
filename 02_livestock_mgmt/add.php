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

<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <h2 class="text-success">Register New Livestock</h2>
    <a href="view_animals.php" class="btn btn-secondary">Back to List</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tag ID <span class="text-danger">*</span></label>
                    <input type="text" name="tag_id" class="form-control" placeholder="e.g. CATTLE-001" value="<?php echo isset($tag_id) ? htmlspecialchars($tag_id) : ''; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
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
                <div class="col-md-6 mb-3">
                    <label class="form-label">Breed</label>
                    <input type="text" name="breed" class="form-control" placeholder="e.g. Holstein" value="<?php echo isset($breed) ? htmlspecialchars($breed) : ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Age</label>
                    <input type="text" name="age" class="form-control" placeholder="e.g. 2 Years" value="<?php echo isset($age) ? htmlspecialchars($age) : ''; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight" class="form-control" placeholder="0.00" value="<?php echo isset($weight) ? htmlspecialchars($weight) : ''; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Health Status <span class="text-danger">*</span></label>
                    <select name="health_status" class="form-select" required>
                        <option value="Healthy" <?php echo (isset($health_status) && $health_status == 'Healthy') ? 'selected' : ''; ?>>Healthy</option>
                        <option value="Sick" <?php echo (isset($health_status) && $health_status == 'Sick') ? 'selected' : ''; ?>>Sick</option>
                        <option value="Under Observation" <?php echo (isset($health_status) && $health_status == 'Under Observation') ? 'selected' : ''; ?>>Under Observation</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Last Checkup Date</label>
                    <input type="date" name="last_checkup_date" class="form-control" value="<?php echo isset($last_checkup_date) ? htmlspecialchars($last_checkup_date) : date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-success me-md-2">Save Record</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
