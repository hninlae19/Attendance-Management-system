-- ====================================================================
-- ATTENDANCE & PAYROLL MANAGEMENT SYSTEM (APMS)
-- Complete Database Schema with Full Foreign Key Relationships & Seed Data
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `payrolldb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `payrolldb`;

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------------------
-- 1. Table: department
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `DeptID` int NOT NULL AUTO_INCREMENT,
  `DeptName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`DeptID`),
  UNIQUE KEY `uq_dept_name` (`DeptName`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 2. Table: position
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `position`;
CREATE TABLE `position` (
  `PositionID` int NOT NULL AUTO_INCREMENT,
  `PositionName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DeptID` int NOT NULL,
  `BasicSalary` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`PositionID`),
  KEY `idx_position_dept` (`DeptID`),
  CONSTRAINT `fk_position_department` FOREIGN KEY (`DeptID`) REFERENCES `department` (`DeptID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 3. Table: employee
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `EmpID` int NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `LastName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Gender` enum('Male','Female','Other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Other',
  `Email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PhoneNumber` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `PositionID` int NOT NULL,
  `JoinDate` date NOT NULL,
  `Status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `ProfilePhoto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PasswordResetRequest` tinyint(1) NOT NULL DEFAULT '0',
  `is_first_login` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`EmpID`),
  UNIQUE KEY `uq_emp_email` (`Email`),
  KEY `idx_employee_position` (`PositionID`),
  KEY `idx_employee_status` (`Status`),
  CONSTRAINT `fk_employee_position` FOREIGN KEY (`PositionID`) REFERENCES `position` (`PositionID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 4. Table: admin
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `AdminID` int NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`AdminID`),
  UNIQUE KEY `uq_admin_email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 5. Table: attendance
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `AttendanceID` int NOT NULL AUTO_INCREMENT,
  `EmpID` int NOT NULL,
  `AttendanceDate` date NOT NULL,
  `CheckInTime` time DEFAULT NULL,
  `CheckOutTime` time DEFAULT NULL,
  `Status` enum('Present','Late','Half Day','Absent','On Leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Present',
  `is_auto_checkout` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`AttendanceID`),
  UNIQUE KEY `uq_emp_attendance_date` (`EmpID`, `AttendanceDate`),
  KEY `idx_attendance_date` (`AttendanceDate`),
  CONSTRAINT `fk_attendance_employee` FOREIGN KEY (`EmpID`) REFERENCES `employee` (`EmpID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 6. Table: bonous
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `bonous`;
CREATE TABLE `bonous` (
  `BonousID` int NOT NULL AUTO_INCREMENT,
  `BonusType` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`BonousID`),
  UNIQUE KEY `uq_bonus_type` (`BonusType`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 7. Table: empbonous
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `empbonous`;
CREATE TABLE `empbonous` (
  `EmpBonousID` int NOT NULL AUTO_INCREMENT,
  `BonousID` int NOT NULL,
  `EmpID` int NOT NULL,
  `BonusDate` date NOT NULL,
  `Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`EmpBonousID`),
  KEY `idx_empbonous_bonus` (`BonousID`),
  KEY `idx_empbonous_emp` (`EmpID`),
  CONSTRAINT `fk_empbonous_bonus` FOREIGN KEY (`BonousID`) REFERENCES `bonous` (`BonousID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_empbonous_employee` FOREIGN KEY (`EmpID`) REFERENCES `employee` (`EmpID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 8. Table: leavetypes
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `leavetypes`;
CREATE TABLE `leavetypes` (
  `LeaveTypeID` int NOT NULL AUTO_INCREMENT,
  `LeaveType` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DaysAllowed` int NOT NULL DEFAULT '0',
  `IsPaid` tinyint(1) NOT NULL DEFAULT '1',
  `DeductionRate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `DurationMonths` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`LeaveTypeID`),
  UNIQUE KEY `uq_leavetype_name` (`LeaveType`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 9. Table: leaverequest
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `leaverequest`;
CREATE TABLE `leaverequest` (
  `RequestID` int NOT NULL AUTO_INCREMENT,
  `LeaveTypeID` int NOT NULL,
  `EmpID` int NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `Reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`RequestID`),
  KEY `idx_leaverequest_type` (`LeaveTypeID`),
  KEY `idx_leaverequest_emp` (`EmpID`),
  CONSTRAINT `fk_leaverequest_leavetype` FOREIGN KEY (`LeaveTypeID`) REFERENCES `leavetypes` (`LeaveTypeID`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_leaverequest_employee` FOREIGN KEY (`EmpID`) REFERENCES `employee` (`EmpID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 10. Table: overtimeassign
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `overtimeassign`;
CREATE TABLE `overtimeassign` ( 
   `OvertimeID` int NOT NULL AUTO_INCREMENT, 
   `EmpID` int NOT NULL, 
   `OvertimeDate` date NOT NULL, 
   `StartTime` datetime DEFAULT NULL, 
   `EndTime` datetime DEFAULT NULL, 
   `TotalHours` decimal(5,2) NOT NULL DEFAULT '0.00', 
   `RateMultiplier` decimal(3,2) NOT NULL DEFAULT '1.00', 
   `OTAmount` decimal(12,2) NOT NULL DEFAULT '0.00', 
   `Status` enum('Pending','Accepted','Rejected','InProgress','Completed','NoOT','Cancelled','No Show') NOT NULL DEFAULT 'Pending', 
   `ApprovedBy` varchar(100) DEFAULT NULL, 
   `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`OvertimeID`), 
   KEY `idx_overtime_emp` (`EmpID`), 
   KEY `idx_overtime_date` (`OvertimeDate`), 
   CONSTRAINT `fk_overtime_employee` FOREIGN KEY (`EmpID`) REFERENCES `employee` (`EmpID`) ON DELETE CASCADE ON UPDATE CASCADE 
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 11. Table: payroll
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `payroll`;
CREATE TABLE `payroll` (
  `PayrollID` int NOT NULL AUTO_INCREMENT,
  `EmpID` int NOT NULL,
  `employee_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BasicSalary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `PayrollMonth` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PayableDays` int NOT NULL DEFAULT '0',
  `BonousAmount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `OvertimeAmount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `LeaveDeductionAmount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `NetSalary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Status` enum('Pending','Approved','Paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`PayrollID`),
  KEY `idx_payroll_emp` (`EmpID`),
  KEY `idx_payroll_month` (`PayrollMonth`),
  CONSTRAINT `fk_payroll_employee` FOREIGN KEY (`EmpID`) REFERENCES `employee` (`EmpID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================
-- SEED DATA & INITIAL RECORDS
-- ====================================================================

-- 1. Departments
INSERT INTO `department` (`DeptID`, `DeptName`) VALUES
(1, 'Executive Management'),
(2, 'Software Engineering & IT'),
(3, 'Human Resources'),
(4, 'Sales & Marketing'),
(5, 'Finance & Accounting');

-- 2. Positions
INSERT INTO `position` (`PositionID`, `PositionName`, `DeptID`, `BasicSalary`) VALUES
(1, 'Chief Executive Officer', 1, 3500000.00),
(2, 'Senior Full Stack Engineer', 2, 1800000.00),
(3, 'Frontend UI/UX Specialist', 2, 1200000.00),
(4, 'HR Operations Manager', 3, 1100000.00),
(5, 'Marketing Lead', 4, 950000.00),
(6, 'Senior Accountant', 5, 1000000.00);

-- 3. Admin User (Password: 'password')
INSERT INTO `admin` (`AdminID`, `Email`, `Password`) VALUES
(1, 'admin@gmail.com', '$2y$10$4yY3U0kLzH4qQd4wHkR3w.Qo3d8d3I3n.5X5n3c3I3n.5X5n3c3I3');

-- 4. Employees (Password: 'password')
INSERT INTO `employee` (`EmpID`, `FirstName`, `LastName`, `Gender`, `Email`, `Password`, `PhoneNumber`, `Address`, `PositionID`, `JoinDate`, `Status`, `ProfilePhoto`, `PasswordResetRequest`, `is_first_login`) VALUES
(1, 'Aung', 'Kyaw', 'Male', 'aungkyaw@gmail.com', '$2y$10$4yY3U0kLzH4qQd4wHkR3w.Qo3d8d3I3n.5X5n3c3I3n.5X5n3c3I3', '09-450123456', 'No. 45, Hledan Road, Kamayut, Yangon', 2, '2025-01-15', 'Active', NULL, 0, 0),
(2, 'Hnin', 'Thiri', 'Female', 'hnin@gmail.com', '$2y$10$4yY3U0kLzH4qQd4wHkR3w.Qo3d8d3I3n.5X5n3c3I3n.5X5n3c3I3', '09-790987654', 'No. 12, Pyay Road, Mayangone, Yangon', 3, '2025-03-01', 'Active', NULL, 0, 0),
(3, 'Bae', 'Nora', 'Male', 'baenora@gmail.com', '$2y$10$4yY3U0kLzH4qQd4wHkR3w.Qo3d8d3I3n.5X5n3c3I3n.5X5n3c3I3', '09-5482499098', 'Bogyoke Road, Taunggyi, Shan State', 5, '2026-08-01', 'Active', NULL, 0, 0),
(4, 'Thura', 'Myint', 'Male', 'thura@gmail.com', '$2y$10$4yY3U0kLzH4qQd4wHkR3w.Qo3d8d3I3n.5X5n3c3I3n.5X5n3c3I3', '09-250112233', 'No. 78, Merchant Street, Kyauktada, Yangon', 4, '2025-06-10', 'Active', NULL, 0, 0),
(5, 'Ei', 'Mon', 'Female', 'eimon@gmail.com', '$2y$10$4yY3U0kLzH4qQd4wHkR3w.Qo3d8d3I3n.5X5n3c3I3n.5X5n3c3I3', '09-970334455', 'No. 23, Insein Road, Insein, Yangon', 6, '2025-09-01', 'Active', NULL, 0, 1);

-- 5. Leave Types
INSERT INTO `leavetypes` (`LeaveTypeID`, `LeaveType`, `DaysAllowed`, `IsPaid`, `DeductionRate`, `DurationMonths`) VALUES
(1, 'Casual Leave', 12, 1, 0.00, 12),
(2, 'Sick Leave', 14, 1, 0.00, 12),
(3, 'Annual Leave', 10, 1, 0.00, 12),
(4, 'Maternity Leave', 90, 1, 0.00, 12),
(5, 'Unpaid Personal Leave', 30, 0, 100.00, 12);

-- 6. Bonus Types
INSERT INTO `bonous` (`BonousID`, `BonusType`) VALUES
(1, 'Performance Incentive'),
(2, 'Annual Water Festival Bonus'),
(3, 'Project Milestone Reward'),
(4, 'Attendance Punctuality Bonus');

-- 7. Employee Bonuses
INSERT INTO `empbonous` (`EmpBonousID`, `BonousID`, `EmpID`, `BonusDate`, `Amount`) VALUES
(1, 1, 1, '2026-08-15', 150000.00),
(2, 4, 2, '2026-08-15', 50000.00),
(3, 3, 3, '2026-08-10', 80000.00);

-- 8. Attendance Records
INSERT INTO `attendance` (`AttendanceID`, `EmpID`, `AttendanceDate`, `CheckInTime`, `CheckOutTime`, `Status`, `is_auto_checkout`) VALUES
(1, 1, '2026-08-18', '08:45:00', '17:30:00', 'Present', 0),
(2, 2, '2026-08-18', '09:12:00', '17:45:00', 'Late', 0),
(3, 3, '2026-08-18', '08:50:00', NULL, 'Present', 0),
(4, 4, '2026-08-18', '08:30:00', '17:00:00', 'Present', 0);

-- 9. Leave Requests
INSERT INTO `leaverequest` (`RequestID`, `LeaveTypeID`, `EmpID`, `StartDate`, `EndDate`, `Reason`, `Status`) VALUES
(1, 2, 2, '2026-08-25', '2026-08-26', 'Seasonal fever and doctor consultation', 'Approved'),
(2, 1, 3, '2026-08-28', '2026-08-29', 'Family ceremony in hometown', 'Pending');

-- 10. Overtime Assignments
INSERT INTO `overtimeassign` (`OvertimeID`, `EmpID`, `OvertimeDate`, `StartTime`, `EndTime`, `TotalHours`, `RateMultiplier`, `OTAmount`, `Status`, `ApprovedBy`) VALUES
(1, 1, '2026-08-19', '2026-08-19 18:00:00', '2026-08-19 21:00:00', 3.00, 1.50, 45000.00, 'Approved', 'Administrator'),
(2, 3, '2026-08-20', '2026-08-20 18:00:00', '2026-08-20 20:00:00', 2.00, 1.50, 16000.00, 'Pending', 'Administrator');

-- 11. Payroll
INSERT INTO `payroll` (`PayrollID`, `EmpID`, `employee_code`, `BasicSalary`, `PayrollMonth`, `BonousAmount`, `OvertimeAmount`, `LeaveDeductionAmount`, `NetSalary`, `Status`) VALUES
(1, 1, 'EMP-0001', 1800000.00, '2026-07', 150000.00, 150000.00, 0.00, 2100000.00, 'Paid'),
(2, 2, 'EMP-0002', 1200000.00, '2026-07', 50000.00, 50000.00, 0.00, 1300000.00, 'Paid'),
(3, 3, 'EMP-0003', 950000.00, '2026-07', 80000.00, 32000.00, 0.00, 1062000.00, 'Paid');

SET FOREIGN_KEY_CHECKS = 1;
