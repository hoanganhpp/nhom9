<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $employee_id = $_POST['employee_id'];
    $basic_salary = $_POST['luong_co_ban'];
    $allowance = $_POST['tro_cap'];
    $bonus = $_POST['thuong'];
    $penalty = $_POST['phat'];

    // Chuẩn bị truy vấn
    $stmt = $conn->prepare("INSERT INTO bang_luong (employee_id, luong_co_ban, tro_cap, thuong, phat) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("idddd", $employee_id, $basic_salary, $allowance, $bonus, $penalty);

        // Thực thi truy vấn
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Lương đã được thêm']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm lương: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi chuẩn bị truy vấn: ' . $conn->error]);
    }
    
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
?>
