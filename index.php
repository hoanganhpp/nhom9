<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Người dùng chưa đăng nhập, chuyển họ đến trang đăng nhập
    header('Location: login.php');
    exit;
}
// Kiểm tra nút Đăng Xuất đã được nhấn
if (isset($_POST['logout'])) {
    // Hủy phiên đăng nhập
    session_destroy();
    header('Location: login.php'); // Chuyển hướng đến trang đăng nhập sau khi đăng xuất
    exit;
}
?>
<?php
include 'layout/header.php';
?>
<div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Domain Management</h4>
                        </div>
                        <div class="card-body">
                        <form id="domainForm" class="mb-3">
        <div class="form-group">
            <label for="domainName">Domain Name</label>
            <input type="text" class="form-control" id="domainName" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Domain</button>
    </form>
    <table id="domainTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Domain Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
    <button id="exportButton" class="btn btn-success mt-3"><i class="fas fa-file-excel"></i> Export to Excel</button>
                        </div>
                    </div>
                </div>
            </div>
            
<div class="modal fade" id="editDomainModal" tabindex="-1" aria-labelledby="editDomainModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDomainModalLabel">Edit Domain</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editDomainForm">
                    <input type="hidden" id="editDomainId">
                    <div class="form-group">
                        <label for="editDomainName">Domain Name</label>
                        <input type="text" class="form-control" id="editDomainName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include 'layout/footer.php';
?>

