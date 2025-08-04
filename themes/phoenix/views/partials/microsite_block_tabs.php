<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Microsite Block Tab Navigation Component
 * 
 * @param array $tabs - Array of tab configurations
 * @param string $block_id - Unique identifier for the block
 * @param string $active_tab - Default active tab (optional)
 */

$tabs = $tabs ?? [];
$block_id = $block_id ?? 'default';
$active_tab = $active_tab ?? (isset($tabs[0]) ? $tabs[0]['id'] : 'content');
?>

<div class="microsite-block-tabs">
    <!-- Tab Navigation -->
    <div class="nav nav-pills nav-fill nav-minimal mb-4" id="<?= $block_id ?>-tab" role="tablist">
        <?php foreach($tabs as $index => $tab): ?>
            <a class="nav-item nav-link <?= $tab['id'] === $active_tab ? 'active' : '' ?>" 
               id="<?= $block_id ?>-<?= $tab['id'] ?>-tab" 
               data-toggle="pill" 
               href="#<?= $block_id ?>-<?= $tab['id'] ?>" 
               role="tab" 
               aria-controls="<?= $block_id ?>-<?= $tab['id'] ?>" 
               aria-selected="<?= $tab['id'] === $active_tab ? 'true' : 'false' ?>"
               data-toggle="tooltip" 
               title="<?= $tab['title'] ?>">
                <i class="<?= $tab['icon'] ?>"></i>
            </a>
        <?php endforeach ?>
    </div>
</div>

<style>
.microsite-block-tabs .nav-minimal {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 4px;
    background-color: #f8f9fa;
}

.microsite-block-tabs .nav-minimal .nav-link {
    border: none;
    border-radius: 6px;
    padding: 5px;
    margin: 0 1px;
    color: #6c757d;
    background: transparent;
    transition: all 0.2s ease;
    text-align: center;
    min-height: 30px;
    min-width: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.microsite-block-tabs .nav-minimal .nav-link:hover {
    background-color: #e9ecef;
    color: #495057;
    transform: translateY(-1px);
}

.microsite-block-tabs .nav-minimal .nav-link.active {
    background-color: #007bff;
    color: white;
    box-shadow: 0 2px 4px rgba(0,123,255,0.3);
}

.microsite-block-tabs .nav-minimal .nav-link.active:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
}

.microsite-block-tabs .nav-minimal .nav-link i {
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .microsite-block-tabs .nav-minimal .nav-link {
        padding: 6px;
        min-height: 32px;
        min-width: 32px;
    }
    
    .microsite-block-tabs .nav-minimal .nav-link i {
        font-size: 0.8rem !important;
    }
}
</style>
