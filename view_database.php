<?php
require_once "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Database</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/stylesheet.css">
</head>
<body>
   
<div class="container">
   <table>
      <tr>
         <th>ID</th>
         <th>Name</th>
         <th>Email</th>
         <th>User Type</th>
         <th>PDF Filename</th>
         <th>Actions</th>
      </tr>

      <?php
      // Retrieve data from the 'user_form' table
      $select_data = "SELECT * FROM user_form";
      $result = mysqli_query($conn, $select_data);

      while ($row = mysqli_fetch_assoc($result)) {
         echo '<tr>';
         echo '<td>' . $row['id'] . '</td>';
         echo '<td>' . $row['name'] . '</td>';
         echo '<td>' . $row['email'] . '</td>';
         echo '<td>' . $row['user_type'] . '</td>';
         echo '<td>' . $row['pdf_filename'] . '</td>';
         echo '<td><a href="download_pdf.php?id=' . $row['id'] . '">Download PDF</a></td>';
         echo '</tr>';
      }
      ?>

   </table>
</div>

</body>
</html>
