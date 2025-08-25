<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_block" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="review" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <?php
    // Define primary tabs for the Review block
    $primary_tabs = [
        [
            'id' => 'content',
            'title' => 'Content',
            'icon' => 'fas fa-edit'
        ],
        [
            'id' => 'design',
            'title' => 'Design',
            'icon' => 'fas fa-palette'
        ],
        [
            'id' => 'display',
            'title' => 'Display',
            'icon' => 'fas fa-eye'
        ]
    ];

    // Set the block_id for the primary tab component
    $primary_tab_block_id = 'review-' . $row->microsite_block_id;
    $tabs = $primary_tabs; // Store primary tabs
    $block_id = $primary_tab_block_id;
    
    // Include the reusable tab navigation
    include THEME_PATH . 'views/partials/microsite_block_tabs.php';
    ?>

    <div class="tab-content" id="review-<?= $row->microsite_block_id ?>-tabContent">
        
        <!-- Content Tab -->
        <div class="tab-pane fade show active" id="review-<?= $row->microsite_block_id ?>-content" role="tabpanel" aria-labelledby="review-<?= $row->microsite_block_id ?>-content-tab">
            
            <!-- Slider Behavior Settings -->
            <div class="form-group">
                <label><i class="fas fa-fw fa-sliders-h fa-sm text-muted mr-1"></i> Slider Behavior</label>
                <div class="card">
                    <div class="card-body">
                        <!-- Slider Mode -->
                        <div class="form-group">
                            <label><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> Slider Mode</label>
                            <div class="row btn-group-toggle" data-toggle="buttons">
                                <div class="col-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->slider_mode ?? 'manual') == 'manual' ? 'active' : '' ?>">
                                        <input type="radio" name="slider_mode" value="manual" class="custom-control-input" <?= ($row->settings->slider_mode ?? 'manual') == 'manual' ? 'checked="checked"' : '' ?> />
                                        <i class="fas fa-fw fa-hand-pointer fa-sm mr-1"></i> Manual Navigation
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->slider_mode ?? 'manual') == 'auto' ? 'active' : '' ?>">
                                        <input type="radio" name="slider_mode" value="auto" class="custom-control-input" <?= ($row->settings->slider_mode ?? 'manual') == 'auto' ? 'checked="checked"' : '' ?> />
                                        <i class="fas fa-fw fa-play-circle fa-sm mr-1"></i> Auto Play
                                    </label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Manual: Users navigate with arrows/dots. Auto: Reviews cycle automatically.</small>
                        </div>

                        <!-- Auto Play Settings -->
                        <div class="form-group auto-play-settings" style="display: <?= ($row->settings->slider_mode ?? 'manual') == 'auto' ? 'block' : 'none' ?>;">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="auto_play" value="1" class="custom-control-input" id="auto_play_<?= $row->microsite_block_id ?>" <?= isset($row->settings->auto_play) && $row->settings->auto_play ? 'checked' : '' ?> />
                                <label class="custom-control-label" for="auto_play_<?= $row->microsite_block_id ?>">
                                    <i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> Enable Auto Play
                                </label>
                            </div>
                        </div>

                        <!-- Slide Duration -->
                        <div class="form-group auto-play-settings" style="display: <?= ($row->settings->slider_mode ?? 'manual') == 'auto' ? 'block' : 'none' ?>;" data-range-counter data-range-counter-suffix="s">
                            <label><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> Slide Duration</label>
                            <input type="range" min="3" max="10" step="1" class="form-control-range" name="slide_duration" value="<?= $row->settings->slide_duration ?? 5 ?>" required="required" />
                            <small class="form-text text-muted">How long each review is displayed (3-10 seconds).</small>
                        </div>

                        <!-- Navigation Controls -->
                        <div class="form-group">
                            <label><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> Navigation Controls</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="show_navigation" value="1" class="custom-control-input" id="show_navigation_<?= $row->microsite_block_id ?>" <?= isset($row->settings->show_navigation) && $row->settings->show_navigation ? 'checked' : '' ?> />
                                        <label class="custom-control-label" for="show_navigation_<?= $row->microsite_block_id ?>">
                                            <i class="fas fa-fw fa-chevron-left fa-sm mr-1"></i> Show Arrows
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="show_indicators" value="1" class="custom-control-input" id="show_indicators_<?= $row->microsite_block_id ?>" <?= isset($row->settings->show_indicators) && $row->settings->show_indicators ? 'checked' : '' ?> />
                                        <label class="custom-control-label" for="show_indicators_<?= $row->microsite_block_id ?>">
                                            <i class="fas fa-fw fa-circle fa-sm mr-1"></i> Show Dots
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transition Effect -->
                        <div class="form-group">
                            <label><i class="fas fa-fw fa-magic fa-sm text-muted mr-1"></i> Transition Effect</label>
                            <div class="row btn-group-toggle" data-toggle="buttons">
                                <div class="col-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->transition_effect ?? 'slide') == 'slide' ? 'active' : '' ?>">
                                        <input type="radio" name="transition_effect" value="slide" class="custom-control-input" <?= ($row->settings->transition_effect ?? 'slide') == 'slide' ? 'checked="checked"' : '' ?> />
                                        <i class="fas fa-fw fa-arrows-alt-h fa-sm mr-1"></i> Slide
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->transition_effect ?? 'slide') == 'fade' ? 'active' : '' ?>">
                                        <input type="radio" name="transition_effect" value="fade" class="custom-control-input" <?= ($row->settings->transition_effect ?? 'slide') == 'fade' ? 'checked="checked"' : '' ?> />
                                        <i class="fas fa-fw fa-adjust fa-sm mr-1"></i> Fade
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Multiple Reviews Management -->
            <div class="form-group">
                <label><i class="fas fa-fw fa-star fa-sm text-muted mr-1"></i> Reviews</label>
                <div id="reviews_<?= $row->microsite_block_id ?>" data-microsite-block-id="<?= $row->microsite_block_id ?>">
                    <?php 
                    // Handle backward compatibility - convert old single review to array format
                    $reviews = [];
                    if (isset($row->settings->reviews)) {
                        if (is_array($row->settings->reviews)) {
                            $reviews = $row->settings->reviews;
                        } else {
                            // Convert object reviews to array format
                            $reviews = [];
                            foreach ($row->settings->reviews as $review) {
                                if (is_object($review)) {
                                    $reviews[] = [
                                        'title' => $review->title ?? '',
                                        'description' => $review->description ?? '',
                                        'author_name' => $review->author_name ?? '',
                                        'author_description' => $review->author_description ?? '',
                                        'stars' => $review->stars ?? 5,
                                        'image' => $review->image ?? ''
                                    ];
                                } else {
                                    $reviews[] = $review;
                                }
                            }
                        }
                    } elseif (isset($row->settings->title) || isset($row->settings->author_name)) {
                        // Convert old single review format to new array format
                        $reviews = [[
                            'title' => $row->settings->title ?? '',
                            'description' => $row->settings->description ?? '',
                            'author_name' => $row->settings->author_name ?? '',
                            'author_description' => $row->settings->author_description ?? '',
                            'stars' => $row->settings->stars ?? 5,
                            'image' => $row->settings->image ?? ''
                        ]];
                    }
                    
                    if (empty($reviews)) {
                        $reviews = [[
                            'title' => '',
                            'description' => '',
                            'author_name' => '',
                            'author_description' => '',
                            'stars' => 5,
                            'image' => ''
                        ]];
                    }
                    
                    // Ensure all reviews are arrays, not objects
                    foreach ($reviews as $key => $review) {
                        if (is_object($review)) {
                            $reviews[$key] = [
                                'title' => $review->title ?? '',
                                'description' => $review->description ?? '',
                                'author_name' => $review->author_name ?? '',
                                'author_description' => $review->author_description ?? '',
                                'stars' => $review->stars ?? 5,
                                'image' => $review->image ?? ''
                            ];
                        }
                    }
                    ?>
                    
                    <?php foreach($reviews as $key => $review): ?>
                        <div class="review-item-wrapper mb-3 border rounded" data-review-item>
                            <!-- Drag Handle and Header -->
                            <div class="review-item-header d-flex align-items-center justify-content-between p-3 bg-light border-bottom" style="cursor: pointer;" data-toggle="collapse" data-target="#review-item-content-<?= $row->microsite_block_id ?>-<?= $key ?>">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;" title="Drag to reorder"></i>
                                    <i class="fas fa-chevron-down review-toggle-icon mr-2 text-muted"></i>
                                    <span class="review-item-title font-weight-medium"><?= htmlspecialchars($review['author_name'] ?? 'New Review') ?></span>
                                    <div class="ml-2">
                                        <?php for($i = 1; $i <= ($review['stars'] ?? 5); $i++): ?>
                                            <i class="fas fa-star text-warning" style="font-size: 0.75rem;"></i>
                                        <?php endfor ?>
                                    </div>
                                </div>
                                <button type="button" data-remove="item" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();">
                                    <i class="fas fa-fw fa-times"></i>
                                </button>
                            </div>

                            <!-- Collapsible Content -->
                            <div id="review-item-content-<?= $row->microsite_block_id ?>-<?= $key ?>" class="collapse <?= $key === 0 ? 'show' : '' ?>">
                                <div class="p-3">
                                    <!-- Review Title -->
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> Review Title</label>
                                        <input type="text" name="review_title[<?= $key ?>]" class="form-control review-title-input" value="<?= htmlspecialchars($review['title'] ?? '') ?>" placeholder="Enter review title..." maxlength="128" />
                                    </div>

                                    <!-- Review Description -->
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> Review Description</label>
                                        <textarea name="review_description[<?= $key ?>]" class="form-control" rows="3" placeholder="Enter review description..." maxlength="1024"><?= $review['description'] ?? '' ?></textarea>
                                    </div>

                                    <!-- Author Name -->
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-user fa-sm text-muted mr-1"></i> Author Name</label>
                                        <input type="text" name="review_author_name[<?= $key ?>]" class="form-control review-author-input" value="<?= htmlspecialchars($review['author_name'] ?? '') ?>" placeholder="Enter author name..." maxlength="128" required />
                                    </div>

                                    <!-- Author Description -->
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-user-tag fa-sm text-muted mr-1"></i> Author Description</label>
                                        <input type="text" name="review_author_description[<?= $key ?>]" class="form-control" value="<?= htmlspecialchars($review['author_description'] ?? '') ?>" placeholder="e.g., Verified Customer, Premium Member..." maxlength="128" />
                                    </div>

                                    <!-- Star Rating -->
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-star fa-sm text-muted mr-1"></i> Star Rating</label>
                                        <div class="star-rating-input" data-rating="<?= $review['stars'] ?? 5 ?>">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star star-input <?= $i <= ($review['stars'] ?? 5) ? 'active' : '' ?>" data-rating="<?= $i ?>"></i>
                                            <?php endfor ?>
                                            <input type="hidden" name="review_stars[<?= $key ?>]" value="<?= $review['stars'] ?? 5 ?>" />
                                        </div>
                                    </div>

                                    <!-- Author Image -->
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> Author Image</label>
                                        <div class="custom-file">
                                            <input type="file" name="review_image[<?= $key ?>]" class="custom-file-input" accept="image/*" />
                                            <label class="custom-file-label">Choose author image...</label>
                                        </div>
                                        <?php if(!empty($review['image'])): ?>
                                            <div class="mt-2">
                                                <img src="<?= \SeeGap\Uploads::get_full_url('block_images') . $review['image'] ?>" class="img-thumbnail" style="max-width: 60px; max-height: 60px;" />
                                                <small class="text-muted d-block">Current image</small>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <button data-add="review_item" data-microsite-block-id="<?= $row->microsite_block_id ?>" type="button" class="btn btn-outline-success btn-block mt-3">
                    <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> Add Review
                </button>
                <small class="form-text text-muted">Create multiple customer reviews to showcase as a slider. You can add up to 20 reviews.</small>
            </div>

        </div>

        <!-- Design Tab -->
        <div class="tab-pane fade" id="review-<?= $row->microsite_block_id ?>-design" role="tabpanel" aria-labelledby="review-<?= $row->microsite_block_id ?>-design-tab">
            
            <?php
            // Define secondary tabs for the design section
            $design_tabs = [
                [
                    'id' => 'colors',
                    'title' => 'Colors',
                    'icon' => 'fas fa-palette'
                ],
                [
                    'id' => 'layout',
                    'title' => 'Layout',
                    'icon' => 'fas fa-align-center'
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

            // Set the block_id for the secondary tab component
            $secondary_block_id = 'review-design-' . $row->microsite_block_id;
            $tabs = $design_tabs; // Use design tabs for the secondary navigation
            $block_id = $secondary_block_id; // Override block_id for secondary tabs
            
            // Include the reusable tab navigation for secondary tabs
            include THEME_PATH . 'views/partials/microsite_block_tabs.php';
            ?>

            <div class="tab-content" id="review-design-<?= $row->microsite_block_id ?>-tabContent">
                
                <!-- Colors Sub-tab -->
                <div class="tab-pane fade show active" id="review-design-<?= $row->microsite_block_id ?>-colors" role="tabpanel" aria-labelledby="review-design-<?= $row->microsite_block_id ?>-colors-tab">
                    
                    <h6 class="text-muted mb-3"><i class="fas fa-fw fa-palette fa-sm mr-1"></i> Review Colors</h6>
                    
                    <!-- Title Color -->
                    <?php
                    $block_id = $row->microsite_block_id;
                    $field_name = 'title_color';
                    $label = 'Title Color';
                    $icon = 'fas fa-paint-brush';
                    $default_color = '#333333';
                    $current_color = $row->settings->title_color ?? $default_color;
                    $include_opacity = true;
                    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                    ?>

                    <!-- Description Color -->
                    <?php
                    $field_name = 'description_color';
                    $label = 'Description Color';
                    $icon = 'fas fa-paint-brush';
                    $default_color = '#666666';
                    $current_color = $row->settings->description_color ?? $default_color;
                    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                    ?>

                    <!-- Author Name Color -->
                    <?php
                    $field_name = 'author_name_color';
                    $label = 'Author Name Color';
                    $icon = 'fas fa-paint-brush';
                    $default_color = '#333333';
                    $current_color = $row->settings->author_name_color ?? $default_color;
                    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                    ?>

                    <!-- Author Description Color -->
                    <?php
                    $field_name = 'author_description_color';
                    $label = 'Author Description Color';
                    $icon = 'fas fa-paint-brush';
                    $default_color = '#666666';
                    $current_color = $row->settings->author_description_color ?? $default_color;
                    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                    ?>

                    <!-- Stars Color -->
                    <?php
                    $field_name = 'stars_color';
                    $label = 'Stars Color';
                    $icon = 'fas fa-star';
                    $default_color = '#ffc107';
                    $current_color = $row->settings->stars_color ?? $default_color;
                    include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                    ?>

                </div>

                <!-- Layout Sub-tab -->
                <div class="tab-pane fade" id="review-design-<?= $row->microsite_block_id ?>-layout" role="tabpanel" aria-labelledby="review-design-<?= $row->microsite_block_id ?>-layout-tab">
                    
                    <!-- Text Alignment -->
                    <div class="form-group">
                        <label for="<?= 'block_text_alignment_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_alignment') ?? 'Text Alignment' ?></label>
                        <div class="row btn-group-toggle" data-toggle="buttons">
                            <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                                <div class="col-6">
                                    <label class="btn btn-light btn-block text-truncate <?= ($row->settings->text_alignment ?? 'center') == $text_alignment ? 'active' : '' ?>">
                                        <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($row->settings->text_alignment ?? 'center') == $text_alignment ? 'checked="checked"' : '' ?> />
                                        <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= ucfirst($text_alignment) ?>
                                    </label>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                </div>

                <!-- Background Sub-tab -->
                <div class="tab-pane fade" id="review-design-<?= $row->microsite_block_id ?>-background" role="tabpanel" aria-labelledby="review-design-<?= $row->microsite_block_id ?>-background-tab">
                    <?php
                    // Set up variables for background component
                    $block_id = $row->microsite_block_id;
                    $settings = $row->settings;
                    $use_accordion = false; // Disable accordion when used in tabs
                    include THEME_PATH . 'views/partials/microsite_block_components/background_settings.php';
                    ?>
                </div>

                <!-- Border Sub-tab -->
                <div class="tab-pane fade" id="review-design-<?= $row->microsite_block_id ?>-border" role="tabpanel" aria-labelledby="review-design-<?= $row->microsite_block_id ?>-border-tab">
                    <?php
                    // Set up variables for border component
                    $block_id = $row->microsite_block_id;
                    $settings = $row->settings;
                    $use_accordion = false; // Disable accordion when used in tabs
                    include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                    ?>
                </div>

                <!-- Shadow Sub-tab -->
                <div class="tab-pane fade" id="review-design-<?= $row->microsite_block_id ?>-shadow" role="tabpanel" aria-labelledby="review-design-<?= $row->microsite_block_id ?>-shadow-tab">
                    <?php
                    // Set up variables for shadow component
                    $block_id = $row->microsite_block_id;
                    $settings = $row->settings;
                    $use_accordion = false; // Disable accordion when used in tabs
                    include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                    ?>
                </div>

                <!-- Animation Sub-tab -->
                <div class="tab-pane fade" id="review-design-<?= $row->microsite_block_id ?>-animation" role="tabpanel" aria-labelledby="review-design-<?= $row->microsite_block_id ?>-animation-tab">
                    <?php
                    // Set up variables for animation component
                    $block_id = $row->microsite_block_id;
                    $settings = $row->settings;
                    $use_accordion = false; // Disable accordion when used in tabs
                    include THEME_PATH . 'views/partials/microsite_block_components/animation_settings.php';
                    ?>
                </div>

            </div>

        </div>

        <!-- Display Tab -->
        <div class="tab-pane fade" id="review-<?= $row->microsite_block_id ?>-display" role="tabpanel" aria-labelledby="review-<?= $row->microsite_block_id ?>-display-tab">
            
            <?php include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; ?>

        </div>

    </div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<!-- Template for new review items -->
<template id="template_review_item_<?= $row->microsite_block_id ?>">
    <div class="review-item-wrapper mb-3 border rounded" data-review-item>
        <!-- Drag Handle and Header -->
        <div class="review-item-header d-flex align-items-center justify-content-between p-3 bg-light border-bottom" style="cursor: pointer;" data-toggle="collapse" data-target="#review-item-content-<?= $row->microsite_block_id ?>-{index}">
            <div class="d-flex align-items-center">
                <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;" title="Drag to reorder"></i>
                <i class="fas fa-chevron-down review-toggle-icon mr-2 text-muted"></i>
                <span class="review-item-title font-weight-medium">New Review</span>
                <div class="ml-2">
                    <i class="fas fa-star text-warning" style="font-size: 0.75rem;"></i>
                    <i class="fas fa-star text-warning" style="font-size: 0.75rem;"></i>
                    <i class="fas fa-star text-warning" style="font-size: 0.75rem;"></i>
                    <i class="fas fa-star text-warning" style="font-size: 0.75rem;"></i>
                    <i class="fas fa-star text-warning" style="font-size: 0.75rem;"></i>
                </div>
            </div>
            <button type="button" data-remove="item" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();">
                <i class="fas fa-fw fa-times"></i>
            </button>
        </div>

        <!-- Collapsible Content -->
        <div id="review-item-content-<?= $row->microsite_block_id ?>-{index}" class="collapse show">
            <div class="p-3">
                <!-- Review Title -->
                <div class="form-group">
                    <label><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> Review Title</label>
                    <input type="text" name="review_title[]" class="form-control review-title-input" placeholder="Enter review title..." maxlength="128" />
                </div>

                <!-- Review Description -->
                <div class="form-group">
                    <label><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> Review Description</label>
                    <textarea name="review_description[]" class="form-control" rows="3" placeholder="Enter review description..." maxlength="1024"></textarea>
                </div>

                <!-- Author Name -->
                <div class="form-group">
                    <label><i class="fas fa-fw fa-user fa-sm text-muted mr-1"></i> Author Name</label>
                    <input type="text" name="review_author_name[]" class="form-control review-author-input" placeholder="Enter author name..." maxlength="128" required />
                </div>

                <!-- Author Description -->
                <div class="form-group">
                    <label><i class="fas fa-fw fa-user-tag fa-sm text-muted mr-1"></i> Author Description</label>
                    <input type="text" name="review_author_description[]" class="form-control" placeholder="e.g., Verified Customer, Premium Member..." maxlength="128" />
                </div>

                <!-- Star Rating -->
                <div class="form-group">
                    <label><i class="fas fa-fw fa-star fa-sm text-muted mr-1"></i> Star Rating</label>
                    <div class="star-rating-input" data-rating="5">
                        <i class="fas fa-star star-input active" data-rating="1"></i>
                        <i class="fas fa-star star-input active" data-rating="2"></i>
                        <i class="fas fa-star star-input active" data-rating="3"></i>
                        <i class="fas fa-star star-input active" data-rating="4"></i>
                        <i class="fas fa-star star-input active" data-rating="5"></i>
                        <input type="hidden" name="review_stars[]" value="5" />
                    </div>
                </div>

                <!-- Author Image -->
                <div class="form-group">
                    <label><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> Author Image</label>
                    <div class="custom-file">
                        <input type="file" name="review_image[]" class="custom-file-input" accept="image/*" />
                        <label class="custom-file-label">Choose author image...</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
'use strict';

// Initialize drag and drop functionality for reviews
function initializeReviewDragAndDrop(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    // Check if Sortable is available
    if (typeof Sortable !== 'undefined') {
        new Sortable(container, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                // Update field names after reordering
                updateReviewFieldNames(containerId);
            }
        });
    } else {
        console.warn('SortableJS not loaded - drag and drop functionality disabled');
    }
}

