# SeeGap Application - Production Ready

A comprehensive URL shortening and link management application deployed on Google Cloud Platform with automated CI/CD pipeline.

## 🌐 Live Application
- **Production URL**: https://si.seegap.com
- **Install Page**: https://si.seegap.com/install/
- **Static IP**: 35.195.20.161

## 🚀 Quick Deployment

### Using the Complete Production Deployment Script
```bash
./deploy-production-complete.sh
```

This script will:
- Deploy GCP infrastructure with Terraform
- Create and upload deployment package
- Install SSL certificates with Let's Encrypt
- Configure domain with HTTPS
- Set up external Cloud SQL database
- Deploy Docker containers (Nginx + PHP + Redis)

### Manual Deployment Steps
1. **Deploy Infrastructure**: 
   ```bash
   cd terraform && terraform apply
   ```
2. **Deploy Application**: 
   ```bash
   ./deploy-docker.sh
   ```
3. **Setup Database**: 
   ```bash
   ./deploy-cloud-sql.sh
   ```

## 🏗️ Infrastructure

### GCP Resources
- **VM Instance**: seegap-app-vm (europe-west1-b)
- **Database**: Cloud SQL MySQL (34.76.46.27)
- **Static IP**: 35.195.20.161
- **SSL**: Let's Encrypt certificates
- **Domain**: si.seegap.com (Cloudflare managed)

### Application Stack
- **Web Server**: Nginx (containerized)
- **Application**: PHP 8.1-FPM (containerized)
- **Cache**: Redis (containerized)
- **Database**: External Cloud SQL MySQL

## 🐳 Docker Configuration

### Services
- **app-nginx**: Web server with SSL termination
- **php**: PHP-FPM application server
- **redis**: In-memory cache

### Key Features
- HTTP to HTTPS redirect
- SSL/TLS encryption
- Security headers
- Optimized caching
- External database connection

## 🔐 Security Features

- ✅ HTTPS/SSL with Let's Encrypt
- ✅ Security headers (HSTS, XSS protection, etc.)
- ✅ Firewall configuration
- ✅ Secure database connections
- ✅ File permission management

## 📁 Project Structure

```
├── app/                    # Application source code
├── docker/                 # Docker configuration
│   ├── nginx/             # Nginx configuration
│   └── php/               # PHP-FPM configuration
├── terraform/             # Infrastructure as Code
├── .github/workflows/     # CI/CD pipeline
├── config.production.php  # Production configuration
├── docker-compose.yml     # Container orchestration
└── deploy-production-complete.sh  # Complete deployment script
```

## 🔧 Configuration Files

### Key Configuration Files Updated for Production:
- `docker/nginx/default.conf` - HTTPS server blocks with SSL
- `docker/nginx/Dockerfile` - Exposes ports 80 and 443
- `docker-compose.yml` - SSL certificate mounting
- `config.production.php` - External database configuration
- `.github/workflows/deploy.yml` - CI/CD with correct IP

## 🚀 CI/CD Pipeline

### GitHub Actions Workflow
- Automated deployment on push/manual trigger
- Preserves user uploads and data
- Zero-downtime deployment
- Automatic rollback on failure

### Deployment Process
1. Create deployment package
2. Upload to GCP VM
3. Backup existing deployment
4. Deploy new version
5. Restart services
6. Verify deployment

## 🗄️ Database Configuration

### External Cloud SQL
- **Host**: 34.76.46.27
- **Database**: seegap_application_db
- **User**: seegap_prod_user_2025
- **SSL**: Enabled

### Connection Details
The application connects to an external Cloud SQL instance for better performance and reliability.

## 📊 Monitoring & Maintenance

### Health Checks
- Application status monitoring
- SSL certificate expiration
- Database connectivity
- Container health

### Backup Strategy
- Automated database backups
- Application file backups
- User upload preservation

## 🔄 SSL Certificate Management

### Let's Encrypt Integration
- Automatic certificate generation
- 90-day renewal cycle
- Domain validation
- Wildcard support

### Certificate Locations
- Host: `/etc/letsencrypt/live/si.seegap.com/`
- Container: `/etc/letsencrypt/`

## 🌍 Domain Configuration

### DNS Settings
- **Domain**: si.seegap.com
- **CDN**: Cloudflare
- **IP**: 35.195.20.161
- **SSL**: Full (strict)

## 📝 Deployment Logs

### Recent Deployments
- **Latest**: Successfully deployed with HTTPS and SSL
- **Status**: ✅ All systems operational
- **Features**: Complete Docker setup with external database

### Deployment History
- Infrastructure deployed via Terraform
- SSL certificates configured
- Domain HTTPS working
- Database connection established
- CI/CD pipeline operational

## 🛠️ Troubleshooting

### Common Issues
1. **SSL Certificate Issues**: Check Let's Encrypt logs
2. **Database Connection**: Verify Cloud SQL IP whitelist
3. **Container Issues**: Check Docker logs
4. **Domain Issues**: Verify Cloudflare DNS settings

### Useful Commands
```bash
# Check container status
docker-compose ps

# View logs
docker-compose logs app-nginx
docker-compose logs php

# Restart services
docker-compose restart

# SSL certificate renewal
sudo certbot renew
```

## 📞 Support

For deployment issues or questions, check the deployment logs or container status.

---

**Last Updated**: July 17, 2025  
**Status**: ✅ Production Ready with HTTPS  
**Version**: 2.0 (Docker + SSL + External DB)
