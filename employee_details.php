<?php
session_start();
include 'config.php'; // Bao gồm tệp cấu hình

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Kiểm tra ID nhân viên từ URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Lấy ID nhân viên từ URL

    // Truy vấn thông tin nhân viên và ảnh
    $query = "SELECT nv.*, a.anh AS image_url, l.luong_co_ban, l.tro_cap, l.thuong, l.phat, 
                     cv.chuc_vu, bc.bang_cap, ln.loai_nhan_vien
              FROM bang_nhan_vien nv
              LEFT JOIN bang_anh_nhan_vien a ON nv.id = a.employee_id
              LEFT JOIN bang_luong l ON nv.id = l.employee_id
              LEFT JOIN bang_chuc_vu cv ON nv.id = cv.employee_id
              LEFT JOIN bang_bang_cap bc ON nv.id = bc.employee_id
              LEFT JOIN bang_loai_nhan_vien ln ON nv.id = ln.employee_id
              WHERE nv.id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-warning'>Không tìm thấy nhân viên.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ID không hợp lệ.</div>";
    exit;
}

include 'layout/header.php';
?>

<div class="container">
    <h2>Thông Tin Chi Tiết Nhân Viên</h2>
    <div class="row align-items-center">
        <div class="col-md-4 text-center">
            <img src="<?php echo htmlspecialchars($employee['image_url']); ?>" alt="Ảnh Nhân Viên" class="img-fluid rounded-circle">
        </div>
        <div class="col-md-8">
            <div class="info-container" style="margin-left: 50%; padding-right: 30px;">
                <?php
                // Danh sách thông tin nhân viên
                $infoFields = [
                    'ID' => $employee['id'],
                    'Tên Nhân Viên' => $employee['ten'],
                    'Giới Tính' => $employee['gioi_tinh'],
                    'Ngày Sinh' => $employee['ngay_sinh'],
                    'Địa Chỉ' => $employee['dia_chi'],
                    'Số Điện Thoại' => $employee['sdt'],
                    'Email' => $employee['email'],
                    'Chuyên Môn' => $employee['chuyen_mon'],
                    'Tôn Giáo' => $employee['ton_giao'],
                    'Quốc Tịch' => $employee['quoc_tich'],
                    'Tình Trạng Hôn Nhân' => $employee['tinh_trang_hon_nhan'],
                    'Trạng Thái' => $employee['trang_thai'],
                    'Lương Cơ Bản' => number_format($employee['luong_co_ban'], 2) . " VND",
                    'Trợ Cấp' => number_format($employee['tro_cap'], 2) . " VND",
                    'Thưởng' => number_format($employee['thuong'], 2) . " VND",
                    'Phạt' => number_format($employee['phat'], 2) . " VND",
                    'Chức Vụ' => $employee['chuc_vu'],
                    'Bằng Cấp' => $employee['bang_cap'],
                    'Loại Nhân Viên' => $employee['loai_nhan_vien'],
                ];

                foreach ($infoFields as $label => $value) {
                    echo "<p><strong>$label:</strong> " . htmlspecialchars($value) . "</p>";
                }
                ?>
            </div>
        </div>
    </div>

    
</div>

<style>
body {
    font-family: Arial, sans-serif;
    transition: background-color 0.5s, color 0.5s;
}

.container {
    background-color: var(--bg-color, #f9f9f9);
    color: var(--text-color, #333);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: auto;
}

h2 {
    text-align: center;
}

.info-container {
    padding-right: 30px;
}

.col-md-4 {
    text-align: center;
}

.img-fluid {
    border: 2px solid #007bff;
    padding: 5px;
    border-radius: 50%;
    max-width: 150px;
    height: auto;
}

p {
    font-size: 16px;
    margin: 10px 0;
}

strong {
    color: #007bff;
}

.btn-danger {
    width: 100%;
    margin-top: 20px;
}
</style>

<?php
include 'layout/footer.php';
?>
