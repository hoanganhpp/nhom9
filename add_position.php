<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $chuc_vu = $_POST['chuc_vu'];
    $mo_ta = $_POST['mo_ta'];
    $trang_thai = $_POST['trang_thai'];

    // Thêm chức vụ vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO bang_chuc_vu (employee_id, chuc_vu, mo_ta, trang_thai) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $employee_id, $chuc_vu, $mo_ta, $trang_thai);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Chức vụ đã được thêm']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm chức vụ: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
?>
