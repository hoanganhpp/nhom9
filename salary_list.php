<?php
session_start();
include 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Lấy danh sách lương cùng với tên nhân viên
$query = "
    SELECT 
        l.id AS salary_id,         
        nv.id AS employee_id,      
        nv.ten AS employee_name, 
        l.luong_co_ban, 
        l.tro_cap, 
        l.thuong, 
        l.phat, 
        nv.trang_thai
    FROM 
        bang_luong l
    JOIN 
        bang_nhan_vien nv ON l.employee_id = nv.id
    ORDER BY 
        nv.id
"; 

$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

include 'layout/header.php';
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Danh Sách Lương</h4>
            <button class="btn btn-success" data-toggle="modal" data-target="#addSalaryModal">Thêm Lương</button>
        </div>
        <div class="card-body">
            <table id="salaryTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Nhân Viên</th>
                        <th>Tên Nhân Viên</th>
                        <th>Lương Cơ Bản</th>
                        <th>Trợ Cấp</th>
                        <th>Thưởng</th>
                        <th>Phạt</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['employee_id']; ?></td>
                            <td><?php echo $row['employee_name']; ?></td>
                            <td><?php echo number_format($row['luong_co_ban'], 2); ?></td>
                            <td><?php echo number_format($row['tro_cap'], 2); ?></td>
                            <td><?php echo number_format($row['thuong'], 2); ?></td>
                            <td><?php echo number_format($row['phat'], 2); ?></td>
                            <td>
                                <button class="btn btn-warning edit-btn" 
                                        data-id="<?php echo $row['salary_id']; ?>" 
                                        data-basic-salary="<?php echo $row['luong_co_ban']; ?>" 
                                        data-allowance="<?php echo $row['tro_cap']; ?>" 
                                        data-bonus="<?php echo $row['thuong']; ?>" 
                                        data-penalty="<?php echo $row['phat']; ?>" 
                                        data-toggle="modal" 
                                        data-target="#editSalaryModal">Sửa</button>

                                <button class="btn btn-danger delete-btn" data-id="<?php echo $row['salary_id']; ?>">Xóa</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm Lương -->
<div class="modal fade" id="addSalaryModal" tabindex="-1" aria-labelledby="addSalaryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSalaryModalLabel">Thêm Lương</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addSalaryForm">
                    <div class="form-group">
                        <label for="addEmployeeId">Tên Nhân Viên</label>
                        <select id="addEmployeeId" class="form-control" required>
                            <option value="" disabled selected>Chọn nhân viên</option>
                            <?php
                            $employeeQuery = "SELECT id, ten FROM bang_nhan_vien WHERE trang_thai = 'hoạt động'";
                            $employeeResult = $conn->query($employeeQuery);
                            while ($employee = $employeeResult->fetch_assoc()) {
                                echo "<option value='{$employee['id']}'>{$employee['ten']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="addBasicSalary">Lương Cơ Bản</label>
                        <input type="number" class="form-control" id="addBasicSalary" required>
                    </div>
                    <div class="form-group">
                        <label for="addAllowance">Trợ Cấp</label>
                        <input type="number" class="form-control" id="addAllowance" required>
                    </div>
                    <div class="form-group">
                        <label for="addBonus">Thưởng</label>
                        <input type="number" class="form-control" id="addBonus" required>
                    </div>
                    <div class="form-group">
                        <label for="addPenalty">Phạt</label>
                        <input type="number" class="form-control" id="addPenalty" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm Lương</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Xử lý form thêm lương
    $('#addSalaryForm').on('submit', function(event) {
        event.preventDefault();
        const employeeId = $('#addEmployeeId').val();
        const basicSalary = $('#addBasicSalary').val();
        const allowance = $('#addAllowance').val();
        const bonus = $('#addBonus').val();
        const penalty = $('#addPenalty').val();

        $.ajax({
            url: 'add_salary.php',
            type: 'POST',
            data: { 
                employee_id: employeeId, 
                luong_co_ban: basicSalary, 
                tro_cap: allowance, 
                thuong: bonus, 
                phat: penalty 
            },
            success: function(response) {
                const result = JSON.parse(response);
                alert(result.message);
                if (result.success) {
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });

    // Xóa lương
    $('.delete-btn').click(function() {
        const salaryId = $(this).data('id');
        if (confirm('Bạn có chắc chắn muốn xóa bản ghi lương này?')) {
            $.ajax({
                url: 'delete_salary.php',
                type: 'POST',
                data: { id: salaryId },
                success: function(response) {
                    const result = JSON.parse(response);
                    alert(result.message);
                    if (result.success) {
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        }
    });
});
</script>

<?php include 'layout/footer.php'; ?>