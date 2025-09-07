-- Drop previous database if exists
DROP DATABASE IF EXISTS cms;

-- Create new database
CREATE DATABASE cms;

-- Select database
USE cms;

-- Start transaction
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `Company`
-- --------------------------------------------------------

CREATE TABLE `Company` (
  `Company_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(100) NOT NULL,
  `Email` VARCHAR(100) UNIQUE,
  `Phone` VARCHAR(20),
  `Address` VARCHAR(255),
  `Type` VARCHAR(50),
  `Zip_code` VARCHAR(10),
  PRIMARY KEY (`Company_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `Employee`
-- --------------------------------------------------------

CREATE TABLE `Employee` (
  `Emp_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Profile` VARCHAR(255),
  `Name` VARCHAR(100) NOT NULL,
  `Email` VARCHAR(100) UNIQUE,
  `Phone` VARCHAR(20),
  `Role` VARCHAR(50),
  `Address` VARCHAR(255),
  `DOB` DATE,
  `Department` VARCHAR(100),
  `Company_ID` INT(11),
  `Password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`Emp_ID`),
  KEY `Company_ID` (`Company_ID`),
  CONSTRAINT `fk_emp_company` FOREIGN KEY (`Company_ID`) REFERENCES `Company` (`Company_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `Client`
-- --------------------------------------------------------

CREATE TABLE `Client` (
  `Client_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(100) NOT NULL,
  `Email` VARCHAR(100) UNIQUE,
  `Password` VARCHAR(255) NOT NULL,
  `Phone` VARCHAR(20),
  `Address` VARCHAR(255),
  `DOB` DATE,
  PRIMARY KEY (`Client_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `Book_Appt`
-- --------------------------------------------------------

CREATE TABLE `Book_Appt` (
  `Appt_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Client_ID` INT(11),
  `Company_ID` INT(11),
  `Emp_ID` INT(11),
  `Date` DATE,
  `Time` TIME,
  `Reason` VARCHAR(255),
  `Status` VARCHAR(50),
  PRIMARY KEY (`Appt_ID`),
  KEY `Client_ID` (`Client_ID`),
  KEY `Company_ID` (`Company_ID`),
  KEY `Emp_ID` (`Emp_ID`),
  CONSTRAINT `fk_appt_client` FOREIGN KEY (`Client_ID`) REFERENCES `Client` (`Client_ID`) ON DELETE CASCADE,
  CONSTRAINT `fk_appt_company` FOREIGN KEY (`Company_ID`) REFERENCES `Company` (`Company_ID`) ON DELETE CASCADE,
  CONSTRAINT `fk_appt_employee` FOREIGN KEY (`Emp_ID`) REFERENCES `Employee` (`Emp_ID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `Time_Stamp`
-- --------------------------------------------------------

CREATE TABLE `Time_Stamp` (
  `Time_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Start_Time` TIME NOT NULL,
  `End_Time` TIME NOT NULL,
  PRIMARY KEY (`Time_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `Timetable`
-- --------------------------------------------------------

CREATE TABLE `Timetable` (
  `Company_ID` INT(11) NOT NULL,
  `Emp_ID` INT(11) NOT NULL,
  `Time_ID` INT(11) NOT NULL,
  PRIMARY KEY (`Company_ID`, `Emp_ID`, `Time_ID`),
  CONSTRAINT `fk_timetable_company` FOREIGN KEY (`Company_ID`) REFERENCES `Company` (`Company_ID`) ON DELETE CASCADE,
  CONSTRAINT `fk_timetable_employee` FOREIGN KEY (`Emp_ID`) REFERENCES `Employee` (`Emp_ID`) ON DELETE CASCADE,
  CONSTRAINT `fk_timetable_time` FOREIGN KEY (`Time_ID`) REFERENCES `Time_Stamp` (`Time_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Commit transaction
COMMIT;
