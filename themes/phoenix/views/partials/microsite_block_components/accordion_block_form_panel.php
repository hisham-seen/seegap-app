<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Accordion Block Form Panel
 * 
 * This component provides the complete form structure for accordion blocks,
 * including primary tabs (Content, Design, Display) and secondary tabs within Design.
 * 
 * @param string $block_id - Unique identifier for the block (e.g., 'create' or actual block ID)
 * @param object $settings - Block settings object with default values
 * @param object $row - Block row data (for update form) or mock object (for create modal)
 * @param string $form_type - 'create' or 'update' to determine form behavior
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$form_type = $form_type ?? 'update';
$row = $row ?? (object)['microsite_block_id' => $block_id, 'settings' => $settings];

// Set up default settings for create form
if ($form_type === 'create') {
    $default_settings = (object) [
        'items' => [],
        'accordion_mode' => 'single',
        'default_state' => 'first_open',
        'text_color' => '#333333',
        'text_alignment' => 'center',
        'background_color' => '#ffffff',
        'border_width' => 0,
        'border_color' => '#ffffff',
        'border_radius' => 4,
        'border_style' => 'solid',
        'border_shadow_offset_x' => 0,
        'border_shadow_offset_y' => 0,
        'border_shadow_blur' => 0,
        'border_shadow_spread' => 0,
        'border_shadow_color' => '#00000010',
        'animation' => false,
        'animation_runs' => 'repeat-1',
        'animation_delay' => 0
    ];
    
    // Merge with any provided settings
    foreach ($default_settings as $key => $value) {
        if (!isset($settings->$key)) {
            $settings->$key = $value;
        }
    }
    $row->settings = $settings;
}

// Generate unique IDs based on block_id
$unique_id = $form_type === 'create' ? 'create' : $row->microsite_block_id;
?>

