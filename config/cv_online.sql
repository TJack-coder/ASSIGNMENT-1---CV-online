-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2026 at 11:49 PM
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
-- Database: `cv_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Software Development'),
(2, 'Data Science'),
(3, 'Finance & Accounting'),
(4, 'Marketing'),
(5, 'Education'),
(6, 'Design & Creative');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `certificate_name_id` int(11) NOT NULL,
  `organizations_id` int(11) NOT NULL,
  `year_issued` year(4) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `cv_id`, `certificate_name_id`, `organizations_id`, `year_issued`, `description`) VALUES
(2, 5, 1, 1, '2025', '6.5');

-- --------------------------------------------------------

--
-- Table structure for table `certificate_name`
--

CREATE TABLE `certificate_name` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate_name`
--

INSERT INTO `certificate_name` (`id`, `name`) VALUES
(1, 'IELTS'),
(2, 'TOEIC'),
(3, 'Google Data Analytics Certificate'),
(4, 'AWS Cloud Practitioner'),
(5, 'Microsoft Office Specialist'),
(6, 'Cisco CCNA');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `countries_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `countries_id`) VALUES
(1, 'Ho Chi Minh City', 1),
(2, 'Ha Noi', 1),
(3, 'Da Nang', 1),
(4, 'Can Tho', 1),
(5, 'Binh Duong', 1);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`) VALUES
(1, 'Vietnam'),
(2, 'Singapore'),
(3, 'Japan'),
(4, 'South Korea'),
(5, 'United States');

-- --------------------------------------------------------

--
-- Table structure for table `cvs`
--

CREATE TABLE `cvs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `birthday` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `countries_id` int(11) NOT NULL,
  `cities_id` int(11) NOT NULL,
  `district_id` int(11) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `categories_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cvs`
--

INSERT INTO `cvs` (`id`, `user_id`, `full_name`, `birthday`, `gender`, `email`, `phone_number`, `countries_id`, `cities_id`, `district_id`, `address`, `postal_code`, `categories_id`) VALUES
(3, 1, 'KHANG THÁI ĐỨC', '2005-03-05', 'Male', 'khang.thaiduc@hcmut.edu.vn', '223332323', 1, 1, 1, 'Hcm', '', 6),
(5, 2, 'Tkhang', '2005-03-05', 'Male', 'hu@gmail.com', '0923576477', 1, 1, 1, 'Hcm', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `cv_skills`
--

CREATE TABLE `cv_skills` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `skills_id` int(11) NOT NULL,
  `proficients_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cv_skills`
--

INSERT INTO `cv_skills` (`id`, `cv_id`, `skills_id`, `proficients_id`) VALUES
(3, 5, 7, 4),
(4, 5, 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `degrees`
--

CREATE TABLE `degrees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `degrees`
--

INSERT INTO `degrees` (`id`, `name`) VALUES
(1, 'High School'),
(2, 'Associate Degree'),
(3, 'Bachelor Degree'),
(4, 'Master Degree'),
(5, 'PhD');

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `cities_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`id`, `name`, `cities_id`) VALUES
(1, 'District 1', 1),
(2, 'District 3', 1),
(3, 'Binh Thanh', 1),
(4, 'Tan Binh', 1),
(5, 'Thu Duc', 1),
(6, 'Ba Dinh', 2),
(7, 'Hoan Kiem', 2),
(8, 'Hai Chau', 3);

-- --------------------------------------------------------

--
-- Table structure for table `educations`
--

CREATE TABLE `educations` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `degree_level_id` int(11) NOT NULL,
  `major_id` int(11) NOT NULL,
  `start_year` year(4) NOT NULL,
  `end_year` year(4) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `educations`
--

INSERT INTO `educations` (`id`, `cv_id`, `institution_id`, `degree_level_id`, `major_id`, `start_year`, `end_year`, `description`) VALUES
(1, 3, 3, 3, 4, '2025', '2029', NULL),
(3, 5, 2, 3, 1, '2022', '2029', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employment_types`
--

CREATE TABLE `employment_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employment_types`
--

INSERT INTO `employment_types` (`id`, `name`) VALUES
(1, 'Full-time'),
(2, 'Part-time'),
(3, 'Internship'),
(4, 'Freelance'),
(5, 'Contract');

-- --------------------------------------------------------

--
-- Table structure for table `industries`
--

