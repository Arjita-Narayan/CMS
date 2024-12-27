<?php
include "db.php";
session_start();

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '{$username}'";
    $select_user_query = mysqli_query($connection, $query);
    if (!$select_user_query) {
        die("QUERY FAILED" . mysqli_error($connection));
    }

    if (mysqli_num_rows($select_user_query) == 0) {
        // User not found
        header("Location: ../index.php");
        exit;
    }

    $row = mysqli_fetch_assoc($select_user_query);
    $db_user_password = $row['user_password'];

    // Verify the password
    if (password_verify($password, $db_user_password)) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['firstname'] = $row['user_firstname'];
        $_SESSION['lastname'] = $row['user_lastname'];
        $_SESSION['user_role'] = $row['user_role'];

        header("Location: ../admin");
        exit;
    } else {
        // Incorrect password
        header("Location: ../index.php");
        exit;
    }
}
?>