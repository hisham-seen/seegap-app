# Removed Settings Sections - Documentation

This document explains which settings sections have been removed from the admin panel and how to restore them if needed.

## Removed Settings Sections

The following settings sections have been completely disabled and removed:

1. **🤝 Affiliate** - Affiliate program management
2. **📲 Push Notifications** - Push notification settings
3. **📤 Offload & CDN** - File offloading and CDN configuration
4. **📱 PWA** - Progressive Web App settings
5. **🖼️ Image Optimizer** - Image optimization settings
6. **🧷 Dynamic OG Images** - Dynamic Open Graph image generation
7. **📄 License** - License management
8. **🆘 Support** - Support system settings

## What Was Removed

### 1. Navigation Menu Items
- **File**: `themes/phoenix/views/admin/settings/index.php`
- **Action**: Removed dropdown options and sidebar navigation links for all specified sections
- **Effect**: Settings sections no longer appear in the admin settings navigation

### 2. Controller Methods
- **File**: `app/controllers/admin/AdminSettings.php`
- **Action**: Completely removed the following controller methods:
  - `affiliate()`
  - `push_notifications()`
  - `offload()`
  - `pwa()`
  - `image_optimizer()`
  - `dynamic_og_images()`
  - `license()`
  - `support()`
- **Effect**: No backend processing available for these settings

### 3. Route Blocking
- **File**: `app/controllers/admin/AdminSettings.php`
- **Action**: Added explicit route blocking in the `process()` method
- **Code Added**:
```php
/* Block access to disabled settings sections */
$disabled_methods = ['affiliate', 'push_notifications', 'offload', 'pwa', 'image_optimizer', 'dynamic_og_images', 'license', 'support'];

if(isset(\Altum\Router::$method) && in_array(\Altum\Router::$method, $disabled_methods)) {
    redirect('admin/settings/main');
}
```
- **Effect**: Direct URL access to these settings automatically redirects to main settings

## Current Status
**All specified settings sections are completely DISABLED**

## What This Means
- The removed settings sections are not visible in the admin navigation
- Direct URLs to these settings (e.g., `/admin/settings/pwa`) automatically redirect to main settings
- No backend processing is available for these settings
- **Important**: Existing functionality from these features may continue to work if already configured
- Settings data in the database remains intact

## How to Restore Settings Sections

If you need to restore any of these settings sections, follow these steps:

### Step 1: Restore Navigation Links

Edit `themes/phoenix/views/admin/settings/index.php` and add back the navigation items:

**For Dropdown Menu (Mobile):**
```php
<option value="<?= url('admin/settings/affiliate') ?>" class="nav-link" <?= $data->method == 'affiliate' ? 'selected="selected"' : null ?>>🤝 <?= l('admin_settings.affiliate.tab') ?></option>
<option value="<?= url('admin/settings/push_notifications') ?>" class="nav-link" <?= $data->method == 'push_notifications' ? 'selected="selected"' : null ?>>📲 <?= l('admin_settings.push_notifications.tab') ?></option>
<option value="<?= url('admin/settings/offload') ?>" class="nav-link" <?= $data->method == 'offload' ? 'selected="selected"' : null ?>>📤 <?= l('admin_settings.offload.tab') ?></option>
<option value="<?= url('admin/settings/pwa') ?>" class="nav-link" <?= $data->method == 'pwa' ? 'selected="selected"' : null ?>>📱 <?= l('admin_settings.pwa.tab') ?></option>
<option value="<?= url('admin/settings/image_optimizer') ?>" class="nav-link" <?= $data->method == 'image_optimizer' ? 'selected="selected"' : null ?>>🖼️ <?= l('admin_settings.image_optimizer.tab') ?></option>
<option value="<?= url('admin/settings/dynamic_og_images') ?>" class="nav-link" <?= $data->method == 'dynamic_og_images' ? 'selected="selected"' : null ?>>🧷 <?= l('admin_settings.dynamic_og_images.tab') ?></option>
<option value="<?= url('admin/settings/license') ?>" class="nav-link" <?= $data->method == 'license' ? 'selected="selected"' : null ?>>📄 <?= l('admin_settings.license.tab') ?></option>
<option value="<?= url('admin/settings/support') ?>" class="nav-link" <?= $data->method == 'support' ? 'selected="selected"' : null ?>>🆘 <?= l('admin_settings.support.tab') ?></option>
```

