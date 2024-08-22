<?php

require_once './helpers/helper.php';
require_once './database/connection.php';

class UserLogin
{
    public $email = '';
    public $password = '';
    public $errors = [];

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function login()
    {
        $this->validateData();

        if (count($this->errors) === 0) {

            $dbObj = new DatabaseConnection();
            $database_conn = $dbObj->connectToDB();

            $email = mysqli_real_escape_string($database_conn, $this->email);
            $password = mysqli_real_escape_string($database_conn, $this->password);

            $sql = "SELECT * FROM users WHERE email = '$email'";

            if (!mysqli_query($database_conn, $sql)) {
                // echo "Error: " . $sql . "<br>" . mysqli_error($database_conn);
                $this->errors['auth-error'] = 'Something went wrong!';
            } else {
                $result = mysqli_query($database_conn, $sql);
                $row = mysqli_num_rows($result);

                if ($row === 1) {
                    $userData = mysqli_fetch_assoc($result);
                    if (password_verify($password, $userData['password'])) {
                        $_SESSION['user_id'] = $userData['id'];
                        $_SESSION['username'] = $userData['name'];
                        $_SESSION['useremail'] = $userData['email'];

                        if ($email === 'admin@admin.com') {
                            flashMessage('login-success', 'Logged-in successfully');
                            header('Location:admin/customers.php');
                            exit;
                        } else {
                            flashMessage('login-success', 'Logged-in successfully');
                            header('Location:customer/dashboard.php');
                            exit;
                        }
                    } else {
                        $this->errors['auth-error'] = 'Invalid email or password.';
                    }
                } else {
                    $this->errors['auth-error'] = 'Invalid email or password.';
                }
            }
        }
    }

    public function validateData()
    {
        $this->errors = [];

        // Validation checking for user's email
        if (empty($this->email)) {
            $this->errors['email'] = 'You must enter your email';
        }
        else {
            $this->email = sanitize($this->email);
        
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Enter a valid email';
            }
        }
        
        // Validation checking for user's password
        if (empty($this->password)) {
            $this->errors['password'] = 'You must enter your password';
        }
        else {
            $this->password = sanitize($this->password);
        
            if (strlen($this->password) < 8) {
                $this->errors['password'] = 'Your password should be atleast 8 characters.';
            }
        }
    }
}


?>