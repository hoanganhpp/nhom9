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
