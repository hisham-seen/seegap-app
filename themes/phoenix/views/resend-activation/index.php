<?php defined('SEEGAP') || die() ?>

<?= \SeeGap\Alerts::output_alerts() ?>

<h1 class="h5 mb-2"><?= l('resend_activation.header') ?></h1>
<p class="text-muted mb-3"><?= l('resend_activation.subheader') ?></p>

<form action="" method="post" class="mt-3" role="form">
    <div class="form-group">
        <label for="email"><?= l('global.email') ?></label>
        <input id="email" type="email" name="email" class="form-control <?= \SeeGap\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['email'] ?>" required="required" autofocus="autofocus" />
        <?= \SeeGap\Alerts::output_field_error('email') ?>
    </div>

    <?php if(settings()->captcha->resend_activation_is_enabled): ?>
        <div class="form-group">
            <?php $data->captcha->display() ?>
        </div>
    <?php endif ?>

    <div class="form-group mt-4">
        <button type="submit" name="submit" class="btn btn-primary btn-block my-1" <?= isset($_COOKIE['resend_activation_lockout']) ? 'disabled="disabled"' : null ?>><?= l('resend_activation.submit') ?></button>
    </div>
</form>

<div class="mt-5 text-center">
    <a href="<?= url('login' . $data->redirect_append) ?>" class="text-muted"><?= l('resend_activation.return') ?></a>
</div>

<?php ob_start() ?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "<?= l('index.title') ?>",
                    "item": "<?= url() ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "<?= l('resend_activation.title') ?>",
                    "item": "<?= url('resend-activation') ?>"
                }
            ]
        }
    </script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
