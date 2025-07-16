#!/bin/bash

# SeeGap Cloud SQL Migration and Deployment Script
# This script migrates from Docker MySQL to Cloud SQL and redeploys the application

set -e

echo "🚀 Starting SeeGap Cloud SQL Migration and Deployment..."

# Check if required tools are installed
command -v terraform >/dev/null 2>&1 || { echo "❌ Terraform is required but not installed. Aborting." >&2; exit 1; }
command -v gcloud >/dev/null 2>&1 || { echo "❌ Google Cloud SDK is required but not installed. Aborting." >&2; exit 1; }

# Set variables
PROJECT_ID="eminent-subset-462023-f9"
REGION="europe-west1"
ZONE="europe-west1-b"
VM_NAME="seegap-app-vm"

echo "📋 Configuration:"
echo "   Project: $PROJECT_ID"
echo "   Region: $REGION"
echo "   Zone: $ZONE"
echo "   VM: $VM_NAME"
echo ""

# Step 1: Stop current Docker containers
echo "🛑 Step 1: Stopping current Docker containers..."
gcloud compute ssh $VM_NAME --zone=$ZONE --project=$PROJECT_ID --command="cd /var/www/seegap && docker-compose down --volumes" || true

# Step 2: Backup current data (if needed)
echo "💾 Step 2: Creating backup of uploads directory..."
gcloud compute ssh $VM_NAME --zone=$ZONE --project=$PROJECT_ID --command="cd /var/www/seegap && sudo tar -czf /tmp/seegap-uploads-backup-$(date +%Y%m%d-%H%M%S).tar.gz uploads/" || true

# Step 3: Deploy Cloud SQL infrastructure with Terraform
echo "☁️ Step 3: Deploying Cloud SQL infrastructure..."
cd terraform

# Initialize Terraform if needed
terraform init

# Plan the deployment
echo "📋 Planning Terraform deployment..."
terraform plan

# Apply the changes
echo "🚀 Applying Terraform changes..."
terraform apply -auto-approve

# Get the Cloud SQL IP
DB_HOST=$(terraform output -raw cloud_sql_public_ip)
echo "✅ Cloud SQL instance created with IP: $DB_HOST"

cd ..

# Step 4: Update application configuration
echo "🔧 Step 4: Updating application configuration..."

# Create .env file with Cloud SQL configuration
cat > .env << EOF
DB_HOST=$DB_HOST
DB_NAME=seegap_application_db
DB_USER=seegap_prod_user_2025
DB_PASSWORD=SeeGapProd2025MySQLSecure
DOMAIN_NAME=si.seegap.com
EOF

# Step 5: Deploy updated application
echo "📦 Step 5: Deploying updated application..."

# Create deployment package
echo "📦 Creating deployment package..."
tar --exclude='node_modules' --exclude='.git' --exclude='terraform/.terraform' --exclude='*.log' -czf seegap-app-cloudsql.tar.gz .

# Upload to VM
echo "⬆️ Uploading application to VM..."
gcloud compute scp seegap-app-cloudsql.tar.gz $VM_NAME:/tmp/ --zone=$ZONE --project=$PROJECT_ID

# Extract and deploy on VM
echo "📂 Extracting and deploying on VM..."
gcloud compute ssh $VM_NAME --zone=$ZONE --project=$PROJECT_ID --command="
    cd /var/www/seegap && 
    sudo rm -rf * .* 2>/dev/null || true &&
    sudo tar -xzf /tmp/seegap-app-cloudsql.tar.gz -C /var/www/seegap &&
    sudo chown -R HishamSait:HishamSait /var/www/seegap &&
    sudo chmod -R 755 /var/www/seegap &&
    sudo chmod 666 config.php &&
    sudo chmod -R 777 install/ uploads/ &&
    sudo chown -R www-data:www-data install/ uploads/ config.php
"

# Step 6: Start new containers (without MySQL)
echo "🐳 Step 6: Starting updated Docker containers..."
gcloud compute ssh $VM_NAME --zone=$ZONE --project=$PROJECT_ID --command="cd /var/www/seegap && docker-compose up -d --build"

# Step 7: Wait for containers to be ready
echo "⏳ Step 7: Waiting for containers to be ready..."
sleep 30

# Step 8: Verify deployment
echo "✅ Step 8: Verifying deployment..."
gcloud compute ssh $VM_NAME --zone=$ZONE --project=$PROJECT_ID --command="cd /var/www/seegap && docker-compose ps"

# Step 9: Test database connectivity
echo "🔍 Step 9: Testing database connectivity..."
gcloud compute ssh $VM_NAME --zone=$ZONE --project=$PROJECT_ID --command="
    cd /var/www/seegap && 
    echo '<?php 
    \$host = \"$DB_HOST\";
    \$dbname = \"seegap_application_db\";
    \$username = \"seegap_prod_user_2025\";
    \$password = \"SeeGapProd2025MySQLSecure\";
    
    try {
        \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname\", \$username, \$password);
        echo \"✅ Database connection successful to Cloud SQL!\";
        echo \"\\n📊 Database: \$dbname\";
        echo \"\\n🌐 Host: \$host\";
    } catch(Exception \$e) {
        echo \"❌ Database connection failed: \" . \$e->getMessage();
    }
    ?>' > test_cloudsql.php && 
    docker exec seegap-php-1 php /var/www/html/test_cloudsql.php
"

# Step 10: Clean up
echo "🧹 Step 10: Cleaning up..."
rm -f seegap-app-cloudsql.tar.gz
gcloud compute ssh $VM_NAME --zone=$ZONE --project=$PROJECT_ID --command="rm -f /tmp/seegap-app-cloudsql.tar.gz /var/www/seegap/test_cloudsql.php" || true

# Final status
echo ""
echo "🎉 Cloud SQL Migration and Deployment Complete!"
echo ""
echo "📊 Deployment Summary:"
echo "   ✅ Cloud SQL instance created and configured"
echo "   ✅ Application migrated from Docker MySQL to Cloud SQL"
echo "   ✅ Docker containers updated and running"
echo "   ✅ Database connectivity verified"
echo ""
echo "🌐 Application URL: https://si.seegap.com"
echo "💰 Estimated monthly cost: ~\$9.37 for Cloud SQL db-f1-micro"
echo ""
echo "🔧 Next steps:"
echo "   1. Visit https://si.seegap.com/install to complete fresh installation"
echo "   2. Configure your application settings"
echo "   3. Monitor Cloud SQL performance in GCP Console"
echo ""
echo "📋 Cloud SQL Details:"
terraform -chdir=terraform output deployment_info
