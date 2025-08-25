<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Text Block Form Panel
 * 
 * This component provides the complete form structure for text blocks,
 * including primary tabs (Content, Style, Display) and secondary tabs within Style.
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
        'content' => '',
        'text_color' => '#ffffff',
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
// Define tabs for the text block
$tabs = [
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

// Set the block_id for the tab component
$primary_tab_block_id = 'text-' . $unique_id;
$primary_tabs = $tabs; // Store primary tabs

// Temporarily set variables for primary tabs
$block_id = $primary_tab_block_id;
$tabs = $primary_tabs;

// Include the reusable tab navigation
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="text-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="text-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="text-<?= $unique_id ?>-content-tab">
        
        <!-- Rich Text Content -->
        <div class="form-group">
            <label><i class="fas fa-fw fa-edit fa-sm text-muted mr-1"></i> Content</label>
            <textarea name="content" class="form-control wysiwyg-editor" rows="8" placeholder="Enter your content here. Use the toolbar to format text, add headings, lists, and more..."><?= $row->settings->content ?? '' ?></textarea>
            <small class="form-text text-muted">Use the rich text editor to create headings, paragraphs, lists, and formatted text. The editor supports all common formatting options.</small>
        </div>

    </div>

    <!-- Style Tab -->
    <div class="tab-pane fade" id="text-<?= $unique_id ?>-style" role="tabpanel" aria-labelledby="text-<?= $unique_id ?>-style-tab">
        
        <?php
        // Define secondary tabs for the style section
        $style_tabs = [
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
        $secondary_block_id = 'text-style-' . $unique_id;
        $tabs = $style_tabs; // Use style tabs for the secondary navigation
        $block_id = $secondary_block_id; // Override block_id for secondary tabs
        
        // Include the reusable tab navigation for secondary tabs
        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
        ?>

        <div class="tab-content" id="text-style-<?= $unique_id ?>-tabContent">
            
            <!-- Text Styling Sub-tab -->
            <div class="tab-pane fade show active" id="text-style-<?= $unique_id ?>-text-styling" role="tabpanel" aria-labelledby="text-style-<?= $unique_id ?>-text-styling-tab">
                <?php
                // Set up variables for text styling component (without accordion)
                $block_id = $unique_id;
                $component_settings = $row->settings;
                $collapsed = false; // Don't use accordion in sub-tab
                
                // Include text styling component content without accordion wrapper
                $field_name = 'text_color';
                $label = l('microsite_link.text_color');
                $icon = 'fas fa-paint-brush';
                $default_color = '#ffffff';
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
            <div class="tab-pane fade" id="text-style-<?= $unique_id ?>-background" role="tabpanel" aria-labelledby="text-style-<?= $unique_id ?>-background-tab">
                <?php
                // Set up variables for background component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/background_settings.php';
                ?>
            </div>

            <!-- Border Sub-tab -->
            <div class="tab-pane fade" id="text-style-<?= $unique_id ?>-border" role="tabpanel" aria-labelledby="text-style-<?= $unique_id ?>-border-tab">
                <?php
                // Set up variables for border component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                ?>
            </div>

            <!-- Shadow Sub-tab -->
            <div class="tab-pane fade" id="text-style-<?= $unique_id ?>-shadow" role="tabpanel" aria-labelledby="text-style-<?= $unique_id ?>-shadow-tab">
                <?php
                // Set up variables for shadow component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                ?>
            </div>

            <!-- Animation Sub-tab -->
            <div class="tab-pane fade" id="text-style-<?= $unique_id ?>-animation" role="tabpanel" aria-labelledby="text-style-<?= $unique_id ?>-animation-tab">
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

<script>
// Real-time canvas update function for animation properties
window.updateCanvasAnimation = function(blockId) {
    if (typeof $ !== 'undefined' && $('#microsite_preview_iframe').length) {
        const iframe = $('#microsite_preview_iframe');
        const iframeDoc = iframe.contents();
        const microsite_link = iframeDoc.find(`[data-microsite-block-id="${blockId}"]`);
        
        if (microsite_link.length) {
            // Get animation values from the current form inputs with proper selectors
            const animation = $(`#animation_${blockId}`).val() || 'false';
            const runs = $(`#animation_runs_${blockId}`).val() || 'repeat-1';
            const delay = $(`#animation_delay_${blockId}`).val() || 0;
            
            // Find the element that gets animation classes (either .card or .text-break)
            let element = microsite_link.find('.card');
            if (!element.length) {
                element = microsite_link.find('.text-break');
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
                    
                    // Apply delay - always set to ensure consistency
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
</script>
            </div>

        </div>

    </div>

    <!-- Display Tab -->
    <div class="tab-pane fade" id="text-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="text-<?= $unique_id ?>-display-tab">
        
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

<script>
'use strict';

// Store Quill instances for form submission
window.textQuillInstances = window.textQuillInstances || [];

// Initialize WYSIWYG editors for text content
function initializeTextWysiwygEditors() {
    document.querySelectorAll('.wysiwyg-editor').forEach(function(textarea) {
        if (!textarea.classList.contains('wysiwyg-initialized')) {
            // Initialize Quill editor
            const editorContainer = document.createElement('div');
            editorContainer.style.minHeight = '200px';
            textarea.style.display = 'none';
            textarea.parentNode.insertBefore(editorContainer, textarea);
            
            const quill = new Quill(editorContainer, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link'],
                        ['clean']
                    ]
                },
                placeholder: 'Enter your content here. Use the toolbar to format text, add headings, lists, and more...'
            });
            
            // Set initial content
            if (textarea.value) {
                quill.root.innerHTML = textarea.value;
            }
            
            // Update textarea when content changes
            quill.on('text-change', function() {
                textarea.value = quill.root.innerHTML;
            });
            
            // Store the instance for form submission
            window.textQuillInstances.push({
                quill: quill,
                textarea: textarea
            });
            
            textarea.classList.add('wysiwyg-initialized');
        }
    });
}

// Sync all Quill editors before form submission
function syncTextQuillEditors() {
    if (window.textQuillInstances) {
        window.textQuillInstances.forEach(function(instance) {
            if (instance.quill && instance.textarea) {
                instance.textarea.value = instance.quill.root.innerHTML;
            }
        });
    }
}

// Initialize existing editors on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize immediately
    initializeTextWysiwygEditors();
});

// Also initialize when content becomes visible (for update forms)
document.addEventListener('shown.bs.modal', function(event) {
    // Initialize WYSIWYG editors when modal is shown
    setTimeout(function() {
        initializeTextWysiwygEditors();
    }, 100);
});

// Initialize when tabs are shown
document.addEventListener('shown.bs.tab', function(event) {
    // Initialize WYSIWYG editors when tab is shown
    setTimeout(function() {
        initializeTextWysiwygEditors();
    }, 100);
});

// Initialize immediately if Quill is already available
if (typeof Quill !== 'undefined') {
    // Initialize right away if Quill is loaded
    setTimeout(function() {
        initializeTextWysiwygEditors();
    }, 100);
}

// Observe for new content being added to the DOM
if (typeof window.textObserver === 'undefined') {
    window.textObserver = new MutationObserver(function(mutations) {
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
            initializeTextWysiwygEditors();
        }, 100);
    }
});

    // Start observing
    window.textObserver.observe(document.body, {
        childList: true,
        subtree: true
    });
}
</script>
