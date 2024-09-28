<?php
session_start();
include 'config.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Kiểm tra ID phòng ban từ URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Truy vấn thông tin phòng ban
    $query = "SELECT * FROM bang_phong_ban WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $department = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-warning'>Không tìm thấy phòng ban.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ID không hợp lệ.</div>";
    exit;
}

// Xử lý cập nhật phòng ban
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_phong_ban = $_POST['ten_phong_ban'];

    $updateQuery = "UPDATE bang_phong_ban SET ten_phong_ban = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $ten_phong_ban, $id);
    if ($updateStmt->execute()) {
        header('Location: departments.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Cập nhật không thành công.</div>";
    }
}

include 'layout/header.php';
?>

<div class="container mt-5">
    <h4>Sửa Phòng Ban</h4>
    <form method="post">
        <div class="form-group">
            <label for="ten_phong_ban">Tên Phòng Ban</label>
            <input type="text" class="form-control" id="ten_phong_ban" name="ten_phong_ban" value="<?php echo htmlspecialchars($department['ten_phong_ban']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>

<?php include 'layout/footer.php'; ?>
