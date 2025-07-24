<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="accordion" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <div id="<?= 'accordion_items_' . $row->microsite_block_id ?>" data-microsite-block-id="<?= $row->microsite_block_id ?>">
        <?php if(isset($row->settings->items) && !empty($row->settings->items)): ?>
            <?php foreach($row->settings->items as $key => $item): ?>
                <div class="mb-4">
                    <div class="form-group">
                        <label for="<?= 'item_title_' . $key . '_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_accordion.title') ?></label>
                        <input id="<?= 'item_title_' . $key . '_' . $row->microsite_block_id ?>" type="text" name="item_title[<?= $key ?>]" class="form-control" value="<?= $item->title ?? '' ?>" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="<?= 'item_content_' . $key . '_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('microsite_accordion.content') ?></label>
                        <textarea id="<?= 'item_content_' . $key . '_' . $row->microsite_block_id ?>" name="item_content[<?= $key ?>]" class="form-control" required="required"><?= $item->content ?? '' ?></textarea>
                    </div>

                    <button type="button" data-remove="item" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>

    <div class="mb-3">
        <button data-add="accordion_item" data-microsite-block-id="<?= $row->microsite_block_id ?>" type="button" class="btn btn-outline-success btn-block"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'button_settings_container_' . $row->microsite_block_id ?>" aria-expanded="false" aria-controls="<?= 'button_settings_container_' . $row->microsite_block_id ?>">
        <i class="fas fa-fw fa-square-check fa-sm mr-1"></i> <?= l('microsite_link.button_header') ?>
    </button>

    <div class="collapse" id="<?= 'button_settings_container_' . $row->microsite_block_id ?>">
        <div class="form-group">
            <label for="<?= 'accordion_text_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_color') ?></label>
            <input id="<?= 'accordion_text_color_' . $row->microsite_block_id ?>" type="hidden" name="text_color" class="form-control" value="<?= $row->settings->text_color ?>" required="required" />
            <div class="text_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'accordion_background_color_' . $row->microsite_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('microsite_link.background_color') ?></label>
            <input id="<?= 'accordion_background_color_' . $row->microsite_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
            <div class="background_color_pickr"></div>
        </div>
    </div>
    <?php include THEME_PATH . 'views/partials/display_settings.php'; ?>
<div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<template id="template_accordion_item">
    <div class="mb-4">
        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_accordion.title') ?></label>
            <input id="" type="text" name="item_title[]" class="form-control" value="" required="required" />
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('microsite_accordion.content') ?></label>
            <textarea id="" name="item_content[]" class="form-control" required="required"></textarea>
        </div>

        <button type="button" data-remove="item" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<?php ob_start() ?>
<script>
    /* Accordion Script */
    'use strict';

    /* add new */
    let accordion_item_add = event => {
        let microsite_block_id = event.currentTarget.getAttribute('data-microsite-block-id');
        let clone = document.querySelector(`#template_accordion_item`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="accordion_items_${microsite_block_id}"] .mb-4`).length;

        if(count >= 100) return;

        clone.querySelector(`input[name="item_title[]"`).setAttribute('name', `item_title[${count}]`);
        clone.querySelector(`textarea[name="item_content[]"`).setAttribute('name', `item_content[${count}]`);

        document.querySelector(`[id="accordion_items_${microsite_block_id}"]`).appendChild(clone);

        accordion_item_remove_initiator();
    };

    document.querySelectorAll('[data-add="accordion_item"]').forEach(element => {
        element.addEventListener('click', accordion_item_add);
    })

    /* remove */
    let accordion_item_remove = event => {
        event.currentTarget.closest('.mb-4').remove();
    };

    let accordion_item_remove_initiator = () => {
        document.querySelectorAll('[id^="accordion_items_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', accordion_item_remove);
            element.addEventListener('click', accordion_item_remove)
        })
    };

    accordion_item_remove_initiator();
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'accordion_block') ?>