// Update field names after reordering
function updateReviewFieldNames(containerId) {
    const container = document.getElementById(containerId);
    const items = container.querySelectorAll('.review-item-wrapper');
    
    items.forEach((item, index) => {
        // Update all input field names
        const titleInput = item.querySelector('input[name^="review_title"]');
        const descriptionTextarea = item.querySelector('textarea[name^="review_description"]');
        const authorNameInput = item.querySelector('input[name^="review_author_name"]');
        const authorDescInput = item.querySelector('input[name^="review_author_description"]');
        const starsInput = item.querySelector('input[name^="review_stars"]');
        const imageInput = item.querySelector('input[name^="review_image"]');
        
        if (titleInput) titleInput.name = `review_title[${index}]`;
        if (descriptionTextarea) descriptionTextarea.name = `review_description[${index}]`;
        if (authorNameInput) authorNameInput.name = `review_author_name[${index}]`;
        if (authorDescInput) authorDescInput.name = `review_author_description[${index}]`;
        if (starsInput) starsInput.name = `review_stars[${index}]`;
        if (imageInput) imageInput.name = `review_image[${index}]`;
        
        // Update collapse target and ID
        const collapseContent = item.querySelector('.collapse');
        const toggleButton = item.querySelector('[data-target]');
        
        if (collapseContent && toggleButton) {
            const blockId = containerId.replace('reviews_', '');
            const newId = `review-item-content-${blockId}-${index}`;
            collapseContent.id = newId;
            toggleButton.setAttribute('data-target', `#${newId}`);
        }
    });
}

