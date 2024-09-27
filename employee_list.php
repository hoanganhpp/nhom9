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

// Hàm để thêm nhân viên
function addEmployee($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_employee'])) {
        $data = [
            $_POST['ten'],
            $_POST['gioi_tinh'],
            $_POST['ngay_sinh'],
            $_POST['dia_chi'],
            $_POST['sdt'],
            $_POST['email'],
            $_POST['chuyen_mon'],
            $_POST['ton_giao'],
            $_POST['quoc_tich'],
            $_POST['tinh_trang_hon_nhan'],
            $_POST['trang_thai'],
            $_POST['phong_ban_id'],
        ];
        
        // Thêm vào bảng nhân viên
        $stmt = $conn->prepare("INSERT INTO bang_nhan_vien (ten, gioi_tinh, ngay_sinh, dia_chi, sdt, email, chuyen_mon, ton_giao, quoc_tich, tinh_trang_hon_nhan, trang_thai, phong_ban_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssi", ...$data);
        
        if ($stmt->execute()) {
            $employee_id = $stmt->insert_id; // Lấy ID nhân viên vừa thêm
            
            // Thêm vào bảng bằng cấp
            $stmt = $conn->prepare("INSERT INTO bang_bang_cap (employee_id, bang_cap) VALUES (?, ?)");
            $stmt->bind_param("is", $employee_id, $_POST['bang_cap']);
            $stmt->execute();

            echo "<script>alert('Thêm nhân viên thành công!');</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra: " . $stmt->error . "');</script>";
        }
    }
}

// Hàm để sửa thông tin nhân viên
function editEmployee($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_employee'])) {
        $data = [
            $_POST['ten'],
            $_POST['gioi_tinh'],
            $_POST['ngay_sinh'],
            $_POST['dia_chi'],
            $_POST['sdt'],
            $_POST['email'],
            $_POST['chuyen_mon'],
            $_POST['ton_giao'],
            $_POST['quoc_tich'],
            $_POST['tinh_trang_hon_nhan'],
            $_POST['trang_thai'],
            $_POST['phong_ban_id'],
            $_POST['id'],
        ];
        
        // Cập nhật thông tin nhân viên
        $stmt = $conn->prepare("UPDATE bang_nhan_vien SET ten=?, gioi_tinh=?, ngay_sinh=?, dia_chi=?, sdt=?, email=?, chuyen_mon=?, ton_giao=?, quoc_tich=?, tinh_trang_hon_nhan=?, trang_thai=?, phong_ban_id=? WHERE id=?");
        $stmt->bind_param("ssssssssssssi", ...$data);
        
        if ($stmt->execute()) {
            // Cập nhật bảng bằng cấp
            $stmt = $conn->prepare("UPDATE bang_bang_cap SET bang_cap=? WHERE employee_id=?");
            $stmt->bind_param("si", $_POST['bang_cap'], $_POST['id']);
            $stmt->execute();

            echo "<script>alert('Cập nhật nhân viên thành công!');</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra: " . $stmt->error . "');</script>";
        }
    }
}

// Hàm để xóa nhân viên
function deleteEmployee($conn) {
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
}

