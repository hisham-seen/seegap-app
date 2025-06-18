CREATE DATABASE IF NOT EXISTS seegap_production_db;
CREATE USER IF NOT EXISTS 'seegap_prod_user_2025'@'%' IDENTIFIED BY 'SeeGapProd2025MySQLSecure';
GRANT ALL PRIVILEGES ON seegap_production_db.* TO 'seegap_prod_user_2025'@'%';
FLUSH PRIVILEGES;
SELECT User, Host FROM mysql.user WHERE User LIKE 'seegap%';
