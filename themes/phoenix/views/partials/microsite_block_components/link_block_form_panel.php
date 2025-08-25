<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Link Block Form Panel
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object with default values
 * @param object $row - Block row data (for update) or mock object (for create)
 * @param string $form_type - 'create' or 'update'
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$form_type = $form_type ?? 'update';
$row = $row ?? (object)['microsite_block_id' => $block_id, 'settings' => $settings];

// Set up default settings for create form
if ($form_type === 'create') {
    $default_settings = (object) [
        'name' => '',
        'location_url' => '',
        'open_in_new_tab' => false,
        'image' => '',
        'icon' => '',
        'text_color' => '#333333',
        'background_color' => '#ffffff',
        'text_alignment' => 'center',
        'border_width' => 0,
        'border_color' => '#ffffff',
        'border_radius' => 'rounded',
        'border_style' => 'solid',
        'border_shadow_offset_x' => 0,
        'border_shadow_offset_y' => 0,
        'border_shadow_blur' => 0,
        'border_shadow_spread' => 0,
        'border_shadow_color' => '#00000010',
        'animation' => false,
        'animation_runs' => 'repeat-1',
        'animation_delay' => 0,
        'display_continents' => [],
        'display_countries' => [],
        'display_cities' => [],
        'display_devices' => [],
        'display_languages' => [],
        'display_operating_systems' => [],
        'display_browsers' => [],
    ];
    
    foreach ($default_settings as $key => $value) {
        if (!isset($settings->$key)) {
            $settings->$key = $value;
        }
    }
    $row->settings = $settings;
}

$unique_id = $form_type === 'create' ? 'create' : $row->microsite_block_id;

// Define primary tabs
$primary_tabs = [
    ['id' => 'content', 'title' => 'Content', 'icon' => 'fas fa-edit'],
    ['id' => 'style', 'title' => 'Style', 'icon' => 'fas fa-palette'],
    ['id' => 'display', 'title' => 'Display', 'icon' => 'fas fa-eye']
];
?>

<!-- Primary Tab Navigation -->
<?php
$block_id = 'link-' . $unique_id;
$tabs = $primary_tabs;
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="link-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="link-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="link-<?= $unique_id ?>-content-tab">
        
        <!-- Link Settings -->
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $url_field = 'location_url';
        $new_tab_field = 'open_in_new_tab';
        $url_label = l('microsite_link.location_url');
        $new_tab_label = l('microsite_link.open_in_new_tab');
        include THEME_PATH . 'views/partials/microsite_block_components/link_settings.php';
        ?>

        <!-- Link Name -->
        <div class="form-group">
            <label for="<?= 'link_name_' . $unique_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_link.name') ?></label>
            <input id="<?= 'link_name_' . $unique_id ?>" type="text" name="name" class="form-control" value="<?= $row->settings->name ?>" maxlength="128" required="required" />
        </div>

        <!-- Icon -->
        <div class="form-group">
            <label for="<?= 'link_icon_' . $unique_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('global.icon') ?></label>
            <input id="<?= 'link_icon_' . $unique_id ?>" type="text" name="icon" class="form-control" value="<?= $row->settings->icon ?>" placeholder="<?= l('global.icon_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
        </div>

    </div>

    <!-- Style Tab -->
    <div class="tab-pane fade" id="link-<?= $unique_id ?>-style" role="tabpanel" aria-labelledby="link-<?= $unique_id ?>-style-tab">
        
        <?php
        // Define style sub-tabs
        $style_tabs = [
            ['id' => 'text-styling', 'title' => 'Text', 'icon' => 'fas fa-font'],
            ['id' => 'background', 'title' => 'Background', 'icon' => 'fas fa-fill'],
            ['id' => 'border', 'title' => 'Border', 'icon' => 'fas fa-border-style'],
            ['id' => 'shadow', 'title' => 'Shadow', 'icon' => 'fas fa-clone'],
            ['id' => 'animation', 'title' => 'Animation', 'icon' => 'fas fa-film']
        ];
        
        // Secondary Tab Navigation
        $block_id = 'link-style-' . $unique_id;
        $tabs = $style_tabs;
        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
        ?>

        <div class="tab-content" id="link-style-<?= $unique_id ?>-tabContent">
            
            <!-- Text Styling Sub-tab -->
            <div class="tab-pane fade show active" id="link-style-<?= $unique_id ?>-text-styling" role="tabpanel" aria-labelledby="link-style-<?= $unique_id ?>-text-styling-tab">
                
                <?php
                // Set up variables for shared components
                $block_id = $unique_id;
                $settings = $row->settings;
                
                // Text Color
                $field_name = 'text_color';
                $label = l('microsite_link.text_color');
                $icon = 'fas fa-paint-brush';
                $default_color = '#333333';
                $current_color = $row->settings->text_color ?? $default_color;
                include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                
                // Text Alignment
                $field_name = 'text_alignment';
                $label = l('microsite_link.alignment') ?? 'Alignment';
                $icon = 'fas fa-align-center';
                $alignment_options = ['center', 'justify', 'left', 'right'];
                $default_alignment = 'center';
                include THEME_PATH . 'views/partials/microsite_block_components/alignment.php';
                ?>

            </div>

            <!-- Background Sub-tab -->
            <div class="tab-pane fade" id="link-style-<?= $unique_id ?>-background" role="tabpanel" aria-labelledby="link-style-<?= $unique_id ?>-background-tab">
                
                <?php
                // Set up variables for background component (without accordion) - same as text/image blocks
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/background_settings.php';
                ?>

            </div>

            <!-- Border Sub-tab -->
            <div class="tab-pane fade" id="link-style-<?= $unique_id ?>-border" role="tabpanel" aria-labelledby="link-style-<?= $unique_id ?>-border-tab">
                
                <?php
                // Set up variables for border component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                ?>

            </div>

            <!-- Shadow Sub-tab -->
            <div class="tab-pane fade" id="link-style-<?= $unique_id ?>-shadow" role="tabpanel" aria-labelledby="link-style-<?= $unique_id ?>-shadow-tab">
                
                <?php
                // Set up variables for shadow component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                ?>

            </div>

            <!-- Animation Sub-tab -->
            <div class="tab-pane fade" id="link-style-<?= $unique_id ?>-animation" role="tabpanel" aria-labelledby="link-style-<?= $unique_id ?>-animation-tab">
                
                <?php
                // Set up variables for animation component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/animation_settings.php';
                ?>

            </div>

        </div>

    </div>

    <!-- Display Tab -->
    <div class="tab-pane fade" id="link-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="link-<?= $unique_id ?>-display-tab">
        
        <?php 
        $block_id = $unique_id;
        include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; 
        ?>

    </div>

</div>
