<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: view_books.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $category = trim($_POST['category']);

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, isbn=?, category=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $author, $isbn, $category, $id);
    
    if ($stmt->execute()) {
        $message = "Book metadata updated.";
        $book['title'] = $title;
        $book['author'] = $author;
        $book['isbn'] = $isbn;
        $book['category'] = $category;
    } else {
        $error = "Update failure.";
    }
}
?>

<div class="container-fluid">
    <h2 class="mt-4 text-navy fw-bold">Maintain Catalog #<?php echo $id; ?></h2>
    <hr>
    <?php if($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>

    <div class="card shadow border-0">
        <div class="card-body p-5">
            <form action="" method="post">
                <div class="mb-3">
                    <label class="form-label fw-bold">TITLE</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">AUTHOR</label>
                        <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">ISBN</label>
                        <input type="text" name="isbn" class="form-control" value="<?php echo htmlspecialchars($book['isbn']); ?>" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">CATEGORY</label>
                    <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($book['category']); ?>" required>
                </div>
                <button type="submit" class="btn btn-navy px-5 shadow">SYNC CATALOG</button>
                <a href="view_books.php" class="btn btn-secondary px-4">CANCEL</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
