<?php defined('ALTUMCODE') || die() ?>

<form action="" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

    <div class="form-group">
        <label for="gtin"><i class="fas fa-fw fa-barcode fa-sm text-muted mr-1"></i> <?= l('gs1_links.input.gtin') ?></label>
        <input type="text" id="gtin" name="gtin" class="form-control <?= \Altum\Alerts::has_field_errors('gtin') ? 'is-invalid' : null ?>" value="<?= $data['gs1_link']->gtin ?>" placeholder="<?= l('gs1_links.input.gtin_placeholder') ?>" required="required" />
        <small class="form-text text-muted"><?= l('gs1_links.input.gtin_help') ?></small>
        <?= \Altum\Alerts::output_field_error('gtin') ?>
    </div>

    <div class="form-group">
        <label for="target_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('gs1_links.input.target_url') ?></label>
        <input type="url" id="target_url" name="target_url" class="form-control <?= \Altum\Alerts::has_field_errors('target_url') ? 'is-invalid' : null ?>" value="<?= $data['gs1_link']->target_url ?>" placeholder="<?= l('global.url_placeholder') ?>" required="required" />
        <small class="form-text text-muted"><?= l('gs1_links.input.target_url_help') ?></small>
        <?= \Altum\Alerts::output_field_error('target_url') ?>
    </div>

    <div class="form-group">
        <label for="title"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('gs1_links.input.title') ?></label>
        <input type="text" id="title" name="title" class="form-control" value="<?= $data['gs1_link']->title ?>" maxlength="256" />
        <small class="form-text text-muted"><?= l('gs1_links.input.title_help') ?></small>
    </div>

    <div class="form-group">
        <label for="description"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('gs1_links.input.description') ?></label>
        <textarea id="description" name="description" class="form-control" rows="3"><?= $data['gs1_link']->description ?></textarea>
        <small class="form-text text-muted"><?= l('gs1_links.input.description_help') ?></small>
    </div>

    <?php if(count($data['domains'])): ?>
        <div class="form-group">
            <label for="domain_id"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('link.input.domain_id') ?></label>
            <select id="domain_id" name="domain_id" class="custom-select">
                <option value=""><?= remove_url_protocol_from_url(SITE_URL) ?></option>
                <?php foreach($data['domains'] as $row): ?>
                    <option value="<?= $row->domain_id ?>" <?= $data['gs1_link']->domain_id == $row->domain_id ? 'selected="selected"' : null ?>><?= $row->host ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('link.input.domain_id_help') ?></small>
        </div>
    <?php endif ?>

    <?php if(count($data['projects'])): ?>
        <div class="form-group">
            <label for="project_id"><i class="fas fa-fw fa-project-diagram fa-sm text-muted mr-1"></i> <?= l('projects.project') ?></label>
            <select id="project_id" name="project_id" class="custom-select">
                <option value=""><?= l('global.none') ?></option>
                <?php foreach($data['projects'] as $row): ?>
                    <option value="<?= $row->project_id ?>" <?= $data['gs1_link']->project_id == $row->project_id ? 'selected="selected"' : null ?>><?= $row->name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('projects.project_help') ?></small>
        </div>
    <?php endif ?>

    <?php if(count($data['pixels'])): ?>
        <div class="form-group">
            <label for="pixels_ids"><i class="fas fa-fw fa-adjust fa-sm text-muted mr-1"></i> <?= l('pixels.pixels') ?></label>
            <select id="pixels_ids" name="pixels_ids[]" class="custom-select" multiple="multiple">
                <?php foreach($data['pixels'] as $row): ?>
                    <option value="<?= $row->pixel_id ?>" <?= in_array($row->pixel_id, $data['gs1_link']->pixels_ids) ? 'selected="selected"' : null ?>><?= $row->name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('pixels.pixels_help') ?></small>
        </div>
    <?php endif ?>

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="utm_source"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('link.input.utm_source') ?></label>
                <input type="text" id="utm_source" name="utm_source" class="form-control" value="<?= $data['gs1_link']->settings->utm->source ?? null ?>" />
                <small class="form-text text-muted"><?= l('link.input.utm_source_help') ?></small>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="utm_medium"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('link.input.utm_medium') ?></label>
                <input type="text" id="utm_medium" name="utm_medium" class="form-control" value="<?= $data['gs1_link']->settings->utm->medium ?? null ?>" />
                <small class="form-text text-muted"><?= l('link.input.utm_medium_help') ?></small>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="utm_campaign"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('link.input.utm_campaign') ?></label>
                <input type="text" id="utm_campaign" name="utm_campaign" class="form-control" value="<?= $data['gs1_link']->settings->utm->campaign ?? null ?>" />
                <small class="form-text text-muted"><?= l('link.input.utm_campaign_help') ?></small>
            </div>
        </div>
    </div>

    <div class="custom-control custom-switch my-3">
        <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data['gs1_link']->is_enabled ? 'checked="checked"' : null ?>>
        <label class="custom-control-label" for="is_enabled"><?= l('link.input.is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('link.input.is_enabled_help') ?></small>
    </div>

    <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
</form>

<?php ob_start() ?>
<script>
    'use strict';

    /* GTIN validation */
    document.querySelector('#gtin').addEventListener('input', event => {
        let gtin = event.target.value.replace(/\D/g, ''); // Remove non-digits
        
        if(gtin.length > 14) {
            gtin = gtin.substring(0, 14);
        }
        
        event.target.value = gtin;
    });

    /* Pixels */
    <?php if(count($data['pixels'])): ?>
    $('#pixels_ids').select2({
        theme: 'bootstrap4',
        placeholder: <?= json_encode(l('global.none')) ?>,
        allowClear: true,
        closeOnSelect: false
    });
    <?php endif ?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
