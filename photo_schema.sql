CREATE DATABASE IF NOT EXISTS payrolldb;
USE payrolldb;

SET FOREIGN_KEY_CHECKS=0;

-- Drop old tables
DROP TABLE IF EXISTS attendance, attendance_corrections, bonuses, deductions, departments, employees, holidays, leave_requests, leave_types, notifications, overtime_assignment_employees, overtime_assignments, overtime_requests, payroll, positions, settings, users;

-- Drop new tables (if re-running)
DROP TABLE IF EXISTS `Department`, `Position`, `Employee`, `LeaveTypes`, `LeaveRequest`, `OvertimeAssign`, `Bonous`, `EmpBonous`, `Payroll`, `Attendance`, `Admin`;

CREATE TABLE `Department` (
  DeptID int NOT NULL AUTO_INCREMENT,
  DeptName varchar(100) NOT NULL,
  PRIMARY KEY (DeptID)
);

CREATE TABLE `Position` (
  PositionID int NOT NULL AUTO_INCREMENT,
  PositionName varchar(100) NOT NULL,
  DeptID int NOT NULL,
  BasicSalary decimal(10,2) NOT NULL,
  PRIMARY KEY (PositionID),
  FOREIGN KEY (`DeptID`) REFERENCES `Department`(`DeptID`) ON DELETE CASCADE
);

CREATE TABLE `Employee` (
  EmpID int NOT NULL AUTO_INCREMENT,
  FirstName varchar(100) NOT NULL,
  LastName varchar(100) NOT NULL,
  Gender varchar(20) NOT NULL,
  Email varchar(255) NOT NULL,
  Password varchar(255) NOT NULL,
  PhoneNumber varchar(20) NOT NULL,
  Address text NOT NULL,
  PositionID int NOT NULL,
  JoinDate date NOT NULL,
  Status varchar(20) NOT NULL,
  PRIMARY KEY (EmpID),
  FOREIGN KEY (`PositionID`) REFERENCES `Position`(`PositionID`) ON DELETE CASCADE
);

CREATE TABLE `LeaveTypes` (
  LeaveTypeID int NOT NULL AUTO_INCREMENT,
  LeaveType varchar(100) NOT NULL,
  DaysAllowed int NOT NULL,
  IsPaid tinyint(1) NOT NULL,
  DeductionRate decimal(5,2) NOT NULL,
  PRIMARY KEY (LeaveTypeID)
);

CREATE TABLE `LeaveRequest` (
  RequestID int NOT NULL AUTO_INCREMENT,
  LeaveTypeID int NOT NULL,
  EmpID int NOT NULL,
  StartDate date NOT NULL,
  EndDate date NOT NULL,
  Reason text NOT NULL,
  Status varchar(20) NOT NULL,
  PRIMARY KEY (RequestID),
  FOREIGN KEY (`LeaveTypeID`) REFERENCES `LeaveTypes`(`LeaveTypeID`) ON DELETE CASCADE,
  FOREIGN KEY (`EmpID`) REFERENCES `Employee`(`EmpID`) ON DELETE CASCADE
);

CREATE TABLE `OvertimeAssign` (
  OvertimeID int NOT NULL AUTO_INCREMENT,
  EmpID int NOT NULL,
  OvertimeDate date NOT NULL,
  StartTime time DEFAULT NULL,
  EndTime time DEFAULT NULL,
  OvertimeHours decimal(5,2) NOT NULL,
  OTRate decimal(5,2) NOT NULL,
  OTAmount decimal(10,2) NOT NULL,
  PRIMARY KEY (OvertimeID),
  FOREIGN KEY (EmpID) REFERENCES `Employee`(EmpID) ON DELETE CASCADE
);

CREATE TABLE `Bonous` (
  BonousID int NOT NULL AUTO_INCREMENT,
  BonusType varchar(100) NOT NULL,
  PRIMARY KEY (BonousID)
);

CREATE TABLE `EmpBonous` (
  EmpBonousID int NOT NULL AUTO_INCREMENT,
  BonousID int NOT NULL,
  EmpID int NOT NULL,
  BonusDate date NOT NULL,
  Amount decimal(10,2) NOT NULL,
  PRIMARY KEY (EmpBonousID),
  FOREIGN KEY (`BonousID`) REFERENCES `Bonous`(`BonousID`) ON DELETE CASCADE,
  FOREIGN KEY (`EmpID`) REFERENCES `Employee`(`EmpID`) ON DELETE CASCADE
);

CREATE TABLE `Payroll` (
  PayrollID int NOT NULL AUTO_INCREMENT,
  EmpID int NOT NULL,
  BasicSalary decimal(10,2) NOT NULL,
  PayrollMonth varchar(20) NOT NULL,
  BonousAmount decimal(10,2) NOT NULL,
  OvertimeAmount decimal(10,2) NOT NULL,
  LeaveDeductionAmount decimal(10,2) NOT NULL,
  NetSalary decimal(10,2) NOT NULL,
  Status varchar(20) NOT NULL,
  PRIMARY KEY (PayrollID),
  FOREIGN KEY (EmpID) REFERENCES `Employee`(EmpID) ON DELETE CASCADE
);

CREATE TABLE `Attendance` (
  AttendanceID int NOT NULL AUTO_INCREMENT,
  EmpID int NOT NULL,
  CheckInTime time,
  CheckOutTime time,
  AttendanceDate date NOT NULL,
  Status varchar(20) NOT NULL,
  PRIMARY KEY (AttendanceID),
  FOREIGN KEY (EmpID) REFERENCES `Employee`(EmpID) ON DELETE CASCADE
);

CREATE TABLE `Admin` (
  AdminID int NOT NULL AUTO_INCREMENT,
  Email varchar(255) NOT NULL,
  Password varchar(255) NOT NULL,
  PRIMARY KEY (AdminID)
);

SET FOREIGN_KEY_CHECKS=1;

INSERT INTO Admin (Email, Password) VALUES ('admin@payroll.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
