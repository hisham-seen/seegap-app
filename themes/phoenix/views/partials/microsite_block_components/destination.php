<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Destination Component for Microsite Blocks
 * Provides destination URL and link settings
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param string $form_type - 'create' or 'update' to determine form behavior
 * @param bool $show_basic_only - Show only basic URL field (default: false)
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$form_type = $form_type ?? 'update';
$show_basic_only = $show_basic_only ?? false;
?>

<!-- Basic Destination URL -->
<div class="form-group">
    <label for="<?= 'destination_location_url_' . $block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('microsite_link.location_url') ?></label>
    <input id="<?= 'destination_location_url_' . $block_id ?>" type="url" class="form-control" name="location_url" value="<?= $settings->location_url ?? '' ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
    <small class="form-text text-muted"><?= l('microsite_link.location_url_help') ?? 'Enter the URL where users will be redirected when they click this block' ?></small>
</div>

<?php if (!$show_basic_only && $form_type === 'update'): ?>
    <!-- Advanced Link Settings (for update form only) -->
    <?php
    $row = (object)['settings' => $settings];
    include THEME_PATH . 'views/partials/microsite_block_components/link_settings.php';
    ?>
<?php endif; ?>
