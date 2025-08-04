<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Size Selector Component for Microsite Blocks
 * Provides visual size selection with preview cards
 * 
 * @param string $block_id - Unique identifier for the block
 * @param string $field_name - Field name for size selection (default: 'size')
 * @param array $sizes - Array of sizes with keys, display names, and descriptions
 * @param string $default_size - Default selected size
 * @param string $label - Label for the field
 * @param string $icon - Icon class for the label (default: 'fas fa-expand-arrows-alt')
 * @param string $unit - Unit to display (default: 'px')
 */

$block_id = $block_id ?? 'default';
$field_name = $field_name ?? 'size';
$sizes = $sizes ?? [
    '80' => ['name' => 'Compact', 'description' => '80px'],
    '100' => ['name' => 'Standard', 'description' => '100px'],
    '120' => ['name' => 'Large', 'description' => '120px'],
    '140' => ['name' => 'Hero', 'description' => '140px']
];
$default_size = $default_size ?? '100';
$label = $label ?? 'Size';
$icon = $icon ?? 'fas fa-expand-arrows-alt';
$unit = $unit ?? 'px';
?>

<div class="form-group">
    <label for="<?= $field_name . '_' . $block_id ?>"><i class="<?= $icon ?> fa-fw fa-sm text-muted mr-1"></i> <?= $label ?></label>
    <div class="row">
        <?php foreach($sizes as $value => $config): ?>
            <div class="col-6 col-md-3">
                <label class="size-option">
                    <input type="radio" name="<?= $field_name ?>" value="<?= $value ?>" <?= $value == $default_size ? 'checked' : '' ?> class="d-none">
                    <div class="size-preview size-<?= $value ?> <?= $value == $default_size ? 'active' : '' ?>">
                        <div class="size-demo"></div>
                        <small><?= $config['name'] ?><br><?= $config['description'] ?></small>
                    </div>
                </label>
            </div>
        <?php endforeach ?>
    </div>
</div>

<style>
.size-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.size-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px 10px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.size-preview:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.15);
}

.size-option input:checked + .size-preview,
.size-preview.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.size-demo {
    border-radius: 50%;
    margin: 0 auto 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Size variations */
.size-80 .size-demo { width: 20px; height: 20px; }
.size-100 .size-demo { width: 25px; height: 25px; }
.size-120 .size-demo { width: 30px; height: 30px; }
.size-140 .size-demo { width: 35px; height: 35px; }
.size-160 .size-demo { width: 40px; height: 40px; }
.size-180 .size-demo { width: 45px; height: 45px; }
.size-200 .size-demo { width: 50px; height: 50px; }

/* For non-circular elements */
.size-demo.square {
    border-radius: 8px;
}

.size-demo.rectangle {
    border-radius: 4px;
    height: 20px;
}

.size-preview small {
    font-weight: 500;
    color: #495057;
    line-height: 1.2;
}

@media (max-width: 576px) {
    .size-preview {
        padding: 10px 5px;
    }
    
    .size-demo {
        transform: scale(0.8);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sizeOptions = document.querySelectorAll('input[name="<?= $field_name ?>"]');
    const sizePreviews = document.querySelectorAll('.size-preview');
    
    // Add click handlers to size previews
    sizePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="<?= $field_name ?>"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all size previews
                sizePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected size
                this.classList.add('active');
                
                // Trigger change event for external listeners
                radioInput.dispatchEvent(new Event('change'));
            }
        });
    });
    
    // Handle radio button changes directly
    sizeOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all size previews
            sizePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected size
            const selectedPreview = this.parentElement.querySelector('.size-preview');
            if (selectedPreview) {
                selectedPreview.classList.add('active');
            }
        });
    });
});
</script>
