-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 14, 2021 at 12:37 PM
-- Server version: 10.3.25-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
  `anonymous` tinyint(1) NOT NULL,
  `name` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed` timestamp NULL DEFAULT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` blob NOT NULL,
  `state` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cobrand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en-gb',
  `cobrand_data` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mark_fixed` tinyint(1) NOT NULL,
  `mark_open` tinyint(1) DEFAULT NULL,
  `problem_state` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `external_id` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `send_fail_count` int(11) NOT NULL,
  `send_fail_reason` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `send_fail_timestamp` timestamp NULL DEFAULT NULL,
  `whensent` timestamp NULL DEFAULT NULL
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
-- Table structure for table `defect_types`
--

CREATE TABLE `defect_types` (
  `id` int(11) NOT NULL,
  `body_id` int(11) NOT NULL,
  `name` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flickr_imported`
--

CREATE TABLE `flickr_imported` (
  `id` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `problem_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `problem`
--

CREATE TABLE `problem` (
  `id` int(11) NOT NULL,
  `postcode` text NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `bodies_str` text DEFAULT NULL,
  `bodies_missing` text DEFAULT NULL,
  `areas` text NOT NULL,
  `category` varchar(255) DEFAULT 'Other',
  `title` text NOT NULL,
  `detail` text NOT NULL,
  `photo` blob DEFAULT NULL,
  `used_map` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `anonymous` tinyint(1) NOT NULL,
  `external_id` text DEFAULT NULL,
  `external_body` text DEFAULT NULL,
  `external_team` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed` timestamp NULL DEFAULT NULL,
  `state` text NOT NULL,
  `lang` varchar(255) DEFAULT 'en-gb',
  `service` varchar(255) DEFAULT '',
  `cobrand` varchar(255) DEFAULT '',
  `cobrand_data` varchar(255) DEFAULT '',
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `whensent` timestamp NULL DEFAULT NULL,
  `send_questionnaire` tinyint(1) NOT NULL DEFAULT 1,
  `extra` text DEFAULT NULL,
  `flagged` tinyint(1) NOT NULL DEFAULT 0,
  `geocode` blob DEFAULT NULL,
  `response_priority_id` int(11) DEFAULT NULL,
  `send_fail_count` int(11) NOT NULL DEFAULT 0,
  `send_fail_reason` text DEFAULT NULL,
  `send_fail_timestamp` timestamp NULL DEFAULT NULL,
  `send_method_used` text DEFAULT NULL,
  `non_public` tinyint(1) DEFAULT 0,
  `external_source` text DEFAULT NULL,
  `external_source_id` text DEFAULT NULL,
  `interest_count` int(11) DEFAULT 0,
  `subcategory` text DEFAULT NULL,
  `defect_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Table structure for table `secret`
--

CREATE TABLE `secret` (
  `secret` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(72) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_data` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `label` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id`, `label`, `type`, `name`) VALUES
(1, 'investigating', 'open', 'Investigating'),
(2, 'in progress', 'open', 'In progress'),
(3, 'planned', 'open', 'Planned'),
(4, 'action scheduled', 'open', 'Action scheduled'),
(5, 'unable to fix', 'closed', 'No further action'),
(6, 'not responsible', 'closed', 'Not responsible'),
(7, 'duplicate', 'closed', 'Duplicate'),
(8, 'internal referral', 'closed', 'Internal referral'),
(9, 'fixed', 'fixed', 'Fixed');

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
  `scope` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` blob NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
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
  ADD KEY `problem_id` (`problem_id`);

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
-- Indexes for table `defect_types`
--
ALTER TABLE `defect_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `body_id` (`body_id`);

--
-- Indexes for table `flickr_imported`
--
ALTER TABLE `flickr_imported`
  ADD KEY `problem_id` (`problem_id`);

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
-- Indexes for table `problem`
--
ALTER TABLE `problem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `defect_type_id` (`defect_type_id`);

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
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `defect_types`
--
ALTER TABLE `defect_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `problem`
--
ALTER TABLE `problem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Constraints for table `defect_types`
--
ALTER TABLE `defect_types`
  ADD CONSTRAINT `defect_types_ibfk_1` FOREIGN KEY (`body_id`) REFERENCES `body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `flickr_imported`
--
ALTER TABLE `flickr_imported`
  ADD CONSTRAINT `flickr_imported_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `moderation_original_data`
--
ALTER TABLE `moderation_original_data`
  ADD CONSTRAINT `moderation_original_data_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `moderation_original_data_ibfk_2` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `problem`
--
ALTER TABLE `problem`
  ADD CONSTRAINT `problem_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `problem_ibfk_2` FOREIGN KEY (`defect_type_id`) REFERENCES `contact_defect_types` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
