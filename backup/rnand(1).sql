-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2023 at 11:43 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rnand`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_accounts`
--

CREATE TABLE `cms_accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `group` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cms_accounts`
--

INSERT INTO `cms_accounts` (`id`, `name`, `email`, `password`, `created_at`, `group`) VALUES
(1, 'wxip', 'wxipnocap@gmail.com', 'a8f5f167f44f4964e6c998dee827110c', '2022-12-20 22:50:48', 0),
(2, 'cements', 'kruc', 'a8f5f167f44f4964e6c998dee827110c', '2022-12-22 22:50:48', 1),
(3, 'rnand', 'hehe@hehe.com', '202cb962ac59075b964b07152d234b70', '2022-12-27 21:14:39', 1),
(4, 'sidnijs', 'lohs', '9cdfb439c7876e703e307864c9167a15', '2023-01-04 19:43:08', 0),
(5, 'asd', 'asd', '7815696ecbf1c96e6894b779456d330e', '2023-01-04 19:43:37', 0),
(6, 'kekw', 'kekw', '333bac057792d4d419dba9bad8afe0d5', '2023-01-04 19:44:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cms_categories`
--

CREATE TABLE `cms_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `parent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cms_categories`
--

INSERT INTO `cms_categories` (`id`, `name`, `description`, `parent`) VALUES
(1, 'Informācija', '', NULL),
(2, 'Spēles', '', NULL),
(3, 'Pazinojumi', 'ololololo', 1),
(4, 'noteikumi', 'test1', 1),
(5, 'konkursi', 'adfjlhgsjadfhgjsagh', 1),
(6, 'Minecraft', 'Minecraft', 2),
(7, 'MTA: San Andreas', 'MTA: San Andreas', 2);

-- --------------------------------------------------------

--
-- Table structure for table `cms_chat`
--

CREATE TABLE `cms_chat` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `message` text NOT NULL,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cms_chat`
--

INSERT INTO `cms_chat` (`id`, `author`, `message`, `posted_at`) VALUES
(1, 1, 'lol a kas te notiek', '2022-12-19 20:48:51'),
(2, 1, 'test', '2022-12-19 21:34:38'),
(3, 1, 'lol kas ttt xdd', '2022-12-19 21:36:59'),
(4, 1, 'yo cau ko notiek', '2022-12-19 21:55:11'),
(5, 1, 'kiop asdif jsd;fa sdxdd', '2022-12-19 21:57:44'),
(6, 1, 'labais a ka tev vispar iet?', '2022-12-19 21:57:51'),
(7, 1, 'ne nu var ari ta vispar', '2022-12-19 21:57:59'),
(8, 1, 'ir ari ja', '2022-12-19 22:05:51'),
(9, 2, 'ko tu ble', '2022-12-19 22:06:43'),
(10, 2, 'test', '2022-12-19 22:23:10'),
(11, 2, 'ja', '2022-12-19 22:35:15'),
(12, 1, 'ko tu', '2022-12-21 21:27:45'),
(13, 1, 'respekt', '2022-12-21 21:27:52'),
(14, 1, 'wsup', '2022-12-22 17:44:41'),
(15, 1, 'yup', '2022-12-27 19:13:26'),
(16, 3, 'jd', '2022-12-27 19:19:13'),
(17, 1, 'asdfasdf', '2023-01-04 16:14:08');

-- --------------------------------------------------------

--
-- Table structure for table `cms_posts`
--

CREATE TABLE `cms_posts` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `author` int(11) NOT NULL,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `edited_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `topic` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `cms_posts`
--

INSERT INTO `cms_posts` (`id`, `content`, `author`, `posted_at`, `edited_at`, `topic`) VALUES
(1, 'zdcf', 1, '2023-01-04 14:44:11', '2023-01-04 14:44:11', 1),
(2, 'respekt', 3, '2023-01-04 14:51:41', '2023-01-04 14:51:41', 1),
(3, '<p>test slhb<strong>adfjgnsjdfng,ld</strong>fgjmsdfga</p>', 1, '2023-01-04 16:46:06', '2023-01-04 16:46:30', 1),
(4, '<div style=\"background:#eeeeee;border:1px solid #cccccc;padding:5px 10px;\">sa<sub>dfd</sub>f l<sup>ol</sup>ol<s>olo lolo</s>ol <strong>XDDD </strong>\r\n<hr />\r\n<p>&nbsp;</p>\r\n</div>\r\n\r\n<p>un k oitu</p>\r\n\r\n<hr />\r\n<p>&nbsp;</p>\r\n', 1, '2023-01-04 16:49:22', '2023-01-04 16:49:22', 1),
(5, '<h2 style=\"font-style:italic;\">Adriāns lohs</h2>\r\n\r\n<h3 style=\"color:#aaaaaa;font-style:italic;\">respekt</h3>\r\n\r\n<p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>\r\n', 1, '2023-01-04 16:55:24', '2023-01-04 16:55:24', 1),
(6, '<p>k otu</p>\r\n', 1, '2023-01-04 16:56:15', '2023-01-04 16:56:15', 1),
(7, '<p>aegdfhsdfghdfgh</p>\r\n', 1, '2023-01-04 17:10:16', '2023-01-04 17:10:16', 1),
(8, '<p>dsfghdgh</p>\r\n', 1, '2023-01-04 17:10:18', '2023-01-04 17:10:18', 1),
(9, '<p>dfghdfhjfghjj</p>\r\n', 1, '2023-01-04 17:10:21', '2023-01-04 17:10:21', 1),
(10, '<p>ko tu</p>\r\n', 6, '2023-01-04 17:44:24', '2023-01-04 17:44:24', 1),
(11, '<p>asd</p>\r\n', 6, '2023-01-04 18:25:12', '2023-01-04 18:25:12', 2),
(12, '<p>asdasd</p>\r\n', 6, '2023-01-04 18:32:48', '2023-01-04 18:32:48', 2),
(13, '<p>lol</p>\r\n', 6, '2023-01-04 18:34:00', '2023-01-04 18:34:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `cms_topics`
--

CREATE TABLE `cms_topics` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `author` int(11) NOT NULL,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `edited_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `parent_cat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `cms_topics`
--

INSERT INTO `cms_topics` (`id`, `name`, `author`, `posted_at`, `edited_at`, `parent_cat`) VALUES
(1, 'sa', 1, '2023-01-04 14:45:40', '2023-01-04 14:45:40', 3),
(2, 'asdasd', 6, '2023-01-04 18:32:48', '2023-01-04 18:32:48', 3),
(3, 'kroplis', 6, '2023-01-04 18:34:00', '2023-01-04 18:34:00', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_accounts`
--
ALTER TABLE `cms_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_categories`
--
ALTER TABLE `cms_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_chat`
--
ALTER TABLE `cms_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_posts`
--
ALTER TABLE `cms_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_topics`
--
ALTER TABLE `cms_topics`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_accounts`
--
ALTER TABLE `cms_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cms_categories`
--
ALTER TABLE `cms_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cms_chat`
--
ALTER TABLE `cms_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cms_posts`
--
ALTER TABLE `cms_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cms_topics`
--
ALTER TABLE `cms_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
