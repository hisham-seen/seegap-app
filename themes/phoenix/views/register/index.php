<?php defined('SEEGAP') || die() ?>

<?= \SeeGap\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">
        <h1><?= l('register.header') ?></h1>

        <form action="" method="post" role="form">
            <?php if(!settings()->users->register_only_social_logins): ?>
                <div class="form-group">
                    <label for="name"><?= l('global.name') ?></label>
                    <input id="name" type="text" name="name" class="form-control <?= \SeeGap\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->values['name'] ?>" maxlength="32" required="required" autofocus="autofocus" />
                    <?= \SeeGap\Alerts::output_field_error('name') ?>
                </div>

                <div class="form-group">
                    <label for="email"><?= l('global.email') ?></label>
                    <input id="email" type="email" name="email" class="form-control <?= \SeeGap\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['email'] ?>" maxlength="128" required="required" />
                    <?= \SeeGap\Alerts::output_field_error('email') ?>
                </div>

                <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                    <label for="password"><?= l('global.password') ?></label>
                    <input id="password" type="password" name="password" class="form-control <?= \SeeGap\Alerts::has_field_errors('password') ? 'is-invalid' : null ?>" value="<?= $data->values['password'] ?>" required="required" />
                    <?= \SeeGap\Alerts::output_field_error('password') ?>
                </div>

                <?php if(settings()->captcha->register_is_enabled): ?>
                    <div class="form-group">
                        <?php $data->captcha->display() ?>
                    </div>
                <?php endif ?>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="accept" class="custom-control-input" id="accept" required="required">
                    <label class="custom-control-label" for="accept">
                        <?= sprintf(
                            l('register.accept'),
                            '<a href="' . settings()->main->terms_and_conditions_url . '" target="_blank">' . l('global.terms_and_conditions') . '</a>',
                            '<a href="' . settings()->main->privacy_policy_url . '" target="_blank">' . l('global.privacy_policy') . '</a>'
                        ) ?>
                    </label>
                </div>

                <?php if(settings()->users->register_display_newsletter_checkbox): ?>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="is_newsletter_subscribed" class="custom-control-input" id="is_newsletter_subscribed">
                        <label class="custom-control-label" for="is_newsletter_subscribed">
                            <?= l('register.is_newsletter_subscribed') ?>
                        </label>
                    </div>
                <?php endif ?>

                <button type="submit" name="submit" class="btn btn-primary" <?= isset($_COOKIE['register_lockout']) ? 'disabled="disabled"' : null ?>><?= l('register.register') ?></button>
            <?php endif ?>
        </form>

        <?php if(settings()->facebook->is_enabled || settings()->google->is_enabled || settings()->twitter->is_enabled || settings()->discord->is_enabled || settings()->linkedin->is_enabled || settings()->microsoft->is_enabled): ?>
            <hr />

            <div>
                <?php if(settings()->facebook->is_enabled): ?>
                    <a href="<?= url('login/facebook-initiate') ?>" class="btn btn-light">
                        <img src="<?= ASSETS_FULL_URL . 'images/facebook.svg' ?>" />
                        <?= l('login.facebook') ?>
                    </a>
                <?php endif ?>
                <?php if(settings()->google->is_enabled): ?>
                    <a href="<?= url('login/google-initiate') ?>" class="btn btn-light">
                        <img src="<?= ASSETS_FULL_URL . 'images/google.svg' ?>" />
                        <?= l('login.google') ?>
                    </a>
                <?php endif ?>
                <?php if(settings()->twitter->is_enabled): ?>
                    <a href="<?= url('login/twitter-initiate') ?>" class="btn btn-light">
                        <img src="<?= ASSETS_FULL_URL . 'images/x.svg' ?>" />
                        <?= l('login.twitter') ?>
                    </a>
                <?php endif ?>
                <?php if(settings()->discord->is_enabled): ?>
                    <a href="<?= url('login/discord-initiate') ?>" class="btn btn-light">
                        <img src="<?= ASSETS_FULL_URL . 'images/discord.svg' ?>" />
                        <?= l('login.discord') ?>
                    </a>
                <?php endif ?>
                <?php if(settings()->linkedin->is_enabled): ?>
                    <a href="<?= url('login/linkedin-initiate') ?>" class="btn btn-light">
                        <img src="<?= ASSETS_FULL_URL . 'images/linkedin.svg' ?>" />
                        <?= l('login.linkedin') ?>
                    </a>
                <?php endif ?>
                <?php if(settings()->microsoft->is_enabled): ?>
                    <a href="<?= url('login/microsoft-initiate') ?>" class="btn btn-light">
                        <img src="<?= ASSETS_FULL_URL . 'images/microsoft.svg' ?>" />
                        <?= l('login.microsoft') ?>
                    </a>
                <?php endif ?>
            </div>
        <?php endif ?>

        <div class="text-center">
            <?= sprintf(l('register.login'), '<a href="' . url('login' . $data->redirect_append) . '">' . l('register.login_help') . '</a>') ?>
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
                    "name": "<?= l('register.title') ?>",
                    "item": "<?= url('register') ?>"
                }
            ]
        }
    </script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
