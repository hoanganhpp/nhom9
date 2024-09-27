<?php
include 'config.php'; // Bao gồm tệp cấu hình

$query = "SELECT id, ten FROM bang_nhan_vien"; // Lấy dữ liệu nhân viên
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($employee = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($employee['id']) . '</td>';
        echo '<td>' . htmlspecialchars($employee['ten']) . '</td>';
        echo '<td><a href="employee_details.php?id=' . htmlspecialchars($employee['id']) . '" class="btn btn-info">Xem Hồ Sơ</a></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3">Không có nhân viên nào.</td></tr>';
}
?>
