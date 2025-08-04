# Form Submissions Setup Guide

This guide will help you set up the form submissions functionality in your SeeGap application.

## 🚀 Quick Setup

### Option 1: Automated Setup (Recommended)
```bash
# Make sure you're in the SeeGap root directory
./setup_database.sh
```

### Option 2: Manual Setup
```bash
# Connect to your MySQL database and run:
mysql -u your_username -p your_database_name < setup_form_submissions_table.sql
```

### Option 3: Docker MySQL Setup
```bash
# If using Docker MySQL container:
docker exec -i appseegapcom-mysql-1 mysql -u root -p your_database_name < setup_form_submissions_table.sql
```

## 📋 What Gets Created

### Database Table: `form_submissions`
```sql
CREATE TABLE `form_submissions` (
  `form_submission_id` int NOT NULL AUTO_INCREMENT,
  `microsite_block_id` int NOT NULL,
  `link_id` int NOT NULL,
  `form_type` varchar(32) NOT NULL DEFAULT 'custom',
  `responses` longtext,
  `metadata` longtext,
  `ip` varchar(64) DEFAULT NULL,
  `user_agent` text,
  `submitted_at` datetime NOT NULL,
  PRIMARY KEY (`form_submission_id`),
  KEY `microsite_block_id` (`microsite_block_id`),
  KEY `link_id` (`link_id`),
  KEY `submitted_at` (`submitted_at`)
);
```

### Test Data
- 2 sample form submissions will be inserted for testing
- Sample responses include ratings, text inputs, and metadata

## 🧪 Testing the Setup

### 1. Verify Database Setup
Visit: `http://localhost:8080/test_form_submissions.php`

This will show you:
- ✅ Table structure
- 📊 Submission count
- 📋 Recent submissions
- 🔧 System status

### 2. Test Form Submissions
Visit: `http://localhost:8080/ixrew`

This should display:
- Form with various question types
- Star ratings
- Number ratings
- Text inputs

### 3. Submit a Test Form
1. Fill out the form fields
2. Click Submit
3. Check for success message
4. Refresh the test page to see new submission

## 📊 Viewing Form Data

### Database Query Examples

#### View All Submissions
```sql
SELECT * FROM form_submissions ORDER BY submitted_at DESC;
```

#### View Formatted Responses
```sql
SELECT 
    form_submission_id,
    microsite_block_id,
    JSON_PRETTY(responses) as formatted_responses,
    submitted_at
FROM form_submissions;
```

#### Analytics Query - Average Ratings
```sql
SELECT 
    JSON_UNQUOTE(JSON_EXTRACT(response.value, '$.question')) as question,
    AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(response.value, '$.response')) AS DECIMAL(3,2))) as avg_rating
FROM form_submissions fs
JOIN JSON_TABLE(fs.responses, '$[*]' COLUMNS (
    question VARCHAR(255) PATH '$.question',
    type VARCHAR(50) PATH '$.type',
    response TEXT PATH '$.response'
)) as response
WHERE response.type IN ('rating_star', 'rating_number')
GROUP BY response.question;
```

## 🔧 Troubleshooting

### Common Issues

#### 1. Table Already Exists Error
```sql
-- Check if table exists
SHOW TABLES LIKE 'form_submissions';

-- If it exists, you can drop and recreate:
DROP TABLE form_submissions;
-- Then run the setup script again
```

#### 2. Foreign Key Constraint Errors
```sql
-- Check if referenced tables exist
SHOW TABLES LIKE 'microsites_blocks';
SHOW TABLES LIKE 'links';

-- If they don't exist, create the table without foreign keys:
CREATE TABLE `form_submissions` (
  `form_submission_id` int NOT NULL AUTO_INCREMENT,
  `microsite_block_id` int NOT NULL,
  `link_id` int NOT NULL,
  `form_type` varchar(32) NOT NULL DEFAULT 'custom',
  `responses` longtext,
  `metadata` longtext,
  `ip` varchar(64) DEFAULT NULL,
  `user_agent` text,
  `submitted_at` datetime NOT NULL,
  PRIMARY KEY (`form_submission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 3. Form Not Displaying
- Check if form block exists in database
- Verify form.php template exists
- Check browser console for JavaScript errors

#### 4. Submissions Not Saving
- Check database connection
- Verify AJAX endpoint is accessible
- Check server logs for PHP errors

### Log Files
Check these locations for errors:
- `uploads/logs/2025-07-24.log`
- Docker logs: `docker-compose logs php`
- MySQL logs: `docker-compose logs mysql`

## 📈 Analytics & Reporting

### Built-in Analytics
The form data structure supports:
- Response distribution analysis
- Rating averages and trends
- Submission volume tracking
- Geographic analysis (by IP)
- User behavior patterns

### Export Options
- CSV export for Excel analysis
- JSON API for custom integrations
- Direct database queries for BI tools

## 🔒 Privacy & Compliance

### Data Retention
Configure automatic cleanup:
```sql
-- Delete submissions older than 1 year
DELETE FROM form_submissions 
WHERE submitted_at < DATE_SUB(NOW(), INTERVAL 365 DAY);
```

### Data Anonymization
```sql
-- Anonymize IP addresses after 90 days
UPDATE form_submissions 
SET ip = 'ANONYMIZED' 
WHERE submitted_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
```

## 🎯 Next Steps

1. **Setup Complete**: Run the setup script
2. **Test Forms**: Submit test data
3. **Verify Storage**: Check database entries
4. **Configure Analytics**: Set up reporting dashboards
5. **Production Ready**: Deploy with confidence

## 📞 Support

If you encounter issues:
1. Check the troubleshooting section above
2. Review log files for specific errors
3. Verify database connectivity
4. Ensure all required files are present

The form submission system is now ready for production use! 🎉
