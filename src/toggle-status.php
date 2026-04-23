<?php
include('./database.php');

if (isset($_GET['id']) && isset($_GET['current_status'])) {
    $id = $_GET['id'];
    $current = $_GET['current_status'];
    
    $new_status = ($current === 'open') ? 'close' : 'open';

    try {
        $stmt = $dbconnect->prepare("UPDATE departments SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $id]);
        
        header("Location: dashboard-manager.php?status=changed");
        exit();
    } catch (PDOException $e) {
        die("Error updating status: " . $e->getMessage());
    }
} else {
    header("Location: dashboard-manager.php");
    exit();
}