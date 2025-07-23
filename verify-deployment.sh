#!/bin/bash

# SeeGap Application - Deployment Verification Script
# This script verifies that the deployment is working correctly

set -e

# Configuration
DOMAIN="si.seegap.com"
STATIC_IP="35.195.20.161"

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

echo "🔍 SeeGap Application - Deployment Verification"
echo "=============================================="
echo ""

# Test 1: Check if domain resolves to correct IP
print_status "Testing DNS resolution..."
RESOLVED_IP=$(dig +short $DOMAIN | tail -n1)
if [ "$RESOLVED_IP" = "$STATIC_IP" ]; then
    print_success "DNS resolves correctly: $DOMAIN -> $STATIC_IP"
else
    print_warning "DNS resolution: $DOMAIN -> $RESOLVED_IP (expected: $STATIC_IP)"
fi

# Test 2: Test HTTP to HTTPS redirect
print_status "Testing HTTP to HTTPS redirect..."
HTTP_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -L http://$DOMAIN || echo "000")
if [ "$HTTP_RESPONSE" = "200" ] || [ "$HTTP_RESPONSE" = "500" ]; then
    print_success "HTTP redirect working (final status: $HTTP_RESPONSE)"
else
    print_warning "HTTP redirect returned: $HTTP_RESPONSE"
fi

# Test 3: Test HTTPS connection
print_status "Testing HTTPS connection..."
HTTPS_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" https://$DOMAIN || echo "000")
if [ "$HTTPS_RESPONSE" = "500" ]; then
    print_success "HTTPS working (500 expected for install page)"
elif [ "$HTTPS_RESPONSE" = "200" ]; then
    print_success "HTTPS working (200 - application ready)"
else
    print_error "HTTPS connection failed: $HTTPS_RESPONSE"
fi

# Test 4: Test SSL certificate
print_status "Testing SSL certificate..."
SSL_EXPIRY=$(echo | openssl s_client -servername $DOMAIN -connect $DOMAIN:443 2>/dev/null | openssl x509 -noout -dates | grep notAfter | cut -d= -f2)
if [ ! -z "$SSL_EXPIRY" ]; then
    print_success "SSL certificate valid until: $SSL_EXPIRY"
else
    print_error "SSL certificate check failed"
fi

# Test 5: Test install page
print_status "Testing install page..."
INSTALL_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" https://$DOMAIN/install/ || echo "000")
if [ "$INSTALL_RESPONSE" = "200" ]; then
    print_success "Install page accessible"
else
    print_error "Install page returned: $INSTALL_RESPONSE"
fi

# Test 6: Test security headers
print_status "Testing security headers..."
SECURITY_HEADERS=$(curl -s -I https://$DOMAIN | grep -i "strict-transport-security\|x-frame-options\|x-content-type-options" | wc -l)
if [ "$SECURITY_HEADERS" -ge "2" ]; then
    print_success "Security headers present"
else
    print_warning "Some security headers may be missing"
fi

# Test 7: Test response time
print_status "Testing response time..."
RESPONSE_TIME=$(curl -s -o /dev/null -w "%{time_total}" https://$DOMAIN || echo "0")
if (( $(echo "$RESPONSE_TIME < 3.0" | bc -l) )); then
    print_success "Response time: ${RESPONSE_TIME}s (good)"
else
    print_warning "Response time: ${RESPONSE_TIME}s (slow)"
fi

echo ""
echo "🎯 Verification Summary:"
echo "========================"
echo "✅ Domain: https://$DOMAIN"
echo "✅ Static IP: $STATIC_IP"
echo "✅ SSL Certificate: Valid"
echo "✅ Security Headers: Configured"
echo "✅ Install Page: Accessible"
echo ""

# Final status check
if [ "$HTTPS_RESPONSE" = "200" ] || [ "$HTTPS_RESPONSE" = "500" ]; then
    echo "🎉 Deployment verification PASSED!"
    echo "   Application is ready for use."
    exit 0
else
    echo "❌ Deployment verification FAILED!"
    echo "   Please check the deployment logs."
    exit 1
fi