// Update review item title in header
function updateReviewTitle(input) {
    const wrapper = input.closest('.review-item-wrapper');
    const titleSpan = wrapper.querySelector('.review-item-title');
    if (titleSpan) {
        titleSpan.textContent = input.value || 'New Review';
    }
}

// Handle star rating clicks
function initializeStarRatings() {
    document.querySelectorAll('.star-rating-input').forEach(function(container) {
        const stars = container.querySelectorAll('.star-input');
        const hiddenInput = container.querySelector('input[type="hidden"]');
        
        stars.forEach(function(star, index) {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                
                // Update hidden input
                hiddenInput.value = rating;
                
                // Update star display
                stars.forEach(function(s, i) {
                    if (i < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
                
                // Update header stars display
                updateHeaderStars(container.closest('.review-item-wrapper'), rating);
            });
        });
    });
}

// Update stars in the header
function updateHeaderStars(wrapper, rating) {
    const headerStars = wrapper.querySelector('.review-item-header .ml-2');
    if (headerStars) {
        headerStars.innerHTML = '';
        for (let i = 0; i < 5; i++) {
            const star = document.createElement('i');
            star.className = `fas fa-star ${i < rating ? 'text-warning' : 'text-muted'}`;
            star.style.fontSize = '0.75rem';
            headerStars.appendChild(star);
        }
    }
}

// Handle slider mode changes
function toggleAutoPlaySettings() {
    const sliderModeRadios = document.querySelectorAll('input[name="slider_mode"]');
    const autoPlaySettings = document.querySelectorAll('.auto-play-settings');
    
    sliderModeRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            const isAuto = this.value === 'auto';
            autoPlaySettings.forEach(function(setting) {
                setting.style.display = isAuto ? 'block' : 'none';
            });
        });
    });
}

