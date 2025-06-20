<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="image_grid" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <!-- Image Upload Section -->
    <div class="form-group">
        <label for="<?= 'new_images_' . $row->microsite_block_id ?>">
            <i class="fas fa-fw fa-images fa-sm text-muted mr-1"></i> <?= l('global.images') ?>
        </label>
        <input 
            id="<?= 'new_images_' . $row->microsite_block_id ?>" 
            type="file" 
            name="new_images[]" 
            multiple 
            accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image_grid']['whitelisted_image_extensions']) ?>" 
            class="form-control-file" 
        />
        <small class="form-text text-muted">
            <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image_grid']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?>
            <br>Hold Ctrl/Cmd to select multiple images.
        </small>
    </div>

    <!-- Current Images Display -->
    <?php 
    // Handle both new (items) and legacy (images) data structures
    $items_to_display = [];
    if(!empty($row->settings->items)) {
        $items_to_display = $row->settings->items;
    } elseif(!empty($row->settings->images)) {
        $items_to_display = $row->settings->images;
    }
    ?>
    
    <?php if(!empty($items_to_display)): ?>
        <div class="mb-3">
            <h6><i class="fas fa-fw fa-images fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.current_images') ?> (<?= count($items_to_display) ?>)</h6>
            <small class="text-muted d-block mb-2">
                <i class="fas fa-arrows-alt fa-xs mr-1"></i> Drag to reorder • 
                <i class="fas fa-edit fa-xs mr-1"></i> Click to edit • 
                <i class="fas fa-trash fa-xs mr-1"></i> Click × to remove
            </small>
            <div class="row" id="image-grid-<?= $row->microsite_block_id ?>">
                <?php foreach($items_to_display as $key => $item): ?>
                    <div class="col-4 mb-2 image-item" data-index="<?= $key ?>" data-image="<?= is_object($item) ? $item->image : $item['image'] ?>">
                        <div class="card h-100 image-card">
                            <div class="position-relative">
                                <img 
                                    src="<?= \SeeGap\Uploads::get_full_url('block_images') . (is_object($item) ? $item->image : $item['image']) ?>" 
                                    class="card-img-top" 
                                    style="height: 60px; object-fit: cover; cursor: pointer;" 
                                    alt="Image <?= $key + 1 ?>" 
                                    onclick="editImage(<?= $row->microsite_block_id ?>, <?= $key ?>, '<?= is_object($item) ? $item->image : $item['image'] ?>', '<?= is_object($item) ? ($item->image_alt ?? '') : ($item['image_alt'] ?? '') ?>', '<?= is_object($item) ? ($item->location_url ?? '') : ($item['location_url'] ?? '') ?>')"
                                />
                                <div class="position-absolute" style="top: 2px; right: 2px;">
                                    <span class="badge badge-dark badge-sm" style="font-size: 0.7rem;"><?= $key + 1 ?></span>
                                </div>
                                <div class="position-absolute" style="top: 2px; left: 2px;">
                                    <button type="button" class="btn btn-danger btn-sm" style="padding: 1px 4px; font-size: 0.6rem;" onclick="removeImage(<?= $row->microsite_block_id ?>, <?= $key ?>)" title="Remove image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="position-absolute drag-handle" style="bottom: 2px; right: 2px; cursor: move;" title="Drag to reorder">
                                    <i class="fas fa-grip-vertical text-white" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7); font-size: 0.8rem;"></i>
                                </div>
                            </div>
                            <div class="card-body p-1">
                                <small class="text-muted d-block text-truncate" style="font-size: 0.7rem;" title="<?= is_object($item) ? $item->image : $item['image'] ?>"><?= is_object($item) ? $item->image : $item['image'] ?></small>
                                <?php 
                                $alt_text = is_object($item) ? ($item->image_alt ?? '') : ($item['image_alt'] ?? '');
                                if(!empty($alt_text)): 
                                ?>
                                    <small class="text-info d-block text-truncate" style="font-size: 0.65rem;" title="<?= $alt_text ?>">Alt: <?= $alt_text ?></small>
                                <?php endif ?>
                                <?php 
                                $location_url = is_object($item) ? ($item->location_url ?? '') : ($item['location_url'] ?? '');
                                if(!empty($location_url)): 
                                ?>
                                    <small class="text-success d-block text-truncate" style="font-size: 0.65rem;" title="<?= $location_url ?>">
                                        <i class="fas fa-link fa-xs"></i> Link
                                    </small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            
            <!-- Hidden inputs to store image order and data -->
            <input type="hidden" name="image_order" id="image-order-<?= $row->microsite_block_id ?>" value="<?= implode(',', array_keys($items_to_display)) ?>">
            <input type="hidden" name="images_data" id="images-data-<?= $row->microsite_block_id ?>" value="<?= htmlspecialchars(json_encode($items_to_display)) ?>">
        </div>
    <?php else: ?>
        <div class="mb-3">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                No images uploaded yet. Use the upload section above to add images to your grid.
            </div>
        </div>
    <?php endif ?>

    <!-- Basic Settings -->
    <div class="form-group custom-control custom-switch">
        <input
            id="<?= 'open_in_new_tab_' . $row->microsite_block_id ?>"
            name="open_in_new_tab" 
            type="checkbox"
            class="custom-control-input"
            <?= ($row->settings->open_in_new_tab ?? false) ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'open_in_new_tab_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> <?= l('microsite_link.open_in_new_tab') ?></label>
    </div>

    <!-- Grid Layout Settings -->
    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'grid_layout_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'grid_layout_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-th fa-sm mr-1"></i> <?= l('microsite_image_grid.grid_layout_settings') ?>
    </button>

    <div class="collapse" id="<?= 'grid_layout_container_' . $row->microsite_block_id ?>">
        <!-- Columns -->
        <div class="form-group">
            <label for="<?= 'columns_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-columns fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.columns') ?></label>
            <select id="<?= 'columns_' . $row->microsite_block_id ?>" name="columns" class="custom-select">
                <option value="1" <?= ($row->settings->columns ?? 3) == 1 ? 'selected' : '' ?>><?= l('microsite_image_grid.columns_1') ?></option>
                <option value="2" <?= ($row->settings->columns ?? 3) == 2 ? 'selected' : '' ?>><?= l('microsite_image_grid.columns_2') ?></option>
                <option value="3" <?= ($row->settings->columns ?? 3) == 3 ? 'selected' : '' ?>><?= l('microsite_image_grid.columns_3') ?></option>
                <option value="4" <?= ($row->settings->columns ?? 3) == 4 ? 'selected' : '' ?>><?= l('microsite_image_grid.columns_4') ?></option>
                <option value="5" <?= ($row->settings->columns ?? 3) == 5 ? 'selected' : '' ?>><?= l('microsite_image_grid.columns_5') ?></option>
                <option value="6" <?= ($row->settings->columns ?? 3) == 6 ? 'selected' : '' ?>><?= l('microsite_image_grid.columns_6') ?></option>
            </select>
        </div>

        <!-- Grid Gap -->
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'grid_gap_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.grid_gap') ?></label>
            <input 
                id="<?= 'grid_gap_' . $row->microsite_block_id ?>" 
                type="range" 
                min="0" 
                max="50" 
                step="5"
                name="grid_gap" 
                class="form-control-range" 
                value="<?= $row->settings->grid_gap ?? 10 ?>" 
                required="required"
            />
        </div>
    </div>

    <!-- Visual & Layout Settings -->
    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'visual_settings_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'visual_settings_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-palette fa-sm mr-1"></i> <?= l('microsite_image_grid.visual_layout_settings') ?>
    </button>

    <div class="collapse" id="<?= 'visual_settings_container_' . $row->microsite_block_id ?>">
        <!-- Image Height -->
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'image_height_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.image_height') ?></label>
            <input 
                id="<?= 'image_height_' . $row->microsite_block_id ?>" 
                type="range" 
                min="100" 
                max="500" 
                step="10"
                name="image_height" 
                class="form-control-range" 
                value="<?= $row->settings->image_height ?? 200 ?>" 
                required="required"
            />
        </div>

        <!-- Aspect Ratio -->
        <div class="form-group">
            <label for="<?= 'aspect_ratio_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.aspect_ratio') ?></label>
            <select id="<?= 'aspect_ratio_' . $row->microsite_block_id ?>" name="aspect_ratio" class="custom-select">
                <option value="custom" <?= ($row->settings->aspect_ratio ?? '1:1') == 'custom' ? 'selected' : '' ?>><?= l('microsite_image_grid.aspect_ratio_custom') ?></option>
                <option value="16:9" <?= ($row->settings->aspect_ratio ?? '') == '16:9' ? 'selected' : '' ?>><?= l('microsite_image_grid.aspect_ratio_16_9') ?></option>
                <option value="4:3" <?= ($row->settings->aspect_ratio ?? '') == '4:3' ? 'selected' : '' ?>><?= l('microsite_image_grid.aspect_ratio_4_3') ?></option>
                <option value="1:1" <?= ($row->settings->aspect_ratio ?? '1:1') == '1:1' ? 'selected' : '' ?>><?= l('microsite_image_grid.aspect_ratio_1_1') ?></option>
                <option value="21:9" <?= ($row->settings->aspect_ratio ?? '') == '21:9' ? 'selected' : '' ?>><?= l('microsite_image_grid.aspect_ratio_21_9') ?></option>
            </select>
        </div>

        <!-- Image Fit -->
        <div class="form-group">
            <label for="<?= 'image_fit_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.image_fit') ?></label>
            <select id="<?= 'image_fit_' . $row->microsite_block_id ?>" name="image_fit" class="custom-select">
                <option value="cover" <?= ($row->settings->image_fit ?? 'cover') == 'cover' ? 'selected' : '' ?>><?= l('microsite_image_grid.image_fit_cover') ?></option>
                <option value="contain" <?= ($row->settings->image_fit ?? '') == 'contain' ? 'selected' : '' ?>><?= l('microsite_image_grid.image_fit_contain') ?></option>
                <option value="fill" <?= ($row->settings->image_fit ?? '') == 'fill' ? 'selected' : '' ?>><?= l('microsite_image_grid.image_fit_fill') ?></option>
                <option value="scale-down" <?= ($row->settings->image_fit ?? '') == 'scale-down' ? 'selected' : '' ?>><?= l('microsite_image_grid.image_fit_scale_down') ?></option>
            </select>
        </div>

        <!-- Border Radius -->
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'border_radius_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.border_radius') ?></label>
            <input 
                id="<?= 'border_radius_' . $row->microsite_block_id ?>" 
                type="range" 
                min="0" 
                max="50" 
                step="1"
                name="border_radius" 
                class="form-control-range" 
                value="<?= $row->settings->border_radius ?? 0 ?>" 
                required="required"
            />
        </div>

        <!-- Hover Effect -->
        <div class="form-group">
            <label for="<?= 'hover_effect_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-magic fa-sm text-muted mr-1"></i> <?= l('microsite_image_grid.hover_effect') ?></label>
            <select id="<?= 'hover_effect_' . $row->microsite_block_id ?>" name="hover_effect" class="custom-select">
                <option value="none" <?= ($row->settings->hover_effect ?? 'none') == 'none' ? 'selected' : '' ?>><?= l('microsite_image_grid.hover_effect_none') ?></option>
                <option value="zoom" <?= ($row->settings->hover_effect ?? '') == 'zoom' ? 'selected' : '' ?>><?= l('microsite_image_grid.hover_effect_zoom') ?></option>
                <option value="fade" <?= ($row->settings->hover_effect ?? '') == 'fade' ? 'selected' : '' ?>><?= l('microsite_image_grid.hover_effect_fade') ?></option>
                <option value="lift" <?= ($row->settings->hover_effect ?? '') == 'lift' ? 'selected' : '' ?>><?= l('microsite_image_grid.hover_effect_lift') ?></option>
            </select>
        </div>

    </div>

    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<!-- Image Edit Modal -->
