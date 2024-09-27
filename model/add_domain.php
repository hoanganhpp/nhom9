<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $domain_name = $_POST['domain_name'];

    // Kiểm tra xem tên miền đã tồn tại hay chưa
    $check_sql = "SELECT * FROM domains WHERE domain_name='$domain_name'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Tên miền đã tồn tại
        echo json_encode(array('status' => 'error', 'message' => 'Domain already exists'));
    } else {
        // Tên miền chưa tồn tại, thực hiện thêm mới
        $sql = "INSERT INTO domains (domain_name) VALUES ('$domain_name')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(array('status' => 'success', 'message' => 'New domain added successfully'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error: ' . $sql . '<br>' . $conn->error));
        }
    }
}

$conn->close();
?>
<?php
session_start();
include 'db_connection.php'; // Kết nối đến cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['domainName'])) {
    $domainName = $_POST['domainName'];

    // Thực hiện truy vấn thêm miền
    $stmt = $conn->prepare("INSERT INTO bang_mien (ten_mien, trang_thai) VALUES (?, 'hoạt động')");
    $stmt->bind_param("s", $domainName);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Domain added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding domain']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>