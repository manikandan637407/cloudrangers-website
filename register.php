<?php

$name = $_POST['name'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$upswd1 = $_POST['upswd1'];
$upswd2 = $_POST['upswd2'];
$ration = $_POST['ration'];
$aadhar = $_POST['aadhar'];
$phone = $_POST['phone'];

if (!empty($name) && !empty($fullname) && !empty($email) && !empty($upswd1) && !empty($upswd2) && !empty($ration) && !empty($aadhar) && !empty($phone)) {
    
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "cloudrangers";
    
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
    
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }
    
    // Check for existing email, ration card, Aadhar, or phone number
    $checkQuery = "SELECT email, ration, aadhar, phone FROM register WHERE email = ? OR ration = ? OR aadhar = ? OR phone = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ssss", $email, $ration, $aadhar, $phone);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "Error: Email, Ration Card, Aadhar, or Phone is already registered.";
    } else {
        $stmt->close();
        
        $insertQuery = "INSERT INTO register (name, fullname, email, upswd1, upswd2, ration, aadhar, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssssssii", $name, $fullname, $email, $upswd1, $upswd2, $ration, $aadhar, $phone);
        
        if ($stmt->execute()) {
            echo "New record inserted successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "All fields are required";
}
?>