-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 29, 2026 at 03:26 AM
-- Server version: 8.4.3
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `english_listening_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `baitest`
--

CREATE TABLE `baitest` (
  `MaBai` varchar(10) NOT NULL,
  `MaBanDo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `MaCD` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `MaCDN` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `TenBai` varchar(20) DEFAULT NULL,
  `TrangThaiBai` varchar(20) DEFAULT NULL,
  `MoTa` varchar(100) DEFAULT NULL,
  `TuKhoaHangDoc` varchar(50) DEFAULT NULL,
  `AnhTroChoi` varchar(255) DEFAULT NULL,
  `SoManhGhep` int DEFAULT NULL,
  `TongSoPhan` int DEFAULT NULL,
  `TongThoiLuong` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `baitest`
--

INSERT INTO `baitest` (`MaBai`, `MaBanDo`, `MaCD`, `MaCDN`, `TenBai`, `TrangThaiBai`, `MoTa`, `TuKhoaHangDoc`, `AnhTroChoi`, `SoManhGhep`, `TongSoPhan`, `TongThoiLuong`) VALUES
('BT01', NULL, 'CD01', 'CDN01', 'Travel Basic', 'Mo', 'Mo ta 1', NULL, NULL, NULL, 2, '55s'),
('BT02', NULL, 'CD02', 'CDN02', 'Food Easy', 'Mo', 'Mo ta 2', NULL, NULL, NULL, 1, '25s'),
('BT03', NULL, 'CD03', 'CDN03', 'School Talk', 'Dong', 'Mo ta 3', NULL, NULL, NULL, NULL, NULL),
('BT04', 'BD04', 'CD04', 'CDN04', 'Office Chat', 'Mo', 'Mo ta 4', NULL, NULL, NULL, NULL, NULL),
('BT05', 'BD05', 'CD05', 'CDN05', 'Health Care', 'Dong', 'Mo ta 5', NULL, NULL, NULL, NULL, NULL),
('BT06', 'BD06', 'CD06', 'CDN06', 'Tech News', 'Mo', 'Mo ta 6', NULL, NULL, NULL, NULL, NULL),
('BT07', 'BD07', 'CD07', 'CDN07', 'Sport Time', 'Mo', 'Mo ta 7', NULL, NULL, NULL, NULL, NULL),
('BT09', 'BD09', 'CD09', 'CDN09', 'Movie World', 'Mo', 'Mo ta 9', NULL, NULL, NULL, NULL, NULL),
('BT10', NULL, 'CD10', 'CDN10', 'Shopping Mall', 'Mo', 'Mo ta 10', NULL, NULL, NULL, NULL, NULL),
('BT11', NULL, 'CD02', 'CDN01', 'Bài Test 1', 'Mo', NULL, NULL, NULL, NULL, 2, '2'),
('BT12', NULL, 'CD03', 'CDN01', 'Bài Test 2', 'Mo', NULL, NULL, NULL, NULL, 2, '2'),
('BT80277042', 'BD01', NULL, NULL, 'Map 1', 'Mo', NULL, 'CODE', NULL, NULL, 1, NULL),
('BT80801077', 'BD02', NULL, NULL, 'Map 2', 'Mo', NULL, 'PLAY', NULL, NULL, 3, NULL),
('BT80882560', 'BD03', NULL, NULL, 'Map 3', 'Mo', NULL, 'SPORTS', NULL, NULL, 0, NULL),
('BT80883815', 'BD04', NULL, NULL, 'Map 4', 'Mo', NULL, 'CARRY', NULL, NULL, NULL, NULL),
('BT81266205', 'BD05', NULL, NULL, 'Map 5', 'Mo', NULL, NULL, NULL, NULL, NULL, NULL),
('BT81267346', 'BD06', NULL, NULL, 'Map 6', 'Mo', NULL, NULL, NULL, NULL, NULL, NULL),
('BT81268659', 'BD07', NULL, NULL, 'Map 7', 'Mo', NULL, NULL, NULL, NULL, NULL, NULL),
('BT81277244', 'BD08', NULL, NULL, 'Map 8', 'Mo', NULL, NULL, 'puzzle_1781277244_c87a9250687e94721123128bdb4e499e.jpg', 4, NULL, NULL),
('BT81348900', 'BD09', NULL, NULL, 'Map 9', 'Mo', NULL, NULL, NULL, NULL, NULL, NULL),
('BT81352296', 'BD10', NULL, NULL, 'Map 10', 'Mo', NULL, NULL, NULL, NULL, NULL, NULL),
('BT81439784', NULL, 'CD02', NULL, 'Food 1', 'Mo', NULL, NULL, NULL, NULL, NULL, NULL),
('BT81612439', NULL, 'CD01', 'CDN01', 'Travel 1', 'Mo', NULL, NULL, NULL, NULL, NULL, NULL),
('BT82218382', NULL, NULL, 'CDN09', 'Test 1', 'Mo', NULL, NULL, NULL, NULL, 4, NULL);

--
-- Triggers `baitest`
--
DELIMITER $$
CREATE TRIGGER `trg_baitest_check` BEFORE INSERT ON `baitest` FOR EACH ROW BEGIN
    IF NEW.MaBanDo IS NULL
       AND NEW.MaCD IS NULL
       AND NEW.MaCDN IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Bai test phai thuoc it nhat 1: BanDo, ChuDe hoac ChuDeNho';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bandophieuluu`
--

