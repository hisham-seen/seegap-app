<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Image Grid Manager Component for Microsite Blocks
 * Provides comprehensive image grid management with drag/drop, editing, and layout controls
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param array $whitelisted_extensions - Allowed image file extensions
 * @param int $size_limit - Maximum file size limit
 * @param string $uploads_file_key - Upload directory key (default: 'block_images')
 * @param bool $show_upload - Whether to show the upload section (default: true)
 * @param bool $show_grid_settings - Whether to show grid layout settings (default: true)
 * @param bool $show_visual_settings - Whether to show visual settings (default: true)
 * @param int $max_images - Maximum number of images allowed (default: 50)
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$whitelisted_extensions = $whitelisted_extensions ?? ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$size_limit = $size_limit ?? (2 * 1024 * 1024); // 2MB default
$uploads_file_key = $uploads_file_key ?? 'block_images';
$show_upload = $show_upload ?? true;
$show_grid_settings = $show_grid_settings ?? true;
$show_visual_settings = $show_visual_settings ?? true;
$max_images = $max_images ?? 50;

// Handle both new (items) and legacy (images) data structures
$items_to_display = [];
if(!empty($settings->items)) {
    $items_to_display = $settings->items;
} elseif(!empty($settings->images)) {
    $items_to_display = $settings->images;
}
?>