<?php
// Define tabs for the accordion block
$tabs = [
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

// Set the block_id for the tab component
$primary_tab_block_id = 'accordion-' . $unique_id;
$primary_tabs = $tabs; // Store primary tabs

// Temporarily set variables for primary tabs
$block_id = $primary_tab_block_id;
$tabs = $primary_tabs;

// Include the reusable tab navigation
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="accordion-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="accordion-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="accordion-<?= $unique_id ?>-content-tab">
        
        <!-- Accordion Behavior Settings -->
        <div class="form-group">
            <label><i class="fas fa-fw fa-cogs fa-sm text-muted mr-1"></i> Accordion Behavior</label>
            <div class="card">
                <div class="card-body">
                    <!-- Accordion Mode -->
                    <div class="form-group">
                        <label><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> Accordion Mode</label>
                        <div class="row btn-group-toggle" data-toggle="buttons">
                            <div class="col-6">
                                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->accordion_mode ?? 'single') == 'single' ? 'active' : '' ?>">
                                    <input type="radio" name="accordion_mode" value="single" class="custom-control-input" <?= ($row->settings->accordion_mode ?? 'single') == 'single' ? 'checked="checked"' : '' ?> />
                                    <i class="fas fa-fw fa-layer-group fa-sm mr-1"></i> Single Item Only
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->accordion_mode ?? 'single') == 'multiple' ? 'active' : '' ?>">
                                    <input type="radio" name="accordion_mode" value="multiple" class="custom-control-input" <?= ($row->settings->accordion_mode ?? 'single') == 'multiple' ? 'checked="checked"' : '' ?> />
                                    <i class="fas fa-fw fa-bars fa-sm mr-1"></i> Allow Multiple Open
                                </label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Single Item: Classic accordion behavior (only one item open at a time). Multiple: Allow several items to be expanded simultaneously.</small>
                    </div>

                    <!-- Default State -->
                    <div class="form-group">
                        <label><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> Default State</label>
                        <div class="row btn-group-toggle" data-toggle="buttons">
                            <div class="col-4">
                                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->default_state ?? 'first_open') == 'all_closed' ? 'active' : '' ?>">
                                    <input type="radio" name="default_state" value="all_closed" class="custom-control-input" <?= ($row->settings->default_state ?? 'first_open') == 'all_closed' ? 'checked="checked"' : '' ?> />
                                    <i class="fas fa-fw fa-minus fa-sm mr-1"></i> All Closed
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->default_state ?? 'first_open') == 'first_open' ? 'active' : '' ?>">
                                    <input type="radio" name="default_state" value="first_open" class="custom-control-input" <?= ($row->settings->default_state ?? 'first_open') == 'first_open' ? 'checked="checked"' : '' ?> />
                                    <i class="fas fa-fw fa-play fa-sm mr-1"></i> First Item Open
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->default_state ?? 'first_open') == 'custom' ? 'active' : '' ?>">
                                    <input type="radio" name="default_state" value="custom" class="custom-control-input" <?= ($row->settings->default_state ?? 'first_open') == 'custom' ? 'checked="checked"' : '' ?> />
                                    <i class="fas fa-fw fa-sliders-h fa-sm mr-1"></i> Custom
                                </label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Choose how the accordion appears when the page loads. Custom allows you to set individual item states below.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accordion Items -->
        <div class="form-group">
            <label><i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> Accordion Items</label>
            <div id="accordion_items_<?= $unique_id ?>" data-microsite-block-id="<?= $unique_id ?>">
                <?php if(!empty($row->settings->items) && is_array($row->settings->items)): ?>
                    <?php foreach($row->settings->items as $key => $item): ?>
                        <div class="accordion-item-wrapper mb-3 border rounded" data-accordion-item>
                            <!-- Drag Handle and Header -->
                            <div class="accordion-item-header d-flex align-items-center justify-content-between p-3 bg-light border-bottom" style="cursor: pointer;" data-toggle="collapse" data-target="#accordion-item-content-<?= $unique_id ?>-<?= $key ?>">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;" title="Drag to reorder"></i>
                                    <i class="fas fa-chevron-down accordion-toggle-icon mr-2 text-muted"></i>
                                    <span class="accordion-item-title font-weight-medium"><?= htmlspecialchars($item->title ?? 'Accordion Item') ?></span>
                                </div>
                                <button type="button" data-remove="item" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();">
                                    <i class="fas fa-fw fa-times"></i>
                                </button>
                            </div>

                            <!-- Collapsible Content -->
                            <div id="accordion-item-content-<?= $unique_id ?>-<?= $key ?>" class="collapse">
                                <div class="p-3">
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> Title</label>
                                        <input type="text" name="item_title[<?= $key ?>]" class="form-control accordion-title-input" value="<?= htmlspecialchars($item->title ?? '') ?>" placeholder="Enter accordion title..." maxlength="256" required />
                                    </div>

                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-edit fa-sm text-muted mr-1"></i> Content</label>
                                        <textarea name="item_content[<?= $key ?>]" class="form-control wysiwyg-editor" rows="6" placeholder="Enter your content here. You can use rich text formatting..."><?= $item->content ?? '' ?></textarea>
                                        <small class="form-text text-muted">Use the toolbar above to format your text, add links, lists, and more.</small>
                                    </div>

                                    <div class="form-group custom-control-container" style="display: <?= ($row->settings->default_state ?? 'first_open') == 'custom' ? 'block' : 'none' ?>;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="item_open_default[<?= $key ?>]" value="1" class="custom-control-input" id="item_open_default_<?= $unique_id ?>_<?= $key ?>" <?= isset($item->open_default) && $item->open_default ? 'checked' : '' ?> />
                                            <label class="custom-control-label" for="item_open_default_<?= $unique_id ?>_<?= $key ?>">
                                                <i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> Open by Default
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Check this to have this accordion item expanded when the page loads (only visible when "Custom" default state is selected).</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <div class="accordion-item-wrapper mb-3 border rounded" data-accordion-item>
                        <!-- Drag Handle and Header -->
                        <div class="accordion-item-header d-flex align-items-center justify-content-between p-3 bg-light border-bottom" style="cursor: pointer;" data-toggle="collapse" data-target="#accordion-item-content-<?= $unique_id ?>-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;" title="Drag to reorder"></i>
                                <i class="fas fa-chevron-down accordion-toggle-icon mr-2 text-muted"></i>
                                <span class="accordion-item-title font-weight-medium">New Accordion Item</span>
                            </div>
                            <button type="button" data-remove="item" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();">
                                <i class="fas fa-fw fa-times"></i>
                            </button>
                        </div>

                        <!-- Collapsible Content -->
                        <div id="accordion-item-content-<?= $unique_id ?>-0" class="collapse show">
                            <div class="p-3">
                                <div class="form-group">
                                    <label><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> Title</label>
                                    <input type="text" name="item_title[0]" class="form-control accordion-title-input" placeholder="Enter accordion title..." maxlength="256" required />
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-fw fa-edit fa-sm text-muted mr-1"></i> Content</label>
                                    <textarea name="item_content[0]" class="form-control wysiwyg-editor" rows="6" placeholder="Enter your content here. You can use rich text formatting..."></textarea>
                                    <small class="form-text text-muted">Use the toolbar above to format your text, add links, lists, and more.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
            </div>
            <button data-add="accordion_item" data-microsite-block-id="<?= $unique_id ?>" type="button" class="btn btn-outline-success btn-block mt-3">
                <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> Add Accordion Item
            </button>
            <small class="form-text text-muted">Create expandable sections with rich content. You can add up to 20 items.</small>
        </div>

    </div>

    <!-- Design Tab -->
    <div class="tab-pane fade" id="accordion-<?= $unique_id ?>-design" role="tabpanel" aria-labelledby="accordion-<?= $unique_id ?>-design-tab">
        
        <?php
        // Define secondary tabs for the design section
        $design_tabs = [
            [
                'id' => 'text-styling',
                'title' => 'Text',
                'icon' => 'fas fa-font'
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
        $secondary_block_id = 'accordion-design-' . $unique_id;
        $tabs = $design_tabs; // Use design tabs for the secondary navigation
        $block_id = $secondary_block_id; // Override block_id for secondary tabs
        
        // Include the reusable tab navigation for secondary tabs
        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
        ?>

        <div class="tab-content" id="accordion-design-<?= $unique_id ?>-tabContent">
            
            <!-- Text Styling Sub-tab -->
            <div class="tab-pane fade show active" id="accordion-design-<?= $unique_id ?>-text-styling" role="tabpanel" aria-labelledby="accordion-design-<?= $unique_id ?>-text-styling-tab">
                <?php
                // Set up variables for text styling component (without accordion)
                $block_id = $unique_id;
                $component_settings = $row->settings;
                $collapsed = false; // Don't use accordion in sub-tab
                
                // Include text color picker
                $field_name = 'text_color';
                $label = l('microsite_link.text_color');
                $icon = 'fas fa-paint-brush';
                $default_color = '#333333';
                $current_color = $component_settings->text_color ?? $default_color;
                include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                ?>
                
                <!-- Text Alignment -->
                <div class="form-group">
                    <label for="<?= 'block_text_alignment_' . $unique_id ?>"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_alignment') ?></label>
                    <div class="row btn-group-toggle" data-toggle="buttons">
                        <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                            <div class="col-6">
                                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->text_alignment ?? 'center') == $text_alignment ? 'active' : '' ?>">
                                    <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($row->settings->text_alignment ?? 'center') == $text_alignment ? 'checked="checked"' : '' ?> onchange="updateCanvasText('<?= $unique_id ?>')" />
                                    <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= l('microsite_link.text_alignment.' . $text_alignment) ?>
                                </label>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <!-- Background Sub-tab -->
            <div class="tab-pane fade" id="accordion-design-<?= $unique_id ?>-background" role="tabpanel" aria-labelledby="accordion-design-<?= $unique_id ?>-background-tab">
                <?php
                // Set up variables for background component (without accordion)
                $block_id = $unique_id;
                $component_settings = $row->settings;
                $field_name = 'background_color';
                
                // Include background color picker directly
                $bg_field_name = $field_name;
                $bg_label = l('microsite_link.background_color');
                $bg_icon = 'fas fa-fill';
                $bg_default = '#ffffff';
                $bg_current = $component_settings->$field_name ?? $bg_default;
                $bg_include_opacity = true; // Enable opacity for background colors

                // Set variables for color picker component
                $field_name = $bg_field_name;
                $label = $bg_label;
                $icon = $bg_icon;
                $default_color = $bg_default;
                $current_color = $bg_current;
                $include_opacity = $bg_include_opacity;

                include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                ?>
            </div>

            <!-- Border Sub-tab -->
            <div class="tab-pane fade" id="accordion-design-<?= $unique_id ?>-border" role="tabpanel" aria-labelledby="accordion-design-<?= $unique_id ?>-border-tab">
                <?php
                // Set up variables for border component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                ?>
            </div>

            <!-- Shadow Sub-tab -->
            <div class="tab-pane fade" id="accordion-design-<?= $unique_id ?>-shadow" role="tabpanel" aria-labelledby="accordion-design-<?= $unique_id ?>-shadow-tab">
                <?php
                // Set up variables for shadow component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                ?>
            </div>

            <!-- Animation Sub-tab -->
            <div class="tab-pane fade" id="accordion-design-<?= $unique_id ?>-animation" role="tabpanel" aria-labelledby="accordion-design-<?= $unique_id ?>-animation-tab">
                <?php
                // Set up variables for animation component (without accordion)
                $component_block_id = $unique_id;
                $component_settings = $row->settings;
                
                // Include animation settings directly without accordion wrapper
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

    <!-- Display Tab -->
    <div class="tab-pane fade" id="accordion-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="accordion-<?= $unique_id ?>-display-tab">
        
        <?php 
        // Set up variables for display settings component
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
        
        // Temporarily set $row for the display settings component
        $original_row = isset($row) ? $row : null;
        $row = $display_row;
        include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php';
        $row = $original_row; // Restore original $row
        ?>

    </div>

</div>

<!-- Template for new accordion items -->
<template id="template_accordion_item_<?= $unique_id ?>">
    <div class="accordion-item-wrapper mb-3 border rounded" data-accordion-item>
        <!-- Drag Handle and Header -->
        <div class="accordion-item-header d-flex align-items-center justify-content-between p-3 bg-light border-bottom" style="cursor: pointer;" data-toggle="collapse" data-target="#accordion-item-content-<?= $unique_id ?>-{index}">
            <div class="d-flex align-items-center">
                <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;" title="Drag to reorder"></i>
                <i class="fas fa-chevron-down accordion-toggle-icon mr-2 text-muted"></i>
                <span class="accordion-item-title font-weight-medium">New Accordion Item</span>
            </div>
            <button type="button" data-remove="item" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();">
                <i class="fas fa-fw fa-times"></i>
            </button>
        </div>

        <!-- Collapsible Content -->
        <div id="accordion-item-content-<?= $unique_id ?>-{index}" class="collapse show">
            <div class="p-3">
                <div class="form-group">
                    <label><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> Title</label>
                    <input type="text" name="item_title[]" class="form-control accordion-title-input" placeholder="Enter accordion title..." maxlength="256" required />
                </div>

                <div class="form-group">
                    <label><i class="fas fa-fw fa-edit fa-sm text-muted mr-1"></i> Content</label>
                    <textarea name="item_content[]" class="form-control wysiwyg-editor" rows="6" placeholder="Enter your content here. You can use rich text formatting..."></textarea>
                    <small class="form-text text-muted">Use the toolbar above to format your text, add links, lists, and more.</small>
                </div>

                <div class="form-group custom-control-container" style="display: none;">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="item_open_default[]" value="1" class="custom-control-input" id="item_open_default_<?= $unique_id ?>_{index}" />
                        <label class="custom-control-label" for="item_open_default_<?= $unique_id ?>_{index}">
                            <i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> Open by Default
                        </label>
                    </div>
                    <small class="form-text text-muted">Check this to have this accordion item expanded when the page loads (only visible when "Custom" default state is selected).</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
'use strict';

// Initialize WYSIWYG editors for accordion content
function initializeWysiwygEditors() {
    document.querySelectorAll('.wysiwyg-editor').forEach(function(textarea) {
        if (!textarea.classList.contains('wysiwyg-initialized')) {
            // Initialize Quill editor
            const editorContainer = document.createElement('div');
            editorContainer.style.minHeight = '150px';
            textarea.style.display = 'none';
            textarea.parentNode.insertBefore(editorContainer, textarea);
            
            const quill = new Quill(editorContainer, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link'],
                        ['clean']
                    ]
                },
                placeholder: 'Enter your content here...'
            });
            
            // Set initial content
            if (textarea.value) {
                quill.root.innerHTML = textarea.value;
            }
            
            // Update textarea when content changes
            quill.on('text-change', function() {
                textarea.value = quill.root.innerHTML;
            });
            
            textarea.classList.add('wysiwyg-initialized');
        }
    });
}

