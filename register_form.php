<?php
// Place your database connection and configuration here
require_once "config.php";

if (isset($_POST['submit'])) {
    $name =  $_POST['name'];
    $email =  $_POST['email'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];
    $user_type = $_POST['user_type'];

    // File upload handling
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $pdf_file = $_FILES['pdf_file'];
        $pdf_filename = basename($pdf_file['name']);

        // Check if the file is a PDF
        $pdf_ext = strtolower(pathinfo($pdf_filename, PATHINFO_EXTENSION));
        if ($pdf_ext !== 'pdf') {
            $error[] = 'Only PDF files are allowed to be uploaded';
        } else {
            $pdf_content = file_get_contents($pdf_file['tmp_name']);
        }
    }

    $select = "SELECT * FROM user_form WHERE email = '$email' && password = '$pass' ";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {
        if ($pass != $cpass) {
            $error[] = 'Passwords do not match!';
        } else {
            // Insert user data into the database
            $insert = "INSERT INTO user_form (name, email, password, user_type, pdf_filename, pdf_content) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert);

            // Bind the parameters
            mysqli_stmt_bind_param($stmt, 'sssssb', $name, $email, $pass, $user_type, $pdf_filename, $pdf_content);

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                // Data inserted successfully
                header('location: login_form.php');
            } else {
                $error[] = 'Failed to insert user data into the database';
            }

            mysqli_stmt_close($stmt);
        }
    }
}

?>
