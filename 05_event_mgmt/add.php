<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Fetch Event Types
$event_types = $conn->query("SELECT * FROM event_types ORDER BY type_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $type_id = $_POST['type_id'];
    $event_date = $_POST['event_date'];
    $venue = trim($_POST['venue']);
    $organizer = trim($_POST['organizer']);
    $description = trim($_POST['description']);

    if (empty($title) || empty($type_id) || empty($event_date) || empty($venue)) {
        $error = "Essential event details (*) are missing.";
    } else {
        $stmt = $conn->prepare("INSERT INTO events (title, type_id, event_date, venue, organizer, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $title, $type_id, $event_date, $venue, $organizer, $description);
        
        if ($stmt->execute()) {
            $message = "Event launched successfully! Check the registry.";
        } else {
            $error = "Blast off failed: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 fw-bold text-dark">Host New Event</h1>
    <a href="view_events.php" class="btn btn-outline-dark"><i class="fas fa-arrow-left me-2"></i>To Registry</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success alert-dismissible shadow-sm fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger alert-dismissible shadow-sm fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <label class="form-label fw-bold">Event Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g. Annual Sports Meet 2026" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Event Category <span class="text-danger">*</span></label>
                    <select name="type_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php while($t = $event_types->fetch_assoc()): ?>
                            <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['type_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Event Date <span class="text-danger">*</span></label>
                    <input type="date" name="event_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Venue / Location <span class="text-danger">*</span></label>
                    <input type="text" name="venue" class="form-control" placeholder="e.g. Main Auditorium" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Organizer / Department</label>
                    <input type="text" name="organizer" class="form-control" placeholder="e.g. Science Club">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Event Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Brief about the event..."></textarea>
            </div>

            <div class="text-end pt-3">
                <button type="reset" class="btn btn-light px-4 me-2">Clear</button>
                <button type="submit" class="btn btn-magenta btn-lg px-5 shadow">Launch Event</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
