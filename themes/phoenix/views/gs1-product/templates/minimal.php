<?php defined('SEEGAP') || die() ?>

<?php
/* Get design configuration */
$template = $data->design_config->template ?? 'minimal';
$theme = $data->design_config->theme ?? 'blue';
$enabled_datapoints = $data->design_config->enabled_datapoints ?? [];
$section_accordions = $data->design_config->section_accordions ?? [];
$is_preview = $data->is_preview ?? false;

/* Helper functions for checking product data */
function has_product_data($product, $field) {
    return !empty($product->$field) && $product->$field !== null && $product->$field !== '';
}

/* NEW: Fixed function - show datapoint if enabled, regardless of data */
function should_display_datapoint($datapoint_key, $enabled_datapoints = []) {
    // If no datapoints are specified, don't show anything
    if (empty($enabled_datapoints)) {
        return false;
    }
    return in_array($datapoint_key, $enabled_datapoints);
}

/* NEW: Get display value with placeholder for empty fields */
function get_display_value($product, $datapoint, $placeholder = 'Not specified') {
    if (has_product_data($product, $datapoint)) {
        return $product->$datapoint;
    }
    return '<span class="text-muted font-italic">' . $placeholder . '</span>';
}

/* NEW: Check if section should be accordion */
function is_section_accordion($section_key, $section_accordions) {
    return in_array($section_key, $section_accordions);
}

/* NEW: Generate section wrapper for minimal template */
function section_wrapper_start($section_key, $section_accordions, $title) {
    $is_accordion = is_section_accordion($section_key, $section_accordions);
    $section_id = 'gs1_section_' . $section_key;
    
    if ($is_accordion) {
        return '
        <div class="gs1-minimal-section accordion-section">
            <div class="accordion-header" data-toggle="collapse" data-target="#' . $section_id . '" aria-expanded="true" aria-controls="' . $section_id . '">
                <h2 class="gs1-minimal-section-title">' . $title . ' <i class="fas fa-chevron-down accordion-toggle" style="float: right; font-size: 0.8rem; margin-top: 0.2rem;"></i></h2>
            </div>
            <div class="collapse show" id="' . $section_id . '">';
    } else {
        return '
        <div class="gs1-minimal-section">
            <h2 class="gs1-minimal-section-title">' . $title . '</h2>';
    }
}

function section_wrapper_end($section_key, $section_accordions) {
    $is_accordion = is_section_accordion($section_key, $section_accordions);
    
    if ($is_accordion) {
        return '
            </div>
        </div>';
    } else {
        return '
        </div>';
    }
}

/* Theme color mapping */
$theme_colors = [
    'blue' => ['primary' => '#007bff', 'secondary' => '#6c757d', 'accent' => '#17a2b8'],
    'green' => ['primary' => '#28a745', 'secondary' => '#6c757d', 'accent' => '#20c997'],
    'purple' => ['primary' => '#6f42c1', 'secondary' => '#6c757d', 'accent' => '#e83e8c'],
    'orange' => ['primary' => '#fd7e14', 'secondary' => '#6c757d', 'accent' => '#ffc107'],
    'red' => ['primary' => '#dc3545', 'secondary' => '#6c757d', 'accent' => '#e83e8c'],
    'gray' => ['primary' => '#6c757d', 'secondary' => '#495057', 'accent' => '#adb5bd']
];

$colors = $theme_colors[$theme] ?? $theme_colors['blue'];
?>

<style>
:root {
    --primary-color: <?= $colors['primary'] ?>;
    --secondary-color: <?= $colors['secondary'] ?>;
    --accent-color: <?= $colors['accent'] ?>;
}

.gs1-minimal-template {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    line-height: 1.5;
    color: #333;
    background: white;
    max-width: 600px;
    margin: 0 auto;
    padding: 1rem;
}

.gs1-minimal-header {
    text-align: center;
    padding: 1.5rem 0;
    border-bottom: 2px solid var(--primary-color);
    margin-bottom: 2rem;
}

.gs1-minimal-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--primary-color);
    margin: 0 0 0.5rem 0;
}

.gs1-minimal-subtitle {
    font-size: 0.9rem;
    color: var(--secondary-color);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.gs1-minimal-section {
    margin-bottom: 1.5rem;
}

.gs1-minimal-section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.75rem;
    padding-bottom: 0.25rem;
    border-bottom: 1px solid #e9ecef;
}

