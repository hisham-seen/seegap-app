# Configure the Google Cloud Provider
terraform {
  required_version = ">= 1.0"
  required_providers {
    google = {
      source  = "hashicorp/google"
      version = "~> 5.0"
    }
    cloudflare = {
      source  = "cloudflare/cloudflare"
      version = "~> 4.0"
    }
  }
}

# Configure the Google Cloud Provider
provider "google" {
  project = var.gcp_project_id
  region  = var.gcp_region
  zone    = var.gcp_zone
}

# Configure Cloudflare Provider
provider "cloudflare" {
  api_token = var.cloudflare_api_token
}

# Create a static IP address
resource "google_compute_address" "seegap_static_ip" {
  name   = "seegap-app-static-ip"
  region = var.gcp_region
}

# Create firewall rule for HTTP/HTTPS traffic
resource "google_compute_firewall" "seegap_firewall" {
  name    = "seegap-app-firewall"
  network = "default"

  allow {
    protocol = "tcp"
    ports    = ["22", "80", "443"]
  }

  source_ranges = ["0.0.0.0/0"]
  target_tags   = ["seegap-app"]
}

# Create the VM instance
resource "google_compute_instance" "seegap_vm" {
  name         = "seegap-app-vm"
  machine_type = var.machine_type
  zone         = var.gcp_zone
  tags         = ["seegap-app"]

  boot_disk {
    initialize_params {
      image = "ubuntu-os-cloud/ubuntu-2204-lts"
      size  = 20
      type  = "pd-standard"
    }
  }

  network_interface {
    network = "default"
    access_config {
      nat_ip = google_compute_address.seegap_static_ip.address
    }
  }

  metadata = {
    ssh-keys = "${var.ssh_username}:${var.ssh_public_key}"
  }

  metadata_startup_script = file("${path.module}/startup-script.sh")

  service_account {
    email  = google_service_account.seegap_service_account.email
    scopes = ["cloud-platform"]
  }

  depends_on = [
    google_service_account.seegap_service_account
  ]
}

# Create service account for the VM
resource "google_service_account" "seegap_service_account" {
  account_id   = "seegap-app-service-account"
  display_name = "SeeGap App Service Account"
  description  = "Service account for SeeGap application VM"
}

# Grant necessary permissions to the service account
resource "google_project_iam_member" "seegap_service_account_roles" {
  for_each = toset([
    "roles/logging.logWriter",
    "roles/monitoring.metricWriter",
    "roles/storage.objectViewer"
  ])

  project = var.gcp_project_id
  role    = each.value
  member  = "serviceAccount:${google_service_account.seegap_service_account.email}"
}

# Get Cloudflare zone information
data "cloudflare_zone" "seegap_zone" {
  name = var.domain_name
}

# Create DNS A record pointing to the static IP
resource "cloudflare_record" "seegap_a_record" {
  zone_id = data.cloudflare_zone.seegap_zone.id
  name    = var.subdomain
  content = google_compute_address.seegap_static_ip.address
  type    = "A"
  ttl     = 1
  proxied = true
}

# Create CNAME record for www subdomain
resource "cloudflare_record" "seegap_www_cname" {
  zone_id = data.cloudflare_zone.seegap_zone.id
  name    = "www.${var.subdomain}"
  content = "${var.subdomain}.${var.domain_name}"
  type    = "CNAME"
  ttl     = 1
  proxied = true
}

# Configure Cloudflare SSL settings
resource "cloudflare_zone_settings_override" "seegap_ssl_settings" {
  zone_id = data.cloudflare_zone.seegap_zone.id
  settings {
    ssl                      = "full"
    always_use_https         = "on"
    min_tls_version          = "1.2"
    opportunistic_encryption = "on"
    tls_1_3                  = "zrt"
    automatic_https_rewrites = "on"
    universal_ssl            = "on"
  }
}

# Configure Cloudflare page rules for caching
resource "cloudflare_page_rule" "seegap_cache_rule" {
  zone_id  = data.cloudflare_zone.seegap_zone.id
  target   = "${var.subdomain}.${var.domain_name}/themes/*"
  priority = 1

  actions {
    cache_level = "cache_everything"
    edge_cache_ttl = 86400
  }
}

# Configure Cloudflare security settings
resource "cloudflare_zone_settings_override" "seegap_security_settings" {
  zone_id = data.cloudflare_zone.seegap_zone.id
  settings {
    security_level     = "medium"
    challenge_ttl      = 1800
    hotlink_protection = "on"
    ip_geolocation     = "on"
  }
}
