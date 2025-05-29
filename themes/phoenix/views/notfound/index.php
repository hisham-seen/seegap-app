<?php defined('ALTUMCODE') || die() ?>

<?= \Altum\Alerts::output_alerts() ?>

<h1 class="h5"><?= l('not_found.header') ?></h1>
<p class="text-muted mt-3"><?= l('not_found.subheader') ?></p>

<div class="mt-4">
    <?php if(is_logged_in()): ?>
        <a href="<?= url('dashboard') ?>" class="btn btn-primary btn-block my-1">
            <i class="fas fa-fw fa-sm fa-table-cells mr-1"></i> <?= l('dashboard.menu') ?>
        </a>
    <?php else: ?>
        <a href="<?= url() ?>" class="btn btn-primary btn-block my-1">
            <i class="fas fa-fw fa-sm fa-home mr-1"></i> <?= l('not_found.button') ?>
        </a>
    <?php endif ?>
    
    <button onclick="history.back()" class="btn btn-light btn-block mt-3">
        <i class="fas fa-fw fa-sm fa-arrow-left mr-1"></i> <?= l('global.go_back') ?>
    </button>
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
                    "name": "<?= l('not_found.title') ?>",
                    "item": "<?= url('404') ?>"
                }
            ]
        }
    </script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
