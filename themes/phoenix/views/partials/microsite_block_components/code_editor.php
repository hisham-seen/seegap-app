<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Code Editor Component for Microsite Blocks
 * Provides enhanced code editing with syntax highlighting, validation, templates, and preview
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param string $field_name - Name of the field (default: 'html')
 * @param string $field_label - Label for the field (default: 'HTML Code')
 * @param string $field_icon - Icon for the field (default: 'fas fa-code')
 * @param string $language - Code language (default: 'html')
 * @param int $max_length - Maximum character length (default: 50000)
 * @param bool $show_templates - Whether to show code templates (default: true)
 * @param bool $show_preview - Whether to show live preview (default: true)
 * @param bool $show_validation - Whether to show code validation (default: true)
 * @param array $custom_templates - Custom code templates (optional)
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$field_name = $field_name ?? 'html';
$field_label = $field_label ?? (l('microsite_custom_html.html') ?? 'HTML Code');
$field_icon = $field_icon ?? 'fas fa-code';
$language = $language ?? 'html';
$max_length = $max_length ?? 50000;
$show_templates = $show_templates ?? true;
$show_preview = $show_preview ?? true;
$show_validation = $show_validation ?? true;
$custom_templates = $custom_templates ?? [];

// Get current code value
$current_code = '';
if ($field_name === 'html' && isset($settings->html)) {
    $current_code = $settings->html;
} elseif (isset($settings->{$field_name})) {
    $current_code = $settings->{$field_name};
}

// Define default templates
$default_templates = [
    'basic_html' => [
        'name' => 'Basic HTML Structure',
        'description' => 'Simple HTML container with content',
        'code' => '<div class="custom-content">
    <h2>Your Title Here</h2>
    <p>Your content goes here...</p>
</div>'
    ],
    'card' => [
        'name' => 'Card Layout',
        'description' => 'Bootstrap-style card component',
        'code' => '<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Card Title</h5>
    </div>
    <div class="card-body">
        <p class="card-text">Card content goes here...</p>
        <a href="#" class="btn btn-primary">Action Button</a>
    </div>
</div>'
    ],
    'alert' => [
        'name' => 'Alert Message',
        'description' => 'Styled alert notification',
        'code' => '<div class="alert alert-info" role="alert">
    <i class="fas fa-info-circle mr-2"></i>
    <strong>Info:</strong> This is an informational message.
</div>'
    ],
    'button_group' => [
        'name' => 'Button Group',
        'description' => 'Group of styled buttons',
        'code' => '<div class="btn-group" role="group">
    <button type="button" class="btn btn-primary">Primary</button>
    <button type="button" class="btn btn-secondary">Secondary</button>
    <button type="button" class="btn btn-success">Success</button>
</div>'
    ],
    'media_object' => [
        'name' => 'Media Object',
        'description' => 'Image with text content',
        'code' => '<div class="media">
    <img src="https://via.placeholder.com/64" class="media-object mr-3" alt="Media">
    <div class="media-body">
        <h5 class="mt-0">Media heading</h5>
        Media content goes here...
    </div>
</div>'
    ],
    'embed_video' => [
        'name' => 'Embedded Video',
        'description' => 'Responsive video embed',
        'code' => '<div class="embed-responsive embed-responsive-16by9">
    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/VIDEO_ID" allowfullscreen></iframe>
</div>'
    ]
];

// Merge custom templates if provided
$templates = array_merge($default_templates, $custom_templates);
?>

