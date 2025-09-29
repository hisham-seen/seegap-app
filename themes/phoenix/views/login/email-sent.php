<?php defined('SEEGAP') || die() ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-envelope-open-text fa-4x text-primary"></i>
                </div>
                
                <h2 class="mb-3"><?= l('login.email_sent.header') ?></h2>
                <p class="text-muted mb-4"><?= l('login.email_sent.subheader') ?></p>
                
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <?= l('login.email_sent.instructions') ?>
                </div>
                
                <div class="mb-4">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        <?= l('login.email_sent.expiry_notice') ?>
                    </small>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="<?= url('login') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        <?= l('login.email_sent.back_to_login') ?>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-4 border-0 bg-light">
            <div class="card-body text-center py-3">
                <h6 class="mb-2">
                    <i class="fas fa-question-circle text-muted me-2"></i>
                    <?= l('login.email_sent.troubleshooting.header') ?>
                </h6>
                <div class="text-start">
                    <small class="text-muted">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-1">
                                <i class="fas fa-check text-success me-2"></i>
                                <?= l('login.email_sent.troubleshooting.check_spam') ?>
                            </li>
                            <li class="mb-1">
                                <i class="fas fa-check text-success me-2"></i>
                                <?= l('login.email_sent.troubleshooting.check_email') ?>
                            </li>
                            <li class="mb-1">
                                <i class="fas fa-check text-success me-2"></i>
                                <?= l('login.email_sent.troubleshooting.wait_minutes') ?>
                            </li>
                        </ul>
                    </small>
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
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "<?= l('login.email_sent.title') ?>",
                    "item": "<?= url('login/email-sent') ?>"
                }
            ]
        }
    </script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
