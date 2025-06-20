<?php defined('SEEGAP') || die() ?>

<?= \SeeGap\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">
        <h1><?= l('lost_password.header') ?></h1>
        <p class="text-muted"><?= l('lost_password.subheader') ?></p>

        <form action="" method="post" role="form">
            <div class="form-group">
                <label for="email"><?= l('global.email') ?></label>
                <input id="email" type="email" name="email" class="form-control <?= \SeeGap\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['email'] ?>" required="required" autofocus="autofocus" />
                <?= \SeeGap\Alerts::output_field_error('email') ?>
            </div>

            <?php if(settings()->captcha->lost_password_is_enabled): ?>
                <div class="form-group">
                    <?php $data->captcha->display() ?>
                </div>
            <?php endif ?>

            <button type="submit" name="submit" class="btn btn-primary" <?= isset($_COOKIE['lost_password_lockout']) ? 'disabled="disabled"' : null ?>><?= l('lost_password.submit') ?></button>
        </form>

        <div class="text-center">
            <a href="<?= url('login' . $data->redirect_append) ?>"><?= l('lost_password.return') ?></a>
        </div>
    </div>
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
                    "name": "<?= l('lost_password.title') ?>",
                    "item": "<?= url('lost-password') ?>"
                }
            ]
        }
    </script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
