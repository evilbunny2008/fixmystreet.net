-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 07, 2021 at 02:56 PM
-- Server version: 10.3.27-MariaDB-0+deb10u1
-- PHP Version: 7.3.27-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fms`
--

-- --------------------------------------------------------

--
-- Table structure for table `abuse`
--

CREATE TABLE `abuse` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

CREATE TABLE `admin_log` (
  `id` int(11) NOT NULL,
  `admin_user` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_type` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` int(11) NOT NULL,
  `action` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `whenedited` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `time_spent` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alert`
--

CREATE TABLE `alert` (
  `id` int(11) NOT NULL,
  `alert_type` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `parameter` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `parameter2` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `confirmed` int(11) NOT NULL DEFAULT 0,
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en-gb',
  `cobrand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cobrand_data` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whensubscribed` timestamp NOT NULL DEFAULT current_timestamp(),
  `whendisabled` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alert_sent`
--

CREATE TABLE `alert_sent` (
  `alert_id` int(11) NOT NULL,
  `parameter` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whenqueued` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alert_type`
--

CREATE TABLE `alert_type` (
  `ref` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_sql_query` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_table` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_link` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_table` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_where` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_order` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_link` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `template` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alert_type`
--

INSERT INTO `alert_type` (`ref`, `head_sql_query`, `head_table`, `head_title`, `head_link`, `head_description`, `item_table`, `item_where`, `item_order`, `item_title`, `item_link`, `item_description`, `template`) VALUES
('area_problems', '', '', 'New problems within {{NAME}}\'s boundary on {{SITE_NAME}}', '/reports', 'The latest problems within {{NAME}}\'s boundary reported by users', 'problem', 'problem.non_public = \'f\' and problem.state NOT IN\n        (\'unconfirmed\', \'hidden\', \'partial\') AND\n    areas like \'%,\'||?||\',%\'', 'created desc', '{{title}}, {{confirmed}}', '/report/{{id}}', '{{detail}}', 'alert-problem-area'),
('council_problems', '', '', 'New problems to {{COUNCIL}} on {{SITE_NAME}}', '/reports', 'The latest problems for {{COUNCIL}} reported by users', 'problem', 'problem.non_public = \'f\' and problem.state NOT IN\n        (\'unconfirmed\', \'hidden\', \'partial\') AND\n    regexp_split_to_array(bodies_str, \',\') && ARRAY[?]', 'created desc', '{{title}}, {{confirmed}}', '/report/{{id}}', '{{detail}}', 'alert-problem-council'),
('local_problems', '', '', 'New local problems on {{SITE_NAME}}', '/', 'The latest local problems reported by users', 'problem_find_nearby(?, ?, ?) as nearby,problem', 'nearby.problem_id = problem.id and problem.non_public = \'f\' and problem.state NOT IN\n        (\'unconfirmed\', \'hidden\', \'partial\')', 'created desc', '{{title}}, {{confirmed}}', '/report/{{id}}', '{{detail}}', 'alert-problem-nearby'),
('local_problems_state', '', '', 'New local problems on {{SITE_NAME}}', '/', 'The latest local problems reported by users', 'problem_find_nearby(?, ?, ?) as nearby,problem', 'nearby.problem_id = problem.id and problem.non_public = \'f\' and problem.state in (?)', 'created desc', '{{title}}, {{confirmed}}', '/report/{{id}}', '{{detail}}', 'alert-problem-nearby'),
('new_fixed_problems', '', '', 'Problems recently reported fixed on {{SITE_NAME}}', '/', 'The latest problems reported fixed by users', 'problem', 'problem.non_public = \'f\' and problem.state in (\'fixed\', \'fixed - user\', \'fixed - council\')', 'lastupdate desc', '{{title}}, {{confirmed}}', '/report/{{id}}', '{{detail}}', 'alert-problem'),
('new_problems', '', '', 'New problems on {{SITE_NAME}}', '/', 'The latest problems reported by users', 'problem', 'problem.non_public = \'f\' and problem.state NOT IN\n        (\'unconfirmed\', \'hidden\', \'partial\')', 'created desc', '{{title}}, {{confirmed}}', '/report/{{id}}', '{{detail}}', 'alert-problem'),
('new_updates', 'select * from problem where id=?', 'problem', 'Updates on {{title}}', '/', 'Updates on {{title}}', 'comment', 'comment.state=\'confirmed\'', 'created desc', 'Update by {{name}}', '/report/{{problem_id}}#comment_{{id}}', '{{text}}', 'alert-update'),
('postcode_local_problems', '', '', 'New problems near {{POSTCODE}} on {{SITE_NAME}}', '/', 'The latest local problems reported by users', 'problem_find_nearby(?, ?, ?) as nearby,problem', 'nearby.problem_id = problem.id and problem.non_public = \'f\' and problem.state NOT IN\n        (\'unconfirmed\', \'hidden\', \'partial\')', 'created desc', '{{title}}, {{confirmed}}', '/report/{{id}}', '{{detail}}', 'alert-problem-nearby'),
('postcode_local_problems_state', '', '', 'New problems near {{POSTCODE}} on {{SITE_NAME}}', '/', 'The latest local problems reported by users', 'problem_find_nearby(?, ?, ?) as nearby,problem', 'nearby.problem_id = problem.id and problem.non_public = \'f\' and problem.state in (?)', 'created desc', '{{title}}, {{confirmed}}', '/report/{{id}}', '{{detail}}', 'alert-problem-nearby'),
('ward_problems', '', '', 'New problems for {{COUNCIL}} within {{WARD}} ward on {{SITE_NAME}}', '/reports', 'The latest problems for {{COUNCIL}} within {{WARD}} ward reported by users', 'problem', 'problem.non_public = \'f\' and problem.state NOT IN\n        (\'unconfirmed\', \'hidden\', \'partial\') AND\n    (regexp_split_to_array(bodies_str, \',\') && ARRAY[?] or bodies_str is null) and\n    areas like \'%,\'||?||\',%\'', 'created desc', '{{title}}, {{confirmed}}', '/report/{{id}}', '{{detail}}', 'alert-problem-ward');

-- --------------------------------------------------------

--
-- Table structure for table `body`
--

CREATE TABLE `body` (
  `id` int(11) NOT NULL,
  `name` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `external_url` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `endpoint` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurisdiction` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_method` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_comments` tinyint(1) NOT NULL DEFAULT 0,
  `comment_user_id` int(11) DEFAULT NULL,
  `suppress_alerts` tinyint(1) NOT NULL DEFAULT 0,
  `can_be_devolved` tinyint(1) NOT NULL DEFAULT 0,
  `send_extended_statuses` tinyint(1) NOT NULL DEFAULT 0,
  `fetch_problems` tinyint(1) NOT NULL DEFAULT 0,
  `blank_updates_permitted` tinyint(1) NOT NULL DEFAULT 0,
  `convert_latlong` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `extra` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `body`
--

INSERT INTO `body` (`id`, `name`, `external_url`, `parent`, `endpoint`, `jurisdiction`, `api_key`, `send_method`, `send_comments`, `comment_user_id`, `suppress_alerts`, `can_be_devolved`, `send_extended_statuses`, `fetch_problems`, `blank_updates_permitted`, `convert_latlong`, `deleted`, `extra`) VALUES
(1, 'Australia', '', NULL, '', '', '', 'Email', 0, NULL, 0, 0, 0, 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `body_areas`
--

CREATE TABLE `body_areas` (
  `body_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed` timestamp NULL DEFAULT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT 1,
  `mark_fixed` tinyint(1) NOT NULL,
  `mark_open` tinyint(1) DEFAULT NULL,
  `extra` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `body_id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT 'Other',
  `email` text NOT NULL,
  `state` text NOT NULL,
  `editor` text NOT NULL,
  `whenedited` timestamp NULL DEFAULT NULL,
  `note` text NOT NULL,
  `extra` text DEFAULT NULL,
  `non_public` tinyint(1) DEFAULT NULL,
  `endpoint` text DEFAULT NULL,
  `jurisdiction` varchar(255) DEFAULT '',
  `api_key` varchar(255) DEFAULT '',
  `send_method` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `contacts`
--
DELIMITER $$
CREATE TRIGGER `contacts_updated` AFTER INSERT ON `contacts` FOR EACH ROW insert into contacts_history (contact_id, body_id, category, email, editor, whenedited, note, state) values (new.id, new.body_id, new.category, new.email, new.editor, new.whenedited, new.note, new.state)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_history`
--

CREATE TABLE `contacts_history` (
  `contacts_history_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `body_id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT 'Other',
  `email` text NOT NULL,
  `state` text NOT NULL,
  `editor` text NOT NULL,
  `whenedited` timestamp NULL DEFAULT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contact_defect_types`
--

CREATE TABLE `contact_defect_types` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `defect_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_response_priorities`
--

CREATE TABLE `contact_response_priorities` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `response_priority_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_response_templates`
--

CREATE TABLE `contact_response_templates` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `response_template_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `councils`
--

CREATE TABLE `councils` (
  `id` int(11) NOT NULL,
  `council_name` varchar(255) NOT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `state` enum('Australian Capital Territory','New South Wales','Northern Territory','Queensland','South Australia','Tasmania','Victoria','Western Australia') DEFAULT 'New South Wales'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `councils`
--

INSERT INTO `councils` (`id`, `council_name`, `contact_name`, `contact_email`, `state`) VALUES
(1, 'Australian Capital Territory', NULL, NULL, 'Australian Capital Territory'),
(2, 'Adelaide City Council', NULL, NULL, 'South Australia'),
(3, 'Adelaide Hills Council', NULL, NULL, 'South Australia'),
(4, 'Albany City Council', NULL, NULL, 'Western Australia'),
(5, 'Albury City Council', NULL, NULL, 'New South Wales'),
(6, 'Alexandrina Council', NULL, NULL, 'New South Wales'),
(7, 'Alice Springs Town Council', NULL, NULL, 'Northern Territory'),
(8, 'Alpine Shire Council', NULL, NULL, 'Victoria'),
(9, 'Anangu Pitjantjatjara Yankunytjatjara', NULL, NULL, 'Northern Territory'),
(10, 'Ararat Rural City Council', NULL, NULL, 'Victoria'),
(11, 'Armadale City Council', NULL, NULL, 'Western Australia'),
(12, 'Armidale Regional Council', NULL, NULL, 'New South Wales'),
(13, 'Auburn City Council', NULL, NULL, 'New South Wales'),
(14, 'Aurukun Shire Council', NULL, NULL, 'Queensland'),
(15, 'Ballarat City Council', NULL, NULL, 'Victoria'),
(16, 'Ballina Shire Council', NULL, NULL, 'New South Wales'),
(17, 'Balonne Shire Council', NULL, NULL, 'Queensland'),
(18, 'Balranald Shire Council', NULL, NULL, 'New South Wales'),
(19, 'Banana Shire Council', NULL, NULL, 'Queensland'),
(20, 'Bankstown City Council', NULL, NULL, 'New South Wales'),
(21, 'Banyule City Council', NULL, NULL, 'Victoria'),
(22, 'Barcaldine Regional Council', NULL, NULL, 'Queensland'),
(23, 'Barcoo Shire Council', NULL, NULL, 'Queensland'),
(24, 'Barkly Shire Council', NULL, NULL, 'Northern Territory'),
(25, 'Barossa Council', NULL, NULL, 'South Australia'),
(26, 'Bass Coast Shire Council', NULL, NULL, 'Victoria'),
(27, 'Bathurst Regional Council', NULL, NULL, 'New South Wales'),
(28, 'Baw Baw Shire Council', NULL, NULL, 'Victoria'),
(29, 'Bayside City Council', NULL, NULL, 'Victoria'),
(30, 'Bega Valley Shire Council', NULL, NULL, 'New South Wales'),
(31, 'Bellingen Shire Council', NULL, NULL, 'New South Wales'),
(32, 'Belyuen Community Government Council', NULL, NULL, 'Northern Territory'),
(33, 'Benalla Rural City Council', NULL, NULL, 'Victoria'),
(34, 'Berri Barmera Council', NULL, NULL, 'South Australia'),
(35, 'Berrigan Shire Council', NULL, NULL, 'New South Wales'),
(36, 'Blackall-Tambo Regional Council', NULL, NULL, 'Queensland'),
(37, 'Blacktown City Council', NULL, NULL, 'New South Wales'),
(38, 'Bland Shire Council', NULL, NULL, 'New South Wales'),
(39, 'Blayney Shire Council', NULL, NULL, 'New South Wales'),
(40, 'Blue Mountains City Council', NULL, NULL, 'New South Wales'),
(41, 'Bogan Shire Council', NULL, NULL, 'New South Wales'),
(42, 'Bombala Council', NULL, NULL, 'New South Wales'),
(43, 'Boorowa Council', NULL, NULL, 'New South Wales'),
(44, 'Boroondara City Council', NULL, NULL, 'Victoria'),
(45, 'Borough of Queenscliffe', NULL, NULL, 'Victoria'),
(46, 'Boulia Shire Council', NULL, NULL, 'Queensland'),
(47, 'Bourke Shire Council', NULL, NULL, 'New South Wales'),
(48, 'Break O\'Day Council', NULL, NULL, 'Tasmania'),
(49, 'Brewarrina Shire Council', NULL, NULL, 'New South Wales'),
(50, 'Brighton Council', NULL, NULL, 'South Australia'),
(51, 'Brimbank City Council', NULL, NULL, 'Victoria'),
(52, 'Brisbane City Council', NULL, NULL, 'Queensland'),
(53, 'Broken Hill City Council', NULL, NULL, 'New South Wales'),
(54, 'Bulloo Shire Council', NULL, NULL, 'Queensland'),
(55, 'Buloke Shire Council', NULL, NULL, 'Victoria'),
(56, 'Bundaberg Regional Council', NULL, NULL, 'Queensland'),
(57, 'Burdekin Shire Council', NULL, NULL, 'Queensland'),
(58, 'Burke Shire Council', NULL, NULL, 'Queensland'),
(59, 'Burnie City Council', NULL, NULL, 'Tasmania'),
(60, 'Burwood Council', NULL, NULL, 'New South Wales'),
(61, 'Byron Shire Council', NULL, NULL, 'New South Wales'),
(62, 'Cabonne Shire Council', NULL, NULL, 'New South Wales'),
(63, 'Cairns Regional Council', NULL, NULL, 'Queensland'),
(64, 'Camden Council', NULL, NULL, 'New South Wales'),
(65, 'Campaspe Shire Council', NULL, NULL, 'Victoria'),
(66, 'Campbelltown City Council', NULL, NULL, 'New South Wales'),
(67, 'Canterbury City Council', NULL, NULL, 'New South Wales'),
(68, 'Cardinia Shire Council', NULL, NULL, 'Victoria'),
(69, 'Carpentaria Shire Council', NULL, NULL, 'Queensland'),
(70, 'Carrathool Shire Council', NULL, NULL, 'New South Wales'),
(71, 'Casey City Council', NULL, NULL, 'Victoria'),
(72, 'Cassowary Coast Regional Council', NULL, NULL, 'Queensland'),
(73, 'Central Coast Council', NULL, NULL, 'New South Wales'),
(74, 'Central Darling Shire Council', NULL, NULL, 'Queensland'),
(75, 'Central Desert Shire Council', NULL, NULL, 'Northern Territory'),
(76, 'Central Goldfields Shire Council', NULL, NULL, 'Victoria'),
(77, 'Central Highlands Council', NULL, NULL, 'Tasmania'),
(78, 'Central Highlands Regional Council', NULL, NULL, 'Queensland'),
(79, 'Cessnock City Council', NULL, NULL, 'New South Wales'),
(80, 'Charters Towers Regional Council', NULL, NULL, 'Queensland'),
(81, 'Cherbourg Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(82, 'Circular Head Council', NULL, NULL, 'Tasmania'),
(83, 'City of Bayswater Council', NULL, NULL, 'Western Australia'),
(84, 'City of Belmont Council', NULL, NULL, 'Western Australia'),
(85, 'City of Bunbury Council', NULL, NULL, 'Western Australia'),
(86, 'City of Burnside Council', NULL, NULL, 'South Australia'),
(87, 'City of Busselton Council', NULL, NULL, 'Western Australia'),
(88, 'City of Canada Bay Council', NULL, NULL, 'New South Wales'),
(89, 'City of Canning Council', NULL, NULL, 'Western Australia'),
(90, 'City of Cockburn Council', NULL, NULL, 'Western Australia'),
(91, 'City of Darwin Council', NULL, NULL, 'Northern Territory'),
(92, 'City of Fremantle Council', NULL, NULL, 'Western Australia'),
(93, 'City of Gosnells Council', NULL, NULL, 'Western Australia'),
(94, 'City of Holdfast Bay', NULL, NULL, 'South Australia'),
(95, 'City of Joondalup Council', NULL, NULL, 'Western Australia'),
(96, 'City of Kalgoorlie-Boulder Council', NULL, NULL, 'Western Australia'),
(97, 'City of Kwinana Council', NULL, NULL, 'Western Australia'),
(98, 'City of Lithgow Council', NULL, NULL, 'New South Wales'),
(99, 'City of Mandurah Council', NULL, NULL, 'Western Australia'),
(100, 'City of Marion', NULL, NULL, 'South Australia'),
(101, 'City of Melville Council', NULL, NULL, 'Western Australia'),
(102, 'City of Mitcham', NULL, NULL, 'South Australia'),
(103, 'City of Mount Gambier', NULL, NULL, 'South Australia'),
(104, 'City of Nedlands Council', NULL, NULL, 'Western Australia'),
(105, 'City of Norwood Payneham and St Peters', NULL, NULL, 'South Australia'),
(106, 'City of Onkaparinga', NULL, NULL, 'South Australia'),
(107, 'City of Palmerston Council', NULL, NULL, 'Northern Territory'),
(108, 'City of Perth Council', NULL, NULL, 'Western Australia'),
(109, 'City of Playford', NULL, NULL, 'South Australia'),
(110, 'City of Port Adelaide Enfield', NULL, NULL, 'South Australia'),
(111, 'City of Port Lincoln', NULL, NULL, 'South Australia'),
(112, 'City of Prospect', NULL, NULL, 'South Australia'),
(113, 'City of Rockingham Council', NULL, NULL, 'Western Australia'),
(114, 'City of Salisbury', NULL, NULL, 'South Australia'),
(115, 'City of South Perth Council', NULL, NULL, 'Western Australia'),
(116, 'City of Stirling Council', NULL, NULL, 'Western Australia'),
(117, 'City of Subiaco Council', NULL, NULL, 'Western Australia'),
(118, 'City of Swan Council', NULL, NULL, 'Western Australia'),
(119, 'City of Tea Tree Gully', NULL, NULL, 'South Australia'),
(120, 'City of Unley', NULL, NULL, 'South Australia'),
(121, 'City of Victor Harbor', NULL, NULL, 'South Australia'),
(122, 'City of Vincent Council', NULL, NULL, 'Western Australia'),
(123, 'City of Wanneroo Council', NULL, NULL, 'Western Australia'),
(124, 'City of West Torrens', NULL, NULL, 'South Australia'),
(125, 'Clare and Gilbert Valleys Council', NULL, NULL, 'South Australia'),
(126, 'Clarence City Council', NULL, NULL, 'Tasmania'),
(127, 'Clarence Valley Council', NULL, NULL, 'New South Wales'),
(128, 'Cloncurry Shire Council', NULL, NULL, 'Queensland'),
(129, 'Cobar Shire Council', NULL, NULL, 'New South Wales'),
(130, 'Coffs Harbour City Council', NULL, NULL, 'New South Wales'),
(131, 'Colac Otway Shire Council', NULL, NULL, 'Victoria'),
(132, 'Conargo Shire Council', NULL, NULL, 'New South Wales'),
(133, 'Cook Shire Council', NULL, NULL, 'New South Wales'),
(134, 'Coolamon Shire Council', NULL, NULL, 'New South Wales'),
(135, 'Coomalie Community Government Council', NULL, NULL, 'Northern Territory'),
(136, 'Cooma-Monaro Shire Council', NULL, NULL, 'New South Wales'),
(137, 'Coonamble Shire Council', NULL, NULL, 'New South Wales'),
(138, 'Coorong District Council', NULL, NULL, 'South Australia'),
(139, 'Cootamundra Shire Council', NULL, NULL, 'Queensland'),
(140, 'Corangamite Shire Council', NULL, NULL, 'Victoria'),
(141, 'Corowa Shire Council', NULL, NULL, 'New South Wales'),
(142, 'Corporation of the City of Whyalla', NULL, NULL, 'South Australia'),
(143, 'Corporation of the Town of Walkerville', NULL, NULL, 'South Australia'),
(144, 'City of Charles Sturt', NULL, NULL, 'South Australia'),
(145, 'Council of the City of Sydney', NULL, NULL, 'New South Wales'),
(146, 'Cowra Shire Council', NULL, NULL, 'New South Wales'),
(147, 'Croydon Shire Council', NULL, NULL, 'Queensland'),
(148, 'Darebin City Council', NULL, NULL, 'Victoria'),
(149, 'Deniliquin Council', NULL, NULL, 'New South Wales'),
(150, 'Derwent Valley Council', NULL, NULL, 'Tasmania'),
(151, 'Devonport City Council', NULL, NULL, 'Tasmania'),
(152, 'Diamantina Shire Council', NULL, NULL, 'Queensland'),
(153, 'District Council of Barunga West', NULL, NULL, 'South Australia'),
(154, 'District Council of Ceduna', NULL, NULL, 'South Australia'),
(155, 'District Council of Cleve', NULL, NULL, 'South Australia'),
(156, 'District Council of Coober Pedy', NULL, NULL, 'South Australia'),
(157, 'District Council of Copper Coast', NULL, NULL, 'South Australia'),
(158, 'District Council of Elliston', NULL, NULL, 'South Australia'),
(159, 'District Council of Franklin Harbour', NULL, NULL, 'South Australia'),
(160, 'District Council of Grant', NULL, NULL, 'South Australia'),
(161, 'District Council of Karoonda East Murr ay', NULL, NULL, 'South Australia'),
(162, 'District Council of Kimba', NULL, NULL, 'South Australia'),
(163, 'District Council of Lower Eyre Peninsula', NULL, NULL, 'South Australia'),
(164, 'District Council of Loxton Waikerie', NULL, NULL, 'South Australia'),
(165, 'Adelaide Plains Council', NULL, NULL, 'South Australia'),
(166, 'District Council of Mount Barker', NULL, NULL, 'South Australia'),
(167, 'District Council of Mount Remarkable', NULL, NULL, 'South Australia'),
(168, 'District Council of Orroroo Carrieton', NULL, NULL, 'South Australia'),
(169, 'District Council of Peterborough', NULL, NULL, 'South Australia'),
(170, 'District Council of Robe', NULL, NULL, 'South Australia'),
(171, 'District Council of Streaky Bay', NULL, NULL, 'South Australia'),
(172, 'District Council of Tumby Bay', NULL, NULL, 'South Australia'),
(173, 'District Council of Yankalilla', NULL, NULL, 'South Australia'),
(174, 'District Council of Yorke Peninsula', NULL, NULL, 'Queensland'),
(175, 'Doomadgee Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(176, 'Dorset Council', NULL, NULL, 'Tasmania'),
(177, 'Dubbo City Council', NULL, NULL, 'New South Wales'),
(178, 'Dungog Shire Council', NULL, NULL, 'New South Wales'),
(179, 'Eastern Arnhem Shire Council', NULL, NULL, 'Northern Territory'),
(180, 'East Gippsland Shire Council', NULL, NULL, 'Victoria'),
(181, 'Etheridge Shire Council', NULL, NULL, 'Queensland'),
(182, 'Eurobodalla Shire Council', NULL, NULL, 'New South Wales'),
(183, 'Fairfield City Council', NULL, NULL, 'New South Wales'),
(184, 'Flinders Council', NULL, NULL, 'Tasmania'),
(185, 'Flinders Ranges Council', NULL, NULL, 'South Australia'),
(186, 'Flinders Shire Council', NULL, NULL, 'Queensland'),
(187, 'Forbes Shire Council', NULL, NULL, 'New South Wales'),
(188, 'Frankston City Council', NULL, NULL, 'Victoria'),
(189, 'Fraser Coast Regional Council', NULL, NULL, 'Queensland'),
(190, 'Shire of Gannawarra', NULL, NULL, 'Victoria'),
(191, 'George Town Council', NULL, NULL, 'Tasmania'),
(192, 'Gilgandra Shire Council', NULL, NULL, 'New South Wales'),
(193, 'Gladstone Regional Council', NULL, NULL, 'Queensland'),
(194, 'Glamorgan Spring Bay Council', NULL, NULL, 'Tasmania'),
(195, 'Glen Eira City Council', NULL, NULL, 'Victoria'),
(196, 'Glenelg Shire Council', NULL, NULL, 'Victoria'),
(197, 'Glen Innes Severn Council', NULL, NULL, 'New South Wales'),
(198, 'Glenorchy City Council', NULL, NULL, 'Tasmania'),
(199, 'Gloucester Shire Council', NULL, NULL, 'New South Wales'),
(200, 'Gold Coast City Council', NULL, NULL, 'Queensland'),
(201, 'Golden Plains Shire Council', NULL, NULL, 'Victoria'),
(202, 'Goondiwindi Regional Council', NULL, NULL, 'Queensland'),
(203, 'Gosford City Council', NULL, NULL, 'New South Wales'),
(204, 'Goulburn Mulwaree Council', NULL, NULL, 'New South Wales'),
(205, 'Greater Bendigo City Council', NULL, NULL, 'Victoria'),
(206, 'Greater Dandenong City Council', NULL, NULL, 'Victoria'),
(207, 'Greater Geelong City Council', NULL, NULL, 'Victoria'),
(208, 'Greater Hume Shire Council', NULL, NULL, 'New South Wales'),
(209, 'Greater Shepparton City Council', NULL, NULL, 'Victoria'),
(210, 'Mid-Coast Council', NULL, NULL, 'New South Wales'),
(211, 'Great Lakes Council', NULL, NULL, 'New South Wales'),
(212, 'Griffith City Council', NULL, NULL, 'New South Wales'),
(213, 'Cootamundra-Gundagai Regional Council', NULL, NULL, 'New South Wales'),
(214, 'Gunnedah Shire Council', NULL, NULL, 'New South Wales'),
(216, 'Gwydir Shire Council', NULL, NULL, 'New South Wales'),
(217, 'Gympie Regional Council', NULL, NULL, 'Queensland'),
(218, 'Harden Shire Council', NULL, NULL, 'New South Wales'),
(219, 'City of Hawkesbury', NULL, NULL, 'New South Wales'),
(220, 'Hay Shire Council', NULL, NULL, 'New South Wales'),
(221, 'Hepburn Shire Council', NULL, NULL, 'Victoria'),
(222, 'Hinchinbrook Shire Council', NULL, NULL, 'Queensland'),
(223, 'Hindmarsh Shire Council', NULL, NULL, 'Victoria'),
(224, 'Hobart City Council', NULL, NULL, 'Tasmania'),
(225, 'Hobsons Bay City Council', NULL, NULL, 'Victoria'),
(226, 'Holroyd City Council', NULL, NULL, 'New South Wales'),
(227, 'Hope Vale Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(228, 'Horsham Rural City Council', NULL, NULL, 'Victoria'),
(229, 'Hume City Council', NULL, NULL, 'Victoria'),
(230, 'Huon Valley Council', NULL, NULL, 'Tasmania'),
(231, 'Hurstville City Council', NULL, NULL, 'New South Wales'),
(232, 'Indigo Shire Council', NULL, NULL, 'Victoria'),
(233, 'Inverell Shire Council', NULL, NULL, 'New South Wales'),
(234, 'Ipswich City Council', NULL, NULL, 'Queensland'),
(235, 'Isaac Regional Council', NULL, NULL, 'Queensland'),
(237, 'Junee Shire Council', NULL, NULL, 'New South Wales'),
(238, 'Kangaroo Island Council', NULL, NULL, 'South Australia'),
(239, 'Katherine Town Council', NULL, NULL, 'Northern Territory'),
(240, 'Kempsey Shire Council', NULL, NULL, 'New South Wales'),
(241, 'Kentish Council', NULL, NULL, 'Tasmania'),
(242, 'Kingborough Council', NULL, NULL, 'Tasmania'),
(243, 'King Island Council', NULL, NULL, 'Tasmania'),
(244, 'Kingston City Council', NULL, NULL, 'Victoria'),
(245, 'Kingston District Council', NULL, NULL, 'South Australia'),
(246, 'Knox City Council', NULL, NULL, 'Victoria'),
(247, 'Kogarah City Council', NULL, NULL, 'New South Wales'),
(248, 'Kowanyama Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(249, 'Ku-ring-gai Council', NULL, NULL, 'New South Wales'),
(250, 'Kyogle Council', NULL, NULL, 'New South Wales'),
(251, 'Lachlan Shire Council', NULL, NULL, 'New South Wales'),
(252, 'Lake Macquarie City Council', NULL, NULL, 'New South Wales'),
(253, 'Lane Cove Municipal Council', NULL, NULL, 'New South Wales'),
(254, 'Latrobe City Council', NULL, NULL, 'Victoria'),
(255, 'Latrobe Council', NULL, NULL, 'Tasmania'),
(256, 'Launceston City Council', NULL, NULL, 'Tasmania'),
(257, 'Leeton Shire Council', NULL, NULL, 'New South Wales'),
(258, 'Leichhardt Municipal Council', NULL, NULL, 'New South Wales'),
(259, 'Light Regional Council', NULL, NULL, 'South Australia'),
(260, 'Lismore City Council', NULL, NULL, 'New South Wales'),
(261, 'Litchfield Council', NULL, NULL, 'Northern Territory'),
(262, 'Liverpool City Council', NULL, NULL, 'New South Wales'),
(263, 'Liverpool Plains Shire Council', NULL, NULL, 'New South Wales'),
(264, 'Lockhart River Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(265, 'Lockhart Shire Council', NULL, NULL, 'New South Wales'),
(266, 'Lockyer Valley Regional Council', NULL, NULL, 'Queensland'),
(267, 'Loddon Shire Council', NULL, NULL, 'Victoria'),
(268, 'Logan City Council', NULL, NULL, 'Queensland'),
(269, 'Longreach Regional Council', NULL, NULL, 'Queensland'),
(270, 'MacDonnell Shire Council', NULL, NULL, 'Northern Territory'),
(271, 'Macedon Ranges Shire Council', NULL, NULL, 'Victoria'),
(272, 'Mackay Regional Council', NULL, NULL, 'Queensland'),
(273, 'Maitland City Council', NULL, NULL, 'New South Wales'),
(274, 'Manly Council', NULL, NULL, 'New South Wales'),
(276, 'Mansfield Shire Council', NULL, NULL, 'Victoria'),
(277, 'Mapoon Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(278, 'Maranoa Regional Council', NULL, NULL, 'Queensland'),
(279, 'City of Maribyrnong', NULL, NULL, 'Victoria'),
(280, 'City of Maroondah', NULL, NULL, 'Victoria'),
(281, 'Marrickville Council', NULL, NULL, 'New South Wales'),
(282, 'Shire of McKinlay', NULL, NULL, 'Queensland'),
(283, 'Meander Valley Council', NULL, NULL, 'Tasmania'),
(284, 'Melbourne City Council', NULL, NULL, 'Victoria'),
(285, 'City of Melton', NULL, NULL, 'Victoria'),
(286, 'Mid-Murray Council', NULL, NULL, 'South Australia'),
(287, 'Mid-Western Regional Council', NULL, NULL, 'New South Wales'),
(288, 'Mildura Rural City Council', NULL, NULL, 'Victoria'),
(289, 'Mitchell Shire Council', NULL, NULL, 'Victoria'),
(290, 'Moira Shire', NULL, NULL, 'Victoria'),
(291, 'City of Monash', NULL, NULL, 'Victoria'),
(292, 'Moonee Valley City Council', NULL, NULL, 'Victoria'),
(293, 'Moorabool Shire Council', NULL, NULL, 'Victoria'),
(294, 'Moree Plains Shire Council', NULL, NULL, 'New South Wales'),
(295, 'Moreland City Council', NULL, NULL, 'Victoria'),
(296, 'Moreton Bay Regional Council', NULL, NULL, 'Queensland'),
(297, 'Mornington Peninsula Shire Council', NULL, NULL, 'Victoria'),
(298, 'Mornington Shire Council', NULL, NULL, 'Queensland'),
(299, 'Mosman Municipal Council', NULL, NULL, 'New South Wales'),
(300, 'Mount Alexander Shire Council', NULL, NULL, 'Victoria'),
(301, 'Mount Isa City Council', NULL, NULL, 'Queensland'),
(302, 'Moyne Shire Council', NULL, NULL, 'Victoria'),
(303, 'Municipal Council of Roxby Downs', NULL, NULL, 'South Australia'),
(304, 'Murray Shire Council', NULL, NULL, 'New South Wales'),
(306, 'Murrindindi Shire Council', NULL, NULL, 'Victoria'),
(307, 'Murrumbidgee Shire Council', NULL, NULL, 'New South Wales'),
(308, 'Murweh Shire Council', NULL, NULL, 'Queensland'),
(309, 'Muswellbrook Shire Council', NULL, NULL, 'New South Wales'),
(310, 'Nambucca Shire Council', NULL, NULL, 'New South Wales'),
(311, 'Napranum Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(312, 'Naracoorte Lucindale Council', NULL, NULL, 'South Australia'),
(313, 'Narrabri Shire Council', NULL, NULL, 'New South Wales'),
(314, 'Narrandera Shire Council', NULL, NULL, 'New South Wales'),
(315, 'Narromine Shire Council', NULL, NULL, 'New South Wales'),
(316, 'Newcastle City Council', NULL, NULL, 'New South Wales'),
(317, 'Nillumbik Shire Council', NULL, NULL, 'Victoria'),
(318, 'North Burnett Regional Council', NULL, NULL, 'Queensland'),
(319, 'Northern Areas Council', NULL, NULL, 'South Australia'),
(320, 'Northern Grampians Shire Council', NULL, NULL, 'Victoria'),
(321, 'Northern Midlands Council', NULL, NULL, 'Tasmania'),
(322, 'Northern Peninsula Area Regional Council', NULL, NULL, 'Queensland'),
(323, 'North Sydney Council', NULL, NULL, 'New South Wales'),
(324, 'Oak Valley Community', NULL, NULL, 'South Australia'),
(325, 'Oberon Council', NULL, NULL, 'Victoria'),
(326, 'Orange City Council', NULL, NULL, 'New South Wales'),
(327, 'Queanbeyan-Palerang Regional Council', NULL, NULL, 'New South Wales'),
(328, 'Palm Island Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(329, 'Parkes Shire Council', NULL, NULL, 'New South Wales'),
(330, 'Paroo Shire Council', NULL, NULL, 'Queensland'),
(331, 'Parramatta City Council', NULL, NULL, 'New South Wales'),
(332, 'Penrith City Council', NULL, NULL, 'New South Wales'),
(333, 'Pittwater Council', NULL, NULL, 'New South Wales'),
(334, 'Pormpur aaw Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(335, 'Port Augusta City Council', NULL, NULL, 'South Australia'),
(336, 'Port Macquarie-Hastings Council', NULL, NULL, 'New South Wales'),
(337, 'Port Phillip City Council', NULL, NULL, 'Victoria'),
(338, 'Port Pirie Regional Council', NULL, NULL, 'South Australia'),
(339, 'Port Stephens Council', NULL, NULL, 'New South Wales'),
(340, 'Pyrenees Shire Council', NULL, NULL, 'Victoria'),
(342, 'Quilpie Shire Council', NULL, NULL, 'Queensland'),
(343, 'Randwick City Council', NULL, NULL, 'New South Wales'),
(344, 'Redland City Council', NULL, NULL, 'Queensland'),
(345, 'Regional Council of Goyder', NULL, NULL, 'South Australia'),
(346, 'Renmark Paringa Council', NULL, NULL, 'South Australia'),
(347, 'Richmond Shire Council', NULL, NULL, 'Queensland'),
(348, 'Richmond Valley Council', NULL, NULL, 'New South Wales'),
(349, 'Rockdale City Council', NULL, NULL, 'New South Wales'),
(350, 'Rockhampton Regional Council', NULL, NULL, 'Queensland'),
(351, 'Roper Gulf Shire Council', NULL, NULL, 'Northern Territory'),
(352, 'Rural City of Murray Bridge', NULL, NULL, 'South Australia'),
(353, 'Ryde City Council', NULL, NULL, 'New South Wales'),
(354, 'Scenic Rim Regional Council', NULL, NULL, 'Queensland'),
(355, 'Serpentine Jarrahdale Shire Council', NULL, NULL, 'Western Australia'),
(356, 'Shellharbour City Council', NULL, NULL, 'New South Wales'),
(357, 'Shire of Ashburton', NULL, NULL, 'Western Australia'),
(358, 'Shire of Augusta-Margaret River', NULL, NULL, 'Western Australia'),
(359, 'Shire of Beverley', NULL, NULL, 'Western Australia'),
(360, 'Shire of Boddington', NULL, NULL, 'Western Australia'),
(361, 'Shire of Boyup Brook', NULL, NULL, 'Western Australia'),
(362, 'Shire of Bridgetown-Greenbushes', NULL, NULL, 'Western Australia'),
(363, 'Shire of Brookton', NULL, NULL, 'Western Australia'),
(364, 'Shire of Broome', NULL, NULL, 'Western Australia'),
(365, 'Shire of Broomehill-Tambellup', NULL, NULL, 'Western Australia'),
(366, 'Shire of Bruce Rock', NULL, NULL, 'Western Australia'),
(367, 'Shire of Capel', NULL, NULL, 'Western Australia'),
(368, 'Shire of Carnamah', NULL, NULL, 'Western Australia'),
(369, 'Shire of Carnarvon', NULL, NULL, 'Western Australia'),
(370, 'Shire of Chapman Valley', NULL, NULL, 'Western Australia'),
(371, 'Shire of Chittering', NULL, NULL, 'Western Australia'),
(372, 'Shire of Collie', NULL, NULL, 'Western Australia'),
(373, 'Shire of Coolgardie', NULL, NULL, 'Western Australia'),
(374, 'Shire of Coorow', NULL, NULL, 'Western Australia'),
(375, 'Shire Of Corrigin', NULL, NULL, 'Western Australia'),
(376, 'Shire of Cranbrook', NULL, NULL, 'Western Australia'),
(377, 'Shire of Cuballing', NULL, NULL, 'Western Australia'),
(378, 'Shire of Cue', NULL, NULL, 'Western Australia'),
(379, 'Shire of Cunderdin', NULL, NULL, 'Western Australia'),
(380, 'Shire of Dalwallinu', NULL, NULL, 'Western Australia'),
(381, 'Shire of Dandaragan', NULL, NULL, 'Western Australia'),
(382, 'Shire of Dardanup', NULL, NULL, 'Western Australia'),
(383, 'Shire of Denmark', NULL, NULL, 'Western Australia'),
(384, 'Shire of Derby/West Kimberley', NULL, NULL, 'Western Australia'),
(385, 'Shire of Donnybrook-Balingup', NULL, NULL, 'Western Australia'),
(386, 'Shire of Dowerin', NULL, NULL, 'Western Australia'),
(387, 'Shire of Dumbleyung', NULL, NULL, 'Western Australia'),
(388, 'Shire of Dundas', NULL, NULL, 'Western Australia'),
(389, 'Shire of East Pilbara', NULL, NULL, 'Western Australia'),
(390, 'Shire of Esperance', NULL, NULL, 'Western Australia'),
(391, 'Shire of Exmouth', NULL, NULL, 'Western Australia'),
(392, 'Shire of Gingin', NULL, NULL, 'Western Australia'),
(393, 'Shire of Gnowangerup', NULL, NULL, 'Western Australia'),
(394, 'Shire of Goomalling', NULL, NULL, 'Western Australia'),
(395, 'Shire of Halls Creek', NULL, NULL, 'Western Australia'),
(396, 'Shire of Harvey', NULL, NULL, 'Western Australia'),
(397, 'Shire of Irwin', NULL, NULL, 'Western Australia'),
(398, 'Shire of Jerramungup', NULL, NULL, 'Western Australia'),
(399, 'Kalamunda Shire Council', NULL, NULL, 'Western Australia'),
(400, 'Shire of Katanning', NULL, NULL, 'Western Australia'),
(401, 'Shire of Kellerberrin', NULL, NULL, 'Western Australia'),
(402, 'Shire of Kent', NULL, NULL, 'Western Australia'),
(403, 'Shire of Kojonup', NULL, NULL, 'Western Australia'),
(404, 'Shire of Kondinin', NULL, NULL, 'Western Australia'),
(405, 'Shire of Koorda', NULL, NULL, 'Western Australia'),
(406, 'Shire of Kulin', NULL, NULL, 'Western Australia'),
(407, 'Shire of Lake Grace', NULL, NULL, 'Western Australia'),
(408, 'Shire of Laverton', NULL, NULL, 'Western Australia'),
(409, 'Shire of Leonora', NULL, NULL, 'Western Australia'),
(410, 'Shire of Manjimup', NULL, NULL, 'Western Australia'),
(411, 'Shire of Meekatharra', NULL, NULL, 'Western Australia'),
(412, 'Shire of Menzies', NULL, NULL, 'Western Australia'),
(413, 'Shire of Merredin', NULL, NULL, 'Western Australia'),
(414, 'Shire of Mingenew', NULL, NULL, 'Western Australia'),
(415, 'Shire of Moora', NULL, NULL, 'Western Australia'),
(416, 'Shire of Morawa', NULL, NULL, 'Western Australia'),
(417, 'Shire of Mount Magnet', NULL, NULL, 'Western Australia'),
(418, 'Shire of Mt Marshall', NULL, NULL, 'Western Australia'),
(419, 'Shire of Mukinbudin', NULL, NULL, 'Western Australia'),
(420, 'Shire of Mundaring', NULL, NULL, 'Western Australia'),
(421, 'Shire of Murchison', NULL, NULL, 'Western Australia'),
(422, 'Shire of Nannup', NULL, NULL, 'Western Australia'),
(423, 'Shire of Narembeen', NULL, NULL, 'Western Australia'),
(424, 'Shire of Narrogin', NULL, NULL, 'Western Australia'),
(425, 'Shire of Ngaanyatjarraku', NULL, NULL, 'Western Australia'),
(426, 'Shire of Northam', NULL, NULL, 'Western Australia'),
(427, 'Shire of Northampton', NULL, NULL, 'Western Australia'),
(428, 'Shire of Nungarin', NULL, NULL, 'Western Australia'),
(429, 'Shire of Peppermint Grove', NULL, NULL, 'Western Australia'),
(430, 'Shire of Perenjori', NULL, NULL, 'Western Australia'),
(431, 'Shire of Pingelly', NULL, NULL, 'Western Australia'),
(432, 'Shire of Plantagenet', NULL, NULL, 'Western Australia'),
(433, 'Shire of Quairading', NULL, NULL, 'Western Australia'),
(434, 'Shire of Ravensthorpe', NULL, NULL, 'Western Australia'),
(435, 'Shire of Roebourne', NULL, NULL, 'Western Australia'),
(436, 'Shire of Sandstone', NULL, NULL, 'Western Australia'),
(437, 'Shire of Shark Bay', NULL, NULL, 'Western Australia'),
(438, 'Shire of Tammin', NULL, NULL, 'Western Australia'),
(439, 'Shire of Three Springs', NULL, NULL, 'Western Australia'),
(440, 'Shire of Toodyay', NULL, NULL, 'Western Australia'),
(441, 'Shire of Trayning', NULL, NULL, 'Western Australia'),
(442, 'Shire of Upper Gascoyne', NULL, NULL, 'Western Australia'),
(443, 'Shire of Victoria Plains', NULL, NULL, 'Western Australia'),
(444, 'Shire of Wagin', NULL, NULL, 'Western Australia'),
(445, 'Shire of Wandering', NULL, NULL, 'Western Australia'),
(446, 'Shire of Waroona', NULL, NULL, 'Western Australia'),
(447, 'Shire of West Arthur', NULL, NULL, 'Western Australia'),
(448, 'Shire of Westonia', NULL, NULL, 'Western Australia'),
(449, 'Shire of Wickepin', NULL, NULL, 'Western Australia'),
(450, 'Shire of Williams', NULL, NULL, 'Western Australia'),
(451, 'Shire of Wiluna', NULL, NULL, 'Western Australia'),
(452, 'Shire of Wongan-Ballidu', NULL, NULL, 'Western Australia'),
(453, 'Shire of Woodanilling', NULL, NULL, 'Western Australia'),
(454, 'Shire of Wyalkatchem', NULL, NULL, 'Western Australia'),
(455, 'Shire of Wyndham East Kimberley', NULL, NULL, 'Western Australia'),
(456, 'Shire of Yalgoo', NULL, NULL, 'Western Australia'),
(457, 'Shire of Yilgarn', NULL, NULL, 'Western Australia'),
(458, 'Shire of York', NULL, NULL, 'Western Australia'),
(459, 'City of Shoalhaven', NULL, NULL, 'New South Wales'),
(460, 'Singleton Council', NULL, NULL, 'New South Wales'),
(461, 'Snowy River Shire Council', NULL, NULL, 'New South Wales'),
(462, 'Somerset Regional Council', NULL, NULL, 'Queensland'),
(463, 'Sorell Council', NULL, NULL, 'Tasmania'),
(464, 'South Burnett Regional Council', NULL, NULL, 'Queensland'),
(465, 'Southern Downs Regional Council', NULL, NULL, 'Queensland'),
(466, 'Southern Grampians Shire Council', NULL, NULL, 'Victoria'),
(467, 'Southern Mallee District Council', NULL, NULL, 'South Australia'),
(468, 'Southern Midlands Council', NULL, NULL, 'Tasmania'),
(469, 'South Gippsland Shire Council', NULL, NULL, 'Victoria'),
(470, 'Stonnington City Council', NULL, NULL, 'Victoria'),
(471, 'Strathbogie Shire Council', NULL, NULL, 'Victoria'),
(472, 'Strathfield Municipal Council', NULL, NULL, 'New South Wales'),
(473, 'Sunshine Coast Regional Council', NULL, NULL, 'Queensland'),
(474, 'Surf Coast Shire Council', NULL, NULL, 'Victoria'),
(475, 'Sutherland Shire', NULL, NULL, 'New South Wales'),
(476, 'Swan Hill Rural City Council', NULL, NULL, 'Victoria'),
(477, 'Tablelands Regional Council', NULL, NULL, 'Queensland'),
(478, 'Tamworth Regional Council', NULL, NULL, 'New South Wales'),
(479, 'Tasman Council', NULL, NULL, 'Tasmania'),
(480, 'Tatiara District Council', NULL, NULL, 'South Australia'),
(481, 'Temora Shire Council', NULL, NULL, 'New South Wales'),
(482, 'Tenterfield Shire Council', NULL, NULL, 'New South Wales'),
(483, 'Bayside Council', NULL, NULL, 'New South Wales'),
(485, 'Hunter\'s Hill Council', NULL, NULL, 'New South Wales'),
(486, 'Kiama Municipal Council', NULL, NULL, 'New South Wales'),
(487, 'Hornsby Shire Council', NULL, NULL, 'New South Wales'),
(489, 'The Hills Shire', NULL, NULL, 'New South Wales'),
(490, 'Tiwi Islands Shire Council', NULL, NULL, 'Northern Territory'),
(491, 'Toowoomba Regional Council', NULL, NULL, 'Queensland'),
(492, 'Torres Shire Council', NULL, NULL, 'Queensland'),
(493, 'Torres Strait Island Regional Council', NULL, NULL, 'Queensland'),
(494, 'Town of Bassendean', NULL, NULL, 'Western Australia'),
(495, 'Town of Cambridge', NULL, NULL, 'Western Australia'),
(496, 'Town of Claremont', NULL, NULL, 'Western Australia'),
(497, 'Town of Cottesloe', NULL, NULL, 'Western Australia'),
(498, 'Town of East Fremantle', NULL, NULL, 'Western Australia'),
(499, 'Town of Gawler Council', NULL, NULL, 'South Australia'),
(500, 'Town of Mosman Park', NULL, NULL, 'Western Australia'),
(501, 'Town of Narrogin', NULL, NULL, 'Western Australia'),
(502, 'Town of Port Hedland', NULL, NULL, 'Western Australia'),
(503, 'Town of Victoria Park', NULL, NULL, 'Western Australia'),
(504, 'Townsville City Council', NULL, NULL, 'Queensland'),
(505, 'Towong Shire', NULL, NULL, 'Victoria'),
(506, 'Snowy Valleys Council', NULL, NULL, 'New South Wales'),
(508, 'Tweed Shire Council', NULL, NULL, 'New South Wales'),
(509, 'Upper Hunter Shire Council', NULL, NULL, 'New South Wales'),
(510, 'Upper Lachlan Shire Council', NULL, NULL, 'New South Wales'),
(511, 'Uralla Shire Council', NULL, NULL, 'New South Wales'),
(512, 'Urana Shire Council', NULL, NULL, 'New South Wales'),
(513, 'Victoria Daly Shire Council', NULL, NULL, 'Northern Territory'),
(514, 'Wagait Shire Council', NULL, NULL, 'Northern Territory'),
(515, 'Wagga Wagga City Council', NULL, NULL, 'New South Wales'),
(516, 'Wakefield Regional Council', NULL, NULL, 'South Australia'),
(517, 'Walcha Council', NULL, NULL, 'New South Wales'),
(518, 'Walgett Shire Council', NULL, NULL, 'New South Wales'),
(519, 'Wangaratta Rural City Council', NULL, NULL, 'Victoria'),
(520, 'Waratah-Wynyard Council', NULL, NULL, 'Tasmania'),
(521, 'Warren Shire Council', NULL, NULL, 'New South Wales'),
(522, 'Northern Beaches Council', NULL, NULL, 'New South Wales'),
(523, 'Warrnambool City Council', NULL, NULL, 'Victoria'),
(524, 'Warrumbungle Shire Council', NULL, NULL, 'New South Wales'),
(525, 'Wattle Range Council', NULL, NULL, 'South Australia'),
(526, 'Waverley Council', NULL, NULL, 'New South Wales'),
(527, 'Weddin Shire Council', NULL, NULL, 'New South Wales'),
(528, 'Weipa Town Authority', NULL, NULL, 'Queensland'),
(530, 'Wellington Shire Council', NULL, NULL, 'Victoria'),
(531, 'Wentworth Shire Council', NULL, NULL, 'New South Wales'),
(532, 'Western Downs Regional Council', NULL, NULL, 'Queensland'),
(533, 'West Tamar Council', NULL, NULL, 'Tasmania'),
(534, 'West Wimmera Shire Council', NULL, NULL, 'Victoria'),
(535, 'Whitehorse City Council', NULL, NULL, 'Victoria'),
(536, 'Whitsunday Regional Council', NULL, NULL, 'Queensland'),
(537, 'Whittlesea City Council', NULL, NULL, 'Victoria'),
(538, 'Willoughby City Council', NULL, NULL, 'New South Wales'),
(539, 'Wingecarribee Shire Council', NULL, NULL, 'New South Wales'),
(540, 'Winton Shire Council', NULL, NULL, 'Queensland'),
(541, 'Wodonga City Council', NULL, NULL, 'Victoria'),
(542, 'Wollondilly Shire Council', NULL, NULL, 'New South Wales'),
(543, 'Wollongong City Council', NULL, NULL, 'New South Wales'),
(544, 'Woollahra Municipal Council', NULL, NULL, 'New South Wales'),
(545, 'Woorabinda Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(546, 'Wudinna District Council', NULL, NULL, 'South Australia'),
(547, 'Wujal Wujal Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(548, 'Wyndham City Council', NULL, NULL, 'Victoria'),
(549, 'Wyong Shire Council', NULL, NULL, 'New South Wales'),
(550, 'Yarrabah Aboriginal Shire Council', NULL, NULL, 'Queensland'),
(551, 'Yarra City Council', NULL, NULL, 'Victoria'),
(552, 'Yarra Ranges Shire Council', NULL, NULL, 'Victoria'),
(553, 'Yarriambiack Shire Council', NULL, NULL, 'Victoria'),
(554, 'Yass Valley Council', NULL, NULL, 'New South Wales'),
(555, 'Young Shire Council', NULL, NULL, 'New South Wales');

-- --------------------------------------------------------

--
-- Table structure for table `defect_type`
--

CREATE TABLE `defect_type` (
  `id` int(3) NOT NULL,
  `defect` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `defect_type`
--

INSERT INTO `defect_type` (`id`, `defect`) VALUES
(1, 'Abandoned Vehicle'),
(2, 'Blocked Drain'),
(3, 'Bus Stop'),
(4, 'Car Parking'),
(5, 'Dog Dropping'),
(6, 'Graffiti'),
(7, 'Illegal Advertising'),
(8, 'Illegal Dumping'),
(9, 'Park/Landscape'),
(10, 'Pavement/Footpath'),
(11, 'Pothole'),
(12, 'Public Right of Way'),
(13, 'Public Toilet'),
(14, 'Road/Highway'),
(15, 'Street Cleaning'),
(16, 'Street Lighting'),
(17, 'Street Signs'),
(18, 'Traffic Lights'),
(19, 'Trees'),
(20, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `manifest_theme`
--

CREATE TABLE `manifest_theme` (
  `id` int(11) NOT NULL,
  `cobrand` text NOT NULL,
  `name` text NOT NULL,
  `short_name` text NOT NULL,
  `background_colour` text DEFAULT NULL,
  `theme_colour` text DEFAULT NULL,
  `images` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `moderation_original_data`
--

CREATE TABLE `moderation_original_data` (
  `id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `title` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` blob DEFAULT NULL,
  `anonymous` tinyint(1) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `extra` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partial_user`
--

CREATE TABLE `partial_user` (
  `id` int(11) NOT NULL,
  `service` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `nsid` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `photos`
--



-- --------------------------------------------------------

--
-- Table structure for table `poi`
--

CREATE TABLE `poi` (
  `poi` varchar(255) NOT NULL,
  `search` varchar(255) NOT NULL,
  `lat` float(8,6) NOT NULL,
  `lng` float(9,6) NOT NULL,
  `address` varchar(255) NOT NULL,
  `council` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `poi`
--

INSERT INTO `poi` (`poi`, `search`, `lat`, `lng`, `address`, `council`) VALUES
('ChIJ-3pOOTLVo2sR1Gk_rbHN_gU', '-29.538941,150.57746', -29.538567, 150.577408, '140-142 Long St, Warialda NSW 2402, Australia', 'Gwydir Shire Council'),
('ChIJ0XPdpM4CE2sRxnsODvKOd2Q', '-34.292542,150.752662', -34.288647, 150.761551, 'Unnamed Road, Cataract NSW 2560, Australia', 'Wollondilly Shire Council'),
('ChIJ21ok8U_3oWsRm200Mz8HIqw', '-29.774921,151.127589', -29.774994, 151.127731, '72 Greaves St, Inverell NSW 2360, Australia', 'Inverell Shire Council'),
('ChIJ32rtuzX3oWsRfFXdsHx89HI', '-29.778728,151.115254', -29.778679, 151.115158, '149 Otho St, Inverell NSW 2360, Australia', 'Inverell Shire Council'),
('ChIJ53mjG1keE2sRgCUuKvKmRP8', '-34.325297,150.893596', -34.333111, 150.892456, 'Unnamed Road, Woonona NSW 2517, Australia', 'Wollongong City Council'),
('ChIJ5U-UID8OE2sR6AwtJCswlkg', '-34.481378,150.7494', -34.478901, 150.747253, 'Unnamed Road, Wongawilli NSW 2530, Australia', 'Wollongong City Council'),
('ChIJ625JicCKoWsRbntJJH1oGa4', '-29.675956,150.933075', -29.676428, 150.933975, '1443 Oakwood Rd, Mount Russell NSW 2360, Australia', 'Inverell Shire Council'),
('ChIJ718okRQPE2sRphgBLmytovQ', '-34.438067,150.694812', -34.431480, 150.702530, 'Unnamed Road, Avon NSW 2574, Australia', 'Wollongong City Council'),
('ChIJ7Ye3tLsHE2sR8g1e304iayM', '-34.364415,150.628207', -34.360523, 150.615219, 'Firetrail No 1, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJ90ArF7N7FGsRbGezFVVK7Uk', '-34.936894,150.444186', -34.935745, 150.443771, 'Yalwal Rd, Yalwal NSW 2540, Australia', 'City of Shoalhaven'),
('ChIJ9_PoWYMEE2sRxPifNmLyv6Q', '-34.343158,150.730861', -34.341927, 150.732254, 'Firetrail No 6, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJA34hGF0EE2sR9GmZpq6NJ5k', '-34.355063,150.708888', -34.357582, 150.715866, 'Firetrail No 6, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJATZ837iJoWsRmf-cIUPGfBk', '28 Burnett St, Delungra NSW', -29.653366, 150.830612, '28 Burnett St, Delungra NSW 2403, Australia', 'Inverell Shire Council'),
('ChIJAWBssCf3oWsR7eIU1rRqF0I', '-29.764679,151.096642', -29.765154, 151.095963, '94 Vernon St, Inverell NSW 2360, Australia', 'Inverell Shire Council'),
('ChIJb8Lys1yPoWsRqD7ZQh35nes', '-29.716739,150.782485', -29.718664, 150.779297, 'Bingara Rd, Delungra NSW 2403, Australia', 'Inverell Shire Council'),
('ChIJBWMWErgFE2sR_BewPAB9vFo', '-34.3749,150.703738', -34.380035, 150.698990, 'Fire Rd No 6a, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJbzzGhLVsE2sRTwwsV6sM43Q', '-34.611741,150.837291', -34.610958, 150.838348, '31 Princes Hwy, Dunmore NSW 2529, Australia', 'Shellharbour City Council'),
('ChIJCfyBjujHEmsRVNfAkoNzW4g', '-34.049251,151.127742', -34.049324, 151.127579, '120 Yathong Rd, Caringbah South NSW 2229, Australia', 'Sutherland Shire'),
('ChIJcxhJ74UKE2sR7CLzUbEnlvM', '-34.49298,150.593875', -34.512241, 150.593430, 'Firetrail No 1d, East Kangaloon NSW 2576, Australia', 'Wingecarribee Shire Council'),
('ChIJd5sNLk_3oWsRVJmNz_OLPw4', '-29.775219,151.123318', -29.775589, 151.123245, '51 Granville St, Inverell NSW 2360, Australia', 'Inverell Shire Council'),
('ChIJDW5ZYH0GE2sRtDw8b83pJMk', '-34.377451,150.660823', -34.360302, 150.666000, 'Firetrail No 6y, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJf-te5WProWsRUMcMFhfk9p0', '-29.916984,150.995903', -29.924606, 150.983841, 'Unnamed Road, Bundarra NSW 2359, Australia', 'Gwydir Shire Council'),
('ChIJfTlsEckFE2sRSJYcATCs4X8', '-34.3715,150.691035', -34.375053, 150.693115, 'Fire Rd No 6a, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJFVA4jUb3oWsRuST2t-ueY80', '-29.770712,151.122374', -29.769888, 151.121445, '160 Evans St, Inverell NSW 2360, Australia', 'Inverell Shire Council'),
('ChIJG0hUuswFE2sReMYPybXGyMg', '-34.366399,150.691722', -34.367630, 150.691391, 'Fire Rd No 6a, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJg3Da6DkdE2sRdAq-Qsi1V9Q', '-34.296372,150.778926', -34.291447, 150.782532, 'Firetrail No 8, Cataract NSW 2560, Australia', 'Wollondilly Shire Council'),
('ChIJGRV72VqPoWsRZjN4ty84rNE', '-29.712732,150.777721', -29.713921, 150.778534, 'Unnamed Road, Delungra NSW 2403, Australia', 'Inverell Shire Council'),
('ChIJh2IoebsIE2sRUK_000xvPIk', '-34.417961,150.674556', -34.411629, 150.659164, 'Firetrail No 1f, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJhUIuY5SgE2sRoGW7crQJBhM', '-34.485623,150.560573', -34.481243, 150.560684, 'Firetrail No 2, Mount Lindsey NSW 2575, Australia', 'Wingecarribee Shire Council'),
('ChIJI3GedE9jFGsRsOH1Vq57Cd0', '-35.003008,150.505298', -35.015240, 150.512009, 'Hell Hole Firetrail, Yerriyong NSW 2540, Australia', 'City of Shoalhaven'),
('ChIJJ2xhn1r3oWsRbeH13kczvCk', '47 Mulligan St', -29.774940, 151.130142, '47 Mulligan St, Inverell NSW 2360, Australia', 'Inverell Shire Council'),
('ChIJj5UAlxyAFGsRG2Jg8TLzfx0', '-34.871008,150.595248', -34.871845, 150.595291, '14 North St, Nowra NSW 2541, Australia', 'City of Shoalhaven'),
('ChIJkVgrgaMfE2sRkKuW5Wd9ARM', '-34.343158,150.946467', -34.339195, 150.925095, 'Unnamed Road, Bulli NSW 2516, Australia', 'Wollongong City Council'),
('ChIJlbWKv-IEE2sRlK38MWzCzZw', '-34.374334,150.721248', -34.375423, 150.732376, 'Unnamed Road, Cordeaux NSW 2526, Australia', 'Wollongong City Council'),
('ChIJp9J_k0KuEmsRUOXv-Wh9AQ8', '140 george st, sydney nsw', -33.859695, 151.209061, '140 George St, The Rocks NSW 2000, Australia', 'Council of the City of Sydney'),
('ChIJPx0fFtIHE2sR8iiHL7f5Fzo', '-34.391,150.6442', -34.380829, 150.621933, 'Firetrail No 1, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJPZAvhOIGE2sRNFTGeUBFuh4', '-34.343441,150.631297', -34.339966, 150.627609, 'Avon Dam Rd, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJQ06_6hMFE2sRfmUP52Fghz8', '-34.3953,150.732234', -34.395252, 150.737137, 'Firetrail No 6f, Cordeaux NSW 2526, Australia', 'Wollongong City Council'),
('ChIJQ6LTFpAIE2sRpuBVAui1GHM', '-34.397,150.644', -34.406200, 150.638138, 'Firetrail No 1, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJqWxqNp4cE2sRNgqZgWu_BVA', '-34.352795,150.794032', -34.350639, 150.795074, 'Unnamed Road, Cordeaux NSW 2526, Australia', 'Wollongong City Council'),
('ChIJRVZFKbmJoWsRFCAZUNPtSoE', '-29.653798,150.83145', -29.653877, 150.831451, '38 Burnett St, Delungra NSW 2403, Australia', 'Inverell Shire Council'),
('ChIJt1JtIT-uEmsRC0b2e2VCR6o', 'george st, sydney nsw', -33.870178, 151.206955, 'George St, Sydney NSW, Australia', 'Council of the City of Sydney'),
('ChIJT4Wq4biJoWsRJUVI6J9g-w8', '-29.653043,150.83059', -29.653145, 150.830200, '26 Burnett St, Delungra NSW 2403, Australia', 'Inverell Shire Council'),
('ChIJTV_lZyUEE2sRjjAVPUznN5A', '-34.355063,150.690692', -34.354424, 150.684738, 'Firetrail No 6b, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJv0jmKLmJoWsRn5uV-VvaqxI', '-29.653791,150.831404', -29.653788, 150.831284, '36 Burnett St, Delungra NSW 2403, Australia', 'Inverell Shire Council'),
('ChIJW-7IWHIPE2sRerZH-gHh9uY', '-34.429571,150.709918', -34.430202, 150.711807, 'Unnamed Road, Avon NSW 2574, Australia', 'Wollongong City Council'),
('ChIJwd07DbmJoWsR5j4wIM8JUxc', '-29.653925,150.829654', -29.653790, 150.829727, '8 Gunnee St, Delungra NSW 2403, Australia', 'Inverell Shire Council'),
('ChIJwXfOJhYHE2sRFtJNOa5SJMs', '-34.331534,150.612414', -34.325748, 150.614868, 'Firetrail No 5e, Bargo NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJxWe4vEX3oWsRRXMm877MhJ0', '-29.772481,151.125293', -29.772348, 151.124954, '120 Henderson St, Inverell NSW 2360, Australia', 'Inverell Shire Council'),
('ChIJX_uY6X_AEmsRRiu_HGnP5VA', '-34,151', -34.000172, 150.999557, 'Unnamed Road, Menai NSW 2234, Australia', 'Sutherland Shire'),
('ChIJYf-CyBAJE2sRSiTMa_rOrLQ', '-34.436934,150.636104', -34.452106, 150.647461, 'Firetrail No 1, Avon NSW 2574, Australia', 'Wingecarribee Shire Council'),
('ChIJyTbciyT3oWsRrXmpB7bPqks', '-29.764223,151.102983', -29.764324, 151.102768, '131 Yetman Rd, Inverell NSW 2360, Australia', 'Inverell Shire Council'),
('ChIJZ2x9RlbsoWsR4Ilkx0EdlFY', '-29.896449,151.006546', -29.889769, 151.003204, 'Auburn Vale Rd, Copeton NSW 2360, Australia', 'Inverell Shire Council'),
('ChIJz85cII0PE2sRZXTF57n1uM0', '-34.473736,150.752833', -34.472607, 150.755585, '30 Vista Pkwy, Wongawilli NSW 2530, Australia', 'Wollongong City Council');

-- --------------------------------------------------------

--
-- Table structure for table `problem`
--

CREATE TABLE `problem` (
  `id` int(11) NOT NULL,
  `latitude` float(8,6) NOT NULL,
  `longitude` float(9,6) NOT NULL,
  `address` varchar(255) NOT NULL,
  `council` varchar(255) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL DEFAULT 1,
  `anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed` timestamp NULL DEFAULT NULL,
  `state` int(2) NOT NULL DEFAULT 1,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `extra` text DEFAULT NULL,
  `non_public` tinyint(1) NOT NULL DEFAULT 0,
  `interest_count` int(11) NOT NULL DEFAULT 0,
  `defect_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `problem`
--



-- --------------------------------------------------------

--
-- Table structure for table `questionnaire`
--

CREATE TABLE `questionnaire` (
  `id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `whensent` timestamp NULL DEFAULT NULL,
  `whenanswered` timestamp NULL DEFAULT NULL,
  `ever_reported` tinyint(1) DEFAULT NULL,
  `old_state` text DEFAULT NULL,
  `new_state` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `report_extra_fields`
--

CREATE TABLE `report_extra_fields` (
  `id` int(11) NOT NULL,
  `name` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cobrand` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `response_priorities`
--

CREATE TABLE `response_priorities` (
  `id` int(11) NOT NULL,
  `body_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `name` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_id` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `response_templates`
--

CREATE TABLE `response_templates` (
  `id` int(11) NOT NULL,
  `body_id` int(11) NOT NULL,
  `title` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `auto_response` tinyint(1) NOT NULL DEFAULT 0,
  `state` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_status_code` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `body_id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `permissions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` int(11) NOT NULL,
  `site` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `site`) VALUES
(1, 'FixMyStreet.net Desktop'),
(2, 'FixMyStreet Android App');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `label` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_colour` enum('red','orange','yellow','grey','green') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'red',
  `name` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id`, `label`, `type`, `icon_colour`, `name`) VALUES
(1, 'investigating', 'open', 'red', 'Investigating'),
(2, 'in progress', 'open', 'orange', 'In progress'),
(3, 'planned', 'open', 'orange', 'Planned'),
(4, 'action scheduled', 'open', 'yellow', 'Action scheduled'),
(5, 'unable to fix', 'closed', 'grey', 'No further action'),
(6, 'not responsible', 'closed', 'grey', 'Not responsible'),
(7, 'duplicate', 'closed', 'grey', 'Duplicate'),
(8, 'internal referral', 'closed', 'yellow', 'Internal referral'),
(9, 'fixed', 'fixed', 'green', 'Fixed');

-- --------------------------------------------------------

--
-- Table structure for table `textmystreet`
--

CREATE TABLE `textmystreet` (
  `name` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `user_id` int(11) NOT NULL,
  `token` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('signup','reset') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'signup'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translation`
--

CREATE TABLE `translation` (
  `id` int(11) NOT NULL,
  `tbl` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` int(11) NOT NULL,
  `col` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `msgstr` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` text DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `phone_verified` tinyint(1) DEFAULT NULL,
  `password` varchar(255) DEFAULT '',
  `from_body` int(11) DEFAULT NULL,
  `flagged` tinyint(1) DEFAULT NULL,
  `is_superuser` tinyint(1) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_active` timestamp NOT NULL DEFAULT current_timestamp(),
  `title` text DEFAULT NULL,
  `twitter_id` bigint(20) DEFAULT NULL,
  `facebook_id` bigint(20) DEFAULT NULL,
  `oidc_ids` text DEFAULT NULL,
  `area_ids` int(11) DEFAULT NULL,
  `extra` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--



-- --------------------------------------------------------

--
-- Table structure for table `user_body_permissions`
--

CREATE TABLE `user_body_permissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body_id` int(11) NOT NULL,
  `permission_type` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_planned_reports`
--

CREATE TABLE `user_planned_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `added` timestamp NOT NULL DEFAULT current_timestamp(),
  `removed` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abuse`
--
ALTER TABLE `abuse`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alert`
--
ALTER TABLE `alert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alert_sent`
--
ALTER TABLE `alert_sent`
  ADD KEY `alert_id` (`alert_id`);

--
-- Indexes for table `alert_type`
--
ALTER TABLE `alert_type`
  ADD PRIMARY KEY (`ref`);

--
-- Indexes for table `body`
--
ALTER TABLE `body`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `body_areas`
--
ALTER TABLE `body_areas`
  ADD KEY `body_id` (`body_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `comment_ibfk_2` (`problem_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts_history`
--
ALTER TABLE `contacts_history`
  ADD PRIMARY KEY (`contacts_history_id`);

--
-- Indexes for table `contact_defect_types`
--
ALTER TABLE `contact_defect_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `defect_type_id` (`defect_type_id`);

--
-- Indexes for table `contact_response_priorities`
--
ALTER TABLE `contact_response_priorities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Indexes for table `contact_response_templates`
--
ALTER TABLE `contact_response_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Indexes for table `councils`
--
ALTER TABLE `councils`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `council_name` (`council_name`),
  ADD KEY `state` (`state`);

--
-- Indexes for table `defect_type`
--
ALTER TABLE `defect_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manifest_theme`
--
ALTER TABLE `manifest_theme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moderation_original_data`
--
ALTER TABLE `moderation_original_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `problem_id` (`problem_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indexes for table `partial_user`
--
ALTER TABLE `partial_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `problem_id` (`problem_id`);

--
-- Indexes for table `poi`
--
ALTER TABLE `poi`
  ADD PRIMARY KEY (`poi`),
  ADD UNIQUE KEY `search` (`search`);

--
-- Indexes for table `problem`
--
ALTER TABLE `problem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `state` (`state`),
  ADD KEY `problem_ibfk_2` (`defect_id`),
  ADD KEY `problem_ibfk_3` (`address`),
  ADD KEY `latitude` (`latitude`),
  ADD KEY `longitude` (`longitude`),
  ADD KEY `site_id` (`site_id`);

--
-- Indexes for table `questionnaire`
--
ALTER TABLE `questionnaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `problem_id` (`problem_id`);

--
-- Indexes for table `report_extra_fields`
--
ALTER TABLE `report_extra_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `response_priorities`
--
ALTER TABLE `response_priorities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `body_id` (`body_id`);

--
-- Indexes for table `response_templates`
--
ALTER TABLE `response_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `body_id` (`body_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `body_id` (`body_id`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`),
  ADD KEY `icon_colour` (`icon_colour`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `translation`
--
ALTER TABLE `translation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_body_permissions`
--
ALTER TABLE `user_body_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `body_id` (`body_id`);

--
-- Indexes for table `user_planned_reports`
--
ALTER TABLE `user_planned_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `alert`
--
ALTER TABLE `alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `body`
--
ALTER TABLE `body`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contacts_history`
--
ALTER TABLE `contacts_history`
  MODIFY `contacts_history_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contact_response_priorities`
--
ALTER TABLE `contact_response_priorities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contact_response_templates`
--
ALTER TABLE `contact_response_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `councils`
--
ALTER TABLE `councils`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=556;
--
-- AUTO_INCREMENT for table `defect_type`
--
ALTER TABLE `defect_type`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `manifest_theme`
--
ALTER TABLE `manifest_theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `moderation_original_data`
--
ALTER TABLE `moderation_original_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `partial_user`
--
ALTER TABLE `partial_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `problem`
--
ALTER TABLE `problem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `questionnaire`
--
ALTER TABLE `questionnaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `report_extra_fields`
--
ALTER TABLE `report_extra_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `response_priorities`
--
ALTER TABLE `response_priorities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `response_templates`
--
ALTER TABLE `response_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `translation`
--
ALTER TABLE `translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `user_body_permissions`
--
ALTER TABLE `user_body_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_planned_reports`
--
ALTER TABLE `user_planned_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `alert_sent`
--
ALTER TABLE `alert_sent`
  ADD CONSTRAINT `alert_sent_ibfk_1` FOREIGN KEY (`alert_id`) REFERENCES `alert` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `body_areas`
--
ALTER TABLE `body_areas`
  ADD CONSTRAINT `body_areas_ibfk_1` FOREIGN KEY (`body_id`) REFERENCES `body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `contact_defect_types`
--
ALTER TABLE `contact_defect_types`
  ADD CONSTRAINT `contact_defect_types_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `contact_defect_types_ibfk_2` FOREIGN KEY (`defect_type_id`) REFERENCES `contact_defect_types` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `contact_response_priorities`
--
ALTER TABLE `contact_response_priorities`
  ADD CONSTRAINT `contact_response_priorities_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `contact_response_templates`
--
ALTER TABLE `contact_response_templates`
  ADD CONSTRAINT `contact_response_templates_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `moderation_original_data`
--
ALTER TABLE `moderation_original_data`
  ADD CONSTRAINT `moderation_original_data_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `moderation_original_data_ibfk_2` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `problem`
--
ALTER TABLE `problem`
  ADD CONSTRAINT `problem_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `problem_ibfk_2` FOREIGN KEY (`defect_id`) REFERENCES `defect_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `problem_ibfk_4` FOREIGN KEY (`state`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `problem_ibfk_5` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `questionnaire`
--
ALTER TABLE `questionnaire`
  ADD CONSTRAINT `questionnaire_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `response_priorities`
--
ALTER TABLE `response_priorities`
  ADD CONSTRAINT `response_priorities_ibfk_1` FOREIGN KEY (`body_id`) REFERENCES `body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `response_templates`
--
ALTER TABLE `response_templates`
  ADD CONSTRAINT `response_templates_ibfk_1` FOREIGN KEY (`body_id`) REFERENCES `body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`body_id`) REFERENCES `body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_body_permissions`
--
ALTER TABLE `user_body_permissions`
  ADD CONSTRAINT `user_body_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_body_permissions_ibfk_2` FOREIGN KEY (`body_id`) REFERENCES `body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_planned_reports`
--
ALTER TABLE `user_planned_reports`
  ADD CONSTRAINT `user_planned_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