CREATE TABLE `industries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `industries`
--

INSERT INTO `industries` (`id`, `name`) VALUES
(1, 'Information Technology'),
(2, 'Finance'),
(3, 'Education'),
(4, 'Marketing'),
(5, 'E-commerce'),
(6, 'Healthcare');

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE `institutions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `institutions`
--

INSERT INTO `institutions` (`id`, `name`) VALUES
(1, 'Ho Chi Minh City University of Technology'),
(2, 'HCMUTE'),
(3, 'FPT University'),
(4, 'University of Economics Ho Chi Minh City'),
(5, 'Foreign Trade University');

-- --------------------------------------------------------

--
-- Table structure for table `job_title`
--

CREATE TABLE `job_title` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_title`
--

INSERT INTO `job_title` (`id`, `name`) VALUES
(1, 'Frontend Developer'),
(2, 'Backend Developer'),
(3, 'Full-stack Developer'),
(4, 'Data Analyst'),
(5, 'Software Engineer'),
(6, 'UI/UX Designer'),
(7, 'Marketing Executive'),
(8, 'Accountant'),
(9, 'Teacher');

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

CREATE TABLE `majors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `majors`
--

INSERT INTO `majors` (`id`, `name`) VALUES
(1, 'Computer Science'),
(2, 'Software Engineering'),
(3, 'Information Systems'),
(4, 'Artificial Intelligence'),
(5, 'Finance'),
(6, 'Accounting'),
(7, 'Marketing');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`) VALUES
(1, 'British Council'),
(2, 'IDP Education'),
(3, 'ETS'),
(4, 'Google'),
(5, 'Amazon Web Services'),
(6, 'Microsoft'),
(7, 'Cisco');

-- --------------------------------------------------------

--
-- Table structure for table `proficients`
--

CREATE TABLE `proficients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proficients`
--

INSERT INTO `proficients` (`id`, `name`) VALUES
(1, 'Beginner'),
(2, 'Intermediate'),
(3, 'Advanced'),
(4, 'Expert');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`) VALUES
(1, 'HTML'),
(2, 'CSS'),
(3, 'JavaScript'),
(4, 'PHP'),
(5, 'MySQL'),
(6, 'Laravel'),
(7, 'Python'),
(8, 'Java'),
(9, 'C++'),
(10, 'React');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `hash_pw` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('job_seeker','employer','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `hash_pw`, `created_at`, `role`) VALUES
(1, 'Nguyen Hai Long', 'jobseeker@gmail.com', '$2y$10$d9bw.cABjagoA0HNR0NEJeMkX3wifCxhPh4dxk8BnJ6nV9LkW69iK', '2026-05-07 08:14:58', 'job_seeker'),
(2, 'TKhang', 'hu@gmail.com', '$2y$10$KP86TaHfJ9wrGXI60QbIOOs0wtZSqcTYhMeSH8eDkQq8Xaft4JsCa', '2026-05-07 16:37:11', 'job_seeker'),
(3, 'TXuan', 'hihi@gmail.com', '$2y$10$kW/lkRqrdpjCOSyiwMYsqe/HF5QGBGKcsy6XDgRcHovZSuZ6YWBe2', '2026-05-07 16:43:14', 'job_seeker'),
(4, 'NLong', 'ha@gmail.com', '$2y$10$cJFH98fSjxn7hon6D0PmFOcx0p5elGve1Qaji4BZDL2JB1oB1lK/K', '2026-05-07 16:44:00', 'job_seeker'),
(5, 'TanTai', 'ho@gmail.com', '$2y$10$F.t3UR8tBans.IkpgcnCve1bmmp6Wap40hwDWXZQJGzCnX.R9i1Oe', '2026-05-07 16:44:55', 'employer');

-- --------------------------------------------------------

--
-- Table structure for table `work_histories`
--

CREATE TABLE `work_histories` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `job_title_id` int(11) NOT NULL,
  `employment_types_id` int(11) NOT NULL,
  `industries_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `start_year` year(4) NOT NULL,
  `end_year` year(4) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_histories`
--

INSERT INTO `work_histories` (`id`, `cv_id`, `job_title_id`, `employment_types_id`, `industries_id`, `company_name`, `start_year`, `end_year`, `description`) VALUES
(2, 5, 4, 1, 3, 'ABC', '2023', '2026', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cv_id` (`cv_id`),
  ADD KEY `certificate_name_id` (`certificate_name_id`),
  ADD KEY `organizations_id` (`organizations_id`);

--
-- Indexes for table `certificate_name`
--
ALTER TABLE `certificate_name`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `countries_id` (`countries_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cvs`
--
ALTER TABLE `cvs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `countries_id` (`countries_id`),
  ADD KEY `cities_id` (`cities_id`),
  ADD KEY `district_id` (`district_id`),
  ADD KEY `categories_id` (`categories_id`);

--
-- Indexes for table `cv_skills`
--
ALTER TABLE `cv_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cv_id` (`cv_id`),
  ADD KEY `skills_id` (`skills_id`),
  ADD KEY `proficients_id` (`proficients_id`);

--
-- Indexes for table `degrees`
--
ALTER TABLE `degrees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_id` (`cities_id`);

--
-- Indexes for table `educations`
--
ALTER TABLE `educations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cv_id` (`cv_id`),
  ADD KEY `institution_id` (`institution_id`),
  ADD KEY `degree_level_id` (`degree_level_id`),
  ADD KEY `major_id` (`major_id`);

--
-- Indexes for table `employment_types`
--
ALTER TABLE `employment_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industries`
--
ALTER TABLE `industries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institutions`
--
ALTER TABLE `institutions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_title`
--
ALTER TABLE `job_title`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proficients`
--
ALTER TABLE `proficients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_histories`
--
ALTER TABLE `work_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cv_id` (`cv_id`),
  ADD KEY `job_title_id` (`job_title_id`),
  ADD KEY `employment_types_id` (`employment_types_id`),
  ADD KEY `industries_id` (`industries_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `certificate_name`
--
ALTER TABLE `certificate_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cvs`
--
ALTER TABLE `cvs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cv_skills`
--
ALTER TABLE `cv_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `degrees`
--
ALTER TABLE `degrees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `educations`
--
ALTER TABLE `educations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employment_types`
--
ALTER TABLE `employment_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `industries`
--
ALTER TABLE `industries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `institutions`
--
ALTER TABLE `institutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `job_title`
--
ALTER TABLE `job_title`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `majors`
--
ALTER TABLE `majors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `proficients`
--
ALTER TABLE `proficients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `work_histories`
--
ALTER TABLE `work_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cvs` (`id`),
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`certificate_name_id`) REFERENCES `certificate_name` (`id`),
  ADD CONSTRAINT `certificates_ibfk_3` FOREIGN KEY (`organizations_id`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`countries_id`) REFERENCES `countries` (`id`);

--
-- Constraints for table `cvs`
--
ALTER TABLE `cvs`
  ADD CONSTRAINT `cvs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cvs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cvs_ibfk_3` FOREIGN KEY (`countries_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `cvs_ibfk_4` FOREIGN KEY (`cities_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `cvs_ibfk_5` FOREIGN KEY (`district_id`) REFERENCES `district` (`id`),
  ADD CONSTRAINT `cvs_ibfk_6` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `cv_skills`
--
ALTER TABLE `cv_skills`
  ADD CONSTRAINT `cv_skills_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cvs` (`id`),
  ADD CONSTRAINT `cv_skills_ibfk_2` FOREIGN KEY (`skills_id`) REFERENCES `skills` (`id`),
  ADD CONSTRAINT `cv_skills_ibfk_3` FOREIGN KEY (`proficients_id`) REFERENCES `proficients` (`id`);

--
-- Constraints for table `district`
--
ALTER TABLE `district`
  ADD CONSTRAINT `district_ibfk_1` FOREIGN KEY (`cities_id`) REFERENCES `cities` (`id`);

--
-- Constraints for table `educations`
--
ALTER TABLE `educations`
  ADD CONSTRAINT `educations_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cvs` (`id`),
  ADD CONSTRAINT `educations_ibfk_2` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`),
  ADD CONSTRAINT `educations_ibfk_3` FOREIGN KEY (`degree_level_id`) REFERENCES `degrees` (`id`),
  ADD CONSTRAINT `educations_ibfk_4` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`);

--
-- Constraints for table `work_histories`
--
ALTER TABLE `work_histories`
  ADD CONSTRAINT `work_histories_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cvs` (`id`),
  ADD CONSTRAINT `work_histories_ibfk_2` FOREIGN KEY (`job_title_id`) REFERENCES `job_title` (`id`),
  ADD CONSTRAINT `work_histories_ibfk_3` FOREIGN KEY (`employment_types_id`) REFERENCES `employment_types` (`id`),
  ADD CONSTRAINT `work_histories_ibfk_4` FOREIGN KEY (`industries_id`) REFERENCES `industries` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
