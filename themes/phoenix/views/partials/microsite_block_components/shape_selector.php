<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Shape Selector Component for Microsite Blocks
 * Provides visual shape selection with preview cards
 * 
 * @param string $block_id - Unique identifier for the block
 * @param string $field_name - Field name for shape selection (default: 'shape')
 * @param array $shapes - Array of shapes with keys and display names
 * @param string $default_shape - Default selected shape
 * @param string $label - Label for the field
 * @param string $icon - Icon class for the label (default: 'fas fa-shapes')
 * @param bool $show_border_radius - Whether to show border radius control for square shapes
 */

$block_id = $block_id ?? 'default';
$field_name = $field_name ?? 'shape';
$shapes = $shapes ?? [
    'circle' => 'Circle',
    'square' => 'Square'
];
$default_shape = $default_shape ?? 'circle';
$label = $label ?? 'Shape';
$icon = $icon ?? 'fas fa-shapes';
$show_border_radius = $show_border_radius ?? true;
?>

<div class="form-group">
    <label><i class="<?= $icon ?> fa-fw fa-sm text-muted mr-1"></i> <?= $label ?></label>
    <div class="row">
        <?php foreach($shapes as $value => $display): ?>
            <div class="col-6">
                <label class="shape-option">
                    <input type="radio" name="<?= $field_name ?>" value="<?= $value ?>" <?= $value === $default_shape ? 'checked' : '' ?> class="d-none">
                    <div class="shape-preview <?= $value ?>-shape <?= $value === $default_shape ? 'active' : '' ?>">
                        <div class="shape-demo"></div>
                        <small><?= $display ?></small>
                    </div>
                </label>
            </div>
        <?php endforeach ?>
    </div>
</div>

<?php if($show_border_radius): ?>
<!-- Border Radius Control (shown only for square shapes) -->
<div class="form-group" id="<?= $field_name ?>-border-radius-container" style="display: <?= $default_shape == 'square' ? 'block' : 'none' ?>;">
    <label for="<?= $field_name . '_border_radius_' . $block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> Corner Radius</label>
    <input type="range" id="<?= $field_name . '_border_radius_' . $block_id ?>" name="<?= $field_name ?>_border_radius" min="0" max="50" value="8" class="form-control-range">
    <small class="form-text text-muted">Adjust corner roundness for square shapes (0 = sharp corners, 50 = fully rounded)</small>
</div>
<?php endif ?>

<style>
.shape-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.shape-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.shape-preview:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.15);
}

.shape-option input:checked + .shape-preview,
.shape-preview.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.shape-demo {
    width: 40px;
    height: 40px;
    margin: 0 auto 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.circle-shape .shape-demo {
    border-radius: 50%;
}

.square-shape .shape-demo {
    border-radius: 8px;
}

.rounded-shape .shape-demo {
    border-radius: 12px;
}

.shape-preview small {
    font-weight: 500;
    color: #495057;
}

@media (max-width: 576px) {
    .shape-preview {
        padding: 10px 5px;
    }
    
    .shape-demo {
        width: 30px;
        height: 30px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shapeOptions = document.querySelectorAll('input[name="<?= $field_name ?>"]');
    const shapePreviews = document.querySelectorAll('.shape-preview');
    const borderRadiusContainer = document.getElementById('<?= $field_name ?>-border-radius-container');
    
    // Add click handlers to shape previews
    shapePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="<?= $field_name ?>"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all shape previews
                shapePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected shape
                this.classList.add('active');
                
                // Show/hide border radius controls
                if (borderRadiusContainer) {
                    if (radioInput.value === 'square') {
                        borderRadiusContainer.style.display = 'block';
                    } else {
                        borderRadiusContainer.style.display = 'none';
                    }
                }
                
                // Trigger change event for external listeners
                radioInput.dispatchEvent(new Event('change'));
            }
        });
    });
    
    // Handle radio button changes directly
    shapeOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all shape previews
            shapePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected shape
            const selectedPreview = this.parentElement.querySelector('.shape-preview');
            if (selectedPreview) {
                selectedPreview.classList.add('active');
            }
            
            // Show/hide border radius controls
            if (borderRadiusContainer) {
                if (this.value === 'square') {
                    borderRadiusContainer.style.display = 'block';
                } else {
                    borderRadiusContainer.style.display = 'none';
                }
            }
        });
    });
});
</script>
