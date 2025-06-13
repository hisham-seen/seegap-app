# GCP Configuration Variables
variable "gcp_project_id" {
  description = "The GCP project ID"
  type        = string
  default     = "eminent-subset-462023-f9"
}

variable "gcp_region" {
  description = "The GCP region"
  type        = string
  default     = "europe-west1"
}

variable "gcp_zone" {
  description = "The GCP zone"
  type        = string
  default     = "europe-west1-b"
}

variable "machine_type" {
  description = "The machine type for the VM instance"
  type        = string
  default     = "e2-medium"
}

# SSH Configuration
variable "ssh_username" {
  description = "SSH username for the VM"
  type        = string
  default     = "HishamSait"
}

variable "ssh_public_key" {
  description = "SSH public key for VM access"
  type        = string
}

# Domain Configuration
variable "domain_name" {
  description = "The main domain name"
  type        = string
  default     = "seegap.com"
}

variable "subdomain" {
  description = "The subdomain for the application"
  type        = string
  default     = "si"
}

# Cloudflare Configuration
variable "cloudflare_api_token" {
  description = "Cloudflare API token"
  type        = string
  sensitive   = true
}

# Application Configuration
variable "app_environment" {
  description = "Application environment (production, staging, development)"
  type        = string
  default     = "production"
}

variable "db_password" {
  description = "Database password"
  type        = string
  sensitive   = true
  default     = "SeeGap#Prod$2025!MySQL@Secure"
}

variable "db_root_password" {
  description = "Database root password"
  type        = string
  sensitive   = true
  default     = "Root#MySQL$2025!SuperSecure@GCP"
}
