#!/bin/bash

# SeeGap Application Deployment Script - Nginx Version
# This script deploys the SeeGap application to GCP using Nginx + PHP-FPM

set -e

echo "🚀 SeeGap Application Deployment - Nginx Version"
echo "=================================================="

# Check if we're in the right directory
if [ ! -f "terraform/main.tf" ]; then
    echo "❌ Error: Please run this script from the project root directory"
    exit 1
fi

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check required tools
echo "🔍 Checking required tools..."
if ! command_exists terraform; then
    echo "❌ Terraform is not installed. Please install Terraform first."
    exit 1
fi

if ! command_exists gcloud; then
    echo "❌ Google Cloud SDK is not installed. Please install gcloud first."
    exit 1
fi

echo "✅ All required tools are available"

# Check if user is authenticated with gcloud
echo "🔐 Checking Google Cloud authentication..."
if ! gcloud auth list --filter=status:ACTIVE --format="value(account)" | grep -q .; then
    echo "❌ You are not authenticated with Google Cloud. Please run 'gcloud auth login' first."
    exit 1
fi

echo "✅ Google Cloud authentication verified"

# Set the project
echo "🔧 Setting Google Cloud project..."
gcloud config set project eminent-subset-462023-f9

# Enable required APIs
echo "📡 Enabling required Google Cloud APIs..."
gcloud services enable compute.googleapis.com
gcloud services enable iam.googleapis.com
gcloud services enable cloudresourcemanager.googleapis.com

# Initialize Terraform
echo "🏗️ Initializing Terraform..."
cd terraform
terraform init

# Plan the deployment
echo "📋 Planning Terraform deployment..."
terraform plan -var-file="terraform.tfvars"

# Ask for confirmation
echo ""
read -p "🤔 Do you want to proceed with the deployment? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Deployment cancelled"
    exit 1
fi

# Apply Terraform configuration
echo "🚀 Applying Terraform configuration..."
terraform apply -var-file="terraform.tfvars" -auto-approve

# Get the VM IP address
VM_IP=$(terraform output -raw vm_external_ip)
echo "📍 VM External IP: $VM_IP"

# Wait for VM to be ready
echo "⏳ Waiting for VM to be ready (this may take 5-10 minutes)..."
sleep 60

# Check if VM is accessible
echo "🔍 Checking VM accessibility..."
for i in {1..20}; do
    if gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command="echo 'VM is ready'" --quiet 2>/dev/null; then
        echo "✅ VM is accessible"
        break
    else
        echo "⏳ Attempt $i: VM not ready yet, waiting 30 seconds..."
        sleep 30
    fi
    
    if [ $i -eq 20 ]; then
        echo "❌ VM is not accessible after 10 minutes. Please check the startup script logs."
        exit 1
    fi
done

# Check if services are running
echo "🔍 Checking if services are running on VM..."
gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command="
    echo '🔍 Checking service status...'
    sudo systemctl is-active nginx && echo '✅ Nginx is running' || echo '❌ Nginx is not running'
    sudo systemctl is-active php8.1-fpm && echo '✅ PHP-FPM is running' || echo '❌ PHP-FPM is not running'
    sudo systemctl is-active mysql && echo '✅ MySQL is running' || echo '❌ MySQL is not running'
"

cd ..

# Create initial deployment package
echo "📦 Creating initial deployment package..."
mkdir -p /tmp/seegap-deploy
rsync -av --exclude='.git' --exclude='.github' --exclude='node_modules' --exclude='*.log' --exclude='uploads/logs/*' --exclude='terraform' --exclude='backup_*' --exclude='test_*' --exclude='seegap-app.tar.gz' --exclude='docker*' --exclude='Dockerfile' . /tmp/seegap-deploy/

# Copy the nginx configuration
cp config.nginx.php /tmp/seegap-deploy/config.php

cd /tmp/seegap-deploy
tar -czf ../seegap-app-initial.tar.gz .
cd -

# Upload and deploy the application
echo "🚀 Uploading and deploying application..."
gcloud compute scp /tmp/seegap-app-initial.tar.gz seegap-app-vm:~/ --zone=europe-west1-b

# Create deployment script
cat > /tmp/initial-deploy.sh << 'EOF'
#!/bin/bash
set -e

echo "🔧 Starting initial deployment..."

# Extract application files
echo "📂 Extracting application files..."
mkdir -p /tmp/seegap-deploy
cd /tmp/seegap-deploy
tar -xzf ~/seegap-app-initial.tar.gz

# Deploy to web directory
echo "🚀 Deploying files to web directory..."
sudo mkdir -p /var/www/html
sudo rsync -av --delete /tmp/seegap-deploy/ /var/www/html/

# Set proper permissions
echo "🔧 Setting file permissions..."
sudo chown -R www-data:www-data /var/www/html
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo find /var/www/html -type f -exec chmod 644 {} \;
sudo chmod -R 777 /var/www/html/uploads/

# Import database schema if it exists
if [ -f "/var/www/html/setup_database.sql" ]; then
    echo "📊 Importing database schema..."
    mysql -u seegap_prod_user_2025 -p'SeeGap#Prod$2025!MySQL@Secure' seegap_production_db < /var/www/html/setup_database.sql || echo "⚠️ Database import failed or already exists"
fi

# Restart services
echo "🔄 Restarting services..."
sudo systemctl reload nginx
sudo systemctl restart php8.1-fpm

# Clean up
rm -rf /tmp/seegap-deploy
rm -f ~/seegap-app-initial.tar.gz

echo "✅ Initial deployment completed!"
EOF

# Upload and execute deployment script
gcloud compute scp /tmp/initial-deploy.sh seegap-app-vm:/tmp/ --zone=europe-west1-b
gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command="chmod +x /tmp/initial-deploy.sh && /tmp/initial-deploy.sh"

# Setup SSL certificate
echo "🔐 Setting up SSL certificate..."
gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command="
    echo '🔐 Setting up SSL certificate with Certbot...'
    sudo certbot --nginx -d si.seegap.com -d www.si.seegap.com --non-interactive --agree-tos --email hisham@seegap.com --redirect || echo '⚠️ SSL setup failed - will use Cloudflare SSL'
"

# Clean up local files
rm -f /tmp/seegap-app-initial.tar.gz
rm -f /tmp/initial-deploy.sh
rm -rf /tmp/seegap-deploy

echo ""
echo "🎉 Deployment completed successfully!"
echo "=================================="
echo "🌐 Application URL: https://si.seegap.com"
echo "📍 VM IP Address: $VM_IP"
echo "🔧 Web Server: Nginx + PHP-FPM"
echo "🗄️ Database: MySQL (localhost)"
echo ""
echo "📋 Next steps:"
echo "1. Visit https://si.seegap.com to verify the application is working"
echo "2. Configure any additional application settings"
echo "3. Set up monitoring and backups as needed"
echo "4. Use GitHub Actions for future deployments"
echo ""
echo "🔧 Useful commands:"
echo "- SSH to VM: gcloud compute ssh seegap-app-vm --zone=europe-west1-b"
echo "- Check logs: gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command='sudo tail -f /var/log/nginx/seegap_error.log'"
echo "- Restart services: gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command='sudo systemctl restart nginx php8.1-fpm'"
