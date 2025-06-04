<?php defined('SEEGAP') || die() ?>
<?php $has_announcements = false ?>
<?php foreach(['guests', 'users'] as $type): ?>
    <?php if(
        settings()->announcements->{$type . '_is_enabled'}
        && settings()->announcements->{$type . '_content'}
        && (!isset($_COOKIE['announcement_' . $type . '_id']) || (isset($_COOKIE['announcement_' . $type . '_id']) && $_COOKIE['announcement_' . $type . '_id'] != settings()->announcements->{$type . '_id'}))
        && (
            ($type == 'guests' && !is_logged_in())
            || ($type == 'users' && is_logged_in())
        )
    ): ?>
        <?php
        $has_announcements = true;

        $announcement_content = settings()->announcements->translations->{\SeeGap\Language::$name}->{$type . '_content'} ?: settings()->announcements->{$type . '_content'};

        /* Dynamic variables processing */
        $replacers = [
            '{{WEBSITE_TITLE}}' => settings()->main->title,
            '{{USER:NAME}}' => is_logged_in() ? \SeeGap\Authentication::$user->name : '',
            '{{USER:EMAIL}}' => is_logged_in() ? \SeeGap\Authentication::$user->email : '',
            '{{USER:CONTINENT_NAME}}' => is_logged_in() ? get_continent_from_continent_code(\SeeGap\Authentication::$user->continent_code) : '',
            '{{USER:COUNTRY_NAME}}' => is_logged_in() ? get_country_from_country_code(\SeeGap\Authentication::$user->country) : '',
            '{{USER:CITY_NAME}}' => is_logged_in() ? \SeeGap\Authentication::$user->city_name : '',
            '{{USER:DEVICE_TYPE}}' => is_logged_in() ? l('global.device.' . \SeeGap\Authentication::$user->device_type) : '',
            '{{USER:OS_NAME}}' => is_logged_in() ? \SeeGap\Authentication::$user->os_name : '',
            '{{USER:BROWSER_NAME}}' => is_logged_in() ? \SeeGap\Authentication::$user->browser_name : '',
            '{{USER:BROWSER_LANGUAGE}}' => is_logged_in() ? get_language_from_locale(\SeeGap\Authentication::$user->browser_language) : '',
        ];

        $announcement_content = str_replace(
            array_keys($replacers),
            array_values($replacers),
            $announcement_content
        );

        $announcement_content = process_spintax($announcement_content);

        ?>

        <div data-announcement="<?= $type ?>" class="announcement-wrapper py-3" style="background-color: <?= settings()->announcements->{$type . '_background_color'} ?>;">
            <div class="container d-flex justify-content-center position-relative">
                <div class="row w-100">
                    <div class="col">
                        <div class="text-center" style="color: <?= settings()->announcements->{$type . '_text_color'} ?>;"><?= $announcement_content ?></div>
                    </div>
                    <div class="col-auto px-0">
                        <div>
                            <button data-announcement-close="<?= $type ?>" data-announcement-id="<?= settings()->announcements->{$type . '_id'} ?>" type="button" class="close ml-2" data-dismiss="alert" data-toggle="tooltip" title="<?= l('global.close') ?>" data-tooltip-hide-on-click>
                                <i class="fas fa-sm fa-times" style="color: <?= settings()->announcements->{$type . '_text_color'} ?>; opacity: .5;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
<?php endforeach ?>

<?php if($has_announcements): ?>
    <?php ob_start() ?>
    <script>
        document.querySelector('[data-announcement-close]').addEventListener('click', event => {
            let type = event.currentTarget.getAttribute('data-announcement-close');
            let id = event.currentTarget.getAttribute('data-announcement-id');
            document.querySelector(`[data-announcement="${type}"]`).style.display = 'none';
            set_cookie(`announcement_${type}_id`, id, 15, <?= json_encode(COOKIE_PATH) ?>);
        })
    </script>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
