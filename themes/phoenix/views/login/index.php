<?php defined('SEEGAP') || die() ?>

<?= \SeeGap\Alerts::output_alerts() ?>

<?php
//SEEGAP:DEMO if(DEMO) {
//SEEGAP:DEMO echo '<div class="card mb-3">';
//SEEGAP:DEMO echo '<div class="card-body py-2 px-3">';
//SEEGAP:DEMO echo '<div class="h6 mb-1">Demo</div>';
//SEEGAP:DEMO echo '<div><small class="text-muted">📱 Some features are disabled as this is a demo version.</small></div>';
//SEEGAP:DEMO echo '<div><small class="text-muted">🛠️ You can login as the admin with the prefilled credentials below.</small></div>';
//SEEGAP:DEMO echo '<div><small class="text-muted">👨‍💻 You can also register your own account to test it as a normal user</small></div>';
//SEEGAP:DEMO echo '</div>';
//SEEGAP:DEMO echo '</div>';
//SEEGAP:DEMO }
?>

<div class="card">
    <div class="card-body">
        <!-- Test deployment update - June 13, 2025 - AUTO DEPLOY TEST -->
        <h1><?= l('login.header') ?></h1>

        <form action="" method="post" role="form">
            <?php if(isset($_SESSION['twofa_required']) && $data->user && $data->user->twofa_secret && $data->user->status == 1): ?>
                <input type="hidden" name="email" value="<?= $data->user ? $data->values['email'] : null ?>" required="required" />
                <input type="hidden" name="password" value="<?= $data->user ? $data->values['password'] : null ?>" required="required" />
                <input type="hidden" name="rememberme" value="<?= $data->values['rememberme'] ? '1' : null ?>">

                <div class="form-group">
                    <label for="twofa_token"><?= l('login.twofa_token') ?></label>
                    <input id="twofa_token" type="text" name="twofa_token" class="form-control <?= \SeeGap\Alerts::has_field_errors('twofa_token') ? 'is-invalid' : null ?>" required="required" autocomplete="off" autofocus="autofocus" placeholder="123 456" maxlength="6" />
                    <?= \SeeGap\Alerts::output_field_error('twofa_token') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-primary"><?= l('login.verify') ?></button>
            <?php else: ?>

                <div class="form-group">
                    <label for="email"><?= l('global.email') ?></label>
                    <input id="email" type="text" name="email" class="form-control <?= \SeeGap\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['email'] ?>" required="required" autofocus="autofocus" />
                    <?= \SeeGap\Alerts::output_field_error('email') ?>
                </div>

                <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                    <label for="password"><?= l('global.password') ?></label>
                    <input id="password" type="password" name="password" class="form-control <?= \SeeGap\Alerts::has_field_errors('password') ? 'is-invalid' : null ?>" value="<?= $data->user ? $data->values['password'] : null ?>" required="required" />
                    <?= \SeeGap\Alerts::output_field_error('password') ?>
                </div>

                <?php if(settings()->captcha->login_is_enabled): ?>
                    <div class="form-group">
                        <?php $data->captcha->display() ?>
                    </div>
                <?php endif ?>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-checkbox" data-toggle="tooltip" title="<?= sprintf(l('login.remember_me_help'), settings()->users->login_rememberme_cookie_days ?? 30) ?>" data-tooltip-hide-on-click>
                        <input type="checkbox" name="rememberme" class="custom-control-input" id="rememberme" <?= $data->values['rememberme'] ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="rememberme"><?= l('login.remember_me') ?></label>
                    </div>

                    <small>
                        <a href="<?= url('lost-password' . $data->redirect_append) ?>"><?= l('login.lost_password') ?></a>
                        <?php if(settings()->users->email_confirmation): ?>
                            / <a href="<?= url('resend-activation' . $data->redirect_append) ?>"><?= l('login.resend_activation') ?></a>
                        <?php endif ?>
                    </small>
                </div>

                <button type="submit" name="submit" class="btn btn-primary" <?= isset($_COOKIE['login_lockout']) ? 'disabled="disabled"' : null ?>><?= l('login.login') ?></button>
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

        <?php if(settings()->users->register_is_enabled): ?>
            <div class="text-center">
                <?= sprintf(l('login.register'), '<a href="' . url('register' . $data->redirect_append) . '">' . l('login.register_help') . '</a>') ?>
            </div>
        <?php endif ?>
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
                    "name": "<?= l('login.title') ?>",
                    "item": "<?= url('login') ?>"
                }
            ]
        }
    </script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
