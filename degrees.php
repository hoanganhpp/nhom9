<?php
session_start();
include 'config.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Lấy danh sách nhân viên và phòng ban
$query = "
    SELECT nv.id, nv.ten, pb.ten_phong_ban
    FROM bang_nhan_vien nv
    LEFT JOIN bang_phong_ban pb ON nv.phong_ban_id = pb.id
";

$result = $conn->query($query);
?>

<?php include 'layout/header.php'; ?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Danh Sách Nhân Viên và Phòng Ban</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Nhân Viên</th>
                        <th>Tên Nhân Viên</th>
                        <th>Phòng Ban</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['ten']); ?></td>
                            <td><?php echo htmlspecialchars($row['ten_phong_ban'] ?? 'Không có phòng ban'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
