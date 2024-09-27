-- Tạo bảng miền
CREATE TABLE IF NOT EXISTS bang_mien (
  id INT NOT NULL AUTO_INCREMENT,
  ten_mien VARCHAR(255) NOT NULL,
  trang_thai ENUM('hoạt động', 'không hoạt động') DEFAULT 'hoạt động',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng phòng ban
CREATE TABLE IF NOT EXISTS bang_phong_ban (
  id INT NOT NULL AUTO_INCREMENT,
  ten_phong_ban VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng nhân viên
CREATE TABLE IF NOT EXISTS bang_nhan_vien (
  id INT NOT NULL AUTO_INCREMENT,
  ten VARCHAR(255) NOT NULL,
  gioi_tinh ENUM('nam', 'nữ', 'khác') NOT NULL,
  ngay_sinh DATE NOT NULL,
  dia_chi VARCHAR(255) NOT NULL,
  sdt VARCHAR(15) NOT NULL,
  email VARCHAR(255) NOT NULL,
  chuyen_mon VARCHAR(255) NOT NULL,
  ton_giao VARCHAR(255) NOT NULL,
  quoc_tich VARCHAR(255) NOT NULL,
  tinh_trang_hon_nhan ENUM('độc thân', 'đã kết hôn', 'ly hôn') NOT NULL,
  trang_thai ENUM('hoạt động', 'không hoạt động') DEFAULT 'hoạt động',
  phong_ban_id INT,
  PRIMARY KEY (id),
  FOREIGN KEY (phong_ban_id) REFERENCES bang_phong_ban(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng lương
CREATE TABLE IF NOT EXISTS bang_luong (
  employee_id INT NOT NULL,
  luong_co_ban DECIMAL(10, 2) NOT NULL,
  tro_cap DECIMAL(10, 2) DEFAULT 0.00,
  thuong DECIMAL(10, 2) DEFAULT 0.00,
  phat DECIMAL(10, 2) DEFAULT 0.00,
  PRIMARY KEY (employee_id),
  FOREIGN KEY (employee_id) REFERENCES bang_nhan_vien(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng chức vụ
CREATE TABLE IF NOT EXISTS bang_chuc_vu (
  employee_id INT NOT NULL,
  chuc_vu VARCHAR(255) NOT NULL,
  PRIMARY KEY (employee_id),
  FOREIGN KEY (employee_id) REFERENCES bang_nhan_vien(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng bằng cấp
CREATE TABLE IF NOT EXISTS bang_bang_cap (
  employee_id INT NOT NULL,
  bang_cap VARCHAR(255) NOT NULL,
  PRIMARY KEY (employee_id),
  FOREIGN KEY (employee_id) REFERENCES bang_nhan_vien(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng loại nhân viên
CREATE TABLE IF NOT EXISTS bang_loai_nhan_vien (
  employee_id INT NOT NULL,
  loai_nhan_vien VARCHAR(255) NOT NULL,
  PRIMARY KEY (employee_id),
  FOREIGN KEY (employee_id) REFERENCES bang_nhan_vien(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng người dùng
CREATE TABLE IF NOT EXISTS bang_user (
  id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'nhân viên') DEFAULT 'nhân viên',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng ảnh nhân viên
CREATE TABLE IF NOT EXISTS bang_anh_nhan_vien (
  employee_id INT NOT NULL,
  anh VARCHAR(255) NOT NULL,
  PRIMARY KEY (employee_id),
  FOREIGN KEY (employee_id) REFERENCES bang_nhan_vien(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dữ liệu mẫu cho bảng miền
INSERT INTO bang_mien (ten_mien, trang_thai) VALUES
  ('congtyhoanganh', 'hoạt động');

-- Dữ liệu mẫu cho bảng phòng ban
INSERT INTO bang_phong_ban (ten_phong_ban) VALUES
  ('Phòng Kỹ thuật'),
  ('Phòng Nhân sự'),
  ('Phòng Kinh doanh'),
  ('Phòng Marketing');

-- Dữ liệu mẫu cho bảng nhân viên
INSERT INTO bang_nhan_vien (ten, gioi_tinh, ngay_sinh, dia_chi, sdt, email, chuyen_mon, ton_giao, quoc_tich, tinh_trang_hon_nhan, trang_thai, phong_ban_id) VALUES
  ('Phạm Hùng Dũng', 'nam', '2003-08-12', 'Thanh Hóa', '0123456789', 'dung@mail.com', 'TESTER', 'Phật giáo', 'Việt Nam', 'đã kết hôn', 'hoạt động', 1),
  ('Trần Hoàng Anh', 'nam', '2003-07-12', 'Thanh Hóa', '0123456789', 'hoanganh@mail.com', 'BO', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 2),
  ('Nguyễn Quang Tháng', 'nam', '2003-06-13', 'Ninh Bình', '0123456789', 'thang@mail.com', 'DEV', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 1),
  ('Mai Quốc Khánh', 'nam', '2004-03-02', 'Thanh Hóa', '0123456789', 'khanh@mail.com', 'DEV', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 3),
  ('Nguyễn Văn Hoàng', 'nam', '2001-01-08', 'Thanh Hóa', '0123456789', 'hoang@mail.com', 'BA', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 4),
  ('Nguyễn Minh Anh', 'nữ', '2000-03-01', 'Thanh Hóa', '0123456789', 'minhanh@mail.com', 'TEST', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 2),
  ('Nguyễn Thị Hông', 'nữ', '2002-08-08', 'Thanh Hóa', '0123456789', 'hong@mail.com', 'DEV', 'Phật giáo', 'Việt Nam', 'độc thân', 'hoạt động', 1);

-- Dữ liệu mẫu cho bảng lương
INSERT INTO bang_luong (employee_id, luong_co_ban, tro_cap, thuong, phat) VALUES
  (1, 15000000, 5000000, 0, 0),
  (2, 25000000, 10000.00, 0, 0),
  (3, 15000000, 5000000, 0, 0),
  (4, 15000000, 5000000, 0, 0),
  (5, 16000000, 7000000, 0, 0),
  (6, 13000000, 6000000, 0, 0),
  (7, 8000000, 5000000, 0, 0);

-- Dữ liệu mẫu cho bảng chức vụ
INSERT INTO bang_chuc_vu (employee_id, chuc_vu) VALUES
  (1, 'TESTER'),
  (2, 'BO'),
  (3, 'Kỹ sư phần mềm'),
  (4, 'Kỹ sư phần mềm'),
  (5, 'BA'),
  (6, 'TESTER'),
  (7, 'Kỹ sư phần mềm');

-- Dữ liệu mẫu cho bảng bằng cấp
INSERT INTO bang_bang_cap (employee_id, bang_cap) VALUES
  (1, 'Cử nhân CNTT'),
  (2, 'Kĩ sư CNTT'),
  (3, 'Cử nhân khoa học máy tính'),
  (4, 'Cử nhân khoa học máy tính'),
  (5, 'Cử nhân CNTT'),
  (6, 'Cử nhân khoa học máy tính'),
  (7, 'Cử nhân CNTT');

-- Dữ liệu mẫu cho bảng loại nhân viên
INSERT INTO bang_loai_nhan_vien (employee_id, loai_nhan_vien) VALUES
  (1, 'Toàn thời gian'),
  (2, 'Toàn thời gian'),
  (3, 'Toàn thời gian'),
  (4, 'Toàn thời gian'),
  (5, 'Toàn thời gian'),
  (6, 'Toàn thời gian'),
  (7, 'Toàn thời gian');

-- Dữ liệu mẫu cho bảng người dùng
INSERT INTO bang_user (username, password, role) VALUES
  ('admin', 'admin123', 'admin'),
  ('user1', 'user123', 'nhân viên'),
  ('user2', 'user456', 'nhân viên');

-- Dữ liệu mẫu cho bảng ảnh nhân viên
INSERT INTO bang_anh_nhan_vien (employee_id, anh) VALUES
  (1, 'path/to/image1.jpg'),
  (2, 'path/to/image2.jpg'),
  (3, 'path/to/image3.jpg'),
  (4, 'path/to/image4.jpg'),
  (5, 'path/to/image5.jpg'),
  (6, 'path/to/image6.jpg'),
  (7, 'path/to/image7.jpg');