<div class="image-grid-manager" id="image-grid-manager-<?= $block_id ?>">
    
    <?php if($show_upload): ?>
        <!-- Image Upload Section -->
        <div class="form-group">
            <label for="new_images_<?= $block_id ?>">
                <i class="fas fa-fw fa-images fa-sm text-muted mr-1"></i> 
                <?= l('global.images') ?>
                <?php if(count($items_to_display) > 0): ?>
                    <span class="badge badge-secondary ml-2"><?= count($items_to_display) ?>/<?= $max_images ?></span>
                <?php endif ?>
            </label>
            <input 
                id="new_images_<?= $block_id ?>" 
                type="file" 
                name="new_images[]" 
                multiple 
                accept="<?= \SeeGap\Uploads::array_to_list_format($whitelisted_extensions) ?>" 
                class="form-control-file image-grid-upload" 
                data-max-images="<?= $max_images ?>"
                data-current-count="<?= count($items_to_display) ?>"
            />
            <small class="form-text text-muted">
                <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($whitelisted_extensions)) . ' ' . sprintf(l('global.accessibility.file_size_limit'), $size_limit) ?>
                <br><i class="fas fa-info-circle fa-xs mr-1"></i>Hold Ctrl/Cmd to select multiple images. Maximum <?= $max_images ?> images.
            </small>
        </div>
    <?php endif ?>

    <!-- Current Images Display -->
    <?php if(!empty($items_to_display)): ?>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">
                    <i class="fas fa-fw fa-images fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_image_grid.current_images') ?? 'Current Images' ?> 
                    <span class="badge badge-primary ml-1"><?= count($items_to_display) ?></span>
                </h6>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary" onclick="selectAllImages('<?= $block_id ?>')" title="Select All">
                        <i class="fas fa-check-square fa-xs"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="removeSelectedImages('<?= $block_id ?>')" title="Remove Selected">
                        <i class="fas fa-trash fa-xs"></i>
                    </button>
                </div>
            </div>
            
            <small class="text-muted d-block mb-3">
                <i class="fas fa-arrows-alt fa-xs mr-1"></i> Drag to reorder • 
                <i class="fas fa-edit fa-xs mr-1"></i> Click to edit • 
                <i class="fas fa-check-square fa-xs mr-1"></i> Click checkbox to select • 
                <i class="fas fa-trash fa-xs mr-1"></i> Use toolbar to remove selected
            </small>
            
            <div class="row image-grid-container" id="image-grid-<?= $block_id ?>" data-block-id="<?= $block_id ?>">
                <?php foreach($items_to_display as $key => $item): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3 image-item" data-index="<?= $key ?>" data-image="<?= is_object($item) ? $item->image : $item['image'] ?>">
                        <div class="card h-100 image-card">
                            <div class="position-relative">
                                <img 
                                    src="<?= \SeeGap\Uploads::get_full_url($uploads_file_key) . (is_object($item) ? $item->image : $item['image']) ?>" 
                                    class="card-img-top image-preview" 
                                    style="height: 120px; object-fit: cover; cursor: pointer;" 
                                    alt="Image <?= $key + 1 ?>" 
                                    onclick="editImage('<?= $block_id ?>', <?= $key ?>, '<?= is_object($item) ? $item->image : $item['image'] ?>', '<?= is_object($item) ? ($item->image_alt ?? '') : ($item['image_alt'] ?? '') ?>', '<?= is_object($item) ? ($item->location_url ?? '') : ($item['location_url'] ?? '') ?>')"
                                />
                                
                                <!-- Image Controls -->
                                <div class="image-controls">
                                    <!-- Selection Checkbox -->
                                    <div class="position-absolute" style="top: 4px; left: 4px;">
                                        <input type="checkbox" class="image-selector" data-index="<?= $key ?>" style="transform: scale(1.2);">
                                    </div>
                                    
                                    <!-- Image Number -->
                                    <div class="position-absolute" style="top: 4px; right: 4px;">
                                        <span class="badge badge-dark image-number" style="font-size: 0.7rem;"><?= $key + 1 ?></span>
                                    </div>
                                    
                                    <!-- Quick Remove -->
                                    <div class="position-absolute" style="bottom: 4px; left: 4px;">
                                        <button type="button" class="btn btn-danger btn-sm quick-remove" style="padding: 2px 6px; font-size: 0.7rem;" onclick="removeImage('<?= $block_id ?>', <?= $key ?>)" title="Remove image">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Drag Handle -->
                                    <div class="position-absolute drag-handle" style="bottom: 4px; right: 4px; cursor: move;" title="Drag to reorder">
                                        <i class="fas fa-grip-vertical text-white" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.8); font-size: 0.9rem;"></i>
                                    </div>
                                </div>
                                
                                <!-- Image Overlay for Better Visibility -->
                                <div class="image-overlay"></div>
                            </div>
                            
                            <div class="card-body p-2">
                                <small class="text-muted d-block text-truncate" style="font-size: 0.75rem;" title="<?= is_object($item) ? $item->image : $item['image'] ?>">
                                    <?= is_object($item) ? $item->image : $item['image'] ?>
                                </small>
                                
                                <?php 
                                $alt_text = is_object($item) ? ($item->image_alt ?? '') : ($item['image_alt'] ?? '');
                                if(!empty($alt_text)): 
                                ?>
                                    <small class="text-info d-block text-truncate" style="font-size: 0.7rem;" title="<?= $alt_text ?>">
                                        <i class="fas fa-tag fa-xs mr-1"></i><?= $alt_text ?>
                                    </small>
                                <?php endif ?>
                                
                                <?php 
                                $location_url = is_object($item) ? ($item->location_url ?? '') : ($item['location_url'] ?? '');
                                if(!empty($location_url)): 
                                ?>
                                    <small class="text-success d-block text-truncate" style="font-size: 0.7rem;" title="<?= $location_url ?>">
                                        <i class="fas fa-link fa-xs mr-1"></i>Linked
                                    </small>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            
            <!-- Hidden inputs to store image order and data -->
            <input type="hidden" name="image_order" id="image-order-<?= $block_id ?>" value="<?= implode(',', array_keys($items_to_display)) ?>">
            <input type="hidden" name="images_data" id="images-data-<?= $block_id ?>" value="<?= htmlspecialchars(json_encode($items_to_display)) ?>">
        </div>
    <?php else: ?>
        <div class="mb-3">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                <?= l('microsite_image_grid.no_images') ?? 'No images uploaded yet. Use the upload section above to add images to your grid.' ?>
            </div>
        </div>
    <?php endif ?>

    <?php if($show_grid_settings): ?>
        <!-- Grid Layout Settings -->
        <div class="card mb-3">
            <div class="card-header" data-toggle="collapse" data-target="#grid_layout_container_<?= $block_id ?>" aria-expanded="false" style="cursor: pointer;">
                <h6 class="mb-0">
                    <i class="fas fa-fw fa-th fa-sm text-muted mr-2"></i>
                    <?= l('microsite_image_grid.grid_layout_settings') ?? 'Grid Layout Settings' ?>
                    <i class="fas fa-chevron-down float-right"></i>
                </h6>
            </div>
            <div id="grid_layout_container_<?= $block_id ?>" class="collapse">
                <div class="card-body">
                    <!-- Columns -->
                    <div class="form-group">
                        <label for="columns_<?= $block_id ?>">
                            <i class="fas fa-fw fa-columns fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.columns') ?? 'Columns' ?>
                        </label>
                        <select id="columns_<?= $block_id ?>" name="columns" class="custom-select">
                            <option value="1" <?= ($settings->columns ?? 3) == 1 ? 'selected' : '' ?>>1 Column</option>
                            <option value="2" <?= ($settings->columns ?? 3) == 2 ? 'selected' : '' ?>>2 Columns</option>
                            <option value="3" <?= ($settings->columns ?? 3) == 3 ? 'selected' : '' ?>>3 Columns</option>
                            <option value="4" <?= ($settings->columns ?? 3) == 4 ? 'selected' : '' ?>>4 Columns</option>
                            <option value="5" <?= ($settings->columns ?? 3) == 5 ? 'selected' : '' ?>>5 Columns</option>
                            <option value="6" <?= ($settings->columns ?? 3) == 6 ? 'selected' : '' ?>>6 Columns</option>
                        </select>
                    </div>

                    <!-- Grid Gap -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="px">
                        <label for="grid_gap_<?= $block_id ?>">
                            <i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.grid_gap') ?? 'Grid Gap' ?>
                        </label>
                        <input 
                            id="grid_gap_<?= $block_id ?>" 
                            type="range" 
                            min="0" 
                            max="50" 
                            step="5"
                            name="grid_gap" 
                            class="form-control-range" 
                            value="<?= $settings->grid_gap ?? 10 ?>" 
                        />
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <?php if($show_visual_settings): ?>
        <!-- Visual Settings -->
        <div class="card mb-3">
            <div class="card-header" data-toggle="collapse" data-target="#visual_settings_container_<?= $block_id ?>" aria-expanded="false" style="cursor: pointer;">
                <h6 class="mb-0">
                    <i class="fas fa-fw fa-palette fa-sm text-muted mr-2"></i>
                    <?= l('microsite_image_grid.visual_settings') ?? 'Visual Settings' ?>
                    <i class="fas fa-chevron-down float-right"></i>
                </h6>
            </div>
            <div id="visual_settings_container_<?= $block_id ?>" class="collapse">
                <div class="card-body">
                    <!-- Image Height -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="px">
                        <label for="image_height_<?= $block_id ?>">
                            <i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.image_height') ?? 'Image Height' ?>
                        </label>
                        <input 
                            id="image_height_<?= $block_id ?>" 
                            type="range" 
                            min="100" 
                            max="500" 
                            step="10"
                            name="image_height" 
                            class="form-control-range" 
                            value="<?= $settings->image_height ?? 200 ?>" 
                        />
                    </div>

                    <!-- Aspect Ratio -->
                    <div class="form-group">
                        <label for="aspect_ratio_<?= $block_id ?>">
                            <i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.aspect_ratio') ?? 'Aspect Ratio' ?>
                        </label>
                        <select id="aspect_ratio_<?= $block_id ?>" name="aspect_ratio" class="custom-select">
                            <option value="custom" <?= ($settings->aspect_ratio ?? '1:1') == 'custom' ? 'selected' : '' ?>>Custom Height</option>
                            <option value="16:9" <?= ($settings->aspect_ratio ?? '') == '16:9' ? 'selected' : '' ?>>16:9 (Widescreen)</option>
                            <option value="4:3" <?= ($settings->aspect_ratio ?? '') == '4:3' ? 'selected' : '' ?>>4:3 (Standard)</option>
                            <option value="1:1" <?= ($settings->aspect_ratio ?? '1:1') == '1:1' ? 'selected' : '' ?>>1:1 (Square)</option>
                            <option value="21:9" <?= ($settings->aspect_ratio ?? '') == '21:9' ? 'selected' : '' ?>>21:9 (Ultrawide)</option>
                        </select>
                    </div>

                    <!-- Image Fit -->
                    <div class="form-group">
                        <label for="image_fit_<?= $block_id ?>">
                            <i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.image_fit') ?? 'Image Fit' ?>
                        </label>
                        <select id="image_fit_<?= $block_id ?>" name="image_fit" class="custom-select">
                            <option value="cover" <?= ($settings->image_fit ?? 'cover') == 'cover' ? 'selected' : '' ?>>Cover (Fill & Crop)</option>
                            <option value="contain" <?= ($settings->image_fit ?? '') == 'contain' ? 'selected' : '' ?>>Contain (Fit Inside)</option>
                            <option value="fill" <?= ($settings->image_fit ?? '') == 'fill' ? 'selected' : '' ?>>Fill (Stretch)</option>
                            <option value="scale-down" <?= ($settings->image_fit ?? '') == 'scale-down' ? 'selected' : '' ?>>Scale Down</option>
                        </select>
                    </div>

                    <!-- Border Radius -->
                    <div class="form-group" data-range-counter data-range-counter-suffix="px">
                        <label for="border_radius_<?= $block_id ?>">
                            <i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.border_radius') ?? 'Border Radius' ?>
                        </label>
                        <input 
                            id="border_radius_<?= $block_id ?>" 
                            type="range" 
                            min="0" 
                            max="50" 
                            step="1"
                            name="border_radius" 
                            class="form-control-range" 
                            value="<?= $settings->border_radius ?? 0 ?>" 
                        />
                    </div>

                    <!-- Hover Effect -->
                    <div class="form-group">
                        <label for="hover_effect_<?= $block_id ?>">
                            <i class="fas fa-fw fa-magic fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_image_grid.hover_effect') ?? 'Hover Effect' ?>
                        </label>
                        <select id="hover_effect_<?= $block_id ?>" name="hover_effect" class="custom-select">
                            <option value="none" <?= ($settings->hover_effect ?? 'none') == 'none' ? 'selected' : '' ?>>None</option>
                            <option value="zoom" <?= ($settings->hover_effect ?? '') == 'zoom' ? 'selected' : '' ?>>Zoom In</option>
                            <option value="fade" <?= ($settings->hover_effect ?? '') == 'fade' ? 'selected' : '' ?>>Fade</option>
                            <option value="lift" <?= ($settings->hover_effect ?? '') == 'lift' ? 'selected' : '' ?>>Lift Up</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

