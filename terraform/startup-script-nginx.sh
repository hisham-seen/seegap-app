#!/bin/bash

# SeeGap Application VM Startup Script - Nginx + PHP-FPM
# This script runs when the VM is first created and sets up the environment

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

# Install Nginx
echo "🌐 Installing Nginx..."
apt-get install -y nginx

# Install PHP 8.1 and required extensions
echo "🐘 Installing PHP 8.1 and extensions..."
add-apt-repository ppa:ondrej/php -y
apt-get update
apt-get install -y \
    php8.1 \
    php8.1-fpm \
    php8.1-mysql \
    php8.1-curl \
    php8.1-gd \
    php8.1-mbstring \
    php8.1-xml \
    php8.1-zip \
    php8.1-bcmath \
    php8.1-json \
    php8.1-intl \
    php8.1-soap \
    php8.1-imagick \
    php8.1-redis \
    php8.1-memcached \
    php8.1-opcache

# Install MySQL Server
echo "🗄️ Installing MySQL Server..."
debconf-set-selections <<< 'mysql-server mysql-server/root_password password Root#MySQL!SuperSecure@GCP'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password Root#MySQL!SuperSecure@GCP'
apt-get install -y mysql-server

# Secure MySQL installation
echo "🔒 Securing MySQL installation..."
mysql -u root -p'Root#MySQL!SuperSecure@GCP' <<EOF
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
FLUSH PRIVILEGES;
EOF

# Create application database and user
echo "📊 Creating application database..."
mysql -u root -p'Root#MySQL!SuperSecure@GCP' <<EOF
CREATE DATABASE IF NOT EXISTS seegap_application_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'seegap_prod_user_2025'@'localhost' IDENTIFIED BY 'SeeGapProd2025MySQLSecure';
GRANT ALL PRIVILEGES ON seegap_application_db.* TO 'seegap_prod_user_2025'@'localhost';
FLUSH PRIVILEGES;
EOF

# Configure PHP-FPM
echo "⚙️ Configuring PHP-FPM..."
cat > /etc/php/8.1/fpm/pool.d/seegap.conf << 'EOF'
[seegap]
user = www-data
group = www-data
listen = /run/php/php8.1-fpm-seegap.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500

php_admin_value[upload_max_filesize] = 10M
php_admin_value[post_max_size] = 10M
php_admin_value[max_execution_time] = 300
php_admin_value[memory_limit] = 256M
php_admin_value[error_log] = /var/log/php8.1-fpm-seegap.log
php_admin_flag[log_errors] = on

env[HOSTNAME] = $HOSTNAME
env[PATH] = /usr/local/bin:/usr/bin:/bin
env[TMP] = /tmp
env[TMPDIR] = /tmp
env[TEMP] = /tmp
EOF

# Configure Nginx for SeeGap
echo "🌐 Configuring Nginx..."
cat > /etc/nginx/sites-available/seegap << 'EOF'
server {
    listen 80;
    listen [::]:80;
    server_name si.seegap.com www.si.seegap.com;
    root /var/www/html;
    index index.php index.html index.htm;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/javascript;

    # Handle PHP files
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm-seegap.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Security
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
    }

    # Handle static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|webp|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Security - deny access to sensitive files
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    location ~ /(config|install|update)/ {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Pretty URLs for the application
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Logging
    access_log /var/log/nginx/seegap_access.log;
    error_log /var/log/nginx/seegap_error.log;
}
EOF

# Enable the site
ln -sf /etc/nginx/sites-available/seegap /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
nginx -t

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

[nginx-http-auth]
enabled = true
filter = nginx-http-auth
logpath = /var/log/nginx/seegap_error.log
maxretry = 5
bantime = 3600
EOF

systemctl enable fail2ban
systemctl start fail2ban

# Create web directory
echo "📁 Creating web directory..."
mkdir -p /var/www/html
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

# Create uploads directory with proper permissions
mkdir -p /var/www/html/uploads
mkdir -p /var/www/html/uploads/logs
chown -R www-data:www-data /var/www/html/uploads
chmod -R 777 /var/www/html/uploads

# Install Certbot for SSL certificates
echo "🔐 Installing Certbot..."
apt-get install -y certbot python3-certbot-nginx

# Set up SSL certificates (will run after domain is configured)
echo "🔐 Setting up SSL certificate automation..."
cat > /root/setup-ssl.sh << 'EOF'
#!/bin/bash
# Wait for DNS propagation
sleep 60

# Get SSL certificate
certbot --nginx -d si.seegap.com -d www.si.seegap.com --non-interactive --agree-tos --email hisham@seegap.com --redirect

# Test renewal
certbot renew --dry-run

# Reload nginx
systemctl reload nginx

echo "SSL certificates configured successfully!"
EOF

chmod +x /root/setup-ssl.sh

# Create systemd service to run SSL setup after boot
cat > /etc/systemd/system/seegap-ssl-setup.service << 'EOF'
[Unit]
Description=SeeGap SSL Certificate Setup
After=network-online.target
Wants=network-online.target

[Service]
Type=oneshot
ExecStart=/root/setup-ssl.sh
RemainAfterExit=yes

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

# Set up log rotation
echo "📝 Setting up log rotation..."
cat > /etc/logrotate.d/seegap-app << 'EOF'
/var/www/html/uploads/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}

/var/log/nginx/seegap_*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data adm
    postrotate
        systemctl reload nginx
    endscript
}

/var/log/php8.1-fpm-seegap.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        systemctl reload php8.1-fpm
    endscript
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
chown -R www-data:www-data /var/backups/seegap

# Final system optimization
echo "⚡ Final system optimization..."
echo 'vm.swappiness=10' >> /etc/sysctl.conf
echo 'net.core.rmem_max = 16777216' >> /etc/sysctl.conf
echo 'net.core.wmem_max = 16777216' >> /etc/sysctl.conf

# Start and enable services
echo "🚀 Starting services..."
systemctl start mysql
systemctl enable mysql
systemctl start php8.1-fpm
systemctl enable php8.1-fpm
systemctl start nginx
systemctl enable nginx

# Clean up
echo "🧹 Cleaning up..."
apt-get autoremove -y
apt-get autoclean

echo "✅ VM setup completed successfully!"
echo "🌐 Ready for SeeGap application deployment with Nginx + PHP-FPM"
echo "📊 System Status:"
echo "   - Nginx: $(nginx -v 2>&1)"
echo "   - PHP: $(php -v | head -n1)"
echo "   - MySQL: $(mysql --version)"
echo "   - Git: $(git --version)"
echo ""
echo "🔧 Services Status:"
systemctl is-active nginx && echo "   - Nginx: ✅ Running" || echo "   - Nginx: ❌ Not Running"
systemctl is-active php8.1-fpm && echo "   - PHP-FPM: ✅ Running" || echo "   - PHP-FPM: ❌ Not Running"
systemctl is-active mysql && echo "   - MySQL: ✅ Running" || echo "   - MySQL: ❌ Not Running"
echo ""
echo "🔧 Next steps:"
echo "   1. Deploy application code to /var/www/html"
echo "   2. Configure SSL with Certbot"
echo "   3. Import database schema"
