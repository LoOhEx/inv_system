-- SQL Script to Update QC Values from Numeric to String Format
-- Run this script to update your existing database

-- 1. Update inv_needs table structure
ALTER TABLE `inv_needs` MODIFY COLUMN `dvc_qc` varchar(50) DEFAULT NULL;

-- 2. Update inv_report table structure  
ALTER TABLE `inv_report` MODIFY COLUMN `dvc_qc` varchar(50) DEFAULT NULL;

-- 3. Convert existing numeric QC values to string format in inv_needs
UPDATE `inv_needs` SET `dvc_qc` = 'LN' WHERE `dvc_qc` = '0';
UPDATE `inv_needs` SET `dvc_qc` = 'DN' WHERE `dvc_qc` = '1';

-- 4. Convert existing numeric QC values to string format in inv_report
UPDATE `inv_report` SET `dvc_qc` = 'LN' WHERE `dvc_qc` = '0';
UPDATE `inv_report` SET `dvc_qc` = 'DN' WHERE `dvc_qc` = '1';

-- 5. Convert existing numeric QC values to string format in inv_act (if any)
UPDATE `inv_act` SET `dvc_qc` = 'LN' WHERE `dvc_qc` = '0';
UPDATE `inv_act` SET `dvc_qc` = 'DN' WHERE `dvc_qc` = '1';

-- Verify the changes
SELECT 'inv_needs QC values:' as table_name, dvc_qc, COUNT(*) as count 
FROM inv_needs 
GROUP BY dvc_qc;

SELECT 'inv_report QC values:' as table_name, dvc_qc, COUNT(*) as count 
FROM inv_report 
GROUP BY dvc_qc;

SELECT 'inv_act QC values:' as table_name, dvc_qc, COUNT(*) as count 
FROM inv_act 
GROUP BY dvc_qc;
