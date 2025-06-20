<?php defined('SEEGAP') || die() ?>

<?php 
// Get items from settings, handle both new (items) and legacy (images) data structures
$items_to_display = [];
if(!empty($data->link->settings->items)) {
    $items_to_display = (array) $data->link->settings->items;
} elseif(!empty($data->link->settings->images)) {
    $items_to_display = (array) $data->link->settings->images;
} elseif(!empty($data->link->settings->image)) {
    // Handle legacy single image structure
    $items_to_display = [[
        'image' => $data->link->settings->image,
        'image_alt' => $data->link->settings->image_alt ?? '',
        'location_url' => $data->link->settings->location_url ?? ''
    ]];
}
?>

<?php if(count($items_to_display)): ?>
    <?php
    // Get all customization settings with defaults
    $columns = $data->link->settings->columns ?? 3;
    $grid_gap = $data->link->settings->grid_gap ?? 10;
    $image_height = $data->link->settings->image_height ?? 200;
    $aspect_ratio = $data->link->settings->aspect_ratio ?? '1:1';
    $image_fit = $data->link->settings->image_fit ?? 'cover';
    $border_radius = $data->link->settings->border_radius ?? 0;
    $hover_effect = $data->link->settings->hover_effect ?? 'none';
    
    // Calculate height based on aspect ratio with minimum height validation
    $calculated_height = $image_height;
    if($aspect_ratio !== 'custom') {
        switch($aspect_ratio) {
            case '16:9':
                $calculated_height = 'calc(100vw / ' . $columns . ' * 9 / 16)';
                break;
            case '4:3':
                $calculated_height = 'calc(100vw / ' . $columns . ' * 3 / 4)';
                break;
            case '1:1':
                $calculated_height = 'calc(100vw / ' . $columns . ')';
                break;
            case '21:9':
                $calculated_height = 'calc(100vw / ' . $columns . ' * 9 / 21)';
                break;
        }
    } else {
        // Ensure minimum height of 100px for custom height
        $safe_height = max(100, (int)$image_height);
        $calculated_height = $safe_height . 'px';
    }
    
    // Calculate column width based on columns and gap
    $column_width = 'calc(' . (100 / $columns) . '% - ' . ($grid_gap * ($columns - 1) / $columns) . 'px)';
    
    // Generate unique ID for this grid instance
    $grid_id = 'image_grid_' . $data->link->microsite_block_id;
    ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div id="<?= $grid_id ?>" class="image-grid-container" style="--grid-columns: <?= $columns ?>; --grid-gap: <?= $grid_gap ?>px; --border-radius: <?= $border_radius ?>px;">
        <?php foreach($items_to_display as $key => $item): ?>
            <div class="image-grid-item" data-index="<?= $key ?>">
                <?php 
                // Handle both object and array formats
                $image_file = is_object($item) ? $item->image : $item['image'];
                $image_alt = is_object($item) ? ($item->image_alt ?? '') : ($item['image_alt'] ?? '');
                $location_url = is_object($item) ? ($item->location_url ?? '') : ($item['location_url'] ?? '');
                ?>
                
                <div class="image-wrapper" style="height: <?= $calculated_height ?>;">
                    <?php if($location_url): ?>
                        <a href="<?= $location_url . $data->link->utm_query ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" target="<?= $data->link->settings->open_in_new_tab ? '_blank' : '_self' ?>" class="image-grid-link <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>">
                            <img 
                                src="<?= \SeeGap\Uploads::get_full_url('block_images') . $image_file ?>" 
                                class="image-grid-image hover-effect-<?= $hover_effect ?> <?= ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null ?>" 
                                style="object-fit: <?= $image_fit ?>;" 
                                alt="<?= $image_alt ?>" 
                                loading="lazy" 
                            />
                        </a>
                    <?php else: ?>
                        <img 
                            src="<?= \SeeGap\Uploads::get_full_url('block_images') . $image_file ?>" 
                            class="image-grid-image hover-effect-<?= $hover_effect ?>" 
                            style="object-fit: <?= $image_fit ?>;" 
                            alt="<?= $image_alt ?>" 
                            loading="lazy" 
                        />
                    <?php endif ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<!-- CSS Styles for Image Grid -->
<style>
#<?= $grid_id ?> {
    width: 100%;
    display: grid;
    gap: <?= $grid_gap ?>px;
    grid-template-columns: repeat(<?= $columns ?>, 1fr);
}

#<?= $grid_id ?> .image-grid-item {
    position: relative;
    overflow: hidden;
    border-radius: <?= $border_radius ?>px;
}

#<?= $grid_id ?> .image-wrapper {
    position: relative;
    width: 100%;
    height: <?= $calculated_height ?>;
    overflow: hidden;
    border-radius: <?= $border_radius ?>px;
}

#<?= $grid_id ?> .image-grid-image {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: <?= $image_fit ?>;
    transition: all 0.3s ease;
    border-radius: <?= $border_radius ?>px;
}

#<?= $grid_id ?> .image-grid-link {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
}

#<?= $grid_id ?> .image-grid-link:hover {
    text-decoration: none;
}

/* Hover Effects */
<?php if($hover_effect === 'zoom'): ?>
#<?= $grid_id ?> .hover-effect-zoom:hover {
    transform: scale(1.05);
}
<?php elseif($hover_effect === 'fade'): ?>
#<?= $grid_id ?> .hover-effect-fade:hover {
    opacity: 0.8;
}
<?php elseif($hover_effect === 'lift'): ?>
#<?= $grid_id ?> .hover-effect-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
<?php endif ?>

/* Responsive Design - Maintain column count but adjust sizing */
@media (max-width: 768px) {
    #<?= $grid_id ?> .image-wrapper {
        height: <?= $aspect_ratio !== 'custom' ? 'calc((100vw - ' . ($grid_gap * ($columns - 1)) . 'px) / ' . $columns . ' * 9 / 16)' : max(150, (int)$image_height * 0.8) . 'px' ?>;
    }
}

@media (max-width: 480px) {
    #<?= $grid_id ?> .image-wrapper {
        height: <?= $aspect_ratio !== 'custom' ? 'calc((100vw - ' . ($grid_gap * ($columns - 1)) . 'px) / ' . $columns . ' * 9 / 16)' : max(120, (int)$image_height * 0.6) . 'px' ?>;
    }
}
</style>

<?php endif ?>