CREATE TABLE `bandophieuluu` (
  `MaBanDo` varchar(10) NOT NULL,
  `TenBanDo` varchar(20) DEFAULT NULL,
  `YeuCauBanDo` varchar(20) DEFAULT NULL,
  `TrangThaiBanDo` varchar(20) DEFAULT NULL,
  `HinhAnh` varchar(200) DEFAULT NULL,
  `SoCauHoiBoss` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bandophieuluu`
--

INSERT INTO `bandophieuluu` (`MaBanDo`, `TenBanDo`, `YeuCauBanDo`, `TrangThaiBanDo`, `HinhAnh`, `SoCauHoiBoss`) VALUES
('BD01', 'Thành Phố', '0', 'Mo khoa', 'Thành Phố.png', NULL),
('BD02', 'Suối Nguồn', '100', 'Mo khoa', 'Suoi Nguon.png', NULL),
('BD03', 'Sa Mạc', '200', 'Khoa', 'Sa Mac.png', NULL),
('BD04', 'Muôn Thú', '300', 'Khoa', 'Muon Thu.png', NULL),
('BD05', 'Dung Nham', '400', 'Khoa', 'Dung Nham.png', NULL),
('BD06', 'Đất Đá', '500', 'Khoa', 'Dat Da.png', NULL),
('BD07', 'Cây Xanh', '600', 'Khoa', 'Cay Xanh.png', NULL),
('BD08', 'Cái Chết', '700', 'Khoa', 'Cai Chet.png', NULL),
('BD09', 'Biển Đảo', '800', 'Khoa', 'Bien Dao.png', NULL),
('BD10', 'Băng Giá', '900', 'Khoa', 'Bang Gia.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `capdonghe`
--

CREATE TABLE `capdonghe` (
  `MaCDN` varchar(10) NOT NULL,
  `TenCDN` varchar(20) DEFAULT NULL,
  `MoTaCDN` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `capdonghe`
--

INSERT INTO `capdonghe` (`MaCDN`, `TenCDN`, `MoTaCDN`) VALUES
('CDN01', 'Nghe Cơ Bản', 'Nghe co ban'),
('CDN02', 'Nghe Đơn Giản', 'Nghe don gian'),
('CDN03', 'Nghe Trung Bình', 'Nghe trung binh'),
('CDN04', 'Nghe Hội Thoại', 'Nghe hoi thoai'),
('CDN05', 'Nghe Nâng Cao', 'Nghe nang cao'),
('CDN06', 'Nghe Học Thuật', 'Nghe hoc thuat'),
('CDN07', 'Nghe Chuyên Sâu', 'Nghe chuyen sau'),
('CDN08', 'B2', 'Nghe B2'),
('CDN09', 'B1', 'Nghe B1'),
('CDN10', 'A2', 'Nghe A2'),
('LV923tRM', 'A1', 'Nghe A1');

-- --------------------------------------------------------

--
-- Table structure for table `cauhoi`
--

CREATE TABLE `cauhoi` (
  `MaCauHoi` varchar(10) NOT NULL,
  `MaLoai` varchar(10) NOT NULL,
  `NDCauHoi` varchar(2000) DEFAULT NULL,
  `ViTriGiao` int NOT NULL DEFAULT '0',
  `TrangThaiCauHoi` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cauhoi`
--

INSERT INTO `cauhoi` (`MaCauHoi`, `MaLoai`, `NDCauHoi`, `ViTriGiao`, `TrangThaiCauHoi`) VALUES
('CH01', 'L01', '...One, A ... by the ...', 0, 'Active'),
('CH02', 'L01', 'Where do you live?', 0, 'Active'),
('CH03', 'L01', 'How old are you?', 0, 'Active'),
('CH04', 'L01', 'What time is it?', 0, 'Active'),
('CH05', 'L01', 'Do you like music?', 0, 'Active'),
('CH06', 'L01', 'Can you swim?', 0, 'Active'),
('CH07', 'L01', 'What is your hobby?', 0, 'Active'),
('CH08', 'L01', 'Where is the station?', 0, 'Active'),
('CH09', 'L01', 'How much is this?', 0, 'Active'),
('CH10', 'L01', 'What did you eat?', 0, 'Active'),
('CH1256', 'L01', 'What animal do you hear?', 0, 'Active'),
('CH1290', 'L01', 'Con Cừu', 0, 'Active'),
('CH1372', 'L01', 'Sắp xếp lại thành câu hoàn chỉnh', 0, 'Active'),
('CH1693', 'L01', 'What word do you hear?', 0, 'Active'),
('CH2205', 'L01', 'Sắp xếp lại thành câu hoàn chỉnh', 0, 'Active'),
('CH2440', 'L01', 'Where do you live?', 0, 'Active'),
('CH2643', 'L01', 'Where do you live?', 0, 'Active'),
('CH2812', 'L01', 'What word do you hear?', 0, 'Active'),
('CH3012', 'L01', 'Tom\'s favorite sport is football.', 0, 'Active'),
('CH3013', 'L01', 'Động Vật', 0, 'Active'),
('CH3598', 'L01', 'What word do you hear?', 2, 'Active'),
('CH4147', 'L01', 'Sắp xếp lại thành câu hoàn chỉnh', 0, 'Active'),
('CH4437', 'L01', 'Bãi Biển', 0, 'Active'),
('CH4496', 'L01', 'Anna has a cat named Mimi.', 0, 'Active'),
('CH4592', 'L01', 'Which dish did Mark cook in the competition?', 0, 'Active'),
('CH4703', 'L01', 'Where is the girl\'s hat?', 0, 'Active'),
('CH4779', 'L01', 'Sắp xếp lại thành câu hoàn chỉnh', 0, 'Active'),
('CH4792', 'L01', 'What word do you hear?', 4, 'Active'),
('CH4946', 'L01', 'Where is the girl\'s book now?', 0, 'Active'),
('CH5162', 'L01', 'Where do you live?', 0, 'Active'),
('CH5768', 'L01', 'What fruit do you hear?', 0, 'Active'),
('CH5798', 'L01', 'Sách', 0, 'Active'),
('CH6390', 'L01', 'What word do you hear?', 0, 'Active'),
('CH6774', 'L01', 'What word do you hear?', 1, 'Active'),
('CH6789', 'L01', 'Điền từ còn thiếu', 0, 'Active'),
('CH6855', 'L01', 'Which day is the carnival taking place this year?', 0, 'Active'),
('CH7171', 'L01', 'What word do you hear?', 0, 'Active'),
('CH7301', 'L01', 'What fruit do you hear?', 0, 'Active'),
('CH7324', 'L01', 'What word do you hear?', 0, 'Active'),
('CH7566', 'L01', 'Who lives with Josh in his house?', 0, 'Active'),
('CH8547', 'L01', 'What word do you hear?', 1, 'Active'),
('CH8548', 'L01', 'What word do you hear?', 0, 'Active'),
('CH8714', 'L01', 'What word do you hear?', 0, 'Active'),
('CH8823', 'L01', 'What word do you hear?', 0, 'Active'),
('CH8937', 'L01', 'Who is probably talking on the phone?', 0, 'Active'),
('CH9203', 'L01', 'Sarah leaves home after her school starts.', 0, 'Active'),
('CH9533', 'L01', 'Con khỉ', 0, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `chitietlambai`
--

CREATE TABLE `chitietlambai` (
  `MaCTLB` varchar(10) NOT NULL,
  `UserID` varchar(10) NOT NULL,
  `SoLanLam` int DEFAULT NULL,
  `MaBai` varchar(10) DEFAULT NULL,
  `ThoiGianLam` int DEFAULT NULL COMMENT 'Thời gian làm bài tính bằng giây',
  `SoCauDung` int DEFAULT NULL,
  `TongSoCau` int DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chitietlambai`
--

INSERT INTO `chitietlambai` (`MaCTLB`, `UserID`, `SoLanLam`, `MaBai`, `ThoiGianLam`, `SoCauDung`, `TongSoCau`, `CreatedAt`) VALUES
('CT01', 'U01', 1, NULL, NULL, NULL, NULL, '2026-05-24 14:45:37'),
('CT02', 'U02', 2, NULL, NULL, NULL, NULL, '2026-05-24 14:45:37'),
('CT03', 'U03', 3, NULL, NULL, NULL, NULL, '2026-05-24 14:45:37'),
('CT04', 'U04', 1, NULL, NULL, NULL, NULL, '2026-05-24 14:45:37'),
('CT05', 'U05', 4, NULL, NULL, NULL, NULL, '2026-05-24 14:45:37'),
('CT06', 'U06', 2, NULL, NULL, NULL, NULL, '2026-05-24 14:45:37'),
('CT07', 'U07', 5, NULL, NULL, NULL, NULL, '2026-05-24 14:45:37'),
('CT08', 'U08', 3, NULL, NULL, NULL, NULL, '2026-05-24 14:45:37'),
('CT09', 'U09', 6, NULL, NULL, NULL, NULL, '2026-05-24 14:45:37'),
('CT10', 'U10', 2, NULL, NULL, NULL, NULL, '2026-05-24 14:45:37'),
('CTAFUIZXBS', 'U02', 1, 'BT82218382', 48, 0, 3, '2026-06-23 12:52:20'),
('CTAS0IMSNP', 'U01', 1, 'BT02', 5, 2, 2, '2026-05-24 14:57:24'),
('CTJHMUP54P', 'U02', 1, 'BT01', 12, 4, 4, '2026-06-24 14:44:29'),
('CTJWDVKE1O', 'U01', 1, 'BT81612439', 11, 1, 1, '2026-06-16 12:23:50'),
('CTLB177963', 'U01', 1, 'BT01', 11, 0, 3, '2026-05-24 14:48:03'),
('CTP3EAGRQ5', 'U02', 1, 'BT82218382', 31, 3, 4, '2026-06-23 13:19:57'),
('CTSQZUCQAI', 'U01', 1, 'BT11', 6, 0, 0, '2026-06-14 10:04:57'),
('CTTFL6YJSG', 'U02', 1, 'BT82218382', 31, 2, 4, '2026-06-23 15:01:00'),
('CTUIO1RZTV', 'U01', 1, 'BT01', 18, 4, 4, '2026-06-01 01:01:28'),
('CTXD5RS9FC', 'U01', 1, 'BT01', 28, 4, 4, '2026-06-15 01:37:30');

-- --------------------------------------------------------

--
-- Table structure for table `choi`
--

CREATE TABLE `choi` (
  `MaBanDo` varchar(10) NOT NULL,
  `MaCTLB` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `choi`
--

INSERT INTO `choi` (`MaBanDo`, `MaCTLB`) VALUES
('BD01', 'CT01'),
('BD02', 'CT02'),
('BD03', 'CT03'),
('BD04', 'CT04'),
('BD05', 'CT05'),
('BD06', 'CT06'),
('BD07', 'CT07'),
('BD08', 'CT08'),
('BD09', 'CT09'),
('BD10', 'CT10');

-- --------------------------------------------------------

--
-- Table structure for table `chude`
--

CREATE TABLE `chude` (
  `MaCD` varchar(10) NOT NULL,
  `TenCD` varchar(20) DEFAULT NULL,
  `MoTa` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chude`
--

INSERT INTO `chude` (`MaCD`, `TenCD`, `MoTa`) VALUES
('CD01', 'Du Lịch', 'Du Lịch'),
('CD02', 'Đồ Ăn', 'Do An'),
('CD03', 'Trường Học', 'Truong Hoc'),
('CD04', 'Công Việc', 'Chu de cong viec'),
('CD05', 'Sức Khỏe', 'Chu de suc khoe'),
('CD06', 'Công Nghệ', 'Chu de cong nghe'),
('CD07', 'Thể Thao', 'Chu de the thao'),
('CD08', 'Âm Nhạc', 'Chu de am nhac'),
('CD09', 'Phim Ảnh', 'Chu de phim anh'),
('CD10', 'Mua Sắm', 'Chu de mua sam'),
('CD174nTF', 'Giao Tiếp', 'Giao Tiếp');

-- --------------------------------------------------------

--
-- Table structure for table `ket qua`
--

CREATE TABLE `ket qua` (
  `MaCTLB` varchar(10) NOT NULL,
  `MaCauHoi` varchar(10) NOT NULL,
  `KetQuaChon` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ket qua`
--

INSERT INTO `ket qua` (`MaCTLB`, `MaCauHoi`, `KetQuaChon`) VALUES
('CT01', 'CH01', 'Dung'),
('CT02', 'CH02', 'Dung'),
('CT03', 'CH03', 'Sai'),
('CT04', 'CH04', 'Dung'),
('CT05', 'CH05', 'Dung'),
('CT06', 'CH06', 'Sai'),
('CT07', 'CH07', 'Dung'),
('CT08', 'CH08', 'Dung'),
('CT09', 'CH09', 'Sai'),
('CT10', 'CH10', 'Dung'),
('CTAFUIZXBS', 'CH4592', 'PA6556'),
('CTAFUIZXBS', 'CH4703', 'PA1747'),
('CTAFUIZXBS', 'CH4946', 'PA2584'),
('CTAS0IMSNP', 'CH05', 'PA05'),
('CTAS0IMSNP', 'CH06', 'PA06'),
('CTJHMUP54P', 'CH01', 'PA01'),
('CTJHMUP54P', 'CH03', 'PA03'),
('CTJHMUP54P', 'CH04', 'PA04'),
('CTJHMUP54P', 'CH2440', 'PA2308'),
('CTJWDVKE1O', 'CH6789', 'PA6185'),
('CTLB177963', 'CH01', 'PA01'),
('CTLB177963', 'CH03', 'PA03'),
('CTLB177963', 'CH04', 'PA04'),
('CTP3EAGRQ5', 'CH4592', 'PA9346'),
('CTP3EAGRQ5', 'CH4703', 'PA1747'),
('CTP3EAGRQ5', 'CH4946', 'PA5366'),
('CTP3EAGRQ5', 'CH7566', 'PA6602'),
('CTTFL6YJSG', 'CH4592', 'PA9170'),
('CTTFL6YJSG', 'CH4703', 'PA1941'),
('CTTFL6YJSG', 'CH4946', 'PA9801'),
('CTTFL6YJSG', 'CH7566', 'PA6602'),
('CTUIO1RZTV', 'CH01', 'PA01'),
('CTUIO1RZTV', 'CH03', 'PA03'),
('CTUIO1RZTV', 'CH04', 'PA04'),
('CTUIO1RZTV', 'CH2440', 'PA2308'),
('CTXD5RS9FC', 'CH01', 'PA01'),
('CTXD5RS9FC', 'CH03', 'PA03'),
('CTXD5RS9FC', 'CH04', 'PA04'),
('CTXD5RS9FC', 'CH2440', 'PA2308');

-- --------------------------------------------------------

--
-- Table structure for table `loaicauhoi`
--

CREATE TABLE `loaicauhoi` (
  `MaLoai` varchar(10) NOT NULL,
  `TenLoai` varchar(20) DEFAULT NULL,
  `MoTaLoai` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `loaicauhoi`
--

INSERT INTO `loaicauhoi` (`MaLoai`, `TenLoai`, `MoTaLoai`) VALUES
('L01', 'Trac nghiem', 'Chon dap an dung'),
('L02', 'Dien tu', 'Nhap dap an'),
('L03', 'Nghe va chon', 'Nghe audio'),
('L04', 'Sap xep', 'Sap xep cau'),
('L05', 'Dung sai', 'Kiem tra dung sai'),
('L06', 'Noi', 'Tra loi bang giong noi'),
('L07', 'Doc hieu', 'Doc va tra loi'),
('L08', 'Nghe hieu', 'Nghe va hieu'),
('L09', 'Ghep doi', 'Noi thong tin'),
('L10', 'Mini game', 'Dang game');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2024_01_01_000000_create_users_table', 1),
(3, '2024_01_01_000001_create_categories_table', 1),
(4, '2024_01_01_000002_create_theses_table', 1),
(5, '2024_01_01_000003_create_documents_table', 1),
(6, '2024_01_01_000004_create_listening_results_table', 1),
(7, '2026_05_24_214502_add_columns_to_chitietlambai_table', 2),
(8, '2026_05_28_151207_add_diemman_to_user_table', 3),
(9, '2026_06_07_103511_add_crossword_columns_to_tables', 4),
(10, '2026_06_12_220651_add_game_puzzle_fields_to_baitest_table', 5),
(11, '2026_06_13_190754_add_so_cau_hoi_to_bandophieuluu_table', 6),
(12, '2026_06_14_170000_add_accent_and_speed_to_user_table', 7),
(13, '2026_06_14_173000_add_survey_fields_to_user_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `phan`
--

CREATE TABLE `phan` (
  `MaPhan` varchar(10) NOT NULL,
  `MaBai` varchar(10) NOT NULL,
  `MaTep` varchar(10) DEFAULT NULL,
  `TenPhan` varchar(100) DEFAULT NULL,
  `ThuTuPhan` int DEFAULT NULL,
  `SoCauHoi` int DEFAULT NULL,
  `ThoiLuong` varchar(20) DEFAULT NULL,
  `GioiHanPhat` varchar(20) DEFAULT NULL,
  `MoTaPhan` varchar(255) DEFAULT NULL,
  `TrangThaiPhan` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phan`
--

INSERT INTO `phan` (`MaPhan`, `MaBai`, `MaTep`, `TenPhan`, `ThuTuPhan`, `SoCauHoi`, `ThoiLuong`, `GioiHanPhat`, `MoTaPhan`, `TrangThaiPhan`) VALUES
('P01', 'BT01', 'TP01', 'Part 1 - Conversation', 1, 2, '30s', '3 lan', 'Doan hoi thoai du lich', 'Active'),
('P02', 'BT01', 'TP02', 'Part 2 - Airport Announcement', 2, 5, '25s', '3 lan', 'Thong bao tai san bay', 'Active'),
('P03', 'BT02', 'TP03', 'Part 1 - Food Ordering', 1, 10, '40s', '5 lan', 'Hoi thoai goi mon an', 'Active'),
('P10234', 'BT81267346', 'TP473', '1', 1, 1, NULL, NULL, '', 'Active'),
('P15791', 'BT81277244', NULL, '4', 4, 1, NULL, NULL, '', 'Active'),
('P17796', 'BT81267346', 'TP257', '5', 5, 1, NULL, NULL, '', 'Active'),
('P19665', 'BT81277244', 'TP451', '1', 1, 1, NULL, NULL, '', 'Active'),
('P21380', 'BT80801077', 'TP903', '2', 2, 1, NULL, NULL, '', 'Active'),
('P21618', 'BT80277042', 'TP145', '2', 2, 1, NULL, NULL, '', 'Active'),
('P21916', 'BT80883815', 'TP488', '2', 2, 1, NULL, NULL, '', 'Active'),
('P22437', 'BT80277042', 'TP530', '3', 3, 1, NULL, NULL, '', 'Active'),
('P22623', 'BT82218382', 'TP608', '1', 1, 1, NULL, NULL, '', 'Active'),
('P25987', 'BT81348900', 'TP392', '2', 2, 1, NULL, NULL, '', 'Active'),
('P30375', 'BT82218382', 'TP596', '4', 4, 1, NULL, NULL, '', 'Active'),
('P30843', 'BT81348900', 'TP893', '1', 1, 1, NULL, NULL, '', 'Active'),
('P32928', 'BT80883815', 'TP604', '1', 1, 1, NULL, NULL, '', 'Active'),
('P37081', 'BT82218382', 'TP819', '2', 2, 1, NULL, NULL, '', 'Active'),
('P37486', 'BT81352296', NULL, '2', 2, 1, NULL, NULL, '', 'Active'),
('P41785', 'BT81277244', NULL, '2', 2, 1, NULL, NULL, '', 'Active'),
('P42541', 'BT81439784', 'TP829', '1', 1, 0, NULL, NULL, '', 'Active'),
('P42659', 'BT81267346', 'TP996', '3', 3, 1, NULL, NULL, '', 'Active'),
('P43280', 'BT81612439', 'TP670', '1', 1, 1, NULL, NULL, '', 'Active'),
('P44337', 'BT81268659', 'TP824', '1', 1, 1, NULL, NULL, '', 'Active'),
('P44377', 'BT80883815', 'TP693', '3', 3, 1, NULL, NULL, '', 'Active'),
('P44398', 'BT81268659', 'TP314', '2', 2, 1, NULL, NULL, '', 'Active'),
('P51792', 'BT81277244', NULL, '3', 3, 1, NULL, NULL, '', 'Active'),
('P53951', 'BT81352296', NULL, '1', 1, 1, NULL, NULL, '', 'Active'),
('P54531', 'BT81267346', 'TP674', '2', 2, 1, NULL, NULL, '', 'Active'),
('P56590', 'BT82218382', 'TP131', '3', 3, 1, NULL, NULL, '', 'Active'),
('P61223', 'BT80801077', 'TP726', '3', 3, 1, NULL, NULL, '', 'Active'),
('P63990', 'BT81267346', 'TP148', '4', 4, 1, NULL, NULL, '', 'Active'),
('P66599', 'BT81348900', 'TP503', '3', 3, 1, NULL, NULL, '', 'Active'),
('P70928', 'BT81439784', 'TP211', '2', 2, 0, NULL, NULL, '', 'Active'),
('P75691', 'BT10', 'TP926', '1', 1, 1, NULL, NULL, '', 'Active'),
('P77096', 'BT81266205', 'TP253', '2', 2, 1, NULL, NULL, '', 'Active'),
('P78895', 'BT80277042', 'TP958', '1', 1, 1, NULL, NULL, '', 'Active'),
('P79192', 'BT81266205', 'TP655', '1', 1, 1, NULL, NULL, '', 'Active'),
('P81041', 'BT11', NULL, '1', 1, 0, NULL, NULL, '', 'Active'),
('P81670', 'BT80277042', 'TP454', '4', 4, 1, NULL, NULL, '', 'Active'),
('P86460', 'BT80882560', 'TP860', '2', 2, 1, NULL, NULL, '', 'Active'),
('P89385', 'BT80801077', 'TP412', '1', 1, 1, NULL, NULL, '', 'Active'),
('P94565', 'BT80882560', 'TP374', '1', 1, 1, NULL, NULL, '', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `phan_cauhoi`
--

CREATE TABLE `phan_cauhoi` (
  `MaPhan` varchar(10) NOT NULL,
  `MaCauHoi` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phan_cauhoi`
--

INSERT INTO `phan_cauhoi` (`MaPhan`, `MaCauHoi`) VALUES
('P01', 'CH01'),
('P02', 'CH03'),
('P02', 'CH04'),
('P03', 'CH05'),
('P03', 'CH06'),
('P15791', 'CH1256'),
('P42659', 'CH1290'),
('P44337', 'CH1372'),
('P44377', 'CH1693'),
('P44398', 'CH2205'),
('P01', 'CH2440'),
('P53951', 'CH2643'),
('P22437', 'CH2812'),
('P25987', 'CH3012'),
('P54531', 'CH3013'),
('P21618', 'CH3598'),
('P94565', 'CH4147'),
('P10234', 'CH4437'),
('P30843', 'CH4496'),
('P37081', 'CH4592'),
('P22623', 'CH4703'),
('P86460', 'CH4779'),
('P81670', 'CH4792'),
('P56590', 'CH4946'),
('P41785', 'CH5162'),
('P51792', 'CH5768'),
('P17796', 'CH5798'),
('P21916', 'CH6390'),
('P61223', 'CH6774'),
('P43280', 'CH6789'),
('P19665', 'CH6855'),
('P21380', 'CH7171'),
('P37486', 'CH7301'),
('P79192', 'CH7324'),
('P30375', 'CH7566'),
('P32928', 'CH8547'),
('P77096', 'CH8548'),
('P89385', 'CH8714'),
('P78895', 'CH8823'),
('P75691', 'CH8937'),
('P66599', 'CH9203'),
('P63990', 'CH9533');

-- --------------------------------------------------------

--
-- Table structure for table `phuongancauhoi`
--

CREATE TABLE `phuongancauhoi` (
  `MaPA` varchar(10) NOT NULL,
  `MaCauHoi` varchar(10) NOT NULL,
  `NDPA` varchar(2000) DEFAULT NULL,
  `DapAn` varchar(100) DEFAULT NULL,
  `Diem` int DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phuongancauhoi`
--

INSERT INTO `phuongancauhoi` (`MaPA`, `MaCauHoi`, `NDPA`, `DapAn`, `Diem`, `HinhAnh`) VALUES
('PA01', 'CH01', 'A. Unit, Picnic, River', 'Dung', 10, NULL),
('PA03', 'CH03', 'I am 20 years old', 'Dung', 10, NULL),
('PA04', 'CH04', 'It is 7 PM', 'Dung', 10, NULL),
('PA05', 'CH05', 'Yes, I do', 'Dung', 10, NULL),
('PA06', 'CH06', 'Yes, I can', 'Dung', 10, NULL),
('PA07', 'CH07', 'I like football', 'Dung', 10, NULL),
('PA08', 'CH08', '; straight ahead', 'Dung', 10, NULL),
('PA09', 'CH09', 'It is 100 dollars', 'Dung', 10, NULL),
('PA10', 'CH10', 'I ate noodles', 'Dung', 10, NULL),
('PA1013', 'CH6789', 'D. no, sleep', 'Sai', 0, NULL),
('PA11', 'CH01', 'B. One, Picnit, Raiver', 'Sai', 0, NULL),
('PA12', 'CH01', 'C. Quan, Pitnic, River', 'Sai', 0, NULL),
('PA1379', 'CH7324', 'D. Lake', 'Sai', 0, NULL),
('PA1399', 'CH8937', 'D. A real estate agent', 'Sai', 0, NULL),
('PA1435', 'CH9533', 'A. Monkey', 'Dung', 10, NULL),
('PA1488', 'CH3013', 'A. Animal', 'Dung', 10, NULL),
('PA1584', 'CH8548', 'A. Bike', 'Sai', 0, NULL),
('PA1747', 'CH4703', 'B. ', 'Sai', 0, 'options/uQZuVqCXSDSwvT8CYUqImAB8tmlrryQAGGo1UNh7.png'),
('PA1825', 'CH4779', 'A. Which day is the carnival taking place this year ?', 'Dung', 10, NULL),
('PA1941', 'CH4703', 'A. ', 'Dung', 10, 'options/hDb1D1moWEGK788GA6H5iFvURwVfJIcnpfN0RNnI.png'),
('PA2001', 'CH8714', 'A. Quả Táo', 'Dung', 10, NULL),
('PA2106', 'CH6789', 'B. usually, shoping', 'Sai', 0, NULL),
('PA2203', 'CH5768', 'A. Banana', 'Dung', 10, NULL),
('PA2240', 'CH1372', 'A. Where|do|you|live ?', 'Dung', 10, NULL),
('PA2308', 'CH2440', 'A. ', 'Dung', 10, 'options/iHToSdgDUIKLW7edp4Yuf4fiJ8VyVUU8brsvjV06.gif'),
('PA2696', 'CH2643', 'A. I live in Vietnam', 'Dung', 10, NULL),
('PA3363', 'CH9203', 'A. True', 'Sai', 0, NULL),
('PA3694', 'CH9203', 'B. False', 'Dung', 10, NULL),
('PA3854', 'CH7301', 'A. Banana', 'Dung', 10, NULL),
('PA3879', 'CH7324', 'C. Life', 'Sai', 0, NULL),
('PA4023', 'CH4437', 'A. Beach', 'Dung', 10, NULL),
('PA4736', 'CH2643', 'B. A receptionist at the dentist\'s', 'Sai', 0, NULL),
('PA4763', 'CH5162', 'A. I live in Vietnam', 'Dung', 10, NULL),
('PA4791', 'CH8548', 'D. Bite', 'Sai', 0, NULL),
('PA4882', 'CH6774', 'A. Xe Hơi', 'Dung', 10, NULL),
('PA5032', 'CH4496', 'B. False', 'Sai', 0, NULL),
('PA5175', 'CH2205', 'A. What|sports|do|you|like|best ?', 'Dung', 10, NULL),
('PA5347', 'CH7324', 'A. Like', 'Dung', 10, NULL),
('PA5366', 'CH4946', 'A. ', 'Dung', 10, 'options/ajQydDlQZ0Uolo5BcDxvvUSeGso90DAkC0n25XgZ.png'),
('PA5425', 'CH4792', 'A. APPLE', 'Dung', 10, NULL),
('PA5583', 'CH1290', 'A. Sheep', 'Dung', 10, NULL),
('PA5803', 'CH4946', 'B. ', 'Sai', 0, 'options/o7djlMT1FQzdS3zJzhbu6DY7CrFNpRvfSFIDOE71.png'),
('PA5804', 'CH3012', 'A. True', 'Sai', 0, NULL),
('PA5879', 'CH6390', 'A. Monkey', 'Dung', 10, NULL),
('PA5925', 'CH8823', 'A. CAR', 'Dung', 10, NULL),
('PA6001', 'CH4703', 'C. ', 'Sai', 0, 'options/uZ0g1pXhyhXo3UcWqcw1l9GDWlGPeK8ZAdZJflXd.png'),
('PA6185', 'CH6789', 'A. often, travel', 'Dung', 10, NULL),
('PA6326', 'CH5798', 'A. Book', 'Dung', 10, NULL),
('PA6382', 'CH7324', 'B. Light', 'Sai', 0, NULL),
('PA6534', 'CH8548', 'C. By', 'Sai', 0, NULL),
('PA6556', 'CH4592', 'A. ', 'Sai', 0, 'options/qdrHFERJE1IH9Y6I6bZvpyLRpeQUSZSQfnt5TQ41.png'),
('PA6584', 'CH01', 'D. Quan, Pitnic, River, Thanks', NULL, NULL, NULL),
('PA6602', 'CH7566', 'B. ', 'Dung', 10, 'options/kubUh6onZS4pWl7PWLBeVA8CkBs0vTKrwwiBsDWN.png'),
('PA6667', 'CH4496', 'A. True', 'Dung', 10, NULL),
('PA6757', 'CH2440', 'B. ', 'Sai', NULL, 'options/kE9pUuePMDoRq8r1iwIwXNDgz54LFANNQYdWWkTk.png'),
('PA7041', 'CH2643', 'C. A travel agent', 'Sai', 0, NULL),
('PA7297', 'CH3598', 'A. LION', 'Dung', 10, NULL),
('PA7338', 'CH1256', 'A. Animal', 'Dung', 10, NULL),
('PA7500', 'CH8937', 'B. A receptionist at the dentist\'s', 'Dung', 10, NULL),
('PA7619', 'CH6789', 'C. never, fishing', 'Sai', 0, NULL),
('PA7877', 'CH8937', 'A. A hotel receptionist', 'Sai', 0, NULL),
('PA8085', 'CH3012', 'B. False', 'Dung', 10, NULL),
('PA8280', 'CH1693', 'A. Often', 'Dung', 10, NULL),
('PA8519', 'CH4147', 'A. What means of transportation did you hear ?', 'Dung', 10, NULL),
('PA8696', 'CH2812', 'A. DESK', 'Dung', 10, NULL),
('PA8911', 'CH7301', 'B. ORANGE', 'Sai', 0, NULL),
('PA8912', 'CH8548', 'B. Buy', 'Dung', 10, NULL),
('PA9170', 'CH4592', 'B. ', 'Sai', 0, 'options/0Uqz9mo4oE4HOLeC17CB4tw1oURozuatV2NgUoRU.png'),
('PA9183', 'CH8547', 'A. BALL', 'Dung', 10, NULL),
('PA9346', 'CH4592', 'C. ', 'Dung', 10, 'options/VihoZQ0qSiGfXaHEZhP0EhWuBg9xVn2HIVcaneGr.png'),
('PA9420', 'CH7566', 'A. ', 'Sai', 0, 'options/s1UarviI1JO5Ojqw0cCK1NsQ1vzEcrk4X3TEUF8K.png'),
('PA9724', 'CH7566', 'C. ', 'Sai', 0, 'options/2NW2vBGCoEaKHZha02Ke5SwwGCwct69Gt339i5kh.png'),
('PA9801', 'CH4946', 'C. ', 'Sai', 0, 'options/ARFguNW6itwqs0YoaGg1ZMyFXhdQnn11d8au0LOH.png'),
('PA9901', 'CH8937', 'C. A travel agent', 'Sai', 0, NULL),
('PA9910', 'CH7171', 'A. Sư Tử', 'Dung', 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tepamthanh`
--

CREATE TABLE `tepamthanh` (
  `MaTep` varchar(10) NOT NULL,
  `DuongDan` varchar(100) DEFAULT NULL,
  `TGTep` varchar(50) DEFAULT NULL,
  `GioiHanPhat` varchar(20) DEFAULT NULL,
  `TenTep` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tepamthanh`
--

INSERT INTO `tepamthanh` (`MaTep`, `DuongDan`, `TGTep`, `GioiHanPhat`, `TenTep`) VALUES
('TP01', '1.mp3', '2s', '3 lan', 'audio1'),
('TP02', '2.mp3', '25s', '3 lan', 'audio2'),
('TP03', '3.mp3', '40s', '5 lan', 'audio3'),
('TP04', 'audio/a4.mp3', '35s', '5 lan', 'audio4'),
('TP05', 'audio/a5.mp3', '50s', '2 lan', 'audio5'),
('TP06', 'audio/a6.mp3', '20s', '4 lan', 'audio6'),
('TP07', 'audio/a7.mp3', '45s', '3 lan', 'audio7'),
('TP08', 'audio/a8.mp3', '60s', '2 lan', 'audio8'),
('TP09', 'audio/a9.mp3', '55s', '3 lan', 'audio9'),
('TP10', 'audio/a10.mp3', '15s', '5 lan', 'audio10'),
('TP131', '1782220683_4494f246608d443d8b65e1f42e21cb4c.mp3', '30s', '3 lần', 'Audio 3'),
('TP145', '1782306798_040ed8bf2e43481fae15ace061b4887b.mp3', '1s', '3 lần', 'Audio 2'),
('TP148', '1782226288_a225ffbd69ee4913b99db2a45292a012.mp3', '1s', '3 lần', 'Audio 4'),
('TP211', '1781487536_f53be779c1d74a9fa0c3dabeaf7e6b7a.mp3', '2s', '3 lần', 'Audio 2'),
('TP253', '1781266579_80d650828bfb47c583d572eb9e684e3d.mp3', '2s', '3 lan', 'Audio 2'),
('TP257', '1782226326_302c98d6292e4fc3819056fd787f7fd8.mp3', '1s', '3 lần', 'Audio 5'),
('TP314', '1781269555_cf71803d50d54bfaba7d942af2edfaeb.mp3', '3s', '3 lan', 'Audio 2'),
('TP374', '1782049760_a552fb07df6144959e515fa47c833e46.mp3', '2s', '3 lần', 'Audio 1'),
('TP392', '1781349102_7fa3997ba3e74f719c20d0a293472473.mp3', '6s', '3 lan', 'Audio 2'),
('TP412', '1780844098_3d2d7efde0b9455297ca04a20087bc24.mp3', '2s', '3 lan', 'Audio 1'),
('TP451', '1781277265_ttsmaker-file-2026-6-12-21-55-58.mp3', '15s', '3 lan', 'Audio 1'),
('TP454', '1782306917_817b129deee74210abe0f18a9d7ce482.mp3', '1s', '3 lần', 'Audio 4'),
('TP473', '1781268045_c5638622099a49508bb2ed342d1a515b.mp3', '2s', '3 lan', 'Audio 1'),
('TP488', '1782226090_bb7bf4201c114e6fbf9944f85dab4a75.mp3', '1s', '3 lần', 'Audio 2'),
('TP503', '1781349114_397efa264cdc43abb298fc9c0ff8f25b.mp3', '7s', '3 lan', 'Audio 3'),
('TP530', '1782306883_8ce84f035bdb49889513eed5b0585886.mp3', '1s', '3 lần', 'Audio 3'),
('TP596', '1782220708_fd021e506ba140f28b85969bc00d6082.mp3', '20s', '3 lần', 'Audio 4'),
('TP604', '1782226015_e2b0ff4d7d744348b2462df62651ad80.mp3', '1s', '3 lần', 'Audio 1'),
('TP608', '1782218432_5a968b4b2818483486db3d52cbf2ded8.mp3', '15s', '3 lần', 'Audio 1'),
('TP655', '1781266347_cb7811b13bc44adf851d9e7548857d45.mp3', '2s', '3 lan', 'Audio 1'),
('TP670', '1781612583_212e608d74dd4008a053b19f90badc74.mp3', '2s', '3 lần', 'Audio 1'),
('TP674', '1781268125_653c64d900c14af59ae57d03c006f1c2.mp3', '2s', '3 lan', 'Audio 2'),
('TP693', '1782226133_cf9f13c391e546df82589cea17631a13.mp3', '1s', '3 lần', 'Audio 3'),
('TP726', '1780844683_1278caca91854261b54a13befdf47949.mp3', '2s', '3 lan', 'Audio 3'),
('TP819', '1782220659_921c388f74bf4132940d6b740b3d8247.mp3', '13s', '3 lần', 'Audio 2'),
('TP824', '1781269441_4c862caa6c9b4ec2831728795fad1260.mp3', '3s', '3 lan', 'Audio 1'),
('TP829', '1781440320_b4164473c7434f169af5526f16a1f999.mp3', '2s', '3 lần', 'Audio 1'),
('TP860', '1782049912_9690d0f96bfa4295a357b43600c4181f.mp3', '2s', '3 lần', 'Audio 2'),
('TP893', '1781349088_fb7447c856f34abd871f42d764a2e515.mp3', '5s', '3 lan', 'Audio 1'),
('TP903', '1780844173_999e12ae446c424387a1700eb2075194.mp3', '2s', '3 lan', 'Audio 2'),
('TP926', '1780236819_4.mp3', '15s', '3 lan', 'Audio 1'),
('TP958', '1782306754_564fc0bc3cdb449ca3aead81978be66a.mp3', '1s', '3 lần', 'Audio 1'),
('TP996', '1781268207_70c75480bc854a0098a33a4c636487d7.mp3', '2s', '3 lan', 'Audio 3');

-- --------------------------------------------------------

--
-- Table structure for table `tientrinh`
--

CREATE TABLE `tientrinh` (
  `MaBanDo` varchar(10) NOT NULL,
  `UserID` varchar(10) NOT NULL,
  `MaXH` varchar(10) NOT NULL,
  `ViTri` varchar(20) DEFAULT NULL,
  `KetQuaMan` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tientrinh`
--

INSERT INTO `tientrinh` (`MaBanDo`, `UserID`, `MaXH`, `ViTri`, `KetQuaMan`) VALUES
('BD01', 'U01', 'XH01', 'HoanThanh', '100'),
('BD01', 'U02', 'XH01', 'HoanThanh', '100'),
('BD01', 'USABWOJD3D', 'XH01', 'HoanThanh', '100'),
('BD02', 'U01', 'XH01', 'HoanThanh', '100'),
('BD03', 'U01', 'XH01', 'HoanThanh', '100'),
('BD04', 'U01', 'XH01', 'HoanThanh', '100'),
('BD05', 'U01', 'XH01', 'HoanThanh', '100'),
('BD05', 'U02', 'XH01', 'HoanThanh', '100'),
('BD06', 'U01', 'XH01', 'HoanThanh', '100'),
('BD07', 'U01', 'XH01', 'HoanThanh', '100'),
('BD07', 'U02', 'XH01', 'HoanThanh', '100'),
('BD08', 'U01', 'XH01', 'HoanThanh', '100'),
('BD08', 'U02', 'XH01', 'HoanThanh', '100'),
('BD09', 'U01', 'XH01', 'HoanThanh', '100'),
('BD09', 'U02', 'XH01', 'HoanThanh', '100'),
('BD10', 'U01', 'XH01', 'HoanThanh', '100');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` varchar(10) NOT NULL,
  `UserName` varchar(30) DEFAULT NULL,
  `Email` varchar(70) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Role` varchar(10) DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT NULL,
  `LastLoginAt` datetime DEFAULT NULL,
  `AnhDaiDien` varchar(100) DEFAULT NULL,
  `SDT` int DEFAULT NULL,
  `GioiTinh` varchar(20) DEFAULT NULL,
  `NgaySinh` datetime DEFAULT NULL,
  `TongDiem` bigint DEFAULT NULL,
  `DiemMan` int NOT NULL DEFAULT '0',
  `Vien` varchar(100) DEFAULT NULL,
  `preferred_accent` varchar(10) DEFAULT 'en-US',
  `preferred_speed` double(8,2) DEFAULT '1.00',
  `learning_goal` varchar(50) DEFAULT NULL,
  `current_level` varchar(20) DEFAULT NULL,
  `daily_target_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserName`, `Email`, `google_id`, `email_verified_at`, `Password`, `Role`, `Status`, `CreatedAt`, `LastLoginAt`, `AnhDaiDien`, `SDT`, `GioiTinh`, `NgaySinh`, `TongDiem`, `DiemMan`, `Vien`, `preferred_accent`, `preferred_speed`, `learning_goal`, `current_level`, `daily_target_time`) VALUES
('U01', 'tinpham', 'tin1@gmail.com', NULL, NULL, '$2y$12$Vo/ovsyX.wlD4EBrV/Z0HeyfM5xpuIUuUSIlv96Mf1GxLYVnjQaeq', 'user', 'active', '2026-01-01 00:00:00', '2026-05-01 00:00:00', 'avatars/1779957919_b1a4b797511f432e508bd0377f316c57.jpg', 344219144, 'Nam', '2004-01-01 00:00:00', 120, 1300, 'vien6.jpg', 'en-GB', 1.00, 'communication', 'beginner', 10),
('U02', 'admin', 'admin@gmail.com', NULL, NULL, '$2y$12$xW9vbBO2BtMsFH/NcEFnFezNg0fIJCm0WnSe01M5pwtSy6S5jjk.i', 'admin', 'active', '2026-01-02 00:00:00', '2026-05-01 00:00:00', 'avatars/1780837868_cyber_mascot_helper.png', 912345672, 'Nam', '2004-02-02 00:00:00', 200, 1500, 'vien1.jpg', 'en-US', 1.00, NULL, NULL, NULL),
('U03', 'minhthu', 'tin3@gmail.com', NULL, NULL, '$2y$12$Fy2Y10MDpAdR3yAQhPrh0erQXjRrN8OcnZdTtrKqsZe7Jl0.hBFuO', 'user', 'active', '2026-01-03 00:00:00', '2026-05-01 00:00:00', 'avatars/1780881576_4e71b3d3bda05a3fbd4e682e180d7cfa.jpg', 912345673, 'Khác', '2004-03-03 00:00:00', 300, 100, NULL, 'en-US', 1.00, NULL, NULL, NULL),
('U04', 'hoangan', 'tin4@gmail.com', NULL, NULL, '$2y$12$aVICZYmGmxLKXaw9z26pDOyPUb0W4Ch5wAL3Ll554fwqDsBeJzZpG', 'admin', 'active', '2026-01-04 00:00:00', '2026-05-01 00:00:00', 'a4.png', 912345674, 'Nam', '2004-04-04 00:00:00', 450, 1000, NULL, 'en-US', 1.00, NULL, NULL, NULL),
('U05', 'ngocmai', 'tin5@gmail.com', NULL, NULL, '$2y$12$ahhczsCsrDJFGSdB/RbaCOKsFqKZ4WFT440HAPGmEQBy8f3XzVxjG', 'user', 'active', '2026-01-05 00:00:00', '2026-05-01 00:00:00', 'a5.png', 912345675, 'Nu', '2004-05-05 00:00:00', 500, 1000, NULL, 'en-US', 1.00, NULL, NULL, NULL),
('U06', 'quocbao', 'tin6@gmail.com', NULL, NULL, '$2y$12$wMdnPjmSebbHV.vviWFwk.w1LMfqUaBXuPP5YxXQXgphgmsW4jCdu', 'user', 'active', '2026-01-06 00:00:00', '2026-05-01 00:00:00', 'a6.png', 912345676, 'Nam', '2004-06-06 00:00:00', 150, 1000, NULL, 'en-US', 1.00, NULL, NULL, NULL),
('U07', 'thaovy', 'tin7@gmail.com', NULL, NULL, '$2y$12$xks7brCTiITvbvDX/EgNZujCHCPjZyKAmMjdd3t6BjRJDZcpMejVa', 'user', 'active', '2026-01-07 00:00:00', '2026-05-01 00:00:00', 'a7.png', 912345677, 'Nu', '2004-07-07 00:00:00', 620, 1000, NULL, 'en-US', 1.00, NULL, NULL, NULL),
('U08', 'ductri', 'tin8@gmail.com', NULL, NULL, '$2y$12$2SN9YzhY9pNcE2Yow5.BceQBjVnaAHuIhbb5wvqi9HLE1o/B9rEMW', 'user', 'active', '2026-01-08 00:00:00', '2026-05-01 00:00:00', 'a8.png', 912345678, 'Nam', '2004-08-08 00:00:00', 700, 1000, NULL, 'en-US', 1.00, NULL, NULL, NULL),
('U09', 'kimngan', 'tin9@gmail.com', NULL, NULL, '$2y$12$FWpwpFxgmOObIZLV6dBqO./77wuKtpvOXW2v7IFi6CuYh8ox81eNe', 'user', 'active', '2026-01-09 00:00:00', '2026-05-01 00:00:00', 'a9.png', 912345679, 'Nu', '2004-09-09 00:00:00', 810, 1000, NULL, 'en-US', 1.00, NULL, NULL, NULL),
('U10', 'Văn Phúc', 'tin10@gmail.com', NULL, NULL, '$2y$12$M7ACtBBcaOXCS.Y4W5whg.O6HHtffKGMXb2Pm2yXhYd7gZhexbFAC', 'user', 'active', '2026-01-10 00:00:00', '2026-05-01 00:00:00', 'a10.png', 912345680, 'Nam', '2004-10-10 00:00:00', 920, 1000, NULL, 'en-US', 1.00, NULL, NULL, NULL),
('US1ZWV12E9', 'Tí Cận', 'tin2@gmail.com', NULL, NULL, '$2y$12$hVFUswDK7xLFYNmXni..p.NOda/SRUsUw/Vjnwh/I5H3MTKWTPFcK', 'user', 'active', '2026-05-17 14:46:09', NULL, NULL, NULL, NULL, NULL, NULL, 1000, NULL, 'en-US', 1.00, NULL, NULL, NULL),
('USABWOJD3D', 'Tín Phạm', 'phamthanhtin2004tv@gmail.com', '116980773761346197715', NULL, '$2y$12$kwXMZC.v.cd.UigY2KpPZOmyl1g4gbO/T2OqoeA4/k89CyI5RPSOm', 'user', 'active', '2026-05-29 19:45:34', NULL, NULL, NULL, NULL, NULL, NULL, 1000, NULL, 'en-US', 1.00, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xephang`
--

CREATE TABLE `xephang` (
  `MaXH` varchar(10) NOT NULL,
  `TenXH` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `xephang`
--

INSERT INTO `xephang` (`MaXH`, `TenXH`) VALUES
('XH01', 'Dong'),
('XH02', 'Bac'),
('XH03', 'Vang'),
('XH04', 'Bach Kim'),
('XH05', 'Kim Cuong'),
('XH06', 'Tinh Anh'),
('XH07', 'Cao Thu'),
('XH08', 'Huyen Thoai'),
('XH09', 'Than Thoai'),
('XH10', 'Bat Diet');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `baitest`
--
ALTER TABLE `baitest`
  ADD PRIMARY KEY (`MaBai`),
  ADD KEY `co nhieu_FK` (`MaCDN`),
  ADD KEY `chua_FK` (`MaCD`),
  ADD KEY `Association_18_FK` (`MaBanDo`);

--
-- Indexes for table `bandophieuluu`
--
ALTER TABLE `bandophieuluu`
  ADD PRIMARY KEY (`MaBanDo`);

--
-- Indexes for table `capdonghe`
--
ALTER TABLE `capdonghe`
  ADD PRIMARY KEY (`MaCDN`);

--
-- Indexes for table `cauhoi`
--
ALTER TABLE `cauhoi`
  ADD PRIMARY KEY (`MaCauHoi`),
  ADD KEY `chua nhieu_FK` (`MaLoai`);

--
-- Indexes for table `chitietlambai`
--
ALTER TABLE `chitietlambai`
  ADD PRIMARY KEY (`MaCTLB`),
  ADD KEY `cochua_FK` (`UserID`);

--
-- Indexes for table `choi`
--
ALTER TABLE `choi`
  ADD PRIMARY KEY (`MaBanDo`,`MaCTLB`),
  ADD KEY `choi_FK` (`MaBanDo`),
  ADD KEY `choi2_FK` (`MaCTLB`);

--
-- Indexes for table `chude`
--
ALTER TABLE `chude`
  ADD PRIMARY KEY (`MaCD`);

--
-- Indexes for table `ket qua`
--
ALTER TABLE `ket qua`
  ADD PRIMARY KEY (`MaCTLB`,`MaCauHoi`),
  ADD KEY `ket qua_FK` (`MaCTLB`),
  ADD KEY `ket qua2_FK` (`MaCauHoi`);

--
-- Indexes for table `loaicauhoi`
--
ALTER TABLE `loaicauhoi`
  ADD PRIMARY KEY (`MaLoai`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phan`
--
ALTER TABLE `phan`
  ADD PRIMARY KEY (`MaPhan`),
  ADD KEY `FK_PHAN_BAITEST` (`MaBai`),
  ADD KEY `FK_PHAN_TEP` (`MaTep`);

--
-- Indexes for table `phan_cauhoi`
--
ALTER TABLE `phan_cauhoi`
  ADD PRIMARY KEY (`MaPhan`,`MaCauHoi`),
  ADD KEY `FK_PC_CAUHOI` (`MaCauHoi`);

--
-- Indexes for table `phuongancauhoi`
--
ALTER TABLE `phuongancauhoi`
  ADD PRIMARY KEY (`MaPA`),
  ADD KEY `ton tai_FK` (`MaCauHoi`);

--
-- Indexes for table `tepamthanh`
--
ALTER TABLE `tepamthanh`
  ADD PRIMARY KEY (`MaTep`);

--
-- Indexes for table `tientrinh`
--
ALTER TABLE `tientrinh`
  ADD PRIMARY KEY (`MaBanDo`,`UserID`,`MaXH`),
  ADD KEY `TienTrinh_FK` (`MaBanDo`),
  ADD KEY `TienTrinh2_FK` (`UserID`),
  ADD KEY `TienTrinh3_FK` (`MaXH`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `xephang`
--
ALTER TABLE `xephang`
  ADD PRIMARY KEY (`MaXH`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `baitest`
--
ALTER TABLE `baitest`
  ADD CONSTRAINT `FK_BAITEST_ASSOCIATI_BANDOPHI` FOREIGN KEY (`MaBanDo`) REFERENCES `bandophieuluu` (`MaBanDo`),
  ADD CONSTRAINT `FK_BAITEST_CHUA_CHUDE` FOREIGN KEY (`MaCD`) REFERENCES `chude` (`MaCD`),
  ADD CONSTRAINT `FK_BAITEST_CO NHIEU_CAPDONGH` FOREIGN KEY (`MaCDN`) REFERENCES `capdonghe` (`MaCDN`);

--
-- Constraints for table `cauhoi`
--
ALTER TABLE `cauhoi`
  ADD CONSTRAINT `FK_CAUHOI_CHUA NHIE_LOAICAUH` FOREIGN KEY (`MaLoai`) REFERENCES `loaicauhoi` (`MaLoai`);

--
-- Constraints for table `chitietlambai`
--
ALTER TABLE `chitietlambai`
  ADD CONSTRAINT `FK_CHITIETL_COCHUA_USER` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `choi`
--
ALTER TABLE `choi`
  ADD CONSTRAINT `FK_CHOI_CHOI2_CHITIETL` FOREIGN KEY (`MaCTLB`) REFERENCES `chitietlambai` (`MaCTLB`),
  ADD CONSTRAINT `FK_CHOI_CHOI_BANDOPHI` FOREIGN KEY (`MaBanDo`) REFERENCES `bandophieuluu` (`MaBanDo`);

--
-- Constraints for table `ket qua`
--
ALTER TABLE `ket qua`
  ADD CONSTRAINT `FK_KET QUA_KET QUA2_CAUHOI` FOREIGN KEY (`MaCauHoi`) REFERENCES `cauhoi` (`MaCauHoi`),
  ADD CONSTRAINT `FK_KET QUA_KET QUA_CHITIETL` FOREIGN KEY (`MaCTLB`) REFERENCES `chitietlambai` (`MaCTLB`);

--
-- Constraints for table `phan`
--
ALTER TABLE `phan`
  ADD CONSTRAINT `FK_PHAN_BAITEST` FOREIGN KEY (`MaBai`) REFERENCES `baitest` (`MaBai`),
  ADD CONSTRAINT `FK_PHAN_TEP` FOREIGN KEY (`MaTep`) REFERENCES `tepamthanh` (`MaTep`);

--
-- Constraints for table `phan_cauhoi`
--
ALTER TABLE `phan_cauhoi`
  ADD CONSTRAINT `FK_PC_CAUHOI` FOREIGN KEY (`MaCauHoi`) REFERENCES `cauhoi` (`MaCauHoi`),
  ADD CONSTRAINT `FK_PC_PHAN` FOREIGN KEY (`MaPhan`) REFERENCES `phan` (`MaPhan`);

--
-- Constraints for table `phuongancauhoi`
--
ALTER TABLE `phuongancauhoi`
  ADD CONSTRAINT `FK_PHUONGAN_TON TAI_CAUHOI` FOREIGN KEY (`MaCauHoi`) REFERENCES `cauhoi` (`MaCauHoi`);

--
-- Constraints for table `tientrinh`
--
ALTER TABLE `tientrinh`
  ADD CONSTRAINT `FK_TIENTRIN_TIENTRINH_BANDOPHI` FOREIGN KEY (`MaBanDo`) REFERENCES `bandophieuluu` (`MaBanDo`),
  ADD CONSTRAINT `FK_TIENTRIN_TIENTRINH_USER` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `FK_TIENTRIN_TIENTRINH_XEPHANG` FOREIGN KEY (`MaXH`) REFERENCES `xephang` (`MaXH`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