.gs1-minimal-field {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.gs1-minimal-field:last-child {
    border-bottom: none;
}

.gs1-minimal-label {
    font-weight: 500;
    color: var(--secondary-color);
    font-size: 0.9rem;
    flex: 0 0 40%;
    margin-right: 1rem;
}

.gs1-minimal-value {
    flex: 1;
    color: #333;
    font-size: 0.9rem;
    text-align: right;
    word-break: break-word;
}

.gs1-minimal-description {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 4px;
    border-left: 3px solid var(--primary-color);
    margin: 1rem 0;
    font-size: 0.9rem;
    line-height: 1.6;
}

.gs1-minimal-images {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.5rem;
    margin: 1rem 0;
}

.gs1-minimal-image {
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.gs1-minimal-image img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    display: block;
}

.gs1-minimal-qr {
    text-align: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 4px;
    margin: 2rem 0;
}

.gs1-minimal-qr-title {
    font-size: 1rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 500;
}

.gs1-minimal-qr-code {
    display: inline-block;
    padding: 0.5rem;
    background: white;
    border-radius: 4px;
    border: 1px solid #dee2e6;
}

.gs1-minimal-footer {
    text-align: center;
    padding: 1rem 0;
    border-top: 1px solid #e9ecef;
    margin-top: 2rem;
    font-size: 0.8rem;
    color: var(--secondary-color);
}

.gs1-minimal-badge {
    display: inline-block;
    background: var(--primary-color);
    color: white;
    padding: 0.2rem 0.5rem;
    font-size: 0.7rem;
    border-radius: 3px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    font-weight: 500;
}

/* Accordion styles for minimal template */
.accordion-section .accordion-header {
    cursor: pointer;
    transition: all 0.3s ease;
}

.accordion-section .accordion-header:hover {
    opacity: 0.8;
}

.accordion-section .accordion-toggle {
    transition: transform 0.3s ease;
}

.accordion-section .accordion-header[aria-expanded="false"] .accordion-toggle {
    transform: rotate(-90deg);
}

.accordion-section .collapse {
    transition: all 0.3s ease;
    overflow: hidden;
}

.accordion-section .collapse:not(.show) {
    display: none;
}

.accordion-section .collapse.show {
    display: block;
}

@media (max-width: 480px) {
    .gs1-minimal-template {
        padding: 0.5rem;
    }
    
    .gs1-minimal-field {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .gs1-minimal-label {
        margin-bottom: 0.25rem;
        margin-right: 0;
    }
    
    .gs1-minimal-value {
        text-align: left;
    }
    
    .gs1-minimal-images {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<div class="gs1-minimal-template">
    
    <!-- Header -->
    <div class="gs1-minimal-header">
        <h1 class="gs1-minimal-title">
            <?= should_display_datapoint('product_name', $enabled_datapoints) ? get_display_value($data->product, 'product_name', $data->product->gtin) : $data->product->gtin ?>
        </h1>
        <p class="gs1-minimal-subtitle">
            <?= l('products.gs1.digital_passport') ?>
        </p>
        <?php if (has_product_data($data->product, 'gtin')): ?>
        <div style="margin-top: 0.5rem;">
            <span class="gs1-minimal-badge"><?= $data->product->gtin ?></span>
        </div>
        <?php endif ?>
    </div>

    <!-- Basic Information Section -->
    <?php 
    $basic_datapoints = ['product_name', 'brand_name', 'product_description', 'category', 'subcategory', 'manufacturer', 'country_of_origin'];
    $has_basic_info = false;
    foreach($basic_datapoints as $dp) {
        if(should_display_datapoint($dp, $enabled_datapoints)) {
            $has_basic_info = true;
            break;
        }
    }
    ?>
    <?php if($has_basic_info): ?>
    <?= section_wrapper_start('basic_info', $section_accordions, 'Product Details') ?>
        
        <?php if (should_display_datapoint('product_name', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.product_name') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'product_name') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('brand_name', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.brand_name') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'brand_name') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('manufacturer', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.manufacturer') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'manufacturer') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('category', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.category') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'category') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('subcategory', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.subcategory') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'subcategory') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('country_of_origin', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.country_of_origin') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'country_of_origin') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('product_description', $enabled_datapoints)): ?>
        <div class="gs1-minimal-description">
            <?= nl2br(get_display_value($data->product, 'product_description')) ?>
        </div>
        <?php endif ?>

    <?= section_wrapper_end('basic_info', $section_accordions) ?>
    <?php endif ?>

    <!-- Product Images Section -->
    <?php if (should_display_datapoint('product_images', $enabled_datapoints)): ?>
    <?= section_wrapper_start('images', $section_accordions, 'Images') ?>
        <?php if (has_product_data($data->product, 'product_images')): ?>
        <div class="gs1-minimal-images">
            <?php foreach($data->product->product_images as $image): ?>
            <div class="gs1-minimal-image">
                <img src="<?= $image ?>" alt="<?= $data->product->product_name ?>" loading="lazy">
            </div>
            <?php endforeach ?>
        </div>
        <?php else: ?>
        <div class="text-center text-muted" style="padding: 2rem;">
            <i class="fas fa-image fa-2x mb-2"></i>
            <p style="font-size: 0.9rem;">No product images available</p>
        </div>
        <?php endif ?>
    <?= section_wrapper_end('images', $section_accordions) ?>
    <?php endif ?>

    <!-- Technical Specifications Section -->
    <?php 
    $spec_datapoints = ['net_weight', 'dimensions', 'net_weight_kg', 'length_m', 'width_m', 'height_m'];
    $has_specs = false;
    foreach($spec_datapoints as $dp) {
        if(should_display_datapoint($dp, $enabled_datapoints)) {
            $has_specs = true;
            break;
        }
    }
    ?>
    <?php if($has_specs): ?>
    <?= section_wrapper_start('specifications', $section_accordions, 'Specifications') ?>
        
        <?php if (should_display_datapoint('net_weight', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.net_weight') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'net_weight') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('net_weight_kg', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.net_weight_kg') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'net_weight_kg') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('dimensions', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.dimensions') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'dimensions') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('length_m', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.length_m') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'length_m') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('width_m', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.width_m') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'width_m') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('height_m', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.height_m') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'height_m') ?></span>
        </div>
        <?php endif ?>

    <?= section_wrapper_end('specifications', $section_accordions) ?>
    <?php endif ?>

    <!-- Content & Compliance Section -->
    <?php 
    $content_datapoints = ['ingredients', 'nutritional_info', 'allergen_info', 'certifications', 'organic_certification', 'fair_trade_certification', 'halal_certified', 'kosher_certified', 'gluten_free', 'vegan', 'vegetarian', 'non_gmo'];
    $has_content = false;
    foreach($content_datapoints as $dp) {
        if(should_display_datapoint($dp, $enabled_datapoints)) {
            $has_content = true;
            break;
        }
    }
    ?>
    <?php if($has_content): ?>
    <?= section_wrapper_start('content_compliance', $section_accordions, 'Content & Compliance') ?>
        
        <?php if (should_display_datapoint('ingredients', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.ingredients') ?></span>
            <span class="gs1-minimal-value"><?= nl2br(get_display_value($data->product, 'ingredients')) ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('nutritional_info', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.nutritional_info') ?></span>
            <span class="gs1-minimal-value"><?= nl2br(get_display_value($data->product, 'nutritional_info')) ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('allergen_info', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.allergen_info') ?></span>
            <span class="gs1-minimal-value"><?= nl2br(get_display_value($data->product, 'allergen_info')) ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('certifications', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.certifications') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'certifications') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('organic_certification', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.organic_certification') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'organic_certification') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('fair_trade_certification', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.fair_trade_certification') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'fair_trade_certification') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('halal_certified', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.halal_certified') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'halal_certified') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('kosher_certified', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.kosher_certified') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'kosher_certified') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('gluten_free', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.gluten_free') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'gluten_free') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('vegan', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.vegan') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'vegan') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('vegetarian', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.vegetarian') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'vegetarian') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('non_gmo', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label"><?= l('products.non_gmo') ?></span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'non_gmo') ?></span>
        </div>
        <?php endif ?>

    <?= section_wrapper_end('content_compliance', $section_accordions) ?>
    <?php endif ?>

    <!-- Sustainability Section -->
    <?php 
    $sustainability_datapoints = ['carbon_footprint', 'water_usage', 'renewable_energy_percentage', 'recyclability_score', 'sustainability_certifications', 'supply_chain_transparency', 'ethical_sourcing', 'key_suppliers', 'blockchain_verified'];
    $has_sustainability = false;
    foreach($sustainability_datapoints as $dp) {
        if(should_display_datapoint($dp, $enabled_datapoints)) {
            $has_sustainability = true;
            break;
        }
    }
    ?>
    <?php if($has_sustainability): ?>
    <?= section_wrapper_start('sustainability', $section_accordions, 'Sustainability') ?>
        
        <?php if (should_display_datapoint('carbon_footprint', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Carbon Footprint</span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'carbon_footprint') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('water_usage', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Water Usage</span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'water_usage') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('renewable_energy_percentage', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Renewable Energy</span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'renewable_energy_percentage') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('recyclability_score', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Recyclability Score</span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'recyclability_score') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('sustainability_certifications', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Sustainability Certifications</span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'sustainability_certifications') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('supply_chain_transparency', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Supply Chain Transparency</span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'supply_chain_transparency') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('ethical_sourcing', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Ethical Sourcing</span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'ethical_sourcing') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('key_suppliers', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Key Suppliers</span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'key_suppliers') ?></span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('blockchain_verified', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Blockchain Verified</span>
            <span class="gs1-minimal-value"><?= get_display_value($data->product, 'blockchain_verified') ?></span>
        </div>
        <?php endif ?>

    <?= section_wrapper_end('sustainability', $section_accordions) ?>
    <?php endif ?>

    <!-- Digital Links Section -->
    <?php 
    $digital_datapoints = ['product_url', 'manufacturer_url', 'purchase_url', 'manual_url', 'support_url', 'facebook_url', 'instagram_url', 'twitter_url', 'youtube_url'];
    $has_digital = false;
    foreach($digital_datapoints as $dp) {
        if(should_display_datapoint($dp, $enabled_datapoints)) {
            $has_digital = true;
            break;
        }
    }
    ?>
    <?php if($has_digital): ?>
    <?= section_wrapper_start('digital_links', $section_accordions, 'Digital Links') ?>
        
        <?php if (should_display_datapoint('product_url', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Product Website</span>
            <span class="gs1-minimal-value">
                <?php if (has_product_data($data->product, 'product_url')): ?>
                <a href="<?= $data->product->product_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                    Visit Website
                </a>
                <?php else: ?>
                <?= get_display_value($data->product, 'product_url') ?>
                <?php endif ?>
            </span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('manufacturer_url', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Manufacturer</span>
            <span class="gs1-minimal-value">
                <?php if (has_product_data($data->product, 'manufacturer_url')): ?>
                <a href="<?= $data->product->manufacturer_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                    Visit Manufacturer
                </a>
                <?php else: ?>
                <?= get_display_value($data->product, 'manufacturer_url') ?>
                <?php endif ?>
            </span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('purchase_url', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Purchase</span>
            <span class="gs1-minimal-value">
                <?php if (has_product_data($data->product, 'purchase_url')): ?>
                <a href="<?= $data->product->purchase_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                    Buy Now
                </a>
                <?php else: ?>
                <?= get_display_value($data->product, 'purchase_url') ?>
                <?php endif ?>
            </span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('manual_url', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Manual</span>
            <span class="gs1-minimal-value">
                <?php if (has_product_data($data->product, 'manual_url')): ?>
                <a href="<?= $data->product->manual_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                    View Manual
                </a>
                <?php else: ?>
                <?= get_display_value($data->product, 'manual_url') ?>
                <?php endif ?>
            </span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('support_url', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Support</span>
            <span class="gs1-minimal-value">
                <?php if (has_product_data($data->product, 'support_url')): ?>
                <a href="<?= $data->product->support_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                    Get Support
                </a>
                <?php else: ?>
                <?= get_display_value($data->product, 'support_url') ?>
                <?php endif ?>
            </span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('facebook_url', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Facebook</span>
            <span class="gs1-minimal-value">
                <?php if (has_product_data($data->product, 'facebook_url')): ?>
                <a href="<?= $data->product->facebook_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                    Follow
                </a>
                <?php else: ?>
                <?= get_display_value($data->product, 'facebook_url') ?>
                <?php endif ?>
            </span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('instagram_url', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Instagram</span>
            <span class="gs1-minimal-value">
                <?php if (has_product_data($data->product, 'instagram_url')): ?>
                <a href="<?= $data->product->instagram_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                    Follow
                </a>
                <?php else: ?>
                <?= get_display_value($data->product, 'instagram_url') ?>
                <?php endif ?>
            </span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('twitter_url', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">Twitter</span>
            <span class="gs1-minimal-value">
                <?php if (has_product_data($data->product, 'twitter_url')): ?>
                <a href="<?= $data->product->twitter_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                    Follow
                </a>
                <?php else: ?>
                <?= get_display_value($data->product, 'twitter_url') ?>
                <?php endif ?>
            </span>
        </div>
        <?php endif ?>

        <?php if (should_display_datapoint('youtube_url', $enabled_datapoints)): ?>
        <div class="gs1-minimal-field">
            <span class="gs1-minimal-label">YouTube</span>
            <span class="gs1-minimal-value">
                <?php if (has_product_data($data->product, 'youtube_url')): ?>
                <a href="<?= $data->product->youtube_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                    Watch
                </a>
                <?php else: ?>
                <?= get_display_value($data->product, 'youtube_url') ?>
                <?php endif ?>
            </span>
        </div>
        <?php endif ?>

    <?= section_wrapper_end('digital_links', $section_accordions) ?>
    <?php endif ?>


    <!-- Footer -->
    <div class="gs1-minimal-footer">
        <p>GS1 Digital Product Passport • Updated <?= date('M j, Y') ?></p>
    </div>

</div>

<!-- Add accordion functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Accordion functionality for minimal template
document.addEventListener('DOMContentLoaded', function() {
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const targetElement = document.querySelector(target);
            const toggle = this.querySelector('.accordion-toggle');
            
            if (targetElement) {
                if (targetElement.classList.contains('show')) {
                    targetElement.classList.remove('show');
                    this.setAttribute('aria-expanded', 'false');
                    if (toggle) toggle.style.transform = 'rotate(-90deg)';
                } else {
                    targetElement.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                    if (toggle) toggle.style.transform = 'rotate(0deg)';
                }
            }
        });
    });
});
</script>
