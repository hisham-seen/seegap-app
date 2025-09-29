<?php defined('SEEGAP') || die() ?>

<div class="d-flex mb-4">
    <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-wrench text-primary-900 mr-2"></i> <?= sprintf(l('admin_settings.header'), l('admin_settings.' . $data->method . '.tab')) ?></h1>
</div>

<?= \SeeGap\Alerts::output_alerts() ?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">

                <form action="<?= url('admin/settings/' . $data->method) ?>" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />

                    <?= $this->views['method'] ?>
                </form>

            </div>
        </div>
    </div>
</div>
