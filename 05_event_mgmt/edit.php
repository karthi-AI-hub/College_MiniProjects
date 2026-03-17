<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Fetch Event Types
$event_types = $conn->query("SELECT * FROM event_types ORDER BY type_name ASC");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$event) {
        die("Event not found in the horizon.");
    }
} else {
    header("Location: view_events.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $type_id = $_POST['type_id'];
    $event_date = $_POST['event_date'];
    $venue = trim($_POST['venue']);
    $organizer = trim($_POST['organizer']);
    $description = trim($_POST['description']);

    if (empty($title) || empty($type_id) || empty($event_date) || empty($venue)) {
        $error = "Crucial fields remain empty.";
    } else {
        $stmt = $conn->prepare("UPDATE events SET title=?, type_id=?, event_date=?, venue=?, organizer=?, description=? WHERE id=?");
        $stmt->bind_param("sissssi", $title, $type_id, $event_date, $venue, $organizer, $description, $id);
        
        if ($stmt->execute()) {
            $message = "Event successfully rescheduled/updated!";
            // Refresh data
            $stmt_refresh = $conn->prepare("SELECT * FROM events WHERE id = ?");
            $stmt_refresh->bind_param("i", $id);
            $stmt_refresh->execute();
            $event = $stmt_refresh->get_result()->fetch_assoc();
            $stmt_refresh->close();
        } else {
            $error = "Course correction failed: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 fw-bold text-dark">Modify Event Details</h1>
    <a href="view_events.php" class="btn btn-outline-dark"><i class="fas fa-arrow-left me-2"></i>Registry</a>
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
                    <input type="text" name="title" class="form-control form-control-lg" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Event Category <span class="text-danger">*</span></label>
                    <select name="type_id" class="form-select" required>
                        <?php 
                        $event_types->data_seek(0);
                        while($t = $event_types->fetch_assoc()) {
                            $sel = ($event['type_id'] == $t['id']) ? 'selected' : '';
                            echo "<option value='{$t['id']}' $sel>{$t['type_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Event Date <span class="text-danger">*</span></label>
                    <input type="date" name="event_date" class="form-control" value="<?php echo $event['event_date']; ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Venue / Location <span class="text-danger">*</span></label>
                    <input type="text" name="venue" class="form-control" value="<?php echo htmlspecialchars($event['venue']); ?>" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Organizer / Department</label>
                    <input type="text" name="organizer" class="form-control" value="<?php echo htmlspecialchars($event['organizer']); ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Event Description</label>
                <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>

            <div class="text-end pt-3">
                <button type="submit" class="btn btn-magenta btn-lg px-5 shadow">Update Event</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
