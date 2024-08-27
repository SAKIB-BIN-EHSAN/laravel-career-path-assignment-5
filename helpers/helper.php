<?php

session_start();

function sanitize(string $value)
{
    return htmlspecialchars(stripslashes(trim($value)));
}

/*
* Get current balance
*/
function getCurrentBalanceOfLoggedInUser($databaseType): ?float
{
    require_once '../database/connection.php';
    $userEmail = $_SESSION['useremail'];

    if ($databaseType === 'sql') {
        $databaseObj = new DatabaseConnection();
        $database_conn = $databaseObj->connectToDB();
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
    } else {
        // Read data from file
        $fileName = "../data/balances.txt";
        $myFile = fopen($fileName, "r") or die('Unable to open file');
        $balances = file($fileName, FILE_IGNORE_NEW_LINES);

        foreach ($balances as $balance) {
        $balanceInfo = explode(",", $balance);

        if ($balanceInfo[1] == $userEmail) {
            $currentBalance = floatval($balanceInfo[2]);
            break;
        }
        }
        fclose($myFile);

        return $currentBalance;
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