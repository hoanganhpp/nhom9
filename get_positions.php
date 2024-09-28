<?php
session_start();
include 'config.php';

$query = "
    SELECT 
        cv.employee_id,
        nv.ten AS employee_name, 
        cv.chuc_vu
    FROM 
        bang_chuc_vu cv
    JOIN 
        bang_nhan_vien nv ON cv.employee_id = nv.id
"; 

$result = $conn->query($query);
$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