// Initialize file input labels
function initializeFileInputs() {
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const label = this.nextElementSibling;
            const fileName = this.files[0] ? this.files[0].name : 'Choose author image...';
            label.textContent = fileName;
        });
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize drag and drop for existing containers
    document.querySelectorAll('[id^="reviews_"]').forEach(function(container) {
        initializeReviewDragAndDrop(container.id);
    });
    
    // Initialize star ratings
    initializeStarRatings();
    
    // Initialize slider mode toggles
    toggleAutoPlaySettings();
    
    // Initialize file inputs
    initializeFileInputs();
    
    // Add title update listeners for existing items
    document.querySelectorAll('.review-author-input').forEach(function(input) {
        input.addEventListener('input', function() {
            updateReviewTitle(this);
        });
    });
    
    // Initialize add review button listeners
    document.querySelectorAll('[data-add="review_item"]').forEach(function(element) {
        element.addEventListener('click', review_item_add);
    });
    
    // Initialize remove review button listeners
    review_item_remove_initiator();
});

// Review item management
let review_item_add = function(event) {
    let microsite_block_id = event.currentTarget.getAttribute('data-microsite-block-id');
    let clone = document.querySelector(`#template_review_item_${microsite_block_id}`).content.cloneNode(true);
    let count = document.querySelectorAll(`[id="reviews_${microsite_block_id}"] .review-item-wrapper`).length;

    if(count >= 20) {
        alert('Maximum 20 reviews allowed.');
        return;
    }

    // Update IDs and targets in the cloned template
    const collapseContent = clone.querySelector('.collapse');
    const toggleButton = clone.querySelector('[data-target]');
    const newId = `review-item-content-${microsite_block_id}-${count}`;
    
    if (collapseContent) collapseContent.id = newId;
    if (toggleButton) toggleButton.setAttribute('data-target', `#${newId}`);

    // Update field names with index
    clone.querySelector('input[name="review_title[]"]').setAttribute('name', `review_title[${count}]`);
    clone.querySelector('textarea[name="review_description[]"]').setAttribute('name', `review_description[${count}]`);
    clone.querySelector('input[name="review_author_name[]"]').setAttribute('name', `review_author_name[${count}]`);
    clone.querySelector('input[name="review_author_description[]"]').setAttribute('name', `review_author_description[${count}]`);
    clone.querySelector('input[name="review_stars[]"]').setAttribute('name', `review_stars[${count}]`);
    clone.querySelector('input[name="review_image[]"]').setAttribute('name', `review_image[${count}]`);

    // Add event listener for title updates
    const authorInput = clone.querySelector('.review-author-input');
    if (authorInput) {
        authorInput.addEventListener('input', function() {
            updateReviewTitle(this);
        });
    }

    document.querySelector(`[id="reviews_${microsite_block_id}"]`).appendChild(clone);

    // Initialize functionality for new item
    setTimeout(function() {
        initializeReviewDragAndDrop(`reviews_${microsite_block_id}`);
        initializeStarRatings();
        initializeFileInputs();
    }, 100);

    review_item_remove_initiator();
};

