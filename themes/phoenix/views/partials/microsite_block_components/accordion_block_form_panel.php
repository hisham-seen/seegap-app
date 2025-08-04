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
                                    <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($row->settings->text_alignment ?? 'center') == $text_alignment ? 'checked="checked"' : '' ?> />
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
                    <select id="<?= 'animation_' . $component_block_id ?>" name="animation" class="form-control">
                        <option value="false" <?= (!isset($component_settings->animation) || !$component_settings->animation) ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                        <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                            <option value="<?= $animation ?>" <?= (isset($component_settings->animation) && $component_settings->animation == $animation) ? 'selected="selected"' : null ?>><?= l('microsite_animations.' . $animation) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="<?= 'animation_runs_' . $component_block_id ?>"><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
                    <select id="<?= 'animation_runs_' . $component_block_id ?>" name="animation_runs" class="form-control">
                        <option value="repeat-1" <?= (!isset($component_settings->animation_runs) || $component_settings->animation_runs == 'repeat-1') ? 'selected="selected"' : null ?>>1</option>
                        <option value="repeat-2" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'repeat-2') ? 'selected="selected"' : null ?>>2</option>
                        <option value="repeat-3" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'repeat-3') ? 'selected="selected"' : null ?>>3</option>
                        <option value="infinite" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'infinite') ? 'selected="selected"' : null ?>><?= l('global.infinite') ?></option>
                    </select>
                </div>

                <div class="form-group" data-range-counter data-range-counter-suffix="ms">
                    <label for="<?= 'animation_delay_' . $component_block_id ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_delay') ?></label>
                    <input id="<?= 'animation_delay_' . $component_block_id ?>" type="range" min="0" max="5000" step="100" class="form-control-range" name="animation_delay" value="<?= $component_settings->animation_delay ?? 0 ?>" required="required" />
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
</script>
