<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="<?= url('gs1-link-manager/edit/' . $data->id) ?>">
            <i class="fas fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.edit') ?>
        </a>

        <a class="dropdown-item" href="<?= url('gs1-link/' . $data->id . '/statistics') ?>">
            <i class="fas fa-fw fa-sm fa-chart-bar mr-2"></i> <?= l('link.statistics.link') ?>
        </a>

        <?php if(settings()->codes->qr_codes_is_enabled): ?>
            <a href="<?= url('qr-code-create?name=' . $data->resource_name . '&type=url&url=' . url('01/' . $data->resource_name) . '&gs1_link_id=' . $data->id . '&url_dynamic=1') ?>" class="dropdown-item">
                <i class="fas fa-fw fa-sm fa-qrcode mr-2"></i> <?= l('qr_codes.create') ?>
            </a>
        <?php endif ?>

        <a href="#" data-toggle="modal" data-target="#gs1_link_duplicate_modal" class="dropdown-item" data-gs1-link-id="<?= $data->id ?>">
            <i class="fas fa-fw fa-sm fa-clone mr-2"></i> <?= l('global.duplicate') ?>
        </a>

        <a href="#" data-toggle="modal" data-target="#gs1_link_reset_modal" class="dropdown-item" data-gs1-link-id="<?= $data->id ?>">
            <i class="fas fa-fw fa-sm fa-redo mr-2"></i> <?= l('global.reset') ?>
        </a>

        <div class="dropdown-divider"></div>

        <a href="#" data-toggle="modal" data-target="#gs1_link_delete_modal" data-gs1-link-id="<?= $data->id ?>" data-resource-name="<?= $data->resource_name ?>" class="dropdown-item">
            <i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?>
        </a>
    </div>
</div>
