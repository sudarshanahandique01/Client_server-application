<?php
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

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<div class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Register Now</h3>
      <?php
      if (isset($error)) {
         foreach ($error as $err) {
            echo '<span class="error-msg">' . $err . '</span>';
         }
      }
      ?>
      <input type="text" name="name" required placeholder="Enter your name">
      <input type="email" name="email" required placeholder="Enter your email">
      <input type="password" name="password" required placeholder="Enter your password">
      <input type="password" name="cpassword" required placeholder="Confirm your password">
      <select name="user_type">
         <option value="user">User</option>
         <option value="admin">Admin</option>
      </select>
      <input type="file" name="pdf_file" required accept=".pdf">
      <!-- 'accept=".pdf"' specifies that only PDF files are allowed to be selected -->
      <div class="register"> <input type="submit" name="submit" value="Register Now" class="form-btn"></div>
      <p>Already have an account? <a href="login_form.php">Login now</a></p>
   </form>

   <!-- Add a link to view the database at the top of the page -->
   <div style="text-align: center; margin-top: 20px;">
     <div class="view"> <a href="view_database.php" style="color: blue;"><button>View Database</button></a></div>
   </div>
</div>

</body>
</html>
