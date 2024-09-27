<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {

    header('Location: index.php');
    exit;
}

require_once 'config.php';

$error_message = '';
$success_message = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error_message = "Vui lòng điền đầy đủ thông tin.";
    } else {

        $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
        if ($stmt = $conn->prepare($sql)) {

            $stmt->bind_param("ss", $username, $password);

            $stmt->execute();

            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $success_message = true;
            } else {

                $error_message = "Tên đăng nhập hoặc mật khẩu không đúng.";
            }

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
    <title>Login - Admin quản ký nhân viên</title>
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
