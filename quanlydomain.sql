-- Tạo bảng miền
CREATE TABLE IF NOT EXISTS `bang_mien` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten_mien` varchar(255) NOT NULL,
  `trang_thai` enum('hoạt động','không hoạt động') DEFAULT 'hoạt động',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng nhân viên
CREATE TABLE IF NOT EXISTS `bang_nhan_vien` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten` varchar(255) NOT NULL,
  `gioi_tinh` enum('nam','nữ','khác') NOT NULL,
  `ngay_sinh` date NOT NULL,
  `dia_chi` varchar(255) NOT NULL,
  `sdt` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `chuyen_mon` varchar(255) NOT NULL,
  `ton_giao` varchar(255) NOT NULL,
  `quoc_tich` varchar(255) NOT NULL,
  `tinh_trang_hon_nhan` enum('độc thân', 'đã kết hôn', 'ly hôn') NOT NULL,
  `trang_thai` enum('hoạt động', 'không hoạt động') DEFAULT 'hoạt động',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng lương
CREATE TABLE IF NOT EXISTS `bang_luong` (
  `employee_id` int NOT NULL,
  `luong_co_ban` decimal(10, 2) NOT NULL,
  `tro_cap` decimal(10, 2) DEFAULT 0.00,
  `thuong` decimal(10, 2) DEFAULT 0.00,
  `phat` decimal(10, 2) DEFAULT 0.00,
  PRIMARY KEY (`employee_id`),
  FOREIGN KEY (`employee_id`) REFERENCES `bang_nhan_vien`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng chức vụ
CREATE TABLE IF NOT EXISTS `bang_chuc_vu` (
  `employee_id` int NOT NULL,
  `chuc_vu` varchar(255) NOT NULL,
  PRIMARY KEY (`employee_id`),
  FOREIGN KEY (`employee_id`) REFERENCES `bang_nhan_vien`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng bằng cấp
CREATE TABLE IF NOT EXISTS `bang_bang_cap` (
  `employee_id` int NOT NULL,
  `bang_cap` varchar(255) NOT NULL,
  PRIMARY KEY (`employee_id`),
  FOREIGN KEY (`employee_id`) REFERENCES `bang_nhan_vien`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng loại nhân viên
CREATE TABLE IF NOT EXISTS `bang_loai_nhan_vien` (
  `employee_id` int NOT NULL,
  `loai_nhan_vien` varchar(255) NOT NULL,
  PRIMARY KEY (`employee_id`),
  FOREIGN KEY (`employee_id`) REFERENCES `bang_nhan_vien`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dữ liệu mẫu cho bảng miền
INSERT INTO `bang_mien` (`ten_mien`, `trang_thai`) VALUES
  ('shopphamhien.com', 'hoạt động');

-- Dữ liệu mẫu cho bảng nhân viên
INSERT INTO `bang_nhan_vien` (`ten`, `gioi_tinh`, `ngay_sinh`, `dia_chi`, `sdt`, `email`, `chuyen_mon`, `ton_giao`, `quoc_tich`, `tinh_trang_hon_nhan`, `trang_thai`) VALUES
  ('Nguyen Van A', 'nam', '1990-01-01', '123 Main St', '0123456789', 'example@mail.com', 'CNTT', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động');

-- Dữ liệu mẫu cho bảng lương
INSERT INTO `bang_luong` (`employee_id`, `luong_co_ban`, `tro_cap`, `thuong`, `phat`) VALUES
  (1, 5000000.00, 1000000.00, 500000.00, 0.00);

-- Dữ liệu mẫu cho bảng chức vụ
INSERT INTO `bang_chuc_vu` (`employee_id`, `chuc_vu`) VALUES
  (1, 'Kỹ sư phần mềm');

-- Dữ liệu mẫu cho bảng bằng cấp
INSERT INTO `bang_bang_cap` (`employee_id`, `bang_cap`) VALUES
  (1, 'Cử nhân khoa học máy tính');

-- Dữ liệu mẫu cho bảng loại nhân viên
INSERT INTO `bang_loai_nhan_vien` (`employee_id`, `loai_nhan_vien`) VALUES
  (1, 'Toàn thời gian');

-- Đặt lại môi trường
/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
