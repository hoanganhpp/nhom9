<?php
session_start();
include 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $chuc_vu = $_POST['chuc_vu'];

    if (empty($employee_id) || empty($chuc_vu)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE bang_chuc_vu SET chuc_vu = ? WHERE employee_id = ?");
    $stmt->bind_param("si", $chuc_vu, $employee_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật chức vụ thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $stmt->error]);
    }

    $stmt->close();
}
?>
