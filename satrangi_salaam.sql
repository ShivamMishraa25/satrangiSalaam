-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2026 at 09:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `satrangi_salaam`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `images` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `images`, `created_at`) VALUES
(2, 'this is second announcement', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '', '2025-02-03 20:31:55'),
(3, '', '', '', '2025-02-03 21:08:09'),
(4, '', '', '', '2025-02-03 21:37:24'),
(5, '', '', '../uploads/announcements/blog_1738618770_0.jpg,../uploads/announcements/chat_1738618770_1.jpg', '2025-02-03 21:39:30'),
(6, 'this is 3rd announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738619045_0.jpg,../uploads/announcements/chat_1738619045_1.jpg', '2025-02-03 21:44:05'),
(7, 'this is 3rd announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738619098_0.jpg,../uploads/announcements/chat_1738619098_1.jpg', '2025-02-03 21:44:58'),
(8, 'this is 3rd announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738619106_0.jpg,../uploads/announcements/chat_1738619106_1.jpg', '2025-02-03 21:45:06'),
(9, '', '', '../uploads/announcements/blog_1738619122_0.jpg,../uploads/announcements/chat_1738619122_1.jpg', '2025-02-03 21:45:22'),
(10, 'this is 3rd announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738619447_0.jpg,../uploads/announcements/chat_1738619447_1.jpg', '2025-02-03 21:50:47'),
(11, 'this is 3rd announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738620821_0.jpg,../uploads/announcements/chat_1738620821_1.jpg', '2025-02-03 22:13:41'),
(12, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738620932_0.jpg,../uploads/announcements/chat_1738620932_1.jpg', '2025-02-03 22:15:32'),
(13, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738620977_0.jpg,../uploads/announcements/chat_1738620977_1.jpg', '2025-02-03 22:16:17'),
(14, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738621217_0.jpg,../uploads/announcements/chat_1738621217_1.jpg', '2025-02-03 22:20:17'),
(15, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738621550_0.jpg,../uploads/announcements/chat_1738621550_1.jpg', '2025-02-03 22:25:50'),
(16, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738622023_0.jpg,../uploads/announcements/chat_1738622023_1.jpg', '2025-02-03 22:33:43'),
(17, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738622431_0.jpg,../uploads/announcements/chat_1738622431_1.jpg', '2025-02-03 22:40:31'),
(18, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738622658_0.jpg,../uploads/announcements/chat_1738622658_1.jpg', '2025-02-03 22:44:18'),
(19, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738623095_0.jpg,../uploads/announcements/chat_1738623095_1.jpg', '2025-02-03 22:51:35'),
(20, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738623511_0.jpg,../uploads/announcements/chat_1738623511_1.jpg', '2025-02-03 22:58:31'),
(21, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '', '2025-02-03 23:04:45'),
(22, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '', '2025-02-03 23:09:05'),
(23, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738624172_0.jpg,../uploads/announcements/chat - Copy_1738624172_1.jpg', '2025-02-03 23:09:32'),
(24, 'this is 4th announcement with email', 'yapping\r\nnew line\r\nnew line with br <br>\r\nanother new long line heloooo kjsqod qw ugdxwYDG AUFDAWXTF DWTEDF YWtcdw yZCDYwdc zOYDCoyt dcwyTDC WZE FWEZFWUDFZD UFW DZUFW DEZF wozudf d', '../uploads/announcements/blog_1738624278_0.jpg,../uploads/announcements/chat - Copy_1738624278_1.jpg', '2025-02-03 23:11:18'),
(25, 'new try maybe visible', 'yayay yaya ayaya a yay a ya ay a ya ya yaaya ayaa a ayay aya aya ayayay ayaaya ayaaya yayaayyaya ya ayaa ay ay aya a ya aayaa yya', '../uploads/announcements/blog_1738624428_0.jpg,../uploads/announcements/chat - Copy_1738624428_1.jpg', '2025-02-03 23:13:48'),
(26, 'new try maybe visible 2', 'yayay yaya ayaya a yay a ya ay a ya ya yaaya ayaa a ayay aya aya ayayay ayaaya ayaaya yayaayyaya ya ayaa ay ay aya a ya aayaa yya', '../uploads/announcements/blog_1738624763_0.jpg,../uploads/announcements/chat - Copy_1738624763_1.jpg', '2025-02-03 23:19:23'),
(27, 'new try maybe visible 2', 'yayay yaya ayaya a yay a ya ay a ya ya yaaya ayaa a ayay aya aya ayayay ayaaya ayaaya yayaayyaya ya ayaa ay ay aya a ya aayaa yya', '../uploads/announcements/blog_1738624946_0.jpg,../uploads/announcements/chat - Copy_1738624946_1.jpg', '2025-02-03 23:22:26'),
(28, 'new try maybe visible 2', 'yayay yaya ayaya a yay a ya ay a ya ya yaaya ayaa a ayay aya aya ayayay ayaaya ayaaya yayaayyaya ya ayaa ay ay aya a ya aayaa yya', '../uploads/announcements/blog_1738625765_0.jpg,../uploads/announcements/chat - Copy_1738625765_1.jpg', '2025-02-03 23:36:05'),
(29, 'new try maybe visible 2', 'yayay yaya ayaya a yay a ya ay a ya ya yaaya ayaa a ayay aya aya ayayay ayaaya ayaaya yayaayyaya ya ayaa ay ay aya a ya aayaa yya', '../uploads/announcements/blog_1738625913_0.jpg,../uploads/announcements/chat - Copy_1738625913_1.jpg', '2025-02-03 23:38:33'),
(30, 'new try maybe visible 2', 'yayay yaya ayaya a yay a ya ay a ya ya yaaya ayaa a ayay aya aya ayayay ayaaya ayaaya yayaayyaya ya ayaa ay ay aya a ya aayaa yya', '../uploads/announcements/blog_1738625923_0.jpg,../uploads/announcements/chat - Copy_1738625923_1.jpg', '2025-02-03 23:38:43'),
(31, 'new try maybe visible 2', 'yayay yaya ayaya a yay a ya ay a ya ya yaaya ayaa a ayay aya aya ayayay ayaaya ayaaya yayaayyaya ya ayaa ay ay aya a ya aayaa yya', '../uploads/announcements/blog_1738625976_0.jpg,../uploads/announcements/chat - Copy_1738625976_1.jpg', '2025-02-03 23:39:36'),
(32, 'fake trial demo title', 'yayay yaya ayaya a yay a ya ay a ya ya yaaya ayaa a ayay aya aya ayayay ayaaya ayaaya this is a trial notification yayaayyaya ya ayaa ay ay aya a ya aayaa yya', '../uploads/announcements/blog_1738626625_0.jpg,../uploads/announcements/chat - Copy_1738626625_1.jpg', '2025-02-03 23:50:25'),
(33, 'dummy announcement test', 'hello world, lets see if this works.\r\nnew paragraph.', '../uploads/announcements/blog_1738648718_0.jpg,../uploads/announcements/chat - Copy_1738648718_1.jpg', '2025-02-04 05:58:38'),
(34, 'dummy announcement test', 'hello world, lets see if this works.\r\nnew paragraph.', '../uploads/announcements/blog_1738648800_0.jpg,../uploads/announcements/chat - Copy_1738648800_1.jpg', '2025-02-04 06:00:00'),
(35, 'dummy announcement test', 'hello world, lets see if this works.\r\nnew paragraph.', '../uploads/announcements/blog_1738649330_0.jpg,../uploads/announcements/chat - Copy_1738649330_1.jpg', '2025-02-04 06:08:50'),
(36, 'dummy announcement test', 'hello world, lets see if this works.\r\nnew paragraph.', '../uploads/announcements/blog_1738649456_0.jpg,../uploads/announcements/chat - Copy_1738649456_1.jpg', '2025-02-04 06:10:56'),
(37, 'dummy announcement test', 'hello world, lets see if this works.\r\nnew paragraph.', '../uploads/announcements/blog_1738649573_0.jpg,../uploads/announcements/chat - Copy_1738649573_1.jpg', '2025-02-04 06:12:53'),
(38, 'dummy announcement test', 'hello world, lets see if this works? idk man\r\nnew para started here. another one.', '../uploads/announcements/blog_1738649659_0.jpg,../uploads/announcements/chat - Copy_1738649659_1.jpg', '2025-02-04 06:14:19'),
(39, 'dummy announcement test', 'hello world, lets see if this works? idk man\r\nnew para started here.\r\n\r\nanother one.', '../uploads/announcements/blog_1738649823_0.jpg,../uploads/announcements/chat - Copy_1738649823_1.jpg', '2025-02-04 06:17:03'),
(40, 'dummy announcement test', 'hello world, lets see if this works? idk man\r\nnew para started here.\r\n\r\nanother one.', '../uploads/announcements/blog_1738650168_0.jpg,../uploads/announcements/chat - Copy_1738650168_1.jpg', '2025-02-04 06:22:48'),
(41, 'dummy announcement test', 'hello world, lets see if this works? idk man\r\nnew para started here.\r\n\r\nanother one.', '../uploads/announcements/blog_1738650273_0.jpg,../uploads/announcements/chat - Copy_1738650273_1.jpg', '2025-02-04 06:24:33'),
(42, 'dummy announcement test', 'hello world, lets see if this works? idk man\r\nnew para started here.\r\n\r\nanother one.', '../uploads/announcements/blog_1738650334_0.jpg,../uploads/announcements/chat - Copy_1738650334_1.jpg', '2025-02-04 06:25:34'),
(43, 'dummy announcement test', 'hello world, lets see if this works? idk man\r\nnew para started here.\r\n\r\nanother one.', '../uploads/announcements/blog_1738650453_0.jpg,../uploads/announcements/chat - Copy_1738650453_1.jpg', '2025-02-04 06:27:33'),
(44, 'dummy announcement test', 'hello this is a demo email.\r\nthanks for helping.', '../uploads/announcements/blog_1738650561_0.jpg,../uploads/announcements/chat - Copy_1738650561_1.jpg', '2025-02-04 06:29:21'),
(45, 'bka bkasbdka asbal blA', 'this is a line.\r\nthis is anoter line\r\n\r\nthis is 2 lines gap', '../uploads/announcements/blog_1738655548_0.jpg,../uploads/announcements/chat_1738655548_1.jpg', '2025-02-04 07:52:28'),
(46, 'bka bkasbdka asbal blA', 'this is a line.\r\nthis is anoter line\r\n\r\nthis is 2 lines gap', '../uploads/announcements/blog_1738655579_0.jpg,../uploads/announcements/chat_1738655579_1.jpg', '2025-02-04 07:52:59'),
(47, 'bka bkasbdka asbal blA', 'this is a line.\r\nthis is anoter line\r\n\r\nthis is 2 lines gap', '../uploads/announcements/blog_1738656049_0.jpg,../uploads/announcements/chat_1738656049_1.jpg', '2025-02-04 08:00:49'),
(48, 'bka bkasbdka asbal blA', 'this is a line.\r\nthis is anoter line\r\n\r\nthis is 2 lines gap', '../uploads/announcements/blog_1738656373_0.jpg,../uploads/announcements/chat_1738656373_1.jpg', '2025-02-04 08:06:13'),
(49, 'bka bkasbdka asbal blA', 'this is a line.\r\nthis is anoter line\r\n\r\nthis is 2 lines gap', '../uploads/announcements/blog_1738656409_0.jpg,../uploads/announcements/chat_1738656409_1.jpg', '2025-02-04 08:06:49'),
(50, 'bka bkasbdka asbal blA', 'this is a line.\r\nthis is anoter line\r\n\r\nthis is 2 lines gap', '../uploads/announcements/blog_1738656561_0.jpg,../uploads/announcements/chat_1738656561_1.jpg', '2025-02-04 08:09:21'),
(51, 'bka bkasbdka asbal blA', 'this is a line.\r\nthis is anoter line\r\n\r\nthis is 2 lines gap', '../uploads/announcements/blog_1738656587_0.jpg,../uploads/announcements/chat_1738656587_1.jpg', '2025-02-04 08:09:47'),
(52, 'bka bkasbdka asbal blA', 'this is a line.\r\nthis is anoter line\r\n\r\nthis is 2 lines gap', '../uploads/announcements/blog_1738656692_0.jpg,../uploads/announcements/chat_1738656692_1.jpg', '2025-02-04 08:11:32'),
(53, 'bka bkasbdka asbal blA', 'this is a line.\r\nthis is anoter line\r\n\r\nthis is 2 lines gap', '../uploads/announcements/blog_1738656925_0.jpg,../uploads/announcements/chat_1738656925_1.jpg', '2025-02-04 08:15:25'),
(54, 'bka bkasbdka asbal blA', 'this is a line.\r\nthis is anoter line\r\n\r\nthis is 2 lines gap', '../uploads/announcements/blog_1738657135_0.jpg,../uploads/announcements/chat_1738657135_1.jpg', '2025-02-04 08:18:55'),
(55, 'title title tittle title', 'lets try something else.\r\nnew line.\r\n\r\nnew line of 2 gaps\r\nanother new line.', '../uploads/announcements/blog_1738657206_0.jpg,../uploads/announcements/chat_1738657206_1.jpg', '2025-02-04 08:20:06'),
(56, 'title title tittle title', 'lets try something else.\r\nnew line.\r\n\r\nnew line of 2 gaps\r\nanother new line.', '../uploads/announcements/blog_1738657460_0.jpg,../uploads/announcements/chat_1738657460_1.jpg', '2025-02-04 08:24:20'),
(57, 'title title tittle title', 'lets try something else.\r\nnew line.\r\n\r\nnew line of 2 gaps\r\nanother new line.', '../uploads/announcements/blog_1738657689_0.jpg,../uploads/announcements/chat_1738657689_1.jpg', '2025-02-04 08:28:09'),
(58, 'title title tittle title', 'lets try something else.\r\nnew line.\r\n\r\nnew line of 2 gaps\r\nanother new line.', '../uploads/announcements/blog_1738657850_0.jpg,../uploads/announcements/chat_1738657850_1.jpg', '2025-02-04 08:30:50'),
(59, 'this should be in db', 'kaha h db me?', '', '2025-02-04 08:32:22'),
(60, 'Announcement Title of Long Length', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit.\r\n Corrupti, facere fuga veniam qui numquam quas debitis delectus repellendus. Quasi in voluptate porro doloribus perspiciatis quas exercitationem fugit impedit veniam laudantium?\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Corrupti, facere fuga veniam qui numquam quas debitis delectus repellendus.\r\n\r\nQuasi in voluptate porro doloribus perspiciatis quas exercitationem fugit impedit veniam laudantium?', '../uploads/announcements/bnw_1741239725_0.jpg,../uploads/announcements/image2_1741239725_1.jpg,../uploads/announcements/image3_1741239725_2.jpg', '2025-03-06 05:42:05'),
(61, 'try 1', 'lorem', '../uploads/announcements/chat_1738614136_1_1741357127_0.jpg', '2025-03-07 14:18:47'),
(62, 'this is an annoucement title, hellow nice, हिन्दी मे bhi likhna है to.', 'lorem ipsum dolc aj habxjzhbcl jhbzjhb zaljdbh wjlaez jled bajbhd zwjl bljds bljk dckd sdc ksjc ksncjs nclslc snd ls dlcnlsdcnl sl lanlasl lj dewdnlkw dlwk dwen n e dwjdnlwnde wed lw dlek wlk elk ewd ed jwld jlwe dlwj dlw \'kk k kjk\"\"j jk jk kj kj k.', '../uploads/announcements/shivam_1754682249_0.jpg,../uploads/announcements/shivammishraa_1754682249_1.jpg,../uploads/announcements/shivamJaipur_1754682249_2.jpg,../uploads/announcements/shivamMishra_1754682249_3.jpg', '2025-08-08 19:44:09'),
(63, 'this is an annoucement title, hellow nice, हिन्दी मे bhi likhna है to.', 'lorem ipsum dolc aj habxjzhbcl jhbzjhb zaljdbh wjlaez jled bajbhd zwjl bljds bljk dckd sdc ksjc ksncjs nclslc snd ls dlcnlsdcnl sl lanlasl lj dewdnlkw dlwk dwen n e dwjdnlwnde wed lw dlek wlk elk ewd ed jwld jlwe dlwj dlw \'kk k kjk\"\"j jk jk kj kj k.', '../uploads/announcements/Pi7_Tool_shivam_1754682939_0.jpg,../uploads/announcements/shivam_1754682939_1.jpg', '2025-08-08 19:55:39'),
(64, 'this is an annoucement title, hellow nice, हिन्दी मे bhi likhna है to.', 'lorem ipsum dolc aj habxjzhbcl jhbzjhb zaljdbh wjlaez jled bajbhd zwjl bljds bljk dckd sdc ksjc ksncjs nclslc snd ls dlcnlsdcnl sl lanlasl lj dewdnlkw dlwk dwen n e dwjdnlwnde wed lw dlek wlk elk ewd ed jwld jlwe dlwj dlw \'kk k kjk\"\"j jk jk kj kj k.', '../uploads/announcements/signature_1754683047_0.jpg,../uploads/announcements/Pi7_Tool_shivam_1754683047_1.jpg,../uploads/announcements/shivam_1754683047_2.jpg,../uploads/announcements/Pi7_Tool_shivamMishra_1754683047_3.jpg,../uploads/announcements/shivammishraa_1754683047_4.jpg,../uploads/announcements/shivamJaipur_1754683047_5.jpg', '2025-08-08 19:57:27'),
(65, 'this is an annoucement title, hellow nice, हिन्दी मे bhi likhna है to.', 'lorem ipsum dolc aj habxjzhbcl jhbzjhb zaljdbh wjlaez jled bajbhd zwjl bljds bljk dckd sdc ksjc ksncjs nclslc snd ls dlcnlsdcnl sl lanlasl lj dewdnlkw dlwk dwen n e dwjdnlwnde wed lw dlek wlk elk ewd ed jwld jlwe dlwj dlw \'kk k kjk\"\"j jk jk kj kj k.', '../uploads/announcements/19_1754722757_0.jpg,../uploads/announcements/67c9b5fcf010e_IMG-20250306-WA0009_1754722757_1.jpg,../uploads/announcements/684e377b21410_DSC_2213_1754722757_2.jpeg', '2025-08-09 06:59:17'),
(66, 'Test announcement emails', 'wderftghyfdsfdafgt ewsrdtdswderfgt ewfrgtdsdesfrd wefrgdtf.', '../uploads/announcements/StackBlitz Guide_1772982466_0.pdf', '2026-03-08 15:07:46'),
(67, 'Bro Listen!! This is a TEA', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.\r\n\r\nEtiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna.\r\nSed consequat, leo eget bibendum sodales, augue velit cursus nunc.', '../uploads/announcements/Trust+Yourself._1775851824_0.png,../uploads/announcements/wp_1775851824_1.png,../uploads/announcements/0410_1775851824_2.jpg,../uploads/announcements/Untitled+design_1775851824_3.jpg,../uploads/announcements/Untitled+design+(1)_1775851824_4.jpg,../uploads/announcements/img1_1775851824_5.jpg,../uploads/announcements/Success_1775851824_6.jpg', '2026-04-10 20:10:24');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','dismissed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collaboration_certificates`
--

CREATE TABLE `collaboration_certificates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `works` text DEFAULT NULL,
  `certificate_no` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collaboration_certificates`
--

INSERT INTO `collaboration_certificates` (`id`, `name`, `works`, `certificate_no`, `created_at`) VALUES
(1, 'miss mansi sonkar', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, assumenda facilis voluptate unde iste inventore nisi labore! Minima sint quaerat dolore ut neque at fugit inventore aperiam!Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, assumenda facilis voluptate unde iste inventore nisi labore! Minima sint quaerat dolore ut neque at fugit inventore aperiam!', 1, '2025-02-02 21:13:21');

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, 'dummy', 'dummy@dum.com', '7355474464', NULL, NULL, '2025-03-05 17:42:33'),
(2, 'dummy', 'dummy@dum.com', '7355474464', NULL, NULL, '2025-03-05 17:53:17'),
(3, 'dummy', 'dummy@dum.com', '7355474464', NULL, NULL, '2025-03-05 17:53:29'),
(4, 'dummy', 'dummy@dum.com', '7355474464', NULL, NULL, '2025-03-05 18:00:26'),
(5, 'dummy', 'dummy@dum.com', '7355474464', NULL, NULL, '2025-03-05 18:01:13'),
(6, 'dummy', 'dummy@dum.com', '7355474464', NULL, NULL, '2025-03-05 18:03:48'),
(7, 'dummy', 'dummy@dum.com', '7355474464', NULL, NULL, '2025-03-05 18:07:54'),
(8, 'trial 2', 'fhealfn@sfkjsnd.com', '1234567890', NULL, NULL, '2025-03-05 18:08:59'),
(9, 'trial 2', 'fhealfn@sfkjsnd.com', '1234567890', NULL, NULL, '2025-03-05 18:10:26'),
(10, 'trial 2', 'fhealfn@sfkjsnd.com', '1234567890', NULL, NULL, '2025-03-05 18:11:18'),
(11, 'trial 2', 'fhealfn@sfkjsnd.com', '1234567890', NULL, NULL, '2025-03-05 18:13:45'),
(12, 'trial 2', 'fhealfn@sfkjsnd.com', '1234567890', NULL, NULL, '2025-03-05 18:15:54'),
(13, 'god', 'god@terimaa.com', '1234567890', NULL, NULL, '2025-03-05 18:17:30'),
(14, 'god', 'god@terimaa.com', '1234567890', NULL, NULL, '2025-03-05 18:23:17'),
(15, 'god', 'god@terimaa.com', '1234567890', NULL, NULL, '2025-03-05 18:24:11'),
(16, 'god', 'god@terimaa.com', '1234567890', NULL, NULL, '2025-03-05 18:24:31'),
(17, 'god', 'god@terimaa.com', '1234567890', NULL, NULL, '2025-03-05 18:44:23'),
(18, 'Shivam Mishra', '000shivammishra000@gmail.com', '07355474464', NULL, NULL, '2025-08-09 16:05:10'),
(19, 'Shivam Mishra', '000shivammishra000@gmail.com', '07355474464', NULL, NULL, '2025-08-09 16:06:26'),
(20, 'Shivam Mishra', '000shivammishra000@gmail.com', '07355474464', NULL, NULL, '2025-08-09 16:07:49'),
(21, 'Shivam Mishra', '000shivammishra000@gmail.com', '07355474464', NULL, NULL, '2025-08-09 16:28:32'),
(22, 'shivam mishra test', 'shivam.m4464@gmail.com', '07355474464', NULL, NULL, '2026-04-13 18:29:14'),
(23, 'Shivam Mishra', 'shivam.m4464@gmail.com', '7355474464', NULL, NULL, '2026-04-13 18:56:10'),
(24, 'Shivam Mishra', 'shivam.m4464@gmail.com', '07355474464', NULL, NULL, '2026-04-13 18:56:30'),
(25, 'Shivam Mishra', 'shivam.m4464@gmail.com', '07355474464', NULL, NULL, '2026-04-13 18:57:23'),
(26, 'Shivam Mishra', 'shivam.m4464@gmail.com', '07355474464', NULL, NULL, '2026-04-13 18:59:21'),
(27, 'Shivam Mishra', 'shivam.m4464@gmail.com', '07355474464', NULL, NULL, '2026-04-13 19:05:03'),
(28, 'Shivam Mishra', 'shivam.m4464@gmail.com', '07355474464', NULL, NULL, '2026-04-13 19:08:40'),
(29, 'Shivam Mishra', 'shivam.m4464@gmail.com', '98767355474464', '', 'testing dbms', '2026-04-13 19:20:00'),
(30, 'Shivam Mishra', 'shivam.m4464@gmail.com', '07355474464', 'volunteer', 'kuytfxgfdvvjucfyc nbmbjiyucgh vnbmnjiyuch vnbmnjlogiyfutc', '2026-04-13 19:20:57');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `whatsapp` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `certificate_no` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `name`, `amount`, `email`, `whatsapp`, `created_at`, `status`, `certificate_no`) VALUES
(1, 'shivam', 51, 'shivam.m4464@gmail.com', '7355474464', '2025-03-05 20:01:05', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_location` varchar(255) NOT NULL,
  `event_description` text NOT NULL,
  `event_image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_date`, `event_location`, `event_description`, `event_image`) VALUES
(1, 'event name bla bla', '2025-01-01', 'prayagraj, uttar pradesh, india', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur, expedita nesciunt ex saepe labore placeat porro modi exercitationem, excepturi quibusdam, qui consequuntur! Distinctio, quibusdam. Quo beatae quae odio numquam placeat.Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur, expedita nesciunt ex saepe labore placeat porro modi exercitationem, excepturi quibusdam, qui consequuntur! Distinctio, quibusdam. Quo beatae quae odio numquam placeat.', '../uploads/portrait.png,../uploads/shivam.png'),
(2, 'event name bla bla', '2025-01-01', 'prayagraj, uttar pradesh, india', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum harum aperiam tempore laborum, esse maiores optio maxime natus rem, quibusdam ratione aut neque magni mollitia modi doloremque, consectetur est placeat.', 'uploads/portrait.png,uploads/shivam.png'),
(3, 'event name bla bla', '2025-01-01', 'prayagraj, uttar pradesh, india', '\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:', ''),
(4, 'event name bla bla', '2025-01-01', 'prayagraj, uttar pradesh, india', '\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:', ''),
(5, 'event name bla bla', '2025-01-01', 'prayagraj, uttar pradesh, india', '\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:\"Location:', ''),
(6, 'new', '2025-02-03', 'prayagraj, uttar pradesh, india', 'this should be on top', ''),
(7, 'event name bla bla', '2025-02-03', 'prayagraj, uttar pradesh, india', 'badsvsdscvaeubsdjsr', '../uploads/blog.jpg,../uploads/chat.jpg,../uploads/data.jpg,../uploads/ecom.jpg'),
(8, 'event name bla bla', '2025-01-05', 'prayagraj, uttar pradesh, india', 'badsvsdscvaeubsdjsr', '../uploads/blog.jpg,../uploads/chat.jpg,../uploads/data.jpg,../uploads/ecom.jpg'),
(9, 'try 1', '2025-03-06', 'prayagraj, uttar pradesh, india', 'trstrsgfsfg lorem', '../uploads/blog_1738614136_0.jpg'),
(10, 'event name bla bla', '2025-08-07', 'prayagraj, uttar pradesh, india', 'अखिल भारतीय सतरंगी सलाम संगठन भारतीय ट्रस्ट अधिनियम के तहत पंजीकृत एक वैध संस्था है, जिसका मुख्यालय प्रयागराज (इलाहाबाद), उत्तर प्रदेश में स्थित है। हम निरंतर मानवाधिकार, महिला अधिकार, एलजीबीटी अधिकार, पर्यावरण व लैंगिक समानता पर काम कर रहे हैं।', '../uploads/19.jpg'),
(11, 'event name bla bla', '2025-08-07', 'prayagraj, uttar pradesh, india', 'lorem ipsum dolc aj habxjzhbcl jhbzjhb zaljdbh wjlaez jled bajbhd zwjl bljds bljk dckd sdc ksjc ksncjs nclslc snd ls dlcnlsdcnl sl lanlasl lj dewdnlkw dlwk dwen n e dwjdnlwnde wed lw dlek wlk elk ewd ed jwld jlwe dlwj dlw \'kk k kjk\"\"j jk jk kj kj k.', '../uploads/events/68970afe6c461_1754729214.jpg,../uploads/events/68970afe6c95d_1754729214.jpeg'),
(12, 'Fill This Form To Get Your Experience Certificate', '2025-08-06', 'prayagraj, uttar pradesh, india', 'copy-paste error.\r\n\r\nSecond Code Block (236 lines): Only has the <footer> element at the very end of the document, which is the correct and standard way to structure a document.\r\n\r\nThe extra lines in the first block are from that duplicate footer. The browser would render the first footer and ignore the second one, but it\'s still invalid HTML and unnecessary code.', '../uploads/events/68970bb666833_1754729398.jpg,../uploads/events/68970bb667cd1_1754729398.jpeg'),
(13, 'Nice Event We Had', '2026-04-01', 'prayagraj, uttar pradesh, india', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc.', '../uploads/events/69d94e9d6e578_1775849117.png,../uploads/events/69d94e9d6ed7b_1775849117.png,../uploads/events/69d94e9d6eff5_1775849117.jpg,../uploads/events/69d94e9d6f424_1775849117.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `exmembers`
--

CREATE TABLE `exmembers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `post` varchar(50) NOT NULL,
  `moved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exmembers`
--

INSERT INTO `exmembers` (`id`, `name`, `email`, `post`, `moved_at`) VALUES
(1, 'bhartendu vimal dubey', 'dummy@dum.com', 'promoted', '2025-03-07 14:23:16'),
(2, 'bhartendu vimal dubey', 'dummy@dummy.com', 'promoted', '2025-03-07 14:23:26'),
(3, 'shivam', '000shivammishra000@gmail.com', 'resigned', '2025-03-07 14:23:34');

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `period` varchar(50) DEFAULT NULL,
  `post` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `whatsapp` varchar(15) NOT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `certificate_no` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image_path`, `uploaded_at`) VALUES
(6, '69d94664096ca_Success.jpg', '2026-04-10 18:50:12');

-- --------------------------------------------------------

--
-- Table structure for table `moderators`
--

CREATE TABLE `moderators` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'moderator'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moderators`
--

INSERT INTO `moderators` (`id`, `username`, `password`, `role`) VALUES
(1, 'Shivammishraa25', '$2y$10$BIOYNJsF0UapcVSEllk.XeK7T7w.z042FgatIBqvNK6v5liI6TS0u', 'moderator'),
(2, 'umania26', '$2y$10$28QPD84I4pNDTNnoA7QH6eeihxVCSetpmj/ErGPRD14fsDllJP2Wm', 'moderator'),
(3, 'dipshesad', '$2y$10$Yuz0YI5wrlLm10TU750oYeV7ykdX3JSZd7PxNKWvOAHhykfZxx4KW', 'moderator');

-- --------------------------------------------------------

--
-- Table structure for table `participation`
--

CREATE TABLE `participation` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `whatsapp` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `certificate_no` varchar(50) DEFAULT NULL,
  `collaborators` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participation`
--

INSERT INTO `participation` (`id`, `name`, `event_name`, `event_date`, `whatsapp`, `email`, `status`, `certificate_no`, `collaborators`) VALUES
(1, 'bhartendu vimal dubey', 'Fill This Form To Get Your Experience Certificate', '2025-02-06', '121212121212', 'shivam.m446@gmail.com', 'approved', '9', NULL),
(2, 'bhartendu vimal dubey', 'Fill This Form To Get Your Experience Certificate', '2025-01-29', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', NULL),
(3, 'bhartendu vimal dubey', 'Fill This Form To Get Your Experience Certificate', '2025-01-29', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', NULL),
(4, 'bhartendu vimal dubey', 'Fill This Form To Get Your Experience Certificate', '2025-01-29', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', NULL),
(5, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you will recieve your Experience Certificate in digital format.', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', NULL),
(6, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', NULL),
(7, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaborators collaborators collaborators collaborators'),
(8, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is.', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', NULL),
(9, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is done.', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', NULL),
(10, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', NULL),
(11, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you will recieve your Experience Certificate in digital format.', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', NULL),
(12, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you will recieve your Experience Certificate in digital format.', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaborators collaborators collaborators collaborators'),
(13, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you will recieve your Experience Certificate in digital format.', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaboratorscollaboratorscollaborators'),
(14, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaboratorscollaboratorscollaborators collaborators'),
(15, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaborators collaborators collaborators'),
(16, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', ''),
(17, 'bhartendu vimal dubey', 'Fill This Form To Get Your Experience Certificate', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaboratorscollaboratorscollaborators collaborators'),
(18, 'bhartendu vimal dubey', 'Fill This Form To Get Your Experience Certificate', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaborators collaborators collaborators collaborators'),
(19, 'bhartendu vimal dubey', 'Fill This Form To Get Your Experience Certificate', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaboratorscollaboratorscollaboratorscollaborators'),
(20, 'bhartendu vimal dubey', 'Fill This Form To Get Your Experience Certificate', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaboratorscollaboratorscollaboratorscollaborators'),
(21, 'bhartendu vimal dubey', 'Fill This Form To Get Your Experience Certificate', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaboratorscollaboratorscollaboratorscollaborators'),
(22, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaborators collaborators collaborators'),
(23, 'bhartendu vimal dubey', 'We need your Whatsapp                                                                                    ', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaborators collaborators collaborators'),
(24, 'bhartendu vimal dubey', 'We need your Whatsapp                                                                                 .   ', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaborators collaborators collaborators'),
(25, 'bhartendu vimal dubey', 'We need your Whatsapp <br>', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaboratorscollaboratorscollaborators'),
(26, 'bhartendu vimal dubey', 'We need your Whatsapp <br><br><br>', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaboratorscollaboratorscollaborators'),
(27, 'bhartendu vimal dubey', 'We need your Whatsapp <br><br>', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaboratorscollaboratorscollaborators'),
(28, 'bhartendu vimal dubey', 'We need your Whatsapp <br><br>', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'collaborators collaborators collaboratorscollaboratorscollaborators'),
(29, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'kaushlya nand giri aka teena maa ki jayy kara lagao sab log'),
(30, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', ''),
(31, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', ''),
(32, 'bhartendu vimala', 'Wsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '12121212', 'shivam.m4464@gmail.com', 'approved', '10', 'kaushlya nand giri aka teena ma'),
(33, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'approved', '10', 'kaushlya nand giri aka teena maa ki jayy kara lagao sab log'),
(34, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'pending', NULL, NULL),
(35, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'pending', NULL, NULL),
(36, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'pending', NULL, NULL),
(37, 'bhartendu vimal dubey', 'We need your Whatsapp number and email to send your certificate to you.  Once your form is approved, you', '2025-02-06', '121212121212', 'shivam.m4464@gmail.com', 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token_hash`, `expires_at`, `used_at`, `created_at`) VALUES
(1, 1, 'ee3e18e1eba4ef8016ba900ef9b2fc8851c92b6c817ce595200801eed96a493c', '2026-03-08 21:24:10', '2026-03-08 20:31:10', '2026-03-08 20:24:10'),
(2, 1, '9b2a83fa92a737f93523a4827af5fb46920fa65e0923b90741abd732e8ccdc13', '2026-03-08 21:31:10', '2026-03-08 20:31:13', '2026-03-08 20:31:10'),
(3, 1, 'beadf1806c53f95f655d2fb5b50f6fb9d6ae945767678ca42c982ff2b0fd1d57', '2026-03-08 22:02:18', '2026-03-08 21:02:21', '2026-03-08 21:02:18'),
(4, 1, '8f6115df04d4f81758f6a65387987bb75edf1941a86c8f97a81ecca716433e3e', '2026-03-08 22:03:00', '2026-03-08 21:03:52', '2026-03-08 21:03:00'),
(5, 1, '6d278d5939f8035edb7669d252b228018c0e615bd053cd04f7a2b3bbaa7ddab4', '2026-03-08 22:19:33', NULL, '2026-03-08 21:19:33');

-- --------------------------------------------------------

--
-- Table structure for table `performance`
--

CREATE TABLE `performance` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `works` text DEFAULT NULL,
  `certificate_no` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `performance`
--

INSERT INTO `performance` (`id`, `name`, `works`, `certificate_no`, `created_at`) VALUES
(1, 'miss mansi sonkar', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, assumenda facilis voluptate unde iste inventore nisi labore! Minima sint quaerat dolore ut neque at fugit inventore aperiam!', 1, '2025-02-02 20:56:51'),
(2, 'miss mansi sonkar', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, assumenda facilis voluptate unde iste inventore nisi labore! Minima sint quaerat dolore ut neque at fugit inventore aperiam!Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, as', 2, '2025-02-02 20:58:16'),
(3, 'miss mansi sonkar', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, assumenda facilis voluptate unde iste inventore nisi labore! Minima sint quaerat dolore ut neque at fugit inventore aperiam!Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, as', 3, '2025-02-02 20:59:25'),
(4, 'miss mansi sonkar', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, assumenda facilis voluptate unde iste inventore nisi labore! Minima sint quaerat dolore ut neque at fugit inventore aperiam!Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, assumenda facilis voluptate unde iste inventore nisi labore! Minima sint quaerat dolore ut neque at fugit inventore aperiam!Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, assumenda facilis voluptate unde iste inventore nisi labore! Minima sint quaerat dolore ut neque at fugit inventore aperiam!', 4, '2025-02-02 20:59:57'),
(5, 'miss mansi sonkar', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, assumenda facilis voluptate unde iste inventore nisi labore! Minima sint quaerat dolore ut neque at fugit inventore aperiam!Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas optio quos adipisci, assumenda facilis voluptate unde iste inventore nisi labore! Minima sint quaerat dolore ut neque at fugit inventore aperiam!', 5, '2025-02-02 21:07:00');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `preferred_name` varchar(255) DEFAULT NULL,
  `pronouns` varchar(50) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `post` varchar(255) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','dismissed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `name`, `preferred_name`, `pronouns`, `father_name`, `post`, `reference`, `address`, `occupation`, `mobile_no`, `email`, `photo`, `city`, `password`, `submission_date`, `status`) VALUES
(1, 'shivam', 'shiv', 'he', 'shivendra', 'Member', 'bharat', 'pbh', 'web dev', '7355474464', 'shivam.m446@gmail.com', NULL, NULL, '$2y$10$nmj/PGTHGIGzfOo8zMjlfO22BQTJ7QD4BzniFpDh4wG5XpfixQRdC', '2025-02-02 20:32:56', 'approved'),
(2, 'shivam', 'shiv', 'he', 'shivendra', 'Senior Member', 'bharat', 'pbh', 'web dev', '7355474464', '000shivammishra000@gmail.com', NULL, NULL, '$2y$10$KwzeuGmPhHs6GHSKCEswge7lYXw3Ypya.W5gkMs9C501y12rCn1YC', '2025-02-03 22:40:06', 'approved'),
(3, 'bhartendu vimal dubey', 'dummy', 'he/his', 'Shivam Mishra', 'Senior Member', 'shivam', 'nepal border ke pass', 'ngo owner', '7355474464', 'shivam.m4464@gmail.comm', NULL, 'Siddharthnagar', '$2y$10$VeCClLlCSVT0ZiiVpoiay.2VdBrcAGp6JbGqLDJzPCIDLknOnngiK', '2025-02-19 14:35:30', 'pending'),
(4, 'bhartendu vimal dubey', 'dummy', 'he/his', 'Shivam Mishra', 'Senior Member', 'shivam', 'nepal border ke pass', 'ngo owner', '7355474464', 'shivam.m4466@gmail.comm', NULL, 'Siddharthnagar', '$2y$10$rCBEnKGjct7ndaWxq/tOw.vHERqwxqpg9T80C6Z/pbn27vE6XC76O', '2025-02-19 14:37:17', 'dismissed'),
(5, 'first 1', 'dummy', 'he/his/him', 'Shivam Mishra', 'Senior Member', 'shivam', 'nepal border ke pass', 'ngo owner', '7355474464', 'shivam.m4466@gmail.cooomm', NULL, 'Siddharthnagar', '$2y$10$6XSwRS5mqNozncV98JIYGuoaFciRDMj3LnqKX7C9HqfJFTnq7SzvW', '2025-02-19 15:23:06', 'approved'),
(6, 'shivam', 'dummy', 'he/his', 'shivendra', 'Senior Member', 'shivam', 'pbh, up', 'web dev', '7355474464', 'shivam.m4464@gmail.commmmm', NULL, 'Siddharthnagar', '$2y$10$ZldLQjVRJcN6aQoX2E6zoOtbZwLR84qLC7w/OtuQ32nDer6M3VMMK', '2025-02-19 15:27:20', 'dismissed'),
(7, 'bhartendu vimal dubey', 'dummy', 'he/his', 'shivendra', 'Member', 'shivam', 'pbh,UP', 'web dev', '7355474464', 'dummy@dum.commmm', 'uploads/members/user_1739979381.jpg', 'Siddharthnagar', '$2y$10$ZHWxt/y0P5hS6FnMMo.MTuXSoDQRQ2Thr/iiuB8hTE0NUENd577ka', '2025-02-19 15:36:21', 'approved'),
(8, 'bhartendu vimal dubey', 'dummy', 'he/his/him', 'Shivam Mishra', 'NGO owner', 'shivam mishra', 'nepal border ke pass', 'ngo owner', '7355474464', 'bvd@gmail.com', 'uploads/members/user_1739979514.jpg', 'Siddharthnagar', '$2y$10$dc5U3Vrtx3VyY/WHMpK4QejBj1xRzm55lXPjdj6.YxwXaKC9Suexq', '2025-02-19 15:38:34', 'approved'),
(9, 'Bhartendu Vimal Dubey', 'Bharat', 'he/his/him', 'Mr. Papa Dubey', 'Senior Trustee Founder', 'Shivam Mishra', 'Nepal border ke pass', 'NGO Owner', '7355474464', 'bharat@gmail.com', 'uploads/members/user_1739995647.jpg', 'Siddharthnagar', '$2y$10$ShIKD/i3PDTtU4b1Te5V6e5.8zXMPteEt8iblGZ3MlJWYEIdzWcSm', '2025-02-19 20:07:27', 'approved'),
(10, 'shivam', 'dummy', 'he/his', 'shivendra', 'Member', 'Shivam Mishra', 'pbh, up', 'web dev', '7355474464', 'dummy@dum.commmmmmmmmmmmmmmm', 'uploads/members/user_1739996029.jpg', 'Siddharthnagar', '$2y$10$KY4NXa2yrR4Fu82OXgbLKuFas99iSsXbqc5DF5Ix6A0iRfdwHszuW', '2025-02-19 20:13:49', 'approved'),
(11, 'Abhay Vishwakarma', 'Abhi', 'he/him', 'Mr. Vishwakarma', 'Senior Trustee', 'shivam', 'Prayagraj', 'worker', '1234567890', 'abhi@a.com', 'uploads/members/user_1740003882.jpg', 'Prayagraj', '$2y$10$zLcVIjfFuE6V5Tyq.9rag.lN2ko8g3k0yo5sN3dqtNqOSQg35Y.mS', '2025-02-19 22:24:42', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE `updates` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `updates`
--

INSERT INTO `updates` (`id`, `content`, `created_at`) VALUES
(1, 'this is first trial announcement <br> hello\r\nthis is another line without br', '2025-02-03 20:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `preferred_name` varchar(255) DEFAULT NULL,
  `pronouns` varchar(50) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `post` varchar(255) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `approveddate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `preferred_name`, `pronouns`, `father_name`, `post`, `reference`, `address`, `occupation`, `mobile_no`, `email`, `photo`, `city`, `password`, `approveddate`) VALUES
(1, 'shivam', 'shiv', 'he', 'shivendra', 'Member', 'bharat', 'pbh', 'web dev', '7355474464', 'shivam.m4464@gmail.com', NULL, NULL, '$2y$10$DLK3xKhosPJm4mVeTwo46uBazEmQPjEPeuKGThPBDEkoGuZSjp78W', '2025-02-02 20:34:26'),
(6, 'dummy', 'dummy', 'dummy', 'dummy dad', 'Member', 'dummy', 'dummy', 'dummy', '1234567890', 'dummy@dumm.com', '', 'dummy', '$2y$10$8a3bPWK3N9HxjaJdE53vuO/kRRKjeHx3Yq/Fqg1JSosZRr329g0wK', '2025-02-19 11:31:46'),
(7, 'shivam', 'dummy', 'he/his', 'shivendra', 'Senior Member', 'shivam', 'dummy', 'web dev', '7355474464', 'shivendra455@gmail.com', '', 'Siddharthnagar', '$2y$10$0n9T46iFEA0C.3qO5Rauvu.DF1rjovH/nIA7bBoVW79TMynX/blMm', '2025-02-19 11:49:57'),
(9, 'shivam', 'dummy', 'he/his', 'shivendra', 'Senior Member', 'shivam', 'dummy', 'web dev', '7355474464', 'du@gmail.com', '', 'Siddharthnagar', '$2y$10$KjBoWprRQVdjkUwpdwMBPu/7noOuAWXpjGgEetm9S5Ja0pCZiAGum', '2025-02-19 11:54:29'),
(10, 'bhartendu vimal dubey', 'dummy', 'he/his/him', 'Shivam Mishra', 'NGO owner', 'shivam mishra', 'nepal border ke pass', 'ngo owner', '7355474464', 'bvd@gmail.com', 'uploads/members/user_1739979514.jpg', 'Siddharthnagar', '$2y$10$dc5U3Vrtx3VyY/WHMpK4QejBj1xRzm55lXPjdj6.YxwXaKC9Suexq', '2025-02-19 17:50:42'),
(11, 'Bhartendu Vimal Dubey', 'Bharat', 'he/his/him', 'Mr. Papa Dubey', 'Senior Trustee Founder', 'Shivam Mishra', 'Nepal border ke pass', 'NGO Owner', '7355474464', 'bharat@gmail.com', 'uploads/members/user_1739995647.jpg', 'Siddharthnagar', '$2y$10$ShIKD/i3PDTtU4b1Te5V6e5.8zXMPteEt8iblGZ3MlJWYEIdzWcSm', '2025-02-19 20:08:46'),
(12, 'bhartendu vimal dubey', 'dummy', 'he/his', 'shivendra', 'Member', 'shivam', 'pbh,UP', 'web dev', '7355474464', 'dummy@dum.commmm', 'uploads/members/user_1739979381.jpg', 'Siddharthnagar', '$2y$10$ZHWxt/y0P5hS6FnMMo.MTuXSoDQRQ2Thr/iiuB8hTE0NUENd577ka', '2025-02-19 20:13:09'),
(13, 'shivam', 'dummy', 'he/his', 'shivendra', 'Member', 'Shivam Mishra', 'pbh, up', 'web dev', '7355474464', 'dummy@dum.commmmmmmmmmmmmmmm', 'uploads/members/user_1739996029.jpg', 'Siddharthnagar', '$2y$10$KY4NXa2yrR4Fu82OXgbLKuFas99iSsXbqc5DF5Ix6A0iRfdwHszuW', '2025-02-19 20:13:58'),
(14, 'Abhay Vishwakarma', 'Abhi', 'he/him', 'Mr. Vishwakarma', 'Senior Trustee', 'shivam', 'Prayagraj', 'worker', '1234567890', 'abhi@a.com', 'uploads/members/user_1740003882.jpg', 'Prayagraj', '$2y$10$zLcVIjfFuE6V5Tyq.9rag.lN2ko8g3k0yo5sN3dqtNqOSQg35Y.mS', '2025-02-19 22:25:18'),
(15, 'first 1', 'dummy', 'he/his/him', 'Shivam Mishra', 'Senior Member', 'shivam', 'nepal border ke pass', 'ngo owner', '7355474464', 'shivam.m4466@gmail.cooomm', NULL, 'Siddharthnagar', '$2y$10$6XSwRS5mqNozncV98JIYGuoaFciRDMj3LnqKX7C9HqfJFTnq7SzvW', '2025-03-07 14:20:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collaboration_certificates`
--
ALTER TABLE `collaboration_certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_no` (`certificate_no`);

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exmembers`
--
ALTER TABLE `exmembers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moderators`
--
ALTER TABLE `moderators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `participation`
--
ALTER TABLE `participation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token_hash_idx` (`token_hash`),
  ADD KEY `user_id_idx` (`user_id`),
  ADD KEY `expires_idx` (`expires_at`);

--
-- Indexes for table `performance`
--
ALTER TABLE `performance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_no` (`certificate_no`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collaboration_certificates`
--
ALTER TABLE `collaboration_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `exmembers`
--
ALTER TABLE `exmembers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `moderators`
--
ALTER TABLE `moderators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `participation`
--
ALTER TABLE `participation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `performance`
--
ALTER TABLE `performance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
