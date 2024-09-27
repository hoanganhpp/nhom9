<!-- <?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id']; // ID nhân viên
    $position = $_POST['position']; // Tên chức vụ
    $description = $_POST['description']; // Mô tả
    $status = $_POST['status']; // Trạng thái

    // Kiểm tra dữ liệu đầu vào
    if (empty($employee_id) || empty($position) || empty($description) || empty($status)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin.']);
        exit;
    }

    // Thực hiện thêm vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO bang_chuc_vu (employee_id, chuc_vu, mo_ta, trang_thai) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $employee_id, $position, $description, $status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm chức vụ thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm chức vụ: ' . $stmt->error]);
    }

    $stmt->close();
}
$conn->close();
?> -->
