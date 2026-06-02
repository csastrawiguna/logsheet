-- Adminer 5.4.2 MariaDB 12.2.2-MariaDB-ubu2404 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS `logsheet`;
CREATE DATABASE `logsheet` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `logsheet`;

DROP TABLE IF EXISTS `assets_headset`;
CREATE TABLE `assets_headset` (
  `headset_id` int(11) NOT NULL AUTO_INCREMENT,
  `headset_brand` varchar(32) NOT NULL,
  `headset_model` varchar(64) NOT NULL,
  `headset_sn` text NOT NULL,
  `headset_recdate` date DEFAULT NULL,
  `headset_remark` varchar(512) NOT NULL,
  `headset_status` text NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(32) NOT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`headset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `assets_inventory`;
CREATE TABLE `assets_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(16) NOT NULL,
  `pc_id` int(11) NOT NULL,
  `monitor1_id` int(11) DEFAULT NULL,
  `monitor2_id` int(11) DEFAULT NULL,
  `ipphone_id` int(11) DEFAULT NULL,
  `headset_id` int(11) DEFAULT NULL,
  `others1_id` int(11) DEFAULT NULL,
  `others2_id` int(11) DEFAULT NULL,
  `others3_id` int(11) DEFAULT NULL,
  `remark` varchar(512) NOT NULL,
  `last_modified_by` varchar(32) NOT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `assets_ipphone`;
CREATE TABLE `assets_ipphone` (
  `ipphone_id` int(11) NOT NULL AUTO_INCREMENT,
  `ipphone_brand` varchar(32) NOT NULL,
  `ipphone_model` varchar(64) NOT NULL,
  `ipphone_sn` text NOT NULL,
  `ipphone_recdate` date DEFAULT NULL,
  `ipphone_remark` varchar(512) NOT NULL,
  `ipphone_status` text NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(32) NOT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ipphone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `assets_monitor`;
CREATE TABLE `assets_monitor` (
  `monitor_id` int(11) NOT NULL AUTO_INCREMENT,
  `monitor_deptown` varchar(32) NOT NULL,
  `monitor_brand` varchar(32) NOT NULL,
  `monitor_size` int(11) NOT NULL,
  `monitor_model` varchar(64) NOT NULL,
  `monitor_sn` text NOT NULL,
  `monitor_recdate` date DEFAULT NULL,
  `monitor_remark` varchar(512) NOT NULL,
  `monitor_status` text NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(32) NOT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`monitor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `assets_others`;
CREATE TABLE `assets_others` (
  `others_id` int(11) NOT NULL AUTO_INCREMENT,
  `others_deptown` varchar(32) NOT NULL,
  `others_product` varchar(32) NOT NULL,
  `others_function` int(11) NOT NULL,
  `others_brand` varchar(32) NOT NULL,
  `others_model` varchar(32) NOT NULL,
  `others_sn` text NOT NULL,
  `others_recdate` date DEFAULT NULL,
  `others_remark` varchar(512) NOT NULL,
  `others_status` text NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(32) NOT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`others_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `assets_pc`;
CREATE TABLE `assets_pc` (
  `pc_id` int(11) NOT NULL AUTO_INCREMENT,
  `pc_brand` varchar(32) NOT NULL,
  `pc_deptown` varchar(32) NOT NULL,
  `pc_model` varchar(64) NOT NULL,
  `pc_sn` varchar(128) NOT NULL,
  `pc_ip` varchar(20) NOT NULL,
  `pc_name` varchar(64) DEFAULT NULL,
  `pc_macaddress` varchar(64) DEFAULT NULL,
  `pc_spec` varchar(256) NOT NULL,
  `pc_recdate` date DEFAULT NULL,
  `pc_remark` varchar(512) NOT NULL,
  `pc_status` varchar(32) NOT NULL,
  `saved_by` varchar(16) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(16) DEFAULT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`pc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `aux_daily`;
CREATE TABLE `aux_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `is_oh` tinyint(4) NOT NULL DEFAULT 1,
  `agent` varchar(32) NOT NULL,
  `ext` varchar(16) NOT NULL,
  `staffed_time` int(11) NOT NULL DEFAULT 0,
  `aux_0` int(11) NOT NULL DEFAULT 0,
  `aux_1` int(11) NOT NULL DEFAULT 0,
  `aux_2` int(11) NOT NULL DEFAULT 0,
  `aux_3` int(11) NOT NULL DEFAULT 0,
  `aux_4` int(11) NOT NULL DEFAULT 0,
  `aux_5` int(11) NOT NULL DEFAULT 0,
  `aux_6` int(11) NOT NULL DEFAULT 0,
  `aux_7` int(11) NOT NULL DEFAULT 0,
  `aux_8` int(11) NOT NULL DEFAULT 0,
  `aux_9` int(11) NOT NULL DEFAULT 0,
  `aux_1099` int(11) NOT NULL DEFAULT 0,
  `remark` varchar(128) DEFAULT NULL,
  `saved_by` varchar(32) DEFAULT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `aux_monthly`;
CREATE TABLE `aux_monthly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` date DEFAULT NULL,
  `agent` varchar(32) NOT NULL,
  `ext` varchar(16) NOT NULL,
  `staffed_time` int(11) NOT NULL DEFAULT 0,
  `aux_0` int(11) NOT NULL DEFAULT 0,
  `aux_1` int(11) NOT NULL DEFAULT 0,
  `aux_2` int(11) NOT NULL DEFAULT 0,
  `aux_3` int(11) NOT NULL DEFAULT 0,
  `aux_4` int(11) NOT NULL DEFAULT 0,
  `aux_5` int(11) NOT NULL DEFAULT 0,
  `aux_6` int(11) NOT NULL DEFAULT 0,
  `aux_7` int(11) NOT NULL DEFAULT 0,
  `aux_8` int(11) NOT NULL DEFAULT 0,
  `aux_9` int(11) NOT NULL DEFAULT 0,
  `aux_1099` int(11) NOT NULL DEFAULT 0,
  `remark` varchar(128) DEFAULT NULL,
  `saved_by` varchar(32) DEFAULT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `blackbook`;
CREATE TABLE `blackbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent` varchar(16) NOT NULL,
  `date` date DEFAULT NULL,
  `type` varchar(128) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 3,
  `detail` varchar(4096) NOT NULL,
  `remark` text DEFAULT NULL,
  `voice_link` varchar(256) DEFAULT NULL,
  `saved_by` varchar(16) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(16) DEFAULT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `blackbook_scoring`;
CREATE TABLE `blackbook_scoring` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(128) NOT NULL,
  `bahasa` varchar(500) DEFAULT NULL,
  `level` varchar(32) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `blackbook_scoring_level`;
CREATE TABLE `blackbook_scoring_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(32) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `break_date`;
CREATE TABLE `break_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `remark` varchar(400) DEFAULT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `break_schedule`;
CREATE TABLE `break_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `break_date_id` int(11) DEFAULT NULL,
  `break_group` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `break_time`;
CREATE TABLE `break_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workday` varchar(32) NOT NULL,
  `group` varchar(4) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `calendar`;
CREATE TABLE `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent` varchar(32) NOT NULL,
  `permit_type` varchar(126) DEFAULT NULL,
  `reason` varchar(256) NOT NULL,
  `description` text DEFAULT NULL,
  `permit_status` varchar(16) NOT NULL,
  `color` varchar(24) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(64) DEFAULT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(32) NOT NULL,
  `replied_to` int(11) DEFAULT NULL,
  `message` varchar(5000) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `is_sticky` int(11) NOT NULL DEFAULT 0,
  `note_sticky` varchar(512) DEFAULT NULL,
  `tagged_by` varchar(32) DEFAULT NULL,
  `quota_limit` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `chat_reply_template`;
CREATE TABLE `chat_reply_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `wording` varchar(2048) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `remark` varchar(500) DEFAULT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `chat_setting`;
CREATE TABLE `chat_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(128) NOT NULL,
  `value` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `coaching_assignment`;
CREATE TABLE `coaching_assignment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coaching_id` int(11) NOT NULL,
  `agent` varchar(32) NOT NULL,
  `coach_done` int(11) NOT NULL DEFAULT 0,
  `agent_done` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `coaching_list`;
CREATE TABLE `coaching_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coaching_date` date DEFAULT NULL,
  `coaching_category` varchar(512) NOT NULL,
  `coaching_title` varchar(512) NOT NULL,
  `coaching_description` varchar(512) NOT NULL,
  `is_general` int(11) NOT NULL DEFAULT 0,
  `coach` varchar(32) NOT NULL,
  `result` varchar(512) NOT NULL,
  `remark` varchar(512) DEFAULT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `csindex_survey`;
CREATE TABLE `csindex_survey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` date DEFAULT NULL,
  `data_datetime` text NOT NULL,
  `customer_name` varchar(128) NOT NULL,
  `customer_phone` varchar(16) NOT NULL,
  `customer_city` varchar(64) NOT NULL,
  `system_code` varchar(4) NOT NULL,
  `data_model` varchar(32) NOT NULL,
  `i_detail` varchar(512) NOT NULL,
  `action_detail` varchar(512) NOT NULL,
  `data_remark` varchar(128) NOT NULL,
  `agent` varchar(32) NOT NULL,
  `questioner_1` char(1) NOT NULL,
  `questioner_2` char(1) NOT NULL,
  `is_done` int(11) NOT NULL,
  `survey_datetime` datetime DEFAULT NULL,
  `survey_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `daily_absence`;
CREATE TABLE `daily_absence` (
  `absent_id` int(11) NOT NULL AUTO_INCREMENT,
  `absent_date` date DEFAULT NULL,
  `cti_id` varchar(50) NOT NULL,
  `permit_type` varchar(50) NOT NULL,
  `permit_reason` varchar(100) NOT NULL,
  `permit_remark` varchar(250) NOT NULL,
  `input_by` varchar(32) NOT NULL,
  `input_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(32) DEFAULT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`absent_id`),
  KEY `cti_id` (`cti_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `daily_agent_info_monitoring`;
CREATE TABLE `daily_agent_info_monitoring` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info_type` varchar(64) NOT NULL,
  `source` varchar(32) NOT NULL,
  `agent` varchar(32) NOT NULL,
  `date` date DEFAULT NULL,
  `customer_data` varchar(200) NOT NULL,
  `done_by_agent` tinyint(1) NOT NULL DEFAULT 0,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent` (`agent`),
  KEY `done_by_agent` (`done_by_agent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `dashboard_item`;
CREATE TABLE `dashboard_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_nick` varchar(32) NOT NULL,
  `dashboard_item` varchar(256) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `dept_code` varchar(16) NOT NULL,
  `department_name` varchar(128) NOT NULL,
  `division` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `education_material`;
CREATE TABLE `education_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_category` varchar(128) NOT NULL,
  `category` varchar(128) NOT NULL,
  `material_title` varchar(128) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `material_link` varchar(512) DEFAULT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `elearning_assignment`;
CREATE TABLE `elearning_assignment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elearning_id` int(11) NOT NULL,
  `user_id` varchar(32) NOT NULL,
  `pretest_done` int(11) NOT NULL DEFAULT 0,
  `pretest_score` float(10,2) NOT NULL DEFAULT 0.00,
  `pretest_start` datetime DEFAULT NULL,
  `pretest_date` datetime DEFAULT NULL,
  `posttest_done` int(11) NOT NULL DEFAULT 0,
  `posttest_remedial` int(11) NOT NULL DEFAULT 0,
  `score_remedial` varchar(128) DEFAULT NULL,
  `posttest_start` datetime DEFAULT NULL,
  `posttest_score` float(10,2) NOT NULL DEFAULT 0.00,
  `posttest_date` datetime DEFAULT NULL,
  `is_pass` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `elearning_id` (`elearning_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `elearning_category`;
CREATE TABLE `elearning_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` date DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `test_duration` int(11) NOT NULL,
  `question_qty` int(11) NOT NULL,
  `pretest` int(11) NOT NULL,
  `posttest_attemp` int(11) NOT NULL DEFAULT 1,
  `passing_score` int(11) NOT NULL,
  `elearning_material` varchar(128) NOT NULL,
  `status` int(11) NOT NULL,
  `created_by` varchar(32) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `last_modified_by` varchar(32) NOT NULL,
  `last_modified_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `elearning_examination`;
CREATE TABLE `elearning_examination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionaire_id` int(11) NOT NULL,
  `elearning_id` int(11) NOT NULL,
  `pre_post` varchar(16) NOT NULL,
  `user_id` varchar(32) NOT NULL,
  `correct_key` varchar(1) NOT NULL,
  `answer` varchar(1) DEFAULT NULL,
  `is_correct` int(11) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questionaire_id` (`questionaire_id`),
  KEY `elearning_id` (`elearning_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `elearning_questionaire`;
CREATE TABLE `elearning_questionaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(64) NOT NULL,
  `difficulty` varchar(32) NOT NULL DEFAULT 'reguler',
  `period` date DEFAULT NULL,
  `question` varchar(2048) NOT NULL,
  `picture_link` varchar(128) NOT NULL,
  `option_a` varchar(256) NOT NULL,
  `option_b` varchar(256) NOT NULL,
  `option_c` varchar(256) NOT NULL,
  `option_d` varchar(256) NOT NULL,
  `option_e` varchar(256) NOT NULL,
  `correct_key` char(5) NOT NULL,
  `status` int(11) NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `elearning_questionaire_assignment`;
CREATE TABLE `elearning_questionaire_assignment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elearning_id` int(11) NOT NULL,
  `questionaire_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `general_info`;
CREATE TABLE `general_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detail_info` varchar(32000) NOT NULL,
  `status` int(11) NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `general_queue`;
CREATE TABLE `general_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(128) NOT NULL,
  `is_selected` int(11) NOT NULL DEFAULT 0,
  `agent` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `general_setting`;
CREATE TABLE `general_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(128) NOT NULL,
  `value` varchar(128) NOT NULL DEFAULT '1',
  `remark` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `jobdesk`;
CREATE TABLE `jobdesk` (
  `jobcode` varchar(12) NOT NULL,
  `jobdesk` varchar(128) NOT NULL,
  `section` varchar(128) NOT NULL,
  `dept_code` varchar(128) NOT NULL,
  PRIMARY KEY (`jobcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `kpi_best_agent_detail`;
CREATE TABLE `kpi_best_agent_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` date DEFAULT NULL,
  `agent` varchar(32) NOT NULL,
  `productivity_result` decimal(10,4) NOT NULL,
  `productivity_score` int(11) NOT NULL,
  `smilevoice_result` decimal(10,4) NOT NULL,
  `smilevoice_score` int(11) NOT NULL,
  `attendance_result` decimal(10,4) NOT NULL,
  `attendance_score` int(11) NOT NULL,
  `elearning_result` decimal(10,3) NOT NULL,
  `elearning_score` int(11) NOT NULL,
  `teamwork_result` decimal(10,4) NOT NULL,
  `teamwork_score` int(11) NOT NULL,
  `total_score` decimal(10,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `kpi_best_agent_measurement`;
CREATE TABLE `kpi_best_agent_measurement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobcode` varchar(32) NOT NULL,
  `item` varchar(32) NOT NULL,
  `range_min` decimal(10,4) NOT NULL,
  `range_max` decimal(10,4) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `kpi_best_agent_target`;
CREATE TABLE `kpi_best_agent_target` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(64) NOT NULL,
  `description` varchar(128) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `kpi_measurement`;
CREATE TABLE `kpi_measurement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobcode` varchar(16) NOT NULL,
  `fiscal` varchar(16) NOT NULL,
  `item` varchar(32) NOT NULL,
  `range_min` decimal(10,2) NOT NULL,
  `range_max` decimal(10,2) NOT NULL,
  `criteria` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `kpi_other`;
CREATE TABLE `kpi_other` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` date DEFAULT NULL,
  `agent` varchar(16) NOT NULL,
  `skape_draft` int(11) NOT NULL,
  `skape_solution` int(11) NOT NULL,
  `knowledge_sharing` int(11) NOT NULL,
  `part_callback` decimal(10,2) NOT NULL,
  `complaint_forward` decimal(10,2) NOT NULL,
  `complaint_completion` decimal(10,2) NOT NULL,
  `complaint_report` int(11) NOT NULL,
  `email_reply` decimal(10,2) NOT NULL,
  `promo_inquiry` decimal(10,2) NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(32) NOT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `kpi_target`;
CREATE TABLE `kpi_target` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fiscal` varchar(5) NOT NULL,
  `jobcode` varchar(16) NOT NULL,
  `item` varchar(32) NOT NULL,
  `description` varchar(64) NOT NULL,
  `weight` int(11) NOT NULL,
  `target` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `leave_info`;
CREATE TABLE `leave_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(16) NOT NULL,
  `agent` varchar(32) NOT NULL,
  `annual_leave` int(11) NOT NULL,
  `long_leave` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `leave_quota`;
CREATE TABLE `leave_quota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` year(4) NOT NULL,
  `personal_leave` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `leave_setting`;
CREATE TABLE `leave_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `max_leave` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `lebaran_operation`;
CREATE TABLE `lebaran_operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` year(4) NOT NULL,
  `date` date DEFAULT NULL,
  `inbound` int(11) NOT NULL DEFAULT 0,
  `acd` int(11) NOT NULL DEFAULT 0,
  `car` decimal(3,1) NOT NULL,
  `wa_resolved` int(11) NOT NULL DEFAULT 0,
  `wa_ongoing` int(11) DEFAULT 0,
  `followup` int(11) DEFAULT 0,
  `email_replied` int(11) DEFAULT 0,
  `complaint_reguler` int(11) DEFAULT 0,
  `complaint_urgent_qty` int(11) DEFAULT 0,
  `complaint_urgent_detail` varchar(9192) DEFAULT NULL,
  `remark` varchar(9192) DEFAULT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `log_export`;
CREATE TABLE `log_export` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_date` date DEFAULT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `status` enum('Success','Skip','Failed') DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(32) NOT NULL,
  `link` varchar(32) NOT NULL,
  `icon` text NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `menu_access`;
CREATE TABLE `menu_access` (
  `acess_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `role_access` int(11) NOT NULL,
  PRIMARY KEY (`acess_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `obidience`;
CREATE TABLE `obidience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `overtime_type` varchar(64) NOT NULL,
  `agent_scheduled` varchar(32) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `duration` decimal(10,2) NOT NULL,
  `replaced_by` varchar(32) NOT NULL,
  `actual_overtime` varchar(32) NOT NULL,
  `actual_start` time NOT NULL,
  `actual_end` time NOT NULL,
  `actual_duration` decimal(10,2) NOT NULL,
  `prod_call` int(11) NOT NULL DEFAULT 0,
  `prod_whatsapp` int(11) NOT NULL DEFAULT 0,
  `prod_followup` int(11) NOT NULL DEFAULT 0,
  `prod_others` int(11) NOT NULL DEFAULT 0,
  `prod_remark` varchar(1000) DEFAULT NULL,
  `reason` varchar(250) NOT NULL,
  `remark` varchar(250) DEFAULT NULL,
  `replace_mark` varchar(64) DEFAULT NULL,
  `obidience_index` int(11) NOT NULL DEFAULT 0,
  `leader_in_charge` varchar(32) NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `last_modified_by` varchar(32) DEFAULT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_scheduled` (`agent_scheduled`),
  KEY `actual_overtime` (`actual_overtime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `overtime_allowance`;
CREATE TABLE `overtime_allowance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `overtime_type` varchar(64) NOT NULL,
  `employement` varchar(64) NOT NULL,
  `meal` int(11) NOT NULL,
  `transport` int(11) NOT NULL,
  `remark` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `overtime_hour`;
CREATE TABLE `overtime_hour` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `duration` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `overtime_setting`;
CREATE TABLE `overtime_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employement` varchar(32) NOT NULL,
  `upper_limit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `password_reset`;
CREATE TABLE `password_reset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) NOT NULL,
  `ip_address` varchar(32) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `reason` varchar(128) NOT NULL,
  `is_unlocked` int(11) NOT NULL,
  `is_reseted` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `reset_by` varchar(32) NOT NULL,
  `reset_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `pray_schedule`;
CREATE TABLE `pray_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pray_time` varchar(128) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `productivity`;
CREATE TABLE `productivity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` date DEFAULT NULL,
  `agent` varchar(32) NOT NULL,
  `icall` int(11) NOT NULL,
  `callback` int(11) NOT NULL,
  `follow_up` int(11) NOT NULL,
  `sms` int(11) NOT NULL,
  `webchat` int(11) NOT NULL,
  `whatsapp` int(11) NOT NULL,
  `sharp_id` int(11) NOT NULL,
  `email` int(11) NOT NULL,
  `notif_sap` int(11) NOT NULL,
  `complaint` int(11) NOT NULL,
  `part_code` int(11) NOT NULL,
  `others` int(11) NOT NULL,
  `work_hour` decimal(10,1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agent` (`agent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `productivity_daily`;
CREATE TABLE `productivity_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `agent` varchar(32) NOT NULL,
  `icall` int(11) NOT NULL DEFAULT 0,
  `whatsapp_reply` int(11) NOT NULL DEFAULT 0,
  `sms_email` int(11) NOT NULL DEFAULT 0,
  `followup` int(11) NOT NULL DEFAULT 0,
  `assignment` varchar(256) NOT NULL,
  `target` int(11) DEFAULT NULL,
  `remark` varchar(128) DEFAULT NULL,
  `saved_by` varchar(32) DEFAULT NULL,
  `saved_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `productivity_daily_target`;
CREATE TABLE `productivity_daily_target` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobcode` varchar(16) NOT NULL,
  `target` int(11) NOT NULL,
  `icon` varchar(128) NOT NULL,
  `remark` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `productivity_interval`;
CREATE TABLE `productivity_interval` (
  `agent` varchar(32) NOT NULL,
  `icall` int(11) NOT NULL,
  `whatsapp` int(11) NOT NULL,
  `sms_email` int(11) NOT NULL,
  `follow_up` int(11) NOT NULL,
  `assignment` varchar(32) NOT NULL,
  `remark` varchar(128) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`agent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `productivity_kpi23f`;
CREATE TABLE `productivity_kpi23f` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` date DEFAULT NULL,
  `agent` varchar(32) NOT NULL,
  `icall` int(11) NOT NULL,
  `callback` int(11) NOT NULL,
  `follow_up` int(11) NOT NULL,
  `sms` int(11) NOT NULL,
  `webchat` int(11) NOT NULL,
  `whatsapp` int(11) NOT NULL,
  `sharp_id` int(11) NOT NULL,
  `email` int(11) NOT NULL,
  `notif_sap` int(11) NOT NULL,
  `complaint` int(11) NOT NULL,
  `part_code` int(11) NOT NULL,
  `others` int(11) NOT NULL,
  `work_hour` decimal(10,1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `repeat_question`;
CREATE TABLE `repeat_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent` varchar(32) NOT NULL,
  `date` date DEFAULT NULL,
  `category` varchar(128) NOT NULL,
  `detail` varchar(500) NOT NULL,
  `remark` varchar(128) NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `skape_feedback`;
CREATE TABLE `skape_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(64) DEFAULT NULL,
  `solution_title` varchar(500) NOT NULL,
  `solution_id` varchar(500) NOT NULL,
  `feedback` varchar(2000) NOT NULL,
  `remark` varchar(500) NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `submenu`;
CREATE TABLE `submenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `submenu_name` varchar(32) NOT NULL,
  `submenu_link` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `submenu_access`;
CREATE TABLE `submenu_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `submenu_id` int(11) NOT NULL,
  `role_access` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `survey`;
CREATE TABLE `survey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent` varchar(32) NOT NULL,
  `questioner` text NOT NULL,
  `jawaban` text NOT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `survey_newskape_feedback`;
CREATE TABLE `survey_newskape_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent` varchar(32) NOT NULL,
  `category` varchar(128) NOT NULL,
  `detail` varchar(2048) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `survey_setting`;
CREATE TABLE `survey_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `show_survey` int(11) NOT NULL,
  `qty_min` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `survey_wfhwfo`;
CREATE TABLE `survey_wfhwfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent` varchar(32) NOT NULL,
  `answer` varchar(8) NOT NULL,
  `reason` varchar(512) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` varchar(32) NOT NULL,
  `password` varchar(128) NOT NULL,
  `npk` varchar(16) NOT NULL,
  `fullname` varchar(128) NOT NULL,
  `mendawai_userid` varchar(64) NOT NULL,
  `initial_name` varchar(3) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `joindate` date DEFAULT NULL,
  `retiredate` date DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `email_address` varchar(128) NOT NULL,
  `email_personal` varchar(128) NOT NULL,
  `jobcode` varchar(128) NOT NULL,
  `role_access` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `is_locked` int(11) NOT NULL,
  `view_theme` int(11) NOT NULL,
  `photo` varchar(128) NOT NULL,
  `bg` varchar(128) DEFAULT NULL,
  `bg_position` varchar(128) NOT NULL,
  `quote` varchar(500) NOT NULL,
  `user_moodle` varchar(32) NOT NULL,
  `login_ip` varchar(128) DEFAULT NULL,
  `login_at` datetime DEFAULT NULL,
  `replacement_for` varchar(128) DEFAULT NULL,
  `mpr_approval` varchar(512) DEFAULT NULL,
  `remark` varchar(2000) DEFAULT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `user_contract`;
CREATE TABLE `user_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) NOT NULL,
  `contract_start` date DEFAULT NULL,
  `contract_expired` date DEFAULT NULL,
  `contract_number` int(11) NOT NULL DEFAULT 1,
  `resign_date` date DEFAULT NULL,
  `remark` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `role_access` int(11) NOT NULL,
  `role` varchar(16) NOT NULL,
  `role_name` varchar(64) NOT NULL,
  PRIMARY KEY (`role_access`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `user_wage`;
CREATE TABLE `user_wage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) NOT NULL,
  `year` year(4) NOT NULL,
  `wage` int(11) NOT NULL DEFAULT 0,
  `remark` varchar(512) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `view_theme`;
CREATE TABLE `view_theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(32) NOT NULL,
  `theme_text` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `voice_assesment`;
CREATE TABLE `voice_assesment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` date DEFAULT NULL,
  `agent` varchar(16) NOT NULL,
  `voice_number` int(11) NOT NULL,
  `call_date` date DEFAULT NULL,
  `greeting_complete` int(11) NOT NULL,
  `greeting_smile` int(11) NOT NULL,
  `intonation_straight` int(11) NOT NULL,
  `intonation_clear` int(11) NOT NULL,
  `intonation_not_flat` int(11) NOT NULL,
  `intonation_not_weak` int(11) NOT NULL,
  `intonation_not_high` int(11) NOT NULL,
  `handling_no_jargon` int(11) NOT NULL,
  `handling_customer_name` int(11) NOT NULL,
  `handling_communicative` int(11) NOT NULL,
  `handling_accuracy` int(11) NOT NULL,
  `handling_ask_help` int(11) NOT NULL,
  `closing` int(11) NOT NULL,
  `voice_remark` varchar(512) NOT NULL,
  `voice_link` varchar(256) NOT NULL,
  `survey_by` varchar(16) NOT NULL,
  `survey_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `voice_assesment_25f`;
CREATE TABLE `voice_assesment_25f` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` date DEFAULT NULL,
  `survey_source` varchar(32) NOT NULL DEFAULT 'incoming',
  `agent` varchar(32) NOT NULL,
  `call_date` date DEFAULT NULL,
  `customer_phone` varchar(32) NOT NULL,
  `greeting` int(11) NOT NULL,
  `greeting_remark` varchar(1024) DEFAULT NULL,
  `smile_voice` int(11) NOT NULL,
  `smile_voice_remark` varchar(104) DEFAULT NULL,
  `accuracy` int(11) NOT NULL,
  `accuracy_remark` varchar(1024) DEFAULT NULL,
  `closing` int(11) NOT NULL,
  `closing_remark` varchar(1024) DEFAULT NULL,
  `voice_remark` varchar(512) DEFAULT NULL,
  `voice_link` varchar(256) DEFAULT NULL,
  `survey_by` varchar(16) NOT NULL,
  `survey_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `vote_detail`;
CREATE TABLE `vote_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL,
  `vote_to` varchar(1024) DEFAULT NULL,
  `voted_by` varchar(32) NOT NULL,
  `voted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `vote_list`;
CREATE TABLE `vote_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_name` varchar(128) NOT NULL,
  `vote_desc` varchar(1024) NOT NULL,
  `vote_start` date DEFAULT NULL,
  `vote_end` date DEFAULT NULL,
  `is_active` int(11) NOT NULL,
  `data_list` varchar(1028) NOT NULL,
  `saved_by` varchar(32) NOT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `wa_raw`;
CREATE TABLE `wa_raw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `review_id` int(11) NOT NULL,
  `interaction_id` varchar(24) NOT NULL,
  `sender` varchar(32) NOT NULL,
  `datetime` datetime NOT NULL,
  `message` varchar(4096) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_date` (`review_id`),
  KEY `idx_agent` (`message`(768))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `wa_review`;
CREATE TABLE `wa_review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` date DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `agent` varchar(32) DEFAULT NULL,
  `ticket_number` varchar(100) DEFAULT NULL,
  `system_code` varchar(2) DEFAULT NULL,
  `customer_phone` varchar(32) DEFAULT NULL,
  `score_response` int(11) DEFAULT 0,
  `score_accuracy` int(11) DEFAULT 0,
  `score_wording` int(11) DEFAULT 0,
  `remark` varchar(1000) DEFAULT NULL,
  `saved_by` varchar(32) DEFAULT NULL,
  `saved_at` datetime DEFAULT NULL,
  `updated_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `period_agent` (`period`,`agent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;


DROP TABLE IF EXISTS `working_calendar`;
CREATE TABLE `working_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `working_month` date DEFAULT NULL,
  `working_day` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- 2026-06-02 15:46:28 UTC
