<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Animation Settings Component for Microsite Blocks
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
?>
    <div class="form-group">
        <label for="<?= 'animation_' . $block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation') ?></label>
        <select id="<?= 'animation_' . $block_id ?>" name="animation" class="form-control">
            <option value="false" <?= (!isset($settings->animation) || !$settings->animation) ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
            <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                <option value="<?= $animation ?>" <?= (isset($settings->animation) && $settings->animation == $animation) ? 'selected="selected"' : null ?>><?= l('microsite_animations.' . $animation) ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="form-group">
        <label for="<?= 'animation_runs_' . $block_id ?>"><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
        <select id="<?= 'animation_runs_' . $block_id ?>" name="animation_runs" class="form-control">
            <option value="repeat-1" <?= (!isset($settings->animation_runs) || $settings->animation_runs == 'repeat-1') ? 'selected="selected"' : null ?>>1</option>
            <option value="repeat-2" <?= (isset($settings->animation_runs) && $settings->animation_runs == 'repeat-2') ? 'selected="selected"' : null ?>>2</option>
            <option value="repeat-3" <?= (isset($settings->animation_runs) && $settings->animation_runs == 'repeat-3') ? 'selected="selected"' : null ?>>3</option>
            <option value="infinite" <?= (isset($settings->animation_runs) && $settings->animation_runs == 'infinite') ? 'selected="selected"' : null ?>><?= l('global.infinite') ?></option>
        </select>
    </div>

    <div class="form-group" data-range-counter data-range-counter-suffix="ms">
        <label for="<?= 'animation_delay_' . $block_id ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_delay') ?></label>
        <input id="<?= 'animation_delay_' . $block_id ?>" type="range" min="0" max="5000" step="100" class="form-control-range" name="animation_delay" value="<?= $settings->animation_delay ?? 0 ?>" required="required" />
    </div>
