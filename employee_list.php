<?php
session_start();
include 'config.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Lấy danh sách phòng ban
$phong_ban_query = "SELECT id, ten_phong_ban FROM bang_phong_ban";
$phong_ban_result = $conn->query($phong_ban_query);

// Thêm nhân viên mới
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_employee'])) {
    $ten = $_POST['ten'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $dia_chi = $_POST['dia_chi'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $chuyen_mon = $_POST['chuyen_mon'];
    $ton_giao = $_POST['ton_giao'];
    $quoc_tich = $_POST['quoc_tich'];
    $tinh_trang_hon_nhan = $_POST['tinh_trang_hon_nhan'];
    $trang_thai = $_POST['trang_thai'];
    $bang_cap = $_POST['bang_cap'];
    $phong_ban_id = $_POST['phong_ban_id'];

    // Thêm vào bảng nhân viên
    $stmt = $conn->prepare("INSERT INTO bang_nhan_vien (ten, gioi_tinh, ngay_sinh, dia_chi, sdt, email, chuyen_mon, ton_giao, quoc_tich, tinh_trang_hon_nhan, trang_thai, phong_ban_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssi", $ten, $gioi_tinh, $ngay_sinh, $dia_chi, $sdt, $email, $chuyen_mon, $ton_giao, $quoc_tich, $tinh_trang_hon_nhan, $trang_thai, $phong_ban_id);
    
    if ($stmt->execute()) {
        $employee_id = $stmt->insert_id; // Lấy ID nhân viên vừa thêm
        
        // Thêm vào bảng bằng cấp
        $stmt = $conn->prepare("INSERT INTO bang_bang_cap (employee_id, bang_cap) VALUES (?, ?)");
        $stmt->bind_param("is", $employee_id, $bang_cap);
        $stmt->execute();

        echo "<script>alert('Thêm nhân viên thành công!');</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra: " . $stmt->error . "');</script>";
    }
}

// Sửa thông tin nhân viên
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_employee'])) {
    $id = $_POST['id'];
    $ten = $_POST['ten'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $dia_chi = $_POST['dia_chi'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $chuyen_mon = $_POST['chuyen_mon'];
    $ton_giao = $_POST['ton_giao'];
    $quoc_tich = $_POST['quoc_tich'];
    $tinh_trang_hon_nhan = $_POST['tinh_trang_hon_nhan'];
    $trang_thai = $_POST['trang_thai'];
    $bang_cap = $_POST['bang_cap'];
    $phong_ban_id = $_POST['phong_ban_id'];

    // Cập nhật thông tin nhân viên
    $stmt = $conn->prepare("UPDATE bang_nhan_vien SET ten=?, gioi_tinh=?, ngay_sinh=?, dia_chi=?, sdt=?, email=?, chuyen_mon=?, ton_giao=?, quoc_tich=?, tinh_trang_hon_nhan=?, trang_thai=?, phong_ban_id=? WHERE id=?");
    $stmt->bind_param("ssssssssssssi", $ten, $gioi_tinh, $ngay_sinh, $dia_chi, $sdt, $email, $chuyen_mon, $ton_giao, $quoc_tich, $tinh_trang_hon_nhan, $trang_thai, $phong_ban_id, $id);
    
    if ($stmt->execute()) {
        // Cập nhật bảng bằng cấp
        $stmt = $conn->prepare("UPDATE bang_bang_cap SET bang_cap=? WHERE employee_id=?");
        $stmt->bind_param("si", $bang_cap, $id);
        $stmt->execute();

        echo "<script>alert('Cập nhật nhân viên thành công!');</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra: " . $stmt->error . "');</script>";
    }
}

// Xóa nhân viên
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_employee'])) {
    $id = $_POST['id'];
    
    // Xóa nhân viên từ bảng
    $stmt = $conn->prepare("DELETE FROM bang_nhan_vien WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Xóa nhân viên thành công!');</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra: " . $stmt->error . "');</script>";
    }
}

// Lấy danh sách nhân viên
$query = "SELECT nv.id, nv.ten, nv.gioi_tinh, nv.ngay_sinh, nv.dia_chi, nv.sdt, nv.email, nv.chuyen_mon, nv.ton_giao, nv.quoc_tich, nv.tinh_trang_hon_nhan, nv.trang_thai, bc.bang_cap, nv.phong_ban_id, pb.ten_phong_ban 
          FROM bang_nhan_vien nv 
          LEFT JOIN bang_bang_cap bc ON nv.id = bc.employee_id 
          LEFT JOIN bang_phong_ban pb ON nv.phong_ban_id = pb.id";
$result = $conn->query($query);
?>

<?php include 'layout/header.php'; ?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Danh Sách Nhân Viên và Bằng Cấp</h4>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addEmployeeModal">Thêm Nhân Viên</button>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Nhân Viên</th>
                        <th>Tên Nhân Viên</th>
                        <th>Bằng Cấp</th>
                        
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['ten']; ?></td>
                            <td><?php echo $row['bang_cap'] ?? 'Chưa có'; ?></td>
                            
                            <td>
                                <button class="btn btn-warning edit-btn" 
                                        data-id="<?php echo $row['id']; ?>" 
                                        data-name="<?php echo $row['ten']; ?>" 
                                        data-gioitinh="<?php echo $row['gioi_tinh']; ?>"
                                        data-ngaysinh="<?php echo $row['ngay_sinh']; ?>"
                                        data-diachi="<?php echo $row['dia_chi']; ?>"
                                        data-sdt="<?php echo $row['sdt']; ?>"
                                        data-email="<?php echo $row['email']; ?>"
                                        data-chuyenmon="<?php echo $row['chuyen_mon']; ?>"
                                        data-tongiao="<?php echo $row['ton_giao']; ?>"
                                        data-quocgia="<?php echo $row['quoc_tich']; ?>"
                                        data-trangthai="<?php echo $row['trang_thai']; ?>"
                                        data-bangcap="<?php echo $row['bang_cap']; ?>"
                                        data-phongban="<?php echo $row['phong_ban_id']; ?>"
                                        data-toggle="modal" 
                                        data-target="#editEmployeeModal">Sửa</button>
                                <button class="btn btn-danger delete-btn" data-id="<?php echo $row['id']; ?>">Xóa</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm Nhân Viên -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Thêm Nhân Viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ten">Tên Nhân Viên</label>
                        <input type="text" class="form-control" name="ten" required>
                    </div>
                    <div class="form-group">
                        <label for="gioi_tinh">Giới Tính</label>
                        <select class="form-control" name="gioi_tinh" required>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ngay_sinh">Ngày Sinh</label>
                        <input type="date" class="form-control" name="ngay_sinh" required>
                    </div>
                    <div class="form-group">
                        <label for="dia_chi">Địa Chỉ</label>
                        <input type="text" class="form-control" name="dia_chi" required>
                    </div>
                    <div class="form-group">
                        <label for="sdt">Số Điện Thoại</label>
                        <input type="text" class="form-control" name="sdt" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="chuyen_mon">Chuyên Môn</label>
                        <input type="text" class="form-control" name="chuyen_mon" required>
                    </div>
                    <div class="form-group">
                        <label for="ton_giao">Tôn Giáo</label>
                        <input type="text" class="form-control" name="ton_giao">
                    </div>
                    <div class="form-group">
                        <label for="quoc_tich">Quốc Tịch</label>
                        <input type="text" class="form-control" name="quoc_tich" required>
                    </div>
                    <div class="form-group">
                        <label for="tinh_trang_hon_nhan">Tình Trạng Hôn Nhân</label>
                        <input type="text" class="form-control" name="tinh_trang_hon_nhan">
                    </div>
                    <div class="form-group">
                        <label for="trang_thai">Trạng Thái</label>
                        <input type="text" class="form-control" name="trang_thai" required>
                    </div>
                    <div class="form-group">
                        <label for="bang_cap">Bằng Cấp</label>
                        <input type="text" class="form-control" name="bang_cap" required>
                    </div>
                    <div class="form-group">
                        <label for="phong_ban_id">Phòng Ban</label>
                        <select class="form-control" name="phong_ban_id" required>
                            <?php while ($pb = $phong_ban_result->fetch_assoc()): ?>
                                <option value="<?php echo $pb['id']; ?>"><?php echo $pb['ten_phong_ban']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" name="add_employee">Thêm Nhân Viên</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Nhân Viên -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeModalLabel">Sửa Nhân Viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label for="ten">Tên Nhân Viên</label>
                        <input type="text" class="form-control" name="ten" id="edit-ten" required>
                    </div>
                    <div class="form-group">
                        <label for="gioi_tinh">Giới Tính</label>
                        <select class="form-control" name="gioi_tinh" id="edit-gioi_tinh" required>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ngay_sinh">Ngày Sinh</label>
                        <input type="date" class="form-control" name="ngay_sinh" id="edit-ngay_sinh" required>
                    </div>
                    <div class="form-group">
                        <label for="dia_chi">Địa Chỉ</label>
                        <input type="text" class="form-control" name="dia_chi" id="edit-dia_chi" required>
                    </div>
                    <div class="form-group">
                        <label for="sdt">Số Điện Thoại</label>
                        <input type="text" class="form-control" name="sdt" id="edit-sdt" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="edit-email" required>
                    </div>
                    <div class="form-group">
                        <label for="chuyen_mon">Chuyên Môn</label>
                        <input type="text" class="form-control" name="chuyen_mon" id="edit-chuyen_mon" required>
                    </div>
                    <div class="form-group">
                        <label for="ton_giao">Tôn Giáo</label>
                        <input type="text" class="form-control" name="ton_giao" id="edit-ton_giao">
                    </div>
                    <div class="form-group">
                        <label for="quoc_tich">Quốc Tịch</label>
                        <input type="text" class="form-control" name="quoc_tich" id="edit-quoc_tich" required>
                    </div>
                    <div class="form-group">
                        <label for="tinh_trang_hon_nhan">Tình Trạng Hôn Nhân</label>
                        <input type="text" class="form-control" name="tinh_trang_hon_nhan" id="edit-tinh_trang_hon_nhan">
                    </div>
                    <div class="form-group">
                        <label for="trang_thai">Trạng Thái</label>
                        <input type="text" class="form-control" name="trang_thai" id="edit-trang_thai" required>
                    </div>
                    <div class="form-group">
                        <label for="bang_cap">Bằng Cấp</label>
                        <input type="text" class="form-control" name="bang_cap" id="edit-bang_cap" required>
                    </div>
                    <div class="form-group">
                        <label for="phong_ban_id">Phòng Ban</label>
                        <select class="form-control" name="phong_ban_id" id="edit-phong_ban_id" required>
                            <?php while ($pb = $phong_ban_result->fetch_assoc()): ?>
                                <option value="<?php echo $pb['id']; ?>"><?php echo $pb['ten_phong_ban']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" name="edit_employee">Cập Nhật Nhân Viên</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.edit-btn', function() {
        $('#edit-id').val($(this).data('id'));
        $('#edit-ten').val($(this).data('name'));
        $('#edit-gioi_tinh').val($(this).data('gioitinh'));
        $('#edit-ngay_sinh').val($(this).data('ngaysinh'));
        $('#edit-dia_chi').val($(this).data('diachi'));
        $('#edit-sdt').val($(this).data('sdt'));
        $('#edit-email').val($(this).data('email'));
        $('#edit-chuyen_mon').val($(this).data('chuyenmon'));
        $('#edit-ton_giao').val($(this).data('tongiao'));
        $('#edit-quoc_tich').val($(this).data('quocgia'));
        $('#edit-tinh_trang_hon_nhan').val($(this).data('trangthai'));
        $('#edit-trang_thai').val($(this).data('trangthai'));
        $('#edit-bang_cap').val($(this).data('bangcap'));
        $('#edit-phong_ban_id').val($(this).data('phongban'));
    });
