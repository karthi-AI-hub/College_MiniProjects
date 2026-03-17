<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT e.*, t.type_name 
                            FROM events e 
                            JOIN event_types t ON e.type_id = t.id 
                            WHERE e.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$event) {
        die("Event details not found.");
    }
} else {
    header("Location: view_events.php");
    exit();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <h1 class="h2 fw-bold text-dark">Event Pass Generator</h1>
    <div>
        <button onclick="window.print()" class="btn btn-magenta me-2"><i class="fas fa-print me-2"></i>Print Pass</button>
        <a href="view_events.php" class="btn btn-outline-dark">Registry</a>
    </div>
</div>

<div class="ticket-container shadow">
    <div class="ticket-header">
        <h2 class="text-magenta fw-bold mb-0"><i class="fas fa-bolt me-2"></i>EVENT HORIZON</h2>
        <p class="text-muted small">Invitation & Entry Pass</p>
    </div>

    <div class="row">
        <div class="col-8">
            <h5 class="text-uppercase fw-bold text-dark mb-1">Event Title</h5>
            <h3 class="fw-bold mb-4"><?php echo htmlspecialchars($event['title']); ?></h3>

            <div class="row mb-4">
                <div class="col-6">
                    <p class="text-muted small mb-0 font-weight-bold">DATE</p>
                    <p class="fw-bold"><?php echo date('d F Y', strtotime($event['event_date'])); ?></p>
                </div>
                <div class="col-6">
                    <p class="text-muted small mb-0 font-weight-bold">CATEGORY</p>
                    <p class="fw-bold text-magenta"><?php echo htmlspecialchars($event['type_name']); ?></p>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-muted small mb-0 font-weight-bold">VENUE</p>
                <p class="fw-bold"><?php echo htmlspecialchars($event['venue']); ?></p>
            </div>

            <div class="">
                <p class="text-muted small mb-0 font-weight-bold">ORGANIZER</p>
                <p class="fw-bold"><?php echo htmlspecialchars($event['organizer'] ?: 'N/A'); ?></p>
            </div>
        </div>
        <div class="col-4 text-center border-start">
            <div class="py-4">
                <i class="fas fa-qrcode fa-6x text-dark mb-3 opacity-25"></i>
                <p class="text-muted small">PASS ID: #EV-<?php echo str_pad($event['id'], 4, '0', STR_PAD_LEFT); ?></p>
                <div class="mt-4 pt-3 border-top">
                    <p class="text-magenta fw-bold small">VALID ENTRY</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 pt-3 border-top text-center no-print">
        <p class="text-muted small mb-0">Note: This is an automatically generated pass for Event Horizon Management System.</p>
    </div>
</div>

<style>
/* Font overrides specifically for the ticket for better print appearance */
.ticket-container h3, .ticket-container h5, .ticket-container p {
    font-family: 'Poppins', sans-serif;
}
</style>

<?php
include 'includes/footer.php';
?>
