<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $category = trim($_POST['category']);

    if (empty($title) || empty($author) || empty($isbn)) {
        $error = "Title, Author, and ISBN are mandatory catalog fields.";
    } else {
        // Check Duplicate ISBN
        $check = $conn->prepare("SELECT id FROM books WHERE isbn = ?");
        $check->bind_param("s", $isbn);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "A volume with ISBN $isbn already exists in catalog.";
        } else {
            $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, category, status) VALUES (?, ?, ?, ?, 'Available')");
            $stmt->bind_param("ssss", $title, $author, $isbn, $category);
            
            if ($stmt->execute()) {
                $message = "Title '$title' added to library collection.";
            } else {
                $error = "Error adding title: " . $conn->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-navy fw-bold">Catalog New Title</h1>
    <a href="view_books.php" class="btn btn-outline-navy"><i class="fas fa-arrow-left me-2"></i>Back to Inventory</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success shadow-sm"><?php echo $message; ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger shadow-sm"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Book Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g. Clean Code" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">ISBN Number <span class="text-danger">*</span></label>
                    <input type="text" name="isbn" class="form-control form-control-lg" placeholder="e.g. 978-0132350884" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Author <span class="text-danger">*</span></label>
                    <input type="text" name="author" class="form-control" placeholder="FullName" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="e.g. Computer Science">
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-navy btn-lg px-5">Add to Collection</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
