<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Người dùng đã đăng nhập, chuyển họ đến trang "admin"
    header('Location: index.php');
    exit;
}

// Bao gồm tệp cấu hình
require_once 'config.php';

// Biến để lưu trữ thông báo lỗi
$error_message = '';
$success_message = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ biểu mẫu đăng nhập
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Kiểm tra xem các trường có rỗng không
    if (empty($username) || empty($password)) {
        $error_message = "Vui lòng điền đầy đủ thông tin.";
    } else {
        // Đối với mục đích bảo mật, bạn nên sử dụng hàm băm để băm mật khẩu và kiểm tra với dữ liệu trong cơ sở dữ liệu.
        
        // Sử dụng prepared statements để tránh SQL injection
        $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
        if ($stmt = $conn->prepare($sql)) {
            // Bind biến với prepared statement như là tham số
            $stmt->bind_param("ss", $username, $password);

            // Thực thi prepared statement
            $stmt->execute();

            // Lưu trữ kết quả
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                // Xác thực thành công
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username; // Lưu tên đăng nhập của người dùng trong phiên
                $success_message = true;
            } else {
                // Xác thực thất bại
                $error_message = "Tên đăng nhập hoặc mật khẩu không đúng.";
            }

            // Đóng statement
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mazer Admin Dashboard</title>
    <!-- Mazer -->
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./assets/compiled/css/auth.css">
    <!-- SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<style>
        html[data-bs-theme=light] body{
            font-family: "Oswald", sans-serif;
        }
        html[data-bs-theme=dark] body{
            font-family: "Oswald", sans-serif;
        }
    </style>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="#"><img src="./assets/compiled/svg/logo.svg" alt="Logo"></a>
                    </div>
                    <h1 class="auth-title">Log in.</h1>

                    <form method="POST">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="username" id="username" placeholder="Username">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" name="password" id="password" placeholder="Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>
                    <?php
                    if (!empty($error_message)) {
                        echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Thông báo",
                                text: "' . $error_message . '",
                            });
                        </script>';
                    }

                    if ($success_message) {
                        echo '<script>
                            Swal.fire({
                                icon: "success",
                                title: "Thông báo",
                                text: "Đăng nhập thành công!",
                                showConfirmButton: true
                            }).then(function() {
                                window.location.href = "index.php";
                            });
                        </script>';
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>
    </div>
</body>

</html>