</div>

<!-- Image Edit Modal -->
<div class="modal fade" id="editImageModal-<?= $block_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <img id="editImagePreview-<?= $block_id ?>" src="" class="img-fluid" style="max-height: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="editImageFile-<?= $block_id ?>">
                        <i class="fas fa-image mr-1"></i>Replace Image (Optional)
                    </label>
                    <input type="file" id="editImageFile-<?= $block_id ?>" class="form-control-file" accept="<?= \SeeGap\Uploads::array_to_list_format($whitelisted_extensions) ?>">
                    <small class="form-text text-muted">Leave empty to keep current image</small>
                </div>
                
                <div class="form-group">
                    <label for="editImageAlt-<?= $block_id ?>">
                        <i class="fas fa-tag mr-1"></i>Alt Text
                    </label>
                    <input type="text" id="editImageAlt-<?= $block_id ?>" class="form-control" placeholder="Describe this image..." maxlength="255">
                    <small class="form-text text-muted">Helps with accessibility and SEO</small>
                </div>
                
                <div class="form-group">
                    <label for="editImageUrl-<?= $block_id ?>">
                        <i class="fas fa-link mr-1"></i>Link URL (Optional)
                    </label>
                    <input type="url" id="editImageUrl-<?= $block_id ?>" class="form-control" placeholder="https://example.com">
                    <small class="form-text text-muted">Where should this image link to when clicked?</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveImageEdit('<?= $block_id ?>')">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<style>
