# Output the static IP address
output "static_ip_address" {
  description = "The static IP address of the VM instance"
  value       = google_compute_address.seegap_static_ip.address
}

# Output the VM instance details
output "vm_instance_name" {
  description = "The name of the VM instance"
  value       = google_compute_instance.seegap_vm.name
}

output "vm_instance_zone" {
  description = "The zone of the VM instance"
  value       = google_compute_instance.seegap_vm.zone
}

output "vm_instance_self_link" {
  description = "The self link of the VM instance"
  value       = google_compute_instance.seegap_vm.self_link
}

# Output the service account email
output "service_account_email" {
  description = "The email of the service account"
  value       = google_service_account.seegap_service_account.email
}

# Output the domain information
output "domain_url" {
  description = "The full domain URL"
  value       = "https://${var.subdomain}.${var.domain_name}"
}

output "www_domain_url" {
  description = "The www domain URL"
  value       = "https://www.${var.subdomain}.${var.domain_name}"
}

# Output Cloudflare zone ID
output "cloudflare_zone_id" {
  description = "The Cloudflare zone ID"
  value       = data.cloudflare_zone.seegap_zone.id
}

# Output SSH connection command
output "ssh_connection_command" {
  description = "SSH command to connect to the VM"
  value       = "gcloud compute ssh ${google_compute_instance.seegap_vm.name} --zone=${google_compute_instance.seegap_vm.zone}"
}

# Output Cloud SQL information
output "cloud_sql_instance_name" {
  description = "The name of the Cloud SQL instance"
  value       = google_sql_database_instance.seegap_mysql.name
}

output "cloud_sql_public_ip" {
  description = "The public IP address of the Cloud SQL instance"
  value       = google_sql_database_instance.seegap_mysql.public_ip_address
}

output "cloud_sql_connection_name" {
  description = "The connection name of the Cloud SQL instance"
  value       = google_sql_database_instance.seegap_mysql.connection_name
}

output "database_name" {
  description = "The name of the database"
  value       = google_sql_database.seegap_database.name
}

output "database_user" {
  description = "The database user name"
  value       = google_sql_user.seegap_user.name
  sensitive   = true
}

# Output deployment information
output "deployment_info" {
  description = "Deployment information"
  value = {
    vm_name          = google_compute_instance.seegap_vm.name
    static_ip        = google_compute_address.seegap_static_ip.address
    domain           = "${var.subdomain}.${var.domain_name}"
    environment      = var.app_environment
    region           = var.gcp_region
    zone             = var.gcp_zone
    database_host    = google_sql_database_instance.seegap_mysql.public_ip_address
    database_name    = google_sql_database.seegap_database.name
  }
}