// Initialize drag and drop functionality
function initializeDragAndDrop(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    new Sortable(container, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            // Update field names after reordering
            updateFieldNames(containerId);
        }
    });
}

// Update field names after reordering
function updateFieldNames(containerId) {
    const container = document.getElementById(containerId);
    const items = container.querySelectorAll('.accordion-item-wrapper');
    
    items.forEach((item, index) => {
        const titleInput = item.querySelector('input[name^="item_title"]');
        const contentTextarea = item.querySelector('textarea[name^="item_content"]');
        
        if (titleInput) titleInput.name = `item_title[${index}]`;
        if (contentTextarea) contentTextarea.name = `item_content[${index}]`;
        
        // Update collapse target and ID
        const collapseContent = item.querySelector('.collapse');
        const toggleButton = item.querySelector('[data-target]');
        
        if (collapseContent && toggleButton) {
            const blockId = containerId.replace('accordion_items_', '');
            const newId = `accordion-item-content-${blockId}-${index}`;
            collapseContent.id = newId;
            toggleButton.setAttribute('data-target', `#${newId}`);
        }
    });
}

// Update accordion item title in header
function updateAccordionTitle(input) {
    const wrapper = input.closest('.accordion-item-wrapper');
    const titleSpan = wrapper.querySelector('.accordion-item-title');
    if (titleSpan) {
        titleSpan.textContent = input.value || 'New Accordion Item';
    }
}

