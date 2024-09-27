<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $chuc_vu = $_POST['chuc_vu'];
    $mo_ta = $_POST['mo_ta'];
    $trang_thai = $_POST['trang_thai'];

    $stmt = $conn->prepare("UPDATE bang_chuc_vu SET chuc_vu = ?, mo_ta = ?, trang_thai = ? WHERE id = ?");
    $stmt->bind_param("sssi", $chuc_vu, $mo_ta, $trang_thai, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Chức vụ đã được cập nhật']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật chức vụ']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
?>
