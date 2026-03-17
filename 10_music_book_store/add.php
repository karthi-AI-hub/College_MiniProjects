<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Fetch Types for Dropdown
$types = $conn->query("SELECT * FROM media_type ORDER BY type_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $creator = trim($_POST['creator']);
    $type_id = $_POST['type_id'];
    $price = $_POST['price'];
    $year = $_POST['release_year'];
    $genre = trim($_POST['genre']);

    if (empty($title) || empty($creator) || empty($price)) {
        $error = "Asset Title, Creator, and Price are mandatory for curation.";
    } else {
        $stmt = $conn->prepare("INSERT INTO inventory (title, creator, type_id, price, release_year, genre) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssidis", $title, $creator, $type_id, $price, $year, $genre);
        
        if ($stmt->execute()) {
            $message = "[$title] has been successfully inducted into the catalog.";
        } else {
            $error = "Curator Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-purple fw-bold">Curate Discovery Asset</h1>
    <a href="view_catalog.php" class="btn btn-outline-purple shadow-sm"><i class="fas fa-th-large me-2"></i>Catalog Node</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4"><i class="fas fa-check-circle me-2"></i> <?php echo $message; ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger border-0 shadow-sm mb-4"><i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error; ?></div>
<?php endif; ?>

<div class="card border-0 shadow rounded-4 overflow-hidden">
    <div class="row g-0">
        <div class="col-md-4 bg-purple d-flex flex-column justify-content-center align-items-center text-white p-5">
            <i class="fas fa-compact-disc fa-5x text-gold mb-4 pulse-animation"></i>
            <h4 class="fw-bold">Media Curation</h4>
            <p class="text-center opacity-75 small">Ensure metadata accuracy for optimal purchaser discoverability.</p>
        </div>
        <div class="col-md-8 p-5 bg-white">
            <form action="" method="post">
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">ASSET TITLE</label>
                    <input type="text" name="title" class="form-control form-control-lg border-purple" placeholder="e.g. A Night at the Opera" required>
                </div>
                
                <div class="row">
                    <div class="col-md-7 mb-4">
                        <label class="form-label fw-bold text-muted small">CREATOR (ARTIST / AUTHOR)</label>
                        <input type="text" name="creator" class="form-control" placeholder="Primary Creator" required>
                    </div>
                    <div class="col-md-5 mb-4">
                        <label class="form-label fw-bold text-muted small">MEDIA FORMAT</label>
                        <select name="type_id" class="form-select" required>
                            <?php while($t = $types->fetch_assoc()): ?>
                                <option value="<?php echo $t['id']; ?>"><?php echo $t['type_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-muted small">PRICE VALUATION ($)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-purple fw-bold">$</span>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-muted small">RELEASE YEAR</label>
                        <input type="number" name="release_year" class="form-control" value="<?php echo date('Y'); ?>">
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-muted small">GENRE / CATEGORY</label>
                        <input type="text" name="genre" class="form-control" placeholder="e.g. Classical, Science" required>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="btn btn-purple text-white btn-lg px-5 fw-bold shadow">PUBLISH TO STOREFRONT</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.border-purple { border-color: rgba(75, 0, 130, 0.2); }
.border-purple:focus { border-color: #4b0082; box-shadow: 0 0 0 0.25rem rgba(75, 0, 130, 0.1); }
.pulse-animation { animation: pulse 2s infinite; }
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>

<?php
include 'includes/footer.php';
?>