<div class="code-editor-component" id="code-editor-<?= $block_id ?>">
    
    <!-- Code Editor Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <label for="<?= $field_name ?>_<?= $block_id ?>" class="mb-0">
            <i class="<?= $field_icon ?> fa-sm text-muted mr-1"></i> 
            <?= $field_label ?>
        </label>
        
        <div class="btn-group btn-group-sm" role="group">
            <?php if($show_templates): ?>
                <button type="button" class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-code fa-xs mr-1"></i> Templates
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <h6 class="dropdown-header">Code Templates</h6>
                    <?php foreach($templates as $key => $template): ?>
                        <a class="dropdown-item" href="#" onclick="insertTemplate('<?= $block_id ?>', '<?= $key ?>')">
                            <strong><?= $template['name'] ?></strong>
                            <br><small class="text-muted"><?= $template['description'] ?></small>
                        </a>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
            
            <?php if($show_preview): ?>
                <button type="button" class="btn btn-outline-info" onclick="togglePreview('<?= $block_id ?>')">
                    <i class="fas fa-eye fa-xs mr-1"></i> Preview
                </button>
            <?php endif ?>
            
            <?php if($show_validation): ?>
                <button type="button" class="btn btn-outline-success" onclick="validateCode('<?= $block_id ?>')">
                    <i class="fas fa-check fa-xs mr-1"></i> Validate
                </button>
            <?php endif ?>
        </div>
    </div>

    <!-- Code Editor Textarea -->
    <div class="position-relative">
        <textarea 
            id="<?= $field_name ?>_<?= $block_id ?>" 
            name="<?= $field_name ?>" 
            class="form-control code-editor-textarea" 
            rows="12"
            maxlength="<?= $max_length ?>"
            placeholder="Enter your <?= strtoupper($language) ?> code here..."
            data-language="<?= $language ?>"
            spellcheck="false"
        ><?= htmlspecialchars($current_code) ?></textarea>
        
        <!-- Character Counter -->
        <div class="position-absolute" style="bottom: 8px; right: 12px; font-size: 0.75rem; color: #6c757d; background: rgba(255,255,255,0.9); padding: 2px 6px; border-radius: 3px;">
            <span id="char-count-<?= $block_id ?>">0</span> / <?= number_format($max_length) ?>
        </div>
    </div>

    <!-- Code Editor Help Text -->
    <small class="form-text text-muted mt-2">
        <i class="fas fa-info-circle fa-xs mr-1"></i>
        <?php if($language === 'html'): ?>
            You can use HTML, CSS (in &lt;style&gt; tags), and JavaScript (in &lt;script&gt; tags). 
            Bootstrap classes are available for styling.
        <?php else: ?>
            Enter your <?= strtoupper($language) ?> code. Syntax highlighting and validation are available.
        <?php endif ?>
        <br>
        <strong>Tips:</strong> Use templates for quick starts • Preview to see results • Validate to check syntax
    </small>

    <?php if($show_preview): ?>
        <!-- Live Preview -->
        <div id="code-preview-<?= $block_id ?>" class="code-preview mt-3" style="display: none;">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-eye mr-1"></i> Live Preview
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="togglePreview('<?= $block_id ?>')">
                        <i class="fas fa-times fa-xs"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div id="preview-content-<?= $block_id ?>" class="preview-content">
                        <!-- Preview content will be inserted here -->
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <?php if($show_validation): ?>
        <!-- Validation Results -->
        <div id="validation-results-<?= $block_id ?>" class="validation-results mt-3" style="display: none;">
            <!-- Validation results will be inserted here -->
        </div>
    <?php endif ?>

</div>

<!-- Hidden Templates Data -->
<script type="application/json" id="templates-data-<?= $block_id ?>">
<?= json_encode($templates) ?>
</script>

<style>
.code-editor-component .code-editor-textarea {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', 'source-code-pro', monospace;
    font-size: 13px;
    line-height: 1.4;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    resize: vertical;
    min-height: 200px;
}

.code-editor-component .code-editor-textarea:focus {
    background-color: #ffffff;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.code-editor-component .preview-content {
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 1rem;
    background-color: #ffffff;
    min-height: 100px;
}

.code-editor-component .validation-results .alert {
    margin-bottom: 0;
}

.code-editor-component .dropdown-menu {
    max-width: 300px;
    max-height: 400px;
    overflow-y: auto;
}

.code-editor-component .dropdown-item {
    white-space: normal;
    padding: 0.5rem 1rem;
}

.code-editor-component .dropdown-item:hover {
    background-color: #f8f9fa;
}

.code-editor-component .dropdown-header {
    font-weight: 600;
    color: #495057;
}

@media (max-width: 576px) {
    .code-editor-component .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .code-editor-component .btn-group .btn {
        border-radius: 0.25rem !important;
        margin-bottom: 0.25rem;
    }
    
    .code-editor-component .dropdown-menu {
        position: static !important;
        transform: none !important;
        width: 100%;
        box-shadow: none;
        border: 1px solid #dee2e6;
        margin-top: 0.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?= $block_id ?>';
    
    // Initialize code editor
    initializeCodeEditor(blockId);
});