<div class="modal fade" id="editImageModal-<?= $row->microsite_block_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Edit Image
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Current Image</label>
                    <div class="text-center mb-3">
                        <img id="editImagePreview-<?= $row->microsite_block_id ?>" src="" class="img-fluid" style="max-height: 150px; border-radius: 8px;">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="editImageFile-<?= $row->microsite_block_id ?>">
                        <i class="fas fa-image mr-1"></i>Replace Image (Optional)
                    </label>
                    <input type="file" id="editImageFile-<?= $row->microsite_block_id ?>" class="form-control-file" accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['image_grid']['whitelisted_image_extensions']) ?>">
                    <small class="form-text text-muted">Leave empty to keep current image</small>
                </div>
                
                <div class="form-group">
                    <label for="editImageAlt-<?= $row->microsite_block_id ?>">
                        <i class="fas fa-tag mr-1"></i>Alt Text
                    </label>
                    <input type="text" id="editImageAlt-<?= $row->microsite_block_id ?>" class="form-control" placeholder="Describe this image..." maxlength="255">
                    <small class="form-text text-muted">Helps with accessibility and SEO</small>
                </div>
                
                <div class="form-group">
                    <label for="editImageUrl-<?= $row->microsite_block_id ?>">
                        <i class="fas fa-link mr-1"></i>Link URL (Optional)
                    </label>
                    <input type="url" id="editImageUrl-<?= $row->microsite_block_id ?>" class="form-control" placeholder="https://example.com">
                    <small class="form-text text-muted">Where should this image link to when clicked?</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveImageEdit(<?= $row->microsite_block_id ?>)">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockId = <?= $row->microsite_block_id ?>;
    
    // Initialize drag and drop functionality
    initializeDragAndDrop(blockId);
});

