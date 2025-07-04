<?php defined('SEEGAP') || die() ?>

<?php if(settings()->links->additional_domains_is_enabled): ?>
    <?php $additional_domains = (new \SeeGap\Models\Domain())->get_available_additional_domains(); ?>
<?php endif ?>

<ul class="pricing-features">
    <?php if(settings()->links->microsites_is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.microsites_limit'), ($data->plan_settings->microsites_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->microsites_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->microsites_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>

        <li>
            <div><?= sprintf(l('global.plan_settings.microsite_blocks_limit'), ($data->plan_settings->microsite_blocks_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->microsite_blocks_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->microsite_blocks_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>

        <?php $enabled_microsite_blocks = array_filter((array) $data->plan_settings->enabled_microsite_blocks) ?>
        <?php $enabled_microsite_blocks_count = count($enabled_microsite_blocks) ?>
        <?php
        $enabled_microsite_blocks_string = implode(', ', array_map(function($key) {
            return l('link.microsite.blocks.' . mb_strtolower($key));
        }, array_keys($enabled_microsite_blocks)));
        ?>
        <li>
            <div class="<?= $enabled_microsite_blocks_count ? null : 'text-muted' ?>">
                <?php if($enabled_microsite_blocks_count == count(require APP_PATH . 'includes/enabled_microsite_blocks.php')): ?>
                    <?= l('global.plan_settings.enabled_microsite_blocks_all') ?>
                <?php else: ?>
                    <?= sprintf(l('global.plan_settings.enabled_microsite_blocks_x'), '<strong>' . nr($enabled_microsite_blocks_count) . '</strong>') ?>
                <?php endif ?>

                <span class="mr-1" data-toggle="tooltip" title="<?= $enabled_microsite_blocks_string ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
            </div>
            <i class="fas fa-fw fa-sm <?= $enabled_microsite_blocks_count ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(settings()->links->shortener_is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.links_limit'), ($data->plan_settings->links_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->links_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->links_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>

        <li>
            <div><?= sprintf(l('global.plan_settings.links_bulk_limit'), ($data->plan_settings->links_bulk_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->links_bulk_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->links_bulk_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(settings()->links->files_is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.files_limit'), ($data->plan_settings->files_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->files_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->files_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(settings()->links->events_is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.events_limit'), ($data->plan_settings->events_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->events_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->events_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(settings()->links->static_is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.static_limit'), ($data->plan_settings->static_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->static_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->static_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(settings()->codes->qr_codes_is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.qr_codes_limit'), ($data->plan_settings->qr_codes_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->qr_codes_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->qr_codes_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>

        <li>
            <div><?= sprintf(l('global.plan_settings.qr_codes_bulk_limit'), ($data->plan_settings->qr_codes_bulk_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->qr_codes_bulk_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->qr_codes_bulk_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>

        <li>
            <div><?= sprintf(l('global.plan_settings.gs1_links_limit'), ($data->plan_settings->gs1_links_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->gs1_links_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->gs1_links_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(\SeeGap\Plugin::is_active('email-signatures') && settings()->signatures->is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.signatures_limit'), ($data->plan_settings->signatures_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->signatures_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->signatures_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(settings()->links->splash_page_is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.splash_pages_limit'), ($data->plan_settings->splash_pages_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->splash_pages_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->splash_pages_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(settings()->links->pixels_is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.pixels_limit'), ($data->plan_settings->pixels_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->pixels_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->pixels_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(settings()->links->projects_is_enabled): ?>
    <li>
        <div><?= sprintf(l('global.plan_settings.projects_limit'), ($data->plan_settings->projects_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->projects_limit))) ?></div>
        <i class="fas fa-fw fa-sm <?= $data->plan_settings->projects_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
    </li>
    <?php endif ?>

    <?php if(\SeeGap\Plugin::is_active('teams')): ?>
        <li>
            <div>
                <?= sprintf(l('global.plan_settings.teams_limit'), ($data->plan_settings->teams_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->teams_limit))) ?>

                <span class="ml-1" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.plan_settings.team_members_limit'), '<strong>' . ($data->plan_settings->team_members_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->team_members_limit)) . '</strong>') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
            </div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->teams_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(\SeeGap\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.affiliate_commission_percentage'), nr($data->plan_settings->affiliate_commission_percentage) . '%') ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->affiliate_commission_percentage ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(settings()->links->domains_is_enabled): ?>
        <li>
            <div><?= sprintf(l('global.plan_settings.domains_limit'), ($data->plan_settings->domains_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->domains_limit))) ?></div>
            <i class="fas fa-fw fa-sm <?= $data->plan_settings->domains_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <li>
        <div data-toggle="tooltip" title="<?= ($data->plan_settings->track_links_retention == -1 ? '' : $data->plan_settings->track_links_retention . ' ' . l('global.date.days')) ?>"><?= sprintf(l('global.plan_settings.track_links_retention'), ($data->plan_settings->track_links_retention == -1 ? l('global.unlimited') : \SeeGap\Date::days_format($data->plan_settings->track_links_retention))) ?></div>
        <i class="fas fa-fw fa-sm <?= $data->plan_settings->track_links_retention ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
    </li>

    <?php if(settings()->links->additional_domains_is_enabled): ?>
        <li>
            <div>
                <?= sprintf(l('global.plan_settings.additional_domains'), '<strong>' . nr(count($data->plan_settings->additional_domains ?? [])) . '</strong>') ?>

                <span class="mr-1" data-toggle="tooltip" title="<?= sprintf(l('global.plan_settings.additional_domains_help'), implode(', ', array_map(function($domain_id) use($additional_domains) { return $additional_domains[$domain_id]->host ?? null; }, $data->plan_settings->additional_domains ?? []))) ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
            </div>
            <i class="fas fa-fw fa-sm <?= count($data->plan_settings->additional_domains ?? []) ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(
        \SeeGap\Plugin::is_active('aix')
        && (
            settings()->aix->documents_is_enabled || settings()->aix->images_is_enabled || settings()->aix->transcriptions_is_enabled || settings()->aix->chats_is_enabled
        )
    ): ?>
        <?php $ai_text_models = require \SeeGap\Plugin::get('aix')->path . 'includes/ai_text_models.php'; ?>

        <div class="d-flex justify-content-between align-items-center my-3">
            <button type="button" class="btn btn-sm btn-outline-light text-reset text-decoration-none font-weight-bold px-0 w-100" data-toggle="collapse" data-target=".ai_container">
                <i class="fas fa-fw fa-sm fa-robot mr-1"></i> <?= l('global.plan_settings.aix') ?>
            </button>
        </div>

        <div class="collapse ai_container">
            <?php if(\SeeGap\Plugin::is_active('aix') && settings()->aix->documents_is_enabled): ?>
                <li>
                    <div><?= $ai_text_models[$data->plan_settings->documents_model]['name'] ?></div>
                    <i class="fas fa-fw fa-sm <?= 'fa-check-circle text-success' ?>"></i>
                </li>

                <li>
                    <div><?= sprintf(l('global.plan_settings.documents_per_month_limit'), ($data->plan_settings->documents_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->documents_per_month_limit))) ?></div>
                    <i class="fas fa-fw fa-sm <?= $data->plan_settings->documents_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                </li>

                <li>
                    <div><?= sprintf(l('global.plan_settings.words_per_month_limit'), ($data->plan_settings->words_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->words_per_month_limit))) ?></div>
                    <i class="fas fa-fw fa-sm <?= $data->plan_settings->words_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                </li>
            <?php endif ?>

            <?php if(\SeeGap\Plugin::is_active('aix') && settings()->aix->images_is_enabled): ?>
                <li>
                    <div><?= sprintf(l('global.plan_settings.images_per_month_limit'), ($data->plan_settings->images_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->images_per_month_limit))) ?></div>
                    <i class="fas fa-fw fa-sm <?= $data->plan_settings->images_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                </li>
            <?php endif ?>

            <?php if(\SeeGap\Plugin::is_active('aix') && settings()->aix->transcriptions_is_enabled): ?>
                <div class="d-flex justify-content-between align-items-center my-3">
                    <div>
                        <?= sprintf(l('global.plan_settings.transcriptions_per_month_limit'), '<strong>' . ($data->plan_settings->transcriptions_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->transcriptions_per_month_limit)) . '</strong>') ?>
                    </div>
                    <i class="fas fa-fw fa-sm <?= $data->plan_settings->transcriptions_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                </div>

                <div class="d-flex justify-content-between align-items-center my-3">
                    <div>
                        <?= sprintf(l('global.plan_settings.transcriptions_file_size_limit'), '<strong>' . get_formatted_bytes($data->plan_settings->transcriptions_file_size_limit * 1000 * 1000) . '</strong>') ?>
                    </div>
                    <i class="fas fa-fw fa-sm <?= $data->plan_settings->transcriptions_file_size_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                </div>
            <?php endif ?>

            <?php if(\SeeGap\Plugin::is_active('aix') && settings()->aix->chats_is_enabled): ?>
                <div class="d-flex justify-content-between align-items-center my-3">
                    <div>
                        <?= sprintf(l('global.plan_settings.chats_per_month_limit'), '<strong>' . ($data->plan_settings->chats_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->chats_per_month_limit)) . '</strong>') ?>
                    </div>
                    <i class="fas fa-fw fa-sm <?= $data->plan_settings->chats_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                </div>

                <div class="d-flex justify-content-between align-items-center my-3">
                    <div>
                        <?= sprintf(l('global.plan_settings.chat_messages_per_chat_limit'), '<strong>' . ($data->plan_settings->chat_messages_per_chat_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->chat_messages_per_chat_limit)) . '</strong>') ?>
                    </div>
                    <i class="fas fa-fw fa-sm <?= $data->plan_settings->chat_messages_per_chat_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                </div>
            <?php endif ?>
        </div>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center my-3">
        <button type="button" class="btn btn-sm btn-outline-light text-reset text-decoration-none font-weight-bold px-0 w-100" data-toggle="collapse" data-target=".view_all_container">
            <i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('global.view_all') ?>
        </button>
    </div>

    <div class="collapse view_all_container">
        <?php if(settings()->links->microsites_is_enabled && settings()->links->microsites_themes_is_enabled): ?>
            <li>
                <div class="<?= count($data->plan_settings->microsites_themes ?? []) ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.microsites_themes'), nr(count($data->plan_settings->microsites_themes ?? []))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= count($data->plan_settings->microsites_themes ?? []) ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if(settings()->links->microsites_is_enabled && settings()->links->microsites_templates_is_enabled): ?>
            <li>
                <div class="<?= count($data->plan_settings->microsites_templates ?? []) ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.microsites_templates'), nr(count($data->plan_settings->microsites_templates ?? []))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= count($data->plan_settings->microsites_templates ?? []) ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if(settings()->links->microsites_is_enabled && \SeeGap\Plugin::is_active('payment-blocks')): ?>
            <li>
                <div><?= sprintf(l('global.plan_settings.payment_processors_limit'), ($data->plan_settings->payment_processors_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->payment_processors_limit))) ?></div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->payment_processors_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if(settings()->links->splash_page_is_enabled): ?>
            <?php
            $no_forced_splash_page = true;
            foreach(require APP_PATH . 'includes/links_types.php' as $key => $value) {
                if($data->plan_settings->{'force_splash_page_on_' . $key}) {
                    $no_forced_splash_page = false;
                    break;
                }
            }
            ?>
            <li>
                <div class="<?= $no_forced_splash_page ? null : 'text-muted' ?>">
                    <?= l('global.plan_settings.no_forced_splash_page') ?>

                    <span class="ml-1" data-toggle="tooltip" title="<?= l('global.plan_settings.no_forced_splash_page_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                </div>

                <i class="fas fa-fw fa-sm <?= $no_forced_splash_page ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php foreach(require APP_PATH . 'includes/simple_user_plan_settings.php' as $plan_setting): ?>
            <li>
                <div class="<?= $data->plan_settings->{$plan_setting} ? null : 'text-muted' ?>">
                    <?= l('global.plan_settings.' . $plan_setting) ?>

                    <span class="ml-1" data-toggle="tooltip" title="<?= l('global.plan_settings.' . $plan_setting . '_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                </div>

                <i class="fas fa-fw fa-sm <?= $data->plan_settings->{$plan_setting} ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endforeach ?>

        <?php $enabled_exports_count = count(array_filter((array) $data->plan_settings->export)); ?>

        <?php ob_start() ?>
        <div class='d-flex flex-column'>
            <?php foreach(['csv', 'json', 'pdf'] as $key): ?>
                <?php if($data->plan_settings->export->{$key}): ?>
                    <span class='my-1'><?= sprintf(l('global.export_to'), mb_strtoupper($key)) ?></span>
                <?php else: ?>
                    <s class='my-1'><?= sprintf(l('global.export_to'), mb_strtoupper($key)) ?></s>
                <?php endif ?>
            <?php endforeach ?>
        </div>
        <?php $html = ob_get_clean() ?>

        <div class="d-flex justify-content-between align-items-center my-3 <?= $enabled_exports_count ? null : 'text-muted' ?>">
            <div>
                <?= sprintf(l('global.plan_settings.export'), $enabled_exports_count) ?>
                <span class="mr-1" data-html="true" data-toggle="tooltip" title="<?= $html ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
            </div>

            <i class="fas fa-fw fa-sm <?= $enabled_exports_count ? 'fa-check-circle text-success' : 'fa-times-circle' ?>"></i>
        </div>
    </div>
</ul>
