<?php defined('SEEGAP') || die() ?>

<div class="row mb-4">
    <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
        <h1 class="h4 m-0 text-truncate">
            <i class="fas fa-fw fa-xs <?= isset($data->filters->filters['type']) ? $data->links_types[$data->filters->filters['type']]['icon'] : $data->links_types['link']['icon'] ?> mr-1"></i>
            <?= isset($data->filters->filters['type']) ? l('links.menu.' . $data->filters->filters['type']) : l('links.header') ?>
        </h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('links.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <div class="col-12 col-lg-auto d-flex d-print-none">
        <?php if(isset($data->filters->filters['type'])): ?>
            <div>
                <button type="button" data-toggle="modal" data-target="<?= '#create_' . $data->filters->filters['type'] ?>" class="btn btn-primary">
                    <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('link.' . $data->filters->filters['type'] . '.name') ?>
                </button>
            </div>

            <?php if(settings()->links->shortener_is_enabled && $data->filters->filters['type'] == 'link'): ?>
                <div class="ml-3">
                    <a href="<?= url('link-create') ?>" class="btn btn-outline-primary" data-toggle="tooltip" title="<?= l('link_create.menu') ?>">
                        <i class="fas fa-fw fa-upload fa-sm"></i>
                    </a>
                </div>
            <?php endif ?>

            <?php if(settings()->links->microsites_templates_is_enabled && $data->filters->filters['type'] == 'microsite'): ?>
                <div class="ml-3">
                    <a href="<?= url('microsites-templates') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-fw fa-moon fa-sm mr-1"></i> <?= l('microsites_templates.menu') ?>
                    </a>
                </div>
            <?php endif ?>
        <?php else: ?>
            <div>
                <div class="dropdown">
                    <button type="button" data-toggle="dropdown" data-boundary="viewport" class="btn btn-primary dropdown-toggle dropdown-toggle-simple">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('links.create') ?>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <?php if(settings()->links->microsites_is_enabled): ?>
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#create_microsite">
                                <i class="fas fa-fw fa-circle fa-sm mr-2" style="color: <?= $data->links_types['microsite']['color'] ?>"></i>

                                <?= l('link.microsite.name') ?>
                            </a>
                        <?php endif ?>

                        <?php if(settings()->links->shortener_is_enabled): ?>
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#create_link">
                                <i class="fas fa-fw fa-circle fa-sm mr-2" style="color: <?= $data->links_types['link']['color'] ?>"></i>

                                <?= l('link.link.name') ?>
                            </a>
                        <?php endif ?>

                        <?php if(settings()->links->files_is_enabled): ?>
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#create_file">
                                <i class="fas fa-fw fa-circle fa-sm mr-2" style="color: <?= $data->links_types['file']['color'] ?>"></i>

                                <?= l('link.file.name') ?>
                            </a>
                        <?php endif ?>

                        <?php if(settings()->links->events_is_enabled): ?>
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#create_event">
                                <i class="fas fa-fw fa-circle fa-sm mr-2" style="color: <?= $data->links_types['event']['color'] ?>"></i>

                                <?= l('link.event.name') ?>
                            </a>
                        <?php endif ?>

                        <?php if(settings()->links->static_is_enabled): ?>
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#create_static">
                                <i class="fas fa-fw fa-circle fa-sm mr-2" style="color: <?= $data->links_types['static']['color'] ?>"></i>

                                <?= l('link.static.name') ?>
                            </a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn btn-light dropdown-toggle-simple <?= count($data->links) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                    <i class="fas fa-fw fa-sm fa-download"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right d-print-none">
                    <a href="<?= url('links?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->csv ? null : 'disabled' ?>">
                        <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                    </a>
                    <a href="<?= url('links?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled' ?>">
                        <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                    <a href="#" onclick="window.print();return false;" class="dropdown-item <?= $this->user->plan_settings->export->pdf ? null : 'disabled' ?>">
                        <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                    </a>
                    </a>
                </div>
            </div>
        </div>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= count($data->links) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
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

                    <form action="<?= url('links') ?>" method="get" role="form">
                        <div class="form-group px-4">
                            <label for="filters_search" class="small"><?= l('global.filters.search') ?></label>
                            <input type="search" name="search" id="filters_search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                            <select name="search_by" id="filters_search_by" class="custom-select custom-select-sm">
                                <option value="url" <?= $data->filters->search_by == 'url' ? 'selected="selected"' : null ?>><?= l('links.filters.url') ?></option>
                                <option value="location_url" <?= $data->filters->search_by == 'location_url' ? 'selected="selected"' : null ?>><?= l('links.filters.location_url') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_is_enabled" class="small"><?= l('global.status') ?></label>
                            <select name="is_enabled" id="filters_is_enabled" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= l('global.active') ?></option>
                                <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= l('global.disabled') ?></option>
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

                        <?php if(settings()->links->domains_is_enabled): ?>
                            <div class="form-group px-4">
                                <div class="d-flex justify-content-between">
                                    <label for="filters_domain_id" class="small"><?= l('domains.domain_id') ?></label>
                                    <a href="<?= url('domain-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('global.create') ?></a>
                                </div>
                                <select name="domain_id" id="filters_domain_id" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <?php foreach($data->domains as $domain_id => $domain): ?>
                                        <option value="<?= $domain_id ?>" <?= isset($data->filters->filters['domain_id']) && $data->filters->filters['domain_id'] == $domain_id ? 'selected="selected"' : null ?>><?= $domain->host ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        <?php endif ?>

                        <div class="form-group px-4">
                            <label for="filters_type" class="small"><?= l('global.type') ?></label>
                            <select name="type" id="filters_type" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <?php if(settings()->links->microsites_is_enabled): ?>
                                    <option value="microsite" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'microsite' ? 'selected="selected"' : null ?>><?= l('links.menu.microsite') ?></option>
                                <?php endif ?>

                                <?php if(settings()->links->shortener_is_enabled): ?>
                                    <option value="link" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'link' ? 'selected="selected"' : null ?>><?= l('links.menu.link') ?></option>
                                <?php endif ?>

                                <?php if(settings()->links->files_is_enabled): ?>
                                    <option value="file" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'file' ? 'selected="selected"' : null ?>><?= l('links.menu.file') ?></option>
                                <?php endif ?>

                                <?php if(settings()->links->events_is_enabled): ?>
                                    <option value="event" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'event' ? 'selected="selected"' : null ?>><?= l('links.menu.event') ?></option>
                                <?php endif ?>

                                <?php if(settings()->links->static_is_enabled): ?>
                                    <option value="static" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'static' ? 'selected="selected"' : null ?>><?= l('links.menu.static') ?></option>
                                <?php endif ?>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                            <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                <option value="link_id" <?= $data->filters->order_by == 'link_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                <option value="last_datetime" <?= $data->filters->order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                                <option value="clicks" <?= $data->filters->order_by == 'clicks' ? 'selected="selected"' : null ?>><?= l('links.filters.order_by_clicks') ?></option>
                                <option value="url" <?= $data->filters->order_by == 'url' ? 'selected="selected"' : null ?>><?= l('links.filters.url') ?></option>
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

<?php if(count($data->links)): ?>


    <form id="table" action="<?= SITE_URL . 'links/bulk' ?>" method="post" role="form">
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
                    <th><?= l('link.link') ?></th>
                    <th><?= l('links.clicks') ?></th>
                    <th><?= l('global.status') ?></th>
                    <th><?= l('global.datetime') ?></th>
                    <th><?= l('global.last_datetime') ?></th>
                    <th><?= l('global.actions') ?></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($data->links as $row): ?>
                    <tr>
                        <td data-bulk-table class="d-none">
                            <div class="custom-control custom-checkbox">
                                <input id="selected_link_id_<?= $row->link_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->link_id ?>" />
                                <label class="custom-control-label" for="selected_link_id_<?= $row->link_id ?>"></label>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <div class="d-flex align-items-center">

                                <div class="link-type-icon justify-content-center mr-3 d-flex align-items-center rounded-pill" style="background-color: <?= $data->links_types[$row->type]['color'] ?>" data-toggle="tooltip" title="<?= l('link.' . $row->type . '.name') ?>">
                                    <i class="<?= $data->links_types[$row->type]['icon'] ?> text-white"></i>
                                </div>

                                <div class="d-flex flex-column min-width-0">
                                    <div class="d-inline-block text-truncate">
                                        <a href="<?= url('link/' . $row->link_id) ?>" class="font-weight-bold"><?= $row->url ?></a>
                                        <?php if($row->type == 'microsite' && $row->is_verified): ?>
                                            <span data-toggle="tooltip" title="<?= l('link.microsite.verified') ?>"><i class="fas fa-fw fa-xs fa-check-circle" style="color: #0086ff"></i></span>
                                        <?php endif ?>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <span class="d-inline-block text-truncate small">

                                        <?php if(!empty($row->location_url)): ?>
                                            <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($row->location_url)['host']) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                            <a href="<?= $row->location_url ?>" class="text-muted" title="<?= remove_url_protocol_from_url($row->location_url) ?>" target="_blank" rel="noreferrer"><?= string_truncate(remove_url_protocol_from_url($row->location_url), 32) ?></a>
                                        <?php else: ?>
                                            <img src="<?= isset($row->settings->favicon) && $row->settings->favicon ? \SeeGap\Uploads::get_full_url('favicons') . $row->settings->favicon : get_favicon_url_from_domain(parse_url($row->full_url)['host']) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                            <a href="<?= $row->full_url ?>" class="text-muted" title="<?= remove_url_protocol_from_url($row->full_url) ?>" target="_blank" rel="noreferrer"><?= string_truncate(remove_url_protocol_from_url($row->full_url), 32) ?></a>
                                        <?php endif ?>

                                        </span>
                                    </div>
                                </div>

                            </div>
                        </td>

                        <td class="text-nowrap">
                            <a href="<?= url('link/' . $row->link_id . '/statistics') ?>" class="text-decoration-none text-muted" data-toggle="tooltip" title="<?= l('links.clicks') ?>">
                                <i class="fas fa-fw fa-chart-bar fa-sm mr-1"></i> <?= nr($row->clicks) ?>
                            </a>
                        </td>

                        <td class="text-nowrap">
                            <div class="custom-control custom-switch" data-toggle="tooltip" title="<?= l('links.is_enabled_tooltip') ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="link_is_enabled_<?= $row->link_id ?>"
                                        data-row-id="<?= $row->link_id ?>"
                                        onchange="ajax_call_helper(event, 'link-ajax', 'is_enabled_toggle')"
                                    <?= $row->is_enabled ? 'checked="checked"' : null ?>
                                >
                                <label class="custom-control-label" for="link_is_enabled_<?= $row->link_id ?>"></label>
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

                                <a href="<?= url('link/' . $row->link_id) ?>" class="text-primary mr-3" data-toggle="tooltip" title="<?= l('global.edit') ?>">
                                    <i class="fas fa-fw fa-pencil-alt"></i>
                                </a>

                                <a href="<?= url('link/' . $row->link_id . '/statistics') ?>" class="text-info mr-3" data-toggle="tooltip" title="<?= l('link.statistics.link') ?>">
                                    <i class="fas fa-fw fa-chart-bar"></i>
                                </a>

                                <?php if(settings()->codes->qr_codes_is_enabled): ?>
                                    <a href="<?= url('qr-code-create?name=' . $row->url . '&project_id=' . $row->project_id . '&type=url&url=' . $row->full_url . '&link_id=' . $row->link_id . '&url_dynamic=1') ?>" class="text-secondary mr-3" data-toggle="tooltip" title="<?= l('qr_codes.create') ?>">
                                        <i class="fas fa-fw fa-qrcode"></i>
                                    </a>
                                <?php endif ?>

                                <a href="#" class="text-secondary mr-3" data-toggle="tooltip" title="<?= l('global.clipboard_copy') ?>" aria-label="<?= l('global.clipboard_copy') ?>" data-copy="<?= l('global.clipboard_copy') ?>" data-copied="<?= l('global.clipboard_copied') ?>" data-clipboard-text="<?= $row->full_url ?>">
                                    <i class="fas fa-fw fa-copy"></i>
                                </a>

                                <a href="#" class="text-success mr-3" data-toggle="modal" data-target="#link_duplicate_modal" data-link-id="<?= $row->link_id ?>" title="<?= l('global.duplicate') ?>">
                                    <i class="fas fa-fw fa-clone"></i>
                                </a>

                                <a href="#" class="text-warning mr-3" data-toggle="modal" data-target="#link_reset_modal" data-link-id="<?= $row->link_id ?>" title="<?= l('global.reset') ?>">
                                    <i class="fas fa-fw fa-redo"></i>
                                </a>

                                <a href="#" class="text-danger" data-toggle="modal" data-target="#link_delete_modal" data-link-id="<?= $row->link_id ?>" title="<?= l('global.delete') ?>">
                                    <i class="fas fa-fw fa-trash-alt"></i>
                                </a>

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
        'name' => 'links',
        'has_secondary_text' => false,
    ]); ?>

<?php endif ?>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'link',
    'resource_id' => 'link_id',
    'has_dynamic_resource_name' => false,
    'path' => 'links/delete'
]), 'modals') ?>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'link_duplicate_modal', 'resource_id' => 'link_id', 'path' => 'link-ajax/duplicate']), 'modals'); ?>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/x_reset_modal.php', ['modal_id' => 'link_reset_modal', 'resource_id' => 'link_id', 'path' => 'links/reset']), 'modals'); ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>
