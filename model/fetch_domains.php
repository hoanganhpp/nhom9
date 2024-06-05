<?php
include '../config.php';

$sql = "SELECT * FROM domains";
$result = $conn->query($sql);

$data = array();
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(array('data' => $data));

$conn->close();
?>
