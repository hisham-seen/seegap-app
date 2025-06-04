#!/bin/bash

# Script to enable mod_rewrite in MAMP for pretty URLs
# Run this script to automatically configure MAMP for SeeGap pretty URLs

echo "🚀 SeeGap MAMP mod_rewrite Configuration Script"
echo "=============================================="
echo ""

# Check if MAMP is installed
MAMP_CONF="/Applications/MAMP/conf/apache/httpd.conf"
MAMP_HTDOCS="/Applications/MAMP/htdocs"

if [ ! -f "$MAMP_CONF" ]; then
    echo "❌ MAMP not found at /Applications/MAMP/"
    echo "Please install MAMP or adjust the path in this script."
    exit 1
fi

echo "✅ MAMP installation found"
echo ""

# Backup the original httpd.conf
echo "📋 Creating backup of httpd.conf..."
cp "$MAMP_CONF" "$MAMP_CONF.backup.$(date +%Y%m%d_%H%M%S)"
echo "✅ Backup created: $MAMP_CONF.backup.$(date +%Y%m%d_%H%M%S)"
echo ""

# Enable mod_rewrite
echo "🔧 Enabling mod_rewrite module..."
sed -i '' 's/#LoadModule rewrite_module modules\/mod_rewrite.so/LoadModule rewrite_module modules\/mod_rewrite.so/' "$MAMP_CONF"

# Check if mod_rewrite was enabled
if grep -q "^LoadModule rewrite_module modules/mod_rewrite.so" "$MAMP_CONF"; then
    echo "✅ mod_rewrite module enabled successfully"
else
    echo "⚠️  mod_rewrite might already be enabled or needs manual configuration"
fi
echo ""

# Enable AllowOverride for htdocs directory
echo "🔧 Configuring AllowOverride for htdocs directory..."

# Find and replace AllowOverride None with AllowOverride All in the htdocs directory section
sed -i '' '/DocumentRoot.*htdocs/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' "$MAMP_CONF"

echo "✅ AllowOverride configured for htdocs directory"
echo ""

# Display next steps
echo "🎉 Configuration Complete!"
echo "========================"
echo ""
echo "📝 Next Steps:"
echo "1. Restart MAMP (stop and start Apache)"
echo "2. Test pretty URLs:"
echo "   • http://localhost/login"
echo "   • http://localhost/dashboard"
echo "   • http://localhost/register"
echo ""
echo "🔍 If pretty URLs don't work:"
echo "1. Check MAMP error logs"
echo "2. Verify your .htaccess file is in the correct location"
echo "3. Use fallback URLs: http://localhost/index.php?seegap=login"
echo ""
echo "📁 Your SeeGap application is located at:"
echo "   $MAMP_HTDOCS/app.seegap.com"
echo ""
echo "🔄 To restore original configuration:"
echo "   cp $MAMP_CONF.backup.* $MAMP_CONF"
echo ""
echo "✨ Enjoy your SeeGap application with pretty URLs!"
