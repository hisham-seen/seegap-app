<?php defined('SEEGAP') || die() ?>

<div class="container">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div class="d-flex align-items-center mb-3">
        <h1 class="h4 m-0"><?= l('account_delete.header') ?></h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('account_delete.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Warning:</strong> This action cannot be undone. All your data, links, and account information will be permanently deleted.
            </div>

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="confirm_deletion" class="custom-control-input" id="confirm_deletion" required="required">
                        <label class="custom-control-label" for="confirm_deletion">
                            <i class="fas fa-fw fa-sm fa-check text-muted mr-1"></i>
                            I understand that this action is permanent and cannot be undone
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email_confirmation"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> Confirm your email address</label>
                    <input type="email" id="email_confirmation" name="email_confirmation" class="form-control <?= \SeeGap\Alerts::has_field_errors('email_confirmation') ? 'is-invalid' : null ?>" placeholder="<?= $this->user->email ?>" required="required" />
                    <small class="form-text text-muted">Type your email address to confirm account deletion</small>
                    <?= \SeeGap\Alerts::output_field_error('email_confirmation') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-danger"><?= l('global.delete') ?></button>
            </form>

        </div>
    </div>
</div>
