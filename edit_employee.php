<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE bang_nhan_vien SET 
        ten = ?, 
        gioi_tinh = ?, 
        ngay_sinh = ?, 
        dia_chi = ?, 
        sdt = ?, 
        email = ?, 
        chuyen_mon = ?, 
        trang_thai = ? 
        WHERE id = ?");
        
    $stmt->bind_param("ssssssssi", $name, $gender, $dob, $address, $phone, $email, $specialization, $status, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Employee updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating employee']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
