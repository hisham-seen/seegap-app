<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers;

use Altum\Alerts;

defined('ALTUMCODE') || die();

class AdminPlanCreate extends Controller {

    public function index() {

        if(in_array(settings()->license->type, ['SPECIAL', 'Extended License', 'extended'])) {
            /* Get the available taxes from the system */
            $taxes = db()->get('taxes');
        }

        $additional_domains = db()->where('is_enabled', 1)->where('type', 1)->get('domains');
        $microsites_templates = (new \Altum\Models\MicrositesTemplates())->get_microsites_templates();
        $microsites_themes = (new \Altum\Models\MicrositesThemes())->get_microsites_themes();

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['description'] = input_clean($_POST['description'], 256);

            /* Prices */
            $prices = [
                'monthly' => [],
                'quarterly' => [],
                'biannual' => [],
                'annual' => [],
                'lifetime' => [],
            ];

            foreach((array) settings()->payment->currencies as $currency => $currency_data) {
                $prices['monthly'][$currency] = (float) $_POST['monthly_price'][$currency];
                $prices['quarterly'][$currency] = (float) ($_POST['quarterly_price'][$currency] ?? 0);
                $prices['biannual'][$currency] = (float) ($_POST['biannual_price'][$currency] ?? 0);
                $prices['annual'][$currency] = (float) $_POST['annual_price'][$currency];
                $prices['lifetime'][$currency] = (float) $_POST['lifetime_price'][$currency];
            }

            $prices = json_encode($prices);

            /* Determine the enabled microsite blocks */
            $enabled_microsite_blocks = [];

            foreach(require APP_PATH . 'includes/microsite_blocks.php' as $key => $value) {
                $enabled_microsite_blocks[$key] = isset($_POST['enabled_microsite_blocks']) && in_array($key, $_POST['enabled_microsite_blocks']);
            }

            $settings = [
                'url_minimum_characters' => (int) $_POST['url_minimum_characters'],
                'url_maximum_characters' => (int) $_POST['url_maximum_characters'],
                'additional_domains' => $_POST['additional_domains'] ?? [],
                'microsites_templates' => $_POST['microsites_templates'] ?? [],
                'microsites_themes' => $_POST['microsites_themes'] ?? [],
                'custom_url' => isset($_POST['custom_url']),
                'deep_links' => isset($_POST['deep_links']),
                'no_ads' => isset($_POST['no_ads']),
                'white_labeling_is_enabled' => isset($_POST['white_labeling_is_enabled']),
                'export' => [
                    'pdf'                           => isset($_POST['export']) && in_array('pdf', $_POST['export']),
                    'csv'                           => isset($_POST['export']) && in_array('csv', $_POST['export']),
                    'json'                          => isset($_POST['export']) && in_array('json', $_POST['export']),
                ],
                'removable_branding' => isset($_POST['removable_branding']),
                'custom_branding' => isset($_POST['custom_branding']),
                'statistics' => isset($_POST['statistics']),
                'temporary_url_is_enabled' => isset($_POST['temporary_url_is_enabled']),
                'cloaking_is_enabled' => isset($_POST['cloaking_is_enabled']),
                'app_linking_is_enabled' => isset($_POST['app_linking_is_enabled']),
                'targeting_is_enabled'              => isset($_POST['targeting_is_enabled']),
                'seo' => isset($_POST['seo']),
                'utm' => isset($_POST['utm']),
                'fonts' => isset($_POST['fonts']),
                'password' => isset($_POST['password']),
                'sensitive_content' => isset($_POST['sensitive_content']),
                'leap_link' => isset($_POST['leap_link']),
                'api_is_enabled' => isset($_POST['api_is_enabled']),
                'dofollow_is_enabled' => isset($_POST['dofollow_is_enabled']),
                'custom_pwa_is_enabled' => isset($_POST['custom_pwa_is_enabled']),
                'microsite_blocks_limit' => (int) $_POST['microsite_blocks_limit'],
                'projects_limit' => (int) $_POST['projects_limit'],
                'splash_pages_limit' => (int) $_POST['splash_pages_limit'],
                'pixels_limit' => (int) $_POST['pixels_limit'],
                'qr_codes_limit' => (int) $_POST['qr_codes_limit'],
                'qr_codes_bulk_limit' => (int) $_POST['qr_codes_bulk_limit'],
                'gs1_links_limit' => (int) $_POST['gs1_links_limit'],
                'microsites_limit' => (int) $_POST['microsites_limit'],
                'links_limit' => (int) $_POST['links_limit'],
                'links_bulk_limit'                  => (int) $_POST['links_bulk_limit'],
                'files_limit' => (int) $_POST['files_limit'],
                'events_limit' => (int) $_POST['events_limit'],
                'static_limit' => (int) $_POST['static_limit'],
                'domains_limit' => (int) $_POST['domains_limit'],
                'payment_processors_limit' => (int) $_POST['payment_processors_limit'],
                'signatures_limit' => (int) $_POST['signatures_limit'],
                'teams_limit' => (int) $_POST['teams_limit'],
                'team_members_limit' => (int) $_POST['team_members_limit'],
                'affiliate_commission_percentage' => (int) $_POST['affiliate_commission_percentage'],
                'track_links_retention' => (int) $_POST['track_links_retention'],
                'custom_css_is_enabled' => isset($_POST['custom_css_is_enabled']),
                'custom_js_is_enabled' => isset($_POST['custom_js_is_enabled']),
                'enabled_microsite_blocks' => $enabled_microsite_blocks,
                'exclusive_personal_api_keys'       => isset($_POST['exclusive_personal_api_keys']),
                'documents_model'                   => $_POST['documents_model'],
                'documents_per_month_limit'         => (int) $_POST['documents_per_month_limit'],
                'words_per_month_limit'             => (int) $_POST['words_per_month_limit'],
                'images_api'                        => $_POST['images_api'],
                'images_per_month_limit'            => (int) $_POST['images_per_month_limit'],
                'transcriptions_per_month_limit'    => (int) $_POST['transcriptions_per_month_limit'],
                'transcriptions_file_size_limit'    => $_POST['transcriptions_file_size_limit'] > get_max_upload() || $_POST['transcriptions_file_size_limit'] < 0 || $_POST['transcriptions_file_size_limit'] > 25 ? (get_max_upload() > 25 ? 25 : get_max_upload()) : (float) $_POST['transcriptions_file_size_limit'],
                'chats_model'                       => $_POST['chats_model'],
                'chats_per_month_limit'             => (int) $_POST['chats_per_month_limit'],
                'chat_messages_per_chat_limit'      => (int) $_POST['chat_messages_per_chat_limit'],
                'chat_image_size_limit'    => $_POST['chat_image_size_limit'] > get_max_upload() || $_POST['chat_image_size_limit'] < 0 || $_POST['chat_image_size_limit'] > 20 ? (get_max_upload() > 20 ? 20 : get_max_upload()) : (float) $_POST['chat_image_size_limit'],
                'syntheses_api'                     => $_POST['syntheses_api'],
                'syntheses_per_month_limit'         => (int) $_POST['syntheses_per_month_limit'],
                'synthesized_characters_per_month_limit' => (int) $_POST['synthesized_characters_per_month_limit'],
            ];

            foreach(require APP_PATH . 'includes/links_types.php' as $key => $value) {
                $settings['force_splash_page_on_' . $key] = isset($_POST['force_splash_page_on_' . $key]);
            }

            $_POST['settings'] = json_encode($settings);

            $_POST['color'] = !verify_hex_color($_POST['color']) ? null : $_POST['color'];
            $_POST['status'] = (int) $_POST['status'];
            $_POST['order'] = (int) $_POST['order'];
            $_POST['trial_days'] = (int) $_POST['trial_days'];
            $_POST['taxes_ids'] = json_encode($_POST['taxes_ids'] ?? []);

            /* Translations */
            foreach($_POST['translations'] as $language_name => $array) {
                foreach($array as $key => $value) {
                    $_POST['translations'][$language_name][$key] = input_clean($value);
                }
                if(!array_key_exists($language_name, \Altum\Language::$active_languages)) {
                    unset($_POST['translations'][$language_name]);
                }
            }

            $_POST['translations'] = json_encode($_POST['translations']);

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('plans', [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'prices' => $prices,
                    'settings' => $_POST['settings'],
                    'translations' => $_POST['translations'],
                    'taxes_ids' => $_POST['taxes_ids'],
                    'color' => $_POST['color'],
                    'status' => $_POST['status'],
                    'order' => $_POST['order'],
                    'datetime' => get_date(),
                ]);

                /* Clear the cache */
                cache()->deleteItem('plans');

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('admin/plans');
            }
        }

        /* Main View */
        $data = [
            'taxes' => $taxes ?? null,
            'additional_domains' => $additional_domains,
            'microsites_templates' => $microsites_templates,
            'microsites_themes' => $microsites_themes,
        ];

        $view = new \Altum\View('admin/plan-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
