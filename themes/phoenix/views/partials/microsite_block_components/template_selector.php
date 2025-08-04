<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Template Selector Component for Microsite Blocks
 * Provides visual template selection with preview cards
 * 
 * @param string $block_id - Unique identifier for the block
 * @param string $field_name - Field name for template selection (default: 'template')
 * @param array $templates - Array of templates with keys and display names
 * @param string $default_template - Default selected template
 * @param string $label - Label for the field
 * @param string $icon - Icon class for the label (default: 'fas fa-palette')
 */

$block_id = $block_id ?? 'default';
$field_name = $field_name ?? 'template';
$templates = $templates ?? [
    'classic' => 'Classic',
    'gradient_ring' => 'Gradient Ring',
    'professional' => 'Professional',
    'creative' => 'Creative',
    'minimalist' => 'Minimalist',
    'neon_glow' => 'Neon Glow'
];
$default_template = $default_template ?? 'classic';
$label = $label ?? 'Template';
$icon = $icon ?? 'fas fa-palette';
?>

<div class="form-group">
    <label><i class="<?= $icon ?> fa-fw fa-sm text-muted mr-1"></i> <?= $label ?></label>
    <div class="row">
        <?php foreach($templates as $value => $display): ?>
            <div class="col-6 col-md-4 mb-3">
                <label class="template-option">
                    <input type="radio" name="<?= $field_name ?>" value="<?= $value ?>" <?= $value === $default_template ? 'checked' : '' ?> class="d-none">
                    <div class="template-preview <?= $value ?>-template <?= $value === $default_template ? 'active' : '' ?>">
                        <div class="template-demo"></div>
                        <small class="template-name"><?= $display ?></small>
                    </div>
                </label>
            </div>
        <?php endforeach ?>
    </div>
</div>

<style>
.template-option {
    cursor: pointer;
    display: block;
    width: 100%;
}

.template-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.template-preview:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.15);
}

.template-option input:checked + .template-preview,
.template-preview.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.template-demo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin: 0 auto 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.gradient-ring-template .template-demo {
    border: 3px solid transparent;
    background: linear-gradient(white, white) padding-box, linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1) border-box;
}

.professional-template .template-demo {
    background: #6c757d;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.creative-template .template-demo {
    background: linear-gradient(45deg, #ff9a9e, #fecfef, #fecfef);
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #ff9a9e;
}

.minimalist-template .template-demo {
    background: #ffffff;
    border: 1px solid #dee2e6;
}

.neon-glow-template .template-demo {
    background: #667eea;
    box-shadow: 0 0 20px rgba(102, 126, 234, 0.6);
}

.template-name {
    font-weight: 500;
    color: #495057;
}

@media (max-width: 576px) {
    .template-preview {
        padding: 10px;
    }
    
    .template-demo {
        width: 30px;
        height: 30px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const templateOptions = document.querySelectorAll('input[name="<?= $field_name ?>"]');
    const templatePreviews = document.querySelectorAll('.template-preview');
    
    // Add click handlers to template previews
    templatePreviews.forEach(preview => {
        preview.addEventListener('click', function() {
            const radioInput = this.parentElement.querySelector('input[name="<?= $field_name ?>"]');
            if (radioInput) {
                radioInput.checked = true;
                
                // Remove active class from all template previews
                templatePreviews.forEach(p => p.classList.remove('active'));
                
                // Add active class to selected template
                this.classList.add('active');
                
                // Trigger change event for external listeners
                radioInput.dispatchEvent(new Event('change'));
            }
        });
    });
    
    // Handle radio button changes directly
    templateOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all template previews
            templatePreviews.forEach(preview => {
                preview.classList.remove('active');
            });
            
            // Add active class to selected template
            const selectedPreview = this.parentElement.querySelector('.template-preview');
            if (selectedPreview) {
                selectedPreview.classList.add('active');
            }
        });
    });
});
</script>
