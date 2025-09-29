<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Product Update Secondary Sidebar Configuration
 * GS1 Ireland/EU Compliant Product Management Sections
 */

// Get current section from data or default to general
$current_section = isset($data) && isset($data->section) ? $data->section : 'general';
$product_id = isset($data) && isset($data->product) ? $data->product->product_id : 1;

return [
    'mobile_select_name' => 'product_settings_menu',
    'mobile_select_class' => 'custom-select',
    'desktop_class' => 'product-settings-sidebar',
    'items' => [
        [
            'type' => 'link',
            'url' => url('product-update/' . $product_id . '/general'),
            'icon' => 'fas fa-fw fa-sm fa-info-circle mr-2',
            'label' => l('products.sections.general'),
            'active' => $current_section == 'general',
            'mobile_emoji' => '📋'
        ],
        [
            'type' => 'link',
            'url' => url('product-update/' . $product_id . '/gs1-identifiers'),
            'icon' => 'fas fa-fw fa-sm fa-barcode mr-2',
            'label' => l('products.sections.gs1_identifiers'),
            'active' => $current_section == 'gs1-identifiers',
            'mobile_emoji' => '🏷️'
        ],
        [
            'type' => 'link',
            'url' => url('product-update/' . $product_id . '/attributes'),
            'icon' => 'fas fa-fw fa-sm fa-tags mr-2',
            'label' => l('products.sections.gs1_attributes'),
            'active' => $current_section == 'attributes',
            'mobile_emoji' => '🏭'
        ],
        [
            'type' => 'divider'
        ],
        [
            'type' => 'link',
            'url' => url('product-update/' . $product_id . '/measurements'),
            'icon' => 'fas fa-fw fa-sm fa-ruler-combined mr-2',
            'label' => l('products.sections.gs1_measurements'),
            'active' => $current_section == 'measurements',
            'mobile_emoji' => '📏'
        ],
        [
            'type' => 'link',
            'url' => url('product-update/' . $product_id . '/logistics'),
            'icon' => 'fas fa-fw fa-sm fa-shipping-fast mr-2',
            'label' => l('products.sections.gs1_logistics'),
            'active' => $current_section == 'logistics',
            'mobile_emoji' => '🚚'
        ],
        [
            'type' => 'divider'
        ],
        [
            'type' => 'link',
            'url' => url('product-update/' . $product_id . '/content'),
            'icon' => 'fas fa-fw fa-sm fa-list-ul mr-2',
            'label' => l('products.sections.content_compliance'),
            'active' => $current_section == 'content',
            'mobile_emoji' => '📝'
        ],
        [
            'type' => 'link',
            'url' => url('product-update/' . $product_id . '/digital'),
            'icon' => 'fas fa-fw fa-sm fa-link mr-2',
            'label' => l('products.sections.digital_integration'),
            'active' => $current_section == 'digital',
            'mobile_emoji' => '🔗'
        ],
        [
            'type' => 'link',
            'url' => url('product-update/' . $product_id . '/media'),
            'icon' => 'fas fa-fw fa-sm fa-images mr-2',
            'label' => l('products.sections.media_images'),
            'active' => $current_section == 'media',
            'mobile_emoji' => '🖼️'
        ]
    ]
];
?>