function initializeDragAndDrop(blockId) {
    const grid = document.getElementById('image-grid-' + blockId);
    if (!grid) return;
    
    let draggedElement = null;
    
    // Add drag event listeners to all image items
    const imageItems = grid.querySelectorAll('.image-item');
    imageItems.forEach(item => {
        item.draggable = true;
        
        item.addEventListener('dragstart', function(e) {
            draggedElement = this;
            this.style.opacity = '0.5';
            e.dataTransfer.effectAllowed = 'move';
        });
        
        item.addEventListener('dragend', function(e) {
            this.style.opacity = '';
            draggedElement = null;
        });
        
        item.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });
        
        item.addEventListener('drop', function(e) {
            e.preventDefault();
            if (draggedElement && draggedElement !== this) {
                // Swap the elements
                const draggedIndex = Array.from(grid.children).indexOf(draggedElement);
                const targetIndex = Array.from(grid.children).indexOf(this);
                
                if (draggedIndex < targetIndex) {
                    this.parentNode.insertBefore(draggedElement, this.nextSibling);
                } else {
                    this.parentNode.insertBefore(draggedElement, this);
                }
                
                updateImageOrder(blockId);
                updateImageNumbers(blockId);
            }
        });
    });
}

function updateImageOrder(blockId) {
    const grid = document.getElementById('image-grid-' + blockId);
    const orderInput = document.getElementById('image-order-' + blockId);
    
    if (grid && orderInput) {
        const items = grid.querySelectorAll('.image-item');
        const order = Array.from(items).map(item => item.dataset.index);
        orderInput.value = order.join(',');
    }
}

