<?php defined('SEEGAP') || die() ?>

<?= \SeeGap\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">
        <h1><?= l('reset_password.header') ?></h1>
        <p class="text-muted"><?= l('reset_password.subheader') ?></p>

        <form action="" method="post" role="form">
            <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                <label for="new_password"><?= l('reset_password.new_password') ?></label>
                <input id="new_password" type="password" name="new_password" class="form-control <?= \SeeGap\Alerts::has_field_errors('new_password') ? 'is-invalid' : null ?>" required="required" autofocus="autofocus" />
                <?= \SeeGap\Alerts::output_field_error('new_password') ?>
            </div>

            <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                <label for="repeat_password"><?= l('reset_password.repeat_password') ?></label>
                <input id="repeat_password" type="password" name="repeat_password" class="form-control <?= \SeeGap\Alerts::has_field_errors('repeat_password') ? 'is-invalid' : null ?>" required="required" />
                <?= \SeeGap\Alerts::output_field_error('repeat_password') ?>
            </div>

            <button type="submit" name="submit" class="btn btn-primary"><?= l('reset_password.submit') ?></button>
        </form>

        <div class="text-center">
            <a href="login"><?= l('reset_password.return') ?></a>
        </div>
    </div>
</div>
