<?php session_start();


include('./database.php'); // Ensure this path is correct for your database connection file

if (isset($_SESSION['user_id'])) {
    try {
        // Fetch the latest role and name from the database
        $stmt = $dbconnect->prepare("SELECT role, full_name FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $latestUser = $stmt->fetch();

        if ($latestUser) {
            // Update the session with the actual values from the DB
            $_SESSION['role'] = $latestUser['role'];
            $_SESSION['user_name'] = $latestUser['full_name'];
        }
    } catch (PDOException $e) {
        // Silent error or handle as needed
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link href="../dist/output.css" rel="stylesheet">
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z'/%3E%3C/svg%3E">
</head>