function updateImageNumbers(blockId) {
    const grid = document.getElementById('image-grid-' + blockId);
    if (!grid) return;
    
    const items = grid.querySelectorAll('.image-item');
    items.forEach((item, index) => {
        const badge = item.querySelector('.badge');
        if (badge) {
            badge.textContent = index + 1;
        }
    });
}

function editImage(blockId, index, imageName, altText, linkUrl) {
    const modal = document.getElementById('editImageModal-' + blockId);
    const preview = document.getElementById('editImagePreview-' + blockId);
    const altInput = document.getElementById('editImageAlt-' + blockId);
    const urlInput = document.getElementById('editImageUrl-' + blockId);
    
    // Set current values
    preview.src = '<?= \SeeGap\Uploads::get_full_url('block_images') ?>' + imageName;
    altInput.value = altText || '';
    urlInput.value = linkUrl || '';
    
    // Store current editing info
    modal.dataset.editingIndex = index;
    modal.dataset.currentImage = imageName;
    
    // Show modal
    $(modal).modal('show');
}

function saveImageEdit(blockId) {
    const modal = document.getElementById('editImageModal-' + blockId);
    const index = modal.dataset.editingIndex;
    const altInput = document.getElementById('editImageAlt-' + blockId);
    const urlInput = document.getElementById('editImageUrl-' + blockId);
    const fileInput = document.getElementById('editImageFile-' + blockId);
    const imagesDataInput = document.getElementById('images-data-' + blockId);
    
    try {
        // Get current images data
        let imagesData = JSON.parse(imagesDataInput.value);
        
        // Update the specific image data
        if (imagesData[index]) {
            imagesData[index].image_alt = altInput.value;
            imagesData[index].location_url = urlInput.value;
            
            // Update the hidden input
            imagesDataInput.value = JSON.stringify(imagesData);
            
            // Update the display
            const imageItem = document.querySelector(`[data-index="${index}"]`);
            if (imageItem) {
                const altDisplay = imageItem.querySelector('.text-info');
                const linkDisplay = imageItem.querySelector('.text-success');
                
                // Update alt text display
                if (altInput.value) {
                    if (altDisplay) {
                        altDisplay.textContent = 'Alt: ' + altInput.value;
                        altDisplay.title = altInput.value;
                    } else {
                        const cardBody = imageItem.querySelector('.card-body');
                        const altElement = document.createElement('small');
                        altElement.className = 'text-info d-block text-truncate';
                        altElement.style.fontSize = '0.65rem';
                        altElement.textContent = 'Alt: ' + altInput.value;
                        altElement.title = altInput.value;
                        cardBody.appendChild(altElement);
                    }
                } else if (altDisplay) {
                    altDisplay.remove();
                }
                
                // Update link display
                if (urlInput.value) {
                    if (linkDisplay) {
                        linkDisplay.title = urlInput.value;
                    } else {
                        const cardBody = imageItem.querySelector('.card-body');
                        const linkElement = document.createElement('small');
                        linkElement.className = 'text-success d-block text-truncate';
                        linkElement.style.fontSize = '0.65rem';
                        linkElement.innerHTML = '<i class="fas fa-link fa-xs"></i> Link';
                        linkElement.title = urlInput.value;
                        cardBody.appendChild(linkElement);
                    }
                } else if (linkDisplay) {
                    linkDisplay.remove();
                }
            }
            
            // Handle file replacement if provided
            if (fileInput.files && fileInput.files[0]) {
                // For now, just show a message that the file will be replaced on save
                // The actual file upload will be handled by the form submission
                console.log('New file selected for replacement');
            }
            
            // Close modal
            $(modal).modal('hide');
            
            // Clear form
            altInput.value = '';
            urlInput.value = '';
            fileInput.value = '';
            
        }
    } catch (e) {
        console.error('Error saving image edit:', e);
        alert('Error saving changes. Please try again.');
    }
}

function removeImage(blockId, index) {
    if (!confirm('Are you sure you want to remove this image?')) {
        return;
    }
    
    const imageItem = document.querySelector(`[data-index="${index}"]`);
    const imagesDataInput = document.getElementById('images-data-' + blockId);
    
    if (imageItem && imagesDataInput) {
        try {
            // Remove from data
            let imagesData = JSON.parse(imagesDataInput.value);
            imagesData.splice(index, 1);
            imagesDataInput.value = JSON.stringify(imagesData);
            
            // Remove from DOM
            imageItem.remove();
            
            // Update order and numbers
            updateImageOrder(blockId);
            updateImageNumbers(blockId);
            
            // Update the count in the header
            const header = document.querySelector('h6');
            if (header) {
                const countMatch = header.textContent.match(/\((\d+)\)/);
                if (countMatch) {
                    const newCount = parseInt(countMatch[1]) - 1;
                    header.textContent = header.textContent.replace(/\(\d+\)/, `(${newCount})`);
                }
            }
            
            // Re-initialize drag and drop for remaining items
            initializeDragAndDrop(blockId);
            
        } catch (e) {
            console.error('Error removing image:', e);
            alert('Error removing image. Please try again.');
        }
    }
}
</script>
