<?php defined('SEEGAP') || die() ?>

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
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $data->product->product_name ?? $data->product->gtin ?>">
    <meta name="twitter:description" content="<?= $data->product->product_description ?? l('products.gs1.product_information_for') . ' ' . $data->product->gtin ?>">
    <?php if(!empty($data->product->product_images) && is_array($data->product->product_images) && count($data->product->product_images) > 0): ?>
    <meta name="twitter:image" content="<?= $data->product->product_images[0] ?>">
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
        <?php if($data->product->category): ?>
        "category": "<?= addslashes($data->product->category) ?>",
        <?php endif ?>
        <?php if($data->product->country_of_origin): ?>
        "countryOfOrigin": "<?= addslashes($data->product->country_of_origin) ?>",
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
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
        }
        .product-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
        }
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: none;
        }
        .info-card h5 {
            color: #495057;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .badge-custom {
            background-color: #e9ecef;
            color: #495057;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            margin: 0.25rem;
            display: inline-block;
        }
        .sustainability-score {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            padding: 1rem;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .score-a { background-color: #28a745; color: white; }
        .score-b { background-color: #6f42c1; color: white; }
        .score-c { background-color: #fd7e14; color: white; }
        .score-d { background-color: #dc3545; color: white; }
        .score-e { background-color: #6c757d; color: white; }
        .qr-code-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .footer-section {
            background-color: #343a40;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        .verification-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            margin: 0.25rem;
        }
        .verified { background-color: #d4edda; color: #155724; }
        .unverified { background-color: #f8d7da; color: #721c24; }
        .self-verified { background-color: #fff3cd; color: #856404; }
        .third-party-verified { background-color: #cce5ff; color: #004085; }
        .certified { background-color: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <!-- Product Header -->
    <div class="product-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 mb-3"><?= $data->product->product_name ?? $data->product->gtin ?></h1>
                    <?php if($data->product->brand_name): ?>
                    <h3 class="mb-3"><?= $data->product->brand_name ?></h3>
                    <?php endif ?>
                    <?php if($data->product->product_description): ?>
                    <p class="lead"><?= $data->product->product_description ?></p>
                    <?php endif ?>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark me-2">
                            <i class="fas fa-barcode me-1"></i>
                            GTIN: <?= $data->product->gtin ?>
                        </span>
                        <?php if($data->product->category): ?>
                        <span class="badge bg-light text-dark me-2">
                            <i class="fas fa-tag me-1"></i>
                            <?= $data->product->category ?>
                        </span>
                        <?php endif ?>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <?php if(!empty($data->product->product_images) && is_array($data->product->product_images) && count($data->product->product_images) > 0): ?>
                    <img src="<?= $data->product->product_images[0] ?>" alt="<?= $data->product->product_name ?>" class="product-image">
                    <?php else: ?>
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
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
                <!-- Basic Information -->
                <div class="info-card">
                    <h5><i class="fas fa-info-circle text-primary me-2"></i><?= l('products.gs1.basic_information') ?></h5>
                    <div class="row">
                        <?php if($data->product->manufacturer): ?>
                        <div class="col-md-6 mb-3">
                            <strong><?= l('products.manufacturer') ?>:</strong><br>
                            <?= $data->product->manufacturer ?>
                        </div>
                        <?php endif ?>
                        <?php if($data->product->country_of_origin): ?>
                        <div class="col-md-6 mb-3">
                            <strong><?= l('products.country_of_origin') ?>:</strong><br>
                            <?= $data->product->country_of_origin ?>
                        </div>
                        <?php endif ?>
                        <?php if($data->product->net_weight): ?>
                        <div class="col-md-6 mb-3">
                            <strong><?= l('products.net_weight') ?>:</strong><br>
                            <?= $data->product->net_weight ?>
                        </div>
                        <?php endif ?>
                        <?php if($data->product->dimensions): ?>
                        <div class="col-md-6 mb-3">
                            <strong><?= l('products.dimensions') ?>:</strong><br>
                            <?= $data->product->dimensions ?>
                        </div>
                        <?php endif ?>
                    </div>
                </div>

                <!-- Sustainability Information -->
                <?php if($data->product->carbon_footprint || $data->product->water_usage || $data->product->recyclability_score || $data->product->renewable_energy_percentage): ?>
                <div class="info-card">
                    <h5><i class="fas fa-leaf text-success me-2"></i><?= l('products.gs1.sustainability_information') ?></h5>
                    <div class="row align-items-center">
                        <?php if($data->product->recyclability_score): ?>
                        <div class="col-md-3 text-center">
                            <div class="sustainability-score score-<?= strtolower($data->product->recyclability_score) ?>">
                                <?= $data->product->recyclability_score ?>
                            </div>
                            <small class="text-muted"><?= l('products.gs1.recyclability_score') ?></small>
                        </div>
                        <?php endif ?>
                        <div class="col-md-9">
                            <div class="row">
                                <?php if($data->product->carbon_footprint): ?>
                                <div class="col-sm-6 mb-3">
                                    <i class="fas fa-smog text-warning me-2"></i>
                                    <strong><?= l('products.gs1.carbon_footprint') ?>:</strong><br>
                                    <span class="h6"><?= $data->product->carbon_footprint ?> kg CO₂e</span>
                                </div>
                                <?php endif ?>
                                <?php if($data->product->water_usage): ?>
                                <div class="col-sm-6 mb-3">
                                    <i class="fas fa-tint text-info me-2"></i>
                                    <strong><?= l('products.gs1.water_usage') ?>:</strong><br>
                                    <span class="h6"><?= $data->product->water_usage ?> L</span>
                                </div>
                                <?php endif ?>
                                <?php if($data->product->renewable_energy_percentage): ?>
                                <div class="col-sm-6 mb-3">
                                    <i class="fas fa-solar-panel text-success me-2"></i>
                                    <strong><?= l('products.gs1.renewable_energy') ?>:</strong><br>
                                    <span class="h6"><?= $data->product->renewable_energy_percentage ?>%</span>
                                </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    <?php if($data->product->sustainability_certifications): ?>
                    <div class="mt-3">
                        <strong><?= l('products.gs1.certifications') ?>:</strong><br>
                        <?php foreach(explode(',', $data->product->sustainability_certifications) as $cert): ?>
                        <span class="badge-custom"><?= trim($cert) ?></span>
                        <?php endforeach ?>
                    </div>
                    <?php endif ?>
                </div>
                <?php endif ?>

                <!-- Ingredients & Nutrition -->
                <?php if($data->product->ingredients || $data->product->nutritional_info || $data->product->allergen_info): ?>
                <div class="info-card">
                    <h5><i class="fas fa-apple-alt text-warning me-2"></i><?= l('products.ingredients_nutrition') ?></h5>
                    <?php if($data->product->ingredients): ?>
                    <div class="mb-3">
                        <strong><?= l('products.ingredients') ?>:</strong><br>
                        <?= nl2br($data->product->ingredients) ?>
                    </div>
                    <?php endif ?>
                    <?php if($data->product->nutritional_info): ?>
                    <div class="mb-3">
                        <strong><?= l('products.nutritional_info') ?>:</strong><br>
                        <?= nl2br($data->product->nutritional_info) ?>
                    </div>
                    <?php endif ?>
                    <?php if($data->product->allergen_info): ?>
                    <div class="mb-3">
                        <strong><?= l('products.allergen_info') ?>:</strong><br>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= nl2br($data->product->allergen_info) ?>
                        </div>
                    </div>
                    <?php endif ?>
                </div>
                <?php endif ?>

                <!-- Compliance & Safety -->
                <?php if($data->product->regulatory_compliance || $data->product->safety_standards || $data->product->quality_certifications): ?>
                <div class="info-card">
                    <h5><i class="fas fa-shield-alt text-primary me-2"></i><?= l('products.gs1.compliance_safety') ?></h5>
                    <?php if($data->product->regulatory_compliance): ?>
                    <div class="mb-3">
                        <strong><?= l('products.gs1.regulatory_compliance') ?>:</strong><br>
                        <?= nl2br($data->product->regulatory_compliance) ?>
                    </div>
                    <?php endif ?>
                    <?php if($data->product->safety_standards): ?>
                    <div class="mb-3">
                        <strong><?= l('products.gs1.safety_standards') ?>:</strong><br>
                        <?= nl2br($data->product->safety_standards) ?>
                    </div>
                    <?php endif ?>
                    <?php if($data->product->quality_certifications): ?>
                    <div class="mb-3">
                        <strong><?= l('products.gs1.quality_certifications') ?>:</strong><br>
                        <?php foreach(explode(',', $data->product->quality_certifications) as $cert): ?>
                        <span class="badge-custom"><?= trim($cert) ?></span>
                        <?php endforeach ?>
                    </div>
                    <?php endif ?>
                </div>
                <?php endif ?>

                <!-- Supply Chain Information -->
                <?php if($data->product->supply_chain_transparency || $data->product->ethical_sourcing || $data->product->key_suppliers): ?>
                <div class="info-card">
                    <h5><i class="fas fa-route text-info me-2"></i><?= l('products.gs1.supply_chain_traceability') ?></h5>
                    <div class="row">
                        <?php if($data->product->supply_chain_transparency): ?>
                        <div class="col-md-6 mb-3">
                            <strong><?= l('products.gs1.transparency_level') ?>:</strong><br>
                            <span class="badge bg-<?= $data->product->supply_chain_transparency === 'high' ? 'success' : ($data->product->supply_chain_transparency === 'medium' ? 'warning' : 'secondary') ?>">
                                <?= ucfirst($data->product->supply_chain_transparency) ?>
                            </span>
                        </div>
                        <?php endif ?>
                        <?php if($data->product->ethical_sourcing): ?>
                        <div class="col-md-6 mb-3">
                            <strong><?= l('products.gs1.ethical_sourcing') ?>:</strong><br>
                            <span class="badge bg-info"><?= ucfirst(str_replace('_', ' ', $data->product->ethical_sourcing)) ?></span>
                        </div>
                        <?php endif ?>
                        <?php if($data->product->blockchain_verified): ?>
                        <div class="col-md-12 mb-3">
                            <span class="verification-badge verified">
                                <i class="fas fa-link me-1"></i>
                                <?= l('products.gs1.blockchain_verified') ?>
                            </span>
                        </div>
                        <?php endif ?>
                    </div>
                    <?php if($data->product->key_suppliers): ?>
                    <div class="mt-3">
                        <strong><?= l('products.gs1.key_suppliers') ?>:</strong><br>
                        <?= nl2br($data->product->key_suppliers) ?>
                    </div>
                    <?php endif ?>
                </div>
                <?php endif ?>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Data Verification Status -->
                <div class="info-card">
                    <h5><i class="fas fa-check-circle text-success me-2"></i><?= l('products.gs1.data_verification') ?></h5>
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

                <!-- QR Code -->
                <div class="qr-code-section">
                    <h5><i class="fas fa-qrcode text-primary me-2"></i><?= l('products.gs1.qr_code') ?></h5>
                    <div id="qrcode" class="mb-3"></div>
                    <p class="small text-muted"><?= l('products.gs1.scan_for_info') ?></p>
                    <button class="btn btn-outline-primary btn-sm" onclick="shareProduct()">
                        <i class="fas fa-share me-1"></i>
                        <?= l('global.share') ?>
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="info-card">
                    <h5><i class="fas fa-external-link-alt text-primary me-2"></i><?= l('products.gs1.quick_actions') ?></h5>
                    <div class="d-grid gap-2">
                        <?php if($data->product->product_url): ?>
                        <a href="<?= $data->product->product_url ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-globe me-1"></i>
                            <?= l('products.visit_website') ?>
                        </a>
                        <?php endif ?>
                        <?php if($data->product->purchase_url): ?>
                        <a href="<?= $data->product->purchase_url ?>" target="_blank" class="btn btn-success btn-sm">
                            <i class="fas fa-shopping-cart me-1"></i>
                            <?= l('products.buy_now') ?>
                        </a>
                        <?php endif ?>
                        <?php if($data->product->manual_url): ?>
                        <a href="<?= $data->product->manual_url ?>" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-book me-1"></i>
                            <?= l('products.user_manual') ?>
                        </a>
                        <?php endif ?>
                        <?php if($data->product->support_url): ?>
                        <a href="<?= $data->product->support_url ?>" target="_blank" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-life-ring me-1"></i>
                            <?= l('products.support') ?>
                        </a>
                        <?php endif ?>
                    </div>
                </div>

                <!-- Lifecycle Information -->
                <?php if($data->product->lifecycle_stage || $data->product->expected_lifespan): ?>
                <div class="info-card">
                    <h5><i class="fas fa-history text-warning me-2"></i><?= l('products.gs1.product_lifecycle') ?></h5>
                    <?php if($data->product->lifecycle_stage): ?>
                    <div class="mb-3">
                        <strong><?= l('products.gs1.current_stage') ?>:</strong><br>
                        <span class="badge bg-secondary"><?= ucfirst($data->product->lifecycle_stage) ?></span>
                    </div>
                    <?php endif ?>
                    <?php if($data->product->expected_lifespan): ?>
                    <div class="mb-3">
                        <strong><?= l('products.gs1.expected_lifespan') ?>:</strong><br>
                        <?= $data->product->expected_lifespan ?> <?= $data->product->lifespan_unit ?? 'years' ?>
                    </div>
                    <?php endif ?>
                    <?php if($data->product->end_of_life_instructions): ?>
                    <div class="mb-3">
                        <strong><?= l('products.gs1.end_of_life_instructions') ?>:</strong><br>
                        <small><?= nl2br($data->product->end_of_life_instructions) ?></small>
                    </div>
                    <?php endif ?>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6><?= l('products.gs1.about_digital_passport') ?></h6>
                    <p class="small"><?= l('products.gs1.digital_passport_footer_text') ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small mb-0">
                        <?= l('products.gs1.powered_by') ?> <strong>GS1 Digital Link</strong><br>
                        <a href="https://www.gs1.org/standards/gs1-digital-link" target="_blank" class="text-light">
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
                        dark: '#000000',
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
                // Fallback: Copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('<?= l('products.gs1.link_copied') ?>');
                }).catch(function() {
                    // Fallback for older browsers
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

        // Analytics tracking (if needed)
        <?php if($data->product->gs1_link_id): ?>
        // Track page view for analytics
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
