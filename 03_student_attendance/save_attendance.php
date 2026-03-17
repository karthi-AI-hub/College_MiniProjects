<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance_date = $_POST['attendance_date'];
    $attendance_data = $_POST['attendance']; // Array of student_id => status

    if (!empty($attendance_date) && !empty($attendance_data)) {
        
        // Prepare statement for insertion/update
        // We use ON DUPLICATE KEY UPDATE to handle re-submission for the same day (e.g., correcting mistakes)
        $stmt = $conn->prepare("INSERT INTO attendance_records (student_id, attendance_date, status) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE status = VALUES(status)");

        // Iterate through the submitted attendance data
        // $attendance_data is an associative array where key is student_id and value is 'Present' or 'Absent'
        foreach ($attendance_data as $student_id => $status) {
            // Bind parameters: integer (student_id), string (date), string (status)
            $stmt->bind_param("iss", $student_id, $attendance_date, $status);
            
            // Execute the query for each student
            if (!$stmt->execute()) {
                // In a real app, we might log errors here
                // echo "Error: " . $stmt->error;
            }
        }

        $stmt->close();
        
        // Redirect back with success message
        header("Location: mark_attendance.php?attendance_date=$attendance_date&msg=saved");
        exit();
    } else {
        // Handle empty submission
        header("Location: mark_attendance.php?attendance_date=$attendance_date&error=empty");
        exit();
    }
} else {
    header("Location: mark_attendance.php");
    exit();
}
?>
