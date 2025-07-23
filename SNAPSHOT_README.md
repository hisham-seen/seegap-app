# GCP Snapshot Management for SeeGap Application

This document provides instructions for creating and managing snapshots of your SeeGap application infrastructure in Google Cloud Platform.

## Overview

The snapshot configuration includes:
- **VM Disk Snapshot**: Creates a point-in-time snapshot of your VM's boot disk
- **Cloud SQL Backup**: Creates an on-demand backup of your MySQL database
- **Automated Snapshot Policy**: Sets up daily automated snapshots with 14-day retention

## Files Created

- `terraform/snapshots.tf` - Terraform configuration for snapshot resources
- `create-snapshots.sh` - Automated script to create snapshots
- `terraform/outputs.tf` - Updated with snapshot output information

## Quick Start

### 1. Create Snapshots Immediately

Run the automated script:

```bash
./create-snapshots.sh
```

This script will:
- Validate your environment and authentication
- Show you what will be created
- Ask for confirmation before proceeding
- Create the snapshots and display the results

### 2. Manual Terraform Commands

If you prefer to run Terraform commands manually:

```bash
cd terraform

# Initialize Terraform (if not already done)
terraform init

# Plan the snapshot creation
terraform plan -target=google_compute_snapshot.seegap_vm_snapshot \
               -target=google_sql_backup_run.seegap_db_backup \
               -target=google_compute_resource_policy.seegap_snapshot_policy \
               -target=google_compute_disk_resource_policy_attachment.seegap_snapshot_attachment

# Apply the changes
terraform apply -target=google_compute_snapshot.seegap_vm_snapshot \
                -target=google_sql_backup_run.seegap_db_backup \
                -target=google_compute_resource_policy.seegap_snapshot_policy \
                -target=google_compute_disk_resource_policy_attachment.seegap_snapshot_attachment
```

## Snapshot Details

### VM Disk Snapshot
- **Name Format**: `seegap-vm-snapshot-YYYY-MM-DD-hhmm`
- **Source**: Boot disk of the `seegap-app-vm` instance
- **Location**: `europe-west1` region
- **Labels**: Environment, application, type, and creation metadata

### Database Backup
- **Type**: On-demand backup
- **Instance**: `seegap-mysql-instance`
- **Description**: Includes timestamp of creation

### Automated Snapshot Policy
- **Schedule**: Daily at 04:00 UTC
- **Retention**: 14 days
- **Location**: `europe-west1` region
- **Guest Flush**: Disabled for consistency

## Viewing Snapshots

### GCP Console
- **VM Snapshots**: https://console.cloud.google.com/compute/snapshots
- **SQL Backups**: https://console.cloud.google.com/sql/instances

### Command Line
```bash
# List VM snapshots
gcloud compute snapshots list --filter="name:seegap-vm-snapshot"

# List SQL backups
gcloud sql backups list --instance=seegap-mysql-instance
```

### Terraform Outputs
```bash
cd terraform

# Get snapshot information
terraform output vm_snapshot_name
terraform output vm_snapshot_id
terraform output db_backup_id
terraform output snapshot_policy_name
```

## Restoring from Snapshots

### Restore VM from Snapshot
```bash
# Create a new disk from snapshot
gcloud compute disks create seegap-restored-disk \
    --source-snapshot=SNAPSHOT_NAME \
    --zone=europe-west1-b

# Create a new VM instance with the restored disk
gcloud compute instances create seegap-restored-vm \
    --zone=europe-west1-b \
    --machine-type=e2-medium \
    --disk=name=seegap-restored-disk,boot=yes,auto-delete=yes
```

### Restore Database from Backup
```bash
# Restore to the same instance (creates a new database)
gcloud sql backups restore BACKUP_ID \
    --restore-instance=seegap-mysql-instance \
    --backup-instance=seegap-mysql-instance

# Or restore to a new instance
gcloud sql instances clone seegap-mysql-instance seegap-mysql-restored \
    --backup-id=BACKUP_ID
```

## Managing Snapshots

### Delete Old Snapshots
```bash
# List snapshots older than 30 days
gcloud compute snapshots list \
    --filter="name:seegap-vm-snapshot AND creationTimestamp<-P30D" \
    --format="value(name)"

# Delete specific snapshot
gcloud compute snapshots delete SNAPSHOT_NAME
```

### Modify Snapshot Policy
Edit `terraform/snapshots.tf` and update the policy configuration:
- Change retention period
- Modify schedule
- Update storage locations

Then apply changes:
```bash
cd terraform
terraform apply -target=google_compute_resource_policy.seegap_snapshot_policy
```

## Cost Considerations

- **VM Snapshots**: Charged for incremental storage used
- **SQL Backups**: First 100GB free per instance, then charged per GB
- **Automated Snapshots**: Additional storage costs for retained snapshots

Monitor costs in the [GCP Billing Console](https://console.cloud.google.com/billing).

## Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   # Ensure you have the required roles
   gcloud projects add-iam-policy-binding PROJECT_ID \
       --member="user:YOUR_EMAIL" \
       --role="roles/compute.storageAdmin"
   ```

2. **Terraform State Issues**
   ```bash
   # Refresh Terraform state
   terraform refresh
   
   # Import existing resources if needed
   terraform import google_compute_snapshot.seegap_vm_snapshot SNAPSHOT_NAME
   ```

3. **Snapshot Creation Fails**
   - Check VM is running and accessible
   - Verify sufficient permissions
   - Ensure disk is not in use by critical operations

### Support

For issues with:
- **Terraform**: Check the [Terraform Google Provider documentation](https://registry.terraform.io/providers/hashicorp/google/latest/docs)
- **GCP**: Consult the [Google Cloud documentation](https://cloud.google.com/docs)
- **Application**: Review the main project README.md

## Security Notes

- Snapshots contain all data from the source disk/database
- Ensure proper access controls are in place
- Consider encryption for sensitive data
- Regularly audit snapshot access and retention policies

---

**Last Updated**: January 2025
**Version**: 1.0
