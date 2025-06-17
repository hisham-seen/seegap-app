# Modular Language System

This directory contains the modular language files that replace the large monolithic language files. The system is designed to make language management more organized and maintainable.

## Structure

```
app/languages/modules/
├── core.php           # Core translations (buttons, messages, etc.)
├── date.php           # Date and time related translations
├── auth.php           # Authentication (login, register, etc.)
├── emails.php         # Email templates and messages
├── dashboard.php      # Dashboard interface
├── links.php          # Link management
├── qr-codes.php       # QR code management
├── projects.php       # Project management
├── domains.php        # Domain management
├── pixels.php         # Pixel tracking
├── teams.php          # Team management
├── plans.php          # Subscription plans
├── payments.php       # Payment processing
├── admin.php          # Admin panel
├── api.php            # API documentation
├── index.php          # Homepage and public pages
├── contact.php        # Contact forms
├── pages.php          # Static pages
└── [language]/        # Language-specific overrides
    ├── core.php
    ├── date.php
    └── ...
```

## How It Works

1. **Automatic Loading**: The system automatically loads all module files and merges them into a single language array.

2. **Language-Specific Overrides**: You can create language-specific directories (e.g., `spanish/`, `french/`) to override specific translations for that language.

3. **Backward Compatibility**: The system falls back to the original monolithic language files if modular files are not available.

4. **Caching**: The modular system works with the existing caching mechanism for optimal performance.

## Adding New Modules

1. Create a new PHP file in the `modules/` directory
2. Follow the naming convention: `feature-name.php`
3. Add the module name to the `$modules` array in `Language::load_modular_language()`
4. Structure your translations with appropriate prefixes

Example module file:
```php
<?php
return [
    'feature.title' => 'Feature Title',
    'feature.description' => 'Feature Description',
    'feature.action' => 'Action Button',
];
```

## Language-Specific Overrides

To override translations for a specific language:

1. Create a directory with the language name: `app/languages/modules/spanish/`
2. Create module files with the same names as the base modules
3. Include only the translations you want to override

Example override file (`spanish/core.php`):
```php
<?php
return [
    'global.submit' => 'Enviar',
    'global.cancel' => 'Cancelar',
];
```

## Best Practices

1. **Consistent Naming**: Use dot notation for translation keys (e.g., `module.section.key`)
2. **Logical Grouping**: Group related translations in the same module
3. **Minimal Overrides**: Only override translations that need to be different
4. **Documentation**: Comment complex translation keys
5. **Testing**: Test with different languages to ensure proper loading

## Migration from Monolithic Files

To migrate from the old system:

1. Keep your existing language files as backup
2. The system will automatically use modular files when available
3. Gradually move translations from monolithic files to appropriate modules
4. Test thoroughly before removing old files

## Performance

- Modular loading is optimized for performance
- Caching works the same as before
- Only required modules are loaded when needed
- Language-specific overrides are merged efficiently

## Troubleshooting

- **Missing translations**: Check if the module is listed in the `$modules` array
- **Override not working**: Ensure the language directory name matches exactly
- **Performance issues**: Clear the language cache using `Language::clear_cache()`
- **Fallback issues**: Verify that base module files exist and are properly formatted
