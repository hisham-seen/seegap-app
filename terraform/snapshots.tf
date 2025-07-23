# Create a snapshot of the VM boot disk
resource "google_compute_snapshot" "seegap_vm_snapshot" {
  name        = "seegap-vm-snapshot-${formatdate("YYYY-MM-DD-hhmm", timestamp())}"
  source_disk = google_compute_instance.seegap_vm.boot_disk[0].source
  zone        = var.gcp_zone
  
  description = "Snapshot of SeeGap VM boot disk created on ${formatdate("YYYY-MM-DD HH:mm", timestamp())}"
  
  labels = {
    environment = var.app_environment
    application = "seegap"
    type        = "vm-snapshot"
    created_by  = "terraform"
  }

  # Snapshot retention policy (optional)
  snapshot_encryption_key {
    raw_key = null
  }

  storage_locations = [var.gcp_region]
}

# Note: Cloud SQL backups are automatically created based on the backup_configuration
# in the main.tf file. Manual backups need to be created via gcloud CLI or Console.
# The backup configuration in main.tf already enables daily backups at 03:00 with 7-day retention.

# Create a null resource to trigger manual backup via gcloud CLI
resource "null_resource" "seegap_db_backup" {
  triggers = {
    timestamp = timestamp()
  }
  
  provisioner "local-exec" {
    command = <<-EOT
      echo "Creating manual database backup..."
      gcloud sql backups create \
        --instance=${google_sql_database_instance.seegap_mysql.name} \
        --description="Manual backup created via Terraform on $(date)" \
        --project=${var.gcp_project_id} || echo "Backup creation failed or already exists"
    EOT
  }
  
  depends_on = [
    google_sql_database_instance.seegap_mysql,
    google_sql_database.seegap_database
  ]
}

# Optional: Create a scheduled snapshot policy for regular backups
resource "google_compute_resource_policy" "seegap_snapshot_policy" {
  name   = "seegap-snapshot-policy"
  region = var.gcp_region
  
  description = "Automated snapshot policy for SeeGap VM"
  
  snapshot_schedule_policy {
    schedule {
      daily_schedule {
        days_in_cycle = 1
        start_time    = "04:00"
      }
    }
    
    retention_policy {
      max_retention_days    = 14
      on_source_disk_delete = "KEEP_AUTO_SNAPSHOTS"
    }
    
    snapshot_properties {
      labels = {
        environment = var.app_environment
        application = "seegap"
        type        = "automated-snapshot"
        created_by  = "policy"
      }
      storage_locations = [var.gcp_region]
      guest_flush       = false
    }
  }
}

# Attach the snapshot policy to the VM disk
resource "google_compute_disk_resource_policy_attachment" "seegap_snapshot_attachment" {
  name = google_compute_resource_policy.seegap_snapshot_policy.name
  disk = google_compute_instance.seegap_vm.name
  zone = var.gcp_zone
  
  depends_on = [
    google_compute_instance.seegap_vm,
    google_compute_resource_policy.seegap_snapshot_policy
  ]
}
