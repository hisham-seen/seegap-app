# SeeGap Application - Complete Deployment Setup Guide

This guide will help you set up a complete CI/CD pipeline for your SeeGap application using Terraform, GitHub Actions, and Cloudflare.

## 🏗️ Architecture Overview

```
GitHub Repository → GitHub Actions → Terraform → GCP VM → Cloudflare → Users
                                      ↓
                                  Docker Containers
                                  (Nginx + PHP + MySQL + Redis)
```

## 📋 Prerequisites

### 1. GCP Setup
- ✅ Google Cloud Project: `eminent-subset-462023-f9`
- ✅ Billing enabled
- ✅ Compute Engine API enabled
- ✅ Service Account created with necessary permissions

### 2. GitHub Repository
- Repository created for your SeeGap application
- Repository secrets configured (see below)

### 3. Cloudflare Account
- ✅ Domain: `seegap.com`
- ✅ API token: `jUQEU-cZVzJ5-wep9xsfISgtvbqhi9ySHYQOAtAW`

### 4. SSH Key Pair
- SSH key pair generated for VM access

## 🔐 Required GitHub Secrets

Add these secrets to your GitHub repository (Settings → Secrets and variables → Actions):

### GCP Secrets
```
GCP_PROJECT_ID: eminent-subset-462023-f9
GCP_SERVICE_ACCOUNT_KEY: {"type": "service_account", "project_id": "eminent-subset-462023-f9", ...}
```

### SSH Secrets
```
SSH_PRIVATE_KEY: -----BEGIN OPENSSH PRIVATE KEY-----...
SSH_PUBLIC_KEY: ssh-rsa AAAAB3NzaC1yc2EAAAA...
SERVER_HOST: (will be updated by Terraform automatically)
```

### Cloudflare Secrets
```
CLOUDFLARE_API_TOKEN: jUQEU-cZVzJ5-wep9xsfISgtvbqhi9ySHYQOAtAW
```

### Database Secrets
```
DB_PASSWORD: SeeGap#Prod$2025!MySQL@Secure
DB_ROOT_PASSWORD: Root#MySQL$2025!SuperSecure@GCP
```

## 🚀 Step-by-Step Setup

### Step 1: Create GCP Service Account

```bash
# Set your project ID
export PROJECT_ID="eminent-subset-462023-f9"

# Authenticate with GCP (if not already done)
gcloud auth login
gcloud config set project $PROJECT_ID

# Create service account
gcloud iam service-accounts create seegap-terraform \
    --display-name="SeeGap Terraform Service Account" \
    --project=$PROJECT_ID

# Grant necessary roles
gcloud projects add-iam-policy-binding $PROJECT_ID \
    --member="serviceAccount:seegap-terraform@$PROJECT_ID.iam.gserviceaccount.com" \
    --role="roles/compute.admin"

gcloud projects add-iam-policy-binding $PROJECT_ID \
    --member="serviceAccount:seegap-terraform@$PROJECT_ID.iam.gserviceaccount.com" \
    --role="roles/iam.serviceAccountUser"

gcloud projects add-iam-policy-binding $PROJECT_ID \
    --member="serviceAccount:seegap-terraform@$PROJECT_ID.iam.gserviceaccount.com" \
    --role="roles/compute.networkAdmin"

# Create and download service account key
gcloud iam service-accounts keys create seegap-terraform-key.json \
    --iam-account=seegap-terraform@$PROJECT_ID.iam.gserviceaccount.com

# Display the service account key (copy this to GitHub secrets as GCP_SERVICE_ACCOUNT_KEY)
cat seegap-terraform-key.json
```

### Step 2: Generate SSH Key Pair

```bash
# Generate SSH key pair
ssh-keygen -t rsa -b 4096 -C "seegap-deployment@github.com" -f ~/.ssh/seegap-deployment

# Display public key (copy this to GitHub secrets as SSH_PUBLIC_KEY)
cat ~/.ssh/seegap-deployment.pub

# Display private key (copy this to GitHub secrets as SSH_PRIVATE_KEY)
cat ~/.ssh/seegap-deployment
```

### Step 3: Enable Required GCP APIs

```bash
# Enable required APIs
gcloud services enable compute.googleapis.com
gcloud services enable cloudresourcemanager.googleapis.com
gcloud services enable iam.googleapis.com
```

### Step 4: Configure GitHub Repository

1. Create a new GitHub repository for your SeeGap application
2. Push your current code to the repository:

