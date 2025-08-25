<?php defined('SEEGAP') || die() ?>

<!-- Security & SEO Settings with Sub-tabs -->
<div class="">
    <!-- Sub-tabs Navigation -->
    <div class="microsite-block-tabs">
        <div class="nav nav-pills nav-fill nav-minimal mb-3" id="security-seo-tabs" role="tablist">
            <a class="nav-item nav-link active" id="protection-tab" data-toggle="pill" href="#protection-settings" role="tab" aria-controls="protection-settings" aria-selected="true" data-toggle="tooltip" title="Security & Protection">
                <i class="fas fa-user-shield"></i>
            </a>
            <a class="nav-item nav-link" id="seo-tab" data-toggle="pill" href="#seo-settings" role="tab" aria-controls="seo-settings" aria-selected="false" data-toggle="tooltip" title="SEO Settings">
                <i class="fas fa-search-plus"></i>
            </a>
            <a class="nav-item nav-link" id="development-tab" data-toggle="pill" href="#development-settings" role="tab" aria-controls="development-settings" aria-selected="false" data-toggle="tooltip" title="Development Settings">
                <i class="fas fa-code"></i>
            </a>
            <a class="nav-item nav-link" id="pwa-tab" data-toggle="pill" href="#pwa-settings" role="tab" aria-controls="pwa-settings" aria-selected="false" data-toggle="tooltip" title="PWA Settings">
                <i class="fas fa-mobile-alt"></i>
            </a>
        </div>
    </div>

    <!-- Sub-tabs Content -->
    <div class="tab-content" id="security-seo-tabs-content">
        <!-- Protection Settings Tab -->
        <div class="tab-pane fade show active" 
             id="protection-settings" 
             role="tabpanel" 
             aria-labelledby="protection-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/protection_settings.php' ?>
            </div>
        </div>

        <!-- SEO Settings Tab -->
        <div class="tab-pane fade" 
             id="seo-settings" 
             role="tabpanel" 
             aria-labelledby="seo-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/seo_settings.php' ?>
            </div>
        </div>

        <!-- Development Settings Tab -->
        <div class="tab-pane fade" 
             id="development-settings" 
             role="tabpanel" 
             aria-labelledby="development-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/development_settings.php' ?>
            </div>
        </div>

        <!-- PWA Settings Tab -->
        <div class="tab-pane fade" 
             id="pwa-settings" 
             role="tabpanel" 
             aria-labelledby="pwa-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/pwa_settings.php' ?>
            </div>
        </div>
    </div>
</div>