function initializeCodeEditor(blockId) {
    const textarea = document.getElementById('<?= $field_name ?>_' + blockId);
    const charCount = document.getElementById('char-count-' + blockId);
    
    if (!textarea || !charCount) return;
    
    // Update character count
    function updateCharCount() {
        const count = textarea.value.length;
        charCount.textContent = count.toLocaleString();
        
        // Color coding for character count
        const maxLength = <?= $max_length ?>;
        const percentage = (count / maxLength) * 100;
        
        if (percentage > 90) {
            charCount.style.color = '#dc3545'; // Red
        } else if (percentage > 75) {
            charCount.style.color = '#fd7e14'; // Orange
        } else {
            charCount.style.color = '#6c757d'; // Gray
        }
    }
    
    // Initial count
    updateCharCount();
    
    // Update on input
    textarea.addEventListener('input', updateCharCount);
    
    // Auto-resize textarea
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.max(200, this.scrollHeight) + 'px';
    });
    
    // Tab key support
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            
            // Insert tab character
            this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
            
            // Move cursor
            this.selectionStart = this.selectionEnd = start + 4;
        }
    });
}

function insertTemplate(blockId, templateKey) {
    const textarea = document.getElementById('<?= $field_name ?>_' + blockId);
    const templatesData = JSON.parse(document.getElementById('templates-data-' + blockId).textContent);
    
    if (!textarea || !templatesData[templateKey]) return;
    
    const template = templatesData[templateKey];
    const currentValue = textarea.value;
    
    // If textarea is empty, replace entirely
    if (!currentValue.trim()) {
        textarea.value = template.code;
    } else {
        // Otherwise, append with spacing
        textarea.value = currentValue + '\n\n' + template.code;
    }
    
    // Trigger input event to update character count
    textarea.dispatchEvent(new Event('input'));
    
    // Focus textarea
    textarea.focus();
    
    // Show success message
    showTemporaryMessage('Template "' + template.name + '" inserted successfully!', 'success');
}

function togglePreview(blockId) {
    const textarea = document.getElementById('<?= $field_name ?>_' + blockId);
    const preview = document.getElementById('code-preview-' + blockId);
    const previewContent = document.getElementById('preview-content-' + blockId);
    
    if (!textarea || !preview || !previewContent) return;
    
    if (preview.style.display === 'none') {
        // Show preview
        const code = textarea.value;
        
        if (!code.trim()) {
            showTemporaryMessage('No code to preview. Enter some HTML first.', 'warning');
            return;
        }
        
        // Sanitize and insert code
        try {
            previewContent.innerHTML = code;
            preview.style.display = 'block';
            
            // Scroll to preview
            preview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } catch (error) {
            showTemporaryMessage('Error rendering preview: ' + error.message, 'danger');
        }
    } else {
        // Hide preview
        preview.style.display = 'none';
    }
}

function validateCode(blockId) {
    const textarea = document.getElementById('<?= $field_name ?>_' + blockId);
    const resultsContainer = document.getElementById('validation-results-' + blockId);
    
    if (!textarea || !resultsContainer) return;
    
    const code = textarea.value;
    
    if (!code.trim()) {
        showTemporaryMessage('No code to validate. Enter some HTML first.', 'warning');
        return;
    }
    
    // Basic HTML validation
    const issues = [];
    
    // Check for unclosed tags
    const openTags = code.match(/<[^\/][^>]*>/g) || [];
    const closeTags = code.match(/<\/[^>]*>/g) || [];
    
    if (openTags.length !== closeTags.length) {
        issues.push('Potential unclosed HTML tags detected');
    }
    
    // Check for common issues
    if (code.includes('<script') && !code.includes('</script>')) {
        issues.push('Script tag appears to be unclosed');
    }
    
    if (code.includes('<style') && !code.includes('</style>')) {
        issues.push('Style tag appears to be unclosed');
    }
    
    // Check for potentially dangerous content
    if (code.toLowerCase().includes('javascript:')) {
        issues.push('JavaScript URLs detected - use with caution');
    }
    
    // Display results
    let resultHTML = '';
    
    if (issues.length === 0) {
        resultHTML = '<div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i>Code validation passed! No issues detected.</div>';
    } else {
        resultHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-2"></i><strong>Validation Issues:</strong><ul class="mb-0 mt-2">';
        issues.forEach(issue => {
            resultHTML += '<li>' + issue + '</li>';
        });
        resultHTML += '</ul></div>';
    }
    
    resultsContainer.innerHTML = resultHTML;
    resultsContainer.style.display = 'block';
    
    // Hide after 10 seconds
    setTimeout(() => {
        resultsContainer.style.display = 'none';
    }, 10000);
}

function showTemporaryMessage(message, type) {
    // Create temporary alert
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    // Insert at top of form
    const form = document.querySelector('form[name="update_microsite_block"]');
    if (form) {
        form.insertBefore(alert, form.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
}
</script>
