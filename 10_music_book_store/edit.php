<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
    if (!$item) die("Asset ID not found in current node.");
} else {
    header("Location: view_catalog.php");
    exit();
}

$types = $conn->query("SELECT * FROM media_type ORDER BY type_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $creator = trim($_POST['creator']);
    $type_id = $_POST['type_id'];
    $price = $_POST['price'];
    $year = $_POST['release_year'];
    $genre = trim($_POST['genre']);

    if (empty($title) || empty($price)) {
        $error = "Asset metadata integrity check failed: missing fields.";
    } else {
        $stmt = $conn->prepare("UPDATE inventory SET title=?, creator=?, type_id=?, price=?, release_year=?, genre=? WHERE id=?");
        $stmt->bind_param("ssidisi", $title, $creator, $type_id, $price, $year, $genre, $id);
        
        if ($stmt->execute()) {
            $message = "Asset metadata for [$title] has been updated.";
            // Refresh local data
            $item = $_POST;
            $item['id'] = $id;
        } else {
            $error = "Metasync Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-purple fw-bold">Maintain Asset: ID #<?php echo $id; ?></h1>
    <a href="view_catalog.php" class="btn btn-outline-purple shadow-sm"><i class="fas fa-arrow-left me-2"></i>Full Catalog</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card border-0 shadow rounded-4 overflow-hidden">
    <div class="row g-0">
        <div class="col-md-4 bg-dark d-flex flex-column justify-content-center align-items-center text-white p-5 border-end border-gold border-3">
             <i class="fas <?php echo ($item['type_id'] == 1 || $item['type_id'] == 4) ? 'fa-book-open' : 'fa-music'; ?> fa-5x text-gold mb-4"></i>
             <h4 class="fw-bold text-gold">Metadata Editor</h4>
             <p class="text-center opacity-75 small text-white">Adjusting asset metrics in the cloud repository.</p>
        </div>
        <div class="col-md-8 p-5 bg-white">
            <form action="" method="post">
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">ASSET TITLE</label>
                    <input type="text" name="title" class="form-control form-control-lg" value="<?php echo htmlspecialchars($item['title']); ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-7 mb-4">
                        <label class="form-label fw-bold text-muted small">CREATOR (ARTIST / AUTHOR)</label>
                        <input type="text" name="creator" class="form-control" value="<?php echo htmlspecialchars($item['creator']); ?>" required>
                    </div>
                    <div class="col-md-5 mb-4">
                        <label class="form-label fw-bold text-muted small">MEDIA FORMAT</label>
                        <select name="type_id" class="form-select" required>
                            <?php 
                            $types->data_seek(0);
                            while($t = $types->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $t['id']; ?>" <?php echo ($item['type_id'] == $t['id']) ? 'selected' : ''; ?>>
                                    <?php echo $t['type_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-muted small">PRICE VALUATION ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $item['price']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-muted small">RELEASE YEAR</label>
                        <input type="number" name="release_year" class="form-control" value="<?php echo $item['release_year']; ?>">
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold text-muted small">GENRE / CATEGORY</label>
                        <input type="text" name="genre" class="form-control" value="<?php echo htmlspecialchars($item['genre']); ?>" required>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="btn btn-purple text-white btn-lg px-5 fw-bold shadow-lg">COMMIT METADATA UPDATES</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
