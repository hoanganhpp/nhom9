<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Kiểm tra nút Đăng Xuất đã được nhấn
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

include 'layout/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Hồ sơ nhân viên</h4>
            </div>
            <div class="card-body">
                <table id="employeeTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Nhân Viên</th>
                            <th>Hồ Sơ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dữ liệu sẽ được nạp ở đây bằng Ajax -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Hàm để nạp lại bảng nhân viên
    function loadEmployees() {
        $.ajax({
            url: 'fetch_employees.php',
            type: 'GET',
            success: function(data) {
                $('#employeeTable tbody').html(data);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching employees: ' + error);
            }
        });
    }

    // Tải bảng nhân viên khi trang được tải
    loadEmployees();
});
</script>

<style>
    body {
        background-color: #f8f9fa; /* Màu nền tổng thể nhẹ */
        color: #343a40; /* Màu chữ tối */
        font-family: 'Arial', sans-serif; /* Phông chữ dễ đọc */
    }
    .card {
        border: none; /* Không viền cho thẻ card */
    }
    table {
        width: 100%;
        margin: 20px 0;
        border-collapse: collapse;
    }
    th, td {
        padding: 15px;
        text-align: left;
        border: 1px solid #dee2e6; /* Viền nhẹ cho ô */
        font-size: 16px; /* Kích thước chữ */
    }
    th {
        background-color: #007bff; /* Màu xanh dương cho tiêu đề */
        color: white;
    }
    tr:nth-child(odd) {
        background-color: #e9ecef; /* Màu nền cho hàng lẻ */
    }
    tr:nth-child(even) {
        background-color: #ffffff; /* Màu nền cho hàng chẵn */
    }
    tr:hover {
        background-color: #d1ecf1; /* Màu nền nhẹ cho hàng khi di chuột qua */
    }
    .btn-info {
        background-color: #007bff; /* Nút màu xanh */
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    .btn-info:hover {
        background-color: #0056b3; /* Màu nút khi hover */
    }
</style>

<?php
include 'layout/footer.php';
?>
