-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2024 at 04:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

-- Database: quanlydomain

-- --------------------------------------------------------

-- Table structure for table bang_user
CREATE TABLE IF NOT EXISTS bang_user (
  id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'nhân viên') DEFAULT 'nhân viên',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO bang_user (username, password, role) VALUES
  ('admin', 'admin123', 'admin');

-- Table structure for table bang_anh_nhan_vien
CREATE TABLE bang_anh_nhan_vien (
  id INT NOT NULL AUTO_INCREMENT,
  employee_id INT NOT NULL,
  image_url VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bang_anh_nhan_vien
INSERT INTO bang_anh_nhan_vien (employee_id, image_url) VALUES
  (1, 'path/to/image1.jpg'),
  (2, 'path/to/image2.jpg'),
  (3, 'path/to/image3.jpg'),
  (4, 'path/to/image4.jpg'),
  (5, 'path/to/image5.jpg'),
  (6, 'path/to/image6.jpg'),
  (7, 'path/to/image7.jpg');

-- --------------------------------------------------------

-- Table structure for table bang_bang_cap
CREATE TABLE bang_bang_cap (
  id INT NOT NULL AUTO_INCREMENT,
  employee_id INT NOT NULL,
  bang_cap VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bang_bang_cap
INSERT INTO bang_bang_cap (employee_id, bang_cap) VALUES
(1, 'Cử nhân khoa học máy tính'),
(2, 'Thạc sĩ quản trị kinh doanh'),
(3, 'Cử nhân CNTT'),
(4, 'Kĩ sư CNTT'),
(5, 'Cử nhân khoa học máy tính'),
(6, 'Cử nhân khoa học máy tính'),
(7, 'Cử nhân CNTT'),
(8, 'Cử nhân khoa học máy tính');

-- --------------------------------------------------------

-- Table structure for table bang_chuc_vu
CREATE TABLE bang_chuc_vu (
  id INT NOT NULL AUTO_INCREMENT,
  employee_id INT NOT NULL,
  chuc_vu VARCHAR(255) NOT NULL,
  mo_ta VARCHAR(255) DEFAULT NULL,
  trang_thai ENUM('hoạt động','không hoạt động') DEFAULT 'hoạt động',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bang_chuc_vu
INSERT INTO bang_chuc_vu (employee_id, chuc_vu, mo_ta, trang_thai) VALUES
(1, 'TESTER', 'Quản lý dự án công nghệ thông tin', 'hoạt động'),
(2, 'Quản lý dự án', 'Quản lý dự án công nghệ thông tin', 'hoạt động'),
(3, 'Kỹ sư phần mềm', 'Quản lý dự án công nghệ thông tin', 'hoạt động'),
(4, 'Kỹ sư phần mềm', 'Quản lý dự án công nghệ thông tin', 'hoạt động'),
(5, 'BA', 'Quản lý dự án công nghệ thông tin', 'hoạt động'),
(6, 'TESTER', 'Quản lý dự án công nghệ thông tin', 'hoạt động'),
(7, 'Kỹ sư phần mềm', 'Quản lý dự án công nghệ thông tin', 'hoạt động');

-- --------------------------------------------------------

-- Table structure for table bang_loai_nhan_vien
CREATE TABLE bang_loai_nhan_vien (
  id INT NOT NULL AUTO_INCREMENT,
  employee_id INT NOT NULL,
  loai_nhan_vien VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bang_loai_nhan_vien
INSERT INTO bang_loai_nhan_vien (employee_id, loai_nhan_vien) VALUES
(1, 'Toàn thời gian'),
(2, 'Bán thời gian');

-- --------------------------------------------------------

-- Table structure for table bang_luong
CREATE TABLE bang_luong (
  id INT NOT NULL AUTO_INCREMENT,
  employee_id INT NOT NULL,
  luong_co_ban DECIMAL(10,2) NOT NULL,
  tro_cap DECIMAL(10,2) DEFAULT 0.00,
  thuong DECIMAL(10,2) DEFAULT 0.00,
  phat DECIMAL(10,2) DEFAULT 0.00,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bang_luong
INSERT INTO bang_luong (employee_id, luong_co_ban, tro_cap, thuong, phat) VALUES
(1, 15000000, 5000000, 0, 0),
(2, 25000000, 10000.00, 0, 0),
(3, 15000000, 5000000, 0, 0),
(4, 15000000, 5000000, 0, 0),
(5, 16000000, 7000000, 0, 0),
(6, 13000000, 6000000, 0, 0),
(7, 8000000, 5000000, 0, 0);

-- --------------------------------------------------------

-- Table structure for table bang_mien
CREATE TABLE bang_mien (
  id INT NOT NULL AUTO_INCREMENT,
  ten_mien VARCHAR(255) NOT NULL,
  trang_thai ENUM('hoạt động','không hoạt động') DEFAULT 'hoạt động',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bang_mien
INSERT INTO bang_mien (ten_mien, trang_thai) VALUES
('shopphamhien.com', 'hoạt động');

-- --------------------------------------------------------

-- Table structure for table bang_nhan_vien
CREATE TABLE bang_nhan_vien (
  id INT NOT NULL AUTO_INCREMENT,
  ten VARCHAR(255) NOT NULL,
  gioi_tinh ENUM('nam','nữ','khác') NOT NULL,
  ngay_sinh DATE NOT NULL,
  dia_chi VARCHAR(255) NOT NULL,
  sdt VARCHAR(15) NOT NULL,
  email VARCHAR(255) NOT NULL,
  chuyen_mon VARCHAR(255) NOT NULL,
  ton_giao VARCHAR(255) NOT NULL,
  quoc_tich VARCHAR(255) NOT NULL,
  tinh_trang_hon_nhan ENUM('độc thân','đã kết hôn','ly hôn') NOT NULL,
  trang_thai ENUM('hoạt động','không hoạt động') DEFAULT 'hoạt động',
  phong_ban_id INT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bang_nhan_vien
INSERT INTO bang_nhan_vien (ten, gioi_tinh, ngay_sinh, dia_chi, sdt, email, chuyen_mon, ton_giao, quoc_tich, tinh_trang_hon_nhan, trang_thai, phong_ban_id) VALUES
('Phạm Hùng Dũng', 'nam', '2003-08-12', 'Thanh Hóa', '0123456789', 'dung@mail.com', 'CNTT', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', NULL),
('Trần Hoàng Anh', 'nam', '2003-07-12', 'Thanh Hóa', '0123456789', 'anh@mail.com', 'CNTT', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', NULL),
('Nguyen Thi Anh', 'nữ', '2024-01-08', 'ha loi', '01122335554', 'hanadgas@gmail.com', 'kho hoc may tinh', '', '', 'độc thân', 'hoạt động', NULL),
('Nguyễn Quang Tháng', 'nam', '2003-06-13', 'Ninh Bình', '0123456789', 'thang@mail.com', 'DEV', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 1),
('Mai Quốc Khánh', 'nam', '2004-03-02', 'Thanh Hóa', '0123456789', 'khanh@mail.com', 'DEV', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 3),
('Nguyễn Văn Hoàng', 'nam', '2001-01-08', 'Thanh Hóa', '0123456789', 'hoang@mail.com', 'BA', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 3),
('Nguyễn Minh Anh', 'nữ', '2000-03-01', 'Thanh Hóa', '0123456789', 'minhanh@mail.com', 'TEST', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 2),
('Nguyễn Thị Hông', 'nữ', '2002-08-08', 'Thanh Hóa', '0123456789', 'hong@mail.com', 'DEV', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 1);

-- --------------------------------------------------------

-- Table structure for table bang_phong_ban
CREATE TABLE bang_phong_ban (
  id INT NOT NULL AUTO_INCREMENT,
  ten_phong_ban VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bang_phong_ban
INSERT INTO bang_phong_ban (ten_phong_ban) VALUES
('Phòng Kỹ Thuật'),
('Phòng Kinh Doanh'),
('Phòng Nhân Sự');

-- --------------------------------------------------------

-- Table structure for table domains
CREATE TABLE domains (
  id INT NOT NULL AUTO_INCREMENT,
  domain_name VARCHAR(255) NOT NULL,
  status ENUM('active','inactive') DEFAULT 'active',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table domains
INSERT INTO domains (domain_name, status) VALUES
('sahdjd', 'active'),
('truong con', 'active');

-- Indexes for dumped tables
ALTER TABLE bang_anh_nhan_vien
  ADD KEY employee_id (employee_id);

ALTER TABLE bang_bang_cap
  ADD KEY employee_id (employee_id);

ALTER TABLE bang_chuc_vu
  ADD KEY employee_id (employee_id);

ALTER TABLE bang_loai_nhan_vien
  ADD KEY employee_id (employee_id);

ALTER TABLE bang_luong
  ADD KEY employee_id (employee_id);

ALTER TABLE bang_nhan_vien
  ADD KEY phong_ban_id (phong_ban_id);

-- AUTO_INCREMENT for dumped tables
ALTER TABLE bang_anh_nhan_vien
  MODIFY id INT NOT NULL AUTO_INCREMENT;

ALTER TABLE bang_bang_cap
  MODIFY id INT NOT NULL AUTO_INCREMENT;

ALTER TABLE bang_chuc_vu
  MODIFY id INT NOT NULL AUTO_INCREMENT;

ALTER TABLE bang_loai_nhan_vien
  MODIFY id INT NOT NULL AUTO_INCREMENT;

ALTER TABLE bang_luong
  MODIFY id INT NOT NULL AUTO_INCREMENT;

ALTER TABLE bang_mien
  MODIFY id INT NOT NULL AUTO_INCREMENT;

ALTER TABLE bang_nhan_vien
  MODIFY id INT NOT NULL AUTO_INCREMENT;

ALTER TABLE bang_phong_ban
  MODIFY id INT NOT NULL AUTO_INCREMENT;

ALTER TABLE domains
  MODIFY id INT NOT NULL AUTO_INCREMENT;

-- Constraints for dumped tables
ALTER TABLE bang_anh_nhan_vien
  ADD CONSTRAINT bang_anh_nhan_vien_ibfk_1 FOREIGN KEY (employee_id) REFERENCES bang_nhan_vien (id);

ALTER TABLE bang_bang_cap
  ADD CONSTRAINT bang_bang_cap_ibfk_1 FOREIGN KEY (employee_id) REFERENCES bang_nhan_vien (id) ON DELETE CASCADE;

ALTER TABLE bang_chuc_vu
  ADD CONSTRAINT bang_chuc_vu_ibfk_1 FOREIGN KEY (employee_id) REFERENCES bang_nhan_vien (id) ON DELETE CASCADE;

ALTER TABLE bang_loai_nhan_vien
  ADD CONSTRAINT bang_loai_nhan_vien_ibfk_1 FOREIGN KEY (employee_id) REFERENCES bang_nhan_vien (id) ON DELETE CASCADE;

ALTER TABLE bang_luong
  ADD CONSTRAINT bang_luong_ibfk_1 FOREIGN KEY (employee_id) REFERENCES bang_nhan_vien (id) ON DELETE CASCADE;

ALTER TABLE bang_nhan_vien
  ADD CONSTRAINT bang_nhan_vien_ibfk_1 FOREIGN KEY (phong_ban_id) REFERENCES bang_phong_ban (id) ON DELETE SET NULL;

COMMIT;
