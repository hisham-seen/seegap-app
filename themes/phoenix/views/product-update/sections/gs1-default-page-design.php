<?php defined('SEEGAP') || die() ?>

<input type="hidden" name="section" value="gs1-default-page-design" />

<!-- Check if GS1 Digital Link is enabled and set to default -->
<?php if(!$data->product->gs1_link_enabled || $data->product->gs1_link_type !== 'default'): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle mr-2"></i>
    <strong><?= l('products.gs1.default_page_design_not_available') ?></strong>
    <br>
    <small><?= l('products.gs1.default_page_design_enable_instructions') ?></small>
    <br>
    <a href="#" class="btn btn-sm btn-primary mt-2" onclick="switchToSection('gs1-digital-link')">
        <i class="fas fa-cog fa-sm mr-1"></i>
        <?= l('products.gs1.configure_digital_link') ?>
    </a>
</div>
<?php else: ?>

<?php
// Get current design configuration
// Handle different settings formats properly
$settings = [];
if (is_object($data->product->settings)) {
    // Convert object to array, handling nested objects
    $settings = json_decode(json_encode($data->product->settings), true) ?? [];
} elseif (is_string($data->product->settings)) {
    $settings = json_decode($data->product->settings, true) ?? [];
} elseif (is_array($data->product->settings)) {
    $settings = $data->product->settings;
}

$design_config = $settings['gs1_default_page_design'] ?? [
    'template' => 'modern',
    'theme' => 'blue',
    'enabled_datapoints' => [
        'product_name', 'brand_name', 'product_description', 'product_images',
        'category', 'manufacturer', 'country_of_origin'
    ],
    'last_updated' => null
];

// Define available templates
$templates = [
    'modern' => [
        'name' => 'Modern',
        'description' => 'Clean, contemporary design with gradient backgrounds',
        'preview_image' => 'template-modern.jpg'
    ],
    'classic' => [
        'name' => 'Classic',
        'description' => 'Traditional layout with clean lines',
        'preview_image' => 'template-classic.jpg'
    ],
    'minimal' => [
        'name' => 'Minimal',
        'description' => 'Simple, focused design with emphasis on content',
        'preview_image' => 'template-minimal.jpg'
    ],
    'detailed' => [
        'name' => 'Detailed',
        'description' => 'Comprehensive layout showing maximum information',
        'preview_image' => 'template-detailed.jpg'
    ]
];

// Define datapoint categories
$datapoint_categories = [
    'basic' => [
        'name' => 'Basic Information',
        'datapoints' => [
            'product_name' => 'Product Name',
            'brand_name' => 'Brand Name',
            'product_description' => 'Product Description',
            'product_images' => 'Product Images',
            'category' => 'Category',
            'subcategory' => 'Subcategory',
        ]
    ],
    'specifications' => [
        'name' => 'Specifications',
        'datapoints' => [
            'manufacturer' => 'Manufacturer',
            'country_of_origin' => 'Country of Origin',
            'net_weight' => 'Net Weight',
            'dimensions' => 'Dimensions',
            'net_weight_kg' => 'Net Weight (kg)',
            'length_m' => 'Length (m)',
            'width_m' => 'Width (m)',
            'height_m' => 'Height (m)',
        ]
    ],
    'content_compliance' => [
        'name' => 'Content & Compliance',
        'datapoints' => [
            'ingredients' => 'Ingredients',
            'nutritional_info' => 'Nutritional Information',
            'allergen_info' => 'Allergen Information',
            'certifications' => 'Certifications',
            'organic_certification' => 'Organic Certification',
            'fair_trade_certification' => 'Fair Trade Certification',
            'halal_certified' => 'Halal Certified',
            'kosher_certified' => 'Kosher Certified',
            'gluten_free' => 'Gluten Free',
            'vegan' => 'Vegan',
            'vegetarian' => 'Vegetarian',
            'non_gmo' => 'Non-GMO',
        ]
    ],
    'sustainability' => [
        'name' => 'Sustainability',
        'datapoints' => [
            'carbon_footprint' => 'Carbon Footprint',
            'water_usage' => 'Water Usage',
            'renewable_energy_percentage' => 'Renewable Energy %',
            'recyclability_score' => 'Recyclability Score',
            'sustainability_certifications' => 'Sustainability Certifications',
            'supply_chain_transparency' => 'Supply Chain Transparency',
            'ethical_sourcing' => 'Ethical Sourcing',
            'key_suppliers' => 'Key Suppliers',
            'blockchain_verified' => 'Blockchain Verified',
        ]
    ],
    'digital_links' => [
        'name' => 'Digital Links',
        'datapoints' => [
            'product_url' => 'Product Website',
            'manufacturer_url' => 'Manufacturer URL',
            'purchase_url' => 'Purchase URL',
            'manual_url' => 'User Manual',
            'support_url' => 'Support URL',
            'facebook_url' => 'Facebook',
            'instagram_url' => 'Instagram',
            'twitter_url' => 'Twitter',
            'youtube_url' => 'YouTube',
        ]
    ]
];
?>

