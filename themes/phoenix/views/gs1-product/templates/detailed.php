<?php defined('SEEGAP') || die() ?>

<?php
/* Get design configuration */
$template = $data->design_config->template ?? 'detailed';
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

/* NEW: Generate section wrapper for detailed template */
function section_wrapper_start($section_key, $section_accordions, $title, $icon = 'fas fa-info-circle') {
    $is_accordion = is_section_accordion($section_key, $section_accordions);
    $section_id = 'gs1_section_' . $section_key;
    
    if ($is_accordion) {
        return '
        <div class="gs1-detailed-section accordion-section">
            <div class="accordion-header" data-toggle="collapse" data-target="#' . $section_id . '" aria-expanded="true" aria-controls="' . $section_id . '">
                <div class="gs1-detailed-section-header">
                    <i class="' . $icon . '"></i>
                    ' . $title . '
                    <i class="fas fa-chevron-down accordion-toggle" style="margin-left: auto;"></i>
                </div>
            </div>
            <div class="collapse show" id="' . $section_id . '">
                <div class="gs1-detailed-section-body">';
    } else {
        return '
        <div class="gs1-detailed-section">
            <div class="gs1-detailed-section-header">
                <i class="' . $icon . '"></i>
                ' . $title . '
            </div>
            <div class="gs1-detailed-section-body">';
    }
}

