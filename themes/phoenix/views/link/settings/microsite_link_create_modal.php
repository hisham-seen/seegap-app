<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="microsite_link_create_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="m-0">
                        <i class="fas fa-fw fa-xs fa-plus text-muted mr-1"></i>
                        <?= l('microsite_link_create.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" method="get" role="form" id="search" class="mb-3">
                    <input type="search" name="search" class="form-control form-control-sm" value="" placeholder="<?= l('global.filters.search') ?>" aria-label="<?= l('global.filters.search') ?>" />
                </form>

                <?php foreach(require APP_PATH . 'includes/microsite_blocks_categories.php' as $microsite_block_category_key => $microsite_block_category): ?>
                    <?php $enabled_blocks_html = $disabled_blocks_html = ''; ?>

                    <?php foreach(require APP_PATH . 'includes/enabled_microsite_blocks.php' as $key => $value): ?>

                        <?php if($value['category'] != $microsite_block_category_key) continue ?>

                        <?php ob_start() ?>
                        <?php if($this->user->plan_settings->enabled_microsite_blocks->{$key}): ?>
                            <div class="col-4 col-md-3 p-1" data-block-category="<?= $value['category'] ?>" data-block-id="<?= $key ?>" data-block-name="<?= l('link.microsite.blocks.' . $key) ?>">
                                <button
                                    type="button"
                                    data-dismiss="modal"
                                    data-toggle="modal"
                                    data-target="#create_microsite_<?= $key ?>"
                                    data-tooltip
                                    title="<?= l('microsite_' . $key . '.subheader') ?>"
                                    class="btn btn-sm btn-outline-secondary btn-block d-flex align-items-center py-1 px-2"
                                >
                                    <i class="<?= $data->microsite_blocks[$key]['icon'] ?> fa-xs fa-fw mr-1" style="color: <?= $data->microsite_blocks[$key]['color'] ?>"></i>
                                    <span class="text-truncate"><?= l('link.microsite.blocks.' . $key) ?></span>
                                </button>
                            </div>
                            <?php $enabled_blocks_html .= ob_get_clean(); ?>
                        <?php else: ?>
                            <div class="col-4 col-md-3 p-1" data-block-category="<?= $value['category'] ?>" data-block-id="<?= $key ?>" data-block-name="<?= l('link.microsite.blocks.' . $key) ?>">
                                <button
                                    type="button"
                                    data-toggle="tooltip"
                                    title="<?= l('global.info_message.plan_feature_no_access') ?>"
                                    class="btn btn-sm btn-outline-secondary btn-block disabled d-flex align-items-center py-1 px-2"
                                >
                                    <i class="<?= $data->microsite_blocks[$key]['icon'] ?> fa-xs fa-fw mr-1" style="color: <?= $data->microsite_blocks[$key]['color'] ?>"></i>
                                    <s class="text-truncate"><?= l('link.microsite.blocks.' . $key) ?></s>
                                </button>
                            </div>
                            <?php $disabled_blocks_html .= ob_get_clean(); ?>
                        <?php endif ?>
                    <?php endforeach ?>

                    <?php if($enabled_blocks_html || $disabled_blocks_html): ?>
                        <div class="mb-2" data-category="<?= $microsite_block_category_key ?>">
                            <div class="d-flex align-items-center mb-1">
                                <i class="<?= $microsite_block_category['icon'] ?> fa-xs mr-1" style="color: <?= $microsite_block_category['color'] ?>"></i>
                                <span class="text-muted font-weight-bold"><?= l('microsite_link_create.' . $microsite_block_category_key) ?></span>
                            </div>

                            <div class="row">
                                <?= $enabled_blocks_html ?>
                                <?= $disabled_blocks_html ?>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    document.querySelector('#search').addEventListener('submit', event => {
        event.preventDefault();
    });

    let blocks = [];
    document.querySelectorAll('[data-block-id]').forEach(element => blocks.push({
        id: element.getAttribute('data-block-id'),
        name: element.getAttribute('data-block-name').toLowerCase(),
        category: element.getAttribute('data-block-category').toLowerCase(),
    }));

    ['keyup', 'change', 'search'].forEach(event_key => document.querySelector('#microsite_link_create_modal input').addEventListener(event_key, event => {
        let string = event.currentTarget.value.toLowerCase();

        /* Hide header sections */
        document.querySelectorAll('[data-category]').forEach(element => {
            if(string.length) {
                element.classList.add('d-none');
            } else {
                element.classList.remove('d-none');
            }
        });

        for(let block of blocks) {
            if(block.name.includes(string)) {
                document.querySelector(`[data-block-id="${block.id}"]`).classList.remove('d-none');
                document.querySelector(`[data-category="${block.category}"]`).classList.remove('d-none');
            } else {
                document.querySelector(`[data-block-id="${block.id}"]`).classList.add('d-none');
            }
        }
    }));
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
