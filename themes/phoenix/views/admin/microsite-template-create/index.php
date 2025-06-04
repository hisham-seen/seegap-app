<?php defined('SEEGAP') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/microsites-templates') ?>"><?= l('admin_microsites_templates.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_microsite_template_create.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 mr-1"><i class="fas fa-fw fa-xs fa-moon text-primary-900 mr-2"></i> <?= l('admin_microsite_template_create.header') ?></h1>
</div>

<?= \SeeGap\Alerts::output_alerts() ?>

<div class="card <?= \SeeGap\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <input type="text" id="name" name="name" value="<?= $data->values['name'] ?>" class="form-control <?= \SeeGap\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" required="required" />
                <?= \SeeGap\Alerts::output_field_error('name') ?>
            </div>

            <div class="form-group">
                <label for="link_id"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('admin_microsites_templates.main.link_id') ?></label>
                <select id="link_id" name="link_id" class="custom-select">
                    <?php foreach($data->microsites as $microsite): ?>
                        <option value="<?= $microsite->link_id ?>" <?= $data->values['link_id'] == $microsite->link_id ? 'selected="selected"' : null?>><?= $microsite->link_id . ' - ' . $microsite->full_url ?></option>
                    <?php endforeach ?>
                </select>
                <?= \SeeGap\Alerts::output_field_error('link_id') ?>
                <small class="form-text text-muted"><?= l('admin_microsites_templates.main.link_id_help') ?></small>
            </div>

            <div class="form-group">
                <label for="order"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('global.order') ?></label>
                <input id="order" type="number" name="order" value="<?= $data->values['order'] ?>" class="form-control" />
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->values['is_enabled'] ? 'checked="checked"' : null ?>">
                <label class="custom-control-label" for="is_enabled"><?= l('global.status') ?></label>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.create') ?></button>
        </form>

    </div>
</div>
