<?php
session_start();
include('./database.php'); // This file contains your $dbconnect PDO variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Capture the data from the form
    $name = trim($_POST['department']);
    $description = trim($_POST['description']);
    $deadline = $_POST['deadline'];
    $requirements = isset($_POST['requirements']) ? $_POST['requirements'] : [];

    if (!empty($name) && !empty($description) && !empty($deadline)) {
        try {
            // Start a transaction so if one insert fails, nothing is saved
            $dbconnect->beginTransaction();

            // 2. Insert into 'departments' table (Columns: name, description, deadline)
            $sqlDept = "INSERT INTO departments (name, description, deadline) 
                        VALUES (:name, :description, :deadline)";
            $stmtDept = $dbconnect->prepare($sqlDept);
            $stmtDept->execute([
                ':name'        => $name,
                ':description' => $description,
                ':deadline'    => $deadline
            ]);

            // 3. Get the auto-increment 'id' of the department we just inserted
            $new_department_id = $dbconnect->lastInsertId();

            // 4. Insert each requirement into 'department_requirements' table
            if (!empty($requirements)) {
                $sqlReq = "INSERT INTO department_requirements (department_id, requirement) 
                           VALUES (:dept_id, :req_text)";
                $stmtReq = $dbconnect->prepare($sqlReq);

                foreach ($requirements as $single_req) {
                    $single_req = trim($single_req);
                    if (!empty($single_req)) {
                        $stmtReq->execute([
                            ':dept_id'  => $new_department_id,
                            ':req_text' => $single_req
                        ]);
                    }
                }
            }

            // Save everything to the database
            $dbconnect->commit();

            header("Location: dashboard-manager.php?status=success");
            exit();

        } catch (PDOException $e) {
            // If any error happens, undo the changes
            $dbconnect->rollBack();
            die("Database Error: " . $e->getMessage());
        }
    } else {
        header("Location: add-new-program.php?error=emptyfields");
        exit();
    }
}