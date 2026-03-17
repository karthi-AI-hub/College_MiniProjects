<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Pre-select book if ID is passed
$selected_book_id = isset($_GET['id']) ? $_GET['id'] : '';

// Fetch Available Books
$available_books = $conn->query("SELECT id, title, isbn FROM books WHERE status = 'Available' ORDER BY title ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $student_name = trim($_POST['student_name']);
    $issue_date = $_POST['issue_date'];

    if (empty($book_id) || empty($student_name) || empty($issue_date)) {
        $error = "All fields are required to process lending.";
    } else {
        // Start Transaction
        $conn->begin_transaction();

        try {
            // 1. Insert Transaction Record
            $stmt1 = $conn->prepare("INSERT INTO transactions (book_id, student_name, issue_date, status) VALUES (?, ?, ?, 'Active')");
            $stmt1->bind_param("iss", $book_id, $student_name, $issue_date);
            $stmt1->execute();

            /* 
               SQL UPDATE Logic:
               We change the book's status to 'Issued' so it can no longer be borrowed by others.
               This is a key part of maintaining data integrity in the inventory.
            */
            $stmt2 = $conn->prepare("UPDATE books SET status = 'Issued' WHERE id = ?");
            $stmt2->bind_param("i", $book_id);
            $stmt2->execute();

            $conn->commit();
            $message = "Book successfully issued to $student_name.";
            
            // Clear selection and refresh available books
            $selected_book_id = "";
            $available_books = $conn->query("SELECT id, title, isbn FROM books WHERE status = 'Available' ORDER BY title ASC");
        } catch (Exception $e) {
            $conn->rollback();
            $error = "System Error: Failed to process lending. " . $e->getMessage();
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-navy fw-bold">Issue Library Volume</h1>
    <a href="view_books.php" class="btn btn-outline-navy"><i class="fas fa-list me-2"></i>Inventory</a>
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
                    <label class="form-label fw-bold">Select Available Book <span class="text-danger">*</span></label>
                    <select name="book_id" class="form-select form-select-lg" required>
                        <option value="">Choose a book...</option>
                        <?php while($b = $available_books->fetch_assoc()): ?>
                            <option value="<?php echo $b['id']; ?>" <?php echo ($selected_book_id == $b['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($b['title']); ?> (<?php echo htmlspecialchars($b['isbn']); ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Student/Borrower Name <span class="text-danger">*</span></label>
                    <input type="text" name="student_name" class="form-control form-control-lg" placeholder="Enter Full Name" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Issue Date</label>
                    <input type="date" name="issue_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-navy btn-lg w-100 shadow-sm"><i class="fas fa-check-circle me-2"></i>Commit Transaction</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
