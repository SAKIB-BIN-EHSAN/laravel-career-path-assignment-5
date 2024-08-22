<?php

require_once '../helpers/helper.php';
require_once '../database/connection.php';

class Customer {
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'Customer';
    public $errors = [];

    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function registerCustomer()
    {
        $this->validateData();
        
        if (count($this->errors) === 0) {

            $this->storeData();

            if (count($this->errors) === 0) {
                if ($_SESSION['username'] === 'Admin') {
                    flashMessage('register-success', 'User registered successfully.');
                    header('Location:customers.php');
                    exit;
                } else {
                    flashMessage('register-success', 'User registered successfully.');
                    header('Location:../login.php');
                    exit;
                }
            }
        }
    }

    public function validateData()
    {
        $this->errors = [];
        
        // Validation checking for user's name
        if (empty($this->name)) {
            $this->errors['name'] = 'You must enter your name';
        } else {
            $this->name = sanitize($this->name);
        
            if (strlen($this->name) < 3) {
                $this->errors['name'] = 'Your name should be atleast 3 characters.';
            }
        }
        
        // Validation checking for user's email
        if (empty($this->email)) {
            $this->errors['email'] = 'You must enter your email';
        } else {
            $this->email = sanitize($this->email);
        
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Enter a valid email';
            }
        }
        
        // Validation checking for user's password
        if (empty($this->password)) {
            $this->errors['password'] = 'You must enter your password';
        } else {
            $this->password = sanitize($this->password);
        
            if (strlen($this->password) < 8) {
                $this->errors['password'] = 'Your password should be atleast 8 characters.';
            }
        }
    }

    public function storeData()
    {
        $dbObj = new DatabaseConnection();
        $database_conn = $dbObj->connectToDB();

        $userId = uniqid();

        $this->name = mysqli_real_escape_string($database_conn, $this->name);
        $this->email = mysqli_real_escape_string($database_conn, $this->email);
        $this->password = mysqli_real_escape_string($database_conn, $this->password);
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        if ($this->email === 'admin@admin.com') {
            $this->role = 'Admin';
        }

        $sql = "INSERT INTO users (id, name, email, password, role, balance) VALUES ('$userId', '$this->name', '$this->email', '$this->password', '$this->role', '0.0')";

        if (!mysqli_query($database_conn, $sql)) {
            // echo "Error: " . $sql . "<br>" . mysqli_error($database_conn);
            $this->errors['auth-error'] = 'Something went wrong!';
        }
          
        mysqli_close($database_conn);
    }
}

?>