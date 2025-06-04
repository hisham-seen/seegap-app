-- Database Migration Script: Altum to SeeGap
-- This script updates database content to replace Altum references with SeeGap

-- Update user names
UPDATE users SET name = REPLACE(name, 'AltumCode', 'SeeGap') WHERE name LIKE '%AltumCode%';

-- Update settings - SMTP from_name
UPDATE settings SET value = JSON_SET(value, '$.from_name', 'SeeGap') 
WHERE `key` = 'smtp' AND JSON_EXTRACT(value, '$.from_name') = 'AltumCode';

-- Update settings - branding text
UPDATE settings SET value = REPLACE(value, 'AltumCode', 'SeeGap') WHERE value LIKE '%AltumCode%';
UPDATE settings SET value = REPLACE(value, 'altumcode.com', 'Seegap.com') WHERE value LIKE '%altumcode.com%';
UPDATE settings SET value = REPLACE(value, 'altumco.de', 'Seegap.com') WHERE value LIKE '%altumco.de%';

-- Update microsites_themes with theme path corrections
UPDATE microsites_themes SET settings = REPLACE(settings, 'themes/altum/', 'themes/phoenix/') 
WHERE settings LIKE '%themes/altum/%';

UPDATE microsites_themes SET settings = REPLACE(settings, 'themes\\/altum\\/', 'themes\\/phoenix\\/') 
WHERE settings LIKE '%themes\\/altum\\/%';

-- Update pages table for external links
UPDATE pages SET url = REPLACE(url, 'altumcode.com', 'Seegap.com') WHERE url LIKE '%altumcode.com%';
UPDATE pages SET url = REPLACE(url, 'altumco.de', 'Seegap.com') WHERE url LIKE '%altumco.de%';
UPDATE pages SET title = REPLACE(title, 'AltumCode', 'SeeGap') WHERE title LIKE '%AltumCode%';
UPDATE pages SET description = REPLACE(description, 'AltumCode', 'SeeGap') WHERE description LIKE '%AltumCode%';

-- Update any other tables that might contain altum references
UPDATE settings SET value = REPLACE(value, '66microsites by AltumCode', '66microsites by SeeGap') 
WHERE value LIKE '%66microsites by AltumCode%';

-- Rename stored procedures
DROP PROCEDURE IF EXISTS seegap;
DELIMITER //
CREATE PROCEDURE seegap()
BEGIN
    -- Placeholder procedure
    SELECT 'SeeGap migration completed' as status;
END //
DELIMITER ;

-- Clean up old altum procedures if they exist
DROP PROCEDURE IF EXISTS altum;

