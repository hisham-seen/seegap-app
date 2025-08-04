<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_block" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="accordion" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <?php
    // Use the reusable accordion block form panel
    $block_id = $row->microsite_block_id;
    $settings = $row->settings;
    $form_type = 'update';
    include THEME_PATH . 'views/partials/microsite_block_components/accordion_block_form_panel.php';
    ?>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<template id="template_accordion_item_<?= $row->microsite_block_id ?>">
    <div class="accordion-item-wrapper mb-3 border rounded" data-accordion-item>
        <!-- Drag Handle and Header -->
        <div class="accordion-item-header d-flex align-items-center justify-content-between p-3 bg-light border-bottom" style="cursor: pointer;" data-toggle="collapse" data-target="#accordion-item-content-<?= $row->microsite_block_id ?>-{index}">
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
        <div id="accordion-item-content-<?= $row->microsite_block_id ?>-{index}" class="collapse show">
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
            </div>
        </div>
    </div>
</template>

<?php ob_start() ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<style>
.accordion-item-wrapper {
    transition: all 0.2s ease;
}

.accordion-item-wrapper:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.accordion-item-header {
    transition: background-color 0.2s ease;
}

.accordion-item-header:hover {
    background-color: #e9ecef !important;
}

.accordion-toggle-icon {
    transition: transform 0.2s ease;
}

.accordion-item-header[aria-expanded="true"] .accordion-toggle-icon {
    transform: rotate(180deg);
}

.sortable-ghost {
    opacity: 0.4;
    background: #f8f9fa;
}

.sortable-chosen {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.sortable-drag {
    transform: rotate(5deg);
}

.drag-handle:hover {
    color: #007bff !important;
}
</style>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
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

// Initialize existing editors on page load
document.addEventListener('DOMContentLoaded', function() {
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
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