// Handle default state changes
function toggleCustomControls() {
    const defaultStateRadios = document.querySelectorAll('input[name="default_state"]');
    const customControlContainers = document.querySelectorAll('.custom-control-container');
    
    defaultStateRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            const isCustom = this.value === 'custom';
            customControlContainers.forEach(function(container) {
                container.style.display = isCustom ? 'block' : 'none';
            });
        });
    });
}

// Initialize existing editors on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize immediately
    initializeWysiwygEditors();
    
    // Initialize drag and drop for existing containers
    document.querySelectorAll('[id^="accordion_items_"]').forEach(function(container) {
        initializeDragAndDrop(container.id);
    });
    
    // Add title update listeners for existing items
    document.querySelectorAll('.accordion-title-input').forEach(function(input) {
        input.addEventListener('input', function() {
            updateAccordionTitle(this);
        });
    });
    
    // Initialize default state toggle functionality
    toggleCustomControls();
});

// Also initialize when content becomes visible (for update forms)
document.addEventListener('shown.bs.modal', function(event) {
    // Initialize WYSIWYG editors when modal is shown
    setTimeout(function() {
        initializeWysiwygEditors();
    }, 100);
});

// Initialize when tabs are shown
document.addEventListener('shown.bs.tab', function(event) {
    // Initialize WYSIWYG editors when tab is shown
    setTimeout(function() {
        initializeWysiwygEditors();
    }, 100);
});

