#!/bin/bash

# Comprehensive Altum to SeeGap Replacement Script (Fixed Version)
# This script replaces all instances of altum, altumcode, and altumco.de with SeeGap equivalents
# Author: Generated for SeeGap Migration
# Date: $(date)

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
BACKUP_DIR="./backup_$(date +%Y%m%d_%H%M%S)"
LOG_FILE="./altum_to_seegap_replacement.log"
DRY_RUN=false

# Function to log messages
log() {
    echo -e "${GREEN}[$(date '+%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE"
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$LOG_FILE"
}

info() {
    echo -e "${BLUE}[INFO]${NC} $1" | tee -a "$LOG_FILE"
}

# Function to create backup
create_backup() {
    log "Creating backup in $BACKUP_DIR..."
    mkdir -p "$BACKUP_DIR"
    
    # Copy all files except uploads, vendor, and cache directories
    rsync -av --exclude='uploads/' --exclude='vendor/' --exclude='cache/' --exclude='.git/' . "$BACKUP_DIR/"
    
    log "Backup created successfully"
}

# Function to replace in files with safe regex patterns
replace_in_files_safe() {
    local pattern="$1"
    local replacement="$2"
    local file_pattern="$3"
    local description="$4"
    
    info "Replacing $description..."
    
    if [ "$DRY_RUN" = true ]; then
        info "DRY RUN: Would replace '$pattern' with '$replacement' in $file_pattern files"
        find . -name "$file_pattern" -not -path "./vendor/*" -not -path "./uploads/*" -not -path "./.git/*" -not -path "./backup_*/*" | head -5
        return
    fi
    
    # Find and replace in files using sed for safer replacements
    find . -name "$file_pattern" -not -path "./vendor/*" -not -path "./uploads/*" -not -path "./.git/*" -not -path "./backup_*/*" -type f -exec grep -l "$pattern" {} \; 2>/dev/null | while read -r file; do
        if [[ -f "$file" ]]; then
            info "Processing: $file"
            # Use sed for safer replacement
            sed -i.bak "s|$pattern|$replacement|g" "$file" && rm -f "$file.bak"
        fi
    done
}

# Function to replace using perl with escaped patterns
replace_with_perl() {
    local pattern="$1"
    local replacement="$2"
    local file_pattern="$3"
    local description="$4"
    
    info "Replacing $description..."
    
    if [ "$DRY_RUN" = true ]; then
        info "DRY RUN: Would replace '$pattern' with '$replacement' in $file_pattern files"
        return
    fi
    
    find . -name "$file_pattern" -not -path "./vendor/*" -not -path "./uploads/*" -not -path "./.git/*" -not -path "./backup_*/*" -type f -exec grep -l "$pattern" {} \; 2>/dev/null | while read -r file; do
        if [[ -f "$file" ]]; then
            info "Processing: $file"
            # Use perl with properly escaped patterns
            perl -pi -e "s/\Q$pattern\E/$replacement/g" "$file"
        fi
    done
}

# Function to replace in specific file types
replace_by_file_type() {
    local pattern="$1"
    local replacement="$2"
    local extensions="$3"
    local description="$4"
    
    info "Replacing $description in $extensions files..."
    
    for ext in $extensions; do
        replace_in_files_safe "$pattern" "$replacement" "*.$ext" "$description ($ext files)"
    done
}

