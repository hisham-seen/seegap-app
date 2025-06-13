# CI/CD Setup Instructions

## Overview
This repository is now configured for automatic deployment to production using GitHub Actions.

## Files Synchronized
✅ **Local and Production are now SYNCED** with:
- Production database credentials
- HTTPS configuration (https://si.seegap.com)
- Optimized nginx configuration (theme switching fix)
- Docker compose without Redis dependency
- Security settings enabled

## GitHub Actions Setup

### 1. Repository Secrets
Add these secrets to your GitHub repository (Settings → Secrets and variables → Actions):

```
SSH_PRIVATE_KEY: Your private SSH key for the GCP VM
SSH_USER: HishamSait
SERVER_HOST: 34.140.205.52
```

### 2. SSH Key Setup
Generate an SSH key pair for CI/CD:
```bash
ssh-keygen -t rsa -b 4096 -C "github-actions@si.seegap.com"
```

Add the public key to the VM:
```bash
gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command="echo 'YOUR_PUBLIC_KEY' >> ~/.ssh/authorized_keys"
```

Add the private key to GitHub Secrets as `SSH_PRIVATE_KEY`.

### 3. Git Repository Setup
Initialize git repository on the VM:
```bash
gcloud compute ssh seegap-app-vm --zone=europe-west1-b
cd ~/seegap-app
git init
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git branch -M main
```

## Deployment Process

### Automatic Deployment
- Push to `main` branch triggers automatic deployment
- Manual deployment available via GitHub Actions "Run workflow"

### Manual Deployment
SSH to server and run:
```bash
cd ~/seegap-app
git pull origin main
./deploy.sh
```

## Production Environment

### Current Configuration
- **URL**: https://si.seegap.com
- **Database**: seegap_production_db
- **SSL**: Enabled via Cloudflare
- **Theme Switching**: Fixed (CSS no-cache)
- **Mixed Content**: Resolved

### Database Credentials
```
Host: mysql
Database: seegap_production_db
Username: seegap_prod_user_2025
Password: SeeGap#Prod$2025!MySQL@Secure
```

## Important Notes

1. **Environment Files**: `.env` is gitignored, uses `.env.example` as template
2. **Database**: Production database is clean and ready for installation
3. **SSL**: All URLs use HTTPS to prevent mixed content issues
4. **Caching**: CSS caching disabled for proper theme switching
5. **Permissions**: Automatically set during deployment

## Troubleshooting

### Theme Switching Issues
- CSS files use no-cache headers
- Application cache cleared on deployment
- HTTPS URLs prevent mixed content errors

### Database Issues
- Run `./deploy.sh` to reset permissions
- Check database credentials in `.env`
- Ensure `install/installed` file is removed for fresh installs

### SSL Issues
- Verify SITE_URL uses HTTPS in config.php
- Check Cloudflare SSL settings
- Clear application cache after URL changes