// Initialize immediately if Quill is already available
if (typeof Quill !== 'undefined') {
    // Initialize right away if Quill is loaded
    setTimeout(function() {
        initializeWysiwygEditors();
    }, 100);
}

// Observe for new content being added to the DOM
const accordionObserver = new MutationObserver(function(mutations) {
    let shouldInitialize = false;
    mutations.forEach(function(mutation) {
        if (mutation.type === 'childList') {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    if (node.classList && node.classList.contains('wysiwyg-editor') || 
                        (node.querySelector && node.querySelector('.wysiwyg-editor'))) {
                        shouldInitialize = true;
                    }
                }
            });
        }
    });
    
    if (shouldInitialize) {
        setTimeout(function() {
            initializeWysiwygEditors();
        }, 100);
    }
});

// Start observing
accordionObserver.observe(document.body, {
    childList: true,
    subtree: true
});

// Accordion item management
let accordion_item_add = function(event) {
    let microsite_block_id = event.currentTarget.getAttribute('data-microsite-block-id');
    let clone = document.querySelector(`#template_accordion_item_${microsite_block_id}`).content.cloneNode(true);
    let count = document.querySelectorAll(`[id="accordion_items_${microsite_block_id}"] .accordion-item-wrapper`).length;

    if(count >= 20) {
        alert('Maximum 20 accordion items allowed.');
        return;
    }

    // Update IDs and targets in the cloned template
    const collapseContent = clone.querySelector('.collapse');
    const toggleButton = clone.querySelector('[data-target]');
    const newId = `accordion-item-content-${microsite_block_id}-${count}`;
    
    if (collapseContent) collapseContent.id = newId;
    if (toggleButton) toggleButton.setAttribute('data-target', `#${newId}`);

    // Update field names with index
    clone.querySelector('input[name="item_title[]"]').setAttribute('name', `item_title[${count}]`);
    clone.querySelector('textarea[name="item_content[]"]').setAttribute('name', `item_content[${count}]`);

    // Add event listener for title updates
    const titleInput = clone.querySelector('.accordion-title-input');
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            updateAccordionTitle(this);
        });
    }

    document.querySelector(`[id="accordion_items_${microsite_block_id}"]`).appendChild(clone);

    // Initialize WYSIWYG for new item
    setTimeout(function() {
        initializeWysiwygEditors();
        initializeDragAndDrop(`accordion_items_${microsite_block_id}`);
    }, 100);

    accordion_item_remove_initiator();
};

