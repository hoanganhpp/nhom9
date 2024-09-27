<script src="assets/static/js/initTheme.js"></script>
<div id="app">
    <div id="sidebar">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="#"><img src="./assets/compiled/svg/logo.svg" alt="Logo"></a>
                    </div>
                    <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                            <!-- SVG content -->
                        </svg>
                        <div class="form-check form-switch fs-6">
                            <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                            <label class="form-check-label"></label>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <!-- SVG content -->
                        </svg>
                    </div>
                    <div class="sidebar-toggler x">
                        <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                    </div>
                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-title">Menu</li>
                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link' onclick="navigateTo('employee_list.php', event)">
                            <i class="bi bi-grid-fill"></i>
                            <span>Danh sách nhân viên</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link' onclick="navigateTo('index.php', event)">
                            <i class="bi bi-grid-fill"></i>
                            <span>Hồ sơ nhân viên</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link' onclick="navigateTo('position_list.php', event)">
                            <i class="bi bi-grid-fill"></i>
                            <span>Chức vụ</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link' onclick="navigateTo('salary_list.php', event)">
                            <i class="bi bi-grid-fill"></i>
                            <span>Lương</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link' onclick="navigateTo('degrees.php', event)">
                            <i class="bi bi-grid-fill"></i>
                            <span>Phòng Ban</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <form method="post" id="logoutForm">
                            <input type="submit" name="logout" value="Log Out" id="logoutButton" style="cursor: pointer;">
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    #sidebar {
        width: 250px; /* Đặt chiều rộng cho sidebar */
        background-color: #fff; /* Màu nền cho sidebar */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Đổ bóng nhẹ */
    }
    .sidebar-menu {
        padding: 0; /* Đảm bảo không có padding */
    }
    .sidebar-item {
        list-style: none; /* Bỏ dấu đầu dòng */
    }
    .sidebar-link {
        display: flex; /* Sử dụng flexbox để căn chỉnh nội dung */
        align-items: center; /* Căn giữa nội dung theo chiều dọc */
        padding: 12px 15px; /* Khoảng cách cho dễ nhấn */
        color: #000; /* Màu chữ */
        text-decoration: none; /* Bỏ gạch chân */
        transition: background-color 0.3s ease, color 0.3s ease; /* Hiệu ứng chuyển đổi màu nền */
        margin: 0; /* Đảm bảo không có margin */
        border-radius: 4px; /* Bo tròn góc */
    }
    .sidebar-link:hover {
        background-color: #e0e0e0; /* Màu nền khi hover */
        color: #000; /* Giữ màu chữ khi hover */
    }
    .sidebar-link.active {
        background-color: #007BFF; /* Màu xanh nước biển khi mục được chọn */
        color: #fff; /* Màu chữ trắng khi mục được chọn */
    }
    /* Thêm một khoảng cách giữa các mục */
    .sidebar-item + .sidebar-item {
        border-top: 1px solid #f0f0f0; /* Thêm đường viền giữa các mục */
    }
</style>


<script>
    // Chức năng chuyển hướng và làm sáng mục đã chọn
    function navigateTo(page, event) {
        event.preventDefault(); // Ngăn chặn hành vi mặc định
        const links = document.querySelectorAll('.sidebar-link');
        links.forEach(link => link.classList.remove('active')); // Xóa trạng thái active
        event.currentTarget.classList.add('active'); // Đánh dấu liên kết đang được chọn
        window.location.href = page; // Chuyển hướng đến trang mới
    }

    // Xử lý đăng xuất
    document.getElementById('logoutButton').addEventListener('click', function(event) {
        event.preventDefault(); // Ngăn không cho trang tải lại
        if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
            document.getElementById('logoutForm').submit(); // Gửi form đăng xuất nếu xác nhận
        }
    });

    // Đánh dấu mục đang được chọn khi tải lại trang
    document.addEventListener('DOMContentLoaded', function() {
        const currentUrl = window.location.pathname.split('/').pop(); // Lấy tên file hiện tại
        const links = document.querySelectorAll('.sidebar-link');

        links.forEach(link => {
            const linkPage = link.getAttribute('onclick').match(/'([^']+)'/)[1]; // Lấy trang từ onclick
            if (linkPage === currentUrl) {
                link.classList.add('active'); // Đánh dấu mục đang được chọn
            }
        });
    });
</script>
