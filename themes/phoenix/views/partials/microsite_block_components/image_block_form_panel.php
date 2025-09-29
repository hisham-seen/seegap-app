<?php defined('SEEGAP') || die() ?>

<?php
$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$form_type = $form_type ?? 'update';
$row = $row ?? (object)['microsite_block_id' => $block_id, 'settings' => $settings];

// Set up default settings for create form
if ($form_type === 'create') {
    $default_settings = (object) [
        'image' => '',
        'image_alt' => '',
        'image_height' => '',
        'image_height_unit' => 'px',
        'image_width' => '',
        'image_width_unit' => 'px',
        'location_url' => '',
        'open_in_new_tab' => false,
        'text_alignment' => 'center',
        'background_color' => '#00000000',
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
        'use_product_images' => false,
        'product_image_selections' => [],
        'verified_badge' => (object) [
            'enabled' => false,
            'style' => 'checkmark',
            'position' => 'bottom_right',
            'size' => 'medium',
            'color' => '#1da1f2'
        ]
    ];
    
    foreach ($default_settings as $key => $value) {
        if (!isset($settings->$key)) {
            $settings->$key = $value;
        }
    }
    $row->settings = $settings;
}

$unique_id = $form_type === 'create' ? 'create' : $row->microsite_block_id;

// Define tabs for the image block
$tabs = [
    [
        'id' => 'content',
        'title' => 'Content',
        'icon' => 'fas fa-edit'
    ],
    [
        'id' => 'data',
        'title' => 'Data',
        'icon' => 'fas fa-database'
    ],
    [
        'id' => 'style',
        'title' => 'Style',
        'icon' => 'fas fa-palette'
    ],
    [
        'id' => 'badge',
        'title' => 'Badge',
        'icon' => 'fas fa-certificate'
    ],
    [
        'id' => 'display',
        'title' => 'Display',
        'icon' => 'fas fa-eye'
    ],
    [
        'id' => 'destination',
        'title' => 'Destination',
        'icon' => 'fas fa-link'
    ]
];

$primary_tab_block_id = 'image-' . $unique_id;
$primary_tabs = $tabs;
$block_id = $primary_tab_block_id;
$tabs = $primary_tabs;

