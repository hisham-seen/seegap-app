<?php defined('SEEGAP') || die() ?>

<?php
/* Get design configuration */
$template = $data->design_config->template ?? 'classic';
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

/* NEW: Generate section wrapper for classic template */
function section_wrapper_start($section_key, $section_accordions, $title) {
    $is_accordion = is_section_accordion($section_key, $section_accordions);
    $section_id = 'gs1_section_' . $section_key;
    
    if ($is_accordion) {
        return '
        <div class="gs1-classic-section accordion-section">
            <div class="accordion-header" data-toggle="collapse" data-target="#' . $section_id . '" aria-expanded="true" aria-controls="' . $section_id . '">
                <h2 class="gs1-classic-section-title">' . $title . ' <i class="fas fa-chevron-down accordion-toggle float-right"></i></h2>
            </div>
            <div class="collapse show" id="' . $section_id . '">
                <div class="gs1-classic-section-content">';
    } else {
        return '
        <div class="gs1-classic-section">
            <h2 class="gs1-classic-section-title">' . $title . '</h2>
            <div class="gs1-classic-section-content">';
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

.gs1-classic-template {
    font-family: Georgia, "Times New Roman", serif;
    line-height: 1.7;
    color: #2c3e50;
    background: #f8f9fa;
}

.gs1-classic-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.gs1-classic-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: white;
    border: 3px solid var(--primary-color);
    border-radius: 0;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.gs1-classic-title {
    font-size: 2.8rem;
    font-weight: 400;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.gs1-classic-subtitle {
    font-size: 1.1rem;
    color: var(--secondary-color);
    font-style: italic;
    margin-bottom: 1rem;
}

.gs1-classic-divider {
    width: 100px;
    height: 3px;
    background: var(--primary-color);
    margin: 1rem auto;
}

.gs1-classic-section {
    background: white;
    margin-bottom: 2rem;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.gs1-classic-section-title {
    background: var(--primary-color);
    color: white;
    padding: 1rem 2rem;
    font-size: 1.4rem;
    font-weight: 400;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0;
    border-bottom: 3px solid var(--accent-color);
}

.gs1-classic-section-content {
    padding: 2rem;
}

.gs1-classic-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

.gs1-classic-table th {
    background: #f8f9fa;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--secondary-color);
    border-bottom: 2px solid var(--primary-color);
    width: 30%;
    text-transform: uppercase;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

.gs1-classic-table td {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
    font-size: 1.1rem;
    color: #2c3e50;
}

.gs1-classic-table tr:hover {
    background: #f8f9fa;
}

.gs1-classic-image-section {
    text-align: center;
    padding: 2rem;
}

.gs1-classic-image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.gs1-classic-image-frame {
    border: 5px solid var(--primary-color);
    padding: 10px;
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.gs1-classic-image-frame:hover {
    transform: translateY(-5px);
}

.gs1-classic-image-frame img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}

.gs1-classic-qr-section {
    text-align: center;
    padding: 3rem 2rem;
    background: linear-gradient(45deg, #f8f9fa, white);
    border-top: 3px solid var(--primary-color);
    border-bottom: 3px solid var(--primary-color);
    margin: 2rem 0;
}

.gs1-classic-qr-title {
    font-size: 1.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.gs1-classic-qr-frame {
    display: inline-block;
    padding: 1.5rem;
    background: white;
    border: 3px solid var(--primary-color);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.gs1-classic-footer {
    text-align: center;
    padding: 2rem;
    background: var(--primary-color);
    color: white;
    margin-top: 3rem;
    font-style: italic;
}

.gs1-classic-badge {
    display: inline-block;
    background: var(--accent-color);
    color: white;
    padding: 0.3rem 0.8rem;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 0.5rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .gs1-classic-title {
        font-size: 2rem;
    }
    
    .gs1-classic-container {
        padding: 1rem 0.5rem;
    }
    
    .gs1-classic-section-content {
        padding: 1rem;
    }
    
    .gs1-classic-table th,
    .gs1-classic-table td {
        padding: 0.75rem;
        font-size: 0.9rem;
    }
    
    .gs1-classic-image-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="gs1-classic-template">
    <div class="gs1-classic-container">
        
        <!-- Header -->
        <div class="gs1-classic-header">
            <h1 class="gs1-classic-title">
                <?= $data->product->product_name ?: $data->product->gtin ?>
            </h1>
            <div class="gs1-classic-divider"></div>
            <p class="gs1-classic-subtitle">
                <?= l('products.gs1.digital_passport') ?>
            </p>
            <?php if (has_product_data($data->product, 'gtin')): ?>
            <span class="gs1-classic-badge">GTIN: <?= $data->product->gtin ?></span>
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
        <?= section_wrapper_start('basic_info', $section_accordions, 'Product Information') ?>
                <table class="gs1-classic-table">
                    <?php if (should_display_datapoint('product_name', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.product_name') ?></th>
                        <td><?= get_display_value($data->product, 'product_name') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('brand_name', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.brand_name') ?></th>
                        <td><?= get_display_value($data->product, 'brand_name') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('manufacturer', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.manufacturer') ?></th>
                        <td><?= get_display_value($data->product, 'manufacturer') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('category', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.category') ?></th>
                        <td><?= get_display_value($data->product, 'category') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('subcategory', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.subcategory') ?></th>
                        <td><?= get_display_value($data->product, 'subcategory') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('product_description', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.product_description') ?></th>
                        <td><?= nl2br(get_display_value($data->product, 'product_description')) ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('country_of_origin', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.country_of_origin') ?></th>
                        <td><?= get_display_value($data->product, 'country_of_origin') ?></td>
                    </tr>
                    <?php endif ?>
                </table>
        <?= section_wrapper_end('basic_info', $section_accordions) ?>
        <?php endif ?>

        <!-- Product Images Section -->
        <?php if (should_display_datapoint('product_images', $enabled_datapoints)): ?>
        <?= section_wrapper_start('images', $section_accordions, 'Product Gallery') ?>
            <div class="gs1-classic-image-section">
                <?php if (has_product_data($data->product, 'product_images')): ?>
                <div class="gs1-classic-image-grid">
                    <?php foreach($data->product->product_images as $image): ?>
                    <div class="gs1-classic-image-frame">
                        <img src="<?= $image ?>" alt="<?= $data->product->product_name ?>" loading="lazy">
                    </div>
                    <?php endforeach ?>
                </div>
                <?php else: ?>
                <div class="text-center text-muted" style="padding: 3rem;">
                    <i class="fas fa-image fa-3x mb-3"></i>
                    <p>No product images available</p>
                </div>
                <?php endif ?>
            </div>
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
        <?= section_wrapper_start('specifications', $section_accordions, 'Technical Specifications') ?>
                <table class="gs1-classic-table">
                    <?php if (should_display_datapoint('net_weight', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.net_weight') ?></th>
                        <td><?= get_display_value($data->product, 'net_weight') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('net_weight_kg', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.net_weight_kg') ?></th>
                        <td><?= get_display_value($data->product, 'net_weight_kg') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('dimensions', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.dimensions') ?></th>
                        <td><?= get_display_value($data->product, 'dimensions') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('length_m', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.length_m') ?></th>
                        <td><?= get_display_value($data->product, 'length_m') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('width_m', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.width_m') ?></th>
                        <td><?= get_display_value($data->product, 'width_m') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('height_m', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.height_m') ?></th>
                        <td><?= get_display_value($data->product, 'height_m') ?></td>
                    </tr>
                    <?php endif ?>
                </table>
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
                <table class="gs1-classic-table">
                    <?php if (should_display_datapoint('ingredients', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.ingredients') ?></th>
                        <td><?= nl2br(get_display_value($data->product, 'ingredients')) ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('nutritional_info', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.nutritional_info') ?></th>
                        <td><?= nl2br(get_display_value($data->product, 'nutritional_info')) ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('allergen_info', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.allergen_info') ?></th>
                        <td><?= nl2br(get_display_value($data->product, 'allergen_info')) ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('certifications', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.certifications') ?></th>
                        <td><?= get_display_value($data->product, 'certifications') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('organic_certification', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.organic_certification') ?></th>
                        <td><?= get_display_value($data->product, 'organic_certification') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('fair_trade_certification', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.fair_trade_certification') ?></th>
                        <td><?= get_display_value($data->product, 'fair_trade_certification') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('halal_certified', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.halal_certified') ?></th>
                        <td><?= get_display_value($data->product, 'halal_certified') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('kosher_certified', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.kosher_certified') ?></th>
                        <td><?= get_display_value($data->product, 'kosher_certified') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('gluten_free', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.gluten_free') ?></th>
                        <td><?= get_display_value($data->product, 'gluten_free') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('vegan', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.vegan') ?></th>
                        <td><?= get_display_value($data->product, 'vegan') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('vegetarian', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.vegetarian') ?></th>
                        <td><?= get_display_value($data->product, 'vegetarian') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('non_gmo', $enabled_datapoints)): ?>
                    <tr>
                        <th><?= l('products.non_gmo') ?></th>
                        <td><?= get_display_value($data->product, 'non_gmo') ?></td>
                    </tr>
                    <?php endif ?>
                </table>
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
        <?= section_wrapper_start('sustainability', $section_accordions, 'Sustainability Information') ?>
                <table class="gs1-classic-table">
                    <?php if (should_display_datapoint('carbon_footprint', $enabled_datapoints)): ?>
                    <tr>
                        <th>Carbon Footprint</th>
                        <td><?= get_display_value($data->product, 'carbon_footprint') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('water_usage', $enabled_datapoints)): ?>
                    <tr>
                        <th>Water Usage</th>
                        <td><?= get_display_value($data->product, 'water_usage') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('renewable_energy_percentage', $enabled_datapoints)): ?>
                    <tr>
                        <th>Renewable Energy</th>
                        <td><?= get_display_value($data->product, 'renewable_energy_percentage') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('recyclability_score', $enabled_datapoints)): ?>
                    <tr>
                        <th>Recyclability Score</th>
                        <td><?= get_display_value($data->product, 'recyclability_score') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('sustainability_certifications', $enabled_datapoints)): ?>
                    <tr>
                        <th>Sustainability Certifications</th>
                        <td><?= get_display_value($data->product, 'sustainability_certifications') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('supply_chain_transparency', $enabled_datapoints)): ?>
                    <tr>
                        <th>Supply Chain Transparency</th>
                        <td><?= get_display_value($data->product, 'supply_chain_transparency') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('ethical_sourcing', $enabled_datapoints)): ?>
                    <tr>
                        <th>Ethical Sourcing</th>
                        <td><?= get_display_value($data->product, 'ethical_sourcing') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('key_suppliers', $enabled_datapoints)): ?>
                    <tr>
                        <th>Key Suppliers</th>
                        <td><?= get_display_value($data->product, 'key_suppliers') ?></td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('blockchain_verified', $enabled_datapoints)): ?>
                    <tr>
                        <th>Blockchain Verified</th>
                        <td><?= get_display_value($data->product, 'blockchain_verified') ?></td>
                    </tr>
                    <?php endif ?>
                </table>
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
                <table class="gs1-classic-table">
                    <?php if (should_display_datapoint('product_url', $enabled_datapoints)): ?>
                    <tr>
                        <th>Product Website</th>
                        <td>
                            <?php if (has_product_data($data->product, 'product_url')): ?>
                            <a href="<?= $data->product->product_url ?>" target="_blank" style="color: var(--primary-color);">
                                <?= $data->product->product_url ?>
                            </a>
                            <?php else: ?>
                            <?= get_display_value($data->product, 'product_url') ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('manufacturer_url', $enabled_datapoints)): ?>
                    <tr>
                        <th>Manufacturer Website</th>
                        <td>
                            <?php if (has_product_data($data->product, 'manufacturer_url')): ?>
                            <a href="<?= $data->product->manufacturer_url ?>" target="_blank" style="color: var(--primary-color);">
                                <?= $data->product->manufacturer_url ?>
                            </a>
                            <?php else: ?>
                            <?= get_display_value($data->product, 'manufacturer_url') ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('purchase_url', $enabled_datapoints)): ?>
                    <tr>
                        <th>Purchase Link</th>
                        <td>
                            <?php if (has_product_data($data->product, 'purchase_url')): ?>
                            <a href="<?= $data->product->purchase_url ?>" target="_blank" style="color: var(--primary-color);">
                                <?= $data->product->purchase_url ?>
                            </a>
                            <?php else: ?>
                            <?= get_display_value($data->product, 'purchase_url') ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('manual_url', $enabled_datapoints)): ?>
                    <tr>
                        <th>User Manual</th>
                        <td>
                            <?php if (has_product_data($data->product, 'manual_url')): ?>
                            <a href="<?= $data->product->manual_url ?>" target="_blank" style="color: var(--primary-color);">
                                <?= $data->product->manual_url ?>
                            </a>
                            <?php else: ?>
                            <?= get_display_value($data->product, 'manual_url') ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('support_url', $enabled_datapoints)): ?>
                    <tr>
                        <th>Support</th>
                        <td>
                            <?php if (has_product_data($data->product, 'support_url')): ?>
                            <a href="<?= $data->product->support_url ?>" target="_blank" style="color: var(--primary-color);">
                                <?= $data->product->support_url ?>
                            </a>
                            <?php else: ?>
                            <?= get_display_value($data->product, 'support_url') ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('facebook_url', $enabled_datapoints)): ?>
                    <tr>
                        <th>Facebook</th>
                        <td>
                            <?php if (has_product_data($data->product, 'facebook_url')): ?>
                            <a href="<?= $data->product->facebook_url ?>" target="_blank" style="color: var(--primary-color);">
                                <?= $data->product->facebook_url ?>
                            </a>
                            <?php else: ?>
                            <?= get_display_value($data->product, 'facebook_url') ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('instagram_url', $enabled_datapoints)): ?>
                    <tr>
                        <th>Instagram</th>
                        <td>
                            <?php if (has_product_data($data->product, 'instagram_url')): ?>
                            <a href="<?= $data->product->instagram_url ?>" target="_blank" style="color: var(--primary-color);">
                                <?= $data->product->instagram_url ?>
                            </a>
                            <?php else: ?>
                            <?= get_display_value($data->product, 'instagram_url') ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('twitter_url', $enabled_datapoints)): ?>
                    <tr>
                        <th>Twitter</th>
                        <td>
                            <?php if (has_product_data($data->product, 'twitter_url')): ?>
                            <a href="<?= $data->product->twitter_url ?>" target="_blank" style="color: var(--primary-color);">
                                <?= $data->product->twitter_url ?>
                            </a>
                            <?php else: ?>
                            <?= get_display_value($data->product, 'twitter_url') ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endif ?>

                    <?php if (should_display_datapoint('youtube_url', $enabled_datapoints)): ?>
                    <tr>
                        <th>YouTube</th>
                        <td>
                            <?php if (has_product_data($data->product, 'youtube_url')): ?>
                            <a href="<?= $data->product->youtube_url ?>" target="_blank" style="color: var(--primary-color);">
                                <?= $data->product->youtube_url ?>
                            </a>
                            <?php else: ?>
                            <?= get_display_value($data->product, 'youtube_url') ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endif ?>
                </table>
        <?= section_wrapper_end('digital_links', $section_accordions) ?>
        <?php endif ?>


        <!-- Footer -->
        <div class="gs1-classic-footer">
            <p>
                Certified GS1 Digital Product Passport<br>
                Last Updated: <?= date('F j, Y') ?>
            </p>
        </div>

    </div>
</div>

<!-- Add accordion functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Accordion functionality for classic template
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

<style>
/* Accordion styles for classic template */
.accordion-section .accordion-header {
    cursor: pointer;
    transition: all 0.3s ease;
}

.accordion-section .accordion-header:hover {
    opacity: 0.8;
}

.accordion-section .accordion-toggle {
    transition: transform 0.3s ease;
    font-size: 1rem;
    margin-top: 0.2rem;
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
</style>