// Remove accordion item
let accordion_item_remove = function(event) {
    const wrapper = event.currentTarget.closest('.accordion-item-wrapper');
    const container = wrapper.parentNode;
    
    // Don't allow removing the last item
    if (container.querySelectorAll('.accordion-item-wrapper').length <= 1) {
        alert('At least one accordion item is required.');
        return;
    }
    
    wrapper.remove();
    
    // Update field names after removal
    updateFieldNames(container.id);
};

let accordion_item_remove_initiator = function() {
    document.querySelectorAll('[id^="accordion_items_"] [data-remove]').forEach(function(element) {
        element.removeEventListener('click', accordion_item_remove);
        element.addEventListener('click', accordion_item_remove);
    });
    
    // Add title update listeners
    document.querySelectorAll('[id^="accordion_items_"] .accordion-title-input').forEach(function(input) {
        input.removeEventListener('input', updateAccordionTitle);
        input.addEventListener('input', function() {
            updateAccordionTitle(this);
        });
    });
};

// Add event listeners
document.querySelectorAll('[data-add="accordion_item"]').forEach(function(element) {
    element.addEventListener('click', accordion_item_add);
});

accordion_item_remove_initiator();

// Real-time canvas update functions for accordion blocks
window.updateCanvasAnimation = function(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get animation values from the current form inputs
            const animation = $(`#animation_${blockId}`).val() || 'false';
            const runs = $(`#animation_runs_${blockId}`).val() || 'repeat-1';
            const delay = $(`#animation_delay_${blockId}`).val() || 0;
            
            // Target the accordion container for animations
            const accordion_container = microsite_link.find('.accordion');
            
            if (accordion_container.length) {
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
                
                accordion_container.removeClass(animateClasses.join(' '));
                
                if (animation !== 'false' && animation !== '') {
                    // Add new animation classes
                    accordion_container.addClass('animate__animated');
                    accordion_container.addClass(`animate__${animation}`);
                    
                    // Add repeat class
                    if (runs && runs !== 'repeat-1') {
                        accordion_container.addClass(`animate__${runs}`);
                    }
                    
                    // Apply delay
                    const delayMs = parseInt(delay) || 0;
                    accordion_container.css('animation-delay', `${delayMs}ms`);
                    
                    // Force animation restart by triggering reflow
                    accordion_container[0].offsetHeight; // trigger reflow
                    
                    // Remove and re-add animated class to restart animation
                    setTimeout(() => {
                        accordion_container.removeClass('animate__animated');
                        accordion_container[0].offsetHeight; // trigger reflow
                        setTimeout(() => {
                            accordion_container.addClass('animate__animated');
                        }, 50);
                    }, 50);
                }
            }
        }
    }
};

