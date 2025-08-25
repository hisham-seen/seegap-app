<?php defined('SEEGAP') || die() ?>

<!-- Domain Settings Component -->
<?php if(count($data->domains)): ?>
    <div id="is_main_link_wrapper" class="form-group custom-control custom-switch mb-2">
        <input id="is_main_link" name="is_main_link" type="checkbox" class="custom-control-input" <?= $data->link->domain_id && $data->domains[$data->link->domain_id]->link_id == $data->link->link_id ? 'checked="checked"' : null ?>>
        <label class="custom-control-label" for="is_main_link"><?= l('link.settings.is_main_link') ?></label>
        <small class="form-text text-muted"><?= l('link.settings.is_main_link_help') ?></small>
    </div>
<?php endif ?>