.image-grid-manager .image-card {
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.image-grid-manager .image-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.image-grid-manager .image-item.selected .image-card {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.image-grid-manager .image-controls {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.image-grid-manager .image-card:hover .image-controls {
    opacity: 1;
}

.image-grid-manager .image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0) 70%, rgba(0,0,0,0.1) 100%);
    pointer-events: none;
}

.image-grid-manager .drag-handle {
    background: rgba(0,0,0,0.7);
    border-radius: 4px;
    padding: 2px 4px;
}

.image-grid-manager .quick-remove {
    opacity: 0.9;
}

.image-grid-manager .quick-remove:hover {
    opacity: 1;
    transform: scale(1.1);
}

.image-grid-manager .image-number {
    background: rgba(0,0,0,0.8) !important;
}

.image-grid-manager .image-selector {
    background: rgba(255,255,255,0.9);
    border-radius: 3px;
}

@media (max-width: 768px) {
    .image-grid-manager .image-controls {
        opacity: 1;
    }
    
    .image-grid-manager .col-lg-3,
    .image-grid-manager .col-md-4 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?= $block_id ?>';
    
    // Initialize image grid functionality
    initializeImageGrid(blockId);
    
    // Initialize upload validation
    initializeUploadValidation(blockId);
});

function initializeImageGrid(blockId) {
    const grid = document.getElementById('image-grid-' + blockId);
    if (!grid) return;
    
    // Initialize drag and drop
    initializeDragAndDrop(blockId);
    
    // Initialize selection handlers
    initializeSelectionHandlers(blockId);
}

