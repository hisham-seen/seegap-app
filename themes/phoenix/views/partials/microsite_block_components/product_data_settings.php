<?php defined('SEEGAP') || die() ?>

<?php
$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$use_product_images = $settings->use_product_images ?? false;
$product_image_selections = $settings->product_image_selections ?? [];

// Get the product from microsite settings if available
$product = null;
if(isset($data->link->settings->product_id)) {
    $product_model = new \SeeGap\Models\Product();
    $product = $product_model->get_product_by_id($data->link->settings->product_id);
}
?>

<div class="form-group mb-3">
    <div class="custom-control custom-switch">
        <input 
            type="checkbox" 
            id="use_product_images_<?= $block_id ?>" 
            name="use_product_images" 
            class="custom-control-input"
            <?= $use_product_images ? 'checked="checked"' : '' ?>
            <?= !$product ? 'disabled' : '' ?>
        >
        <label class="custom-control-label" for="use_product_images_<?= $block_id ?>">
            <?= l('microsite_blocks.use_product_images') ?>
        </label>
    </div>

    <?php if(!$product): ?>
        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle mr-2"></i>
            <?= l('microsite_blocks.select_product_first') ?>
        </div>
    <?php endif ?>

    <div id="product_images_container_<?= $block_id ?>" class="mt-3" style="display: <?= ($use_product_images && $product) ? 'block' : 'none' ?>;">
        <?php if($product && !empty($product->product_images)): ?>
            <?php foreach($product->product_images as $index => $image): ?>
                <div class="custom-control custom-checkbox mb-2">
                    <input
                        type="checkbox"
                        id="product_image_<?= $block_id ?>_<?= $index ?>"
                        name="product_image_selections[]"
                        value="<?= $index ?>"
                        class="custom-control-input"
                        <?= in_array($index, $product_image_selections) ? 'checked="checked"' : '' ?>
                    >
                    <label class="custom-control-label d-flex align-items-center" for="product_image_<?= $block_id ?>_<?= $index ?>">
                        <img src="<?= UPLOADS_FULL_URL . 'block_images/' . $image ?>" 
                             alt="Product Image <?= $index + 1 ?>"
                             class="rounded mr-2"
                             style="width: 50px; height: 50px; object-fit: cover;"
                        >
                        <?= l('microsite_blocks.product_image') ?> <?= $index + 1 ?>
                    </label>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleSwitch = document.getElementById('use_product_images_<?= $block_id ?>');
    const container = document.getElementById('product_images_container_<?= $block_id ?>');
    
    if(toggleSwitch) {
        toggleSwitch.addEventListener('change', function() {
            container.style.display = this.checked ? 'block' : 'none';
        });
    }
});
</script>
