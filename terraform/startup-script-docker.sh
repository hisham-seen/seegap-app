#!/bin/bash

# SeeGap Application VM Startup Script - Docker Version
# This script runs when the VM is first created and sets up the Docker environment

set -e

# Update system packages
echo "🔄 Updating system packages..."
apt-get update
apt-get upgrade -y

# Install essential packages
echo "📦 Installing essential packages..."
apt-get install -y \
    curl \
    wget \
    git \
    unzip \
    software-properties-common \
    apt-transport-https \
    ca-certificates \
    gnupg \
    lsb-release \
    htop \
    nano \
    vim \
    fail2ban \
    ufw \
    rsync

# Install Docker
echo "🐳 Installing Docker..."
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null
apt-get update
apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Start and enable Docker
systemctl start docker
systemctl enable docker

# Add user to docker group
usermod -aG docker HishamSait

# Install Docker Compose (standalone)
echo "🐳 Installing Docker Compose..."
curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose

# Configure firewall
echo "🔥 Configuring firewall..."
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 80/tcp
ufw allow 443/tcp
ufw --force enable

# Configure fail2ban for SSH protection
echo "🛡️ Configuring fail2ban..."
cat > /etc/fail2ban/jail.local << 'EOF'
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3

[sshd]
enabled = true
port = ssh
logpath = /var/log/auth.log
maxretry = 3
bantime = 3600
EOF

systemctl enable fail2ban
systemctl start fail2ban

# Install MySQL Client for database setup
echo "🗄️ Installing MySQL client..."
apt-get install -y mysql-client

# Create application directory
echo "📁 Creating application directory..."
mkdir -p /var/www/seegap
chown -R HishamSait:HishamSait /var/www/seegap

# Create database setup script
echo "🗄️ Creating database setup script..."
cat > /root/setup-database.sh << 'EOF'
#!/bin/bash
set -e

echo "🗄️ Setting up database..."

# Wait for MySQL container to be ready
echo "⏳ Waiting for MySQL container to be ready..."
for i in {1..30}; do
    if docker exec seegap-mysql-1 mysql -u root -p'Root#MySQL!SuperSecure@GCP' -e "SELECT 1" >/dev/null 2>&1; then
        echo "✅ MySQL is ready"
        break
    else
        echo "⏳ Attempt $i: MySQL not ready yet, waiting 10 seconds..."
        sleep 10
    fi
    
    if [ $i -eq 30 ]; then
        echo "❌ MySQL is not ready after 5 minutes"
        exit 1
    fi
done

# Create database and user with specific credentials
echo "📊 Creating database and user..."
docker exec seegap-mysql-1 mysql -u root -p'Root#MySQL!SuperSecure@GCP' << 'SQLEOF'
CREATE DATABASE IF NOT EXISTS seegap_application_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'seegap_prod_user_2025'@'%' IDENTIFIED BY 'SeeGapProd2025MySQLSecure';
GRANT ALL PRIVILEGES ON seegap_application_db.* TO 'seegap_prod_user_2025'@'%';
FLUSH PRIVILEGES;
SQLEOF

echo "✅ Database setup completed!"
echo "📊 Database: seegap_application_db"
echo "👤 Username: seegap_prod_user_2025"
echo "🔗 Connection: mysql://seegap_prod_user_2025:SeeGapProd2025MySQLSecure@localhost/seegap_application_db"
EOF

chmod +x /root/setup-database.sh

# Install Certbot for SSL certificates
echo "🔐 Installing Certbot..."
apt-get install -y certbot

# Create SSL certificate setup script
echo "🔐 Creating SSL certificate setup script..."
cat > /root/setup-ssl.sh << 'EOF'
#!/bin/bash
# Wait for DNS propagation and application to be running
sleep 120

# Stop nginx container temporarily for certificate generation
cd /var/www/seegap
docker-compose stop app-nginx || true

# Get SSL certificate
certbot certonly --standalone -d si.seegap.com -d www.si.seegap.com --non-interactive --agree-tos --email hisham@seegap.com

# Copy certificates to docker volume location
mkdir -p /var/www/seegap/ssl
cp /etc/letsencrypt/live/si.seegap.com/fullchain.pem /var/www/seegap/ssl/
cp /etc/letsencrypt/live/si.seegap.com/privkey.pem /var/www/seegap/ssl/
chown -R HishamSait:HishamSait /var/www/seegap/ssl

# Restart containers
docker-compose up -d

echo "SSL certificates configured successfully!"
EOF

