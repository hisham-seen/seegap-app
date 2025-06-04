<?php defined('SEEGAP') || die() ?>

<section class="container">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate">
                <a href="<?= url('data') ?>" class="text-muted mr-2"><i class="fas fa-fw fa-arrow-left"></i></a>
                <i class="fas fa-fw fa-xs fa-database mr-1"></i> 
                <?= l('data.submissions_header') ?>: 
                <span class="text-primary"><?= $data->form['form_name'] ?></span>
            </h1>

            <div class="ml-2">
                <span class="badge badge-light">
                    <i class="<?= $data->microsite_blocks[$data->form['type']]['icon'] ?> fa-fw fa-sm mr-1"></i>
                    <?= l('link.microsite.blocks.' . $data->form['type']) ?>
                </span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple <?= count($data->form['submissions']) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <?php 
                        // Create export URL without duplicate microsite_block_id
                        $export_url_base = url('data?microsite_block_id=' . $data->form['microsite_block_id']);
                        // Add any other filters except microsite_block_id
                        $filters_get = $data->filters->get_get();
                        if(!empty($filters_get)) {
                            $filters_array = [];
                            parse_str($filters_get, $filters_array);
                            // Remove microsite_block_id if it exists
                            if(isset($filters_array['microsite_block_id'])) {
                                unset($filters_array['microsite_block_id']);
                            }
                            // Rebuild the query string
                            $filters_get = http_build_query($filters_array);
                            if(!empty($filters_get)) {
                                $export_url_base .= '&' . $filters_get;
                            }
                        }
                        ?>
                        <a href="<?= $export_url_base . '&export=csv' ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->csv ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= $export_url_base . '&export=json' ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled' ?>">
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
                    <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= count($data->form['submissions']) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url('data?microsite_block_id=' . $data->form['microsite_block_id']) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <input type="hidden" name="microsite_block_id" value="<?= $data->form['microsite_block_id'] ?>" />

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

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="h6 m-0"><?= l('data.submissions_count') ?>: <span class="text-primary"><?= $data->form['submissions_count'] ?></span></h2>
                </div>
                
                <div>
                    <?php if(!empty($data->form['submissions'])): ?>
                    <a href="<?= url('link/' . $data->form['submissions'][0]->link_id . '?tab=blocks') ?>" class="btn btn-sm btn-outline-secondary" data-toggle="tooltip" title="<?= l('data.microsite') ?>">
                        <i class="fas fa-fw fa-hashtag mr-1"></i> <?= l('data.microsite') ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($data->form['submissions'])): ?>
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
                        <th><?= l('data.submission_details') ?></th>
                        <th><?= l('global.datetime') ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data->form['submissions'] as $row): ?>
                        <tr>
                            <td data-bulk-table class="d-none">
                                <div class="custom-control custom-checkbox">
                                    <input id="selected_datum_id_<?= $row->datum_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->datum_id ?>" />
                                    <label class="custom-control-label" for="selected_datum_id_<?= $row->datum_id ?>"></label>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column small">
                                    <?php 
                                    // Display form name if available
                                    if(isset($row->data->form_name)): ?>
                                        <div class="mb-2"><span class="font-weight-bold text-primary">Form:</span> <?= $row->data->form_name ?></div>
                                    <?php endif; ?>
                                    
                                    <?php foreach($row->data as $key => $value): ?>
                                        <?php if(is_array($value) || is_object($value)): ?>
                                            <?php if($key === 'answers' && is_array($value)): ?>
                                                <?php foreach($value as $answer): ?>
                                                    <div>
                                                        <span class="font-weight-bold"><?= $answer->question ?>:</span> 
                                                        <?= is_array($answer->answer) ? implode(', ', $answer->answer) : $answer->answer ?>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php else: ?>
                                                <div><span class="font-weight-bold"><?= $key ?>:</span> <?= json_encode($value) ?></div>
                                            <?php endif ?>
                                        <?php elseif($key !== 'form_name'): ?>
                                            <div><span class="font-weight-bold"><?= $key ?>:</span> <?= $value ?></div>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </div>
                            </td>

                            <td class="text-nowrap text-muted">
                                <span data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \SeeGap\Date::get($row->datetime, 2) . '<br /><small>' . \SeeGap\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \SeeGap\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                    <i class="fas fa-fw fa-calendar text-muted mr-1"></i>
                                    <?= \SeeGap\Date::get($row->datetime, 1) ?>
                                </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-end">
                                    <?= include_view(THEME_PATH . 'views/data/datum_dropdown_button.php', ['id' => $row->datum_id]) ?>
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
