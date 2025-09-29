<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Product Update Wrapper Layout
 * Provides consistent layout with secondary sidebar, header, and footer
 * for all product update sections
 */

// Get the secondary sidebar configuration
$secondary_sidebar_config = include THEME_PATH . 'views/partials/product_update_sidebar_config.php';
?>

<div class="container">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('products') ?>"><?= l('products.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('products.update.breadcrumb') ?></li>
        </ol>
    </nav>

    <!-- Product Header -->
    <div class="d-flex justify-content-between mb-4">
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
        <div class="d-flex">
            <?php if(settings()->codes->qr_codes_is_enabled): ?>
                <div class="mr-3">
                    <a href="<?= url('qr-code-create?name=' . urlencode($data->product->product_name) . '&type=url&url=' . urlencode($data->product->target_url ?: '#') . '&product_id=' . $data->product->product_id) ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-fw fa-qrcode fa-sm mr-1"></i> <?= l('qr_codes.create') ?>
                    </a>
                </div>
            <?php endif ?>

            <?php if($data->product->gs1_link_id && settings()->gs1_links->gs1_links_is_enabled): ?>
                <div class="mr-3">
                    <a href="<?= url('gs1-link-manager/edit/' . $data->product->gs1_link_id) ?>" class="btn btn-outline-info">
                        <i class="fas fa-fw fa-barcode fa-sm mr-1"></i> <?= l('gs1_links.edit') ?>
                    </a>
                </div>
            <?php endif ?>

            <div class="mr-3">
                <a href="<?= url('products') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-fw fa-arrow-left fa-sm mr-1"></i> <?= l('global.back') ?>
                </a>
            </div>

            <div>
                <a href="#" class="btn btn-outline-danger" data-toggle="modal" data-target="#product_delete_modal" data-product-id="<?= $data->product->product_id ?>" data-resource-name="<?= $data->product->product_name ?>" data-gtin="<?= $data->product->gtin ?>" data-name="<?= $data->product->product_name ?>">
                    <i class="fas fa-fw fa-trash-alt fa-sm mr-1"></i> <?= l('global.delete') ?>
                </a>
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

    <!-- Main Content Area with Secondary Sidebar -->
    <div class="row">
        <!-- Secondary Sidebar -->
        <div class="col-xl-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cog fa-sm mr-1"></i>
                        <?= l('products.sections.title') ?>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?= include_view(THEME_PATH . 'views/partials/secondary_sidebar.php', ['config' => $secondary_sidebar_config]) ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-xl-9">
            <!-- Form wrapper for all sections -->
            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
                
                <?php
                // Load the appropriate section view
                $section_file = THEME_PATH . 'views/product-update/sections/' . $data->section . '.php';
                if (file_exists($section_file)) {
                    include $section_file;
                } else {
                    // Fallback to general section if file doesn't exist
                    include THEME_PATH . 'views/product-update/sections/general.php';
                }
                ?>
            </form>
        </div>
    </div>

    <!-- Product Footer Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-barcode text-primary mr-2"></i>
                                <div>
                                    <small class="text-muted d-block"><?= l('products.input.gtin') ?></small>
                                    <strong><?= $data->product->gtin ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php if($data->product->project_name): ?>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-project-diagram text-info mr-2"></i>
                                <div>
                                    <small class="text-muted d-block"><?= l('projects.project') ?></small>
                                    <strong style="color: <?= $data->product->project_color ?>"><?= $data->product->project_name ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php endif ?>
                        <?php if($data->product->gs1_link_id): ?>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-link text-success mr-2"></i>
                                <div>
                                    <small class="text-muted d-block"><?= l('gs1_links.gs1_link') ?></small>
                                    <strong><?= l('global.linked') ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php endif ?>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-toggle-<?= $data->product->is_enabled ? 'on text-success' : 'off text-secondary' ?> mr-2"></i>
                                <div>
                                    <small class="text-muted d-block"><?= l('global.status') ?></small>
                                    <strong><?= $data->product->is_enabled ? l('global.active') : l('global.disabled') ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Product Delete Modal -->
<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/product_delete_modal.php'), 'modals') ?>
