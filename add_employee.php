<?php
session_start();
include 'config.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['message' => 'Bạn không có quyền thực hiện hành động này.']);
    exit;
}

// Lấy dữ liệu từ yêu cầu
$name = $_POST['name'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$specialization = $_POST['specialization'];
$status = $_POST['status'];

// Truy vấn thêm nhân viên
$query = "INSERT INTO bang_nhan_vien (ten, gioi_tinh, ngay_sinh, dia_chi, sdt, email, chuyen_mon, trang_thai)
          VALUES ('$name', '$gender', '$dob', '$address', '$phone', '$email', '$specialization', '$status')";
if ($conn->query($query) === TRUE) {
    echo json_encode(['message' => 'Thêm nhân viên thành công.']);
} else {
    echo json_encode(['message' => 'Lỗi: ' . $conn->error]);
}

$conn->close();
?>
