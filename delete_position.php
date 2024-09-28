<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];

    // Kiểm tra xem employee_id có hợp lệ không
    if (empty($employee_id)) {
        echo json_encode(['success' => false, 'message' => 'ID nhân viên không được để trống.']);
        exit;
    }

    // Sử dụng prepared statements để tránh SQL Injection
    $stmt = $conn->prepare("DELETE FROM bang_chuc_vu WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Xóa chức vụ thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
