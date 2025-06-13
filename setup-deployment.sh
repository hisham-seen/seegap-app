#!/bin/bash

# SeeGap Application Deployment Setup Script
# This script helps you set up the deployment environment

set -e

echo "🚀 SeeGap Application Deployment Setup"
echo "======================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Project configuration
PROJECT_ID="eminent-subset-462023-f9"
REGION="europe-west1"
ZONE="europe-west1-b"

echo -e "${BLUE}Project ID: ${PROJECT_ID}${NC}"
echo -e "${BLUE}Region: ${REGION}${NC}"
echo -e "${BLUE}Zone: ${ZONE}${NC}"
echo ""

# Check if gcloud is installed
if ! command -v gcloud &> /dev/null; then
    echo -e "${RED}❌ Google Cloud SDK is not installed${NC}"
    echo "Please install it from: https://cloud.google.com/sdk/docs/install"
    exit 1
fi

echo -e "${GREEN}✅ Google Cloud SDK is installed${NC}"

# Check if user is authenticated
if ! gcloud auth list --filter=status:ACTIVE --format="value(account)" | grep -q .; then
    echo -e "${YELLOW}⚠️  Not authenticated with Google Cloud${NC}"
    echo "Please run: gcloud auth login"
    exit 1
fi

echo -e "${GREEN}✅ Authenticated with Google Cloud${NC}"

# Set project
echo "🔧 Setting up project..."
gcloud config set project $PROJECT_ID

# Enable required APIs
echo "🔧 Enabling required APIs..."
gcloud services enable compute.googleapis.com
gcloud services enable cloudresourcemanager.googleapis.com
gcloud services enable iam.googleapis.com

echo -e "${GREEN}✅ APIs enabled${NC}"

# Create service account
echo "🔧 Creating service account..."
if gcloud iam service-accounts describe seegap-terraform@$PROJECT_ID.iam.gserviceaccount.com &> /dev/null; then
    echo -e "${YELLOW}⚠️  Service account already exists${NC}"
else
    gcloud iam service-accounts create seegap-terraform \
        --display-name="SeeGap Terraform Service Account" \
        --project=$PROJECT_ID
    echo -e "${GREEN}✅ Service account created${NC}"
fi

# Grant roles
echo "🔧 Granting IAM roles..."
gcloud projects add-iam-policy-binding $PROJECT_ID \
    --member="serviceAccount:seegap-terraform@$PROJECT_ID.iam.gserviceaccount.com" \
    --role="roles/compute.admin" --quiet

gcloud projects add-iam-policy-binding $PROJECT_ID \
    --member="serviceAccount:seegap-terraform@$PROJECT_ID.iam.gserviceaccount.com" \
    --role="roles/iam.serviceAccountUser" --quiet

gcloud projects add-iam-policy-binding $PROJECT_ID \
    --member="serviceAccount:seegap-terraform@$PROJECT_ID.iam.gserviceaccount.com" \
    --role="roles/compute.networkAdmin" --quiet

echo -e "${GREEN}✅ IAM roles granted${NC}"

# Create service account key
echo "🔧 Creating service account key..."
if [ -f "seegap-terraform-key.json" ]; then
    echo -e "${YELLOW}⚠️  Service account key already exists${NC}"
    read -p "Do you want to create a new key? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        rm -f seegap-terraform-key.json
        gcloud iam service-accounts keys create seegap-terraform-key.json \
            --iam-account=seegap-terraform@$PROJECT_ID.iam.gserviceaccount.com
        echo -e "${GREEN}✅ New service account key created${NC}"
    fi
else
    gcloud iam service-accounts keys create seegap-terraform-key.json \
        --iam-account=seegap-terraform@$PROJECT_ID.iam.gserviceaccount.com
    echo -e "${GREEN}✅ Service account key created${NC}"
fi

