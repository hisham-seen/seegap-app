<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="map" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'map_address_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-map-marker-alt fa-sm text-muted mr-1"></i> <?= l('microsite_map.address') ?></label>
        <input id="<?= 'map_address_' . $row->microsite_block_id ?>" type="text" class="form-control" name="address" value="<?= $row->settings->address ?>" maxlength="64" required="required" />
    </div>

    <div class="form-group">
        <label for="<?= 'map_markers_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('microsite_map.markers') ?></label>
        <textarea id="<?= 'map_markers_' . $row->microsite_block_id ?>" class="form-control" name="markers" maxlength="1024"><?= $row->settings->markers ?></textarea>
        <small class="form-text text-muted"><?= l('microsite_map.markers_help') ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'map_location_url_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_link.location_url') ?></label>
        <input id="<?= 'map_location_url_' . $row->microsite_block_id ?>" type="text" class="form-control" name="location_url" value="<?= $row->location_url ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
    </div>

    <div class="form-group">
        <label for="<?= 'map_zoom_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-search fa-sm text-muted mr-1"></i> <?= l('microsite_map.zoom') ?></label>
        <input id="<?= 'map_zoom_' . $row->microsite_block_id ?>" type="range"  min="1" max="20" class="form-control-range" name="zoom" value="<?= $row->settings->zoom ?>" required="required" />
    </div>

    <div class="form-group">
        <label for="<?= 'map_type_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-map fa-sm text-muted mr-1"></i> <?= l('global.type') ?></label>
        <select id="<?= 'map_type_' . $row->microsite_block_id ?>" name="type" class="custom-select">
            <option value="roadmap" <?= $row->settings->type == 'roadmap' ? 'selected="selected"' : null ?>><?= l('microsite_map.type.roadmap') ?></option>
            <option value="satellite" <?= $row->settings->type == 'satellite' ? 'selected="selected"' : null ?>><?= l('microsite_map.type.satellite') ?></option>
            <option value="terrain" <?= $row->settings->type == 'terrain' ? 'selected="selected"' : null ?>><?= l('microsite_map.type.terrain') ?></option>
            <option value="hybrid" <?= $row->settings->type == 'hybrid' ? 'selected="selected"' : null ?>><?= l('microsite_map.type.hybrid') ?></option>
        </select>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