function section_wrapper_end($section_key, $section_accordions) {
    $is_accordion = is_section_accordion($section_key, $section_accordions);
    
    if ($is_accordion) {
        return '
                </div>
            </div>
        </div>';
    } else {
        return '
            </div>
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

.gs1-detailed-template {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    line-height: 1.6;
    color: #333;
}

.gs1-detailed-header {
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    color: white;
    padding: 2rem 0;
    text-align: center;
    margin-bottom: 2rem;
}

.gs1-detailed-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.gs1-detailed-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    font-weight: 300;
}

.gs1-detailed-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.gs1-detailed-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.gs1-detailed-section-header {
    background: var(--primary-color);
    color: white;
    padding: 1rem 1.5rem;
    font-size: 1.3rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.gs1-detailed-section-body {
    padding: 1.5rem;
}

.gs1-detailed-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.gs1-detailed-field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.gs1-detailed-label {
    font-weight: 600;
    color: var(--secondary-color);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.gs1-detailed-value {
    font-size: 1.1rem;
    color: #333;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 4px solid var(--primary-color);
}

.gs1-detailed-image-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.gs1-detailed-image {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.gs1-detailed-image:hover {
    transform: scale(1.05);
}

.gs1-detailed-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.gs1-detailed-qr-section {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    border-radius: 12px;
    margin: 2rem 0;
}

.gs1-detailed-qr-code {
    display: inline-block;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.gs1-detailed-footer {
    text-align: center;
    padding: 2rem;
    color: var(--secondary-color);
    font-size: 0.9rem;
    border-top: 1px solid #e9ecef;
    margin-top: 3rem;
}

/* Accordion styles for detailed template */
.accordion-section .accordion-header {
    cursor: pointer;
    transition: all 0.3s ease;
}

.accordion-section .accordion-header:hover {
    opacity: 0.9;
}

.accordion-section .accordion-toggle {
    transition: transform 0.3s ease;
    font-size: 1rem;
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

@media (max-width: 768px) {
    .gs1-detailed-title {
        font-size: 2rem;
    }
    
    .gs1-detailed-grid {
        grid-template-columns: 1fr;
    }
    
    .gs1-detailed-content {
        padding: 0 0.5rem;
    }
    
    .gs1-detailed-section-body {
        padding: 1rem;
    }
}
</style>

<div class="gs1-detailed-template">
    <!-- Header -->
    <div class="gs1-detailed-header">
        <div class="container">
            <h1 class="gs1-detailed-title">
                <?= should_display_datapoint('product_name', $enabled_datapoints) ? get_display_value($data->product, 'product_name', $data->product->gtin) : $data->product->gtin ?>
            </h1>
            <p class="gs1-detailed-subtitle">
                <?= l('products.gs1.digital_passport') ?>
            </p>
        </div>
    </div>

    <div class="gs1-detailed-content">
        
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
        <?= section_wrapper_start('basic_info', $section_accordions, 'Basic Information', 'fas fa-info-circle') ?>
            <div class="gs1-detailed-grid">
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.gtin') ?></div>
                    <div class="gs1-detailed-value"><?= $data->product->gtin ?></div>
                </div>

                <?php if (should_display_datapoint('product_name', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.product_name') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'product_name') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('brand_name', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.brand_name') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'brand_name') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('manufacturer', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.manufacturer') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'manufacturer') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('category', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.category') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'category') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('subcategory', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.subcategory') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'subcategory') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('country_of_origin', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.country_of_origin') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'country_of_origin') ?></div>
                </div>
                <?php endif ?>
            </div>

            <?php if (should_display_datapoint('product_description', $enabled_datapoints)): ?>
            <div style="margin-top: 1.5rem;">
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.product_description') ?></div>
                    <div class="gs1-detailed-value"><?= nl2br(get_display_value($data->product, 'product_description')) ?></div>
                </div>
            </div>
            <?php endif ?>
        <?= section_wrapper_end('basic_info', $section_accordions) ?>
        <?php endif ?>

        <!-- Product Images Section -->
        <?php if (should_display_datapoint('product_images', $enabled_datapoints)): ?>
        <?= section_wrapper_start('images', $section_accordions, 'Product Images', 'fas fa-images') ?>
            <?php if (has_product_data($data->product, 'product_images')): ?>
            <div class="gs1-detailed-image-gallery">
                <?php foreach($data->product->product_images as $image): ?>
                <div class="gs1-detailed-image">
                    <img src="<?= $image ?>" alt="<?= $data->product->product_name ?>" loading="lazy">
                </div>
                <?php endforeach ?>
            </div>
            <?php else: ?>
            <div class="text-center text-muted" style="padding: 3rem;">
                <i class="fas fa-image fa-4x mb-3"></i>
                <p>No product images available</p>
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
        <?= section_wrapper_start('specifications', $section_accordions, 'Technical Specifications', 'fas fa-ruler-combined') ?>
            <div class="gs1-detailed-grid">
                <?php if (should_display_datapoint('net_weight', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.net_weight') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'net_weight') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('net_weight_kg', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.net_weight_kg') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'net_weight_kg') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('dimensions', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.dimensions') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'dimensions') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('length_m', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.length_m') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'length_m') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('width_m', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.width_m') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'width_m') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('height_m', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.height_m') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'height_m') ?></div>
                </div>
                <?php endif ?>
            </div>
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
        <?= section_wrapper_start('content_compliance', $section_accordions, 'Content & Compliance', 'fas fa-clipboard-list') ?>
            <div class="gs1-detailed-grid">
                <?php if (should_display_datapoint('ingredients', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.ingredients') ?></div>
                    <div class="gs1-detailed-value"><?= nl2br(get_display_value($data->product, 'ingredients')) ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('nutritional_info', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.nutritional_info') ?></div>
                    <div class="gs1-detailed-value"><?= nl2br(get_display_value($data->product, 'nutritional_info')) ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('allergen_info', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.allergen_info') ?></div>
                    <div class="gs1-detailed-value"><?= nl2br(get_display_value($data->product, 'allergen_info')) ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('certifications', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.certifications') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'certifications') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('organic_certification', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.organic_certification') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'organic_certification') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('fair_trade_certification', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.fair_trade_certification') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'fair_trade_certification') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('halal_certified', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.halal_certified') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'halal_certified') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('kosher_certified', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.kosher_certified') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'kosher_certified') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('gluten_free', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.gluten_free') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'gluten_free') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('vegan', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.vegan') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'vegan') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('vegetarian', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.vegetarian') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'vegetarian') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('non_gmo', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label"><?= l('products.non_gmo') ?></div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'non_gmo') ?></div>
                </div>
                <?php endif ?>
            </div>
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
        <?= section_wrapper_start('sustainability', $section_accordions, 'Sustainability Information', 'fas fa-leaf') ?>
            <div class="gs1-detailed-grid">
                <?php if (should_display_datapoint('carbon_footprint', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Carbon Footprint</div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'carbon_footprint') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('water_usage', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Water Usage</div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'water_usage') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('renewable_energy_percentage', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Renewable Energy</div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'renewable_energy_percentage') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('recyclability_score', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Recyclability Score</div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'recyclability_score') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('sustainability_certifications', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Sustainability Certifications</div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'sustainability_certifications') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('supply_chain_transparency', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Supply Chain Transparency</div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'supply_chain_transparency') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('ethical_sourcing', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Ethical Sourcing</div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'ethical_sourcing') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('key_suppliers', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Key Suppliers</div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'key_suppliers') ?></div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('blockchain_verified', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Blockchain Verified</div>
                    <div class="gs1-detailed-value"><?= get_display_value($data->product, 'blockchain_verified') ?></div>
                </div>
                <?php endif ?>
            </div>
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
        <?= section_wrapper_start('digital_links', $section_accordions, 'Digital Links', 'fas fa-link') ?>
            <div class="gs1-detailed-grid">
                <?php if (should_display_datapoint('product_url', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Product Website</div>
                    <div class="gs1-detailed-value">
                        <?php if (has_product_data($data->product, 'product_url')): ?>
                        <a href="<?= $data->product->product_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                            <?= $data->product->product_url ?>
                        </a>
                        <?php else: ?>
                        <?= get_display_value($data->product, 'product_url') ?>
                        <?php endif ?>
                    </div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('manufacturer_url', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Manufacturer Website</div>
                    <div class="gs1-detailed-value">
                        <?php if (has_product_data($data->product, 'manufacturer_url')): ?>
                        <a href="<?= $data->product->manufacturer_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                            <?= $data->product->manufacturer_url ?>
                        </a>
                        <?php else: ?>
                        <?= get_display_value($data->product, 'manufacturer_url') ?>
                        <?php endif ?>
                    </div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('purchase_url', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Purchase Link</div>
                    <div class="gs1-detailed-value">
                        <?php if (has_product_data($data->product, 'purchase_url')): ?>
                        <a href="<?= $data->product->purchase_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                            <?= $data->product->purchase_url ?>
                        </a>
                        <?php else: ?>
                        <?= get_display_value($data->product, 'purchase_url') ?>
                        <?php endif ?>
                    </div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('manual_url', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">User Manual</div>
                    <div class="gs1-detailed-value">
                        <?php if (has_product_data($data->product, 'manual_url')): ?>
                        <a href="<?= $data->product->manual_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                            <?= $data->product->manual_url ?>
                        </a>
                        <?php else: ?>
                        <?= get_display_value($data->product, 'manual_url') ?>
                        <?php endif ?>
                    </div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('support_url', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Support</div>
                    <div class="gs1-detailed-value">
                        <?php if (has_product_data($data->product, 'support_url')): ?>
                        <a href="<?= $data->product->support_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                            <?= $data->product->support_url ?>
                        </a>
                        <?php else: ?>
                        <?= get_display_value($data->product, 'support_url') ?>
                        <?php endif ?>
                    </div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('facebook_url', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Facebook</div>
                    <div class="gs1-detailed-value">
                        <?php if (has_product_data($data->product, 'facebook_url')): ?>
                        <a href="<?= $data->product->facebook_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                            <?= $data->product->facebook_url ?>
                        </a>
                        <?php else: ?>
                        <?= get_display_value($data->product, 'facebook_url') ?>
                        <?php endif ?>
                    </div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('instagram_url', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Instagram</div>
                    <div class="gs1-detailed-value">
                        <?php if (has_product_data($data->product, 'instagram_url')): ?>
                        <a href="<?= $data->product->instagram_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                            <?= $data->product->instagram_url ?>
                        </a>
                        <?php else: ?>
                        <?= get_display_value($data->product, 'instagram_url') ?>
                        <?php endif ?>
                    </div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('twitter_url', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">Twitter</div>
                    <div class="gs1-detailed-value">
                        <?php if (has_product_data($data->product, 'twitter_url')): ?>
                        <a href="<?= $data->product->twitter_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                            <?= $data->product->twitter_url ?>
                        </a>
                        <?php else: ?>
                        <?= get_display_value($data->product, 'twitter_url') ?>
                        <?php endif ?>
                    </div>
                </div>
                <?php endif ?>

                <?php if (should_display_datapoint('youtube_url', $enabled_datapoints)): ?>
                <div class="gs1-detailed-field">
                    <div class="gs1-detailed-label">YouTube</div>
                    <div class="gs1-detailed-value">
                        <?php if (has_product_data($data->product, 'youtube_url')): ?>
                        <a href="<?= $data->product->youtube_url ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                            <?= $data->product->youtube_url ?>
                        </a>
                        <?php else: ?>
                        <?= get_display_value($data->product, 'youtube_url') ?>
                        <?php endif ?>
                    </div>
                </div>
                <?php endif ?>
            </div>
        <?= section_wrapper_end('digital_links', $section_accordions) ?>
        <?php endif ?>


    </div>

    <!-- Footer -->
    <div class="gs1-detailed-footer">
        <p>
            <i class="fas fa-shield-alt"></i>
            Certified GS1 Digital Product Passport • 
            Last Updated: <?= date('M j, Y') ?>
        </p>
    </div>
</div>

<!-- Add accordion functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Accordion functionality for detailed template
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
