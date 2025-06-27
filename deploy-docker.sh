#!/bin/bash

# SeeGap Application Docker Deployment Script
# This script deploys the SeeGap application to GCP using Docker

set -e

echo "🚀 SeeGap Application Docker Deployment"
echo "======================================="

# Check if we're in the right directory
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Error: Please run this script from the project root directory"
    exit 1
fi

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check required tools
echo "🔍 Checking required tools..."
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

# Check if VM exists
echo "🔍 Checking if VM exists..."
if ! gcloud compute instances describe seegap-app-vm --zone=europe-west1-b --quiet >/dev/null 2>&1; then
    echo "❌ VM 'seegap-app-vm' does not exist. Please run Terraform first to create the infrastructure."
    exit 1
fi

echo "✅ VM exists"

# Wait for VM to be ready
echo "⏳ Checking if VM is ready..."
for i in {1..10}; do
    if gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command="echo 'VM is ready'" --quiet 2>/dev/null; then
        echo "✅ VM is accessible"
        break
    else
        echo "⏳ Attempt $i: VM not ready yet, waiting 30 seconds..."
        sleep 30
    fi
    
    if [ $i -eq 10 ]; then
        echo "❌ VM is not accessible after 5 minutes. Please check the VM status."
        exit 1
    fi
done

# Check if Docker is installed and running
echo "🐳 Checking Docker installation..."
gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command="
    if ! command -v docker >/dev/null 2>&1; then
        echo '❌ Docker is not installed on the VM'
        exit 1
    fi
    
    if ! sudo systemctl is-active docker >/dev/null 2>&1; then
        echo '❌ Docker is not running on the VM'
        exit 1
    fi
    
    echo '✅ Docker is installed and running'
"

# Create deployment package
echo "📦 Creating deployment package..."
mkdir -p /tmp/seegap-docker-deploy

# Copy application files (excluding unnecessary files)
rsync -av \
    --exclude='.git' \
    --exclude='.github' \
    --exclude='node_modules' \
    --exclude='*.log' \
    --exclude='uploads/logs/*' \
    --exclude='terraform' \
    --exclude='backup_*' \
    --exclude='test_*' \
    --exclude='*.tar.gz' \
    --exclude='nginx-seegap-fixed.conf' \
    --exclude='deploy-nginx.sh' \
    . /tmp/seegap-docker-deploy/

# Create the deployment archive
cd /tmp/seegap-docker-deploy
tar -czf ../seegap-docker-app.tar.gz .
cd -

echo "✅ Deployment package created"

# Upload the application to VM
echo "🚀 Uploading application to VM..."
gcloud compute scp /tmp/seegap-docker-app.tar.gz seegap-app-vm:/tmp/ --zone=europe-west1-b

# Create deployment script for VM
cat > /tmp/docker-deploy.sh << 'EOF'
#!/bin/bash
set -e

echo "🔧 Starting Docker deployment..."

# Stop any existing containers
echo "🛑 Stopping existing containers..."
cd /var/www/seegap 2>/dev/null && docker-compose down || echo "No existing containers to stop"

# Extract application files
echo "📂 Extracting application files..."
cd /var/www/seegap

# Backup important data before deployment
echo "💾 Backing up uploads and user data..."
if [ -d "uploads" ]; then
    cp -r uploads /tmp/uploads_backup
    echo "✅ Uploads folder backed up"
fi

# Remove application files but preserve data
echo "🧹 Cleaning old application files..."
find . -maxdepth 1 -type f -not -name "*.env*" -not -name "config.php" -delete 2>/dev/null || true
find . -maxdepth 1 -type d -not -name "uploads" -not -name "." -not -name ".." -exec rm -rf {} + 2>/dev/null || true

# Extract new application files
echo "📦 Extracting new application files..."
tar -xzf /tmp/seegap-docker-app.tar.gz

# Restore backed up data
echo "🔄 Restoring uploads and user data..."
if [ -d "/tmp/uploads_backup" ]; then
    rm -rf uploads 2>/dev/null || true
    mv /tmp/uploads_backup uploads
    echo "✅ Uploads folder restored"
fi

# Set proper ownership
echo "🔧 Setting file ownership..."
sudo chown -R HishamSait:HishamSait /var/www/seegap

# Create environment file for production
echo "⚙️ Creating production environment..."
cat > .env << 'ENVEOF'
DOMAIN_NAME=si.seegap.com
DATABASE_SERVER=mysql
DATABASE_USERNAME=seegap_prod_user_2025
DATABASE_PASSWORD=SeeGapProd2025MySQLSecure
DATABASE_NAME=seegap_application_db
SITE_URL=https://si.seegap.com/
PHP_OPCACHE_ENABLE=1
PHP_APCU_ENABLE=1
ENVEOF

# Update docker-compose for production
echo "🐳 Updating Docker Compose for production..."
sed -i 's/SITE_URL=http:\/\/localhost\//SITE_URL=https:\/\/si.seegap.com\//' docker-compose.yml

# Build and start containers
echo "🚀 Building and starting Docker containers..."
docker-compose build --no-cache
docker-compose up -d

# Wait for containers to be ready
echo "⏳ Waiting for containers to be ready..."
sleep 30

# Check container status
echo "🔍 Checking container status..."
docker-compose ps

# Set up database with specific credentials
echo "🗄️ Setting up database with production credentials..."
sudo /root/setup-database.sh

# Check if application is responding
echo "🌐 Checking application response..."
for i in {1..10}; do
    if curl -f http://localhost/ >/dev/null 2>&1; then
        echo "✅ Application is responding"
        break
    else
        echo "⏳ Attempt $i: Application not ready yet, waiting 10 seconds..."
        sleep 10
    fi
    
    if [ $i -eq 10 ]; then
        echo "⚠️ Application may not be fully ready yet, but deployment completed"
    fi
done

# Clean up
rm -f /tmp/seegap-docker-app.tar.gz

echo "✅ Docker deployment completed!"
echo "🌐 Application should be available at: http://si.seegap.com"
echo "🗄️ Database credentials:"
echo "   - Database: seegap_application_db"
echo "   - Username: seegap_prod_user_2025"
echo "   - Password: SeeGapProd2025MySQLSecure"
echo "   - Site URL: https://si.seegap.com/"
echo "🔧 To check logs: docker-compose logs -f"
EOF

# Upload and execute deployment script
echo "🚀 Executing deployment on VM..."
gcloud compute scp /tmp/docker-deploy.sh seegap-app-vm:/tmp/ --zone=europe-west1-b
gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command="chmod +x /tmp/docker-deploy.sh && /tmp/docker-deploy.sh"

# Clean up local files
rm -f /tmp/seegap-docker-app.tar.gz
rm -f /tmp/docker-deploy.sh
rm -rf /tmp/seegap-docker-deploy

echo ""
echo "🎉 Docker deployment completed successfully!"
echo "=========================================="
echo "🌐 Application URL: https://si.seegap.com"
echo "🐳 Deployment Method: Docker Compose"
echo "📍 VM: seegap-app-vm (europe-west1-b)"
echo ""
echo "📋 Next steps:"
echo "1. Visit https://si.seegap.com to verify the application is working"
echo "2. Complete the application installation if needed"
echo "3. Configure any additional application settings"
echo ""
echo "🔧 Useful commands:"
echo "- SSH to VM: gcloud compute ssh seegap-app-vm --zone=europe-west1-b"
echo "- Check containers: gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command='cd /var/www/seegap && docker-compose ps'"
echo "- View logs: gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command='cd /var/www/seegap && docker-compose logs -f'"
echo "- Restart containers: gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command='cd /var/www/seegap && docker-compose restart'"
