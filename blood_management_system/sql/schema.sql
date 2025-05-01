-- sql/schema.sql

CREATE DATABASE IF NOT EXISTS blood_management;
USE blood_management;

-- Donors
CREATE TABLE donors (
  donor_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  dob DATE NOT NULL,
  gender ENUM('male','female','other') NOT NULL,
  blood_group VARCHAR(3) NOT NULL,
  address TEXT,
  weight FLOAT NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  mobile VARCHAR(20) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  points INT DEFAULT 0,
  last_donation_date DATETIME NULL
);

-- Hospitals
CREATE TABLE hospitals (
  hospital_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  location VARCHAR(255),
  contact_email VARCHAR(100),
  contact_mobile VARCHAR(20),
  password_hash VARCHAR(255) NOT NULL
);

-- Admins
CREATE TABLE admins (
  admin_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  mobile VARCHAR(20),
  password_hash VARCHAR(255) NOT NULL
);

-- Events
CREATE TABLE events (
  event_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  message TEXT NOT NULL,
  date DATE NOT NULL,
  hospital_id INT,
  FOREIGN KEY (hospital_id) REFERENCES hospitals(hospital_id)
);

-- Appointments
CREATE TABLE appointments (
  appointment_id INT AUTO_INCREMENT PRIMARY KEY,
  donor_id INT NOT NULL,
  hospital_id INT NOT NULL,
  date_time DATETIME NOT NULL,
  FOREIGN KEY (donor_id) REFERENCES donors(donor_id),
  FOREIGN KEY (hospital_id) REFERENCES hospitals(hospital_id)
);

-- Inventory
CREATE TABLE inventory (
  blood_type VARCHAR(3) PRIMARY KEY,
  quantity INT NOT NULL DEFAULT 0
);

-- Blood Requests
CREATE TABLE blood_requests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  requester_type ENUM('guest','donor','admin','hospital') NOT NULL,
  requester_id INT NULL,
  name VARCHAR(100) NOT NULL,
  location VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL,
  mobile VARCHAR(20) NOT NULL,
  blood_type VARCHAR(3) NOT NULL,
  quantity INT NOT NULL,
  date_needed DATE NOT NULL,
  emergency TINYINT(1) NOT NULL DEFAULT 0,
  total_cost INT NOT NULL,
  FOREIGN KEY (requester_id) REFERENCES donors(donor_id) ON DELETE SET NULL
);