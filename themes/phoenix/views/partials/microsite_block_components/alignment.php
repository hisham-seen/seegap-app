<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Alignment Component for Microsite Blocks
 * Provides alignment controls for content positioning
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param string $field_name - Field name for the alignment setting (default: 'text_alignment')
 * @param string $label - Label for the alignment control (default: from language)
 * @param string $icon - Icon class for the label (default: 'fas fa-align-center')
 * @param array $alignment_options - Available alignment options (default: center, justify, left, right)
 * @param string $default_alignment - Default alignment value (default: 'center')
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$field_name = $field_name ?? 'text_alignment';
$label = $label ?? l('microsite_link.alignment') ?? 'Alignment';
$icon = $icon ?? 'fas fa-align-center';
$alignment_options = $alignment_options ?? ['center', 'justify', 'left', 'right'];
$default_alignment = $default_alignment ?? 'center';

$current_alignment = $settings->{$field_name} ?? $default_alignment;
?>

<!-- Text/Content Alignment -->
<div class="form-group">
    <label for="<?= $field_name . '_' . $block_id ?>"><i class="<?= $icon ?> fa-fw fa-sm text-muted mr-1"></i> <?= $label ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <?php foreach($alignment_options as $alignment): ?>
            <div class="col-6">
                <label class="btn btn-light btn-block text-truncate <?= $current_alignment == $alignment ? 'active' : '' ?>">
                    <input type="radio" name="<?= $field_name ?>" value="<?= $alignment ?>" class="custom-control-input" <?= $current_alignment == $alignment ? 'checked="checked"' : '' ?> />
                    <i class="fas fa-fw fa-align-<?= $alignment ?> fa-sm mr-1"></i> <?= l('microsite_link.text_alignment.' . $alignment) ?>
                </label>
            </div>
        <?php endforeach ?>
    </div>
</div>