chmod +x /root/setup-ssl.sh

# Create systemd service to run SSL setup after boot
cat > /etc/systemd/system/seegap-ssl-setup.service << 'EOF'
[Unit]
Description=SeeGap SSL Certificate Setup
After=network-online.target docker.service
Wants=network-online.target
Requires=docker.service

[Service]
Type=oneshot
ExecStart=/root/setup-ssl.sh
RemainAfterExit=yes
User=root

[Install]
WantedBy=multi-user.target
EOF

# Enable the service (will run once after boot)
systemctl enable seegap-ssl-setup.service

# Install Google Cloud SDK
echo "☁️ Installing Google Cloud SDK..."
echo "deb [signed-by=/usr/share/keyrings/cloud.google.gpg] https://packages.cloud.google.com/apt cloud-sdk main" | tee -a /etc/apt/sources.list.d/google-cloud-sdk.list
curl https://packages.cloud.google.com/apt/doc/apt-key.gpg | apt-key --keyring /usr/share/keyrings/cloud.google.gpg add -
apt-get update
apt-get install -y google-cloud-sdk

# Configure Git
echo "🔧 Configuring Git..."
sudo -u HishamSait git config --global user.name "SeeGap Deployment"
sudo -u HishamSait git config --global user.email "hisham@seegap.com"
sudo -u HishamSait git config --global init.defaultBranch main

# Create swap file for better performance
echo "💾 Creating swap file..."
fallocate -l 2G /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap sw 0 0' | tee -a /etc/fstab

# Set up log rotation for Docker containers
echo "📝 Setting up log rotation..."
cat > /etc/logrotate.d/docker-containers << 'EOF'
/var/lib/docker/containers/*/*.log {
    daily
    missingok
    rotate 7
    compress
    delaycompress
    notifempty
    copytruncate
}
EOF

# Set up automatic security updates
echo "🔄 Setting up automatic security updates..."
apt-get install -y unattended-upgrades
cat > /etc/apt/apt.conf.d/50unattended-upgrades << 'EOF'
Unattended-Upgrade::Allowed-Origins {
    "${distro_id}:${distro_codename}-security";
    "${distro_id}ESMApps:${distro_codename}-apps-security";
    "${distro_id}ESM:${distro_codename}-infra-security";
};
Unattended-Upgrade::AutoFixInterruptedDpkg "true";
Unattended-Upgrade::MinimalSteps "true";
Unattended-Upgrade::Remove-Unused-Dependencies "true";
Unattended-Upgrade::Automatic-Reboot "false";
EOF

echo "APT::Periodic::Update-Package-Lists \"1\";" > /etc/apt/apt.conf.d/20auto-upgrades
echo "APT::Periodic::Unattended-Upgrade \"1\";" >> /etc/apt/apt.conf.d/20auto-upgrades

# Create deployment user SSH directory
echo "🔑 Setting up SSH directory..."
sudo -u HishamSait mkdir -p /home/HishamSait/.ssh
sudo -u HishamSait chmod 700 /home/HishamSait/.ssh
sudo -u HishamSait touch /home/HishamSait/.ssh/authorized_keys
sudo -u HishamSait chmod 600 /home/HishamSait/.ssh/authorized_keys

# Create backup directory
mkdir -p /var/backups/seegap
chown -R HishamSait:HishamSait /var/backups/seegap

# Final system optimization
echo "⚡ Final system optimization..."
echo 'vm.swappiness=10' >> /etc/sysctl.conf
echo 'net.core.rmem_max = 16777216' >> /etc/sysctl.conf
echo 'net.core.wmem_max = 16777216' >> /etc/sysctl.conf

# Clean up
echo "🧹 Cleaning up..."
apt-get autoremove -y
apt-get autoclean

echo "✅ VM setup completed successfully!"
echo "🐳 Ready for SeeGap application deployment with Docker"
echo "📊 System Status:"
echo "   - Docker: $(docker --version)"
echo "   - Docker Compose: $(docker-compose --version)"
echo "   - Git: $(git --version)"
echo ""
echo "🔧 Services Status:"
systemctl is-active docker && echo "   - Docker: ✅ Running" || echo "   - Docker: ❌ Not Running"
systemctl is-active fail2ban && echo "   - Fail2ban: ✅ Running" || echo "   - Fail2ban: ❌ Not Running"
echo ""
echo "🔧 Next steps:"
echo "   1. Deploy application code to /var/www/seegap"
echo "   2. Run docker-compose up -d"
echo "   3. Configure SSL with Certbot"