window.updateCanvasText = function(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get text values from form inputs
            const textColor = $(`input[name="text_color"]`).val() || '#333333';
            const textAlignment = $(`input[name="text_alignment"]:checked`).val() || 'center';
            
            // Update accordion container text alignment
            const accordion_container = microsite_link.find('.accordion');
            if (accordion_container.length) {
                accordion_container.css('text-align', textAlignment);
            }
            
            // Update text color for buttons and card bodies
            const accordion_buttons = microsite_link.find('.accordion .card-header button');
            const accordion_bodies = microsite_link.find('.accordion .card-body');
            
            accordion_buttons.css('color', textColor);
            accordion_bodies.css('color', textColor);
            
            // Update text alignment for buttons and card bodies
            accordion_buttons.css('text-align', textAlignment);
            accordion_bodies.css('text-align', textAlignment);
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
            
            // Apply background color to individual accordion cards
            const accordion_cards = microsite_link.find('.accordion .card');
            accordion_cards.css('background-color', backgroundColor);
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
            
            // Apply border styling to individual accordion cards
            const accordion_cards = microsite_link.find('.accordion .card');
            
            if (parseInt(borderWidth) > 0) {
                accordion_cards.css('border', `${borderWidth}px ${borderStyle} ${borderColor} !important`);
            } else {
                accordion_cards.css('border', 'none');
            }
            
            if (parseInt(borderRadius) > 0) {
                accordion_cards.css('border-radius', `${borderRadius}px !important`);
            } else {
                accordion_cards.css('border-radius', '0px');
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
            
            // Apply shadow to individual accordion cards
            const accordion_cards = microsite_link.find('.accordion .card');
            
            if (parseInt(shadowBlur) > 0) {
                const boxShadow = `${shadowX}px ${shadowY}px ${shadowBlur}px ${shadowSpread}px ${shadowColor}`;
                accordion_cards.css('box-shadow', boxShadow + ' !important');
            } else {
                accordion_cards.css('box-shadow', 'none');
            }
        }
    }
};
</script>
