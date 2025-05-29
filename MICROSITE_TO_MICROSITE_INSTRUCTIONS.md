# Microsite to Microsite Migration Instructions

This guide provides step-by-step instructions for completely renaming the "Microsites" service to "Microsites" throughout your application.

## ⚠️ CRITICAL WARNING

**MAKE A COMPLETE BACKUP BEFORE PROCEEDING!**

This process will make extensive changes to your database, files, and codebase. Ensure you have:
- Database backup
- Complete file system backup
- Version control commit (if using Git)

## Files Created

1. `microsite_to_microsite_migration.sql` - Database schema migration
2. `microsite_to_microsite_converter.php` - File and content conversion script
3. `MICROSITE_TO_MICROSITE_INSTRUCTIONS.md` - This instruction file

## Migration Process

### Step 1: Backup Everything

```bash
# Database backup
mysqldump -u username -p database_name > backup_before_migration.sql

# File system backup
tar -czf backup_before_migration.tar.gz /path/to/your/app

# Git commit (if using version control)
git add .
git commit -m "Backup before microsite to microsite migration"
```

### Step 2: Test with Dry Run

Before making any changes, run the converter in dry-run mode to see what will be changed:

```bash
php microsite_to_microsite_converter.php --dry-run
```

This will show you:
- Which files will be renamed
- Which directories will be renamed  
- Which files will have content modified
- No actual changes will be made

Review the log file `microsite_to_microsite_conversion.log` to verify the changes look correct.

### Step 3: Database Migration

**IMPORTANT: Do this step first before running the file converter!**

```bash
# Connect to your database
mysql -u username -p database_name

# Run the migration script
source microsite_to_microsite_migration.sql

# Verify the migration worked
SHOW TABLES LIKE '%microsite%';
SELECT COUNT(*) FROM microsites_themes;
SELECT COUNT(*) FROM microsites_blocks;
SELECT COUNT(*) FROM microsites_templates;
```

### Step 4: File and Content Conversion

After the database migration is successful, run the file converter:

```bash
php microsite_to_microsite_converter.php
```

The script will:
1. Rename all files and directories containing "microsite"
2. Update all file contents to replace microsite references with microsite
3. Generate a detailed log of all changes

### Step 5: Verification

After the conversion completes:

1. **Check the log file** `microsite_to_microsite_conversion.log` for any errors
2. **Test key functionality:**
   - Create a new microsite
   - Edit an existing microsite
   - Add/remove microsite blocks
   - Access admin panel microsite sections
   - Test API endpoints

3. **Verify database integrity:**
   ```sql
   -- Check for any remaining microsite references
   SELECT * FROM settings WHERE value LIKE '%microsite%';
   SELECT * FROM users WHERE plan_settings LIKE '%microsite%';
   
   -- Verify foreign keys work
   SELECT COUNT(*) FROM microsites_blocks mb 
   JOIN links l ON mb.link_id = l.link_id;
   ```

4. **Check for missed references:**
   ```bash
   # Search for any remaining microsite references
   grep -r "microsite" . --exclude-dir=backup --exclude="*.log" --exclude="*.sql"
   ```

## What Gets Changed

### Database Changes
- Tables: `microsites_*` → `microsites_*`
- Columns: `microsite_*` → `microsite_*`
- Foreign key constraints updated
- Settings and configuration values updated

### File Changes
- **Controllers:** `MicrositeBlock.php` → `MicrositeBlock.php`
- **Views:** All microsite view files and directories
- **Includes:** `microsite_*.php` → `microsite_*.php`
- **Documentation:** `MICROSITE_BLOCKS_ARCHITECTURE.md` → `MICROSITE_BLOCKS_ARCHITECTURE.md`

### Content Changes
- Class names: `MicrositeBlock` → `MicrositeBlock`
- Method names: `get_microsite()` → `get_microsite()`
- Variables: `$microsite_*` → `$microsite_*`
- CSS classes: `.microsite-*` → `.microsite-*`
- Language keys: `microsite.*` → `microsite.*`
- URLs: `/microsite-*` → `/microsite-*`
- All user-facing text

## Rollback Process

If you need to rollback the changes:

1. **Restore database backup:**
   ```bash
   mysql -u username -p database_name < backup_before_migration.sql
   ```

2. **Restore file system backup:**
   ```bash
   tar -xzf backup_before_migration.tar.gz
   ```

3. **Or use Git to revert:**
   ```bash
   git reset --hard HEAD~1
   ```

## Troubleshooting

### Common Issues

1. **Foreign key constraint errors:**
   - Make sure to run the database migration first
   - Check that all foreign key relationships are properly updated

2. **File permission errors:**
   - Ensure the PHP script has write permissions
   - Run with appropriate user permissions

3. **Missing files after conversion:**
   - Check the log file for rename errors
   - Verify file paths in includes and requires

4. **Broken functionality:**
   - Check for any hardcoded paths that weren't updated
   - Verify all language files were updated
   - Clear any application caches

### Verification Queries

```sql
-- Check table structure
DESCRIBE microsites_themes;
DESCRIBE microsites_blocks;
DESCRIBE microsites_templates;

-- Verify data integrity
SELECT COUNT(*) as total_microsites FROM links WHERE type = 'microsite';
SELECT COUNT(*) as total_blocks FROM microsites_blocks;
SELECT COUNT(*) as total_themes FROM microsites_themes;

-- Check for orphaned records
SELECT COUNT(*) FROM microsites_blocks mb 
LEFT JOIN links l ON mb.link_id = l.link_id 
WHERE l.link_id IS NULL;
```

## Post-Migration Tasks

1. **Clear application caches** (if any)
2. **Update documentation** to reflect new terminology
3. **Test all microsite functionality** thoroughly
4. **Update any external integrations** that reference microsites
5. **Train users** on the new terminology

## Support

If you encounter issues during migration:

1. Check the conversion log file for detailed error messages
2. Verify your backup is complete and restorable
3. Test the rollback process in a staging environment first
4. Consider running the migration on a staging environment before production

## Success Indicators

The migration is successful when:
- ✅ All database tables are renamed correctly
- ✅ All files and directories are renamed
- ✅ No "microsite" references remain in code
- ✅ All functionality works as expected
- ✅ Admin panel shows "Microsites" terminology
- ✅ User interface displays "Microsites" everywhere
- ✅ API endpoints respond correctly
- ✅ No database constraint errors

Remember: This is a significant change that affects the entire application. Take your time, test thoroughly, and don't hesitate to rollback if issues arise.
