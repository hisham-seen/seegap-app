<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Divider Block Form Panel
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
        'icon' => '',
        'show_icon' => false,
        'icon_size' => 20,
        'margin_top' => 0,
        'margin_bottom' => 0,
        'divider_thickness' => 1,
        'divider_style' => 'solid',
        'divider_width' => 100,
        'divider_color' => '#e9ecef',
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
$block_id = 'divider-' . $unique_id;
$tabs = $primary_tabs;
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="divider-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="divider-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="divider-<?= $unique_id ?>-content-tab">
        
        <!-- Show Icon Toggle -->
        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center">
                <label for="<?= 'divider_show_icon_' . $unique_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('microsite_divider.show_icon') ?></label>
                <div class="custom-control custom-switch">
                    <input id="<?= 'divider_show_icon_' . $unique_id ?>" name="show_icon" type="checkbox" class="custom-control-input" <?= ($row->settings->show_icon ?? false) ? 'checked="checked"' : '' ?>>
                    <label class="custom-control-label" for="<?= 'divider_show_icon_' . $unique_id ?>"></label>
                </div>
            </div>
        </div>

        <!-- Icon (only show when toggle is enabled) -->
        <div class="form-group" id="<?= 'divider_icon_container_' . $unique_id ?>" style="<?= ($row->settings->show_icon ?? false) ? '' : 'display: none;' ?>">
            <label for="<?= 'divider_icon_' . $unique_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('microsite_divider.icon') ?></label>
            <input id="<?= 'divider_icon_' . $unique_id ?>" type="text" name="icon" class="form-control" value="<?= $row->settings->icon ?>" placeholder="fas fa-star" />
            <small class="form-text text-muted"><?= l('microsite_divider.icon_help') ?></small>
        </div>

        <!-- Icon Size (only show when toggle is enabled) -->
        <div class="form-group" id="<?= 'divider_icon_size_container_' . $unique_id ?>" style="<?= ($row->settings->show_icon ?? false) ? '' : 'display: none;' ?>" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'divider_icon_size_' . $unique_id ?>"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_divider.icon_size') ?></label>
            <input id="<?= 'divider_icon_size_' . $unique_id ?>" type="range" min="12" max="48" class="form-control-range" name="icon_size" value="<?= $row->settings->icon_size ?? 20 ?>" required="required" />
        </div>

        <!-- Spacing Controls -->
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $spacing_types = ['margin_top', 'margin_bottom'];
        $min_value = 0;
        $max_value = 7;
        $collapsed = false;
        include THEME_PATH . 'views/partials/microsite_block_components/spacing_settings.php';
        ?>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showIconToggle = document.getElementById('<?= 'divider_show_icon_' . $unique_id ?>');
            const iconContainer = document.getElementById('<?= 'divider_icon_container_' . $unique_id ?>');
            const iconSizeContainer = document.getElementById('<?= 'divider_icon_size_container_' . $unique_id ?>');
            
            if (showIconToggle && iconContainer && iconSizeContainer) {
                showIconToggle.addEventListener('change', function() {
                    const display = this.checked ? '' : 'none';
                    iconContainer.style.display = display;
                    iconSizeContainer.style.display = display;
                });
            }
        });
        </script>

    </div>

    <!-- Style Tab -->
    <div class="tab-pane fade" id="divider-<?= $unique_id ?>-style" role="tabpanel" aria-labelledby="divider-<?= $unique_id ?>-style-tab">
        
        <!-- Divider Thickness -->
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'divider_thickness_' . $unique_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_divider.thickness') ?></label>
            <input id="<?= 'divider_thickness_' . $unique_id ?>" type="range" min="1" max="10" class="form-control-range" name="divider_thickness" value="<?= $row->settings->divider_thickness ?? 1 ?>" required="required" />
        </div>

        <!-- Divider Style -->
        <div class="form-group">
            <label for="<?= 'divider_style_' . $unique_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('microsite_divider.style') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <?php foreach(['solid', 'dashed', 'dotted'] as $divider_style): ?>
                    <div class="col-4">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->divider_style ?? 'solid') == $divider_style ? 'active' : '' ?>">
                            <input type="radio" name="divider_style" value="<?= $divider_style ?>" class="custom-control-input" <?= ($row->settings->divider_style ?? 'solid') == $divider_style ? 'checked="checked"' : '' ?> />
                            <?= l('microsite_divider.style_' . $divider_style) ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

        <!-- Divider Width -->
        <div class="form-group" data-range-counter data-range-counter-suffix="%">
            <label for="<?= 'divider_width_' . $unique_id ?>"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('microsite_divider.width') ?></label>
            <input id="<?= 'divider_width_' . $unique_id ?>" type="range" min="10" max="100" step="5" class="form-control-range" name="divider_width" value="<?= $row->settings->divider_width ?? 100 ?>" required="required" />
        </div>

        <!-- Divider Color -->
        <?php
        $block_id = $unique_id;
        $field_name = 'divider_color';
        $label = l('microsite_divider.color');
        $icon = 'fas fa-fill';
        $default_color = '#e9ecef';
        $current_color = $row->settings->divider_color ?? $default_color;
        include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
        ?>

    </div>

    <!-- Display Tab -->
    <div class="tab-pane fade" id="divider-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="divider-<?= $unique_id ?>-display-tab">
        
        <?php 
        $block_id = $unique_id;
        include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; 
        ?>

    </div>

</div>
