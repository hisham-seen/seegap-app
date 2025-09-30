<?php defined('SEEGAP') || die() ?>

<?php
/* Get design configuration */
$template = $data->design_config->template ?? 'modern';
$theme = $data->design_config->theme ?? 'blue';
$enabled_datapoints = $data->design_config->enabled_datapoints ?? [];
$section_accordions = $data->design_config->section_accordions ?? [];

/* Theme color mapping */
$theme_colors = [
    'blue' => ['primary' => '#007bff', 'secondary' => '#6c757d', 'accent' => '#17a2b8'],
    'green' => ['primary' => '#28a745', 'secondary' => '#6c757d', 'accent' => '#20c997'],
    'purple' => ['primary' => '#6f42c1', 'secondary' => '#6c757d', 'accent' => '#e83e8c'],
    'orange' => ['primary' => '#fd7e14', 'secondary' => '#6c757d', 'accent' => '#ffc107'],
    'red' => ['primary' => '#dc3545', 'secondary' => '#6c757d', 'accent' => '#fd7e14'],
    'gray' => ['primary' => '#6c757d', 'secondary' => '#495057', 'accent' => '#17a2b8']
];

$colors = $theme_colors[$theme] ?? $theme_colors['blue'];

/* Helper function to check if datapoint is enabled */
function is_datapoint_enabled($datapoint, $enabled_datapoints) {
    return in_array($datapoint, $enabled_datapoints);
}

/* Helper function to check if product has data for a datapoint */
function has_product_data($product, $datapoint) {
    switch($datapoint) {
        case 'product_name':
            return !empty($product->product_name);
        case 'brand_name':
            return !empty($product->brand_name);
        case 'product_description':
            return !empty($product->product_description);
        case 'product_images':
            return !empty($product->product_images) && is_array($product->product_images) && count($product->product_images) > 0;
        case 'category':
            return !empty($product->category);
        case 'subcategory':
            return !empty($product->subcategory);
        case 'manufacturer':
            return !empty($product->manufacturer);
        case 'country_of_origin':
            return !empty($product->country_of_origin);
        case 'net_weight':
            return !empty($product->net_weight);
        case 'dimensions':
            return !empty($product->dimensions);
        case 'net_weight_kg':
            return !empty($product->net_weight_kg);
        case 'length_m':
            return !empty($product->length_m);
        case 'width_m':
            return !empty($product->width_m);
        case 'height_m':
            return !empty($product->height_m);
        case 'ingredients':
            return !empty($product->ingredients);
        case 'nutritional_info':
            return !empty($product->nutritional_info);
        case 'allergen_info':
            return !empty($product->allergen_info);
        case 'certifications':
            return !empty($product->certifications);
        case 'organic_certification':
            return !empty($product->organic_certification);
        case 'fair_trade_certification':
            return !empty($product->fair_trade_certification);
        case 'halal_certified':
            return !empty($product->halal_certified);
        case 'kosher_certified':
            return !empty($product->kosher_certified);
        case 'gluten_free':
            return !empty($product->gluten_free);
        case 'vegan':
            return !empty($product->vegan);
        case 'vegetarian':
            return !empty($product->vegetarian);
        case 'non_gmo':
            return !empty($product->non_gmo);
        case 'carbon_footprint':
            return !empty($product->carbon_footprint);
        case 'water_usage':
            return !empty($product->water_usage);
        case 'renewable_energy_percentage':
            return !empty($product->renewable_energy_percentage);
        case 'recyclability_score':
            return !empty($product->recyclability_score);
        case 'sustainability_certifications':
            return !empty($product->sustainability_certifications);
        case 'supply_chain_transparency':
            return !empty($product->supply_chain_transparency);
        case 'ethical_sourcing':
            return !empty($product->ethical_sourcing);
        case 'key_suppliers':
            return !empty($product->key_suppliers);
        case 'blockchain_verified':
            return !empty($product->blockchain_verified);
        case 'product_url':
            return !empty($product->product_url);
        case 'manufacturer_url':
            return !empty($product->manufacturer_url);
        case 'purchase_url':
            return !empty($product->purchase_url);
        case 'manual_url':
            return !empty($product->manual_url);
        case 'support_url':
            return !empty($product->support_url);
        case 'facebook_url':
            return !empty($product->facebook_url);
        case 'instagram_url':
            return !empty($product->instagram_url);
        case 'twitter_url':
            return !empty($product->twitter_url);
        case 'youtube_url':
            return !empty($product->youtube_url);
        default:
            return false;
    }
}

