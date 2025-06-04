# Altum to SeeGap Comprehensive Replacement Guide

## Overview

This document provides a complete guide for replacing all instances of "Altum", "AltumCode", and "altumco.de" with "SeeGap" and "Seegap.com" throughout your application.

## What This Replacement Covers

### 1. **PHP Code Transformations**
- `defined('ALTUMCODE')` → `defined('SEEGAP')`
- `namespace Altum` → `namespace SeeGap`
- `\Altum\` → `\SeeGap\`
- `use Altum` → `use SeeGap`

### 2. **CSS and Frontend**
- `.altum-animate` → `.seegap-animate`
- `.altum-file-input` → `.seegap-file-input`
- All CSS classes with `altum-` prefix → `seegap-`

### 3. **URL Parameters and Routing**
- `?altum=` → `?seegap=`
- `.htaccess` rewrite rules
- Nginx configuration files

### 4. **Branding and Text**
- `AltumCode` → `SeeGap`
- `altumcode.com` → `Seegap.com`
- `altumco.de` → `Seegap.com`

### 5. **Database Content**
- User names and settings
- SMTP configuration
- Theme paths and settings
- External page links

### 6. **Theme Paths**
- `themes/altum/` → `themes/phoenix/`
- JavaScript theme references

## Files Included

### 1. `comprehensive_altum_to_seegap_replacer.sh`
The main replacement script that handles all file transformations.

**Features:**
- ✅ Automatic backup creation
- ✅ Dry-run mode for testing
- ✅ Comprehensive logging
- ✅ Progress tracking
- ✅ Validation and verification
- ✅ Rollback capability

### 2. `database_migration_altum_to_seegap.sql`
Database migration script (auto-generated) that updates:
- User data
- Settings and configuration
- Theme data
- Page links
- Stored procedures

## Usage Instructions

### Step 1: Preparation
```bash
# Navigate to your application directory
cd /path/to/your/app

# Make sure you have backups (the script creates one, but extra safety)
# Stop your web server and any running processes
```

### Step 2: Test Run (Recommended)
```bash
# Run in dry-run mode to see what would be changed
./comprehensive_altum_to_seegap_replacer.sh --dry-run
```

### Step 3: Execute the Replacement
```bash
# Run the actual replacement
./comprehensive_altum_to_seegap_replacer.sh
```

### Step 4: Database Migration
```bash
# Import the generated database migration
mysql -u your_username -p your_database < database_migration_altum_to_seegap.sql
```

### Step 5: Verification
1. Check the generated log file: `altum_to_seegap_replacement.log`
2. Review any warnings about remaining references
3. Test your application thoroughly
4. Check all major functionality

## What Gets Backed Up

The script automatically creates a backup in `backup_YYYYMMDD_HHMMSS/` containing:
- All PHP files
- All configuration files
- All theme files
- All SQL files
- **Excludes:** uploads/, vendor/, cache/, .git/

## Rollback Instructions

If you need to rollback the changes:

```bash
# Stop your application
# Restore from backup
rsync -av backup_YYYYMMDD_HHMMSS/ ./

# Restore database from your database backup
mysql -u your_username -p your_database < your_database_backup.sql
```

## Post-Migration Checklist

### ✅ **Immediate Checks**
- [ ] Application loads without errors
- [ ] Login functionality works
- [ ] Admin panel accessible
- [ ] Database connections working

### ✅ **Functionality Tests**
- [ ] User registration/login
- [ ] Link creation and management
- [ ] QR code generation
- [ ] File uploads
- [ ] Email functionality
- [ ] Payment processing (if applicable)

### ✅ **Visual/Branding Checks**
- [ ] All "AltumCode" references changed to "SeeGap"
- [ ] Email templates updated
- [ ] Footer links updated
- [ ] Admin interface branding

### ✅ **Technical Validation**
- [ ] No PHP errors in logs
- [ ] CSS animations working (seegap-animate classes)
- [ ] File uploads working (seegap-file-input classes)
- [ ] URL routing working correctly

## Troubleshooting

### Common Issues

**1. PHP Fatal Errors about missing classes**
```
Solution: Clear any cached autoloader files
rm -rf vendor/composer/autoload_*
composer dump-autoload
```

**2. CSS animations not working**
```
Solution: Clear browser cache and check CSS files were updated
```

**3. Database connection issues**
```
Solution: Check config.php for any hardcoded references that weren't caught
```

**4. Vendor package issues**
```
Solution: The script preserves altumcode vendor packages. If issues occur:
- Check composer.json
- Run: composer install --no-dev
```

### Manual Review Areas

After running the script, manually check these files:
- `config.php` - Database and application configuration
- `.env` files (if any)
- Any custom configuration files
- Third-party integrations

## Script Options

```bash
# Show help
./comprehensive_altum_to_seegap_replacer.sh --help

# Dry run (recommended first)
./comprehensive_altum_to_seegap_replacer.sh --dry-run

# Normal execution
./comprehensive_altum_to_seegap_replacer.sh
```

## What's NOT Changed

The script preserves:
- Vendor packages (`altumcode/simple-qrcode`, `altumcode/vcard`, etc.)
- Upload files and user data
- Git history
- Cache files

## Support and Recovery

### If Something Goes Wrong
1. **Stop the application immediately**
2. **Restore from the backup:**
   ```bash
   rsync -av backup_YYYYMMDD_HHMMSS/ ./
   ```
3. **Restore database from your backup**
4. **Review the log file** for clues about what went wrong
5. **Contact support** with the log file

### Log File Location
- `altum_to_seegap_replacement.log` - Contains detailed execution log
- Check this file for any warnings or errors

## Security Notes

- The script preserves all security constants and authentication
- All user passwords and sensitive data remain unchanged
- Only branding and namespace references are modified

## Performance Impact

- **During migration:** Application should be offline
- **After migration:** No performance impact expected
- **File size:** Minimal change in total file size

---

## Summary

This comprehensive replacement script transforms your Altum-based application to SeeGap branding while maintaining all functionality. The process is designed to be safe, reversible, and thoroughly logged.

**Estimated Time:** 5-15 minutes depending on application size
**Downtime Required:** Yes, during migration
**Reversible:** Yes, via backup restoration

For questions or issues, refer to the generated log files and this documentation.
