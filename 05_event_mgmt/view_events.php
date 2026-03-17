<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch all events
$query = "SELECT e.*, t.type_name 
          FROM events e 
          JOIN event_types t ON e.type_id = t.id 
          ORDER BY e.event_date DESC";
$res = $conn->query($query);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 fw-bold text-dark">Event Registry</h1>
    <a href="add_event.php" class="btn btn-magenta"><i class="fas fa-plus me-2"></i>New Event</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res->num_rows > 0): ?>
                        <?php while($row = $res->fetch_assoc()): ?>
                            <?php 
                                $is_upcoming = (strtotime($row['event_date']) >= strtotime(date('Y-m-d')));
                                $status_badge = $is_upcoming ? 'bg-success' : 'bg-danger';
                                $status_text = $is_upcoming ? 'Upcoming' : 'Completed';
                            ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><span class="text-magenta"><?php echo htmlspecialchars($row['type_name']); ?></span></td>
                                <td><?php echo date('d M Y', strtotime($row['event_date'])); ?></td>
                                <td><?php echo htmlspecialchars($row['venue']); ?></td>
                                <td><span class="badge <?php echo $status_badge; ?>"><?php echo $status_text; ?></span></td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="view_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light" title="View Pass"><i class="fas fa-id-card"></i></a>
                                        <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light text-danger" title="Delete" onclick="return confirm('Cancel this event?')"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Zero events in registry.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
