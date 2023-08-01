<?php
require_once "config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the PDF file details from the database
    $select = "SELECT pdf_filename, pdf_content FROM user_form WHERE id = ?";
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $pdf_filename, $pdf_content);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Set the appropriate headers for download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $pdf_filename . '"');
    header('Content-Length: ' . strlen($pdf_content));

    // Output the PDF content
    echo $pdf_content;
    exit;
} else {
    echo "PDF not found.";
}
