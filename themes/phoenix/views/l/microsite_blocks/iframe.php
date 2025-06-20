<?php defined('SEEGAP') || die() ?>

<?php
/* Get the iframe URL and settings from the block data */
$iframe_url = $data->link->location_url ?? '';
$iframe_height = $data->link->settings->height ?? 400;
$display_mode = $data->link->settings->display_mode ?? 'page';
$button_text = $data->link->settings->button_text ?? 'View Content';
$button_icon = $data->link->settings->button_icon ?? 'fas fa-external-link-alt';

/* Fallback to settings if location_url is empty */
if (empty($iframe_url) && !empty($data->link->settings->iframe_url)) {
    $iframe_url = $data->link->settings->iframe_url;
}

/* Generate unique modal ID */
$modal_id = 'iframe_modal_' . $data->link->microsite_block_id;
?>

<?php if(!empty($iframe_url)): ?>
<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    
    <?php if($display_mode === 'modal'): ?>
        <!-- Modal Button (styled like link block with full customization) -->
        <?php
        /* Generate button styling */
        $button_style = '';
        $button_style .= 'color: ' . ($data->link->settings->text_color ?? '#ffffff') . ';';
        $button_style .= 'background-color: ' . ($data->link->settings->background_color ?? '#007bff') . ';';
        $button_style .= 'text-align: ' . ($data->link->settings->text_alignment ?? 'center') . ';';
        
        if(($data->link->settings->border_width ?? 0) > 0) {
            $button_style .= 'border: ' . ($data->link->settings->border_width ?? 0) . 'px ' . ($data->link->settings->border_style ?? 'solid') . ' ' . ($data->link->settings->border_color ?? '#000000') . ';';
        }
        
        if(($data->link->settings->border_shadow_blur ?? 0) > 0 || ($data->link->settings->border_shadow_spread ?? 0) > 0) {
            $button_style .= 'box-shadow: ' . ($data->link->settings->border_shadow_offset_x ?? 0) . 'px ' . ($data->link->settings->border_shadow_offset_y ?? 0) . 'px ' . ($data->link->settings->border_shadow_blur ?? 0) . 'px ' . ($data->link->settings->border_shadow_spread ?? 0) . 'px ' . ($data->link->settings->border_shadow_color ?? '#000000') . ';';
        }
        
        $button_class = 'btn btn-block btn-primary link-btn';
        $button_class .= ' ' . (($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : '');
        $button_class .= ' link-btn-' . ($data->link->settings->border_radius ?? 'rounded');
        
        if(($data->link->settings->animation ?? 'false') != 'false') {
            $button_class .= ' animate__animated animate__' . $data->link->settings->animation;
            if($data->link->settings->animation_runs != 'infinite') {
                $button_class .= ' animate__' . $data->link->settings->animation_runs;
            } else {
                $button_class .= ' animate__infinite';
            }
        }
        ?>
        
        <button type="button" class="<?= $button_class ?>" style="<?= $button_style ?>" data-toggle="modal" data-target="#<?= $modal_id ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>">
            <span>
                <?php if($data->link->settings->icon ?? $button_icon): ?>
                    <i class="<?= $data->link->settings->icon ?? $button_icon ?> mr-1"></i>
                <?php endif ?>
            </span>
            <span><?= $data->link->settings->name ?? $button_text ?></span>
        </button>

    <?php else: ?>
        <!-- Page Display Mode -->
        <iframe src="<?= $iframe_url ?>" style="width: 100%; height: <?= (int) $iframe_height ?>px; object-fit: contain; border:0;" class="w-100 rounded" loading="lazy" title="Embedded content"></iframe>
    <?php endif ?>
    
</div>
<?php else: ?>
<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?= l('global.error_message.no_data') ?? 'No URL provided for iframe' ?>
    </div>
</div>
<?php endif ?>

<?php if(!empty($iframe_url) && $display_mode === 'modal'): ?>
<?php ob_start() ?>
<div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $data->link->settings->name ?? $button_text ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <iframe src="<?= $iframe_url ?>" style="width: 100%; height: <?= (int) $iframe_height ?>px; border:0;" loading="lazy" title="<?= $data->link->settings->name ?? $button_text ?>"></iframe>
            </div>
        </div>
    </div>
</div>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'modals') ?>
<?php endif ?>
