<?php
session_start();
include 'config.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Lấy danh sách phòng ban
$query = "SELECT * FROM bang_phong_ban";
$result = $conn->query($query);

include 'layout/header.php';
?>

<div class="container mt-5">
    <h4>Danh Sách Phòng Ban</h4>
    <a href="adddepartments.php" class="btn btn-primary">Thêm Phòng Ban</a>
    <table class="table table-striped table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Phòng Ban</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['ten_phong_ban']); ?></td>
                    <td>
                        <a href="editdepartments.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Sửa</a>
                        <a href="deletedepartments.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'layout/footer.php'; ?>