// Remove review item
let review_item_remove = function(event) {
    const wrapper = event.currentTarget.closest('.review-item-wrapper');
    const container = wrapper.parentNode;
    
    // Don't allow removing the last item
    if (container.querySelectorAll('.review-item-wrapper').length <= 1) {
        alert('At least one review is required.');
        return;
    }
    
    wrapper.remove();
    
    // Update field names after removal
    updateReviewFieldNames(container.id);
};

let review_item_remove_initiator = function() {
    document.querySelectorAll('[id^="reviews_"] [data-remove]').forEach(function(element) {
        element.removeEventListener('click', review_item_remove);
        element.addEventListener('click', review_item_remove);
    });
    
    // Add title update listeners
    document.querySelectorAll('[id^="reviews_"] .review-author-input').forEach(function(input) {
        input.removeEventListener('input', updateReviewTitle);
        input.addEventListener('input', function() {
            updateReviewTitle(this);
        });
    });
};

// Add event listeners
document.querySelectorAll('[data-add="review_item"]').forEach(function(element) {
    element.addEventListener('click', review_item_add);
});

review_item_remove_initiator();

// Real-time canvas update functions for review blocks
window.updateCanvasColors = function(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get color values from the hidden inputs created by Pickr color picker
            const titleColor = $(`input[name="title_color"]`).val() || '#333333';
            const descriptionColor = $(`input[name="description_color"]`).val() || '#666666';
            const authorNameColor = $(`input[name="author_name_color"]`).val() || '#333333';
            const authorDescColor = $(`input[name="author_description_color"]`).val() || '#666666';
            const starsColor = $(`input[name="stars_color"]`).val() || '#ffc107';
            
            // Update review title colors
            microsite_link.find('.review-title, .review-title h1, .review-title h2, .review-title h3, .review-title h4, .review-title h5, .review-title h6').css('color', titleColor);
            
            // Update review description colors
            microsite_link.find('.review-description, .review-content').css('color', descriptionColor);
            
            // Update author name colors
            microsite_link.find('.review-author-name, .author-name').css('color', authorNameColor);
            
            // Update author description colors
            microsite_link.find('.review-author-description, .author-description').css('color', authorDescColor);
            
            // Update stars colors
            microsite_link.find('.review-stars .fas.fa-star, .stars .fas.fa-star').css('color', starsColor);
        }
    }
};

