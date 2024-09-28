<?php
session_start();
include 'config.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Kiểm tra ID phòng ban từ URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Xóa phòng ban
    $deleteQuery = "DELETE FROM bang_phong_ban WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: departments.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Xóa không thành công.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>ID không hợp lệ.</div>";
}
