<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Star Rating Component for Microsite Blocks
 * Provides star rating input with customizable range
 * 
 * @param string $block_id - Unique identifier for the block
 * @param string $field_name - Field name for the rating (default: 'stars')
 * @param string $label - Label for the field
 * @param string $icon - Icon class for the label (default: 'fas fa-star')
 * @param int $min_stars - Minimum star rating (default: 1)
 * @param int $max_stars - Maximum star rating (default: 5)
 * @param int $current_rating - Current rating value
 * @param bool $required - Whether the field is required (default: true)
 */

$block_id = $block_id ?? 'default';
$field_name = $field_name ?? 'stars';
$label = $label ?? l('microsite_review.stars') ?? 'Star Rating';
$icon = $icon ?? 'fas fa-star';
$min_stars = $min_stars ?? 1;
$max_stars = $max_stars ?? 5;
$current_rating = $current_rating ?? $max_stars;
$required = $required ?? true;

$field_id = $field_name . '_' . $block_id;
?>

<div class="form-group">
    <label for="<?= $field_id ?>"><i class="<?= $icon ?> fa-fw fa-sm text-muted mr-1"></i> <?= $label ?></label>
    <input 
        id="<?= $field_id ?>" 
        type="number" 
        min="<?= $min_stars ?>" 
        max="<?= $max_stars ?>" 
        name="<?= $field_name ?>" 
        class="form-control" 
        value="<?= $current_rating ?>" 
        <?= $required ? 'required="required"' : '' ?>
    />
    <small class="form-text text-muted">
        <?= sprintf(l('microsite_review.stars_help') ?? 'Rating from %d to %d stars', $min_stars, $max_stars) ?>
    </small>
</div>
