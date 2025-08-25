<?php defined('SEEGAP') || die() ?>

<!-- Additional Settings Component -->
<div class="form-group mb-3">
    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="share_is_enabled"
                name="share_is_enabled"
            <?= $data->link->settings->share_is_enabled ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="share_is_enabled"><?= l('link.settings.share_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="scroll_buttons_is_enabled"
                name="scroll_buttons_is_enabled"
            <?= $data->link->settings->scroll_buttons_is_enabled ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="scroll_buttons_is_enabled"><?= l('link.settings.scroll_buttons_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('link.settings.scroll_buttons_is_enabled_help') ?></small>
    </div>

    <?php if(settings()->links->directory_is_enabled ?? false): ?>
        <div <?= settings()->links->directory_display != 'all' && !$data->link->is_verified ? 'data-toggle="tooltip" title="' . l('link.settings.verified_required') . '"' : null ?>>
            <div class="<?= settings()->links->directory_display != 'all' && !$data->link->is_verified ? 'container-disabled' : null ?>">
                <div class="form-group custom-control custom-switch">
                    <input
                            type="checkbox"
                            class="custom-control-input"
                            id="directory_is_enabled"
                            name="directory_is_enabled"
                        <?= $data->link->directory_is_enabled ? 'checked="checked"' : null ?>
                    >
                    <label class="custom-control-label" for="directory_is_enabled"><?= l('link.settings.directory_is_enabled') ?></label>
                    <small class="form-text text-muted"><?= sprintf(l('link.settings.directory_is_enabled_help'), '<a href="' . url('directory') . '">' . l('directory.menu') . '</a>') ?></small>
                </div>
            </div>
        </div>

        <?php if(settings()->links->directory_display != 'all' && !$data->link->is_verified): ?>
            <div class="alert alert-info">
                <i class="fas fa-fw fa-info-circle mr-1"></i>
                <?php if(settings()->email_notifications->contact && !empty(settings()->email_notifications->emails)): ?>
                    <?= sprintf(l('link.settings.verified_help'), '<a href="' . url('contact') . '" class="font-weight-bold" target="_blank">', '</a>') ?>
                <?php else: ?>
                    <?= sprintf(l('link.settings.verified_help'), '', '') ?>
                <?php endif ?>
            </div>
        <?php endif ?>
    <?php endif ?>

    <?php if(settings()->links->projects_is_enabled ?? false): ?>
    <div class="form-group">
        <div class="d-flex flex-column flex-xl-row justify-content-between">
            <label for="project_id"><i class="fas fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= l('projects.project_id') ?></label>
            <a href="<?= url('project-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('projects.create') ?></a>
        </div>
        <select id="project_id" name="project_id" class="custom-select">
            <option value=""><?= l('global.none') ?></option>
            <?php foreach($data->projects as $row): ?>
                <option value="<?= $row->project_id ?>" <?= $data->link->project_id == $row->project_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <?php endif ?>

    <?php if(settings()->links->splash_page_is_enabled): ?>
        <div <?= $this->user->plan_settings->splash_pages_limit ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
            <div class="<?= $this->user->plan_settings->splash_pages_limit ? null : 'container-disabled' ?>">
                <div class="form-group">
                    <div class="d-flex flex-column flex-xl-row justify-content-between">
                        <label for="splash_page_id"><i class="fas fa-fw fa-sm fa-droplet text-muted mr-1"></i> <?= l('splash_pages.splash_page_id') ?></label>
                        <a href="<?= url('splash-pages') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('splash_pages.create') ?></a>
                    </div>
                    <select id="splash_page_id" name="splash_page_id" class="custom-select">
                        <option value=""><?= l('global.none') ?></option>
                        <?php foreach($data->splash_pages as $row): ?>
                            <option value="<?= $row->splash_page_id ?>" <?= $data->link->splash_page_id == $row->splash_page_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>
    <?php endif ?>

    <div <?= $this->user->plan_settings->leap_link ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
        <div class="<?= $this->user->plan_settings->leap_link ? null : 'container-disabled' ?>">
            <div class="form-group">
                <label for="leap_link"><i class="fas fa-fw fa-forward fa-sm text-muted mr-1"></i> <?= l('link.settings.leap_link') ?></label>
                <input id="leap_link" type="url" class="form-control" name="leap_link" value="<?= $data->link->settings->leap_link ?>" maxlength="2048" <?= !$this->user->plan_settings->leap_link ? 'disabled="disabled"': null ?> placeholder="<?= l('global.url_placeholder') ?>" autocomplete="off" />
                <small class="form-text text-muted"><?= l('link.settings.leap_link_help') ?></small>
            </div>
        </div>
    </div>
</div>