<!-- 3-Column Layout: Datapoints | Preview | Templates & Settings -->
<div class="row gs1-design-builder link-settings">
    <!-- Left Column - Datapoints (Elements) -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3 shadow-sm">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-fw fa-list-check fa-sm text-muted mr-1"></i> 
                        <span><?= l('products.gs1.elements') ?></span>
                    </h6>
                </div>

                <!-- Elements Navigation Tabs -->
                <div class="microsite-block-tabs">
                    <div class="nav nav-pills nav-fill nav-minimal mb-4" id="elements-tabs" role="tablist">
                        <a class="nav-item nav-link active" id="elements-datapoints-tab" data-toggle="pill" href="#elements-datapoints" role="tab" aria-controls="elements-datapoints" aria-selected="true" data-toggle="tooltip" title="Data Points">
                            <i class="fas fa-database"></i>
                        </a>
                        <a class="nav-item nav-link" id="elements-actions-tab" data-toggle="pill" href="#elements-actions" role="tab" aria-controls="elements-actions" aria-selected="false" data-toggle="tooltip" title="Quick Actions">
                            <i class="fas fa-bolt"></i>
                        </a>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="elements-tabContent">
                    <!-- Datapoints Tab -->
                    <div class="tab-pane fade show active" id="elements-datapoints" role="tabpanel" aria-labelledby="elements-datapoints-tab">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Select data to display</small>
                            <div class="d-flex">
                                <button type="button" class="btn btn-xs btn-outline-primary" id="gs1-select-all">
                                    <i class="fas fa-fw fa-check-square fa-sm"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-secondary ml-1" id="gs1-deselect-all">
                                    <i class="fas fa-fw fa-square fa-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Datapoint Selection -->
                        <div class="datapoint-selection" style="max-height: 500px; overflow-y: auto;">
                            <?php foreach($datapoint_categories as $category_key => $category): ?>
                            <div class="microsite_block card shadow-sm mb-2">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2 d-none d-lg-block">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 24px; height: 24px; background-color: #007bff;">
                                                <i class="fas fa-database fa-fw fa-xs text-white"></i>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1">
                                            <div class="d-flex flex-column">
                                                <div class="text-truncate">
                                                    <a href="#"
                                                       data-toggle="collapse"
                                                       data-target="#gs1_category_content_<?= $category_key ?>"
                                                       aria-expanded="<?= $category_key === 'basic' ? 'true' : 'false' ?>"
                                                       aria-controls="gs1_category_content_<?= $category_key ?>"
                                                       class="text-truncate small font-weight-bold"
                                                    >
                                                        <?= $category['name'] ?>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center text-truncate">
                                                    <span class="text-muted small" style="font-size: 10px;">
                                                        <span class="gs1-category-count" data-category="<?= $category_key ?>">0</span> selected
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ml-2 d-flex align-items-center">
                                            <span class="badge badge-primary badge-sm gs1-category-count" data-category="<?= $category_key ?>">0</span>
                                        </div>
                                    </div>

                                    <div class="collapse mt-3 <?= $category_key === 'basic' ? 'show' : '' ?>" id="gs1_category_content_<?= $category_key ?>">
                                        <div class="border-top pt-2">
                                            <?php foreach($category['datapoints'] as $datapoint_key => $datapoint_name): ?>
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input gs1-datapoint-checkbox" data-category="<?= $category_key ?>" id="gs1_datapoint_<?= $datapoint_key ?>" name="gs1_enabled_datapoints[]" value="<?= $datapoint_key ?>" <?= in_array($datapoint_key, $design_config['enabled_datapoints']) ? 'checked' : '' ?>>
                                                <label class="custom-control-label small" for="gs1_datapoint_<?= $datapoint_key ?>">
                                                    <?= $datapoint_name ?>
                                                </label>
                                            </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                    <!-- Quick Actions Tab -->
                    <div class="tab-pane fade" id="elements-actions" role="tabpanel" aria-labelledby="elements-actions-tab">
                        <div class="text-center">
                            <?php if($data->product->gtin): ?>
                            <a href="<?= url('01/' . $data->product->gtin) ?>" target="_blank" class="btn btn-sm btn-outline-success btn-block mb-2">
                                <i class="fas fa-external-link-alt fa-sm mr-1"></i>
                                View Live Page
                            </a>
                            <?php endif ?>
                            
                            <button type="button" class="btn btn-sm btn-outline-primary btn-block" id="gs1-generate-preview">
                                <i class="fas fa-play fa-sm mr-1"></i>
                                Generate Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Column - Preview -->
    <div class="col-12 col-lg-4">
        <div class="d-flex justify-content-center mb-3">
            <div class="gs1-preview">
                <div class="gs1-preview-container position-relative">
                    <div id="gs1-preview-iframe" class="gs1-preview-frame">
                        <div class="preview-placeholder text-center p-4">
                            <i class="fas fa-mobile-alt fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Mobile Preview</h6>
                            <p class="small text-muted mb-3">Select template and datapoints to see preview</p>
                            <button type="button" class="btn btn-sm btn-primary" id="gs1-generate-preview-alt">
                                <i class="fas fa-play fa-sm mr-1"></i>
                                Generate Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Templates & Settings -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3 shadow-sm">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-fw fa-wrench fa-sm text-muted mr-1"></i> 
                        <span><?= l('link.header.settings_tab') ?></span>
                    </h6>
                    <div class="d-flex">
                        <button type="button" class="btn btn-xs btn-outline-success" id="gs1-save-btn" disabled>
                            <i class="fas fa-fw fa-save fa-sm"></i> <span class="gs1-save-text">Saved</span>
                        </button>
                    </div>
                </div>

                <!-- Settings Navigation Tabs -->
                <div class="microsite-block-tabs">
                    <div class="nav nav-pills nav-fill nav-minimal mb-4" id="settings-tabs" role="tablist">
                        <a class="nav-item nav-link active" id="settings-templates-tab" data-toggle="pill" href="#settings-templates" role="tab" aria-controls="settings-templates" aria-selected="true" data-toggle="tooltip" title="Templates">
                            <i class="fas fa-layer-group"></i>
                        </a>
                        <a class="nav-item nav-link" id="settings-themes-tab" data-toggle="pill" href="#settings-themes" role="tab" aria-controls="settings-themes" aria-selected="false" data-toggle="tooltip" title="Themes">
                            <i class="fas fa-palette"></i>
                        </a>
                        <a class="nav-item nav-link" id="settings-layout-tab" data-toggle="pill" href="#settings-layout" role="tab" aria-controls="settings-layout" aria-selected="false" data-toggle="tooltip" title="Layout Options">
                            <i class="fas fa-list"></i>
                        </a>
                    </div>
                </div>

                <div class="notification-container"></div>

                <!-- Tab Content -->
                <div class="tab-content" id="settings-tabContent">
                    <!-- Templates Tab -->
                    <div class="tab-pane fade show active" id="settings-templates" role="tabpanel" aria-labelledby="settings-templates-tab">
                        <div class="mt-3">
                            <?php foreach($templates as $template_key => $template): ?>
                            <div class="template-item card shadow-sm mb-2">
                                <div class="card-body p-2">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="gs1_template_<?= $template_key ?>" name="gs1_template" value="<?= $template_key ?>" class="custom-control-input gs1-template-radio" <?= ($design_config['template'] ?? 'modern') === $template_key ? 'checked' : '' ?>>
                                        <label class="custom-control-label w-100" for="gs1_template_<?= $template_key ?>">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <div class="template-preview-box" style="width: 40px; height: 30px; background: linear-gradient(45deg, #f8f9fa, #e9ecef); border-radius: 4px; border: 1px solid #dee2e6;"></div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="template-name font-weight-bold small"><?= $template['name'] ?></div>
                                                    <div class="template-description text-muted" style="font-size: 0.75rem;"><?= $template['description'] ?></div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                    <!-- Themes Tab -->
                    <div class="tab-pane fade" id="settings-themes" role="tabpanel" aria-labelledby="settings-themes-tab">
                        <div class="mt-3">
                            <h6 class="mb-2 small">
                                <i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i>
                                Color Theme
                            </h6>
                            <div class="theme-selection">
                                <div class="d-flex flex-wrap">
                                    <?php 
                                    $themes = [
                                        'blue' => '#007bff',
                                        'green' => '#28a745',
                                        'purple' => '#6f42c1',
                                        'orange' => '#fd7e14',
                                        'red' => '#dc3545',
                                        'gray' => '#6c757d'
                                    ];
                                    foreach($themes as $theme_key => $color): ?>
                                    <div class="mr-2 mb-2">
                                        <input type="radio" id="gs1_theme_<?= $theme_key ?>" name="gs1_theme" value="<?= $theme_key ?>" class="d-none gs1-theme-radio" <?= ($design_config['theme'] ?? 'blue') === $theme_key ? 'checked' : '' ?>>
                                        <label for="gs1_theme_<?= $theme_key ?>" class="theme-color-box" style="background-color: <?= $color ?>" title="<?= ucfirst($theme_key) ?>">
                                            <i class="fas fa-check text-white"></i>
                                        </label>
                                    </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Layout Tab -->
                    <div class="tab-pane fade" id="settings-layout" role="tabpanel" aria-labelledby="settings-layout-tab">
                        <div class="mt-3">
                            <h6 class="mb-3 small">
                                <i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i>
                                Section Display Options
                            </h6>
                            
                            <!-- Section Layout Options using Microsite Block Accordion Style -->
                            <div class="layout-options" style="max-height: 400px; overflow-y: auto;">
                                <?php 
                                $layout_sections = [
                                    'basic_info' => [
                                        'name' => 'Basic Information',
                                        'description' => 'Product name, brand, description, images',
                                        'icon' => 'fas fa-info-circle',
                                        'color' => '#007bff'
                                    ],
                                    'specifications' => [
                                        'name' => 'Specifications',
                                        'description' => 'Technical details, dimensions, weight',
                                        'icon' => 'fas fa-cogs',
                                        'color' => '#28a745'
                                    ],
                                    'content_compliance' => [
                                        'name' => 'Content & Compliance',
                                        'description' => 'Ingredients, certifications, allergens',
                                        'icon' => 'fas fa-certificate',
                                        'color' => '#ffc107'
                                    ],
                                    'sustainability' => [
                                        'name' => 'Sustainability',
                                        'description' => 'Environmental impact, certifications',
                                        'icon' => 'fas fa-leaf',
                                        'color' => '#28a745'
                                    ],
                                    'digital_links' => [
                                        'name' => 'Digital Links',
                                        'description' => 'Website, social media, support links',
                                        'icon' => 'fas fa-link',
                                        'color' => '#6f42c1'
                                    ]
                                ];
                                
                                foreach($layout_sections as $section_key => $section): ?>
                                <div class="microsite_block card shadow-sm mb-2">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2 d-none d-lg-block">
                                                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 24px; height: 24px; background-color: <?= $section['color'] ?>;">
                                                    <i class="<?= $section['icon'] ?> fa-fw fa-xs text-white"></i>
                                                </div>
                                            </div>

                                            <div class="flex-grow-1">
                                                <div class="d-flex flex-column">
                                                    <div class="text-truncate">
                                                        <span class="text-truncate small font-weight-bold">
                                                            <?= $section['name'] ?>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center text-truncate">
                                                        <span class="text-muted small" style="font-size: 10px;">
                                                            <?= $section['description'] ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="ml-2 d-flex align-items-center">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input gs1-section-accordion" id="gs1_section_accordion_<?= $section_key ?>" name="gs1_section_accordions[]" value="<?= $section_key ?>" <?= in_array($section_key, $design_config['section_accordions'] ?? []) ? 'checked' : '' ?>>
                                                    <label class="custom-control-label" for="gs1_section_accordion_<?= $section_key ?>" data-toggle="tooltip" title="Show as accordion"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach ?>
                            </div>

                            <div class="mt-3 p-2 bg-light rounded">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle fa-sm mr-1"></i>
                                    <strong>Accordion Mode:</strong> When enabled, sections will be collapsible on the public page, allowing users to expand only the information they need.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif ?>

