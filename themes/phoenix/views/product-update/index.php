<?php defined('SEEGAP') || die() ?>

<link href="<?= ASSETS_FULL_URL ?>css/products-custom.css?v=<?= PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">

    <div class="d-flex mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h4 text-truncate mb-0 mr-3">
            <i class="fas fa-fw fa-pencil-alt text-primary mr-1"></i> 
            <?= l('products.update.header') ?>
        </h1>
        <div class="d-flex align-items-center">
            <span class="badge badge-<?= $data->product->is_enabled ? 'success' : 'secondary' ?> mr-2">
                <?= $data->product->is_enabled ? l('global.active') : l('global.disabled') ?>
            </span>
            <?php if($data->product->gtin): ?>
                <small class="text-muted">
                    <i class="fas fa-barcode fa-sm mr-1"></i>
                    <?= $data->product->gtin ?>
                </small>
            <?php endif ?>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="nav nav-pills nav-fill nav-minimal ml-auto">
        <a class="nav-link active" data-toggle="tooltip" title="<?= l('global.edit') ?>">
            <i class="fas fa-fw fa-pencil-alt"></i>
        </a>

        <?php if(settings()->codes->qr_codes_is_enabled): ?>
            <a href="<?= url('qr-code-create?name=' . urlencode($data->product->product_name) . '&type=url&url=' . urlencode($data->product->target_url ?: '#') . '&product_id=' . $data->product->product_id) ?>" class="nav-link" data-toggle="tooltip" title="<?= l('qr_codes.create') ?>">
                <i class="fas fa-fw fa-qrcode"></i>
            </a>
        <?php endif ?>

        <?php if($data->product->gs1_link_id && settings()->gs1_links->gs1_links_is_enabled): ?>
            <a href="<?= url('gs1-link-manager/edit/' . $data->product->gs1_link_id) ?>" class="nav-link" data-toggle="tooltip" title="<?= l('gs1_links.edit') ?>">
                <i class="fas fa-fw fa-barcode"></i>
            </a>
        <?php endif ?>

        <a href="#" class="nav-link" data-toggle="modal" data-target="#product_duplicate_modal" data-product-id="<?= $data->product->product_id ?>" title="<?= l('global.duplicate') ?>">
            <i class="fas fa-fw fa-clone"></i>
        </a>

        <a href="<?= url('products') ?>" class="nav-link" data-toggle="tooltip" title="<?= l('global.back') ?>">
            <i class="fas fa-fw fa-arrow-left"></i>
        </a>

        <div class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" title="<?= l('global.more_options') ?>">
                <i class="fas fa-fw fa-ellipsis-v"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#product_delete_modal" 
                   data-product-id="<?= $data->product->product_id ?>" 
                   data-resource-name="<?= $data->product->product_name ?>" 
                   data-gtin="<?= $data->product->gtin ?>" 
                   data-name="<?= $data->product->product_name ?>">
                    <i class="fas fa-fw fa-trash-alt fa-sm mr-2"></i> <?= l('global.delete') ?>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Product Information Card -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h5 class="card-title mb-2"><?= $data->product->product_name ?></h5>
                <?php if($data->product->brand_name): ?>
                    <p class="text-muted mb-1">
                        <i class="fas fa-tag fa-sm mr-1"></i>
                        <?= $data->product->brand_name ?>
                    </p>
                <?php endif ?>
                <?php if($data->product->category): ?>
                    <p class="text-muted mb-1">
                        <i class="fas fa-folder fa-sm mr-1"></i>
                        <?= $data->product->category ?>
                        <?php if($data->product->subcategory): ?>
                            <i class="fas fa-angle-right fa-sm mx-1"></i>
                            <?= $data->product->subcategory ?>
                        <?php endif ?>
                    </p>
                <?php endif ?>
            </div>
            <div class="col-md-4 text-md-right">
                <?php if($data->product->datetime): ?>
                    <small class="text-muted d-block">
                        <i class="fas fa-calendar fa-sm mr-1"></i>
                        <?= l('global.created') ?>: <?= \SeeGap\Date::get($data->product->datetime, 2) ?>
                    </small>
                <?php endif ?>
                <?php if($data->product->last_datetime): ?>
                    <small class="text-muted d-block">
                        <i class="fas fa-clock fa-sm mr-1"></i>
                        <?= l('global.last_updated') ?>: <?= \SeeGap\Date::get($data->product->last_datetime, 2) ?>
                    </small>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?= \SeeGap\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">
        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
            
            <?php
            // Load the appropriate section view
            $current_section = $data->section ?? 'general';
            $section_file = THEME_PATH . 'views/product-update/sections/' . $current_section . '.php';
            
            if (file_exists($section_file)) {
                include $section_file;
            } else {
                // Fallback to general section
                include THEME_PATH . 'views/product-update/sections/general.php';
            }
            ?>
        </form>
    </div>
</div>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/product_delete_modal.php'), 'modals') ?>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'product_duplicate_modal', 'resource_id' => 'product_id', 'path' => 'product-ajax/duplicate']), 'modals'); ?>
