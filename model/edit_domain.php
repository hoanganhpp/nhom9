<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $domain_name = $_POST['domain_name'];

    // Kiểm tra xem tên miền đã tồn tại hay chưa
    $check_sql = "SELECT * FROM domains WHERE domain_name='$domain_name' AND id != $id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Tên miền đã tồn tại ngoài chính nó
        echo json_encode(array('status' => 'error', 'message' => 'Domain already exists'));
    } else {
        // Tên miền không trùng, thực hiện cập nhật
        $sql = "UPDATE domains SET domain_name='$domain_name' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(array('status' => 'success', 'message' => 'Domain updated successfully'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error updating domain: ' . $conn->error));
        }
    }
}

$conn->close();
?>
