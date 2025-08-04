<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_block" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="image_slider" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <?php
    // Define tabs for the Image Slider block
    $tabs = [
        [
            'id' => 'content',
            'title' => 'Content',
            'icon' => 'fas fa-images'
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

    // Set the block_id for the tab component
    $block_id = 'image-slider-' . $row->microsite_block_id;
    
    // Include the reusable tab navigation
    include THEME_PATH . 'views/partials/microsite_block_tabs.php';
    ?>

    <div class="tab-content" id="image-slider-<?= $row->microsite_block_id ?>-tabContent">
        
        <!-- Content Tab -->
        <div class="tab-pane fade show active" id="image-slider-<?= $row->microsite_block_id ?>-content" role="tabpanel" aria-labelledby="image-slider-<?= $row->microsite_block_id ?>-content-tab">
            
            <!-- Image Grid Manager (configured for slider) -->
            <?php
            $block_id = $row->microsite_block_id;
            $settings = $row->settings;
            $whitelisted_extensions = $data->microsite_blocks['image_slider']['whitelisted_image_extensions'];
            $size_limit = settings()->links->image_size_limit;
            $uploads_file_key = 'block_images';
            $show_upload = true;
            $show_grid_settings = false; // Slider doesn't need grid layout settings
            $show_visual_settings = false; // Visual settings are in slider settings
            $max_images = 20; // Sliders typically have fewer images than grids
            include THEME_PATH . 'views/partials/microsite_block_components/image_grid_manager.php';
            ?>

            <!-- Slider Settings -->
            <?php
            $block_id = $row->microsite_block_id;
            $settings = $row->settings;
            $collapsed = false; // Show expanded by default in content tab
            $show_autoplay = true;
            $show_navigation = true;
            $show_visual_settings = true;
            $show_behavior_settings = true;
            include THEME_PATH . 'views/partials/microsite_block_components/slider_settings.php';
            ?>

            <!-- Open in New Tab Setting -->
            <div class="form-group custom-control custom-switch mt-4">
                <input
                    id="open_in_new_tab_<?= $row->microsite_block_id ?>"
                    name="open_in_new_tab" 
                    type="checkbox"
                    class="custom-control-input"
                    <?= ($row->settings->open_in_new_tab ?? false) ? 'checked="checked"' : null ?>
                >
                <label class="custom-control-label" for="open_in_new_tab_<?= $row->microsite_block_id ?>">
                    <i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_link.open_in_new_tab') ?>
                </label>
                <small class="form-text text-muted">
                    <?= l('microsite_image_slider.open_in_new_tab_help') ?? 'Open linked images in a new tab/window' ?>
                </small>
            </div>

        </div>

        <!-- Style Tab -->
        <div class="tab-pane fade" id="image-slider-<?= $row->microsite_block_id ?>-style" role="tabpanel" aria-labelledby="image-slider-<?= $row->microsite_block_id ?>-style-tab">
            
            <?php
            // Set up variables for shared components
            $block_id = $row->microsite_block_id;
            $settings = $row->settings;
            
            // Animation Settings
            include THEME_PATH . 'views/partials/microsite_block_components/animation_settings.php';
            ?>

            <!-- Additional Image Slider Styling Options -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Image Slider Styling:</strong> The main visual styling for your image slider is configured in the Content tab. 
                Use the Style tab for additional animations and effects.
            </div>

        </div>

        <!-- Display Tab -->
        <div class="tab-pane fade" id="image-slider-<?= $row->microsite_block_id ?>-display" role="tabpanel" aria-labelledby="image-slider-<?= $row->microsite_block_id ?>-display-tab">
            
            <?php include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; ?>

        </div>

    </div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