/* NEW: Fixed function - show datapoint if enabled, regardless of data */
function should_display_datapoint($datapoint, $enabled_datapoints) {
    // If no datapoints are specified, don't show anything
    if (empty($enabled_datapoints)) {
        return false;
    }
    return in_array($datapoint, $enabled_datapoints);
}

/* NEW: Get display value with placeholder for empty fields */
function get_display_value($product, $datapoint, $placeholder = 'Not specified') {
    if (has_product_data($product, $datapoint)) {
        switch($datapoint) {
            case 'product_images':
                return $product->product_images;
            default:
                return $product->$datapoint;
        }
    }
    return '<span class="text-muted font-italic">' . $placeholder . '</span>';
}

/* NEW: Check if section should be accordion */
function is_section_accordion($section_key, $section_accordions) {
    return in_array($section_key, $section_accordions);
}

/* NEW: Generate section wrapper */
function section_wrapper_start($section_key, $section_accordions, $title, $icon = 'fas fa-info-circle') {
    $is_accordion = is_section_accordion($section_key, $section_accordions);
    $section_id = 'gs1_section_' . $section_key;
    
    if ($is_accordion) {
        return '
        <div class="modern-card accordion-section">
            <div class="accordion-header" data-toggle="collapse" data-target="#' . $section_id . '" aria-expanded="true" aria-controls="' . $section_id . '">
                <h5><i class="' . $icon . '"></i>' . $title . ' <i class="fas fa-chevron-down accordion-toggle float-right"></i></h5>
            </div>
            <div class="collapse show" id="' . $section_id . '">
                <div class="accordion-body">';
    } else {
        return '
        <div class="modern-card">
            <h5><i class="' . $icon . '"></i>' . $title . '</h5>';
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
        </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="<?= \SeeGap\Language::$code ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO Meta Tags -->
    <title><?= $data->product->product_name ?? $data->product->gtin ?> - <?= l('products.gs1.digital_passport') ?></title>
    <meta name="description" content="<?= $data->product->product_description ?? l('products.gs1.product_information_for') . ' ' . $data->product->gtin ?>">
    <meta name="keywords" content="<?= $data->product->product_name ?>, <?= $data->product->brand_name ?>, <?= $data->product->gtin ?>, GS1, Digital Link, Product Information">
    <meta name="author" content="<?= $data->product->brand_name ?? $data->product->manufacturer ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= $data->product->product_name ?? $data->product->gtin ?>">
    <meta property="og:description" content="<?= $data->product->product_description ?? l('products.gs1.product_information_for') . ' ' . $data->product->gtin ?>">
    <meta property="og:type" content="product">
    <meta property="og:url" content="<?= url('01/' . $data->product->gtin) ?>">
    <?php if(!empty($data->product->product_images) && is_array($data->product->product_images) && count($data->product->product_images) > 0): ?>
    <meta property="og:image" content="<?= $data->product->product_images[0] ?>">
    <?php endif ?>
    
    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "<?= addslashes($data->product->product_name ?? $data->product->gtin) ?>",
        "description": "<?= addslashes($data->product->product_description ?? '') ?>",
        "gtin": "<?= $data->product->gtin ?>",
        "brand": {
            "@type": "Brand",
            "name": "<?= addslashes($data->product->brand_name ?? '') ?>"
        },
        "manufacturer": {
            "@type": "Organization",
            "name": "<?= addslashes($data->product->manufacturer ?? '') ?>"
        },
        <?php if(!empty($data->product->product_images) && is_array($data->product->product_images) && count($data->product->product_images) > 0): ?>
        "image": [
            <?php foreach($data->product->product_images as $index => $image): ?>
            "<?= $image ?>"<?= $index < count($data->product->product_images) - 1 ? ',' : '' ?>
            <?php endforeach ?>
        ],
        <?php endif ?>
        "url": "<?= url('01/' . $data->product->gtin) ?>"
    }
    </script>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= url('themes/phoenix/assets/images/favicon.ico') ?>" type="image/x-icon">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: <?= $colors['primary'] ?>;
            --secondary-color: <?= $colors['secondary'] ?>;
            --accent-color: <?= $colors['accent'] ?>;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .modern-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .modern-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="90" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .modern-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .modern-card h5 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        
        .modern-card h5 i {
            margin-right: 0.75rem;
            font-size: 1.2em;
        }
        
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .modern-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.875rem;
            margin: 0.25rem;
            display: inline-block;
            font-weight: 500;
        }
        
        .qr-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: sticky;
            top: 2rem;
        }
        
        .modern-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: none;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        .modern-btn-outline {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .modern-btn-outline:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .sustainability-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            color: white;
            margin: 0 auto 1rem;
        }
        
        .verification-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 500;
            margin: 0.5rem;
        }
        
        .verified { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
        .unverified { background: linear-gradient(135deg, #dc3545, #fd7e14); color: white; }
        .self-verified { background: linear-gradient(135deg, #ffc107, #fd7e14); color: white; }
        .third-party-verified { background: linear-gradient(135deg, #007bff, #17a2b8); color: white; }
        .certified { background: linear-gradient(135deg, #6f42c1, #e83e8c); color: white; }
        
        .footer-modern {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 3rem 0;
            margin-top: 4rem;
        }
        
        .datapoint-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .datapoint-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }
        
        .datapoint-item strong {
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.5rem;
        }
        
        /* Accordion Styles */
        .accordion-section .accordion-header {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .accordion-section .accordion-header:hover {
            background: #f8f9fa;
            border-radius: 15px;
            margin: -0.5rem;
            padding: 0.5rem;
        }
        
        .accordion-section .accordion-toggle {
            transition: transform 0.3s ease;
        }
        
        .accordion-section .accordion-header[aria-expanded="false"] .accordion-toggle {
            transform: rotate(-90deg);
        }
        
        .accordion-body {
            padding-top: 1rem;
        }
    </style>
</head>
<body>
    <!-- Modern Header -->
    <div class="modern-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <?php if(should_display_datapoint('product_name', $enabled_datapoints)): ?>
                    <h1 class="display-3 mb-3 fw-bold"><?= get_display_value($data->product, 'product_name', $data->product->gtin) ?></h1>
                    <?php endif ?>
                    
                    <?php if(should_display_datapoint('brand_name', $enabled_datapoints)): ?>
                    <h2 class="h3 mb-3 opacity-75"><?= get_display_value($data->product, 'brand_name', 'Brand not specified') ?></h2>
                    <?php endif ?>
                    
                    <?php if(should_display_datapoint('product_description', $enabled_datapoints)): ?>
                    <p class="lead mb-4"><?= get_display_value($data->product, 'product_description', 'Product description not available') ?></p>
                    <?php endif ?>
                    
                    <div class="d-flex flex-wrap">
                        <span class="modern-badge me-2 mb-2">
                            <i class="fas fa-barcode me-1"></i>
                            GTIN: <?= $data->product->gtin ?>
                        </span>
                        <?php if(should_display_datapoint('category', $enabled_datapoints)): ?>
                        <span class="modern-badge me-2 mb-2">
                            <i class="fas fa-tag me-1"></i>
                            <?= get_display_value($data->product, 'category', 'Uncategorized') ?>
                        </span>
                        <?php endif ?>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <?php if(should_display_datapoint('product_images', $enabled_datapoints)): ?>
                        <?php if(has_product_data($data->product, 'product_images')): ?>
                        <img src="<?= $data->product->product_images[0] ?>" alt="<?= $data->product->product_name ?>" class="product-image">
                        <?php else: ?>
                        <div class="bg-white bg-opacity-25 rounded d-flex align-items-center justify-content-center" style="height: 250px;">
                            <div class="text-center">
                                <i class="fas fa-image fa-4x opacity-50 mb-2"></i>
                                <div class="small text-muted">No image available</div>
                            </div>
                        </div>
                        <?php endif ?>
                    <?php else: ?>
                    <div class="bg-white bg-opacity-25 rounded d-flex align-items-center justify-content-center" style="height: 250px;">
                        <i class="fas fa-image fa-4x opacity-50"></i>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                
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
                    <div class="datapoint-grid">
                        <?php if(should_display_datapoint('product_name', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.product_name') ?></strong>
                            <?= get_display_value($data->product, 'product_name') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('brand_name', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.brand_name') ?></strong>
                            <?= get_display_value($data->product, 'brand_name') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('manufacturer', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.manufacturer') ?></strong>
                            <?= get_display_value($data->product, 'manufacturer') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('category', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.category') ?></strong>
                            <?= get_display_value($data->product, 'category') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('subcategory', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.subcategory') ?></strong>
                            <?= get_display_value($data->product, 'subcategory') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('country_of_origin', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.country_of_origin') ?></strong>
                            <?= get_display_value($data->product, 'country_of_origin') ?>
                        </div>
                        <?php endif ?>
                    </div>
                    
                    <?php if(should_display_datapoint('product_description', $enabled_datapoints)): ?>
                    <div class="mt-3">
                        <strong><?= l('products.product_description') ?>:</strong><br>
                        <?= get_display_value($data->product, 'product_description') ?>
                    </div>
                    <?php endif ?>
                <?= section_wrapper_end('basic_info', $section_accordions) ?>
                <?php endif ?>

                <!-- Specifications Section -->
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
                <?= section_wrapper_start('specifications', $section_accordions, 'Technical Specifications', 'fas fa-cogs') ?>
                    <div class="datapoint-grid">
                        <?php if(should_display_datapoint('net_weight', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.net_weight') ?></strong>
                            <?= get_display_value($data->product, 'net_weight') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('net_weight_kg', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.net_weight_kg') ?></strong>
                            <?= get_display_value($data->product, 'net_weight_kg') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('dimensions', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.dimensions') ?></strong>
                            <?= get_display_value($data->product, 'dimensions') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('length_m', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.length_m') ?></strong>
                            <?= get_display_value($data->product, 'length_m') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('width_m', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.width_m') ?></strong>
                            <?= get_display_value($data->product, 'width_m') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('height_m', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.height_m') ?></strong>
                            <?= get_display_value($data->product, 'height_m') ?>
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
                <?= section_wrapper_start('content_compliance', $section_accordions, 'Content & Compliance', 'fas fa-certificate') ?>
                    <?php if(should_display_datapoint('ingredients', $enabled_datapoints)): ?>
                    <div class="mb-3">
                        <strong><?= l('products.ingredients') ?>:</strong><br>
                        <?= get_display_value($data->product, 'ingredients') ?>
                    </div>
                    <?php endif ?>
                    
                    <?php if(should_display_datapoint('nutritional_info', $enabled_datapoints)): ?>
                    <div class="mb-3">
                        <strong><?= l('products.nutritional_info') ?>:</strong><br>
                        <?= get_display_value($data->product, 'nutritional_info') ?>
                    </div>
                    <?php endif ?>
                    
                    <?php if(should_display_datapoint('allergen_info', $enabled_datapoints)): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong><?= l('products.allergen_info') ?>:</strong><br>
                        <?= get_display_value($data->product, 'allergen_info') ?>
                    </div>
                    <?php endif ?>
                    
                    <div class="datapoint-grid">
                        <?php if(should_display_datapoint('certifications', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.certifications') ?></strong>
                            <?= get_display_value($data->product, 'certifications') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('organic_certification', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.organic_certification') ?></strong>
                            <?= get_display_value($data->product, 'organic_certification') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('fair_trade_certification', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.fair_trade_certification') ?></strong>
                            <?= get_display_value($data->product, 'fair_trade_certification') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('halal_certified', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.halal_certified') ?></strong>
                            <?= get_display_value($data->product, 'halal_certified') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('kosher_certified', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.kosher_certified') ?></strong>
                            <?= get_display_value($data->product, 'kosher_certified') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('gluten_free', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.gluten_free') ?></strong>
                            <?= get_display_value($data->product, 'gluten_free') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('vegan', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.vegan') ?></strong>
                            <?= get_display_value($data->product, 'vegan') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('vegetarian', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.vegetarian') ?></strong>
                            <?= get_display_value($data->product, 'vegetarian') ?>
                        </div>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('non_gmo', $enabled_datapoints)): ?>
                        <div class="datapoint-item">
                            <strong><?= l('products.non_gmo') ?></strong>
                            <?= get_display_value($data->product, 'non_gmo') ?>
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
                    <div class="row align-items-center">
                        <?php if(should_display_datapoint('recyclability_score', $enabled_datapoints)): ?>
                        <div class="col-md-3 text-center">
                            <div class="sustainability-circle" style="background: linear-gradient(135deg, var(--primary-color), var(--accent-color));">
                                <?= has_product_data($data->product, 'recyclability_score') ? $data->product->recyclability_score : '?' ?>
                            </div>
                            <small class="text-muted">Recyclability Score</small>
                        </div>
                        <?php endif ?>
                        <div class="<?= should_display_datapoint('recyclability_score', $enabled_datapoints) ? 'col-md-9' : 'col-12' ?>">
                            <div class="datapoint-grid">
                                <?php if(should_display_datapoint('carbon_footprint', $enabled_datapoints)): ?>
                                <div class="datapoint-item">
                                    <strong><i class="fas fa-smog me-1"></i>Carbon Footprint</strong>
                                    <?= get_display_value($data->product, 'carbon_footprint') ?>
                                </div>
                                <?php endif ?>
                                
                                <?php if(should_display_datapoint('water_usage', $enabled_datapoints)): ?>
                                <div class="datapoint-item">
                                    <strong><i class="fas fa-tint me-1"></i>Water Usage</strong>
                                    <?= get_display_value($data->product, 'water_usage') ?>
                                </div>
                                <?php endif ?>
                                
                                <?php if(should_display_datapoint('renewable_energy_percentage', $enabled_datapoints)): ?>
                                <div class="datapoint-item">
                                    <strong><i class="fas fa-solar-panel me-1"></i>Renewable Energy</strong>
                                    <?= get_display_value($data->product, 'renewable_energy_percentage') ?>
                                </div>
                                <?php endif ?>
                                
                                <?php if(should_display_datapoint('sustainability_certifications', $enabled_datapoints)): ?>
                                <div class="datapoint-item">
                                    <strong><i class="fas fa-award me-1"></i>Sustainability Certifications</strong>
                                    <?= get_display_value($data->product, 'sustainability_certifications') ?>
                                </div>
                                <?php endif ?>
                                
                                <?php if(should_display_datapoint('supply_chain_transparency', $enabled_datapoints)): ?>
                                <div class="datapoint-item">
                                    <strong><i class="fas fa-link me-1"></i>Supply Chain Transparency</strong>
                                    <?= get_display_value($data->product, 'supply_chain_transparency') ?>
                                </div>
                                <?php endif ?>
                                
                                <?php if(should_display_datapoint('ethical_sourcing', $enabled_datapoints)): ?>
                                <div class="datapoint-item">
                                    <strong><i class="fas fa-handshake me-1"></i>Ethical Sourcing</strong>
                                    <?= get_display_value($data->product, 'ethical_sourcing') ?>
                                </div>
                                <?php endif ?>
                                
                                <?php if(should_display_datapoint('key_suppliers', $enabled_datapoints)): ?>
                                <div class="datapoint-item">
                                    <strong><i class="fas fa-industry me-1"></i>Key Suppliers</strong>
                                    <?= get_display_value($data->product, 'key_suppliers') ?>
                                </div>
                                <?php endif ?>
                                
                                <?php if(should_display_datapoint('blockchain_verified', $enabled_datapoints)): ?>
                                <div class="datapoint-item">
                                    <strong><i class="fas fa-shield-alt me-1"></i>Blockchain Verified</strong>
                                    <?= get_display_value($data->product, 'blockchain_verified') ?>
                                </div>
                                <?php endif ?>
                            </div>
                        </div>
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
                    <div class="d-grid gap-2">
                        <?php if(should_display_datapoint('product_url', $enabled_datapoints)): ?>
                        <?php if(has_product_data($data->product, 'product_url')): ?>
                        <a href="<?= $data->product->product_url ?>" target="_blank" class="modern-btn-outline btn">
                            <i class="fas fa-globe me-1"></i>
                            Product Website
                        </a>
                        <?php else: ?>
                        <div class="modern-btn-outline btn disabled">
                            <i class="fas fa-globe me-1"></i>
                            Product Website <span class="text-muted">(Not available)</span>
                        </div>
                        <?php endif ?>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('manufacturer_url', $enabled_datapoints)): ?>
                        <?php if(has_product_data($data->product, 'manufacturer_url')): ?>
                        <a href="<?= $data->product->manufacturer_url ?>" target="_blank" class="modern-btn-outline btn">
                            <i class="fas fa-industry me-1"></i>
                            Manufacturer Website
                        </a>
                        <?php else: ?>
                        <div class="modern-btn-outline btn disabled">
                            <i class="fas fa-industry me-1"></i>
                            Manufacturer Website <span class="text-muted">(Not available)</span>
                        </div>
                        <?php endif ?>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('purchase_url', $enabled_datapoints)): ?>
                        <?php if(has_product_data($data->product, 'purchase_url')): ?>
                        <a href="<?= $data->product->purchase_url ?>" target="_blank" class="modern-btn">
                            <i class="fas fa-shopping-cart me-1"></i>
                            Buy Now
                        </a>
                        <?php else: ?>
                        <div class="modern-btn disabled">
                            <i class="fas fa-shopping-cart me-1"></i>
                            Buy Now <span class="text-muted">(Not available)</span>
                        </div>
                        <?php endif ?>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('manual_url', $enabled_datapoints)): ?>
                        <?php if(has_product_data($data->product, 'manual_url')): ?>
                        <a href="<?= $data->product->manual_url ?>" target="_blank" class="modern-btn-outline btn">
                            <i class="fas fa-book me-1"></i>
                            User Manual
                        </a>
                        <?php else: ?>
                        <div class="modern-btn-outline btn disabled">
                            <i class="fas fa-book me-1"></i>
                            User Manual <span class="text-muted">(Not available)</span>
                        </div>
                        <?php endif ?>
                        <?php endif ?>
                        
                        <?php if(should_display_datapoint('support_url', $enabled_datapoints)): ?>
                        <?php if(has_product_data($data->product, 'support_url')): ?>
                        <a href="<?= $data->product->support_url ?>" target="_blank" class="modern-btn-outline btn">
                            <i class="fas fa-life-ring me-1"></i>
                            Support
                        </a>
                        <?php else: ?>
                        <div class="modern-btn-outline btn disabled">
                            <i class="fas fa-life-ring me-1"></i>
                            Support <span class="text-muted">(Not available)</span>
                        </div>
                        <?php endif ?>
                        <?php endif ?>
                    </div>
                    
                    <!-- Social Media Links -->
                    <?php 
                    $social_links = ['facebook_url', 'instagram_url', 'twitter_url', 'youtube_url'];
                    $has_social = false;
                    foreach($social_links as $social) {
                        if(should_display_datapoint($social, $enabled_datapoints)) {
                            $has_social = true;
                            break;
                        }
                    }
                    ?>
                    <?php if($has_social): ?>
                    <div class="mt-4">
                        <h6 class="mb-3">Social Media</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php if(should_display_datapoint('facebook_url', $enabled_datapoints)): ?>
                            <?php if(has_product_data($data->product, 'facebook_url')): ?>
                            <a href="<?= $data->product->facebook_url ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <?php else: ?>
                            <div class="btn btn-outline-secondary btn-sm disabled">
                                <i class="fab fa-facebook-f"></i>
                            </div>
                            <?php endif ?>
                            <?php endif ?>
                            
                            <?php if(should_display_datapoint('instagram_url', $enabled_datapoints)): ?>
                            <?php if(has_product_data($data->product, 'instagram_url')): ?>
                            <a href="<?= $data->product->instagram_url ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <?php else: ?>
                            <div class="btn btn-outline-secondary btn-sm disabled">
                                <i class="fab fa-instagram"></i>
                            </div>
                            <?php endif ?>
                            <?php endif ?>
                            
                            <?php if(should_display_datapoint('twitter_url', $enabled_datapoints)): ?>
                            <?php if(has_product_data($data->product, 'twitter_url')): ?>
                            <a href="<?= $data->product->twitter_url ?>" target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <?php else: ?>
                            <div class="btn btn-outline-secondary btn-sm disabled">
                                <i class="fab fa-twitter"></i>
                            </div>
                            <?php endif ?>
                            <?php endif ?>
                            
                            <?php if(should_display_datapoint('youtube_url', $enabled_datapoints)): ?>
                            <?php if(has_product_data($data->product, 'youtube_url')): ?>
                            <a href="<?= $data->product->youtube_url ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <?php else: ?>
                            <div class="btn btn-outline-secondary btn-sm disabled">
                                <i class="fab fa-youtube"></i>
                            </div>
                            <?php endif ?>
                            <?php endif ?>
                        </div>
                    </div>
                    <?php endif ?>
                <?= section_wrapper_end('digital_links', $section_accordions) ?>
                <?php endif ?>
                
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <?php if(is_datapoint_enabled('product_url', $enabled_datapoints) || is_datapoint_enabled('purchase_url', $enabled_datapoints) || is_datapoint_enabled('manual_url', $enabled_datapoints) || is_datapoint_enabled('support_url', $enabled_datapoints)): ?>
                <div class="modern-card">
                    <h5><i class="fas fa-external-link-alt"></i><?= l('products.gs1.quick_actions') ?></h5>
                    <div class="d-grid gap-2">
                        <?php if(is_datapoint_enabled('product_url', $enabled_datapoints) && $data->product->product_url): ?>
                        <a href="<?= $data->product->product_url ?>" target="_blank" class="modern-btn-outline btn">
                            <i class="fas fa-globe me-1"></i>
                            <?= l('products.visit_website') ?>
                        </a>
                        <?php endif ?>
                        
                        <?php if(is_datapoint_enabled('purchase_url', $enabled_datapoints) && $data->product->purchase_url): ?>
                        <a href="<?= $data->product->purchase_url ?>" target="_blank" class="modern-btn">
                            <i class="fas fa-shopping-cart me-1"></i>
                            <?= l('products.buy_now') ?>
                        </a>
                        <?php endif ?>
                        
                        <?php if(is_datapoint_enabled('manual_url', $enabled_datapoints) && $data->product->manual_url): ?>
                        <a href="<?= $data->product->manual_url ?>" target="_blank" class="modern-btn-outline btn">
                            <i class="fas fa-book me-1"></i>
                            <?= l('products.user_manual') ?>
                        </a>
                        <?php endif ?>
                        
                        <?php if(is_datapoint_enabled('support_url', $enabled_datapoints) && $data->product->support_url): ?>
                        <a href="<?= $data->product->support_url ?>" target="_blank" class="modern-btn-outline btn">
                            <i class="fas fa-life-ring me-1"></i>
                            <?= l('products.support') ?>
                        </a>
                        <?php endif ?>
                    </div>
                </div>
                <?php endif ?>

                <!-- Data Verification Status -->
                <div class="modern-card">
                    <h5><i class="fas fa-check-circle"></i><?= l('products.gs1.data_verification') ?></h5>
                    <div class="text-center">
                        <?php
                        $status = $data->product->data_verification_status ?? 'unverified';
                        $statusClass = [
                            'unverified' => 'unverified',
                            'self_verified' => 'self-verified',
                            'third_party_verified' => 'third-party-verified',
                            'certified' => 'certified'
                        ][$status] ?? 'unverified';
                        ?>
                        <div class="verification-badge <?= $statusClass ?> mb-3">
                            <i class="fas fa-<?= $status === 'certified' ? 'certificate' : ($status === 'third_party_verified' ? 'user-check' : ($status === 'self_verified' ? 'user' : 'question-circle')) ?> me-2"></i>
                            <?= l('products.gs1.status_' . $status) ?>
                        </div>
                        <?php if($data->product->passport_last_updated): ?>
                        <small class="text-muted">
                            <?= l('products.gs1.last_updated') ?>: <?= date('M j, Y', strtotime($data->product->passport_last_updated)) ?>
                        </small>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-modern">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6><?= l('products.gs1.about_digital_passport') ?></h6>
                    <p class="small opacity-75"><?= l('products.gs1.digital_passport_footer_text') ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small mb-0">
                        <?= l('products.gs1.powered_by') ?> <strong>GS1 Digital Link</strong><br>
                        <a href="https://www.gs1.org/standards/gs1-digital-link" target="_blank" class="text-light opacity-75">
                            <?= l('products.gs1.learn_more') ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    
    <script>
        // Generate QR Code
        document.addEventListener('DOMContentLoaded', function() {
            const qrCodeElement = document.getElementById('qrcode');
            if (qrCodeElement) {
                QRCode.toCanvas(qrCodeElement, window.location.href, {
                    width: 200,
                    height: 200,
                    margin: 2,
                    color: {
                        dark: '<?= $colors['primary'] ?>',
                        light: '#FFFFFF'
                    }
                }, function (error) {
                    if (error) {
                        console.error('QR Code generation failed:', error);
                        qrCodeElement.innerHTML = '<div class="text-muted"><i class="fas fa-qrcode fa-3x"></i><br>QR Code</div>';
                    }
                });
            }
        });

        // Share Product Function
        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                    title: '<?= addslashes($data->product->product_name ?? $data->product->gtin) ?>',
                    text: '<?= addslashes($data->product->product_description ?? l('products.gs1.product_information_for') . ' ' . $data->product->gtin) ?>',
                    url: window.location.href
                }).catch(console.error);
            } else {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('<?= l('products.gs1.link_copied') ?>');
                }).catch(function() {
                    const textArea = document.createElement('textarea');
                    textArea.value = window.location.href;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('<?= l('products.gs1.link_copied') ?>');
                });
            }
        }

        // Analytics tracking
        <?php if($data->product->gs1_link_id): ?>
        fetch('<?= url('api/gs1-links/' . $data->product->gs1_link_id . '/track') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'page_view',
                user_agent: navigator.userAgent,
                referrer: document.referrer
            })
        }).catch(console.error);
        <?php endif ?>
    </script>
</body>
</html>
