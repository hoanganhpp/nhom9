<?php
session_start();
include 'config.php';

// Xử lý yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $basic_salary = $_POST['luong_co_ban'];
    $allowance = $_POST['tro_cap'];
    $bonus = $_POST['thuong'];
    $penalty = $_POST['phat'];

    // Kiểm tra dữ liệu đầu vào
    if (empty($id) || !is_numeric($basic_salary) || !is_numeric($allowance) || !is_numeric($bonus) || !is_numeric($penalty)) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE bang_luong SET luong_co_ban = ?, tro_cap = ?, thuong = ?, phat = ? WHERE employee_id = ?");
    $stmt->bind_param("ddddi", $basic_salary, $allowance, $bonus, $penalty, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Lương đã được cập nhật']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật lương: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit; // Dừng script sau khi xử lý
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Lương</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<!-- Modal Chỉnh Sửa Lương -->
<div class="modal fade" id="editSalaryModal" tabindex="-1" aria-labelledby="editSalaryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSalaryModalLabel">Chỉnh Sửa Lương</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSalaryForm">
                    <input type="hidden" id="editSalaryId">
                    <div class="form-group">
                        <label for="editBasicSalary">Lương Cơ Bản</label>
                        <input type="number" class="form-control" id="editBasicSalary" required>
                    </div>
                    <div class="form-group">
                        <label for="editAllowance">Trợ Cấp</label>
                        <input type="number" class="form-control" id="editAllowance" required>
                    </div>
                    <div class="form-group">
                        <label for="editBonus">Thưởng</label>
                        <input type="number" class="form-control" id="editBonus" required>
                    </div>
                    <div class="form-group">
                        <label for="editPenalty">Phạt</label>
                        <input type="number" class="form-control" id="editPenalty" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Xử lý form chỉnh sửa
    $('#editSalaryForm').on('submit', function(event) {
        event.preventDefault();

        const salaryId = $('#editSalaryId').val();
        const basicSalary = $('#editBasicSalary').val();
        const allowance = $('#editAllowance').val();
        const bonus = $('#editBonus').val();
        const penalty = $('#editPenalty').val();

        // Kiểm tra dữ liệu
        if (!salaryId || !basicSalary || !allowance || !bonus || !penalty) {
            alert("Vui lòng điền đầy đủ thông tin.");
            return;
        }

        $.ajax({
            url: 'edit_salary.php',
            type: 'POST',
            data: { 
                id: salaryId, 
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

    // Xử lý nút chỉnh sửa
    $(document).on('click', '.edit-btn', function() {
        // Lấy dữ liệu từ nút nhấn
        const salaryId = $(this).data('id');
        const basicSalary = $(this).data('basic-salary');
        const allowance = $(this).data('allowance');
        const bonus = $(this).data('bonus');
        const penalty = $(this).data('penalty');

        // Điền dữ liệu vào modal
        $('#editSalaryId').val(salaryId);
        $('#editBasicSalary').val(basicSalary);
        $('#editAllowance').val(allowance);
        $('#editBonus').val(bonus);
        $('#editPenalty').val(penalty);

        // Hiện modal
        $('#editSalaryModal').modal('show');
    });
});
</script>

</body>
</html>
