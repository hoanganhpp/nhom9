<?php
$database_server = 'localhost';  // Thay thế bằng máy chủ MySQL của bạn
$database_username = 'root';      // Thay thế bằng tên đăng nhập MySQL của bạn
$database_password = '';          // Thay thế bằng mật khẩu MySQL của bạn
$database_name = 'quanlydomain';       // Thay thế bằng tên cơ sở dữ liệu của bạn

// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($database_server, $database_username, $database_password, $database_name);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Lỗi kết nối đến cơ sở dữ liệu: " . $conn->connect_error);
}
?>
