<?php
include '../config.php';

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Tạo đối tượng Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Thiết lập tiêu đề cho các cột
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Domain Name');
$sheet->setCellValue('C1', 'Status');

// Truy vấn dữ liệu từ cơ sở dữ liệu
$sql = "SELECT * FROM domains";
$result = $conn->query($sql);

// Đổ dữ liệu vào các hàng
$rowIndex = 2; // Bắt đầu từ hàng thứ 2 (hàng 1 là tiêu đề)
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowIndex, $row['id']);
    $sheet->setCellValue('B' . $rowIndex, $row['domain_name']);
    $sheet->setCellValue('C' . $rowIndex, $row['status']);
    $rowIndex++;
}

// Thiết lập header để tải về file Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="domains.xlsx"');
header('Cache-Control: max-age=0');

// Xuất file Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$conn->close();
exit;
?>
