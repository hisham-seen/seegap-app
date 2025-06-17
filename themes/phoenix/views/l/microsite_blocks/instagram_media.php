<?php defined('SEEGAP') || die() ?>

<?php
/* Get the Instagram URL and settings from the block data */
$instagram_url = $data->link->settings->instagram_media_url ?? $data->link->location_url ?? '';
$display_mode = $data->link->settings->display_mode ?? 'page';
$button_text = $data->link->settings->button_text ?? l('microsite_instagram_media.button_text_placeholder');
$button_icon = $data->link->settings->button_icon ?? 'fab fa-instagram';

/* Generate unique modal ID */
$modal_id = 'instagram_modal_' . $data->link->microsite_block_id;
?>

<?php if(!empty($instagram_url)): ?>
<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    
    <?php if($display_mode === 'modal'): ?>
        <!-- Accordion Button (styled like link block with full customization) -->
        <?php
        /* Generate button styling */
        $button_style = '';
        $button_style .= 'color: ' . ($data->link->settings->text_color ?? '#ffffff') . ';';
        $button_style .= 'background-color: ' . ($data->link->settings->background_color ?? '#E4405F') . ';';
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
        
        <button type="button" class="<?= $button_class ?>" style="<?= $button_style ?>" onclick="toggleInstagramAccordion('<?= $data->link->microsite_block_id ?>')" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>">
            <span>
                <?php if($data->link->settings->icon ?? $button_icon): ?>
                    <i class="<?= $data->link->settings->icon ?? $button_icon ?> mr-1"></i>
                <?php endif ?>
            </span>
            <span><?= $data->link->settings->name ?? $button_text ?></span>
            <i class="fas fa-chevron-down ml-2" id="chevron_<?= $data->link->microsite_block_id ?>"></i>
        </button>

        <!-- Accordion Content (Instagram Embed) -->
        <div id="instagram_accordion_<?= $data->link->microsite_block_id ?>" class="instagram-accordion-content" style="display: none; overflow: hidden; transition: all 0.3s ease;">
            <div class="mt-3 p-3 border rounded" style="background-color: #f8f9fa;">
                <div class="d-flex justify-content-center">
                    <blockquote class="instagram-media" data-instgrm-permalink="<?= $instagram_url ?>" data-instgrm-version="13"></blockquote>
                </div>
            </div>
        </div>

        <script>
        function toggleInstagramAccordion(blockId) {
            const content = document.getElementById('instagram_accordion_' + blockId);
            const chevron = document.getElementById('chevron_' + blockId);
            
            if (content.style.display === 'none') {
                // Show content
                content.style.display = 'block';
                chevron.classList.remove('fa-chevron-down');
                chevron.classList.add('fa-chevron-up');
                
                // Load Instagram embed script if not already loaded
                if (!window.instgrm) {
                    const script = document.createElement('script');
                    script.src = 'https://www.instagram.com/embed.js';
                    script.onload = function() {
                        if (window.instgrm) {
                            window.instgrm.Embeds.process();
                        }
                    };
                    document.head.appendChild(script);
                } else {
                    // Process Instagram embeds
                    setTimeout(function() {
                        window.instgrm.Embeds.process();
                    }, 100);
                }
            } else {
                // Hide content
                content.style.display = 'none';
                chevron.classList.remove('fa-chevron-up');
                chevron.classList.add('fa-chevron-down');
            }
        }
        </script>

    <?php else: ?>
        <!-- Page Display Mode -->
        <div class="d-flex justify-content-center">
            <blockquote class="instagram-media" data-instgrm-permalink="<?= $instagram_url ?>" data-instgrm-version="13"></blockquote>
        </div>

        <?php if(!\SeeGap\Event::exists_content_type_key('javascript', 'instagram_media')): ?>
        <?php ob_start() ?>
            <script src="https://www.instagram.com/embed.js"></script>
            <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'instagram_media') ?>
        <?php endif ?>

        <script>
            setTimeout(() => {
                document.querySelector('div[data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>"] iframe').style.width = '100%';
            }, 2000);
        </script>
    <?php endif ?>
    
</div>
<?php else: ?>
<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?= l('global.error_message.no_data') ?? 'No Instagram URL provided' ?>
    </div>
</div>
<?php endif ?>
