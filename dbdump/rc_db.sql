-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 29, 2013 at 06:16 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `en2`
--

CREATE TABLE IF NOT EXISTS `en2` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `loc_fb_id` int(10) NOT NULL COMMENT 'unique auto-increment id of the user which is the primary key in fb_user_master table',
  `loc_fr_fb_id` int(10) NOT NULL COMMENT 'unique auto-increment id of the friend which is the primary key in fb_user_master table',
  `is_indirect_friend` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = friend is a direct friend, 1 = friend is an indirect friend',
  `fk_rc_user_id` int(10) NOT NULL DEFAULT '0' COMMENT 'unique auto-increment id of the friend which is the primary key in rc_user_master table',
  PRIMARY KEY (`id`),
  UNIQUE KEY `comb_key` (`loc_fb_id`,`loc_fr_fb_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `en2`
--

INSERT INTO `en2` (`id`, `loc_fb_id`, `loc_fr_fb_id`, `is_indirect_friend`, `fk_rc_user_id`) VALUES
(1, 2, 5, 0, 0),
(2, 5, 3, 0, 0),
(3, 2, 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `extended_network`
--

CREATE TABLE IF NOT EXISTS `extended_network` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `loc_fb_id` int(10) NOT NULL COMMENT 'unique auto-increment id of the user which is the primary key in fb_user_master table',
  `loc_fr_fb_id` int(10) NOT NULL COMMENT 'unique auto-increment id of the friend which is the primary key in fb_user_master table',
  `is_indirect_friend` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = friend is a direct friend, 1 = friend is an indirect friend',
  `fk_rc_user_id` int(10) NOT NULL DEFAULT '0' COMMENT 'unique auto-increment id of the friend which is the primary key in rc_user_master table',
  PRIMARY KEY (`id`),
  UNIQUE KEY `comb_key` (`loc_fb_id`,`loc_fr_fb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `extended_network`
--


-- --------------------------------------------------------

--
-- Table structure for table `fb_process`
--

CREATE TABLE IF NOT EXISTS `fb_process` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fb_user_id` bigint(20) unsigned NOT NULL COMMENT 'unique id provided by facebook',
  `fk_loc_fb_id` int(10) NOT NULL COMMENT 'unique auto-increment id which is the primary key in fb_user_master table',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 =	unprocesed files, 1 = direct friends data has been imported, 2 =	indirect friends data has been imported, if the value is 2, then only we can say that the network is ready and the user is allowed to go to the first login landing page',
  `filename` varchar(255) NOT NULL,
  `send_confirmation_mail` tinyint(4) NOT NULL COMMENT '0 = confirmation mail not sent, 1 = confirmation mail sent',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fb_user_id` (`fb_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `fb_process`
--


-- --------------------------------------------------------

--
-- Table structure for table `fb_suggestion_list`
--

CREATE TABLE IF NOT EXISTS `fb_suggestion_list` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fb_user_id` bigint(20) NOT NULL COMMENT 'unique id provided by facebook',
  `ref_fb_user_id` bigint(20) NOT NULL COMMENT 'unique auto-increment id which is the primary key in fb_user_master table',
  `rem_candidature_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = friend is visible in "Help Your Friend section", 1 = friend''s visibility is cancelled in "Help Your Friend section"',
  `send_message` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = message not sent to friend, 1 = message sent to friend',
  PRIMARY KEY (`id`),
  UNIQUE KEY `comb_key` (`fb_user_id`,`ref_fb_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `fb_suggestion_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `fb_user_master`
--

CREATE TABLE IF NOT EXISTS `fb_user_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fb_user_id` bigint(20) unsigned NOT NULL COMMENT 'unique id provided by facebook',
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `is_rc_profile` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = user does not have a rishtey profile, 1 = user does have a rishtey profile',
  `picture` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `relationship_status` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `del_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = user is added into the suggestion list , 1 = user is deleted from the suggestion list',
  `recm_msg_sent` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = recommendation message not sent, 1 = recommendation message sent',
  `dr_friend_cnt` int(10) NOT NULL DEFAULT '0',
  `indr_friend_cnt` int(10) NOT NULL DEFAULT '0',
  `bride_cnt` int(10) NOT NULL DEFAULT '0',
  `groom_cnt` int(10) NOT NULL DEFAULT '0',
  `candidate_cnt` int(10) NOT NULL DEFAULT '0',
  `set_suggestion_list` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = suggestion list is not ready, 1 = suggestion list is ready',
  `direct_friends_str` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fb_user_id` (`fb_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `fb_user_master`
--


-- --------------------------------------------------------

--
-- Table structure for table `network`
--

CREATE TABLE IF NOT EXISTS `network` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loc_fb_id` int(10) unsigned NOT NULL COMMENT 'unique auto-increment id of the user which is the primary key in fb_user_master table',
  `loc_fr_fb_id` int(10) unsigned NOT NULL COMMENT 'unique auto-increment id of the friend which is the primary key in fb_user_master table',
  `fk_rc_user_id` int(10) unsigned NOT NULL COMMENT 'unique auto-increment id of the friend which is the primary key in rc_user_master table',
  PRIMARY KEY (`id`),
  UNIQUE KEY `comb_key` (`loc_fb_id`,`loc_fr_fb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `network`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_caste_master`
--

CREATE TABLE IF NOT EXISTS `rc_caste_master` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `caste` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `caste` (`caste`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `rc_caste_master`
--

INSERT INTO `rc_caste_master` (`id`, `caste`) VALUES
(34, 'Brahmin'),
(36, 'Kayastha'),
(35, 'Kshatriya'),
(33, 'No Caste'),
(32, 'OBC'),
(29, 'Rajput'),
(31, 'Toda');

-- --------------------------------------------------------

--
-- Table structure for table `rc_education_master`
--

CREATE TABLE IF NOT EXISTS `rc_education_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `education` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `rc_education_master`
--

INSERT INTO `rc_education_master` (`id`, `education`) VALUES
(1, 'None'),
(2, 'Some-School'),
(3, 'High-School'),
(4, 'Some-College'),
(5, 'Bachelors-Degree'),
(6, 'Masters-Degree'),
(7, 'PhD');

-- --------------------------------------------------------

--
-- Table structure for table `rc_log`
--

CREATE TABLE IF NOT EXISTS `rc_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL,
  `referer` text NOT NULL,
  `fb_user_id` bigint(20) NOT NULL COMMENT 'logged in user as recommender/initiator/guardian',
  `own_candidate_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'person to whom recommendation/initiation is sent',
  `other_candidate_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'person for whom recommendation/initiation is sent',
  `page` text NOT NULL,
  `msg` text NOT NULL COMMENT 'log message',
  `state` text NOT NULL COMMENT 'unique value returned by facebook per session',
  `code` text NOT NULL COMMENT 'unique value returned by facebook per session',
  `timestamp` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_matched_profile`
--

CREATE TABLE IF NOT EXISTS `rc_matched_profile` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL,
  `cid_matched` int(10) NOT NULL,
  `is_blocked` tinyint(4) NOT NULL DEFAULT '0',
  `is_abused` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `comb_key_for_Match` (`cid`,`cid_matched`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_matched_profile`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_matched_profile_opposite_temp`
--

CREATE TABLE IF NOT EXISTS `rc_matched_profile_opposite_temp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL,
  `fb_user_id` bigint(20) NOT NULL,
  `cid_matched` int(10) NOT NULL,
  `fb_user_id_matched` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comb_key_for_Match` (`cid`,`cid_matched`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_matched_profile_opposite_temp`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_matched_profile_temp`
--

CREATE TABLE IF NOT EXISTS `rc_matched_profile_temp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL,
  `fb_user_id` bigint(20) NOT NULL,
  `cid_matched` int(10) NOT NULL,
  `fb_user_id_matched` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comb_key_for_Match` (`cid`,`cid_matched`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_matched_profile_temp`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_mtongue_master`
--

CREATE TABLE IF NOT EXISTS `rc_mtongue_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `rc_mtongue_master`
--

INSERT INTO `rc_mtongue_master` (`id`, `language_name`) VALUES
(1, 'Assamese'),
(2, 'Bengali'),
(3, 'English'),
(4, 'Gujarati'),
(5, 'Hindi'),
(6, 'Kannada'),
(7, 'Konkani'),
(8, 'Malayalam'),
(9, 'Marathi'),
(10, 'Marwari'),
(11, 'Oriya'),
(12, 'Punjabi'),
(13, 'Sindhi'),
(14, 'Tamil'),
(15, 'Telugu'),
(16, 'Urdu'),
(17, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `rc_profession_master`
--

CREATE TABLE IF NOT EXISTS `rc_profession_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profession` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `rc_profession_master`
--

INSERT INTO `rc_profession_master` (`id`, `profession`) VALUES
(1, 'Salaried Person'),
(2, 'Business Man'),
(3, 'Social Work'),
(4, 'Engineers'),
(5, 'Doctors');

-- --------------------------------------------------------

--
-- Table structure for table `rc_profiles`
--

CREATE TABLE IF NOT EXISTS `rc_profiles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fb_user_id` bigint(20) NOT NULL COMMENT 'unique id of the candidate provided by facebook',
  `fk_loc_fb_id` int(10) NOT NULL COMMENT 'unique auto-increment id of the candidate from fb_user_master table',
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `marital_status` varchar(255) NOT NULL,
  `religion` varchar(255) NOT NULL,
  `mother_tongue` varchar(255) NOT NULL,
  `caste` varchar(255) NOT NULL,
  `height` varchar(255) NOT NULL,
  `location` text NOT NULL,
  `highest_education` varchar(255) NOT NULL,
  `education_des` varchar(255) DEFAULT NULL,
  `profession` text NOT NULL,
  `profession_des` varchar(255) DEFAULT NULL,
  `salary` double NOT NULL,
  `biodata` varchar(255) NOT NULL,
  `short_recommendation` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = rishtey profile creation is incomplete, 1 = rishtey profile creation is complete',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fb_user_id` (`fb_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_profiles`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_profile_picture`
--

CREATE TABLE IF NOT EXISTS `rc_profile_picture` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fb_user_id` bigint(20) NOT NULL COMMENT 'unique id of the candidate provided by facebook',
  `picture` varchar(255) NOT NULL,
  `img_tag_id` tinyint(4) NOT NULL COMMENT 'candidate image tag id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_profile_picture`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_profile_preference`
--

CREATE TABLE IF NOT EXISTS `rc_profile_preference` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fb_user_id` bigint(20) NOT NULL,
  `fk_loc_fb_id` int(10) NOT NULL,
  `from_age` double NOT NULL,
  `to_age` float NOT NULL,
  `marital_status` varchar(255) NOT NULL,
  `religion` varchar(255) NOT NULL,
  `mother_tongue` varchar(255) NOT NULL,
  `caste` varchar(255) NOT NULL,
  `from_height` double NOT NULL,
  `to_height` double NOT NULL,
  `min_education` varchar(255) NOT NULL,
  `profession` varchar(255) NOT NULL,
  `min_salary` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_profile_preference`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_profile_relation`
--

CREATE TABLE IF NOT EXISTS `rc_profile_relation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fb_user_id` bigint(20) NOT NULL COMMENT 'person to whom recommendation/initiation is send',
  `fk_loc_fb_id` int(10) NOT NULL,
  `other_fb_user_id` bigint(20) NOT NULL COMMENT 'person for whom recommendation/initiation is send',
  `fb_guardian` bigint(20) NOT NULL DEFAULT '0' COMMENT 'person who recommends',
  `guardian_fk_loc_fb_id` int(10) NOT NULL COMMENT 'auto-incremented id of the guardian from fb_user_master table',
  `type` enum('G','R','I') NOT NULL COMMENT 'G = guardian, R = recommender, I = Initiator',
  `recm_msg_sent` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = recommendation message not sent, 1 = recommendation message sent',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 = profile incomplete  1 = profile complete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_profile_relation`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_profile_show_interest`
--

CREATE TABLE IF NOT EXISTS `rc_profile_show_interest` (
  `id` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `cid_matched` int(10) NOT NULL,
  `interest_message` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comb_key` (`cid`,`cid_matched`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rc_profile_show_interest`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_profile_update_message`
--

CREATE TABLE IF NOT EXISTS `rc_profile_update_message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fb_user_id` bigint(20) NOT NULL COMMENT 'This field will store the fb user id of the candidate',
  `update_message` text NOT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_profile_update_message`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_recommendations`
--

CREATE TABLE IF NOT EXISTS `rc_recommendations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fb_user_id` bigint(20) NOT NULL COMMENT 'person who recommends',
  `fr_fb_user_id` bigint(20) NOT NULL COMMENT 'person to whom recommendation is send',
  `other_fr_fb_user_id` bigint(20) NOT NULL COMMENT 'person for whom recommendation is send',
  `relationship` tinyint(4) NOT NULL DEFAULT '0',
  `recommendation` text NOT NULL,
  `type` enum('G','R','I') NOT NULL COMMENT 'G = guardian, R = recommender, I = Initiator',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_recommendations`
--


-- --------------------------------------------------------

--
-- Table structure for table `rc_recommrelation_master`
--

CREATE TABLE IF NOT EXISTS `rc_recommrelation_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `relation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `rc_recommrelation_master`
--

INSERT INTO `rc_recommrelation_master` (`id`, `relation`) VALUES
(2, 'Friend'),
(3, 'Cousin'),
(4, 'Brother'),
(5, 'Sister'),
(6, 'Father'),
(7, 'Mother'),
(8, 'Uncle'),
(9, 'Aunt'),
(10, 'Brother-in-Law'),
(11, 'Sister-in-Law'),
(12, 'Other relative');

-- --------------------------------------------------------

--
-- Table structure for table `rc_relation_master`
--

CREATE TABLE IF NOT EXISTS `rc_relation_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `relation_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `rc_relation_master`
--

INSERT INTO `rc_relation_master` (`id`, `relation_name`) VALUES
(1, ' Never Married'),
(2, 'Divorced'),
(3, 'Widowed'),
(4, 'Separated');

-- --------------------------------------------------------

--
-- Table structure for table `rc_religion_master`
--

CREATE TABLE IF NOT EXISTS `rc_religion_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `religion_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `rc_religion_master`
--

INSERT INTO `rc_religion_master` (`id`, `religion_name`) VALUES
(1, 'Hindu'),
(2, 'Muslim'),
(3, 'Christian'),
(4, 'Sikh'),
(5, 'Parsi'),
(6, 'Jain'),
(7, 'Buddhist'),
(8, 'Jewish'),
(9, 'No religion'),
(10, 'Spiritual'),
(11, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `rc_user_master`
--

CREATE TABLE IF NOT EXISTS `rc_user_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_loc_fb_id` int(10) unsigned NOT NULL COMMENT 'unique auto-increment id of the user from fb_user_master table',
  `picture` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = user not activated  1 = user activated',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rc_user_master`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_candidate`
--

CREATE TABLE IF NOT EXISTS `user_candidate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `loc_fb_id` int(10) NOT NULL,
  `network_candidate_str` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loc_fb_id` (`loc_fb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_candidate`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_friends`
--

CREATE TABLE IF NOT EXISTS `user_friends` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `loc_fb_id` int(10) NOT NULL,
  `network_friends_str` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loc_fb_id` (`loc_fb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_friends`
--