<link rel="stylesheet" href="<?= ASSETS_FULL_URL . 'css/microsite-tabs-theme.css?v=' . PRODUCT_CODE ?>">

<style>
/* GS1 Design Builder - Mobile-First 3-Column Layout */
.gs1-design-builder {
    min-height: 600px;
}

/* Mobile-first responsive design */
@media (max-width: 991.98px) {
    .gs1-design-builder .col-12 {
        margin-bottom: 1rem;
    }
}

/* Template Selection Styles */
.template-item {
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s ease;
}

.template-item:hover {
    border-color: #dee2e6;
}

.template-item.selected {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.template-preview-box {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
    border-radius: 4px;
    position: relative;
}

.template-preview-box::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    right: 2px;
    height: 8px;
    background: linear-gradient(90deg, #007bff 0%, #28a745 50%, #ffc107 100%);
    border-radius: 2px;
    opacity: 0.7;
}

/* Theme Color Selection */
.theme-color-box {
    display: block;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    position: relative;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.theme-color-box:hover {
    transform: scale(1.1);
    border-color: rgba(0,0,0,0.2);
}

.theme-color-box i {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    font-size: 10px;
}

input[type="radio"]:checked + .theme-color-box {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

input[type="radio"]:checked + .theme-color-box i {
    opacity: 1;
}

/* Mobile Preview Styles - Match Microsite Builder Exactly */
.gs1-preview {
    position: relative;
    margin: 0 auto;
    height: auto;
    width: auto;
    display: inline-block;
    text-align: left;
}

.gs1-preview-container {
    overflow: hidden;
    width: 300px;
    height: 625px;
    border-radius: 2.5rem;
    border: 10px solid black;
    position: relative;
    box-shadow: 0 0 30px rgba(0,0,0,0.20);
}

@media (min-width: 768px) {
    .gs1-preview-container {
        width: 375px;
        height: 800px;
    }
}

.gs1-preview-frame {
    width: 100%;
    height: 100%;
    border: 0;
    margin: 0;
    padding: 0;
    background: white;
    overflow: hidden;
    position: relative;
}

.preview-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    padding: 2rem;
}

/* Datapoint Selection */
.datapoint-selection .card {
    border: 1px solid #e9ecef;
    border-radius: 6px;
}

.datapoint-selection .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 6px 6px 0 0;
}

.gs1-category-count {
    font-size: 0.7rem;
    min-width: 18px;
    height: 18px;
    line-height: 16px;
    text-align: center;
}

/* Save Button States */
#gs1-save-btn.btn-outline-success {
    color: #28a745;
    border-color: #28a745;
}

