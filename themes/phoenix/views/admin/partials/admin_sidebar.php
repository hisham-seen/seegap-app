<?php defined('SEEGAP') || die() ?>

<div class="app-sidebar">
    <div class="app-sidebar-title text-truncate">
        <a
                href="<?= url('admin') ?>"
                data-logo
                data-light-value="<?= settings()->main->logo_light != '' ? settings()->main->logo_light_full_url : settings()->main->title ?>"
                data-light-class="<?= settings()->main->logo_light != '' ? 'img-fluid navbar-logo' : '' ?>"
                data-light-tag="<?= settings()->main->logo_light != '' ? 'img' : 'span' ?>"
                data-dark-value="<?= settings()->main->logo_dark != '' ? settings()->main->logo_dark_full_url : settings()->main->title ?>"
                data-dark-class="<?= settings()->main->logo_dark != '' ? 'img-fluid navbar-logo' : '' ?>"
                data-dark-tag="<?= settings()->main->logo_dark != '' ? 'img' : 'span' ?>"
        >
            <?php if(settings()->main->{'logo_' . \SeeGap\ThemeStyle::get()} != ''): ?>
                <img src="<?= settings()->main->{'logo_' . \SeeGap\ThemeStyle::get() . '_full_url'} ?>" class="img-fluid navbar-logo" alt="<?= l('global.accessibility.logo_alt') ?>" />
            <?php else: ?>
                <?= settings()->main->title ?>
            <?php endif ?>
        </a>
    </div>

    <div class="app-sidebar-links-wrapper flex-grow-1">
        <ul class="app-sidebar-links">
            <li class="<?= \SeeGap\Router::$controller == 'AdminIndex' ? 'active' : null ?>">
                <a href="<?= url('admin/') ?>"><i class="fas fa-fw fa-sm fa-fingerprint mr-2"></i> <?= l('admin_index.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminUsers', 'AdminUserUpdate', 'AdminUserCreate', 'AdminUserView']) ? 'active' : null ?>">
                <a href="<?= url('admin/users') ?>"><i class="fas fa-fw fa-sm fa-users mr-2"></i> <?= l('admin_users.menu') ?></a>
            </li>

            <li class="<?= \SeeGap\Router::$controller == 'AdminSettings' ? 'active' : null ?>">
                <a href="<?= url('admin/settings') ?>"><i class="fas fa-fw fa-sm fa-wrench mr-2"></i> <?= l('admin_settings.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminPlans', 'AdminPlanCreate', 'AdminPlanUpdate']) ? 'active' : null ?>">
                <a href="<?= url('admin/plans') ?>"><i class="fas fa-fw fa-sm fa-box-open mr-2"></i> <?= l('admin_plans.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminLanguages', 'AdminLanguageCreate', 'AdminLanguageUpdate']) ? 'active' : null ?>">
                <a href="<?= url('admin/languages') ?>"><i class="fas fa-fw fa-sm fa-language mr-2"></i> <?= l('admin_languages.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminBroadcasts', 'AdminBroadcastCreate', 'AdminBroadcastUpdate']) ? 'active' : null ?>">
                <a href="<?= url('admin/broadcasts') ?>"><i class="fas fa-fw fa-sm fa-mail-bulk mr-2"></i> <?= l('admin_broadcasts.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminInternalNotifications', 'AdminInternalNotificationCreate']) ? 'active' : null ?>">
                <a href="<?= url('admin/internal-notifications') ?>"><i class="fas fa-fw fa-sm fa-bell mr-2"></i> <?= l('admin_internal_notifications.menu') ?></a>
            </li>

            <?php if(\SeeGap\Plugin::is_active('push-notifications')): ?>
                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminPushSubscribers', 'AdminPushNotifications', 'AdminPushNotificationCreate', 'AdminPushNotificationUpdate']) ? 'active' : null ?>">
                    <a href="<?= url('admin/push-notifications') ?>"><i class="fas fa-fw fa-sm fa-bolt-lightning mr-2"></i> <?= l('admin_push_notifications.menu') ?></a>
                </li>
            <?php endif ?>



            <li class="<?= \SeeGap\Router::$controller == 'AdminStatistics' ? 'active' : null ?>">
                <a href="<?= url('admin/statistics') ?>"><i class="fas fa-fw fa-sm fa-chart-bar mr-2"></i> <?= l('admin_statistics.menu') ?></a>
            </li>

            <li class="<?= \SeeGap\Router::$controller == 'AdminApiDocumentation' ? 'active' : null ?>">
                <a href="<?= url('admin/api-documentation') ?>"><i class="fas fa-fw fa-sm fa-code mr-2"></i> <?= l('admin_api_documentation.menu') ?></a>
            </li>

            <?php if(in_array(settings()->license->type, ['SPECIAL','Extended License', 'extended'])): ?>
                <div class="divider-wrapper">
                    <div class="divider"></div>
                </div>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminCodes', 'AdminCodeCreate', 'AdminCodeUpdate']) ? 'active' : null ?>">
                    <a href="<?= url('admin/codes') ?>"><i class="fas fa-fw fa-sm fa-tags mr-2"></i> <?= l('admin_codes.menu') ?></a>
                </li>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminTaxes', 'AdminTaxCreate', 'AdminTaxUpdate']) ? 'active' : null ?>">
                    <a href="<?= url('admin/taxes') ?>"><i class="fas fa-fw fa-sm fa-paperclip mr-2"></i> <?= l('admin_taxes.menu') ?></a>
                </li>

                <li class="<?= \SeeGap\Router::$controller == 'AdminPayments' ? 'active' : null ?>">
                    <a href="<?= url('admin/payments') ?>"><i class="fas fa-fw fa-sm fa-credit-card mr-2"></i> <?= l('admin_payments.menu') ?></a>
                </li>

                <?php if(\SeeGap\Plugin::is_active('affiliate')): ?>
                    <li class="<?= \SeeGap\Router::$controller == 'AdminAffiliatesWithdrawals' ? 'active' : null ?>">
                        <a href="<?= url('admin/affiliates-withdrawals') ?>"><i class="fas fa-fw fa-sm fa-wallet mr-2"></i> <?= l('admin_affiliates_withdrawals.menu') ?></a>
                    </li>
                <?php endif ?>
            <?php endif ?>

            <div class="divider-wrapper">
                <div class="divider"></div>
            </div>

            <?php if(\SeeGap\Plugin::is_active('teams')): ?>
                <li class="<?= \SeeGap\Router::$controller == 'AdminTeams' ? 'active' : null ?>">
                    <a href="<?= url('admin/teams') ?>"><i class="fas fa-fw fa-sm fa-user-shield mr-2"></i> <?= l('admin_teams.menu') ?></a>
                </li>

                <li class="<?= \SeeGap\Router::$controller == 'AdminTeamMembers' ? 'active' : null ?>">
                    <a href="<?= url('admin/team-members') ?>"><i class="fas fa-fw fa-sm fa-user-tag mr-2"></i> <?= l('admin_team_members.menu') ?></a>
                </li>
            <?php endif ?>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminUsersLogs']) ? 'active' : null ?>">
                <a href="<?= url('admin/users-logs') ?>"><i class="fas fa-fw fa-sm fa-scroll mr-2"></i> <?= l('admin_users_logs.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminDomains', 'AdminDomainCreate', 'AdminDomainUpdate']) ? 'active' : null ?>">
                <a href="<?= url('admin/domains') ?>"><i class="fas fa-fw fa-sm fa-globe mr-2"></i> <?= l('admin_domains.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminLinks']) ? 'active' : null ?>">
                <a href="<?= url('admin/links') ?>"><i class="fas fa-fw fa-sm fa-link mr-2"></i> <?= l('admin_links.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminMicrositesBlocks']) ? 'active' : null ?>">
                <a href="<?= url('admin/microsites-blocks') ?>"><i class="fas fa-fw fa-sm fa-table-cells-large mr-2"></i> <?= l('admin_microsites_blocks.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminMicrositesThemes', 'AdminMicrositeThemeCreate', 'AdminMicrositeThemeUpdate']) ? 'active' : null ?>">
                <a href="<?= url('admin/microsites-themes') ?>"><i class="fas fa-fw fa-sm fa-palette mr-2"></i> <?= l('admin_microsites_themes.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminMicrositesTemplates', 'AdminMicrositeTemplateCreate', 'AdminMicrositeTemplateUpdate']) ? 'active' : null ?>">
                <a href="<?= url('admin/microsites-templates') ?>"><i class="fas fa-fw fa-sm fa-moon mr-2"></i> <?= l('admin_microsites_templates.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminQrCodes']) ? 'active' : null ?>">
                <a href="<?= url('admin/qr-codes') ?>"><i class="fas fa-fw fa-sm fa-qrcode mr-2"></i> <?= l('admin_qr_codes.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminGs1Links']) ? 'active' : null ?>">
                <a href="<?= url('admin/gs1-links') ?>"><i class="fas fa-fw fa-sm fa-barcode mr-2"></i> <?= l('admin_gs1_links.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminReports']) ? 'active' : null ?>">
                <a href="<?= url('admin/reports') ?>"><i class="fas fa-fw fa-sm fa-chart-bar mr-2"></i> <?= l('admin_reports.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminProjects']) ? 'active' : null ?>">
                <a href="<?= url('admin/projects') ?>"><i class="fas fa-fw fa-sm fa-project-diagram mr-2"></i> <?= l('admin_projects.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminSplashPages']) ? 'active' : null ?>">
                <a href="<?= url('admin/splash-pages') ?>"><i class="fas fa-fw fa-sm fa-droplet mr-2"></i> <?= l('admin_splash_pages.menu') ?></a>
            </li>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminPixels']) ? 'active' : null ?>">
                <a href="<?= url('admin/pixels') ?>"><i class="fas fa-fw fa-sm fa-adjust mr-2"></i> <?= l('admin_pixels.menu') ?></a>
            </li>

            <?php if(\SeeGap\Plugin::is_active('email-signatures')): ?>
                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminSignatures']) ? 'active' : null ?>">
                    <a href="<?= url('admin/signatures') ?>"><i class="fas fa-fw fa-sm fa-file-signature mr-2"></i> <?= l('admin_signatures.menu') ?></a>
                </li>
            <?php endif ?>

            <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminData']) ? 'active' : null ?>">
                <a href="<?= url('admin/data') ?>"><i class="fas fa-fw fa-sm fa-database mr-2"></i> <?= l('admin_data.menu') ?></a>
            </li>

            <?php if(\SeeGap\Plugin::is_active('payment-blocks')): ?>
                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminPaymentProcessors']) ? 'active' : null ?>">
                    <a href="<?= url('admin/payment-processors') ?>"><i class="fas fa-fw fa-sm fa-credit-card mr-2"></i> <?= l('admin_payment_processors.menu') ?></a>
                </li>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminGuestsPayments']) ? 'active' : null ?>">
                    <a href="<?= url('admin/guests-payments') ?>"><i class="fas fa-fw fa-sm fa-coins mr-2"></i> <?= l('admin_guests_payments.menu') ?></a>
                </li>
            <?php endif ?>

            <?php if(\SeeGap\Plugin::is_active('aix')): ?>
                <div class="divider-wrapper">
                    <div class="divider"></div>
                </div>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminTemplatesCategories', 'AdminTemplateCategoryCreate', 'AdminTemplateCategoryUpdate']) ? 'active' : null ?>">
                    <a href="<?= url('admin/templates-categories') ?>"><i class="fas fa-fw fa-sm fa-folder mr-2"></i> <?= l('admin_templates_categories.menu') ?></a>
                </li>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminTemplates', 'AdminTemplateCreate', 'AdminTemplateUpdate']) ? 'active' : null ?>">
                    <a href="<?= url('admin/templates') ?>"><i class="fas fa-fw fa-sm fa-moon mr-2"></i> <?= l('admin_templates.menu') ?></a>
                </li>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminDocuments']) ? 'active' : null ?>">
                    <a href="<?= url('admin/documents') ?>"><i class="fas fa-fw fa-sm fa-robot mr-2"></i> <?= l('admin_documents.menu') ?></a>
                </li>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminTranscriptions']) ? 'active' : null ?>">
                    <a href="<?= url('admin/transcriptions') ?>"><i class="fas fa-fw fa-sm fa-microphone-alt mr-2"></i> <?= l('admin_transcriptions.menu') ?></a>
                </li>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminSyntheses']) ? 'active' : null ?>">
                    <a href="<?= url('admin/syntheses') ?>"><i class="fas fa-fw fa-sm fa-voicemail mr-2"></i> <?= l('admin_syntheses.menu') ?></a>
                </li>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminChatsAssistants', 'AdminChatAssistantCreate', 'AdminChatAssistantUpdate']) ? 'active' : null ?>">
                    <a href="<?= url('admin/chats-assistants') ?>"><i class="fas fa-fw fa-sm fa-id-card-alt mr-2"></i> <?= l('admin_chats_assistants.menu') ?></a>
                </li>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminChats']) ? 'active' : null ?>">
                    <a href="<?= url('admin/chats') ?>"><i class="fas fa-fw fa-sm fa-comments mr-2"></i> <?= l('admin_chats.menu') ?></a>
                </li>

                <li class="<?= in_array(\SeeGap\Router::$controller, ['AdminImages']) ? 'active' : null ?>">
                    <a href="<?= url('admin/images') ?>"><i class="fas fa-fw fa-sm fa-icons mr-2"></i> <?= l('admin_images.menu') ?></a>
                </li>
            <?php endif ?>
        </ul>
    </div>

    <div class="app-sidebar-footer dropdown">
            <a href="#" class="dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="d-flex align-items-center app-sidebar-footer-block">
                    <img src="<?= get_gravatar($this->user->email) ?>" class="app-sidebar-avatar mr-3" loading="lazy" />

                    <div class="app-sidebar-footer-text d-flex flex-column text-truncate">
                        <span class="text-truncate"><?= $this->user->name ?></span>
                        <small class="text-truncate"><?= $this->user->email ?></small>
                    </div>
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="<?= url('account') ?>"><i class="fas fa-fw fa-sm fa-user-cog mr-2"></i> <?= l('account.menu') ?></a>

                <a class="dropdown-item" href="<?= url('account-preferences') ?>"><i class="fas fa-fw fa-sm fa-sliders-h mr-2"></i> <?= l('account_preferences.menu') ?></a>

                <a class="dropdown-item" href="<?= url('account-plan') ?>"><i class="fas fa-fw fa-sm fa-box-open mr-2"></i> <?= l('account_plan.menu') ?></a>

                <?php if(settings()->payment->is_enabled): ?>
                    <a class="dropdown-item" href="<?= url('account-payments') ?>"><i class="fas fa-fw fa-sm fa-credit-card mr-2"></i> <?= l('account_payments.menu') ?></a>

                    <?php if(\SeeGap\Plugin::is_active('affiliate')): ?>
                        <a class="dropdown-item" href="<?= url('referrals') ?>"><i class="fas fa-fw fa-sm fa-wallet mr-2"></i> <?= l('referrals.menu') ?></a>
                    <?php endif ?>
                <?php endif ?>

                <?php if(settings()->main->api_is_enabled): ?>
                    <a class="dropdown-item" href="<?= url('account-api') ?>"><i class="fas fa-fw fa-sm fa-code mr-2"></i> <?= l('account_api.menu') ?></a>
                <?php endif ?>

                <?php if(\SeeGap\Plugin::is_active('teams')): ?>
                    <a class="dropdown-item" href="<?= url('teams-system') ?>"><i class="fas fa-fw fa-sm fa-user-shield mr-2"></i> <?= l('teams_system.menu') ?></a>
                <?php endif ?>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="<?= url('dashboard') ?>"><i class="fas fa-fw fa-sm fa-th mr-2"></i> <?= l('dashboard.menu') ?></a>

                <?php if(settings()->sso->is_enabled && count((array) settings()->sso->websites)): ?>
                    <div class="dropdown-divider"></div>

                    <?php foreach(settings()->sso->websites as $website): ?>
                        <a class="dropdown-item" href="<?= url('sso/switch?to=' . $website->id . '&redirect=admin') ?>"><i class="<?= $website->icon ?> fa-fw fa-sm mr-2"></i> <?= sprintf(l('sso.menu'), $website->name) ?></a>
                    <?php endforeach ?>
                <?php endif ?>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fas fa-fw fa-sm fa-sign-out-alt mr-2"></i> <?= l('global.menu.logout') ?></a>
            </div>
        </div>

</div>

<?php ob_start() ?>
<script>
    document.querySelector('ul[class="app-sidebar-links"] li.active') && document.querySelector('ul[class="app-sidebar-links"] li.active').scrollIntoView({ behavior: 'smooth', block: 'center' });
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
