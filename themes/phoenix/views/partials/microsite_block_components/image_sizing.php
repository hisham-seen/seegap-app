<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Image Sizing Component for Microsite Blocks
 * Provides height/width controls with custom values and unit selection
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param array $dimensions - Array of dimensions to show (default: ['height'])
 * @param array $unit_options - Available unit options (default: px, em, rem, %, vw, vh)
 * @param string $default_unit - Default unit (default: 'px')
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$dimensions = $dimensions ?? ['height'];
$unit_options = $unit_options ?? [
    'px' => 'px',
    'em' => 'em', 
    'rem' => 'rem',
    '%' => '%',
    'vw' => 'vw',
    'vh' => 'vh'
];
$default_unit = $default_unit ?? 'px';
?>

<?php foreach($dimensions as $dimension): ?>
    <?php
    $value_field_name = 'image_' . $dimension;
    $unit_field_name = 'image_' . $dimension . '_unit';
    $current_value = $settings->{$value_field_name} ?? '';
    $current_unit = $settings->{$unit_field_name} ?? $default_unit;

    $icon = $dimension === 'height' ? 'fas fa-arrows-alt-v' : 'fas fa-arrows-alt-h';
    $label_key = 'microsite_image.' . $dimension;
    $label = l($label_key) ?? ucfirst($dimension);
    ?>

    <!-- Image <?= ucfirst($dimension) ?> -->
    <div class="form-group">
        <label for="<?= $value_field_name . '_' . $block_id ?>"><i class="<?= $icon ?> fa-fw fa-sm text-muted mr-1"></i> <?= $label ?></label>
        <div class="input-group">
            <input 
                id="<?= $value_field_name . '_' . $block_id ?>" 
                type="number" 
                class="form-control" 
                name="<?= $value_field_name ?>" 
                min="0" 
                step="0.1"
                value="<?= $current_value ?>" 
                placeholder="Auto" 
            />
            <div class="input-group-append">
                <select 
                    id="<?= $unit_field_name . '_' . $block_id ?>" 
                    name="<?= $unit_field_name ?>" 
                    class="form-control"
                    style="max-width: 80px;"
                >
                    <?php foreach($unit_options as $value => $display): ?>
                        <option value="<?= $value ?>" <?= $current_unit == $value ? 'selected' : '' ?>>
                            <?= $display ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <small class="form-text text-muted">
            <?= l('microsite_image.' . $dimension . '_help') ?? 'Leave empty for auto ' . $dimension . ', or enter a custom value with unit' ?>
        </small>
    </div>
<?php endforeach; ?>