# Main replacement function
perform_replacements() {
    log "Starting comprehensive Altum to SeeGap replacement..."
    
    # 1. PHP Security Constants
    info "=== Phase 1: PHP Security Constants ==="
    replace_by_file_type "defined('ALTUMCODE')" "defined('SEEGAP')" "php" "PHP security constants"
    replace_by_file_type "ALTUMCODE" "SEEGAP" "php" "ALTUMCODE constant references"
    
    # 2. Namespace Replacements
    info "=== Phase 2: Namespace Replacements ==="
    replace_by_file_type "namespace Altum" "namespace SeeGap" "php" "Namespace declarations"
    replace_with_perl "\\\\Altum\\\\" "\\\\SeeGap\\\\" "*.php" "Namespace usage (escaped)"
    replace_by_file_type "use Altum" "use SeeGap" "php" "Use statements"
    
    # 3. CSS Class Replacements
    info "=== Phase 3: CSS Class Replacements ==="
    replace_by_file_type "altum-animate" "seegap-animate" "css" "CSS animation classes"
    replace_by_file_type "altum-animate" "seegap-animate" "php" "CSS animation classes in PHP"
    replace_by_file_type "altum-file-input" "seegap-file-input" "css" "CSS file input classes"
    replace_by_file_type "altum-file-input" "seegap-file-input" "php" "CSS file input classes in PHP"
    replace_by_file_type ".altum-" ".seegap-" "css" "CSS class selectors"
    
    # 4. URL Parameter Replacements
    info "=== Phase 4: URL Parameter Replacements ==="
    replace_by_file_type "?altum=" "?seegap=" "php" "URL parameters in PHP"
    replace_by_file_type "?altum=" "?seegap=" "htaccess" "URL parameters in htaccess"
    replace_by_file_type "?altum=" "?seegap=" "conf" "URL parameters in conf"
    replace_by_file_type "altum=" "seegap=" "htaccess" "Rewrite rules in htaccess"
    replace_by_file_type "altum=" "seegap=" "conf" "Rewrite rules in conf"
    
    # 5. Branding and Text Replacements
    info "=== Phase 5: Branding and Text Replacements ==="
    replace_by_file_type "AltumCode" "SeeGap" "php" "AltumCode branding in PHP"
    replace_by_file_type "AltumCode" "SeeGap" "sql" "AltumCode branding in SQL"
    replace_by_file_type "AltumCode" "SeeGap" "html" "AltumCode branding in HTML"
    
    replace_by_file_type "altumcode.com" "Seegap.com" "php" "altumcode.com URLs in PHP"
    replace_by_file_type "altumcode.com" "Seegap.com" "sql" "altumcode.com URLs in SQL"
    replace_by_file_type "altumcode.com" "Seegap.com" "html" "altumcode.com URLs in HTML"
    replace_by_file_type "altumcode.com" "Seegap.com" "js" "altumcode.com URLs in JS"
    
    replace_by_file_type "altumco.de" "Seegap.com" "php" "altumco.de URLs in PHP"
    replace_by_file_type "altumco.de" "Seegap.com" "sql" "altumco.de URLs in SQL"
    replace_by_file_type "altumco.de" "Seegap.com" "html" "altumco.de URLs in HTML"
    replace_by_file_type "altumco.de" "Seegap.com" "js" "altumco.de URLs in JS"
    
    # 6. Theme Path Replacements (Fixed)
    info "=== Phase 6: Theme Path Replacements ==="
    replace_by_file_type "themes/altum/" "themes/phoenix/" "php" "Theme paths in PHP"
    replace_by_file_type "themes/altum/" "themes/phoenix/" "sql" "Theme paths in SQL"
    replace_by_file_type "themes/altum/" "themes/phoenix/" "js" "Theme paths in JS"
    
    # Handle escaped paths in SQL separately
    replace_with_perl "themes\\/altum\\/" "themes\\/phoenix\\/" "*.sql" "Escaped theme paths in SQL"
    
    # 7. JavaScript and JSON Replacements
    info "=== Phase 7: JavaScript and JSON Replacements ==="
    replace_by_file_type "altum" "seegap" "js" "JavaScript variables"
    replace_by_file_type "altum" "seegap" "json" "JSON references"
    
    # 8. Database Procedure Names
    info "=== Phase 8: Database Procedure Names ==="
    replace_with_perl "PROCEDURE \`altum\`" "PROCEDURE \`seegap\`" "*.sql" "SQL procedure names"
    replace_by_file_type "call altum" "call seegap" "sql" "SQL procedure calls"
    replace_by_file_type "drop procedure altum" "drop procedure seegap" "sql" "SQL procedure drops"
    
    # 9. Composer and Vendor References (careful handling)
    info "=== Phase 9: Composer References ==="
    if [[ -f "composer.json" ]] && [ "$DRY_RUN" = false ]; then
        info "Updating composer.json references..."
        # Handle composer.json carefully - only update non-vendor package references
        sed -i.bak 's/"altumcode\//"seegap\//g' composer.json && rm -f composer.json.bak
    fi
    
    # 10. Configuration Files
    info "=== Phase 10: Configuration Files ==="
    replace_by_file_type "User-Agent.*AltumCode" "User-Agent: SeeGap.Com/1.0" "php" "User-Agent strings"
    
    log "Replacement process completed!"
}

