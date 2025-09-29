<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Secondary Sidebar Component
 * 
 * Usage:
 * $secondary_sidebar_config = [
 *     'mobile_select_name' => 'settings_menu',
 *     'mobile_select_class' => 'custom-select',
 *     'desktop_class' => 'admin-settings-sidebar',
 *     'items' => [
 *         [
 *             'type' => 'link',
 *             'url' => url('admin/settings/main'),
 *             'icon' => 'fas fa-fw fa-sm fa-home mr-2',
 *             'label' => l('admin_settings.main.tab'),
 *             'active' => $data->method == 'main',
 *             'mobile_emoji' => '🏠'
 *         ],
 *         [
 *             'type' => 'divider'
 *         ],
 *         [
 *             'type' => 'group',
 *             'label' => l('admin_settings.payment_processors'),
 *             'icon' => 'fas fa-fw fa-sm fa-piggy-bank mr-2',
 *             'collapse_id' => 'payment_processors_collapse',
 *             'active' => array_key_exists($data->method, $data->payment_processors),
 *             'items' => [...]
 *         ]
 *     ]
 * ];
 * 
 * include_view(THEME_PATH . 'views/partials/secondary_sidebar.php', ['config' => $secondary_sidebar_config]);
 */

// Configuration should be provided by the controller
if (!isset($config) || empty($config) || !isset($config['items'])) {
    $config = ['items' => []]; // Empty config if none provided
}
$mobile_select_name = isset($config['mobile_select_name']) ? $config['mobile_select_name'] : 'secondary_menu';
$mobile_select_class = isset($config['mobile_select_class']) ? $config['mobile_select_class'] : 'custom-select';
$desktop_class = isset($config['desktop_class']) ? $config['desktop_class'] : 'secondary-sidebar';
$items = isset($config['items']) ? $config['items'] : [];
?>

<div class="app-secondary-sidebar">
    
    <!-- Mobile Dropdown -->
    <div class="d-xl-none p-3">
        <select name="<?= $mobile_select_name ?>" class="<?= $mobile_select_class ?> form-control">
            <?php foreach($items as $item): ?>
                <?php if($item['type'] == 'link'): ?>
                    <option value="<?= $item['url'] ?>" class="nav-link" <?= $item['active'] ? 'selected="selected"' : null ?>>
                        <?= isset($item['mobile_emoji']) ? $item['mobile_emoji'] . ' ' : '' ?><?= $item['label'] ?>
                    </option>
                <?php elseif($item['type'] == 'group' && isset($item['items'])): ?>
                    <?php foreach($item['items'] as $sub_item): ?>
                        <?php if($sub_item['type'] == 'link'): ?>
                            <option value="<?= $sub_item['url'] ?>" class="nav-link" <?= $sub_item['active'] ? 'selected="selected"' : null ?>>
                                <?= isset($sub_item['mobile_emoji']) ? $sub_item['mobile_emoji'] . ' ' : '' ?><?= $sub_item['label'] ?>
                            </option>
                        <?php endif ?>
                    <?php endforeach ?>
                <?php endif ?>
            <?php endforeach ?>
        </select>
    </div>

    <?php ob_start() ?>
    <script>
        document.querySelector('select[name="<?= $mobile_select_name ?>"]').addEventListener('change', event => {
            if(event.currentTarget.value) {
                window.location.href = event.currentTarget.value;
            }
        })
    </script>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>

    <!-- Desktop Sidebar -->
    <div class="app-sidebar-links-wrapper d-none d-xl-flex flex-grow-1">
        <ul class="app-sidebar-links">
            <?php foreach($items as $item): ?>
                <?php if($item['type'] == 'link'): ?>
                    <li class="<?= $item['active'] ? 'active' : null ?>">
                        <a href="<?= $item['url'] ?>">
                            <?php if(isset($item['icon'])): ?>
                                <i class="<?= $item['icon'] ?>"></i>
                            <?php endif ?>
                            <?= $item['label'] ?>
                        </a>
                    </li>
                <?php elseif($item['type'] == 'divider'): ?>
                    <div class="divider-wrapper">
                        <div class="divider"></div>
                    </div>
                <?php elseif($item['type'] == 'group'): ?>
                    <li class="<?= $item['active'] ? 'active' : null ?>">
                        <a href="#" data-toggle="collapse" data-target="#<?= $item['collapse_id'] ?>">
                            <?php if(isset($item['icon'])): ?>
                                <i class="<?= $item['icon'] ?>"></i>
                            <?php endif ?>
                            <?= $item['label'] ?>
                            <i class="fas fa-fw fa-sm fa-caret-down"></i>
                        </a>
                        <div class="collapse <?= $item['active'] ? 'show' : null ?>" id="<?= $item['collapse_id'] ?>">
                            <ul class="app-sidebar-links ml-3">
                                <?php foreach($item['items'] as $sub_item): ?>
                                    <?php if($sub_item['type'] == 'link'): ?>
                                        <li class="<?= $sub_item['active'] ? 'active' : null ?>">
                                            <a href="<?= $sub_item['url'] ?>">
                                                <?php if(isset($sub_item['icon'])): ?>
                                                    <i class="<?= $sub_item['icon'] ?>"></i>
                                                <?php endif ?>
                                                <?= $sub_item['label'] ?>
                                            </a>
                                        </li>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
    </div>
</div>