#gs1-save-btn.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

#gs1-save-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Microsite Block Tabs Styling */
.microsite-block-tabs .nav-pills {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 4px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-control-sm {
    font-size: 0.875rem;
}

.small.font-weight-bold {
    font-weight: 600;
}

/* Mobile Optimizations */
@media (max-width: 767.98px) {
    .gs1-design-builder .card-body {
        padding: 1rem;
    }
    
    .template-preview-box {
        width: 35px;
        height: 25px;
    }
    
    .theme-color-box {
        width: 24px;
        height: 24px;
    }
    
    .gs1-preview {
        max-width: 250px;
    }
    
    .gs1-preview-frame {
        min-height: 350px;
    }
    
    .datapoint-selection {
        max-height: 400px !important;
    }
}

/* Loading States */
.preview-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 15px;
}

/* Preview Content Styles */
.gs1-product-preview {
    padding: 1rem;
    font-size: 0.9rem;
}

.gs1-product-preview h1 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.gs1-product-preview h2 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    color: #6c757d;
}

.gs1-product-preview p {
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 0.75rem;
}

/* Accordion Link Styling - Remove Underlines */
.datapoint-selection .microsite_block a {
    text-decoration: none !important;
    color: inherit;
}

.datapoint-selection .microsite_block a:hover {
    text-decoration: none !important;
    color: #007bff;
}

