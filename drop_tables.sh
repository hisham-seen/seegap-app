#!/bin/bash

# =============================================================================
# Database Table Drop Script
# =============================================================================
# This script safely drops all database tables for the SeeGap application
# It supports both Docker and direct MySQL connections with proper error handling
# =============================================================================

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Database configuration (from config.php)
DB_HOST="mysql"
DB_USER="seegap_user"
DB_PASS="seegap_password"
DB_NAME="seegap_local"
SQL_FILE="drop_tables.sql"

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to check if Docker is available and MySQL container is running
check_docker() {
    if command -v docker &> /dev/null; then
        if docker ps --filter "name=mysql" --format "table {{.Names}}" | grep -q mysql; then
            print_status "Docker MySQL container found and running"
            return 0
        else
            print_warning "Docker is available but MySQL container is not running"
            return 1
        fi
    else
        print_warning "Docker is not available"
        return 1
    fi
}

# Function to execute SQL via Docker
execute_sql_docker() {
    local sql_file="$1"
    print_status "Executing SQL via Docker MySQL container..."
    
    if docker exec -i $(docker ps -q --filter "name=mysql") mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$sql_file"; then
        return 0
    else
        return 1
    fi
}

# Function to execute SQL via direct MySQL connection
execute_sql_direct() {
    local sql_file="$1"
    print_status "Executing SQL via direct MySQL connection..."
    
    # Try localhost first, then the configured host
    for host in "localhost" "127.0.0.1" "$DB_HOST"; do
        print_status "Trying to connect to MySQL at $host..."
        if mysql -h "$host" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$sql_file" 2>/dev/null; then
            print_success "Connected successfully to $host"
            return 0
        fi
    done
    
    print_error "Failed to connect to MySQL on any host"
    return 1
}

# Function to show current tables
show_tables() {
    print_status "Current tables in database '$DB_NAME':"
    
    if check_docker; then
        docker exec -i $(docker ps -q --filter "name=mysql") mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null || print_warning "Could not retrieve table list via Docker"
    else
        for host in "localhost" "127.0.0.1" "$DB_HOST"; do
            if mysql -h "$host" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null; then
                break
            fi
        done || print_warning "Could not retrieve table list via direct connection"
    fi
}

# Function to backup database (optional)
backup_database() {
    local backup_file="backup_$(date +%Y%m%d_%H%M%S).sql"
    print_status "Creating backup: $backup_file"
    
    if check_docker; then
        if docker exec $(docker ps -q --filter "name=mysql") mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$backup_file"; then
            print_success "Backup created: $backup_file"
            return 0
        fi
    else
        for host in "localhost" "127.0.0.1" "$DB_HOST"; do
            if mysqldump -h "$host" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$backup_file" 2>/dev/null; then
                print_success "Backup created: $backup_file"
                return 0
            fi
        done
    fi
    
    print_error "Failed to create backup"
    return 1
}

# Main execution function
main() {
    echo "============================================================================="
    echo "                    SeeGap Database Table Drop Script"
    echo "============================================================================="
    echo
    
    # Check if SQL file exists
    if [[ ! -f "$SQL_FILE" ]]; then
        print_error "SQL file '$SQL_FILE' not found!"
        print_status "Please ensure the drop_tables.sql file exists in the current directory."
        exit 1
    fi
    
    print_status "Found SQL file: $SQL_FILE"
    
    # Show current tables
    echo
    show_tables
    echo
    
    # Confirmation prompt
    print_warning "⚠️  WARNING: This will permanently delete ALL data in the database!"
    print_warning "⚠️  Database: $DB_NAME"
    print_warning "⚠️  This action cannot be undone!"
    echo
    
    read -p "Do you want to create a backup before dropping tables? (y/N): " create_backup
    if [[ $create_backup =~ ^[Yy]$ ]]; then
        if ! backup_database; then
            print_error "Backup failed. Aborting operation for safety."
            exit 1
        fi
        echo
    fi
    
    read -p "Are you absolutely sure you want to drop all tables? Type 'DROP ALL TABLES' to confirm: " confirmation
    if [[ "$confirmation" != "DROP ALL TABLES" ]]; then
        print_status "Operation cancelled by user."
        exit 0
    fi
    
    echo
    print_status "Starting table drop operation..."
    
    # Try Docker first, then direct connection
    if check_docker; then
        if execute_sql_docker "$SQL_FILE"; then
            print_success "Tables dropped successfully via Docker!"
        else
            print_error "Failed to drop tables via Docker, trying direct connection..."
            if execute_sql_direct "$SQL_FILE"; then
                print_success "Tables dropped successfully via direct connection!"
            else
                print_error "Failed to drop tables via both Docker and direct connection!"
                exit 1
            fi
        fi
    else
        if execute_sql_direct "$SQL_FILE"; then
            print_success "Tables dropped successfully via direct connection!"
        else
            print_error "Failed to drop tables!"
            exit 1
        fi
    fi
    
    echo
    print_status "Verifying table drop operation..."
    show_tables
    
    echo
    print_success "✅ Database cleanup completed successfully!"
    print_status "All tables have been dropped from database '$DB_NAME'"
    
    if [[ $create_backup =~ ^[Yy]$ ]]; then
        print_status "💾 Your backup is available as: backup_*.sql"
    fi
}

# Help function
show_help() {
    echo "Usage: $0 [OPTIONS]"
    echo
    echo "Options:"
    echo "  -h, --help     Show this help message"
    echo "  --no-confirm   Skip confirmation prompts (dangerous!)"
    echo "  --backup       Force create backup before dropping"
    echo "  --show-tables  Only show current tables, don't drop"
    echo
    echo "This script drops all database tables for the SeeGap application."
    echo "It will attempt to use Docker first, then fall back to direct MySQL connection."
    echo
    echo "Database Configuration:"
    echo "  Host: $DB_HOST"
    echo "  User: $DB_USER"
    echo "  Database: $DB_NAME"
    echo "  SQL File: $SQL_FILE"
}

# Parse command line arguments
case "${1:-}" in
    -h|--help)
        show_help
        exit 0
        ;;
    --show-tables)
        show_tables
        exit 0
        ;;
    --no-confirm)
        print_warning "Running in no-confirm mode - this is dangerous!"
        # Override confirmation for automated scripts
        confirmation="DROP ALL TABLES"
        ;;
    --backup)
        create_backup="y"
        ;;
esac

# Run main function
main