include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="image-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="image-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-content-tab">
        <?php if ($form_type === 'create'): ?>
            <div class="form-group">
                <label for="<?= 'image_image_' . $unique_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('global.image') ?></label>
                <input id="<?= 'image_image_' . $unique_id ?>" type="file" name="image" accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image']['whitelisted_image_extensions'] ?? ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']) ?>" class="form-control-file seegap-file-input" required="required" data-crop />
                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image']['whitelisted_image_extensions'] ?? ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?></small>
            </div>
        <?php else: ?>
            <?php
            $block_id = $unique_id;
            $field_name = 'image';
            $current_image = $row->settings->image ?? '';
            $accept_types = $data->microsite_blocks['image']['whitelisted_image_extensions'] ?? ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'];
            $label = l('global.image');
            $icon = 'fas fa-image';
            $uploads_file_key = 'block_images';
            $size_limit_setting = settings()->links->image_size_limit;
            $enable_crop = true;
            include THEME_PATH . 'views/partials/microsite_block_components/advanced_image_upload.php';
            ?>
        <?php endif; ?>

        <div class="form-group">
            <label for="<?= 'image_image_alt_' . $unique_id ?>"><i class="fas fa-fw fa-comment-dots fa-sm text-muted mr-1"></i> <?= l('microsite_link.image_alt') ?></label>
            <input id="<?= 'image_image_alt_' . $unique_id ?>" type="text" class="form-control" name="image_alt" value="<?= $row->settings->image_alt ?? '' ?>" maxlength="100" />
            <small class="form-text text-muted"><?= l('microsite_link.image_alt_help') ?></small>
        </div>
    </div>

    <!-- Data Tab -->
    <div class="tab-pane fade" id="image-<?= $unique_id ?>-data" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-data-tab">
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        include THEME_PATH . 'views/partials/microsite_block_components/product_data_settings.php';
        ?>
    </div>

    <!-- Destination Tab -->
    <div class="tab-pane fade" id="image-<?= $unique_id ?>-destination" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-destination-tab">
        <div class="form-group">
            <label for="<?= 'destination_location_url_' . $unique_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_link.location_url') ?></label>
            <input id="<?= 'destination_location_url_' . $unique_id ?>" type="url" class="form-control" name="location_url" value="<?= $row->settings->location_url ?? '' ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('microsite_link.location_url_help') ?? 'Enter the URL where users will be redirected when they click this block' ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input
                id="<?= 'open_in_new_tab_' . $unique_id ?>"
                name="open_in_new_tab" 
                type="checkbox"
                class="custom-control-input"
                <?= ($row->settings->open_in_new_tab ?? false) ? 'checked="checked"' : null ?>
            >
            <label class="custom-control-label" for="<?= 'open_in_new_tab_' . $unique_id ?>"><?= l('microsite_link.open_in_new_tab') ?></label>
            <small class="form-text text-muted"><?= l('microsite_link.open_in_new_tab_help') ?></small>
        </div>
    </div>

    <!-- Style Tab -->
    <div class="tab-pane fade" id="image-<?= $unique_id ?>-style" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-style-tab">
        <?php
        $style_tabs = [
            [
                'id' => 'sizing',
                'title' => 'Sizing',
                'icon' => 'fas fa-expand-arrows-alt'
            ],
            [
                'id' => 'background',
                'title' => 'Background',
                'icon' => 'fas fa-fill'
            ],
            [
                'id' => 'border',
                'title' => 'Border',
                'icon' => 'fas fa-border-style'
            ],
            [
                'id' => 'shadow',
                'title' => 'Shadow',
                'icon' => 'fas fa-clone'
            ],
            [
                'id' => 'animation',
                'title' => 'Animation',
                'icon' => 'fas fa-film'
            ]
        ];

        $secondary_block_id = 'image-style-' . $unique_id;
        $tabs = $style_tabs;
        $block_id = $secondary_block_id;
        
        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
        ?>

        <div class="tab-content" id="image-style-<?= $unique_id ?>-tabContent">
            <!-- Sizing Sub-tab -->
            <div class="tab-pane fade show active" id="image-style-<?= $unique_id ?>-sizing" role="tabpanel" aria-labelledby="image-style-<?= $unique_id ?>-sizing-tab">
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $field_name = 'text_alignment';
                $label = l('microsite_link.text_alignment');
                $icon = 'fas fa-align-center';
                include THEME_PATH . 'views/partials/microsite_block_components/alignment.php';

                $block_id = $unique_id;
                $settings = $row->settings;
                $dimensions = ['height', 'width'];
                include THEME_PATH . 'views/partials/microsite_block_components/image_sizing.php';
                ?>
            </div>

            <!-- Background Sub-tab -->
            <div class="tab-pane fade" id="image-style-<?= $unique_id ?>-background" role="tabpanel" aria-labelledby="image-style-<?= $unique_id ?>-background-tab">
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false;
                include THEME_PATH . 'views/partials/microsite_block_components/background_settings.php';
                ?>
            </div>

            <!-- Border Sub-tab -->
            <div class="tab-pane fade" id="image-style-<?= $unique_id ?>-border" role="tabpanel" aria-labelledby="image-style-<?= $unique_id ?>-border-tab">
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false;
                include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                ?>
            </div>

            <!-- Shadow Sub-tab -->
            <div class="tab-pane fade" id="image-style-<?= $unique_id ?>-shadow" role="tabpanel" aria-labelledby="image-style-<?= $unique_id ?>-shadow-tab">
                <?php
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false;
                include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                ?>
            </div>

            <!-- Animation Sub-tab -->
            <div class="tab-pane fade" id="image-style-<?= $unique_id ?>-animation" role="tabpanel" aria-labelledby="image-style-<?= $unique_id ?>-animation-tab">
                <?php
                $component_block_id = $unique_id;
                $component_settings = $row->settings;
                ?>
                <div class="form-group">
                    <label for="<?= 'animation_' . $component_block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation') ?></label>
                    <select id="<?= 'animation_' . $component_block_id ?>" name="animation" class="form-control" onchange="updateCanvasAnimation('<?= $component_block_id ?>')">
                        <option value="false" <?= (!isset($component_settings->animation) || !$component_settings->animation) ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                        <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                            <option value="<?= $animation ?>" <?= (isset($component_settings->animation) && $component_settings->animation == $animation) ? 'selected="selected"' : null ?>><?= l('microsite_animations.' . $animation) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="<?= 'animation_runs_' . $component_block_id ?>"><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
                    <select id="<?= 'animation_runs_' . $component_block_id ?>" name="animation_runs" class="form-control" onchange="updateCanvasAnimation('<?= $component_block_id ?>')">
                        <option value="repeat-1" <?= (!isset($component_settings->animation_runs) || $component_settings->animation_runs == 'repeat-1') ? 'selected="selected"' : null ?>>1</option>
                        <option value="repeat-2" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'repeat-2') ? 'selected="selected"' : null ?>>2</option>
                        <option value="repeat-3" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'repeat-3') ? 'selected="selected"' : null ?>>3</option>
                        <option value="infinite" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'infinite') ? 'selected="selected"' : null ?>><?= l('global.infinite') ?></option>
                    </select>
                </div>

                <div class="form-group" data-range-counter data-range-counter-suffix="ms">
                    <label for="<?= 'animation_delay_' . $component_block_id ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_delay') ?></label>
                    <input id="<?= 'animation_delay_' . $component_block_id ?>" type="range" min="0" max="5000" step="100" class="form-control-range" name="animation_delay" value="<?= $component_settings->animation_delay ?? 0 ?>" required="required" onchange="updateCanvasAnimation('<?= $component_block_id ?>')" oninput="updateCanvasAnimation('<?= $component_block_id ?>')" />
                </div>
            </div>
        </div>
    </div>

    <!-- Badge Tab -->
    <div class="tab-pane fade" id="image-<?= $unique_id ?>-badge" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-badge-tab">
        <?php
        $block_id = $unique_id;
        $settings = $row->settings;
        $field_prefix = 'verified_badge';
        include THEME_PATH . 'views/partials/microsite_block_components/badge_selector.php';
        ?>
    </div>

    <!-- Display Tab -->
    <div class="tab-pane fade" id="image-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="image-<?= $unique_id ?>-display-tab">
        <?php 
        if ($form_type === 'create') {
            $display_row = (object) [
                'microsite_block_id' => $unique_id,
                'settings' => (object) [
                    'display_continents' => [],
                    'display_countries' => [],
                    'display_cities' => [],
                    'display_devices' => [],
                    'display_languages' => [],
                    'display_operating_systems' => [],
                    'display_browsers' => []
                ],
                'start_date' => null,
                'end_date' => null
            ];
        } else {
            $display_row = $row;
        }
        
        $original_row = isset($row) ? $row : null;
        $row = $display_row;
        include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php';
        $row = $original_row;
        ?>
    </div>
