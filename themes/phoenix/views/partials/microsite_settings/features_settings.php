<?php defined('SEEGAP') || die() ?>

<!-- Features Settings with Sub-tabs -->
<div class="">
    <!-- Sub-tabs Navigation -->
    <div class="microsite-block-tabs">
        <div class="nav nav-pills nav-fill nav-minimal mb-3" id="features-tabs" role="tablist">
            <a class="nav-item nav-link active" id="verification-tab" data-toggle="pill" href="#verification-settings" role="tab" aria-controls="verification-settings" aria-selected="true" data-toggle="tooltip" title="Verification Badge">
                <i class="fas fa-check-circle"></i>
            </a>
            <a class="nav-item nav-link" id="branding-tab" data-toggle="pill" href="#branding-settings" role="tab" aria-controls="branding-settings" aria-selected="false" data-toggle="tooltip" title="Branding Settings">
                <i class="fas fa-random"></i>
            </a>
            <a class="nav-item nav-link" id="pixels-tab" data-toggle="pill" href="#pixels-settings" role="tab" aria-controls="pixels-settings" aria-selected="false" data-toggle="tooltip" title="Pixels & Tracking">
                <i class="fas fa-adjust"></i>
            </a>
            <a class="nav-item nav-link" id="utm-tab" data-toggle="pill" href="#utm-settings" role="tab" aria-controls="utm-settings" aria-selected="false" data-toggle="tooltip" title="UTM Parameters">
                <i class="fas fa-keyboard"></i>
            </a>
            <a class="nav-item nav-link" id="additional-tab" data-toggle="pill" href="#additional-settings" role="tab" aria-controls="additional-settings" aria-selected="false" data-toggle="tooltip" title="Additional Settings">
                <i class="fas fa-cogs"></i>
            </a>
        </div>
    </div>

    <!-- Sub-tabs Content -->
    <div class="tab-content" id="features-tabs-content">
        <!-- Verification Settings Tab -->
        <div class="tab-pane fade show active" 
             id="verification-settings" 
             role="tabpanel" 
             aria-labelledby="verification-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/verification_settings.php' ?>
            </div>
        </div>

        <!-- Branding Settings Tab -->
        <div class="tab-pane fade" 
             id="branding-settings" 
             role="tabpanel" 
             aria-labelledby="branding-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/branding_settings.php' ?>
            </div>
        </div>

        <!-- Pixels Settings Tab -->
        <div class="tab-pane fade" 
             id="pixels-settings" 
             role="tabpanel" 
             aria-labelledby="pixels-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/pixels_settings.php' ?>
            </div>
        </div>

        <!-- UTM Settings Tab -->
        <div class="tab-pane fade" 
             id="utm-settings" 
             role="tabpanel" 
             aria-labelledby="utm-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/utm_settings.php' ?>
            </div>
        </div>

        <!-- Additional Settings Tab -->
        <div class="tab-pane fade" 
             id="additional-settings" 
             role="tabpanel" 
             aria-labelledby="additional-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/additional_settings.php' ?>
            </div>
        </div>
    </div>
</div>
