<?php defined('SEEGAP') || die() ?>

<?= \SeeGap\Alerts::output_alerts() ?>

<?php
//SEEGAP:DEMO if(DEMO) {
//SEEGAP:DEMO echo '<div class="card mb-3">';
//SEEGAP:DEMO echo '<div class="card-body py-2 px-3">';
//SEEGAP:DEMO echo '<div class="h6 mb-1">Demo</div>';
//SEEGAP:DEMO echo '<div><small class="text-muted">📱 Some features are disabled as this is a demo version.</small></div>';
//SEEGAP:DEMO echo '<div><small class="text-muted">🛠️ You can login by entering your email address below.</small></div>';
//SEEGAP:DEMO echo '<div><small class="text-muted">👨‍💻 You can also register your own account to test it as a normal user</small></div>';
//SEEGAP:DEMO echo '</div>';
//SEEGAP:DEMO echo '</div>';
//SEEGAP:DEMO }
?>

<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <h1><?= l('login.header') ?></h1>
            <p class="text-muted"><?= l('login.subheader') ?></p>
        </div>

        <form action="" method="post" role="form">
            <div class="form-group">
                <label for="email"><?= l('global.email') ?></label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    class="form-control <?= \SeeGap\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" 
                    value="<?= $data->values['email'] ?>" 
                    required="required" 
                    autofocus="autofocus" 
                    placeholder="<?= l('login.email_placeholder') ?>"
                    autocomplete="email"
                />
                <?= \SeeGap\Alerts::output_field_error('email') ?>
            </div>

            <?php if(settings()->captcha->login_is_enabled): ?>
                <div class="form-group">
                    <?php $data->captcha->display() ?>
                </div>
            <?php endif ?>

            <div class="d-grid gap-2">
                <button 
                    type="submit" 
                    name="submit" 
                    class="btn btn-primary btn-lg" 
                    <?= isset($_COOKIE['login_lockout']) ? 'disabled="disabled"' : null ?>
                >
                    <i class="fas fa-envelope me-2"></i>
                    <?= l('login.send_login_link') ?>
                </button>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    We'll send you a secure login link that expires in 15 minutes
                </small>
            </div>
        </form>

        <?php if(settings()->users->email_confirmation): ?>
            <div class="text-center mt-3">
                <small>
                    <a href="<?= url('resend-activation' . $data->redirect_append) ?>" class="text-decoration-none">
                        <i class="fas fa-redo me-1"></i>
                        <?= l('login.resend_activation') ?>
                    </a>
                </small>
            </div>
        <?php endif ?>

        <?php if(settings()->users->register_is_enabled): ?>
            <hr class="my-4">
            <div class="text-center">
                <?= sprintf(l('login.register'), '<a href="' . url('register' . $data->redirect_append) . '" class="text-decoration-none fw-bold">' . l('login.register_help') . '</a>') ?>
            </div>
        <?php endif ?>
    </div>
</div>

<?php
// Development Login Window - Remove in production
if(!empty($data->values['email']) && filter_var($data->values['email'], FILTER_VALIDATE_EMAIL)) {
    // Generate login token for development
    $login_token = bin2hex(random_bytes(32));
    $login_url = SITE_URL . 'login/email-login/' . $login_token;
    
    // Update user with login token (if user exists)
    $user = db()->where('email', $data->values['email'])->getOne('users', ['user_id', 'name', 'status']);
    if($user && $user->status == 1) {
        $login_token_expiry = (new \DateTime())->modify('+15 minutes')->format('Y-m-d H:i:s');
        db()->where('user_id', $user->user_id)->update('users', [
            'login_token' => $login_token,
            'login_token_expiry' => $login_token_expiry,
            'login_token_ip' => get_ip()
        ]);
    }
?>
<div class="row justify-content-center mt-3">
    <div class="col-md-8">
        <div class="card border-warning bg-warning bg-opacity-10">
            <div class="card-body text-center py-3">
                <h6 class="mb-2 text-warning">
                    <i class="fas fa-tools me-2"></i>
                    Development Login - Remove in Production
                </h6>
                <?php if($user && $user->status == 1): ?>
                    <p class="mb-2">
                        <small class="text-muted">Login link for: <strong><?= $data->values['email'] ?></strong></small>
                    </p>
                    <a href="<?= $login_url ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        Click here to login
                    </a>
                <?php else: ?>
                    <p class="mb-0">
                        <small class="text-muted">User not found or inactive: <strong><?= $data->values['email'] ?></strong></small>
                    </p>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card border-0 bg-light">
            <div class="card-body text-center py-3">
                <h6 class="mb-2">
                    <i class="fas fa-magic text-primary me-2"></i>
                    How Email Login Works
                </h6>
                <div class="row text-start">
                    <div class="col-md-4 mb-2">
                        <small class="text-muted">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <strong>1.</strong> Enter your email address
                        </small>
                    </div>
                    <div class="col-md-4 mb-2">
                        <small class="text-muted">
                            <i class="fas fa-paper-plane text-primary me-2"></i>
                            <strong>2.</strong> We send you a secure link
                        </small>
                    </div>
                    <div class="col-md-4 mb-2">
                        <small class="text-muted">
                            <i class="fas fa-sign-in-alt text-primary me-2"></i>
                            <strong>3.</strong> Click the link to sign in
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
                    "name": "<?= l('login.title') ?>",
                    "item": "<?= url('login') ?>"
                }
            ]
        }
    </script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
