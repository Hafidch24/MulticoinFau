-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 07, 2022 at 03:27 PM
-- Server version: 10.3.35-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `cheat_logs`
--

CREATE TABLE `cheat_logs` (
  `id` int(32) UNSIGNED NOT NULL,
  `user_id` int(32) UNSIGNED NOT NULL,
  `log` varchar(150) DEFAULT NULL,
  `create_time` int(32) UNSIGNED NOT NULL,
  `ip_address` varchar(75) NOT NULL,
  `relate_id` int(32) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(32) UNSIGNED NOT NULL,
  `currency_name` varchar(75) NOT NULL,
  `name` varchar(75) NOT NULL,
  `code` varchar(75) NOT NULL,
  `api` varchar(75) NOT NULL,
  `reward` decimal(20,6) NOT NULL DEFAULT 0.000000,
  `timer` int(32) DEFAULT 0,
  `limit_claim` int(32) NOT NULL DEFAULT 0,
  `token` varchar(75) NOT NULL,
  `price` decimal(20,6) NOT NULL,
  `last_price` decimal(20,6) NOT NULL,
  `wallet` varchar(20) NOT NULL,
  `account_number` varchar(30) NOT NULL,
  `api_id` varchar(30) NOT NULL,
  `minimum_withdrawal` decimal(30,6) DEFAULT 0.000001
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faucet_history`
--

CREATE TABLE `faucet_history` (
  `id` int(32) UNSIGNED NOT NULL,
  `user_id` int(32) UNSIGNED NOT NULL,
  `ip_address` varchar(75) NOT NULL,
  `amount` decimal(16,8) DEFAULT 0.00000000,
  `method` varchar(75) NOT NULL,
  `claim_time` int(32) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faucet_stats`
--

CREATE TABLE `faucet_stats` (
  `id` int(32) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `new_users` int(32) UNSIGNED DEFAULT 0,
  `active_users` int(32) UNSIGNED DEFAULT 0,
  `autofaucet_count` int(32) UNSIGNED DEFAULT 0,
  `faucet_count` int(32) UNSIGNED DEFAULT 0,
  `shortlink_count` int(32) UNSIGNED DEFAULT 0,
  `ptc_count` int(32) UNSIGNED DEFAULT 0,
  `dice_count` int(32) UNSIGNED DEFAULT 0,
  `offerwall_count` int(32) UNSIGNED DEFAULT 0,
  `deposit_count` int(32) UNSIGNED DEFAULT 0,
  `autofaucet_amount` decimal(30,6) DEFAULT 0.000000,
  `faucet_amount` decimal(30,6) DEFAULT 0.000000,
  `shortlink_amount` decimal(30,6) DEFAULT 0.000000,
  `ptc_amount` decimal(30,6) DEFAULT 0.000000,
  `dice_amount` decimal(30,6) DEFAULT 0.000000,
  `offerwall_amount` decimal(30,6) DEFAULT 0.000000,
  `deposit_amount` decimal(30,6) DEFAULT 0.000000,
  `withdraw_amount` decimal(30,6) DEFAULT 0.000000,
  `is_done` int(11) UNSIGNED DEFAULT 0,
  `coinflip_count` int(32) UNSIGNED DEFAULT 0,
  `coinflip_amount` decimal(30,6) DEFAULT 0.000000,
  `achievement_count` int(32) UNSIGNED DEFAULT 0,
  `achievement_amount` decimal(30,6) DEFAULT 0.000000
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ip_addresses`
--

CREATE TABLE `ip_addresses` (
  `id` int(32) UNSIGNED NOT NULL,
  `ip_address` varchar(75) NOT NULL,
  `user_id` int(32) UNSIGNED NOT NULL,
  `last_use` int(32) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(32) UNSIGNED NOT NULL,
  `name` varchar(75) NOT NULL,
  `reward` decimal(30,6) DEFAULT 0.000000,
  `url` longtext NOT NULL,
  `view_per_day` int(11) UNSIGNED DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `link_history`
--

CREATE TABLE `link_history` (
  `id` int(32) UNSIGNED NOT NULL,
  `user_id` int(32) UNSIGNED NOT NULL,
  `ip_address` varchar(75) NOT NULL,
  `amount` decimal(30,6) DEFAULT 0.000000,
  `link_id` int(10) NOT NULL,
  `claim_time` int(32) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(32) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'status', 'on'),
(2, 'name', 'Ourtecads'),
(3, 'description', 'Faucet'),
(4, 'referral', '20'),
(5, 'antibotlinks', 'on'),
(6, 'username', 'admin'),
(7, 'password', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5'),
(8, 'login_captcha', 'recaptchav3|recaptchav2'),
(9, 'faucet_captcha', ''),
(10, 'recaptcha_v3_site_key', ''),
(12, 'recaptcha_v3_secret_key', ''),
(13, 'recaptcha_v2_site_key', ''),
(14, 'recaptcha_v2_secret_key', ''),
(15, 'c_key', ''),
(17, 'v_key', ''),
(18, 'h_key', ''),
(19, 'hcaptcha_site_key', ''),
(20, 'hcaptcha_secret_key', ''),
(21, 'faucet_top_ad', '<img src=\"https://plchldr.co/i/300x250\">'),
(22, 'faucet_left_ad', '<img src=\"https://plchldr.co/i/160x600\">'),
(23, 'faucet_right_ad', '<img src=\"https://plchldr.co/i/160x600\">'),
(24, 'faucet_footer_ad', '<img src=\"https://plchldr.co/i/728x90\">'),
(25, 'faucet_status', 'on'),
(26, 'firewall', 'on'),
(27, 'captcha_fail_limit', '8'),
(28, 'admin_username', 'Mr. R'),
(29, 'shortlink_status', 'on'),
(30, 'anti_proxy', 'on'),
(31, 'claim_status', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(32) UNSIGNED NOT NULL,
  `username` varchar(14) NOT NULL,
  `wallet` varchar(50) NOT NULL,
  `ip_address` varchar(75) NOT NULL,
  `referred_by` int(32) UNSIGNED DEFAULT 0,
  `last_claim` int(32) UNSIGNED DEFAULT 0,
  `last_active` int(32) UNSIGNED DEFAULT 0,
  `token` varchar(30) NOT NULL,
  `isocode` varchar(10) DEFAULT 'N/A',
  `country` varchar(30) DEFAULT 'N/A',
  `joined` int(32) UNSIGNED DEFAULT 0,
  `status` char(10) DEFAULT '0',
  `last_firewall` int(32) DEFAULT 0,
  `referral_source` varchar(75) NOT NULL,
  `fail` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `verify`
--

CREATE TABLE `verify` (
  `user_id` int(32) UNSIGNED NOT NULL,
  `link_id` int(32) UNSIGNED NOT NULL,
  `url` longtext NOT NULL,
  `ip_address` varchar(75) NOT NULL,
  `secret_keys` varchar(20) NOT NULL,
  `create_time` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_history`
--

CREATE TABLE `withdraw_history` (
  `id` int(32) UNSIGNED NOT NULL,
  `user_id` int(32) UNSIGNED NOT NULL,
  `type` int(10) UNSIGNED NOT NULL,
  `method` varchar(50) NOT NULL,
  `wallet` varchar(50) NOT NULL,
  `ip_address` varchar(75) NOT NULL,
  `amount` decimal(20,8) DEFAULT 0.00000000,
  `amountusd` decimal(30,6) DEFAULT 0.000000,
  `claim_time` int(32) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cheat_logs`
--
ALTER TABLE `cheat_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faucet_history`
--
ALTER TABLE `faucet_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faucet_stats`
--
ALTER TABLE `faucet_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ip_addresses`
--
ALTER TABLE `ip_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `link_history`
--
ALTER TABLE `link_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_history`
--
ALTER TABLE `withdraw_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cheat_logs`
--
ALTER TABLE `cheat_logs`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faucet_history`
--
ALTER TABLE `faucet_history`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faucet_stats`
--
ALTER TABLE `faucet_stats`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ip_addresses`
--
ALTER TABLE `ip_addresses`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `link_history`
--
ALTER TABLE `link_history`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_history`
--
ALTER TABLE `withdraw_history`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
