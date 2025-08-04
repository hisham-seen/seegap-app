<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Link Settings Component for Microsite Blocks
 * Provides URL input and "open in new tab" option
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $row - Block data object
 * @param object $settings - Block settings object
 * @param string $url_field - Field name for URL (default: 'location_url')
 * @param string $new_tab_field - Field name for new tab setting (default: 'open_in_new_tab')
 * @param string $url_label - Label for URL field
 * @param string $new_tab_label - Label for new tab field
 * @param bool $show_new_tab - Whether to show new tab option (default: true)
 */

$block_id = $block_id ?? 'default';
$row = $row ?? (object)[];
$settings = $settings ?? (object)[];
$url_field = $url_field ?? 'location_url';
$new_tab_field = $new_tab_field ?? 'open_in_new_tab';
$url_label = $url_label ?? l('microsite_link.location_url');
$new_tab_label = $new_tab_label ?? l('microsite_link.open_in_new_tab');
$show_new_tab = $show_new_tab ?? true;

$url_value = $row->{$url_field} ?? '';
$new_tab_value = $settings->{$new_tab_field} ?? false;
?>

<!-- Link URL -->
<div class="form-group">
    <label for="<?= $url_field . '_' . $block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= $url_label ?></label>
    <input 
        id="<?= $url_field . '_' . $block_id ?>" 
        type="text" 
        class="form-control" 
        name="<?= $url_field ?>" 
        value="<?= $url_value ?>" 
        maxlength="2048" 
        placeholder="<?= l('global.url_placeholder') ?>" 
    />
    <small class="form-text text-muted"><?= l('microsite_link.location_url_help') ?></small>
</div>

<?php if($show_new_tab): ?>
<!-- Open in New Tab -->
<div class="form-group custom-control custom-switch">
    <input
        id="<?= $new_tab_field . '_' . $block_id ?>"
        name="<?= $new_tab_field ?>" 
        type="checkbox"
        class="custom-control-input"
        <?= $new_tab_value ? 'checked="checked"' : null ?>
    >
    <label class="custom-control-label" for="<?= $new_tab_field . '_' . $block_id ?>"><?= $new_tab_label ?></label>
    <small class="form-text text-muted"><?= l('microsite_link.open_in_new_tab_help') ?></small>
</div>
<?php endif ?>
