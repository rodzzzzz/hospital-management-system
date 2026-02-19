CREATE DATABASE IF NOT EXISTS `TTSI_auto`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `TTSI_auto`;

CREATE TABLE IF NOT EXISTS patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_code VARCHAR(32) NULL UNIQUE,
  philhealth_pin VARCHAR(32) NULL UNIQUE,
  full_name VARCHAR(255) NOT NULL,
  dob DATE NULL,
  sex VARCHAR(16) NULL,
  contact VARCHAR(64) NULL,
  civil_status VARCHAR(32) NULL,
  email VARCHAR(255) NULL,
  street_address VARCHAR(255) NULL,
  barangay VARCHAR(128) NULL,
  city VARCHAR(128) NULL,
  province VARCHAR(128) NULL,
  zip_code VARCHAR(16) NULL,
  employer_name VARCHAR(255) NULL,
  employer_address VARCHAR(255) NULL,
  patient_type VARCHAR(32) NULL,
  initial_location VARCHAR(64) NULL,
  department VARCHAR(64) NULL,
  diagnosis VARCHAR(255) NULL,
  emergency_contact_name VARCHAR(255) NULL,
  emergency_contact_relationship VARCHAR(64) NULL,
  emergency_contact_phone VARCHAR(64) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS philhealth_members (
  patient_id INT NOT NULL,
  philhealth_pin VARCHAR(32) NOT NULL UNIQUE,
  employer_name VARCHAR(255) NULL,
  employer_address VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (patient_id),
  CONSTRAINT fk_philhealth_members_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS philhealth_claims (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL UNIQUE,
  status VARCHAR(32) NOT NULL DEFAULT 'draft',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_philhealth_claims_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS philhealth_forms (
  claim_id INT NOT NULL,
  philhealth_pin VARCHAR(32) NOT NULL,
  form_code VARCHAR(8) NOT NULL,
  data_json JSON NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (claim_id, form_code),
  KEY idx_philhealth_forms_pin (philhealth_pin),
  CONSTRAINT fk_philhealth_forms_claim FOREIGN KEY (claim_id) REFERENCES philhealth_claims(id) ON DELETE CASCADE,
  CONSTRAINT fk_philhealth_forms_pin FOREIGN KEY (philhealth_pin) REFERENCES patients(philhealth_pin) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
