<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Dynamic Social Media Manager Component for Microsite Blocks
 * Provides add/remove functionality for social media platforms with platform selection
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object containing socials data
 * @param string $container_id - ID for the social media container
 * @param int $max_platforms - Maximum number of platforms allowed (default: 20)
 * @param string $field_prefix - Prefix for field names (default: 'socials')
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$container_id = $container_id ?? 'social_media_container_' . $block_id;
$max_platforms = $max_platforms ?? 20;
$field_prefix = $field_prefix ?? 'socials';

// Load social media platforms configuration
$microsite_socials = require APP_PATH . 'includes/microsite_socials.php';

// Get existing social media entries
$existing_socials = [];
if (isset($settings->socials)) {
    foreach ($settings->socials as $platform => $value) {
        if (!empty($value) && isset($microsite_socials[$platform])) {
            $existing_socials[] = [
                'platform' => $platform,
                'value' => $value
            ];
        }
    }
}
?>

<div class="form-group">
    <label><i class="fas fa-fw fa-share-alt fa-sm text-muted mr-1"></i> <?= l('microsite_socials.header') ?? 'Social Media Links' ?></label>
    <div id="<?= $container_id ?>" class="social-media-accordion">
        <?php if(!empty($existing_socials)): ?>
            <?php foreach($existing_socials as $index => $social): ?>
                <div class="social-item card mb-2" data-social-index="<?= $index ?>">
                    <!-- Social Header -->
                    <div class="card-header p-2 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center flex-grow-1">
                            <!-- Platform Icon -->
                            <div class="social-icon mr-2">
                                <i class="<?= $microsite_socials[$social['platform']]['icon'] ?> text-muted"></i>
                            </div>
                            
                            <!-- Platform Preview -->
                            <div class="social-preview flex-grow-1">
                                <strong class="social-platform"><?= l('microsite_socials.' . $social['platform'] . '.name') ?></strong>
                                <small class="text-muted ml-2 social-value"><?= $social['value'] ?></small>
                            </div>
                        </div>
                        
                        <!-- Action Icons -->
                        <div class="social-actions d-flex align-items-center">
                            <!-- Delete Icon -->
                            <i class="fas fa-trash text-danger remove-social mr-2" style="cursor: pointer;" title="Remove Platform"></i>
                            
                            <!-- Collapse Toggle Icon -->
                            <i class="fas fa-chevron-down text-primary" style="cursor: pointer;" data-toggle="collapse" data-target="#social_<?= $index ?>" aria-expanded="false" title="Expand/Collapse"></i>
                        </div>
                    </div>
                    
                    <!-- Social Content (Collapsible) -->
                    <div id="social_<?= $index ?>" class="collapse">
                        <div class="card-body">
                            <!-- Social Value Input with correct field name for socials block -->
                            <div class="form-group">
                                <label><?= l('microsite_socials.' . $social['platform'] . '.name') ?></label>
                                
                                <?php if($microsite_socials[$social['platform']]['input_group']): ?>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <?= remove_url_protocol_from_url(str_replace('%s', '', $microsite_socials[$social['platform']]['format'])) ?>
                                            </span>
                                        </div>
                                        <input 
                                            type="text" 
                                            class="form-control social-value-input" 
                                            name="socials[<?= $social['platform'] ?>]" 
                                            placeholder="<?= l('microsite_socials.' . $social['platform'] . '.placeholder') ?>" 
                                            value="<?= $social['value'] ?>" 
                                            maxlength="<?= $microsite_socials[$social['platform']]['max_length'] ?>" 
                                            required
                                        />
                                    </div>
                                <?php else: ?>
                                    <input 
                                        type="text" 
                                        class="form-control social-value-input" 
                                        name="socials[<?= $social['platform'] ?>]" 
                                        placeholder="<?= l('microsite_socials.' . $social['platform'] . '.placeholder') ?>" 
                                        value="<?= $social['value'] ?>" 
                                        maxlength="<?= $microsite_socials[$social['platform']]['max_length'] ?>" 
                                        required
                                    />
                                <?php endif ?>
                                
                                <small class="form-text text-muted"><?= l('microsite_socials.' . $social['platform'] . '.placeholder') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    
    <!-- Add Platform Section -->
    <div class="add-social-section mt-3">
        <div class="row">
            <div class="col-md-8">
                <select id="platform_selector_<?= $block_id ?>" class="form-control">
                    <option value=""><?= l('microsite_socials.select_platform') ?? 'Select a platform...' ?></option>
                    <?php foreach($microsite_socials as $key => $value): ?>
                        <option value="<?= $key ?>" data-icon="<?= $value['icon'] ?>">
                            <?= l('microsite_socials.' . $key . '.name') ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="button" id="add_social_<?= $block_id ?>" class="btn btn-minimal-add btn-block" data-container-id="<?= $container_id ?>" data-max-platforms="<?= $max_platforms ?>" title="<?= l('microsite_socials.add_platform') ?? 'Add Platform' ?>">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.social-media-accordion .social-item {
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.social-media-accordion .social-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.social-icon {
    width: 20px;
    text-align: center;
}

.social-actions i:hover {
    transform: scale(1.2);
    transition: transform 0.2s ease;
}

.social-preview {
    min-height: 24px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}

.social-platform {
    margin-right: 8px;
}

.social-value {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
    color: #6c757d;
    font-size: 0.875rem;
}

.add-social-section {
    border-top: 1px solid #dee2e6;
    padding-top: 1rem;
}

/* Minimalistic Add Button */
.btn-minimal-add {
    background: transparent;
    border: 1px dashed #dee2e6;
    color: #6c757d;
    font-size: 0.875rem;
    font-weight: 400;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    border-radius: 0.375rem;
}

.btn-minimal-add:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
    color: #495057;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.btn-minimal-add:focus {
    background: #f8f9fa;
    border-color: #80bdff;
    color: #495057;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.1);
    outline: 0;
}

