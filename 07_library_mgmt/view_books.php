<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$where_clause = "";
if ($search) {
    $search = $conn->real_escape_string($search);
    $where_clause = "WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR isbn LIKE '%$search%'";
}

$query = "SELECT * FROM books $where_clause ORDER BY created_at DESC";
$res = $conn->query($query);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-navy fw-bold">Inventory Registry</h1>
    <a href="add.php" class="btn btn-navy shadow-sm"><i class="fas fa-plus me-2"></i>Catalog New Title</a>
</div>

<!-- Search Form -->
<div class="card shadow-sm border-0 mb-4 bg-navy p-3">
    <form action="" method="get">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Title, Author, or ISBN..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-warning" type="submit"><i class="fas fa-search"></i> Search</button>
            <?php if($search): ?>
                <a href="view_books.php" class="btn btn-light">Clear</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-navy">
                    <tr>
                        <th class="ps-4">ISBN</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Current State</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res->num_rows > 0): ?>
                        <?php while($book = $res->fetch_assoc()): ?>
                            <tr class="<?php echo ($book['status'] == 'Issued') ? 'text-muted' : ''; ?>">
                                <td class="ps-4 small"><?php echo htmlspecialchars($book['isbn']); ?></td>
                                <td class="fw-bold fs-6"><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><span class="text-navy"><?php echo htmlspecialchars($book['category']); ?></span></td>
                                <td>
                                    <?php if ($book['status'] == 'Available'): ?>
                                        <span class="badge badge-available px-3 py-2"><i class="fas fa-check me-1"></i>Available</span>
                                    <?php else: ?>
                                        <span class="badge badge-issued px-3 py-2"><i class="fas fa-times me-1"></i>Issued</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <?php if ($book['status'] == 'Available'): ?>
                                            <a href="issue_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-navy"><i class="fas fa-hand-holding me-1"></i> Issue</a>
                                            <a href="edit.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-outline-navy"><i class="fas fa-edit"></i></a>
                                            <a href="delete.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Purge volume?')"><i class="fas fa-trash"></i></a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary disabled">On Loan</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Zero volumes found in current shelf.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
