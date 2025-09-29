<?php defined('SEEGAP') || die() ?>

<div class="container">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-box mr-1"></i> <?= l('products.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('products.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <?php if(($this->user->plan_settings->products_limit ?? -1) != -1 && count((array)$data->products) >= ($this->user->plan_settings->products_limit ?? 0)): ?>
                    <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit') ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('products.create') ?>
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#product_create_modal">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('products.create') ?>
                    </button>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.export') ?>" data-tooltip>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('products?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('products?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.filters.header') ?>" data-tooltip>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url('products') ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="filters_search" class="small"><?= l('global.filters.search') ?></label>
                                <input type="search" name="search" id="filters_search" value="<?= $data->filters->search ?>" class="form-control form-control-sm" />
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                                <select name="search_by" id="filters_search_by" class="custom-select custom-select-sm">
                                    <option value="gtin" <?= $data->filters->search_by == 'gtin' ? 'selected="selected"' : null ?>><?= l('products.table.gtin') ?></option>
                                    <option value="product_name" <?= $data->filters->search_by == 'product_name' ? 'selected="selected"' : null ?>><?= l('products.table.product_name') ?></option>
                                    <option value="brand_name" <?= $data->filters->search_by == 'brand_name' ? 'selected="selected"' : null ?>><?= l('products.table.brand_name') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_is_enabled" class="small"><?= l('global.filters.status') ?></label>
                                <select name="is_enabled" id="filters_is_enabled" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.filters.all') ?></option>
                                    <option value="1" <?= isset($_GET['is_enabled']) && $_GET['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= l('global.active') ?></option>
                                    <option value="0" <?= isset($_GET['is_enabled']) && $_GET['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= l('global.disabled') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_project_id" class="small"><?= l('projects.project') ?></label>
                                <select name="project_id" id="filters_project_id" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.filters.all') ?></option>
                                    <?php foreach($data->projects as $project_id => $project): ?>
                                        <option value="<?= $project_id ?>" <?= isset($_GET['project_id']) && $_GET['project_id'] == $project_id ? 'selected="selected"' : null ?>><?= $project->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <?php if(count($data->categories)): ?>
                            <div class="form-group px-4">
                                <label for="filters_category" class="small"><?= l('products.table.category') ?></label>
                                <select name="category" id="filters_category" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.filters.all') ?></option>
                                    <?php foreach($data->categories as $category): ?>
                                        <option value="<?= $category ?>" <?= isset($_GET['category']) && $_GET['category'] == $category ? 'selected="selected"' : null ?>><?= $category ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <?php endif ?>

                            <?php if(count($data->brands)): ?>
                            <div class="form-group px-4">
                                <label for="filters_brand_name" class="small"><?= l('products.table.brand_name') ?></label>
                                <select name="brand_name" id="filters_brand_name" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.filters.all') ?></option>
                                    <?php foreach($data->brands as $brand): ?>
                                        <option value="<?= $brand ?>" <?= isset($_GET['brand_name']) && $_GET['brand_name'] == $brand ? 'selected="selected"' : null ?>><?= $brand ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <?php endif ?>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="last_datetime" <?= $data->filters->order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                                    <option value="gtin" <?= $data->filters->order_by == 'gtin' ? 'selected="selected"' : null ?>><?= l('products.table.gtin') ?></option>
                                    <option value="product_name" <?= $data->filters->order_by == 'product_name' ? 'selected="selected"' : null ?>><?= l('products.table.product_name') ?></option>
                                    <option value="brand_name" <?= $data->filters->order_by == 'brand_name' ? 'selected="selected"' : null ?>><?= l('products.table.brand_name') ?></option>
                                    <option value="category" <?= $data->filters->order_by == 'category' ? 'selected="selected"' : null ?>><?= l('products.table.category') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                                <select name="order_type" id="filters_order_type" class="custom-select custom-select-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                                <select name="results_per_page" id="filters_results_per_page" class="custom-select custom-select-sm">
                                    <?php foreach([10, 25, 50, 100, 250, 500] as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(count((array)$data->products)): ?>
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('products.table.product') ?></th>
                    <th><?= l('products.table.gtin') ?></th>
                    <th><?= l('products.table.category') ?></th>
                    <th><?= l('global.status') ?></th>
                    <th><?= l('global.datetime') ?></th>
                    <th><?= l('global.last_datetime') ?></th>
                    <th><?= l('global.actions') ?></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($data->products as $row): ?>
                    <tr>
                        <td class="text-nowrap">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <span class="fa-stack fa-1x" data-toggle="tooltip" title="<?= l('products.product') ?>">
                                        <i class="fas fa-circle fa-stack-2x text-primary-100"></i>
                                        <i class="fas fa-box fa-stack-1x text-primary-600"></i>
                                    </span>
                                </div>

                                <div class="d-flex flex-column min-width-0">
                                    <div class="d-inline-block text-truncate">
                                        <a href="<?= url('product-update/' . $row->product_id) ?>" class="font-weight-bold"><?= $row->product_name ?></a>
                                    </div>

                                    <?php if(!empty($row->brand_name)): ?>
                                        <div class="text-muted">
                                            <small><?= $row->brand_name ?></small>
                                        </div>
                                    <?php endif ?>

                                    <?php if(!empty($row->product_description)): ?>
                                        <div class="text-muted">
                                            <small><?= string_truncate($row->product_description, 50) ?></small>
                                        </div>
                                    <?php endif ?>

                                    <?php if($row->project_id): ?>
                                        <div>
                                            <a href="<?= url('products?project_id=' . $row->project_id) ?>" class="text-decoration-none">
                                                <span class="py-1 px-2 border rounded small" style="border-color: <?= $row->project_color ?> !important; color: <?= $row->project_color ?> !important;">
                                                    <?= $row->project_name ?>
                                                </span>
                                            </a>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <div class="text-truncate">
                                <span class="text-muted" data-toggle="tooltip" title="<?= $row->gtin ?>">
                                    <i class="fas fa-fw fa-barcode fa-sm mr-1"></i> <?= $row->gtin ?>
                                </span>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <?php if(!empty($row->category)): ?>
                                <span class="badge badge-light"><?= $row->category ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif ?>
                        </td>

                        <td class="text-nowrap">
                            <div class="custom-control custom-switch" data-toggle="tooltip" title="<?= l('products.is_enabled_tooltip') ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="product_is_enabled_<?= $row->product_id ?>"
                                        data-row-id="<?= $row->product_id ?>"
                                        onchange="ajax_call_helper(event, 'ajax', 'is_enabled_toggle')"
                                    <?= $row->is_enabled ? 'checked="checked"' : null ?>
                                >
                                <label class="custom-control-label" for="product_is_enabled_<?= $row->product_id ?>"></label>
                            </div>
                        </td>

                        <td class="text-nowrap text-muted">
                            <span data-toggle="tooltip" title="<?= \SeeGap\Date::get($row->datetime, 1) ?>">
                                <?= \SeeGap\Date::get($row->datetime, 2) ?>
                            </span>
                        </td>

                        <td class="text-nowrap text-muted">
                            <?php if($row->last_datetime): ?>
                                <span data-toggle="tooltip" title="<?= \SeeGap\Date::get($row->last_datetime, 1) ?>">
                                    <?= \SeeGap\Date::get($row->last_datetime, 2) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif ?>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">

                                <a href="<?= url('product-update/' . $row->product_id) ?>" class="text-primary mr-3" data-toggle="tooltip" title="<?= l('global.edit') ?>">
                                    <i class="fas fa-fw fa-pencil-alt"></i>
                                </a>

                                <?php if(settings()->codes->qr_codes_is_enabled): ?>
                                    <a href="<?= url('qr-code-create?name=' . urlencode($row->product_name) . '&type=url&url=' . urlencode($row->target_url ?: '#') . '&product_id=' . $row->product_id) ?>" class="text-secondary mr-3" data-toggle="tooltip" title="<?= l('qr_codes.create') ?>">
                                        <i class="fas fa-fw fa-qrcode"></i>
                                    </a>
                                <?php endif ?>

                                <?php if($row->gs1_link_id && settings()->gs1_links->gs1_links_is_enabled): ?>
                                    <a href="<?= url('gs1-link-manager/edit/' . $row->gs1_link_id) ?>" class="text-info mr-3" data-toggle="tooltip" title="<?= l('gs1_links.edit') ?>">
                                        <i class="fas fa-fw fa-barcode"></i>
                                    </a>
                                <?php endif ?>

                                <a href="#" class="text-success mr-3" data-toggle="modal" data-target="#product_duplicate_modal" data-product-id="<?= $row->product_id ?>" title="<?= l('global.duplicate') ?>">
                                    <i class="fas fa-fw fa-clone"></i>
                                </a>

                                <a href="#" class="text-danger" data-toggle="modal" data-target="#product_delete_modal" data-product-id="<?= $row->product_id ?>" data-resource-name="<?= $row->product_name ?>" data-gtin="<?= $row->gtin ?>" data-name="<?= $row->product_name ?>" title="<?= l('global.delete') ?>">
                                    <i class="fas fa-fw fa-trash-alt"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'products',
            'has_secondary_text' => true,
        ]); ?>
    <?php endif ?>
</div>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/product_delete_modal.php'), 'modals') ?>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'product_duplicate_modal', 'resource_id' => 'product_id', 'path' => 'product-ajax/duplicate']), 'modals'); ?>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/product_create_modal.php', ['data' => $data]), 'modals'); ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
