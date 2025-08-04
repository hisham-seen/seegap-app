<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Dynamic Item Manager Component for Microsite Blocks
 * Provides add/remove functionality for dynamic lists of items
 * 
 * @param string $block_id - Unique identifier for the block
 * @param string $container_id - ID for the items container
 * @param string $template_id - ID for the item template
 * @param array $items - Current items array
 * @param array $fields - Array of field configurations
 * @param string $add_button_text - Text for add button (default: 'Add Item')
 * @param string $remove_button_text - Text for remove button (default: 'Remove')
 * @param int $max_items - Maximum number of items allowed (default: 100)
 */

$block_id = $block_id ?? 'default';
$container_id = $container_id ?? 'items_container_' . $block_id;
$template_id = $template_id ?? 'template_item_' . $block_id;
$items = $items ?? [];
$fields = $fields ?? [
    ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'icon' => 'fas fa-signature', 'required' => true],
    ['name' => 'content', 'type' => 'textarea', 'label' => 'Content', 'icon' => 'fas fa-pen', 'required' => true]
];
$add_button_text = $add_button_text ?? 'Add Item';
$remove_button_text = $remove_button_text ?? 'Remove';
$max_items = $max_items ?? 100;
?>

<!-- Items Container -->
<div id="<?= $container_id ?>" data-microsite-block-id="<?= $block_id ?>">
    <?php if(!empty($items)): ?>
        <?php foreach($items as $key => $item): ?>
            <div class="dynamic-item mb-4">
                <?php foreach($fields as $field): ?>
                    <div class="form-group">
                        <label for="<?= $field['name'] . '_' . $key . '_' . $block_id ?>">
                            <i class="<?= $field['icon'] ?? 'fas fa-edit' ?> fa-fw fa-sm text-muted mr-1"></i> 
                            <?= $field['label'] ?>
                        </label>
                        
                        <?php if($field['type'] === 'textarea'): ?>
                            <textarea 
                                id="<?= $field['name'] . '_' . $key . '_' . $block_id ?>" 
                                name="<?= $field['name'] ?>[<?= $key ?>]" 
                                class="form-control" 
                                <?= ($field['required'] ?? false) ? 'required="required"' : '' ?>
                                <?= isset($field['rows']) ? 'rows="' . $field['rows'] . '"' : '' ?>
                                <?= isset($field['maxlength']) ? 'maxlength="' . $field['maxlength'] . '"' : '' ?>
                            ><?= $item->{$field['name']} ?? '' ?></textarea>
                        <?php else: ?>
                            <input 
                                id="<?= $field['name'] . '_' . $key . '_' . $block_id ?>" 
                                type="<?= $field['type'] ?>" 
                                name="<?= $field['name'] ?>[<?= $key ?>]" 
                                class="form-control" 
                                value="<?= $item->{$field['name']} ?? '' ?>" 
                                <?= ($field['required'] ?? false) ? 'required="required"' : '' ?>
                                <?= isset($field['maxlength']) ? 'maxlength="' . $field['maxlength'] . '"' : '' ?>
                                <?= isset($field['placeholder']) ? 'placeholder="' . $field['placeholder'] . '"' : '' ?>
                            />
                        <?php endif ?>
                        
                        <?php if(isset($field['help'])): ?>
                            <small class="form-text text-muted"><?= $field['help'] ?></small>
                        <?php endif ?>
                    </div>
                <?php endforeach ?>
                
                <button type="button" data-remove="item" class="btn btn-sm btn-block btn-outline-danger">
                    <i class="fas fa-fw fa-times"></i> <?= $remove_button_text ?>
                </button>
            </div>
        <?php endforeach ?>
    <?php endif ?>
</div>

<!-- Add Button -->
<div class="mb-3">
    <button 
        data-add="dynamic_item" 
        data-container-id="<?= $container_id ?>"
        data-template-id="<?= $template_id ?>"
        data-max-items="<?= $max_items ?>"
        type="button" 
        class="btn btn-outline-success btn-block"
    >
        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= $add_button_text ?>
    </button>
