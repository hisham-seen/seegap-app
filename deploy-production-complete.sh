#!/bin/bash

# SeeGap Application - Complete Production Deployment Script
# This script deploys the SeeGap application to GCP with SSL and domain configuration
# Based on successful deployment experience

set -e

echo "🚀 Starting SeeGap Production Deployment..."

# Configuration
GCP_PROJECT_ID="eminent-subset-462023-f9"
GCP_VM_NAME="seegap-app-vm"
GCP_ZONE="europe-west1-b"
DOMAIN="si.seegap.com"
EMAIL="hisham@seegap.com"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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

# Step 1: Deploy Infrastructure with Terraform
print_status "Deploying infrastructure with Terraform..."
cd terraform
terraform init
terraform plan
terraform apply -auto-approve
STATIC_IP=$(terraform output -raw static_ip_address)
print_success "Infrastructure deployed. Static IP: $STATIC_IP"
cd ..

# Step 2: Create deployment package
print_status "Creating deployment package..."
mkdir -p /tmp/deploy
rsync -av --exclude='.git' --exclude='.github' --exclude='node_modules' --exclude='*.log' --exclude='uploads/logs/*' --exclude='terraform' --exclude='backup_*' --exclude='test_*' --exclude='seegap-app.tar.gz' --exclude='vendor' . /tmp/deploy/
cd /tmp/deploy
tar -czf /tmp/seegap-app.tar.gz .
mv /tmp/seegap-app.tar.gz .
print_success "Deployment package created: $(ls -lh seegap-app.tar.gz)"

# Step 3: Upload application files
print_status "Uploading application files to GCP VM..."
gcloud compute scp seegap-app.tar.gz $GCP_VM_NAME:~/ --zone=$GCP_ZONE --project=$GCP_PROJECT_ID

# Step 4: Create and upload deployment script
print_status "Creating VM deployment script..."
cat > /tmp/vm-deploy.sh << 'EOF'
#!/bin/bash
set -e

echo "🔧 Starting VM deployment process..."

# Get the actual username
VM_USER=$(whoami)
echo "VM User: $VM_USER"

# Create backup of current deployment if it exists
if [ -d "/var/www/seegap" ] && [ "$(ls -A /var/www/seegap 2>/dev/null)" ]; then
  echo "📋 Creating backup of current deployment..."
  sudo mkdir -p /var/backups/seegap
  sudo tar -czf /var/backups/seegap/backup-$(date +%Y%m%d-%H%M%S).tar.gz -C /var/www/seegap . || true
fi

echo "📂 Extracting new deployment..."
mkdir -p /tmp/seegap-deploy
cd /tmp/seegap-deploy
tar -xzf ~/seegap-app.tar.gz

echo "⚙️ Setting up production configuration..."
cp config.production.php config.php

echo "💾 Backing up uploads and user data..."
sudo mkdir -p /var/www/seegap
if [ -d "/var/www/seegap/uploads" ]; then
  sudo cp -r /var/www/seegap/uploads /tmp/uploads_backup
  echo "✅ Uploads folder backed up"
fi

echo "🚀 Deploying application files (preserving user data)..."
sudo rsync -av --delete --exclude='uploads' /tmp/seegap-deploy/ /var/www/seegap/

echo "🔄 Restoring uploads and user data..."
if [ -d "/tmp/uploads_backup" ]; then
  sudo rm -rf /var/www/seegap/uploads 2>/dev/null || true
  sudo mv /tmp/uploads_backup /var/www/seegap/uploads
  echo "✅ Uploads folder restored"
else
  sudo mkdir -p /var/www/seegap/uploads
  echo "✅ Uploads folder created"
fi

echo "🔧 Setting proper file permissions..."
sudo chown -R $VM_USER:$VM_USER /var/www/seegap
sudo find /var/www/seegap -type d -exec chmod 755 {} \;
sudo find /var/www/seegap -type f -exec chmod 644 {} \;
sudo chmod -R 777 /var/www/seegap/uploads/

echo "🐳 Setting up Docker environment..."
cd /var/www/seegap

# Install Docker Compose if not present
if ! command -v docker-compose &> /dev/null; then
    echo "📦 Installing Docker Compose..."
    sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
fi

# Install MySQL client if not present
if ! command -v mysql &> /dev/null; then
    echo "🗄️ Installing MySQL client..."
    sudo apt-get update
    sudo apt-get install -y default-mysql-client
fi

# Stop any existing containers
echo "🛑 Stopping existing containers..."
docker-compose down 2>/dev/null || true

# Build and start containers
echo "🐳 Building and starting Docker containers..."
docker-compose build
docker-compose up -d

# Wait for containers to be ready
echo "⏳ Waiting for containers to be ready..."
sleep 30

