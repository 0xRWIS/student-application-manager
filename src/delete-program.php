<?php
session_start();
include('./database.php');

// Check if an ID was actually sent
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Start transaction
        $dbconnect->beginTransaction();

        // 1. Delete requirements first (child table)
        $sql1 = "DELETE FROM department_requirements WHERE department_id = ?";
        $stmt1 = $dbconnect->prepare($sql1);
        $stmt1->execute([$id]);

        // 2. Delete the department (parent table)
        $sql2 = "DELETE FROM departments WHERE id = ?";
        $stmt2 = $dbconnect->prepare($sql2);
        $stmt2->execute([$id]);

        // If both worked, save the changes
        $dbconnect->commit();

        header("Location: dashboard-manager.php?status=deleted");
        exit();

    } catch (PDOException $e) {
        // If anything fails, undo everything
        $dbconnect->rollBack();
        die("Error deleting program: " . $e->getMessage());
    }
} else {
    header("Location: dashboard-manager.php");
    exit();
}