</script>


<?php 
if (isset($_POST['edit_employee'])) {
    // Lấy dữ liệu từ form
    $id = $_POST['id'];
    $ten = $_POST['ten'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $dia_chi = $_POST['dia_chi'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $chuyen_mon = $_POST['chuyen_mon'];
    $ton_giao = $_POST['ton_giao'];
    $quoc_tich = $_POST['quoc_tich'];
    $tinh_trang_hon_nhan = $_POST['tinh_trang_hon_nhan'];
    $trang_thai = $_POST['trang_thai'];
    $bang_cap = $_POST['bang_cap'];
    $phong_ban_id = $_POST['phong_ban_id'];

    // Cập nhật thông tin nhân viên vào cơ sở dữ liệu
    $sql = "UPDATE nhan_vien SET ten = ?, gioi_tinh = ?, ngay_sinh = ?, dia_chi = ?, sdt = ?, email = ?, chuyen_mon = ?, ton_giao = ?, quoc_tich = ?, tinh_trang_hon_nhan = ?, trang_thai = ?, bang_cap = ?, phong_ban_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $ten, $gioi_tinh, $ngay_sinh, $dia_chi, $sdt, $email, $chuyen_mon, $ton_giao, $quoc_tich, $tinh_trang_hon_nhan, $trang_thai, $bang_cap, $phong_ban_id, $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật nhân viên thành công!');</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật!');</script>";
    }
}
?>

<script>
    $(document).on('click', '.edit-btn', function() {
        $('#edit-id').val($(this).data('id'));
        $('#edit-ten').val($(this).data('name'));
        $('#edit-gioi_tinh').val($(this).data('gioitinh'));
        $('#edit-ngay_sinh').val($(this).data('ngaysinh'));
        $('#edit-dia_chi').val($(this).data('diachi'));
        $('#edit-sdt').val($(this).data('sdt'));
        $('#edit-email').val($(this).data('email'));
        $('#edit-chuyen_mon').val($(this).data('chuyenmon'));
        $('#edit-ton_giao').val($(this).data('tongiao'));
        $('#edit-quoc_tich').val($(this).data('quocgia'));
        $('#edit-tinh_trang_hon_nhan').val($(this).data('trangthai'));
        $('#edit-trang_thai').val($(this).data('trangthai'));
        $('#edit-bang_cap').val($(this).data('bangcap'));
        $('#edit-phong_ban_id').val($(this).data('phongban'));
    });
</script>

<?php 
if (isset($_POST['edit_employee'])) {
    // Lấy dữ liệu từ form
    $id = $_POST['id'];
    $ten = $_POST['ten'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $dia_chi = $_POST['dia_chi'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $chuyen_mon = $_POST['chuyen_mon'];
    $ton_giao = $_POST['ton_giao'];
    $quoc_tich = $_POST['quoc_tich'];
    $tinh_trang_hon_nhan = $_POST['tinh_trang_hon_nhan'];
    $trang_thai = $_POST['trang_thai'];
    $bang_cap = $_POST['bang_cap'];
    $phong_ban_id = $_POST['phong_ban_id'];

    // Cập nhật thông tin nhân viên vào cơ sở dữ liệu
    $sql = "UPDATE nhan_vien SET ten = ?, gioi_tinh = ?, ngay_sinh = ?, dia_chi = ?, sdt = ?, email = ?, chuyen_mon = ?, ton_giao = ?, quoc_tich = ?, tinh_trang_hon_nhan = ?, trang_thai = ?, bang_cap = ?, phong_ban_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $ten, $gioi_tinh, $ngay_sinh, $dia_chi, $sdt, $email, $chuyen_mon, $ton_giao, $quoc_tich, $tinh_trang_hon_nhan, $trang_thai, $bang_cap, $phong_ban_id, $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật nhân viên thành công!');</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật!');</script>";
    }
}
?>


<script>
    $(document).on('click', '.edit-btn', function() {
        $('#edit-id').val($(this).data('id'));
        $('#edit-ten').val($(this).data('name'));
        $('#edit-gioi_tinh').val($(this).data('gioitinh'));
        $('#edit-ngay_sinh').val($(this).data('ngaysinh'));
        $('#edit-dia_chi').val($(this).data('diachi'));
        $('#edit-sdt').val($(this).data('sdt'));
        $('#edit-email').val($(this).data('email'));
        $('#edit-chuyen_mon').val($(this).data('chuyenmon'));
        $('#edit-ton_giao').val($(this).data('tongiao'));
        $('#edit-quoc_tich').val($(this).data('quocgia'));
        $('#edit-tinh_trang_hon_nhan').val($(this).data('trangthai'));
        $('#edit-trang_thai').val($(this).data('trangthai'));
        $('#edit-bang_cap').val($(this).data('bangcap'));
        $('#edit-phong_ban_id').val($(this).data('phongban'));
    });

    $(document).on('click', '.delete-btn', function() {
        var employeeId = $(this).data('id');
        if (confirm('Bạn có chắc chắn muốn xóa nhân viên này không?')) {
            $.post('', { id: employeeId, delete_employee: true }, function(response) {
                location.reload();
            });
        }
 


    // Xóa nhân viên
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        if (confirm('Bạn có chắc chắn muốn xóa nhân viên này không?')) {
            $.post('', { delete_employee: true, id: id }, function() {
                location.reload();
            });
        }
    });
});
</script>

<?php include 'layout/footer.php'; ?>