# Generate SSH keys
echo "🔧 Generating SSH keys..."
if [ -f "$HOME/.ssh/seegap-deployment" ]; then
    echo -e "${YELLOW}⚠️  SSH keys already exist${NC}"
    read -p "Do you want to generate new SSH keys? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        ssh-keygen -t rsa -b 4096 -C "seegap-deployment@github.com" -f $HOME/.ssh/seegap-deployment -N ""
        echo -e "${GREEN}✅ New SSH keys generated${NC}"
    fi
else
    ssh-keygen -t rsa -b 4096 -C "seegap-deployment@github.com" -f $HOME/.ssh/seegap-deployment -N ""
    echo -e "${GREEN}✅ SSH keys generated${NC}"
fi

# Display information for GitHub secrets
echo ""
echo "🔐 GitHub Secrets Configuration"
echo "==============================="
echo ""
echo -e "${BLUE}Add these secrets to your GitHub repository:${NC}"
echo ""

echo -e "${YELLOW}GCP_PROJECT_ID:${NC}"
echo "$PROJECT_ID"
echo ""

echo -e "${YELLOW}GCP_SERVICE_ACCOUNT_KEY:${NC}"
cat seegap-terraform-key.json
echo ""

echo -e "${YELLOW}SSH_PUBLIC_KEY:${NC}"
cat $HOME/.ssh/seegap-deployment.pub
echo ""

echo -e "${YELLOW}SSH_PRIVATE_KEY:${NC}"
cat $HOME/.ssh/seegap-deployment
echo ""

echo -e "${YELLOW}CLOUDFLARE_API_TOKEN:${NC}"
echo "jUQEU-cZVzJ5-wep9xsfISgtvbqhi9ySHYQOAtAW"
echo ""

echo -e "${YELLOW}DB_PASSWORD:${NC}"
echo "SeeGap#Prod$2025!MySQL@Secure"
echo ""

echo -e "${YELLOW}DB_ROOT_PASSWORD:${NC}"
echo "Root#MySQL$2025!SuperSecure@GCP"
echo ""

# Create terraform.tfvars file
echo "🔧 Creating Terraform variables file..."
cat > terraform/terraform.tfvars << EOF
# GCP Configuration
gcp_project_id = "$PROJECT_ID"
gcp_region     = "$REGION"
gcp_zone       = "$ZONE"
machine_type   = "e2-medium"

# SSH Configuration
ssh_username   = "HishamSait"
ssh_public_key = "$(cat $HOME/.ssh/seegap-deployment.pub)"

# Domain Configuration
domain_name = "seegap.com"
subdomain   = "si"

# Cloudflare Configuration
cloudflare_api_token = "jUQEU-cZVzJ5-wep9xsfISgtvbqhi9ySHYQOAtAW"

# Application Configuration
app_environment    = "production"
db_password       = "SeeGap#Prod$2025!MySQL@Secure"
db_root_password  = "Root#MySQL$2025!SuperSecure@GCP"
EOF

echo -e "${GREEN}✅ Terraform variables file created${NC}"

echo ""
echo "🎉 Setup Complete!"
echo "=================="
echo ""
echo -e "${GREEN}Next steps:${NC}"
echo "1. Create a GitHub repository for your SeeGap application"
echo "2. Add the secrets shown above to your GitHub repository"
echo "3. Push your code to the repository"
echo "4. The deployment will start automatically!"
echo ""
echo -e "${BLUE}Your application will be available at: https://si.seegap.com${NC}"
echo ""
echo -e "${YELLOW}Important files created:${NC}"
echo "- seegap-terraform-key.json (GCP service account key)"
echo "- ~/.ssh/seegap-deployment (SSH private key)"
echo "- ~/.ssh/seegap-deployment.pub (SSH public key)"
echo "- terraform/terraform.tfvars (Terraform variables)"
echo ""
echo -e "${RED}⚠️  Keep these files secure and do not commit them to version control!${NC}"