# Function to validate replacements
validate_replacements() {
    log "Validating replacements..."
    
    # Check for remaining altum references (excluding vendor)
    remaining_altum=$(find . -name "*.php" -not -path "./vendor/*" -not -path "./uploads/*" -not -path "./.git/*" -not -path "./backup_*/*" -exec grep -l "altum\|ALTUM\|Altum" {} \; 2>/dev/null | wc -l)
    remaining_altumcode=$(find . -name "*.php" -not -path "./vendor/*" -not -path "./uploads/*" -not -path "./.git/*" -not -path "./backup_*/*" -exec grep -l "altumcode\|ALTUMCODE" {} \; 2>/dev/null | wc -l)
    
    if [ "$remaining_altum" -gt 0 ] || [ "$remaining_altumcode" -gt 0 ]; then
        warning "Found $remaining_altum files with 'altum' and $remaining_altumcode files with 'altumcode' references"
        warning "Please review these files manually:"
        find . -name "*.php" -not -path "./vendor/*" -not -path "./uploads/*" -not -path "./.git/*" -not -path "./backup_*/*" -exec grep -l "altum\|ALTUM\|Altum\|altumcode\|ALTUMCODE" {} \; 2>/dev/null | head -10
    else
        log "✅ No remaining altum/altumcode references found in PHP files!"
    fi
}

# Function to generate database migration script
generate_db_migration() {
    log "Generating database migration script..."
    
    cat > "database_migration_altum_to_seegap.sql" << 'EOF'
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

EOF

    log "Database migration script created: database_migration_altum_to_seegap.sql"
}

# Function to show usage
show_usage() {
    echo "Usage: $0 [OPTIONS]"
    echo "Options:"
    echo "  --dry-run    Show what would be changed without making changes"
    echo "  --help       Show this help message"
    echo ""
    echo "This script will:"
    echo "  1. Create a backup of your application"
    echo "  2. Replace all Altum references with SeeGap equivalents"
    echo "  3. Generate a database migration script"
    echo "  4. Validate the changes"
}

# Parse command line arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --dry-run)
            DRY_RUN=true
            shift
            ;;
        --help)
            show_usage
            exit 0
            ;;
        *)
            error "Unknown option: $1"
            show_usage
            exit 1
            ;;
    esac
done

# Main execution
main() {
    log "=== Comprehensive Altum to SeeGap Replacement Script (Fixed) ==="
    log "Starting at $(date)"
    
    if [ "$DRY_RUN" = true ]; then
        warning "DRY RUN MODE - No changes will be made"
    fi
    
    # Create backup (skip in dry run)
    if [ "$DRY_RUN" = false ]; then
        create_backup
    fi
    
    # Perform replacements
    perform_replacements
    
    # Generate database migration script
    if [ "$DRY_RUN" = false ]; then
        generate_db_migration
    fi
    
    # Validate replacements
    if [ "$DRY_RUN" = false ]; then
        validate_replacements
    fi
    
    log "=== Script completed at $(date) ==="
    
    if [ "$DRY_RUN" = false ]; then
        log "Next steps:"
        log "1. Review the changes made"
        log "2. Run the database migration: database_migration_altum_to_seegap.sql"
        log "3. Test your application thoroughly"
        log "4. If issues occur, restore from backup: $BACKUP_DIR"
    else
        log "This was a dry run. Use without --dry-run to perform actual replacements."
    fi
}

# Run the main function
main "$@"
