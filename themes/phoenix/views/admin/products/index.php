<?php defined('SEEGAP') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-box fa-xs text-primary-900 mr-2"></i> <?= l('admin_products.header') ?></h1>

    <div class="d-flex">
        <div>
            <div class="dropdown">
                <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.export') ?>">
                    <i class="fas fa-fw fa-sm fa-download"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right d-print-none">
                    <a href="<?= url('admin/products?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                    </a>
                    <a href="<?= url('admin/products?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= l('global.filters.header') ?>">
                    <i class="fas fa-fw fa-sm fa-filter"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                    <div class="dropdown-header d-flex justify-content-between">
                        <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                        <?php if($data->filters->has_applied_filters): ?>
                            <a href="<?= url('admin/products') ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
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

<?= \SeeGap\Alerts::output_alerts() ?>

<?php if(count($data->products)): ?>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><input type="checkbox" class="custom-control-input" id="bulk_select_all" /></th>
                <th><?= l('products.table.product') ?></th>
                <th><?= l('products.table.gtin') ?></th>
                <th><?= l('admin_users.main.user') ?></th>
                <th><?= l('products.table.category') ?></th>
                <th><?= l('global.status') ?></th>
                <th><?= l('global.datetime') ?></th>
                <th><?= l('global.actions') ?></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach($data->products as $row): ?>
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="selected_product_id_<?= $row->product_id ?>" name="selected[]" value="<?= $row->product_id ?>" />
                            <label class="custom-control-label" for="selected_product_id_<?= $row->product_id ?>"></label>
                        </div>
                    </td>

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
                                    <span class="font-weight-bold"><?= $row->product_name ?></span>
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
                                        <span class="py-1 px-2 border rounded small" style="border-color: <?= $row->project_color ?> !important; color: <?= $row->project_color ?> !important;">
                                            <?= $row->project_name ?>
                                        </span>
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
                        <div class="d-flex flex-column">
                            <div>
                                <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->user_name ?></a>
                            </div>
                            <div>
                                <span class="text-muted"><?= $row->user_email ?></span>
                            </div>
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
                        <?php if($row->is_enabled): ?>
                            <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check"></i> <?= l('global.active') ?></span>
                        <?php else: ?>
                            <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash"></i> <?= l('global.disabled') ?></span>
                        <?php endif ?>
                    </td>

                    <td class="text-nowrap text-muted">
                        <span data-toggle="tooltip" title="<?= \SeeGap\Date::get($row->datetime, 1) ?>">
                            <?= \SeeGap\Date::get($row->datetime, 2) ?>
                        </span>
                    </td>

                    <td>
                        <div class="d-flex align-items-center">
                            <a href="#" data-toggle="modal" data-target="#product_delete_modal" data-product-id="<?= $row->product_id ?>" data-resource-name="<?= $row->product_name ?>" class="btn btn-sm btn-outline-danger" title="<?= l('global.delete') ?>">
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

    <div class="mt-3">
        <div class="card">
            <div class="card-body">

                <div class="d-flex flex-column flex-lg-row justify-content-between">
                    <div class="mb-2 mb-lg-0">
                        <div class="text-muted"><?= l('admin.bulk_selected_counter') ?></div>
                        <div class="mt-2">
                            <select name="bulk_action" id="bulk_action" class="custom-select custom-select-sm d-inline-block w-auto mr-2">
                                <option value=""><?= l('global.bulk_actions') ?></option>
                                <option value="delete"><?= l('global.delete') ?></option>
                            </select>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="bulk_apply()"><?= l('global.apply') ?></button>
                        </div>
                    </div>

                    <div>
                        <small class="text-muted"><?= sprintf(l('global.pagination.results'), $data->pagination->current_page ?? 1, $data->pagination->total_pages ?? 1, $data->pagination->total_items ?? 0) ?></small>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php else: ?>
    <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
        'filters_get' => $data->filters->get ?? [],
        'name' => 'admin_products',
        'has_secondary_text' => false,
    ]); ?>
<?php endif ?>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/admin/partials/admin_product_delete_modal.php'), 'modals') ?>
<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals') ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* Bulk action handler */
    let bulk_apply = () => {
        let bulk_action = document.querySelector('#bulk_action').value;
        let selected_checkboxes = document.querySelectorAll('input[name="selected[]"]:checked');

        if(!bulk_action || !selected_checkboxes.length) {
            return;
        }

        switch(bulk_action) {
            case 'delete':

                let selected_products = [];
                selected_checkboxes.forEach(checkbox => {
                    selected_products.push(checkbox.value);
                });

                /* Trigger the bulk delete modal */
                query('#bulk_delete_modal').modal('show');
                document.querySelector('#bulk_delete_modal [name="selected[]"]').value = JSON.stringify(selected_products);

                break;
        }
    }

    document.querySelector('#bulk_select_all') && document.querySelector('#bulk_select_all').addEventListener('change', event => {
        let bulk_select_all = event.currentTarget.checked;

        document.querySelectorAll('input[name="selected[]"]').forEach(element => {
            element.checked = bulk_select_all;
        });
    });

    /* Bulk counter */
    let update_bulk_counter = () => {
        let bulk_counter = document.querySelector('.bulk-selected-counter');
        let selected_checkboxes = document.querySelectorAll('input[name="selected[]"]:checked');

        if(bulk_counter) {
            bulk_counter.innerText = selected_checkboxes.length.toLocaleString();
        }
    }

    update_bulk_counter();

    document.querySelectorAll('input[name="selected[]"]') && document.querySelectorAll('input[name="selected[]"]').forEach(element => {
        element.addEventListener('change', update_bulk_counter);
    });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
