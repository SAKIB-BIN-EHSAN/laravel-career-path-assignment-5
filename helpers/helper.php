<?php

session_start();

function sanitize(string $value)
{
    return htmlspecialchars(stripslashes(trim($value)));
}

/*
* Get current balance
*/
function getCurrentBalanceOfLoggedInUser(): ?float
{
    require_once '../database/connection.php';

    $databaseObj = new DatabaseConnection();
    $database_conn = $databaseObj->connectToDB();
    $userEmail = $_SESSION['useremail'];
    $errors = [];

    $getUserSql = "SELECT balance FROM users WHERE email = '$userEmail'";
    if (!mysqli_query($database_conn, $getUserSql)) {
        $errors['auth-error'] = 'Something went wrong!';
        return 0.0;
    } else {
        $result = mysqli_query($database_conn, $getUserSql);
        $data = mysqli_fetch_assoc($result);

        return floatval($data['balance']);
    }
}

function flashMessage($key, $message = null)
{
    if (isset($message)) {
        $_SESSION['flash'][$key] = $message;
    } else {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);

        return $message;
    }
}

?>