</div>

<!-- Template for New Items -->
<template id="<?= $template_id ?>">
    <div class="dynamic-item mb-4">
        <?php foreach($fields as $field): ?>
            <div class="form-group">
                <label>
                    <i class="<?= $field['icon'] ?? 'fas fa-edit' ?> fa-fw fa-sm text-muted mr-1"></i> 
                    <?= $field['label'] ?>
                </label>
                
                <?php if($field['type'] === 'textarea'): ?>
                    <textarea 
                        name="<?= $field['name'] ?>[]" 
                        class="form-control" 
                        <?= ($field['required'] ?? false) ? 'required="required"' : '' ?>
                        <?= isset($field['rows']) ? 'rows="' . $field['rows'] . '"' : '' ?>
                        <?= isset($field['maxlength']) ? 'maxlength="' . $field['maxlength'] . '"' : '' ?>
                    ></textarea>
                <?php else: ?>
                    <input 
                        type="<?= $field['type'] ?>" 
                        name="<?= $field['name'] ?>[]" 
                        class="form-control" 
                        value="" 
                        <?= ($field['required'] ?? false) ? 'required="required"' : '' ?>
                        <?= isset($field['maxlength']) ? 'maxlength="' . $field['maxlength'] . '"' : '' ?>
                        <?= isset($field['placeholder']) ? 'placeholder="' . $field['placeholder'] . '"' : '' ?>
                    />
                <?php endif ?>
                
                <?php if(isset($field['help'])): ?>
                    <small class="form-text text-muted"><?= $field['help'] ?></small>
                <?php endif ?>
            </div>
        <?php endforeach ?>
        
        <button type="button" data-remove="item" class="btn btn-sm btn-block btn-outline-danger">
            <i class="fas fa-fw fa-times"></i> <?= $remove_button_text ?>
        </button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic Item Manager for <?= $container_id ?>
    const containerId = '<?= $container_id ?>';
    const templateId = '<?= $template_id ?>';
    const maxItems = <?= $max_items ?>;
    
    // Add new item
    const addButton = document.querySelector('[data-add="dynamic_item"][data-container-id="' + containerId + '"]');
    if (addButton) {
        addButton.addEventListener('click', function() {
            const container = document.getElementById(containerId);
            const template = document.getElementById(templateId);
            const currentItems = container.querySelectorAll('.dynamic-item');
            
            if (currentItems.length >= maxItems) {
                alert('Maximum number of items reached (' + maxItems + ')');
                return;
            }
            
            const clone = template.content.cloneNode(true);
            const count = currentItems.length;
            
            // Update field names with proper indexing
            <?php foreach($fields as $field): ?>
            const <?= $field['name'] ?>Field = clone.querySelector('[name="<?= $field['name'] ?>[]"]');
            if (<?= $field['name'] ?>Field) {
                <?= $field['name'] ?>Field.setAttribute('name', '<?= $field['name'] ?>[' + count + ']');
                <?= $field['name'] ?>Field.setAttribute('id', '<?= $field['name'] ?>_' + count + '_<?= $block_id ?>');
            }
            <?php endforeach ?>
            
            container.appendChild(clone);
            initializeRemoveButtons();
        });
    }
    
    // Remove item functionality
    function removeItem(event) {
        const container = document.getElementById(containerId);
        const items = container.querySelectorAll('.dynamic-item');
        
        if (items.length <= 1) {
            // Clear the last item instead of removing it
            const item = event.currentTarget.closest('.dynamic-item');
            const inputs = item.querySelectorAll('input, textarea');
            inputs.forEach(input => input.value = '');
        } else {
            event.currentTarget.closest('.dynamic-item').remove();
        }
    }
    
    function initializeRemoveButtons() {
        const removeButtons = document.querySelectorAll('#' + containerId + ' [data-remove="item"]');
        removeButtons.forEach(button => {
            button.removeEventListener('click', removeItem);
            button.addEventListener('click', removeItem);
        });
    }
    
    // Initialize existing remove buttons
    initializeRemoveButtons();
});
</script>