.btn-minimal-add:active {
    background: #e9ecef;
    border-color: #adb5bd;
    color: #495057;
    transform: translateY(0);
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
}

.btn-minimal-add i {
    opacity: 0.7;
    transition: opacity 0.2s ease;
}

.btn-minimal-add:hover i {
    opacity: 1;
}

@media (max-width: 576px) {
    .social-actions {
        flex-direction: column;
        gap: 4px;
    }
    
    .social-actions i {
        margin: 0 !important;
    }
    
    .input-group-text {
        font-size: 0.75rem;
        padding: 0.375rem 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const containerId = '<?= $container_id ?>';
    const blockId = '<?= $block_id ?>';
    const maxPlatforms = <?= $max_platforms ?>;
    let socialIndex = <?= count($existing_socials) ?>;
    
    // Available platforms data
    const platforms = <?= json_encode($microsite_socials) ?>;
    
    // Add new social platform
    const addButton = document.getElementById('add_social_' + blockId);
    const platformSelector = document.getElementById('platform_selector_' + blockId);
    
    if (addButton && platformSelector) {
        addButton.addEventListener('click', function() {
            const selectedPlatform = platformSelector.value;
            if (!selectedPlatform) {
                alert('Please select a platform first');
                return;
            }
            
            const container = document.getElementById(containerId);
            const currentSocials = container.querySelectorAll('.social-item');
            
            if (currentSocials.length >= maxPlatforms) {
                alert('Maximum number of platforms reached (' + maxPlatforms + ')');
                return;
            }
            
            // Check if platform already exists
            const existingPlatforms = Array.from(container.querySelectorAll('input[name="social_platforms[]"]')).map(input => input.value);
            if (existingPlatforms.includes(selectedPlatform)) {
                alert('This platform has already been added');
                return;
            }
            
            addNewSocial(selectedPlatform);
            platformSelector.value = '';
        });
    }
    
    function addNewSocial(platform) {
        const container = document.getElementById(containerId);
        const newIndex = socialIndex++;
        const platformData = platforms[platform];
        
        const socialHtml = `
            <div class="social-item card mb-2" data-social-index="${newIndex}">
                <div class="card-header p-2 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center flex-grow-1">
                        <div class="social-icon mr-2">
                            <i class="${platformData.icon} text-muted"></i>
                        </div>
                        <div class="social-preview flex-grow-1">
                            <strong class="social-platform">${platformData.name || platform}</strong>
                            <small class="text-muted ml-2 social-value">Not set</small>
                        </div>
                    </div>
                    <div class="social-actions d-flex align-items-center">
                        <i class="fas fa-trash text-danger remove-social mr-2" style="cursor: pointer;" title="Remove Platform"></i>
                        <i class="fas fa-chevron-down text-primary" style="cursor: pointer;" data-toggle="collapse" data-target="#social_${newIndex}" aria-expanded="true" title="Expand/Collapse"></i>
                    </div>
                </div>
                <div id="social_${newIndex}" class="collapse show">
                    <div class="card-body">
                        <div class="form-group">
                            <label>${platformData.name || platform}</label>
                            ${platformData.input_group ? `
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            ${platformData.format.replace('%s', '').replace(/^https?:\/\//, '')}
                                        </span>
                                    </div>
                                    <input 
                                        type="text" 
                                        class="form-control social-value-input" 
                                        name="socials[${platform}]" 
                                        placeholder="${platformData.placeholder || ''}" 
                                        maxlength="${platformData.max_length || 255}" 
                                        required
                                    />
                                </div>
                            ` : `
                                <input 
                                    type="text" 
                                    class="form-control social-value-input" 
                                    name="socials[${platform}]" 
                                    placeholder="${platformData.placeholder || ''}" 
                                    maxlength="${platformData.max_length || 255}" 
                                    required
                                />
                            `}
                            <small class="form-text text-muted">${platformData.placeholder || ''}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', socialHtml);
        initializeSocialEvents();
        
        // Focus on the new input
        const newSocial = container.lastElementChild;
        const newInput = newSocial.querySelector('.social-value-input');
        if (newInput) {
            newInput.focus();
        }
    }
    
    // Initialize all social event handlers
    function initializeSocialEvents() {
        // Remove social functionality
        document.querySelectorAll('#' + containerId + ' .remove-social').forEach(button => {
            button.removeEventListener('click', removeSocial);
            button.addEventListener('click', removeSocial);
        });
        
        // Social value change handler
        document.querySelectorAll('#' + containerId + ' .social-value-input').forEach(input => {
            input.removeEventListener('input', updateSocialPreviews);
            input.addEventListener('input', updateSocialPreviews);
        });
    }
    
    function removeSocial(e) {
        if (confirm('Are you sure you want to remove this social media platform?')) {
            e.target.closest('.social-item').remove();
        }
    }
    
    function updateSocialPreviews() {
        document.querySelectorAll('#' + containerId + ' .social-item').forEach(socialItem => {
            const valueInput = socialItem.querySelector('.social-value-input');
            const valuePreview = socialItem.querySelector('.social-value');
            
            if (valueInput && valuePreview) {
                const value = valueInput.value.trim();
                valuePreview.textContent = value || 'Not set';
            }
        });
    }
    
    // Initialize events for existing socials
    initializeSocialEvents();
    updateSocialPreviews();
});
</script>
