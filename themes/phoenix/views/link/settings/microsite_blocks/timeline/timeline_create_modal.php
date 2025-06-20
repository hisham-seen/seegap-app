<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_timeline" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_timeline.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_timeline" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="timeline" />

                    <div class="notification-container"></div>

                    <div id="<?= 'timeline_items_create' ?>" data-microsite-block-id="create"></div>

                    <div class="mb-3">
                        <button data-add="timeline_item" data-microsite-block-id="create" type="button" class="btn btn-outline-success btn-block"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<template id="template_timeline_item">
    <div class="mb-4">
        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_timeline.title') ?></label>
            <input id="" type="text" name="item_title[]" class="form-control" value="" required="required" />
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('microsite_link.description') ?></label>
            <textarea id="" name="item_description[]" class="form-control" required="required"></textarea>
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-calendar fa-sm text-muted mr-1"></i> <?= l('microsite_timeline.date') ?></label>
            <input id="" type="text" name="item_date[]" class="form-control" value="" required="required" />
        </div>

        <button type="button" data-remove="item" class="btn btn-block btn-sm btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<?php ob_start() ?>
    <script>
        'use strict';

        $('#create_microsite_timeline').on('shown.bs.modal', event => {
            $(event.currentTarget).find('button[data-add]').click();
        })
    </script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
    <script>
        /* Timeline Script */
        'use strict';

        /* add new */
        let timeline_item_add = event => {
            let microsite_block_id = event.currentTarget.getAttribute('data-microsite-block-id');
            let clone = document.querySelector(`#template_timeline_item`).content.cloneNode(true);
            let count = document.querySelectorAll(`[id="timeline_items_${microsite_block_id}"] .mb-4`).length;

            if(count >= 100) return;

            clone.querySelector(`input[name="item_title[]"`).setAttribute('name', `item_title[${count}]`);
            clone.querySelector(`textarea[name="item_description[]"`).setAttribute('name', `item_description[${count}]`);
            clone.querySelector(`input[name="item_date[]"`).setAttribute('name', `item_date[${count}]`);

            document.querySelector(`[id="timeline_items_${microsite_block_id}"]`).appendChild(clone);

            timeline_item_remove_initiator();
        };

        document.querySelectorAll('[data-add="timeline_item"]').forEach(element => {
            element.addEventListener('click', timeline_item_add);
        })

        /* remove */
        let timeline_item_remove = event => {
            event.currentTarget.closest('.mb-4').remove();
        };

        let timeline_item_remove_initiator = () => {
            document.querySelectorAll('[id^="timeline_items_"] [data-remove]').forEach(element => {
                element.removeEventListener('click', timeline_item_remove);
                element.addEventListener('click', timeline_item_remove)
            })
        };

        timeline_item_remove_initiator();
    </script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'timeline_block') ?>
