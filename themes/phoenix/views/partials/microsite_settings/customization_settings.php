<?php defined('SEEGAP') || die() ?>

<!-- Customization Settings with Sub-tabs -->
<div class="">
    <!-- Sub-tabs Navigation -->
    <div class="microsite-block-tabs">
        <div class="nav nav-pills nav-fill nav-minimal mb-3" id="customization-tabs" role="tablist">
            <a class="nav-item nav-link active" id="background-tab" data-toggle="pill" href="#background-settings" role="tab" aria-controls="background-settings" aria-selected="true" data-toggle="tooltip" title="Background Settings">
                <i class="fas fa-palette"></i>
            </a>
            <a class="nav-item nav-link" id="typography-tab" data-toggle="pill" href="#typography-settings" role="tab" aria-controls="typography-settings" aria-selected="false" data-toggle="tooltip" title="Typography Settings">
                <i class="fas fa-font"></i>
            </a>
            <a class="nav-item nav-link" id="layout-tab" data-toggle="pill" href="#layout-settings" role="tab" aria-controls="layout-settings" aria-selected="false" data-toggle="tooltip" title="Layout Settings">
                <i class="fas fa-th-large"></i>
            </a>
        </div>
    </div>

    <!-- Sub-tabs Content -->
    <div class="tab-content" id="customization-tabs-content">
        <!-- Background Settings Tab -->
        <div class="tab-pane fade show active" 
             id="background-settings" 
             role="tabpanel" 
             aria-labelledby="background-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/background_settings.php' ?>
            </div>
        </div>

        <!-- Typography Settings Tab -->
        <div class="tab-pane fade" 
             id="typography-settings" 
             role="tabpanel" 
             aria-labelledby="typography-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/typography_settings.php' ?>
            </div>
        </div>

        <!-- Layout Settings Tab -->
        <div class="tab-pane fade" 
             id="layout-settings" 
             role="tabpanel" 
             aria-labelledby="layout-tab">
            <div class="card-body py-2">
                <?php require THEME_PATH . 'views/partials/microsite_settings/components/layout_settings.php' ?>
            </div>
        </div>
    </div>
</div>
