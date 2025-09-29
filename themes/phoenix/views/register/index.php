<?php defined('SEEGAP') || die() ?>

<?= \SeeGap\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <h1><?= l('register.header') ?></h1>
            <p class="text-muted">Create your account with just your name and email - no password required!</p>
        </div>

        <form action="" method="post" role="form">
            <?php if(!settings()->users->register_only_social_logins): ?>
                <div class="form-group">
                    <label for="name"><?= l('global.name') ?></label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        class="form-control <?= \SeeGap\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" 
                        value="<?= $data->values['name'] ?>" 
                        maxlength="64" 
                        required="required" 
                        autofocus="autofocus" 
                        placeholder="Enter your full name"
                    />
                    <?= \SeeGap\Alerts::output_field_error('name') ?>
                </div>

                <div class="form-group">
                    <label for="email"><?= l('global.email') ?></label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        class="form-control <?= \SeeGap\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" 
                        value="<?= $data->values['email'] ?>" 
                        maxlength="320" 
                        required="required" 
                        placeholder="Enter your email address"
                        autocomplete="email"
                    />
                    <?= \SeeGap\Alerts::output_field_error('email') ?>
                </div>

                <?php if(settings()->captcha->register_is_enabled): ?>
                    <div class="form-group">
                        <?php $data->captcha->display() ?>
                    </div>
                <?php endif ?>

                <div class="custom-control custom-checkbox mb-3">
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
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" name="is_newsletter_subscribed" class="custom-control-input" id="is_newsletter_subscribed">
                        <label class="custom-control-label" for="is_newsletter_subscribed">
                            <?= l('register.is_newsletter_subscribed') ?>
                        </label>
                    </div>
                <?php endif ?>

                <div class="d-grid gap-2">
                    <button 
                        type="submit" 
                        name="submit" 
                        class="btn btn-primary btn-lg" 
                        <?= isset($_COOKIE['register_lockout']) ? 'disabled="disabled"' : null ?>
                    >
                        <i class="fas fa-user-plus me-2"></i>
                        <?= l('register.register') ?>
                    </button>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Secure, passwordless registration - we'll send you login links via email
                    </small>
                </div>
            <?php endif ?>
        </form>

        <hr class="my-4">
        <div class="text-center">
            <?= sprintf(l('register.login'), '<a href="' . url('login' . $data->redirect_append) . '" class="text-decoration-none fw-bold">' . l('register.login_help') . '</a>') ?>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card border-0 bg-light">
            <div class="card-body text-center py-3">
                <h6 class="mb-2">
                    <i class="fas fa-lock text-success me-2"></i>
                    Why Passwordless?
                </h6>
                <div class="row text-start">
                    <div class="col-md-4 mb-2">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            <strong>More Secure:</strong> No passwords to steal or forget
                        </small>
                    </div>
                    <div class="col-md-4 mb-2">
                        <small class="text-muted">
                            <i class="fas fa-bolt text-success me-2"></i>
                            <strong>Faster Login:</strong> Just click the email link
                        </small>
                    </div>
                    <div class="col-md-4 mb-2">
                        <small class="text-muted">
                            <i class="fas fa-mobile-alt text-success me-2"></i>
                            <strong>Works Everywhere:</strong> Any device, any browser
                        </small>
                    </div>
                </div>
            </div>
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
