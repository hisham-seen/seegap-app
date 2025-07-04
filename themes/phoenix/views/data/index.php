<?php defined('SEEGAP') || die() ?>

<section class="container">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-database mr-1"></i> <?= l('data.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('data.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple <?= count($data->forms) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <?php 
                        // Create export URL without duplicate parameters
                        $export_url_base = url('data');
                        // Add any filters
                        $filters_get = $data->filters->get_get();
                        if(!empty($filters_get)) {
                            $export_url_base .= '?' . $filters_get;
                        }
                        // Determine the correct separator for additional parameters
                        $separator = empty($filters_get) ? '?' : '&';
                        ?>
                        <a href="<?= $export_url_base . $separator . 'export=csv' ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->csv ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= $export_url_base . $separator . 'export=json' ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                        </a>
                        <a href="#" onclick="window.print();return false;" class="dropdown-item <?= $this->user->plan_settings->export->pdf ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= count($data->forms) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url(\SeeGap\Router::$original_request) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="type" class="small">
                                    <?= l('global.type') ?>
                                </label>
                                <select name="type" id="type" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <?php foreach(['email_collector', 'phone_collector', 'contact_collector', 'feedback_collector'] as $value): ?>
                                        <option value="<?= $value ?>" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == $value ? 'selected="selected"' : null ?>><?= l('link.microsite.blocks.' . $value) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <?php if(settings()->links->projects_is_enabled): ?>
                            <div class="form-group px-4">
                                <div class="d-flex justify-content-between">
                                    <label for="filters_project_id" class="small"><?= l('projects.project_id') ?></label>
                                    <a href="<?= url('projects') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('global.create') ?></a>
                                </div>
                                <select name="project_id" id="filters_project_id" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <?php foreach($data->projects as $row): ?>
                                        <option value="<?= $row->project_id ?>" <?= isset($data->filters->filters['project_id']) && $data->filters->filters['project_id'] == $row->project_id ? 'selected="selected"' : null ?>><?= $row->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <?php endif ?>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="datum_id" <?= $data->filters->order_by == 'datum_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
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
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
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

            <div class="ml-3">
                <button id="bulk_enable" type="button" class="btn btn-light" data-toggle="tooltip" title="<?= l('global.bulk_actions') ?>"><i class="fas fa-fw fa-sm fa-list"></i></button>

                <div id="bulk_group" class="btn-group d-none" role="group">
                    <div class="btn-group dropdown" role="group">
                        <button id="bulk_actions" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                            <?= l('global.bulk_actions') ?> <span id="bulk_counter" class="d-none"></span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="bulk_actions">
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#bulk_delete_modal"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                        </div>
                    </div>

                    <button id="bulk_disable" type="button" class="btn btn-secondary" data-toggle="tooltip" title="<?= l('global.close') ?>"><i class="fas fa-fw fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($data->forms)): ?>
        <form id="table" action="<?= SITE_URL . 'data/bulk' ?>" method="post" role="form">
            <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
            <input type="hidden" name="type" value="" data-bulk-type />
            <input type="hidden" name="original_request" value="<?= base64_encode(\SeeGap\Router::$original_request) ?>" />
            <input type="hidden" name="original_request_query" value="<?= base64_encode(\SeeGap\Router::$original_request_query) ?>" />

            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th data-bulk-table class="d-none">
                            <div class="custom-control custom-checkbox">
                                <input id="bulk_select_all" type="checkbox" class="custom-control-input" />
                                <label class="custom-control-label" for="bulk_select_all"></label>
                            </div>
                        </th>
                        <th><?= l('data.form_name') ?></th>
                        <th><?= l('global.type') ?></th>
                        <th><?= l('data.submissions_count') ?></th>
                        <th><?= l('data.last_submission') ?></th>
                        <?php if(settings()->links->projects_is_enabled): ?>
                        <th><?= l('projects.project_id') ?></th>
                        <?php endif ?>
                        <th><?= l('global.actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data->forms as $form): ?>
                        <tr>
                            <td data-bulk-table class="d-none">
                                <div class="custom-control custom-checkbox">
                                    <input id="selected_form_id_<?= $form->microsite_block_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $form->microsite_block_id ?>" />
                                    <label class="custom-control-label" for="selected_form_id_<?= $form->microsite_block_id ?>"></label>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <div class="d-flex flex-column">
                                    <a href="<?= url('data?microsite_block_id=' . $form->microsite_block_id) ?>" class="font-weight-bold text-truncate">
                                        <?= $form->form_name ?>
                                    </a>
                                    <?php if(isset($form->instances) && count($form->instances) > 1): ?>
                                    <small class="text-muted"><?= count($form->instances) ?> form instances</small>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <span class="badge badge-light">
                                    <i class="<?= $data->microsite_blocks[$form->type]['icon'] ?> fa-fw fa-sm mr-1"></i>
                                    <?= l('link.microsite.blocks.' . $form->type) ?>
                                </span>
                            </td>

                            <td class="text-nowrap">
                                <span class="badge badge-info">
                                    <?= $form->submissions_count ?>
                                </span>
                            </td>

                            <td class="text-nowrap text-muted">
                                <span data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \SeeGap\Date::get($form->last_submission_datetime, 2) . '<br /><small>' . \SeeGap\Date::get($form->last_submission_datetime, 3) . '</small>' . '<br /><small>(' . \SeeGap\Date::get_timeago($form->last_submission_datetime) . ')</small>') ?>">
                                    <i class="fas fa-fw fa-calendar text-muted mr-1"></i>
                                    <?= \SeeGap\Date::get($form->last_submission_datetime, 1) ?>
                                </span>
                            </td>

                            <?php if(settings()->links->projects_is_enabled): ?>
                            <td class="text-nowrap">
                                <?php if(isset($data->projects[$form->project_id])): ?>
                                    <a href="<?= url('data?project_id=' . $form->project_id) ?>" class="text-decoration-none" data-toggle="tooltip" title="<?= l('projects.project_id') ?>">
                                        <span class="badge badge-light" style="color: <?= $data->projects[$form->project_id]->color ?> !important;">
                                            <?= $data->projects[$form->project_id]->name ?>
                                        </span>
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php endif ?>

                            <td>
                                <div class="d-flex align-items-center">

                                    <a href="<?= url('data?microsite_block_id=' . $form->microsite_block_id) ?>" class="text-primary mr-3" data-toggle="tooltip" title="<?= l('global.view') ?>">
                                        <i class="fas fa-fw fa-eye"></i>
                                    </a>

                                    <a href="<?= url('link/' . $form->link_id . '?tab=blocks') ?>" class="text-info mr-3" data-toggle="tooltip" title="<?= l('data.microsite') ?>">
                                        <i class="fas fa-fw fa-hashtag"></i>
                                    </a>

                                    <?php if(isset($form->instances) && count($form->instances) > 1): ?>
                                        <div class="dropdown">
                                            <a href="#" class="text-secondary" data-toggle="dropdown" data-boundary="viewport" title="Form instances">
                                                <i class="fas fa-fw fa-list"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <h6 class="dropdown-header">Form instances</h6>
                                                <?php foreach($form->instances as $instance_id): ?>
                                                    <a href="<?= url('data?microsite_block_id=' . $instance_id) ?>" class="dropdown-item">
                                                        <i class="fas fa-fw fa-sm fa-eye mr-2"></i> 
                                                        <?= isset($microsite_blocks[$instance_id]) ? $microsite_blocks[$instance_id]->settings->name ?? 'Unknown Form' : 'Unknown Form' ?> 
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'data',
            'has_secondary_text' => false,
        ]); ?>
    <?php endif ?>

</section>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>