# Check container status
echo "📊 Container status:"
docker-compose ps

echo "🔐 Setting up SSL certificates..."
sudo mkdir -p /var/www/seegap/ssl

# Install Certbot if not present
if ! command -v certbot &> /dev/null; then
    echo "📦 Installing Certbot..."
    sudo apt-get install -y certbot
fi

# Check if certificates already exist
if [ ! -f "/etc/letsencrypt/live/si.seegap.com/fullchain.pem" ]; then
  echo "🔐 Generating new SSL certificates..."
  # Stop nginx container temporarily for certificate generation
  docker-compose stop app-nginx
  
  # Get SSL certificate
  sudo certbot certonly --standalone -d si.seegap.com -d www.si.seegap.com --non-interactive --agree-tos --email hisham@seegap.com
  
  # Restart containers with SSL
  docker-compose down
  docker-compose up -d
else
  echo "✅ SSL certificates already exist"
  docker-compose restart
fi

# Always ensure certificates are copied to application ssl directory
echo "🔐 Ensuring SSL certificates are available in application directory..."
if [ -f "/etc/letsencrypt/live/si.seegap.com/fullchain.pem" ]; then
  sudo cp /etc/letsencrypt/live/si.seegap.com/fullchain.pem /var/www/seegap/ssl/
  sudo cp /etc/letsencrypt/live/si.seegap.com/privkey.pem /var/www/seegap/ssl/
  sudo chown -R $VM_USER:$VM_USER /var/www/seegap/ssl
  echo "✅ SSL certificates copied to application directory"
  
  # Restart nginx to pick up certificates
  docker-compose restart app-nginx
  echo "✅ Nginx restarted with SSL certificates"
else
  echo "❌ SSL certificates not found, please check Let's Encrypt setup"
fi

# Wait for services to be fully ready
sleep 15

echo "🧹 Cleaning up temporary files..."
rm -rf /tmp/seegap-deploy
rm -f ~/seegap-app.tar.gz

echo ""
echo "✅ VM deployment completed successfully!"
echo ""
echo "📊 Final Status:"
docker-compose ps
echo ""
echo "🔗 Application should be accessible at: https://si.seegap.com"
EOF

# Upload and execute the VM deployment script
print_status "Uploading and executing deployment script on VM..."
gcloud compute scp /tmp/vm-deploy.sh $GCP_VM_NAME:/tmp/ --zone=$GCP_ZONE --project=$GCP_PROJECT_ID
gcloud compute ssh $GCP_VM_NAME --zone=$GCP_ZONE --project=$GCP_PROJECT_ID --command="chmod +x /tmp/vm-deploy.sh && /tmp/vm-deploy.sh"

# Step 5: Verify deployment
print_status "Verifying deployment..."
sleep 10

# Test HTTP redirect
print_status "Testing HTTP to HTTPS redirect..."
HTTP_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://$DOMAIN || echo "000")
if [ "$HTTP_RESPONSE" = "301" ]; then
    print_success "HTTP to HTTPS redirect working"
else
    print_warning "HTTP redirect returned: $HTTP_RESPONSE"
fi

# Test HTTPS
print_status "Testing HTTPS connection..."
HTTPS_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" https://$DOMAIN || echo "000")
if [ "$HTTPS_RESPONSE" = "500" ]; then
    print_success "HTTPS working (500 expected for install page)"
elif [ "$HTTPS_RESPONSE" = "200" ]; then
    print_success "HTTPS working (200 - application ready)"
else
    print_warning "HTTPS returned: $HTTPS_RESPONSE"
fi

# Test install page
print_status "Testing install page..."
INSTALL_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" https://$DOMAIN/install/ || echo "000")
if [ "$INSTALL_RESPONSE" = "200" ]; then
    print_success "Install page accessible"
else
    print_warning "Install page returned: $INSTALL_RESPONSE"
fi

# Cleanup
print_status "Cleaning up temporary files..."
rm -rf /tmp/deploy
rm -f /tmp/vm-deploy.sh

echo ""
echo "🎉 ===== DEPLOYMENT COMPLETED SUCCESSFULLY ===== 🎉"
echo ""
echo "📊 Deployment Summary:"
echo "   - Domain: https://$DOMAIN"
echo "   - Static IP: $STATIC_IP"
echo "   - VM: $GCP_VM_NAME"
echo "   - Zone: $GCP_ZONE"
echo "   - SSL: Let's Encrypt certificates"
echo "   - Database: External Cloud SQL"
echo ""
echo "🔗 Next Steps:"
echo "   1. Access the application: https://$DOMAIN"
echo "   2. Complete installation: https://$DOMAIN/install/"
echo "   3. Update Cloudflare DNS if needed: $STATIC_IP"
echo ""
echo "✅ Deployment script completed!"