**For Desktop Sidebar:**
```php
<a class="nav-link <?= $data->method == 'affiliate' ? 'active' : null ?>" href="<?= url('admin/settings/affiliate') ?>"><i class="fas fa-fw fa-sm fa-wallet mr-2"></i> <?= l('admin_settings.affiliate.tab') ?></a>
<a class="nav-link <?= $data->method == 'push_notifications' ? 'active' : null ?>" href="<?= url('admin/settings/push_notifications') ?>"><i class="fas fa-fw fa-sm fa-bolt-lightning mr-2"></i> <?= l('admin_settings.push_notifications.tab') ?></a>
<a class="nav-link <?= $data->method == 'offload' ? 'active' : null ?>" href="<?= url('admin/settings/offload') ?>"><i class="fas fa-fw fa-sm fa-cloud mr-2"></i> <?= l('admin_settings.offload.tab') ?></a>
<a class="nav-link <?= $data->method == 'pwa' ? 'active' : null ?>" href="<?= url('admin/settings/pwa') ?>"><i class="fas fa-fw fa-sm fa-mobile-alt mr-2"></i> <?= l('admin_settings.pwa.tab') ?></a>
<a class="nav-link <?= $data->method == 'image_optimizer' ? 'active' : null ?>" href="<?= url('admin/settings/image_optimizer') ?>"><i class="fas fa-fw fa-sm fa-image mr-2"></i> <?= l('admin_settings.image_optimizer.tab') ?></a>
<a class="nav-link <?= $data->method == 'dynamic_og_images' ? 'active' : null ?>" href="<?= url('admin/settings/dynamic_og_images') ?>"><i class="fas fa-fw fa-sm fa-x-ray mr-2"></i> <?= l('admin_settings.dynamic_og_images.tab') ?></a>
<a class="nav-link <?= $data->method == 'license' ? 'active' : null ?>" href="<?= url('admin/settings/license') ?>"><i class="fas fa-fw fa-sm fa-key mr-2"></i> <?= l('admin_settings.license.tab') ?></a>
<a class="nav-link <?= $data->method == 'support' ? 'active' : null ?>" href="<?= url('admin/settings/support') ?>"><i class="fas fa-fw fa-sm fa-life-ring mr-2"></i> <?= l('admin_settings.support.tab') ?></a>
```

### Step 2: Remove Route Blocking

Edit `app/controllers/admin/AdminSettings.php` and remove the route blocking code from the `process()` method.

**Find and remove this code:**
```php
/* Block access to disabled settings sections */
$disabled_methods = ['affiliate', 'push_notifications', 'offload', 'pwa', 'image_optimizer', 'dynamic_og_images', 'license', 'support'];

if(isset(\Altum\Router::$method) && in_array(\Altum\Router::$method, $disabled_methods)) {
    redirect('admin/settings/main');
}
```

### Step 3: Restore Controller Methods

Edit `app/controllers/admin/AdminSettings.php` and add back the controller methods. You would need to restore the complete method implementations for each setting you want to re-enable.

**Note**: The original controller methods were quite large and complex. You may need to restore them from a backup or the original source code.

## Important Notes

1. **Database Integrity**: All settings data remains in the database - only the admin interface access has been removed

2. **Plugin Functionality**: If these features were already configured and active, they may continue to function even though the admin interface is disabled

3. **Backup Recommended**: Always backup your files before making changes

4. **Partial Restoration**: You can restore individual settings sections by following the steps above for only the sections you need

5. **View Files**: The actual settings view files (partials) still exist in `themes/phoenix/views/admin/settings/partials/` - only the navigation and controller access has been removed

## Files Modified
- `themes/phoenix/views/admin/settings/index.php` - Navigation removed
- `app/controllers/admin/AdminSettings.php` - Controller methods removed and route blocking added

## Troubleshooting

**If you get errors after restoring:**
1. Ensure navigation, route blocking removal, and controller methods are all properly restored
2. Check for any PHP syntax errors in the modified files
3. Clear any application cache
4. Verify file permissions are correct

**If settings don't appear after restoring:**
1. Check that the navigation links were added in the correct location
2. Ensure the route blocking code was completely removed
3. Verify that the controller method names match exactly
4. Confirm that the corresponding view files exist in the partials directory
