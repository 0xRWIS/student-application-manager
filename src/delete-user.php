<?php
include('./database.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];

    try {
        $stmt = $dbconnect->prepare("DELETE FROM users WHERE id = :id AND role = 'user'");
        
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: dashboard-student-manager.php?status=deleted");
            exit();
        } else {
            echo "Error: Could not delete the user.";
        }

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: dashboard-student-manager.php");
    exit();
}
?>