<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Admin Settings Sidebar Configuration Builder
 * Returns the configuration array for the reusable secondary sidebar component
 */

function get_admin_settings_sidebar_config($data) {
    $items = [];
    
    // Main settings
    $items[] = [
        'type' => 'link',
        'url' => url('admin/settings/main'),
        'icon' => 'fas fa-fw fa-sm fa-home mr-2',
        'label' => l('admin_settings.main.tab'),
        'active' => $data->method == 'main',
        'mobile_emoji' => '🏠'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/settings/users'),
        'icon' => 'fas fa-fw fa-sm fa-users mr-2',
        'label' => l('admin_settings.users.tab'),
        'active' => $data->method == 'users',
        'mobile_emoji' => '👥'
    ];
    
    // Feature settings
    $items[] = [
        'type' => 'link',
        'url' => url('admin/settings/links'),
        'icon' => 'fas fa-fw fa-sm fa-link mr-2',
        'label' => l('admin_settings.links.tab'),
        'active' => $data->method == 'links',
        'mobile_emoji' => '🔗'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/settings/products'),
        'icon' => 'fas fa-fw fa-sm fa-box mr-2',
        'label' => l('admin_settings.products.tab'),
        'active' => $data->method == 'products',
        'mobile_emoji' => '📦'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/settings/codes'),
        'icon' => 'fas fa-fw fa-sm fa-qrcode mr-2',
        'label' => l('admin_settings.codes.tab'),
        'active' => $data->method == 'codes',
        'mobile_emoji' => '💻'
    ];
    
    // Plugin-dependent items
    if(\SeeGap\Plugin::is_active('email-signatures')) {
        $items[] = [
            'type' => 'link',
            'url' => url('admin/settings/signatures'),
            'icon' => 'fas fa-fw fa-sm fa-file-signature mr-2',
            'label' => l('admin_settings.signatures.tab'),
            'active' => $data->method == 'signatures',
            'mobile_emoji' => '✍️'
        ];
    }
    
    if(\SeeGap\Plugin::is_active('aix')) {
        $items[] = [
            'type' => 'link',
            'url' => url('admin/settings/aix'),
            'icon' => 'fas fa-fw fa-sm fa-robot mr-2',
            'label' => l('admin_settings.aix.tab'),
            'active' => $data->method == 'aix',
            'mobile_emoji' => '🤖'
        ];
    }
    
    // Payment settings
    $items[] = [
        'type' => 'link',
        'url' => url('admin/settings/payment'),
        'icon' => 'fas fa-fw fa-sm fa-credit-card mr-2',
        'label' => l('admin_settings.payment.tab'),
        'active' => $data->method == 'payment',
        'mobile_emoji' => '💳'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/settings/business'),
        'icon' => 'fas fa-fw fa-sm fa-briefcase mr-2',
        'label' => l('admin_settings.business.tab'),
        'active' => $data->method == 'business',
        'mobile_emoji' => '🏢'
    ];
    
    // Payment processors group
    if(!empty($data->payment_processors)) {
        $payment_processor_items = [];
        foreach($data->payment_processors as $key => $value) {
            $payment_processor_items[] = [
                'type' => 'link',
                'url' => url('admin/settings/' . $key),
                'icon' => $value['icon'] . ' fa-fw fa-sm mr-2',
                'label' => l('admin_settings.' . $key . '.tab'),
                'active' => $data->method == $key,
                'mobile_emoji' => '💲'
            ];
        }
        
        $items[] = [
            'type' => 'group',
            'label' => l('admin_settings.payment_processors'),
            'icon' => 'fas fa-fw fa-sm fa-piggy-bank mr-2',
            'collapse_id' => 'payment_processors_collapse',
            'active' => array_key_exists($data->method, $data->payment_processors),
            'items' => $payment_processor_items
        ];
    }
    
    // Security settings
    $items[] = [
        'type' => 'link',
        'url' => url('admin/settings/captcha'),
        'icon' => 'fas fa-fw fa-sm fa-low-vision mr-2',
        'label' => l('admin_settings.captcha.tab'),
        'active' => $data->method == 'captcha',
        'mobile_emoji' => '🧠'
    ];
    
    
    // Divider
    $items[] = ['type' => 'divider'];
    
    // Additional settings
    $additional_settings = [
        'ads' => ['icon' => 'fas fa-fw fa-sm fa-ad mr-2', 'emoji' => '📢'],
        'cookie_consent' => ['icon' => 'fas fa-fw fa-sm fa-cookie mr-2', 'emoji' => '🍪'],
        'socials' => ['icon' => 'fab fa-fw fa-sm fa-instagram mr-2', 'emoji' => '🌐'],
        'smtp' => ['icon' => 'fas fa-fw fa-sm fa-mail-bulk mr-2', 'emoji' => '✉️'],
        'email_templates' => ['icon' => 'fas fa-fw fa-sm fa-envelope-open-text mr-2', 'emoji' => '📝'],
        'theme' => ['icon' => 'fas fa-fw fa-sm fa-palette mr-2', 'emoji' => '🎨'],
        'custom' => ['icon' => 'fas fa-fw fa-sm fa-paint-brush mr-2', 'emoji' => '⚙️'],
        'announcements' => ['icon' => 'fas fa-fw fa-sm fa-bullhorn mr-2', 'emoji' => '📣'],
        'internal_notifications' => ['icon' => 'fas fa-fw fa-sm fa-bell mr-2', 'emoji' => '🔔'],
        'email_notifications' => ['icon' => 'fas fa-fw fa-sm fa-envelope mr-2', 'emoji' => '📧'],
        'webhooks' => ['icon' => 'fas fa-fw fa-sm fa-code-branch mr-2', 'emoji' => '🪝']
    ];
    
    foreach($additional_settings as $setting => $config) {
        $items[] = [
            'type' => 'link',
            'url' => url('admin/settings/' . $setting),
            'icon' => $config['icon'],
            'label' => l('admin_settings.' . $setting . '.tab'),
            'active' => $data->method == $setting,
            'mobile_emoji' => $config['emoji']
        ];
    }
    
    // Another divider
    $items[] = ['type' => 'divider'];
    
    // System settings
    $system_settings = [
        'sso' => ['icon' => 'fas fa-fw fa-sm fa-random mr-2', 'emoji' => '🔐'],
        'cron' => ['icon' => 'fas fa-fw fa-sm fa-sync mr-2', 'emoji' => '⏰'],
        'health' => ['icon' => 'fas fa-fw fa-sm fa-heartbeat mr-2', 'emoji' => '💊'],
        'cache' => ['icon' => 'fas fa-fw fa-sm fa-database mr-2', 'emoji' => '🧊']
    ];
    
    foreach($system_settings as $setting => $config) {
        $items[] = [
            'type' => 'link',
            'url' => url('admin/settings/' . $setting),
            'icon' => $config['icon'],
            'label' => l('admin_settings.' . $setting . '.tab'),
            'active' => $data->method == $setting,
            'mobile_emoji' => $config['emoji']
        ];
    }
    
    return [
        'mobile_select_name' => 'settings_menu',
        'mobile_select_class' => 'custom-select',
        'desktop_class' => 'admin-settings-sidebar',
        'items' => $items
    ];
}
?>
