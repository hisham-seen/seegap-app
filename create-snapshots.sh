#!/bin/bash

# SeeGap GCP Snapshot Creation Script
# This script creates snapshots of the VM disk and database backup

set -e

echo "🔄 Creating GCP Snapshots for SeeGap Application..."
echo "=================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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

# Check if we're in the correct directory
if [ ! -f "terraform/snapshots.tf" ]; then
    print_error "snapshots.tf not found. Please run this script from the project root directory."
    exit 1
fi

# Check if Terraform is installed
if ! command -v terraform &> /dev/null; then
    print_error "Terraform is not installed. Please install Terraform first."
    exit 1
fi

# Check if gcloud is installed and authenticated
if ! command -v gcloud &> /dev/null; then
    print_error "gcloud CLI is not installed. Please install Google Cloud SDK first."
    exit 1
fi

# Check if user is authenticated with gcloud
if ! gcloud auth list --filter=status:ACTIVE --format="value(account)" | grep -q .; then
    print_error "You are not authenticated with gcloud. Please run 'gcloud auth login' first."
    exit 1
fi

print_status "Checking current GCP project..."
CURRENT_PROJECT=$(gcloud config get-value project 2>/dev/null)
if [ -z "$CURRENT_PROJECT" ]; then
    print_error "No GCP project is set. Please run 'gcloud config set project YOUR_PROJECT_ID' first."
    exit 1
fi

print_success "Current GCP project: $CURRENT_PROJECT"

# Navigate to terraform directory
cd terraform

print_status "Initializing Terraform..."
terraform init

print_status "Validating Terraform configuration..."
terraform validate

if [ $? -ne 0 ]; then
    print_error "Terraform validation failed. Please check your configuration."
    exit 1
fi

print_success "Terraform configuration is valid."

print_status "Planning Terraform changes..."
terraform plan -target=google_compute_snapshot.seegap_vm_snapshot \
               -target=null_resource.seegap_db_backup \
               -target=google_compute_resource_policy.seegap_snapshot_policy \
               -target=google_compute_disk_resource_policy_attachment.seegap_snapshot_attachment

echo ""
print_warning "This will create the following snapshots:"
echo "  • VM disk snapshot with timestamp"
echo "  • Cloud SQL database backup"
echo "  • Automated daily snapshot policy (optional)"
echo ""

read -p "Do you want to proceed with creating the snapshots? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    print_warning "Snapshot creation cancelled."
    exit 0
fi

print_status "Creating snapshots..."
terraform apply -target=google_compute_snapshot.seegap_vm_snapshot \
                -target=null_resource.seegap_db_backup \
                -target=google_compute_resource_policy.seegap_snapshot_policy \
                -target=google_compute_disk_resource_policy_attachment.seegap_snapshot_attachment \
                -auto-approve

if [ $? -eq 0 ]; then
    print_success "Snapshots created successfully!"
    echo ""
    print_status "Getting snapshot information..."
    
    # Get snapshot details
    VM_SNAPSHOT_NAME=$(terraform output -raw vm_snapshot_name 2>/dev/null || echo "N/A")
    DB_BACKUP_STATUS=$(terraform output -raw db_backup_status 2>/dev/null || echo "N/A")
    SNAPSHOT_POLICY=$(terraform output -raw snapshot_policy_name 2>/dev/null || echo "N/A")
    
    echo ""
    echo "📊 Snapshot Summary:"
    echo "==================="
    echo "VM Snapshot Name: $VM_SNAPSHOT_NAME"
    echo "DB Backup Status: $DB_BACKUP_STATUS"
    echo "Snapshot Policy: $SNAPSHOT_POLICY"
    echo ""
    
    print_status "You can view your snapshots in the GCP Console:"
    echo "• VM Snapshots: https://console.cloud.google.com/compute/snapshots"
    echo "• SQL Backups: https://console.cloud.google.com/sql/instances"
    echo ""
    
    print_success "Snapshot creation completed successfully! 🎉"
else
    print_error "Failed to create snapshots. Please check the error messages above."
    exit 1
fi