window.updateCanvasAnimation = function(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get animation values from the current form inputs
            const animation = $(`select[name="animation"]`).val() || 'false';
            const runs = $(`select[name="animation_runs"]`).val() || 'repeat-1';
            const delay = $(`input[name="animation_delay"]`).val() || 0;
            
            // Find the review container element
            let element = microsite_link.find('.review-slider, .review-container');
            if (!element.length) {
                element = microsite_link.find('.card');
            }
            if (!element.length) {
                element = microsite_link; // fallback to the block itself
            }
            
            if (element.length) {
                // Remove all existing animate.css classes
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
                    // Add new animation classes
                    element.addClass('animate__animated');
                    element.addClass(`animate__${animation}`);
                    
                    // Add repeat class
                    if (runs && runs !== 'repeat-1') {
                        element.addClass(`animate__${runs}`);
                    }
                    
                    // Apply delay
                    const delayMs = parseInt(delay) || 0;
                    element.css('animation-delay', `${delayMs}ms`);
                    
                    // Force animation restart by triggering reflow
                    element[0].offsetHeight; // trigger reflow
                    
                    // Remove and re-add animated class to restart animation
                    setTimeout(() => {
                        element.removeClass('animate__animated');
                        element[0].offsetHeight; // trigger reflow
                        setTimeout(() => {
                            element.addClass('animate__animated');
                        }, 50);
                    }, 50);
                }
            }
        }
    }
};

