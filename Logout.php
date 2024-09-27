<?php
session_start(); // Bắt đầu phiên làm việc

// Kiểm tra xem người dùng có đang đăng nhập không
if (isset($_SESSION['loggedin'])) {
    // Hủy phiên làm việc và tất cả dữ liệu liên quan
    session_unset();
    session_destroy();
}

// Chuyển hướng người dùng về trang đăng nhập
header("Location: login.php"); // Thay đổi 'login.php' thành trang đăng nhập của bạn
exit();
?>
