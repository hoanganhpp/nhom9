<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $basic_salary = $_POST['luong_co_ban'];
    $allowance = $_POST['tro_cap'];
    $bonus = $_POST['thuong'];
    $penalty = $_POST['phat'];

    $stmt = $conn->prepare("UPDATE bang_luong SET luong_co_ban = ?, tro_cap = ?, thuong = ?, phat = ? WHERE id = ?");
    $stmt->bind_param("ddddi", $basic_salary, $allowance, $bonus, $penalty, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Lương đã được cập nhật']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật lương']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
?>
