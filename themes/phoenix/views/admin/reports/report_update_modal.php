<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="update_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= l('admin_reports.update') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="update_report" method="post" action="<?= url('admin/reports/update') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
                    <input type="hidden" name="request_type" value="update" />
                    <input type="hidden" name="report_id" value="" />

                    <div class="form-group">
                        <label for="update_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('global.name') ?></label>
                        <input type="text" id="update_name" name="name" class="form-control" value="" maxlength="64" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="update_description"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('global.description') ?></label>
                        <textarea id="update_description" name="description" class="form-control" maxlength="256"></textarea>
                        <small class="form-text text-muted"><?= l('global.optional') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="update_superset_domain"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('admin_reports.superset_domain') ?></label>
                        <input type="url" id="update_superset_domain" name="superset_domain" class="form-control" placeholder="https://your-superset-instance.com" required="required" />
                        <small class="form-text text-muted"><?= l('admin_reports.superset_domain_help') ?><br><strong>Example:</strong> https://superset.yourcompany.com</small>
                    </div>

                    <div class="form-group">
                        <label for="update_superset_embed_code"><i class="fas fa-fw fa-code fa-sm text-muted mr-1"></i> <?= l('admin_reports.superset_embed_code') ?></label>
                        <input type="text" id="update_superset_embed_code" name="superset_embed_code" class="form-control" placeholder="b486e85c-45b0-4ae9-831d-6a9d06c22a00" required="required" />
                        <small class="form-text text-muted"><?= l('admin_reports.superset_embed_code_help') ?><br><strong>Example:</strong> b486e85c-45b0-4ae9-831d-6a9d06c22a00</small>
                    </div>

                    <div class="form-group">
                        <label for="update_assigned_user_ids"><i class="fas fa-fw fa-users fa-sm text-muted mr-1"></i> <?= l('admin_reports.assigned_users') ?></label>
                        <select id="update_assigned_user_ids" name="assigned_user_ids[]" class="custom-select" multiple="multiple">
                            <?php foreach($data->users as $user): ?>
                                <option value="<?= $user->user_id ?>"><?= $user->name ?> (<?= $user->email ?>)</option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_reports.assigned_users_help') ?></small>
                    </div>

                    <div class="form-group custom-control custom-switch">
                        <input id="update_is_enabled" name="is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="update_is_enabled"><?= l('global.status') ?></label>
                        <small class="form-text text-muted"><?= l('admin_reports.is_enabled_help') ?></small>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-primary"><?= l('global.update') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* Initialize select2 for user assignment */
    $('#update_assigned_user_ids').select2({
        theme: 'bootstrap4',
        placeholder: '<?= l('admin_reports.assigned_users_placeholder') ?>',
        allowClear: true,
        dropdownParent: $('#update_modal')
    });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
