<?php
// Bật hiển thị lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối cơ sở dữ liệu
require_once 'config.php';

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Mã hóa mật khẩu
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$user1_password = password_hash('user123', PASSWORD_DEFAULT);
$user2_password = password_hash('user456', PASSWORD_DEFAULT);

// Chèn dữ liệu
$sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Lỗi chuẩn bị câu lệnh: " . $conn->error);
}

// Dữ liệu cho admin
$username = 'admin';
$hashed_password = $admin_password;
$role = 'admin';
$stmt->bind_param("sss", $username, $hashed_password, $role);
if ($stmt->execute()) {
    echo "Dữ liệu đã được chèn thành công cho $username.<br>";
} else {
    echo "Lỗi: " . $stmt->error . "<br>";
}

// Dữ liệu cho user1
$username = 'user1';
$hashed_password = $user1_password;
$role = 'nhân viên';
$stmt->bind_param("sss", $username, $hashed_password, $role);
if ($stmt->execute()) {
    echo "Dữ liệu đã được chèn thành công cho $username.<br>";
} else {
    echo "Lỗi: " . $stmt->error . "<br>";
}

// Dữ liệu cho user2
$username = 'user2';
$hashed_password = $user2_password;
$role = 'nhân viên';
$stmt->bind_param("sss", $username, $hashed_password, $role);
if ($stmt->execute()) {
    echo "Dữ liệu đã được chèn thành công cho $username.<br>";
} else {
    echo "Lỗi: " . $stmt->error . "<br>";
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>
