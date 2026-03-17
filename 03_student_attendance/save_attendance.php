<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: mark_attendance.php?msg=auth");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance_date = $_POST['attendance_date'];
    $attendance_data = $_POST['attendance'] ?? []; // Array of student_id => status

    $today = date('Y-m-d');
    if ($attendance_date !== $today) {
        header("Location: mark_attendance.php?msg=date");
        exit();
    }

    $existing_check = $conn->prepare("SELECT COUNT(*) as total FROM attendance_records WHERE attendance_date = ?");
    if (!$existing_check) {
        header("Location: mark_attendance.php?msg=error");
        exit();
    }
    $existing_check->bind_param("s", $attendance_date);
    $existing_check->execute();
    $existing_check->bind_result($existing_total);
    $existing_check->fetch();
    $existing_check->close();

    if ($existing_total > 0) {
        header("Location: mark_attendance.php?msg=locked");
        exit();
    }

    if (!empty($attendance_date) && !empty($attendance_data)) {
        // Prepare statement for insertion
        $stmt = $conn->prepare("INSERT INTO attendance_records (student_id, attendance_date, status) VALUES (?, ?, ?)");
        if (!$stmt) {
            header("Location: mark_attendance.php?msg=error");
            exit();
        }

        // Iterate through the submitted attendance data
        // $attendance_data is an associative array where key is student_id and value is 'Present' or 'Absent'
        foreach ($attendance_data as $student_id => $status) {
            // Bind parameters: integer (student_id), string (date), string (status)
            $stmt->bind_param("iss", $student_id, $attendance_date, $status);
            
            // Execute the query for each student
            if (!$stmt->execute()) {
                $stmt->close();
                header("Location: mark_attendance.php?msg=error");
                exit();
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
