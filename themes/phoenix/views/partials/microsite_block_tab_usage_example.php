<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Example of how to use the reusable microsite block tabs component
 * This file demonstrates the usage pattern for other block types
 */

// Example for CTA Block
$cta_tabs = [
    [
        'id' => 'content',
        'title' => 'Content',
        'icon' => 'fas fa-edit'
    ],
    [
        'id' => 'style',
        'title' => 'Style', 
        'icon' => 'fas fa-palette'
    ],
    [
        'id' => 'display',
        'title' => 'Display',
        'icon' => 'fas fa-eye'
    ]
];

// Example for Review Block
$review_tabs = [
    [
        'id' => 'content',
        'title' => 'Content',
        'icon' => 'fas fa-edit'
    ],
    [
        'id' => 'style',
        'title' => 'Style',
        'icon' => 'fas fa-palette'
    ],
    [
        'id' => 'display',
        'title' => 'Display',
        'icon' => 'fas fa-eye'
    ]
];

// Example for Image Block
$image_tabs = [
    [
        'id' => 'content',
        'title' => 'Content',
        'icon' => 'fas fa-image'
    ],
    [
        'id' => 'style',
        'title' => 'Style',
        'icon' => 'fas fa-palette'
    ],
    [
        'id' => 'display',
        'title' => 'Display',
        'icon' => 'fas fa-eye'
    ]
];

// Usage Pattern:
// 1. Define your tabs array with id, title, and icon
// 2. Set the block_id variable (usually block-type + block-id)
// 3. Include the tab navigation component
// 4. Create tab-content div with matching IDs
// 5. Add tab-pane divs for each tab

/*
Example implementation in a block update form:

<?php
$tabs = [
    ['id' => 'content', 'title' => 'Content', 'icon' => 'fas fa-edit'],
    ['id' => 'style', 'title' => 'Style', 'icon' => 'fas fa-palette'],
    ['id' => 'display', 'title' => 'Display', 'icon' => 'fas fa-eye']
];
$block_id = 'cta-' . $row->microsite_block_id;
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="cta-<?= $row->microsite_block_id ?>-tabContent">
    <div class="tab-pane fade show active" id="cta-<?= $row->microsite_block_id ?>-content" role="tabpanel">
        <!-- Content tab fields -->
    </div>
    <div class="tab-pane fade" id="cta-<?= $row->microsite_block_id ?>-style" role="tabpanel">
        <!-- Style tab fields -->
    </div>
    <div class="tab-pane fade" id="cta-<?= $row->microsite_block_id ?>-display" role="tabpanel">
        <!-- Display tab fields -->
    </div>
</div>

Note: The tabs now display as icon-only with tooltips showing the title on hover.
This creates a clean, minimalistic interface: [📝] [🎨] [👁️]
*/
?>
