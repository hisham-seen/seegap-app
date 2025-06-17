# Language Module Index

This file provides an overview of all available language modules in the modular language system.

## Available Modules

### Core System Modules

1. **core.php** - Essential global elements
   - Global actions and buttons (submit, create, delete, edit, etc.)
   - Common messages (success, error, info)
   - Navigation elements (pagination, filters, menus)
   - Form elements and validation
   - Accessibility features
   - Numbers and formatting

2. **date.php** - Date and time related translations
   - Date formats and patterns
   - Day and month names (long/short)
   - Time units (seconds, minutes, hours, days, etc.)
   - Relative time expressions
   - Date picker elements

3. **auth.php** - Authentication and user management
   - Login and logout
   - Registration process
   - Password reset and recovery
   - Account activation
   - Two-factor authentication
   - Social login options

### Feature Modules

4. **dashboard.php** - Dashboard interface
   - Dashboard navigation and widgets
   - Statistics and metrics
   - Quick actions
   - Recent activity displays

5. **links.php** - Link management
   - Link creation and editing
   - Link settings and options
   - Link statistics and analytics
   - Link actions (copy, share, delete)
   - Link types and targeting

6. **qr-codes.php** - QR code management
   - QR code creation and editing
   - QR code types (URL, text, email, WiFi, etc.)
   - Design customization options
   - QR code analytics
   - Download and sharing options

7. **projects.php** - Project organization
   - Project creation and management
   - Project collaboration features
   - Project templates
   - Project analytics
   - Team permissions

8. **domains.php** - Custom domain management
   - Domain addition and verification
   - DNS configuration
   - Domain status and settings
   - SSL certificate management

9. **pixels.php** - Tracking pixel management
   - Pixel creation and configuration
   - Pixel types (Facebook, Google, custom)
   - Pixel installation and testing
   - Privacy compliance features

10. **teams.php** - Team collaboration
    - Team creation and management
    - Member invitations and roles
    - Team permissions and settings
    - Team billing and usage

### Account & Business Modules

11. **account.php** - User account management
    - Personal information settings
    - Password and security settings
    - Two-factor authentication
    - API key management
    - Account preferences
    - Billing information

12. **plans.php** - Subscription plans
    - Plan features and limits
    - Plan comparison and selection
    - Usage tracking
    - Billing cycles and payments
    - Plan upgrades and downgrades

13. **payments.php** - Payment management
    - Payment history and details
    - Payment methods
    - Invoices and receipts
    - Refunds and disputes
    - Subscription management

### Communication Modules

14. **emails.php** - Email templates and notifications
    - User welcome and activation emails
    - Password reset emails
    - Payment confirmation emails
    - Data collection notifications
    - Admin notification emails
    - Team invitation emails

15. **contact.php** - Contact and support
    - Contact forms and information
    - Support resources
    - FAQ sections
    - Response time information
    - Social media links

### Public & Marketing Modules

16. **index.php** - Homepage and public pages
    - Hero sections and features
    - Pricing information
    - Testimonials and FAQ
    - Call-to-action elements
    - Footer content
    - Error pages

17. **pages.php** - Static pages
    - About us page
    - Privacy policy
    - Terms of service
    - Help center
    - Security information
    - Status page
    - Blog and careers

### Technical Modules

18. **api.php** - API documentation
    - API overview and authentication
    - Endpoint documentation
    - Request/response examples
    - Error handling
    - SDKs and libraries
    - Rate limiting information

19. **admin.php** - Admin panel
    - Admin navigation and dashboard
    - User management
    - System settings
    - Payment and plan management
    - System maintenance
    - Logs and reports

## Module Structure

Each module follows this structure:
```php
<?php
return [
    'module.section.key' => 'Translation value',
    'module.section.another_key' => 'Another translation',
    // ...
];
```

## Naming Conventions

- **Module names**: lowercase with hyphens (e.g., `qr-codes.php`)
- **Translation keys**: dot notation with module prefix (e.g., `links.create`, `account.settings.general`)
- **Sections**: logical grouping within modules (e.g., `links.statistics`, `account.twofa`)

## Language-Specific Overrides

To create language-specific overrides:
1. Create a directory: `app/languages/modules/{language_name}/`
2. Create module files with the same names
3. Include only the translations you want to override

Example:
```
app/languages/modules/spanish/
├── core.php
├── auth.php
└── links.php
```

## Adding New Modules

1. Create a new PHP file in the modules directory
2. Add the module name to the `$modules` array in `Language::load_modular_language()`
3. Follow the naming conventions and structure
4. Document the module in this index

## Total Translation Count

The modular system contains approximately **2,500+ translation keys** organized across 19 modules, making it much more manageable than a single large file.

## Benefits

- **Organized**: Related translations grouped together
- **Maintainable**: Smaller files are easier to edit
- **Collaborative**: Multiple translators can work on different modules
- **Scalable**: Easy to add new modules for new features
- **Efficient**: Only load required modules when needed
- **Version Control Friendly**: Fewer merge conflicts
