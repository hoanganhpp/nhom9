<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $sql = "SELECT status FROM domains WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $new_status = ($row['status'] == 'active') ? 'inactive' : 'active';

    $sql = "UPDATE domains SET status='$new_status' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