.datapoint-selection .microsite_block a:focus {
    text-decoration: none !important;
    outline: none;
    box-shadow: none;
}

/* Theme-based preview styling */
.template-modern { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.template-classic { background: #f8f9fa; }
.template-minimal { background: white; }
.template-detailed { background: linear-gradient(to bottom, #f8f9fa, white); }

.theme-blue .gs1-product-preview { border-left: 4px solid #007bff; }
.theme-green .gs1-product-preview { border-left: 4px solid #28a745; }
.theme-purple .gs1-product-preview { border-left: 4px solid #6f42c1; }
.theme-orange .gs1-product-preview { border-left: 4px solid #fd7e14; }
.theme-red .gs1-product-preview { border-left: 4px solid #dc3545; }
.theme-gray .gs1-product-preview { border-left: 4px solid #6c757d; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let hasChanges = false;
    
    // Update category counts
    function updateGs1CategoryCounts() {
        document.querySelectorAll('.gs1-category-count').forEach(function(badge) {
            const category = badge.getAttribute('data-category');
            const checkedBoxes = document.querySelectorAll(`.gs1-datapoint-checkbox[data-category="${category}"]:checked`);
            badge.textContent = checkedBoxes.length;
        });
    }

    // Update save button state
    function updateSaveButton() {
        const saveBtn = document.getElementById('gs1-save-btn');
        const saveText = document.querySelector('.gs1-save-text');
        
        if (!saveBtn || !saveText) {
            console.warn('Save button or save text element not found');
            return;
        }
        
        if (hasChanges) {
            saveBtn.disabled = false;
            saveBtn.className = 'btn btn-xs btn-success';
            saveText.textContent = 'Save Changes';
        } else {
            saveBtn.disabled = true;
            saveBtn.className = 'btn btn-xs btn-outline-success';
            saveText.textContent = 'Saved';
        }
    }

    // Mark changes
    function markChanges() {
        hasChanges = true;
        updateSaveButton();
        generateGs1Preview(); // Auto-generate preview on changes
    }

    // Initialize counts
    updateGs1CategoryCounts();
    updateSaveButton();

    // Template selection handlers
    document.querySelectorAll('.gs1-template-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            // Update template item styling
            document.querySelectorAll('.template-item').forEach(item => item.classList.remove('selected'));
            this.closest('.template-item').classList.add('selected');
            markChanges();
        });
    });

    // Theme selection handlers
    document.querySelectorAll('.gs1-theme-radio').forEach(function(radio) {
        radio.addEventListener('change', markChanges);
    });

    // Section accordion handlers
    document.querySelectorAll('.gs1-section-accordion').forEach(function(checkbox) {
        checkbox.addEventListener('change', markChanges);
    });

    // Update counts when checkboxes change
    document.querySelectorAll('.gs1-datapoint-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateGs1CategoryCounts();
            markChanges();
        });
    });

    // Select all datapoints
    document.getElementById('gs1-select-all')?.addEventListener('click', function() {
        document.querySelectorAll('.gs1-datapoint-checkbox').forEach(function(checkbox) {
            checkbox.checked = true;
        });
        updateGs1CategoryCounts();
        markChanges();
    });

    // Deselect all datapoints
    document.getElementById('gs1-deselect-all')?.addEventListener('click', function() {
        document.querySelectorAll('.gs1-datapoint-checkbox').forEach(function(checkbox) {
            checkbox.checked = false;
        });
        updateGs1CategoryCounts();
        markChanges();
    });

    // Preview functionality - now uses server-side rendering for accuracy
    async function generateGs1Preview() {
        const template = document.querySelector('input[name="gs1_template"]:checked')?.value || 'modern';
        const theme = document.querySelector('input[name="gs1_theme"]:checked')?.value || 'blue';
        const enabledDatapoints = Array.from(document.querySelectorAll('.gs1-datapoint-checkbox:checked')).map(cb => cb.value);

        const previewFrame = document.getElementById('gs1-preview-iframe');
        
        // Show loading state
        previewFrame.innerHTML = `
            <div class="preview-loading">
                <div class="text-center">
                    <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                    <div class="small text-muted">Generating preview...</div>
                </div>
            </div>
        `;

        try {
            // Get product ID from current URL or form
            const productId = <?= $data->product->product_id ?? 0 ?>;
            
            if (!productId) {
                throw new Error('Product ID not found');
            }

            // Call the server-side preview endpoint
            const response = await fetch(`<?= url('gs1-product/preview') ?>/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    template: template,
                    theme: theme,
                    enabled_datapoints: enabledDatapoints
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.html) {
                // Create an iframe to display the preview with proper styling isolation
                const iframe = document.createElement('iframe');
                iframe.style.width = '100%';
                iframe.style.height = '100%';
                iframe.style.border = 'none';
                iframe.style.borderRadius = '15px';
                iframe.style.overflow = 'hidden';
                
                previewFrame.innerHTML = '';
                previewFrame.appendChild(iframe);
                
                // Write the HTML content to the iframe
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                iframeDoc.open();
                iframeDoc.write(data.html);
                iframeDoc.close();
                
                // Add mobile viewport meta tag to iframe
                const meta = iframeDoc.createElement('meta');
                meta.name = 'viewport';
                meta.content = 'width=device-width, initial-scale=1, shrink-to-fit=no';
                iframeDoc.head.appendChild(meta);
                
                // Scale the iframe content to fit the preview container
                iframe.onload = function() {
                    const iframeBody = iframeDoc.body;
                    if (iframeBody) {
                        // Apply mobile-first scaling
                        iframeBody.style.transform = 'scale(0.8)';
                        iframeBody.style.transformOrigin = 'top left';
                        iframeBody.style.width = '125%'; // Compensate for scale
                        iframeBody.style.height = '125%';
                    }
                };
                
            } else {
                throw new Error(data.error || 'Failed to generate preview');
            }

        } catch (error) {
            console.error('Preview generation error:', error);
            
            // Show error state with fallback
            previewFrame.innerHTML = `
                <div class="preview-placeholder text-center p-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h6 class="text-muted">Preview Error</h6>
                    <p class="small text-muted mb-3">Unable to generate preview. Please try again.</p>
                    <div class="small text-muted">
                        Template: ${template} • Theme: ${theme} • ${enabledDatapoints.length} datapoints
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="generateGs1Preview()">
                        <i class="fas fa-redo fa-sm mr-1"></i>
                        Retry
                    </button>
                </div>
            `;
        }
    }

    // Preview button clicks
    document.getElementById('gs1-generate-preview')?.addEventListener('click', generateGs1Preview);
    document.getElementById('gs1-generate-preview-alt')?.addEventListener('click', generateGs1Preview);

    // Auto-generate initial preview
    setTimeout(generateGs1Preview, 500);

    // Initialize selected template styling
    const selectedTemplate = document.querySelector('.gs1-template-radio:checked');
    if (selectedTemplate) {
        selectedTemplate.closest('.template-item').classList.add('selected');
    }

    // Save button handler - integrates with main form submission
    document.getElementById('gs1-save-btn')?.addEventListener('click', async function() {
        if (!hasChanges) return;
        
        const saveBtn = this;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin fa-sm"></i> <span>Saving...</span>';
        
        // Collect all GS1 design data
        const template = document.querySelector('input[name="gs1_template"]:checked')?.value || 'modern';
        const theme = document.querySelector('input[name="gs1_theme"]:checked')?.value || 'blue';
        const enabledDatapoints = Array.from(document.querySelectorAll('.gs1-datapoint-checkbox:checked')).map(cb => cb.value);
        const sectionAccordions = Array.from(document.querySelectorAll('.gs1-section-accordion:checked')).map(cb => cb.value);
        
        // Create form data for submission
        const formData = new FormData();
        formData.append('section', 'gs1-default-page-design');
        formData.append('gs1_template', template);
        formData.append('gs1_theme', theme);
        enabledDatapoints.forEach(datapoint => {
            formData.append('gs1_enabled_datapoints[]', datapoint);
        });
        sectionAccordions.forEach(section => {
            formData.append('gs1_section_accordions[]', section);
        });
        
        // Add CSRF token if available
        const csrfToken = document.querySelector('input[name="token"]')?.value;
        if (csrfToken) {
            formData.append('token', csrfToken);
        }
        
        // Submit via AJAX to the same endpoint as the main form
        try {
            const response = await fetch(window.location.href, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            let data;
            try {
                data = await response.json();
                console.log('Response data:', data);
            } catch (jsonError) {
                const text = await response.text();
                console.log('Non-JSON response:', text);
                throw new Error('Server returned non-JSON response');
            }
            
            console.log('Processing response data:', data);
            console.log('Data type:', typeof data);
            console.log('Data status:', data ? data.status : 'undefined');
            
            if (data && data.status === 'success') {
                console.log('Success condition met, updating UI');
                
                try {
                    hasChanges = false;
                    console.log('hasChanges set to false');
                    
                    try {
                        updateSaveButton();
                        console.log('updateSaveButton called');
                    } catch (updateButtonError) {
                        console.error('Error in updateSaveButton:', updateButtonError);
                        // Skip updateSaveButton and continue with manual button update
                    }
                    
                    // Show success feedback
                    saveBtn.innerHTML = '<i class="fas fa-check fa-sm"></i> <span>Saved!</span>';
                    saveBtn.className = 'btn btn-xs btn-success';
                    console.log('Button UI updated');
                    
                    // Show toast notification if available
                    if (typeof show_toast === 'function') {
                        show_toast('GS1 design settings saved successfully!', 'success');
                        console.log('Toast notification shown');
                    }
                    
                    setTimeout(() => {
                        try {
                            updateSaveButton();
                            console.log('Delayed updateSaveButton completed');
                        } catch (timeoutError) {
                            console.error('Error in setTimeout callback:', timeoutError);
                        }
                    }, 2000);
                    
                    console.log('Success handling completed successfully');
                } catch (successError) {
                    console.error('Error during success handling:', successError);
                    throw successError;
                }
            } else {
                console.log('Success condition not met, throwing error');
                console.log('Data object:', data);
                throw new Error((data && data.message) || 'Save failed - invalid response format');
            }
        } catch (error) {
            console.error('Save error:', error);
            
            // Show error state
            saveBtn.innerHTML = '<i class="fas fa-exclamation-triangle fa-sm"></i> <span>Error</span>';
            saveBtn.className = 'btn btn-xs btn-danger';
            saveBtn.disabled = false;
            
            // Show error notification if available
            if (typeof show_toast === 'function') {
                show_toast('Failed to save GS1 design settings. Please try again.', 'error');
            } else {
                alert('Failed to save GS1 design settings. Please try again.');
            }
            
            setTimeout(() => {
                updateSaveButton();
            }, 3000);
        }
    });
});
</script>
