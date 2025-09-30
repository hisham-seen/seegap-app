<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Admin Statistics Sidebar Configuration Builder
 * Returns the configuration array for the reusable secondary sidebar component
 */

function get_admin_statistics_sidebar_config($data) {
    $items = [];
    
    // Core Statistics
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/growth?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-seedling mr-1',
        'label' => l('admin_statistics.growth.menu'),
        'active' => $data->type == 'growth',
        'mobile_emoji' => '🌱'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/users?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-users mr-1',
        'label' => l('admin_statistics.users.menu'),
        'active' => $data->type == 'users',
        'mobile_emoji' => '👥'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/users_map?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-map mr-1',
        'label' => l('admin_statistics.users_map.menu'),
        'active' => $data->type == 'users_map',
        'mobile_emoji' => '🗺️'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/database?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-database mr-1',
        'label' => l('admin_statistics.database.menu'),
        'active' => $data->type == 'database',
        'mobile_emoji' => '🗄️'
    ];
    
    // Payment & Revenue (if enabled)
    if(in_array(settings()->license->type, ['SPECIAL','Extended License', 'extended'])) {
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/payments?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-credit-card mr-1',
            'label' => l('admin_statistics.payments.menu'),
            'active' => $data->type == 'payments',
            'mobile_emoji' => '💳'
        ];
        
        if(\SeeGap\Plugin::is_active('affiliate')) {
            $items[] = [
                'type' => 'link',
                'url' => url('admin/statistics/affiliates_commissions?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
                'icon' => 'fas fa-fw fa-sm fa-wallet mr-1',
                'label' => l('admin_statistics.affiliates_commissions.menu'),
                'active' => $data->type == 'affiliates_commissions',
                'mobile_emoji' => '💰'
            ];
            
            $items[] = [
                'type' => 'link',
                'url' => url('admin/statistics/affiliates_withdrawals?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
                'icon' => 'fas fa-fw fa-sm fa-wallet mr-1',
                'label' => l('admin_statistics.affiliates_withdrawals.menu'),
                'active' => $data->type == 'affiliates_withdrawals',
                'mobile_emoji' => '💸'
            ];
        }
    }
    
    if(\SeeGap\Plugin::is_active('payment-blocks')) {
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/payment_processors?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-credit-card mr-1',
            'label' => l('admin_payment_processors.menu'),
            'active' => $data->type == 'payment_processors',
            'mobile_emoji' => '🏦'
        ];
        
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/guests_payments?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-coins mr-1',
            'label' => l('admin_guests_payments.menu'),
            'active' => $data->type == 'guests_payments',
            'mobile_emoji' => '🪙'
        ];
    }
    
    // Divider
    $items[] = ['type' => 'divider'];
    
    // Links & Content
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/links?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-link mr-1',
        'label' => l('links.menu.link'),
        'active' => $data->type == 'links',
        'mobile_emoji' => '🔗'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/products?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-box mr-1',
        'label' => l('admin_statistics.products.menu'),
        'active' => $data->type == 'products',
        'mobile_emoji' => '📦'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/microsites?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-hashtag mr-1',
        'label' => l('links.menu.microsite'),
        'active' => $data->type == 'microsites',
        'mobile_emoji' => '#️⃣'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/microsites_blocks?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-th-large mr-1',
        'label' => l('admin_statistics.microsites_blocks.menu'),
        'active' => $data->type == 'microsites_blocks',
        'mobile_emoji' => '🧩'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/qr_codes?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-qrcode mr-1',
        'label' => l('admin_statistics.qr_codes.menu'),
        'active' => $data->type == 'qr_codes',
        'mobile_emoji' => '📱'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/files?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-file mr-1',
        'label' => l('links.menu.file'),
        'active' => $data->type == 'files',
        'mobile_emoji' => '📄'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/static?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-file-code mr-1',
        'label' => l('links.menu.static'),
        'active' => $data->type == 'static',
        'mobile_emoji' => '📝'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/events?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-calendar-alt mr-1',
        'label' => l('links.menu.event'),
        'active' => $data->type == 'events',
        'mobile_emoji' => '📅'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/track_links?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-chart-bar mr-1',
        'label' => l('admin_statistics.track_links.menu'),
        'active' => $data->type == 'track_links',
        'mobile_emoji' => '📈'
    ];
    
    // Another divider
    $items[] = ['type' => 'divider'];
    
    // Infrastructure & Tools
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/domains?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-globe mr-1',
        'label' => l('admin_domains.menu'),
        'active' => $data->type == 'domains',
        'mobile_emoji' => '🌐'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/pixels?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-adjust mr-1',
        'label' => l('admin_pixels.menu'),
        'active' => $data->type == 'pixels',
        'mobile_emoji' => '🎯'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/projects?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-project-diagram mr-1',
        'label' => l('admin_projects.menu'),
        'active' => $data->type == 'projects',
        'mobile_emoji' => '📋'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/splash_pages?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-droplet mr-1',
        'label' => l('admin_statistics.splash_pages.menu'),
        'active' => $data->type == 'splash_pages',
        'mobile_emoji' => '💧'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/data?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-database mr-1',
        'label' => l('admin_data.menu'),
        'active' => $data->type == 'data',
        'mobile_emoji' => '📊'
    ];
    
    // Another divider
    $items[] = ['type' => 'divider'];
    
    // Communication
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/broadcasts?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-mail-bulk mr-1',
        'label' => l('admin_statistics.broadcasts.menu'),
        'active' => $data->type == 'broadcasts',
        'mobile_emoji' => '📢'
    ];
    
    $items[] = [
        'type' => 'link',
        'url' => url('admin/statistics/internal_notifications?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
        'icon' => 'fas fa-fw fa-sm fa-bell mr-1',
        'label' => l('admin_internal_notifications.menu'),
        'active' => $data->type == 'internal_notifications',
        'mobile_emoji' => '🔔'
    ];
    
    // Teams (if enabled)
    if(\SeeGap\Plugin::is_active('teams')) {
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/teams?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-user-shield mr-1',
            'label' => l('admin_teams.menu'),
            'active' => $data->type == 'teams',
            'mobile_emoji' => '👥'
        ];
        
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/teams_members?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-user-tag mr-1',
            'label' => l('admin_statistics.teams_members.menu'),
            'active' => $data->type == 'teams_members',
            'mobile_emoji' => '🏷️'
        ];
    }
    
    // Additional Features
    if(\SeeGap\Plugin::is_active('email-signatures') && settings()->signatures->is_enabled) {
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/signatures?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-file-signature mr-1',
            'label' => l('admin_statistics.signatures.menu'),
            'active' => $data->type == 'signatures',
            'mobile_emoji' => '✍️'
        ];
    }
    
    // AI Features (if enabled)
    if(\SeeGap\Plugin::is_active('aix')) {
        // Another divider for AI section
        $items[] = ['type' => 'divider'];
        
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/documents?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-robot mr-1',
            'label' => l('admin_statistics.documents.menu'),
            'active' => $data->type == 'documents',
            'mobile_emoji' => '🤖'
        ];
        
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/images?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-icons mr-1',
            'label' => l('admin_statistics.images.menu'),
            'active' => $data->type == 'images',
            'mobile_emoji' => '🖼️'
        ];
        
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/transcriptions?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-microphone-alt mr-1',
            'label' => l('admin_statistics.transcriptions.menu'),
            'active' => $data->type == 'transcriptions',
            'mobile_emoji' => '🎤'
        ];
        
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/syntheses?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-voicemail mr-1',
            'label' => l('admin_statistics.syntheses.menu'),
            'active' => $data->type == 'syntheses',
            'mobile_emoji' => '🔊'
        ];
        
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/chats?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-comments mr-1',
            'label' => l('admin_statistics.chats.menu'),
            'active' => $data->type == 'chats',
            'mobile_emoji' => '💬'
        ];
        
        $items[] = [
            'type' => 'link',
            'url' => url('admin/statistics/chats_messages?start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']),
            'icon' => 'fas fa-fw fa-sm fa-comment-dots mr-1',
            'label' => l('admin_statistics.chats_messages.menu'),
            'active' => $data->type == 'chats_messages',
            'mobile_emoji' => '💭'
        ];
    }
    
    return [
        'mobile_select_name' => 'statistics_menu',
        'mobile_select_class' => 'custom-select',
        'desktop_class' => 'admin-statistics-sidebar',
        'items' => $items
    ];
}
?>
