# Plugins Management - Enable/Disable Instructions

This document explains how to enable or disable the plugins management system in your application.

## Current Status
**Plugins management is currently DISABLED**

## What This Means
- The "Plugins" menu item is not visible in the admin sidebar
- Direct URLs to `/admin/plugins` return 404 errors
- All plugin management functionality is inaccessible through the admin interface
- **Important**: Existing active plugins continue to function normally

## How to Enable Plugins Management

### Step 1: Enable the Route
Edit `app/core/Router.php` and find the admin routes section (around line 1380).

**Find this commented section:**
```php
// 'plugins' => [
//     'controller' => 'AdminPlugins',
// ],
```

**Uncomment it to:**
```php
'plugins' => [
    'controller' => 'AdminPlugins',
],
```

### Step 2: Add Navigation Link
Edit `themes/phoenix/views/admin/partials/admin_sidebar.php` and find where you want to add the plugins link (suggested location: after the push-notifications section, around line 50).

**Add this code:**
```php
<li class="<?= \Altum\Router::$controller == 'AdminPlugins' ? 'active' : null ?>">
    <a href="<?= url('admin/plugins') ?>"><i class="fas fa-fw fa-sm fa-puzzle-piece mr-2"></i> <?= l('admin_plugins.menu') ?></a>
</li>
```

## How to Disable Plugins Management

### Step 1: Disable the Route
Edit `app/core/Router.php` and find the admin routes section.

**Find this section:**
```php
'plugins' => [
    'controller' => 'AdminPlugins',
],
```

**Comment it out:**
```php
// 'plugins' => [
//     'controller' => 'AdminPlugins',
// ],
```

### Step 2: Remove Navigation Link
Edit `themes/phoenix/views/admin/partials/admin_sidebar.php` and find the plugins navigation link.

**Remove this entire block:**
```php
<li class="<?= \Altum\Router::$controller == 'AdminPlugins' ? 'active' : null ?>">
    <a href="<?= url('admin/plugins') ?>"><i class="fas fa-fw fa-sm fa-puzzle-piece mr-2"></i> <?= l('admin_plugins.menu') ?></a>
</li>
```

## Important Notes

1. **Plugin Functionality**: Disabling the management interface does NOT disable active plugins. They will continue to work.

2. **File Safety**: These changes only modify routing and navigation. No plugin files are deleted.

3. **Reversible**: You can easily switch between enabled/disabled states by following the instructions above.

4. **Cache**: After making changes, you may need to clear any application cache if your system uses caching.

5. **Backup**: Always backup your files before making changes.

## Files Modified
- `app/core/Router.php` - Controls routing to plugins management
- `themes/phoenix/views/admin/partials/admin_sidebar.php` - Controls admin navigation menu

## Troubleshooting

**If plugins management doesn't appear after enabling:**
1. Check that both files were modified correctly
2. Clear any application cache
3. Verify file permissions are correct
4. Check for any PHP syntax errors in the modified files

**If you get errors after disabling:**
1. Ensure the route is properly commented out
2. Make sure the navigation link is completely removed
3. Check for any remaining references to AdminPlugins controller