window.updateCanvasShadow = function(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get shadow values from form inputs
            const shadowX = $(`input[name="border_shadow_offset_x"]`).val() || 0;
            const shadowY = $(`input[name="border_shadow_offset_y"]`).val() || 0;
            const shadowBlur = $(`input[name="border_shadow_blur"]`).val() || 0;
            const shadowSpread = $(`input[name="border_shadow_spread"]`).val() || 0;
            const shadowColor = $(`input[name="border_shadow_color"]`).val() || '#00000010';
            
            // Apply shadow to review container
            let element = microsite_link.find('.review-slider, .review-container');
            if (!element.length) {
                element = microsite_link.find('.card');
            }
            if (!element.length) {
                element = microsite_link;
            }
            
            if (element.length) {
                if (parseInt(shadowBlur) > 0) {
                    const boxShadow = `${shadowX}px ${shadowY}px ${shadowBlur}px ${shadowSpread}px ${shadowColor}`;
                    element.css('box-shadow', boxShadow + ' !important');
                } else {
                    element.css('box-shadow', 'none');
                }
            }
        }
    }
};

window.updateCanvasBackground = function(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get background color from form input
            const backgroundColor = $(`input[name="background_color"]`).val() || '#ffffff';
            
            // Apply background color to review container
            let element = microsite_link.find('.review-slider, .review-container');
            if (!element.length) {
                element = microsite_link.find('.card');
            }
            if (!element.length) {
                element = microsite_link;
            }
            
            if (element.length) {
                element.css('background-color', backgroundColor);
            }
        }
    }
};

