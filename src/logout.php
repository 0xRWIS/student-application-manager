<?php
// 1. Start the session to access it
session_start();

// 2. Unset all session variables (Clear the data)
$_SESSION = array();

// 3. Destroy the session cookie on the user's browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finally, destroy the session on the server
session_destroy();

// 5. Redirect the user back to the login page
header("Location: login.php");
exit();
?>