function initializeUploadValidation(blockId) {
    const uploadInput = document.getElementById('new_images_' + blockId);
    if (!uploadInput) return;
    
    uploadInput.addEventListener('change', function() {
        const maxImages = parseInt(this.dataset.maxImages);
        const currentCount = parseInt(this.dataset.currentCount);
        const selectedFiles = this.files.length;
        
        if (currentCount + selectedFiles > maxImages) {
            alert(`Maximum ${maxImages} images allowed. You currently have ${currentCount} images and selected ${selectedFiles} more.`);
            this.value = '';
            return;
        }
        
        // Validate file types and sizes
        for (let file of this.files) {
            if (file.size > <?= $size_limit ?>) {
                alert(`File "${file.name}" is too large. Maximum size is <?= number_format($size_limit / 1024 / 1024, 1) ?>MB.`);
                this.value = '';
                return;
            }
        }
    });
}

function initializeDragAndDrop(blockId) {
    const grid = document.getElementById('image-grid-' + blockId);
    if (!grid) return;
    
    let draggedElement = null;
    
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

function initializeSelectionHandlers(blockId) {
    const grid = document.getElementById('image-grid-' + blockId);
    if (!grid) return;
    
    const checkboxes = grid.querySelectorAll('.image-selector');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const imageItem = this.closest('.image-item');
            if (this.checked) {
                imageItem.classList.add('selected');
            } else {
                imageItem.classList.remove('selected');
            }
        });
    });
}