</div>

<script>
window.updateCanvasAnimation = function(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            const animation = $(`#animation_${blockId}`).val() || 'false';
            const runs = $(`#animation_runs_${blockId}`).val() || 'repeat-1';
            const delay = $(`#animation_delay_${blockId}`).val() || 0;
            
            let element = microsite_link.find('.card');
            if (!element.length) {
                element = microsite_link.find('img');
            }
            if (!element.length) {
                element = microsite_link;
            }
            
            if (element.length) {
                const animateClasses = [
                    'animate__animated', 'animate__bounce', 'animate__flash', 'animate__pulse', 
                    'animate__rubberBand', 'animate__shakeX', 'animate__shakeY', 'animate__headShake',
                    'animate__swing', 'animate__tada', 'animate__wobble', 'animate__jello',
                    'animate__heartBeat', 'animate__backInDown', 'animate__backInLeft',
                    'animate__backInRight', 'animate__backInUp', 'animate__bounceIn',
                    'animate__bounceInDown', 'animate__bounceInLeft', 'animate__bounceInRight',
                    'animate__bounceInUp', 'animate__fadeIn', 'animate__fadeInDown',
                    'animate__fadeInDownBig', 'animate__fadeInLeft', 'animate__fadeInLeftBig',
                    'animate__fadeInRight', 'animate__fadeInRightBig', 'animate__fadeInUp',
                    'animate__fadeInUpBig', 'animate__fadeInTopLeft', 'animate__fadeInTopRight',
                    'animate__fadeInBottomLeft', 'animate__fadeInBottomRight', 'animate__flip',
                    'animate__flipInX', 'animate__flipInY', 'animate__lightSpeedIn',
                    'animate__lightSpeedInRight', 'animate__lightSpeedInLeft', 'animate__rotateIn',
                    'animate__rotateInDownLeft', 'animate__rotateInDownRight', 'animate__rotateInUpLeft',
                    'animate__rotateInUpRight', 'animate__jackInTheBox', 'animate__rollIn',
                    'animate__zoomIn', 'animate__zoomInDown', 'animate__zoomInLeft',
                    'animate__zoomInRight', 'animate__zoomInUp', 'animate__slideInDown',
                    'animate__slideInLeft', 'animate__slideInRight', 'animate__slideInUp',
                    'animate__repeat-1', 'animate__repeat-2', 'animate__repeat-3', 'animate__infinite'
                ];
                
                element.removeClass(animateClasses.join(' '));
                
                if (animation !== 'false' && animation !== '') {
                    element.addClass('animate__animated');
                    element.addClass(`animate__${animation}`);
                    
                    if (runs && runs !== 'repeat-1') {
                        element.addClass(`animate__${runs}`);
                    }
                    
                    const delayMs = parseInt(delay) || 0;
                    element.css('animation-delay', `${delayMs}ms`);
                    
                    element[0].offsetHeight;
                    
                    setTimeout(() => {
                        element.removeClass('animate__animated');
                        element[0].offsetHeight;
                        setTimeout(() => {
                            element.addClass('animate__animated');
                        }, 50);
                    }, 50);
                }
            }
        }
    }
};
</script>
