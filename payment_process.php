<?php
session_start();
include('db.php');

// Handle file upload and donation data
if(isset($_POST['donation_amount']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $amount = mysqli_real_escape_string($con, $_POST['donation_amount']);
    $payment_status = "Pending";
    $proof_file = NULL;
    
    // Handle file upload if exists
    if(isset($_FILES['donation_proof']) && $_FILES['donation_proof']['error'] === 0) {
        $file = $_FILES['donation_proof'];
        
        // File properties
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        
        // Get file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed extensions
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'pdf');
        
        // Validate file
        if(in_array($file_ext, $allowed_extensions)) {
            // Check file size (5MB max)
            if($file_size <= 5242880) { // 5MB in bytes
                // Create uploads directory if it doesn't exist
                $upload_dir = 'uploads/donation_proofs/';
                if(!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                // Generate unique file name
                $new_file_name = 'donation_' . uniqid() . '_' . time() . '.' . $file_ext;
                $file_destination = $upload_dir . $new_file_name;
                
                // Move uploaded file
                if(move_uploaded_file($file_tmp, $file_destination)) {
                    $proof_file = $new_file_name;
                } else {
                    error_log("File upload failed: Could not move file");
                }
            } else {
                error_log("File upload failed: File too large");
            }
        } else {
            error_log("File upload failed: Invalid file type");
        }
    }
    
    // Insert donation record
    $insert_query = "INSERT INTO donation (name, email, phone, amount, payment_status, proof_file) 
                     VALUES ('$name', '$email', '$phone', '$amount', '$payment_status', " . 
                     ($proof_file ? "'$proof_file'" : "NULL") . ")";
    
    if(mysqli_query($con, $insert_query)) {
        $_SESSION['OID'] = mysqli_insert_id($con);
        echo json_encode(['status' => 'success', 'order_id' => $_SESSION['OID']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($con)]);
    }
}

// Handle payment completion
if(isset($_POST['payment_id']) && isset($_SESSION['OID'])){
    $payment_id = mysqli_real_escape_string($con, $_POST['payment_id']);
    $order_id = $_SESSION['OID'];
    
    $update_query = "UPDATE donation 
                     SET payment_status='Complete', payment_id='$payment_id' 
                     WHERE id='$order_id'";
    
    if(mysqli_query($con, $update_query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($con)]);
    }
}
?>