function selectAllImages(blockId) {
    const grid = document.getElementById('image-grid-' + blockId);
    if (!grid) return;
    
    const checkboxes = grid.querySelectorAll('.image-selector');
    const allSelected = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allSelected;
        const imageItem = checkbox.closest('.image-item');
        if (checkbox.checked) {
            imageItem.classList.add('selected');
        } else {
            imageItem.classList.remove('selected');
        }
    });
}

function removeSelectedImages(blockId) {
    const grid = document.getElementById('image-grid-' + blockId);
    if (!grid) return;
    
    const selectedItems = grid.querySelectorAll('.image-item.selected');
    if (selectedItems.length === 0) {
        alert('No images selected.');
        return;
    }
    
    if (!confirm(`Are you sure you want to remove ${selectedItems.length} selected image(s)?`)) {
        return;
    }
    
    const imagesDataInput = document.getElementById('images-data-' + blockId);
    let imagesData = JSON.parse(imagesDataInput.value);
    
    // Remove selected items in reverse order to maintain indices
    const indicesToRemove = Array.from(selectedItems).map(item => parseInt(item.dataset.index)).sort((a, b) => b - a);
    
    indicesToRemove.forEach(index => {
        imagesData.splice(index, 1);
        selectedItems[indicesToRemove.indexOf(index)].remove();
    });
    
    imagesDataInput.value = JSON.stringify(imagesData);
    updateImageOrder(blockId);
    updateImageNumbers(blockId);
    
    // Re-initialize functionality
    initializeImageGrid(blockId);
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
        const badge = item.querySelector('.image-number');
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
    preview.src = '<?= \SeeGap\Uploads::get_full_url($uploads_file_key) ?>' + imageName;
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
    const imagesDataInput = document.getElementById('images-data-' + blockId);
    
    try {
        let imagesData = JSON.parse(imagesDataInput.value);
        
        if (imagesData[index]) {
            imagesData[index].image_alt = altInput.value;
            imagesData[index].location_url = urlInput.value;
            
            imagesDataInput.value = JSON.stringify(imagesData);
            
            // Update the display
            const imageItem = document.querySelector(`[data-index="${index}"]`);
            if (imageItem) {
                const cardBody = imageItem.querySelector('.card-body');
                
                // Remove existing alt and link displays
                const existingAlt = cardBody.querySelector('.text-info');
                const existingLink = cardBody.querySelector('.text-success');
                if (existingAlt) existingAlt.remove();
                if (existingLink) existingLink.remove();
                
                // Add new alt text display
                if (altInput.value) {
                    const altElement = document.createElement('small');
                    altElement.className = 'text-info d-block text-truncate';
                    altElement.style.fontSize = '0.7rem';
                    altElement.innerHTML = '<i class="fas fa-tag fa-xs mr-1"></i>' + altInput.value;
                    altElement.title = altInput.value;
                    cardBody.appendChild(altElement);
                }
                
                // Add new link display
                if (urlInput.value) {
                    const linkElement = document.createElement('small');
                    linkElement.className = 'text-success d-block text-truncate';
                    linkElement.style.fontSize = '0.7rem';
                    linkElement.innerHTML = '<i class="fas fa-link fa-xs mr-1"></i>Linked';
                    linkElement.title = urlInput.value;
                    cardBody.appendChild(linkElement);
                }
            }
            
            $(modal).modal('hide');
            altInput.value = '';
            urlInput.value = '';
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
            let imagesData = JSON.parse(imagesDataInput.value);
            imagesData.splice(index, 1);
            imagesDataInput.value = JSON.stringify(imagesData);
            
            imageItem.remove();
            updateImageOrder(blockId);
            updateImageNumbers(blockId);
            initializeImageGrid(blockId);
            
        } catch (e) {
            console.error('Error removing image:', e);
            alert('Error removing image. Please try again.');
        }
    }
}
</script>