window.updateCanvasBorder = function(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get border values from form inputs
            const borderWidth = $(`input[name="border_width"]`).val() || 0;
            const borderColor = $(`input[name="border_color"]`).val() || '#ffffff';
            const borderStyle = $(`select[name="border_style"]`).val() || 'solid';
            const borderRadius = $(`input[name="border_radius"]`).val() || 0;
            
            // Apply border styling to review container
            let element = microsite_link.find('.review-slider, .review-container');
            if (!element.length) {
                element = microsite_link.find('.card');
            }
            if (!element.length) {
                element = microsite_link;
            }
            
            if (element.length) {
                if (parseInt(borderWidth) > 0) {
                    element.css('border', `${borderWidth}px ${borderStyle} ${borderColor} !important`);
                } else {
                    element.css('border', 'none');
                }
                
                if (parseInt(borderRadius) > 0) {
                    element.css('border-radius', `${borderRadius}px !important`);
                } else {
                    element.css('border-radius', '0px');
                }
            }
        }
    }
};
</script>

<style>
/* Review management styles */
.review-item-wrapper {
    transition: all 0.2s ease;
}

.review-item-wrapper:hover {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.review-item-header {
    transition: background-color 0.2s ease;
}

.review-item-header:hover {
    background-color: #f8f9fa !important;
}

.review-toggle-icon {
    transition: transform 0.2s ease;
}

.review-item-header[aria-expanded="true"] .review-toggle-icon {
    transform: rotate(180deg);
}

/* Star rating styles */
.star-rating-input {
    font-size: 1.5rem;
    cursor: pointer;
}

.star-rating-input .star-input {
    color: #ddd;
    transition: color 0.2s ease;
    margin-right: 0.25rem;
}

.star-rating-input .star-input:hover,
.star-rating-input .star-input.active {
    color: #ffc107;
}

.star-rating-input .star-input:hover {
    transform: scale(1.1);
}

/* Sortable styles */
.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    background-color: #f8f9fa;
}

.sortable-drag {
    transform: rotate(5deg);
}

/* Auto-play settings animation */
.auto-play-settings {
    transition: all 0.3s ease;
    overflow: hidden;
}
</style>
