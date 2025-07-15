#!/bin/bash

# SeeGap Incremental Deployment Script
# This script only updates files that have changed, preserving uploads and user data

set -e

echo "🚀 SeeGap Incremental Deployment"
echo "================================="

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
    echo "❌ VM 'seegap-app-vm' does not exist. Please run full deployment first."
    exit 1
fi

echo "✅ VM exists"

# Check what files have changed since last commit
echo "📊 Analyzing changed files..."
CHANGED_FILES=$(git diff --name-only HEAD~1 HEAD 2>/dev/null || echo "")
if [ -z "$CHANGED_FILES" ]; then
    echo "ℹ️ No changes detected since last commit. Checking working directory..."
    CHANGED_FILES=$(git diff --name-only HEAD 2>/dev/null || echo "")
fi

if [ -z "$CHANGED_FILES" ]; then
    echo "✅ No files have changed. Deployment not needed."
    exit 0
fi

echo "📝 Changed files detected:"
echo "$CHANGED_FILES" | sed 's/^/  - /'

# Create incremental deployment package with only changed files
echo "📦 Creating incremental deployment package..."
mkdir -p /tmp/seegap-incremental-deploy

# Copy only changed files
echo "$CHANGED_FILES" | while read -r file; do
    if [ -f "$file" ]; then
        # Create directory structure
        mkdir -p "/tmp/seegap-incremental-deploy/$(dirname "$file")"
        # Copy the file
        cp "$file" "/tmp/seegap-incremental-deploy/$file"
        echo "  ✓ Added: $file"
    fi
done

# Create file list for deployment script
echo "$CHANGED_FILES" > /tmp/seegap-incremental-deploy/changed_files.txt

# Create the deployment archive
cd /tmp/seegap-incremental-deploy
tar -czf ../seegap-incremental.tar.gz .
cd -

echo "✅ Incremental package created with $(echo "$CHANGED_FILES" | wc -l) changed files"

# Upload the incremental package to VM
echo "🚀 Uploading incremental package to VM..."
gcloud compute scp /tmp/seegap-incremental.tar.gz seegap-app-vm:/tmp/ --zone=europe-west1-b

# Create incremental deployment script for VM
cat > /tmp/incremental-deploy.sh << 'EOF'
#!/bin/bash
set -e

echo "🔧 Starting incremental deployment..."

# Extract incremental package
echo "📂 Extracting incremental package..."
cd /tmp
rm -rf seegap-incremental 2>/dev/null || true
mkdir -p seegap-incremental
cd seegap-incremental
tar -xzf /tmp/seegap-incremental.tar.gz

# Read the list of changed files
if [ -f "changed_files.txt" ]; then
    echo "📝 Files to update:"
    cat changed_files.txt | sed 's/^/  - /'
    
    # Update each changed file
    while read -r file; do
        if [ -f "$file" ]; then
            echo "🔄 Updating: $file"
            
            # Create directory if it doesn't exist
            sudo mkdir -p "/var/www/seegap/$(dirname "$file")"
            
            # Copy the updated file
            sudo cp "$file" "/var/www/seegap/$file"
            
            echo "  ✅ Updated: $file"
        fi
    done < changed_files.txt
else
    echo "❌ No file list found in package"
    exit 1
fi

# Set proper ownership for updated files
echo "🔧 Setting file ownership..."
sudo chown -R HishamSait:HishamSait /var/www/seegap

# Check if any PHP files were updated (restart PHP-FPM if needed)
if grep -q "\.php$" changed_files.txt; then
    echo "🔄 PHP files updated, restarting PHP container..."
    cd /var/www/seegap
    if [ -f "docker-compose.yml" ]; then
        # Docker deployment
        if docker-compose restart php 2>/dev/null; then
            echo "✅ PHP container restarted"
        else
            echo "⚠️ PHP container restart failed, but files were updated"
        fi
    else
        # Nginx deployment
        if sudo systemctl restart php8.1-fpm 2>/dev/null; then
            echo "✅ PHP-FPM restarted"
        else
            echo "⚠️ PHP-FPM restart failed, but files were updated"
        fi
    fi
fi

# Check if any CSS/JS files were updated (clear cache if needed)
if grep -qE "\.(css|js|scss)$" changed_files.txt; then
    echo "🧹 Frontend assets updated, clearing cache..."
    sudo rm -rf /var/www/seegap/uploads/cache/* 2>/dev/null || true
    echo "✅ Cache cleared"
fi

# Check if any template files were updated
if grep -qE "\.(php|html|tpl)$" changed_files.txt; then
    echo "🔄 Template files updated, clearing template cache..."
    sudo rm -rf /var/www/seegap/uploads/cache/templates/* 2>/dev/null || true
    echo "✅ Template cache cleared"
fi

# Clean up
rm -rf /tmp/seegap-incremental
rm -f /tmp/seegap-incremental.tar.gz

echo "✅ Incremental deployment completed!"
echo "📊 Updated $(wc -l < changed_files.txt) files"
EOF

# Upload and execute incremental deployment script
echo "🚀 Executing incremental deployment on VM..."
gcloud compute scp /tmp/incremental-deploy.sh seegap-app-vm:/tmp/ --zone=europe-west1-b
gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command="chmod +x /tmp/incremental-deploy.sh && /tmp/incremental-deploy.sh"

# Clean up local files
rm -f /tmp/seegap-incremental.tar.gz
rm -f /tmp/incremental-deploy.sh
rm -rf /tmp/seegap-incremental-deploy

echo ""
echo "🎉 Incremental deployment completed successfully!"
echo "=============================================="
echo "🌐 Application URL: https://si.seegap.com"
echo "📊 Files updated: $(echo "$CHANGED_FILES" | wc -l)"
echo "💾 Uploads preserved: ✅"
echo "🗄️ Database preserved: ✅"
echo ""
echo "📋 What was updated:"
echo "$CHANGED_FILES" | sed 's/^/  ✓ /'
echo ""
echo "🔧 Useful commands:"
echo "- Check application: curl -I https://si.seegap.com"
echo "- View logs: gcloud compute ssh seegap-app-vm --zone=europe-west1-b --command='cd /var/www/seegap && docker-compose logs -f'"
echo "- SSH to VM: gcloud compute ssh seegap-app-vm --zone=europe-west1-b"
