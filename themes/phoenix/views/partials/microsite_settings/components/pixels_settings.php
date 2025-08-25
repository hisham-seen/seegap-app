<?php defined('SEEGAP') || die() ?>

<!-- Pixels & Tracking Settings Component -->
<?php if(settings()->links->pixels_is_enabled): ?>
<div class="form-group mb-3">
    <label class="small mb-1"><i class="fas fa-fw fa-adjust fa-sm text-muted mr-1"></i> <?= l('link.settings.pixels_header') ?></label>
    <div class="form-group">
        <div class="d-flex flex-column flex-xl-row justify-content-between">
            <label><i class="fas fa-fw fa-sm fa-adjust text-muted mr-1"></i> <?= l('link.settings.pixels_ids') ?></label>
            <a href="<?= url('pixels') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('pixels.create') ?></a>
        </div>

        <div class="row">
            <?php $available_pixels = require APP_PATH . 'includes/pixels.php'; ?>
            <?php foreach($data->pixels as $pixel): ?>
                <div class="col-12 col-lg-6">
                    <div class="custom-control custom-checkbox my-2">
                        <input id="pixel_id_<?= $pixel->pixel_id ?>" name="pixels_ids[]" value="<?= $pixel->pixel_id ?>" type="checkbox" class="custom-control-input" <?= in_array($pixel->pixel_id, $data->link->pixels_ids) ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label d-flex align-items-center" for="pixel_id_<?= $pixel->pixel_id ?>">
                            <span class="text-truncate" title="<?= $pixel->name ?>"><?= $pixel->name ?></span>
                            <small class="badge badge-light ml-1" data-toggle="tooltip" title="<?= $available_pixels[$pixel->type]['name'] ?>">
                                <i class="<?= $available_pixels[$pixel->type]['icon'] ?> fa-fw fa-sm" style="color: <?= $available_pixels[$pixel->type]['color'] ?>"></i>
                            </small>
                        </label>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-info">
    <i class="fas fa-fw fa-info-circle mr-1"></i>
    Pixels tracking is not enabled on this system.
</div>
<?php endif ?>