```bash
# Initialize git repository (if not already done)
git init
git add .
git commit -m "Initial commit: SeeGap application with Terraform deployment"

# Add remote repository (replace with your actual repository URL)
git remote add origin https://github.com/YOUR_USERNAME/seegap-app.git
git branch -M main
git push -u origin main
```

### Step 5: Add GitHub Secrets

1. Go to your GitHub repository
2. Navigate to Settings → Secrets and variables → Actions
3. Add the following secrets:

| Secret Name | Value |
|-------------|-------|
| `GCP_PROJECT_ID` | `eminent-subset-462023-f9` |
| `GCP_SERVICE_ACCOUNT_KEY` | Contents of `seegap-terraform-key.json` |
| `SSH_PRIVATE_KEY` | Contents of `~/.ssh/seegap-deployment` |
| `SSH_PUBLIC_KEY` | Contents of `~/.ssh/seegap-deployment.pub` |
| `CLOUDFLARE_API_TOKEN` | `jUQEU-cZVzJ5-wep9xsfISgtvbqhi9ySHYQOAtAW` |
| `DB_PASSWORD` | `SeeGap#Prod$2025!MySQL@Secure` |
| `DB_ROOT_PASSWORD` | `Root#MySQL$2025!SuperSecure@GCP` |

## 🔄 Deployment Process

### Automatic Deployment (Push to main)
1. Push code to `main` branch
2. GitHub Actions automatically:
   - Deploys infrastructure with Terraform
   - Deploys application code
   - Configures environment
   - Starts Docker services
   - Verifies deployment

### Manual Deployment
1. Go to GitHub Actions tab in your repository
2. Select "Deploy SeeGap Application" workflow
3. Click "Run workflow"
4. Choose options:
   - Environment: `production`
   - Terraform action: `apply`

### Infrastructure Management
- **Plan only**: Choose `plan` to see what changes will be made
- **Deploy**: Choose `apply` to deploy infrastructure and application
- **Destroy**: Choose `destroy` to tear down infrastructure (use with caution!)

## 🌐 Domain Configuration

Your application will be available at:
- **Primary URL**: https://si.seegap.com
- **WWW URL**: https://www.si.seegap.com

Cloudflare will automatically:
- Configure DNS records
- Enable SSL/TLS encryption
- Set up caching rules
- Enable security features

## 🐳 Docker Services

Your application runs with these services:
- **Nginx**: Web server and reverse proxy
- **PHP-FPM**: PHP application server
- **MySQL**: Database server
- **Redis**: Caching and session storage

## 📊 Monitoring and Maintenance

### Health Checks
The deployment includes automatic health checks:
- Application accessibility
- SSL certificate validation
- Service status verification

### Logs
Access logs via SSH:
```bash
# Connect to VM
gcloud compute ssh seegap-app-vm --zone=europe-west1-b

# View application logs
cd ~/seegap-app
docker-compose logs -f

# View specific service logs
docker-compose logs -f nginx
docker-compose logs -f php
docker-compose logs -f mysql
```

### Backup Strategy
- Database backups are handled by the application
- File uploads are stored in persistent Docker volumes
- Infrastructure is defined as code (can be recreated)

## 🔧 Troubleshooting

### Common Issues

1. **Deployment fails with SSH connection error**
   - Check SSH keys are correctly added to GitHub secrets
   - Verify VM is running and accessible

2. **Terraform fails to create resources**
   - Check GCP service account permissions
   - Verify project ID and billing are enabled

3. **Application not accessible**
   - Check Cloudflare DNS settings
   - Verify Docker services are running
   - Check firewall rules

4. **SSL certificate issues**
   - Cloudflare handles SSL automatically
   - Check domain is properly configured in Cloudflare

### Manual Recovery

If automatic deployment fails, you can manually deploy:

```bash
# Connect to VM
gcloud compute ssh seegap-app-vm --zone=europe-west1-b

# Navigate to application directory
cd ~/seegap-app

# Pull latest code
git pull origin main

# Run deployment script
./deploy.sh

# Check service status
docker-compose ps
```

## 🎯 Next Steps

1. **Set up monitoring**: Consider adding application monitoring
2. **Configure backups**: Set up automated database backups
3. **Add staging environment**: Create a staging environment for testing
4. **Set up alerts**: Configure alerts for downtime or errors

## 📞 Support

For issues with this deployment setup:
1. Check the GitHub Actions logs for detailed error messages
2. Review the Terraform outputs for infrastructure details
3. Check Docker logs for application-specific issues

---

**🎉 Congratulations!** Your SeeGap application is now set up with a complete CI/CD pipeline using modern DevOps practices.
