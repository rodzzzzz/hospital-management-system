-- Add purpose_of_visit column to patients table
ALTER TABLE `patients` 
ADD COLUMN `purpose_of_visit` varchar(255) DEFAULT NULL 
AFTER `diagnosis`;

-- Add purpose_of_visit column to patient_queue table
ALTER TABLE `patient_queue` 
ADD COLUMN `purpose_of_visit` varchar(255) DEFAULT NULL 
AFTER `notes`;
