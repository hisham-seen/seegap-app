<?php defined('SEEGAP') || die() ?>

<div>
    <div class="form-group">
        <label for="login_subject"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('admin_settings.email_templates.login_subject') ?></label>
        <input type="text" id="login_subject" name="login_subject" class="form-control" value="<?= settings()->email_templates->login_subject ?? '' ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.email_templates.login_subject_help') ?></small>
    </div>

    <div class="form-group">
        <label for="login_body"><i class="fas fa-fw fa-sm fa-align-left text-muted mr-1"></i> <?= l('admin_settings.email_templates.login_body') ?></label>
        <textarea id="login_body" name="login_body" class="form-control" data-code-editor data-mode="htmlmixed"><?= settings()->email_templates->login_body ?? '' ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.email_templates.login_body_help') ?></small>
        <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>', ['{{USER_NAME}}', '{{USER_EMAIL}}', '{{USER_IP}}', '{{USER_DEVICE}}', '{{SITE_TITLE}}', '{{SITE_URL}}', '{{LOGIN_LINK}}', '{{SECURITY_CODE}}']) . '</code>') ?></small>
    </div>

    <hr class="my-4">

    <div class="form-group">
        <label for="welcome_subject"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('admin_settings.email_templates.welcome_subject') ?></label>
        <input type="text" id="welcome_subject" name="welcome_subject" class="form-control" value="<?= settings()->email_templates->welcome_subject ?? '' ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.email_templates.welcome_subject_help') ?></small>
    </div>

    <div class="form-group">
        <label for="welcome_body"><i class="fas fa-fw fa-sm fa-align-left text-muted mr-1"></i> <?= l('admin_settings.email_templates.welcome_body') ?></label>
        <textarea id="welcome_body" name="welcome_body" class="form-control" data-code-editor data-mode="htmlmixed"><?= settings()->email_templates->welcome_body ?? '' ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.email_templates.welcome_body_help') ?></small>
        <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>', ['{{USER_NAME}}', '{{USER_EMAIL}}', '{{SITE_TITLE}}', '{{SITE_URL}}']) . '</code>') ?></small>
    </div>

    <hr class="my-4">

    <div class="form-group">
        <label for="account_delete_subject"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('admin_settings.email_templates.account_delete_subject') ?></label>
        <input type="text" id="account_delete_subject" name="account_delete_subject" class="form-control" value="<?= settings()->email_templates->account_delete_subject ?? '' ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.email_templates.account_delete_subject_help') ?></small>
    </div>

    <div class="form-group">
        <label for="account_delete_body"><i class="fas fa-fw fa-sm fa-align-left text-muted mr-1"></i> <?= l('admin_settings.email_templates.account_delete_body') ?></label>
        <textarea id="account_delete_body" name="account_delete_body" class="form-control" data-code-editor data-mode="htmlmixed"><?= settings()->email_templates->account_delete_body ?? '' ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.email_templates.account_delete_body_help') ?></small>
        <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>', ['{{USER_NAME}}', '{{USER_EMAIL}}', '{{USER_IP}}', '{{USER_DEVICE}}', '{{SITE_TITLE}}', '{{SITE_URL}}']) . '</code>') ?></small>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<?php ob_start() ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
<?php \SeeGap\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>

<script>
    try {
        let textarea_elements = document.querySelectorAll('textarea[data-code-editor]');

        textarea_elements.forEach(textarea_element => {
            let code_editor_instance = CodeMirror.fromTextArea(textarea_element, {
                lineNumbers: true,
                lineWrapping: true,
                mode: textarea_element.getAttribute("data-mode") || "htmlmixed",
                theme: 'default',
                indentUnit: 4,
                tabSize: 4,
                indentWithTabs: true,
                matchBrackets: true,
                autoCloseBrackets: true,
                styleActiveLine: true,
            });
        });
    } catch(error) {
        /* :) */
    }
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
