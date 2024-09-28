<?php
session_start();
include 'config.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Xử lý thêm phòng ban
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_phong_ban = $_POST['ten_phong_ban'];

    $insertQuery = "INSERT INTO bang_phong_ban (ten_phong_ban) VALUES (?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("s", $ten_phong_ban);
    if ($stmt->execute()) {
        header('Location: departments.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Thêm không thành công.</div>";
    }
}

include 'layout/header.php';
?>

<div class="container mt-5">
    <h4>Thêm Phòng Ban Mới</h4>
    <form method="post">
        <div class="form-group">
            <label for="ten_phong_ban">Tên Phòng Ban</label>
            <input type="text" class="form-control" id="ten_phong_ban" name="ten_phong_ban" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
    </form>
</div>

<?php include 'layout/footer.php'; ?>