// Thực thi các hàm
addEmployee($conn);
editEmployee($conn);
deleteEmployee($conn);

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
        <div class="card-header d-flex justify-content-between align-items-center">
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
                        <th>Phòng Ban</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['ten']; ?></td>
                            <td><?php echo $row['bang_cap'] ?? 'Chưa có'; ?></td>
                            <td><?php echo $row['ten_phong_ban']; ?></td>
                            <td>
                                <button class="btn btn-warning edit-btn" data-id="<?php echo $row['id']; ?>" 
                                        data-name="<?php echo $row['ten']; ?>" 
                                        data-gioitinh="<?php echo $row['gioi_tinh']; ?>"
                                        data-ngaysinh="<?php echo $row['ngay_sinh']; ?>"
                                        data-diachi="<?php echo $row['dia_chi']; ?>"
                                        data-sdt="<?php echo $row['sdt']; ?>"
                                        data-email="<?php echo $row['email']; ?>"
                                        data-chuyenmon="<?php echo $row['chuyen_mon']; ?>"
                                        data-tongiao="<?php echo $row['ton_giao']; ?>"
                                        data-quocgia="<?php echo $row['quoc_tich']; ?>"
                                        data-trangthai="<?php echo $row['tinh_trang_hon_nhan']; ?>"
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Thêm Nhân Viên</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="ten">Tên</label>
                            <input type="text" name="ten" class="form-control" placeholder="Tên" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gioi_tinh">Giới Tính</label>
                            <select name="gioi_tinh" class="form-control" required>
                                <option value="">Chọn Giới Tính</option>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="ngay_sinh">Ngày Sinh</label>
                            <input type="date" name="ngay_sinh" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sdt">Số Điện Thoại</label>
                            <input type="text" name="sdt" class="form-control" placeholder="Số Điện Thoại" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="dia_chi">Địa Chỉ</label>
                            <input type="text" name="dia_chi" class="form-control" placeholder="Địa Chỉ" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="chuyen_mon">Chuyên Môn</label>
                            <input type="text" name="chuyen_mon" class="form-control" placeholder="Chuyên Môn" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ton_giao">Tôn Giáo</label>
                            <input type="text" name="ton_giao" class="form-control" placeholder="Tôn Giáo" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="quoc_tich">Quốc Tịch</label>
                            <input type="text" name="quoc_tich" class="form-control" placeholder="Quốc Tịch" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tinh_trang_hon_nhan">Tình Trạng Hôn Nhân</label>
                            <select name="tinh_trang_hon_nhan" class="form-control" required>
                                <option value="">Tình Trạng Hôn Nhân</option>
                                <option value="Độc Thân">Độc Thân</option>
                                <option value="Đã Kết Hôn">Đã Kết Hôn</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="trang_thai">Trạng Thái</label>
                            <select name="trang_thai" class="form-control" required>
                                <option value="">Trạng Thái</option>
                                <option value="Đang Làm">Đang Làm</option>
                                <option value="Nghỉ Việc">Nghỉ Việc</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="bang_cap">Bằng Cấp</label>
                            <input type="text" name="bang_cap" class="form-control" placeholder="Bằng Cấp" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phong_ban_id">Phòng Ban</label>
                        <select name="phong_ban_id" class="form-control" required>
                            <option value="">Chọn Phòng Ban</option>
                            <?php while ($pb = $phong_ban_result->fetch_assoc()): ?>
                                <option value="<?php echo $pb['id']; ?>"><?php echo $pb['ten_phong_ban']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" name="add_employee" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Nhân Viên -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Sửa Nhân Viên</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit-ten">Tên</label>
                            <input type="text" name="ten" id="edit-ten" class="form-control" placeholder="Tên" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-gioi-tinh">Giới Tính</label>
                            <select name="gioi_tinh" id="edit-gioi-tinh" class="form-control" required>
                                <option value="">Chọn Giới Tính</option>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit-ngay-sinh">Ngày Sinh</label>
                            <input type="date" name="ngay_sinh" id="edit-ngay-sinh" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-sdt">Số Điện Thoại</label>
                            <input type="text" name="sdt" id="edit-sdt" class="form-control" placeholder="Số Điện Thoại" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit-dia-chi">Địa Chỉ</label>
                            <input type="text" name="dia_chi" id="edit-dia-chi" class="form-control" placeholder="Địa Chỉ" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-email">Email</label>
                            <input type="email" name="email" id="edit-email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit-chuyen-mon">Chuyên Môn</label>
                            <input type="text" name="chuyen_mon" id="edit-chuyen-mon" class="form-control" placeholder="Chuyên Môn" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-ton-giao">Tôn Giáo</label>
                            <input type="text" name="ton_giao" id="edit-ton-giao" class="form-control" placeholder="Tôn Giáo" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit-quoc-tich">Quốc Tịch</label>
                            <input type="text" name="quoc_tich" id="edit-quoc-tich" class="form-control" placeholder="Quốc Tịch" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-tinh-trang-hon-nhan">Tình Trạng Hôn Nhân</label>
                            <select name="tinh_trang_hon_nhan" id="edit-tinh-trang-hon-nhan" class="form-control" required>
                                <option value="">Tình Trạng Hôn Nhân</option>
                                <option value="Độc Thân">Độc Thân</option>
                                <option value="Đã Kết Hôn">Đã Kết Hôn</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit-trang-thai">Trạng Thái</label>
                            <select name="trang_thai" id="edit-trang-thai" class="form-control" required>
                                <option value="">Trạng Thái</option>
                                <option value="Đang Làm">Đang Làm</option>
                                <option value="Nghỉ Việc">Nghỉ Việc</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-bang-cap">Bằng Cấp</label>
                            <input type="text" name="bang_cap" id="edit-bang-cap" class="form-control" placeholder="Bằng Cấp" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-phong-ban-id">Phòng Ban</label>
                        <select name="phong_ban_id" id="edit-phong-ban-id" class="form-control" required>
                            <option value="">Chọn Phòng Ban</option>
                            <?php $phong_ban_result->data_seek(0); ?>
                            <?php while ($pb = $phong_ban_result->fetch_assoc()): ?>
                                <option value="<?php echo $pb['id']; ?>"><?php echo $pb['ten_phong_ban']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" name="edit_employee" class="btn btn-warning">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .modal-header {
        border-bottom: 2px solid #007bff;
    }

    .modal-footer {
        border-top: 2px solid #007bff;
    }

    .form-control {
        border-radius: 0.5rem;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #007bff;
    }

    .close {
        font-size: 1.5rem;
    }
</style>


<script>
    // Chuyển dữ liệu từ bảng vào modal sửa
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-ten').value = this.dataset.name;
            document.getElementById('edit-gioi-tinh').value = this.dataset.gioitinh;
            document.getElementById('edit-ngay-sinh').value = this.dataset.ngaysinh;
            document.getElementById('edit-dia-chi').value = this.dataset.diachi;
            document.getElementById('edit-sdt').value = this.dataset.sdt;
            document.getElementById('edit-email').value = this.dataset.email;
            document.getElementById('edit-chuyen-mon').value = this.dataset.chuyenmon;
            document.getElementById('edit-ton-giao').value = this.dataset.tongiao;
            document.getElementById('edit-quoc-tich').value = this.dataset.quocgia;
            document.getElementById('edit-tinh-trang-hon-nhan').value = this.dataset.trangthai;
            document.getElementById('edit-bang-cap').value = this.dataset.bangcap;
            document.getElementById('edit-phong-ban-id').value = this.dataset.phongban;
        });
    });

    // Xóa nhân viên
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            if (confirm('Bạn có chắc chắn muốn xóa nhân viên này?')) {
                const id = this.dataset.id;
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="id" value="${id}">
                                  <input type="hidden" name="delete_employee" value="1">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
</script>

<?php include 'layout/footer.php'; ?>
