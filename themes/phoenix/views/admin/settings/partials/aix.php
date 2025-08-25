<?php defined('SEEGAP') || die() ?>

<div>
    <h2 class="h5">OpenAI</h2>
    <p class="text-muted">Used for <code>NSFW moderation</code>, <code>AI Documents</code>, <code>AI Images</code>, <code>AI Transcriptions</code>, <code>AI Syntheses</code>, <code>AI Chats</code>.</p>
    <div class="form-group">
        <label for="openai_api_key"><?= l('admin_settings.aix.openai_api_key') ?></label>
        <textarea id="openai_api_key" name="openai_api_key" class="form-control"><?= settings()->aix->openai_api_key ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.aix.openai_api_key_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="input_moderation_is_enabled" name="input_moderation_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->input_moderation_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="input_moderation_is_enabled"><?= l('admin_settings.aix.input_moderation_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.aix.input_moderation_is_enabled_help') ?></small>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#documents_container" aria-expanded="false" aria-controls="documents_container">
        <i class="fas fa-fw fa-robot fa-sm mr-1"></i> <?= l('admin_documents.menu') ?>
    </button>

    <div class="collapse" id="documents_container">
        <div class="form-group custom-control custom-switch">
            <input id="documents_is_enabled" name="documents_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->documents_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="documents_is_enabled"><?= l('admin_settings.aix.documents_is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="documents_available_languages"><?= l('admin_settings.aix.documents_available_languages') ?></label>
            <textarea id="documents_available_languages" type="text" name="documents_available_languages" class="form-control" rows="5"><?= implode(',', settings()->aix->documents_available_languages ?? []) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.aix.documents_available_languages_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#images_container" aria-expanded="false" aria-controls="images_container">
        <i class="fas fa-fw fa-icons fa-sm mr-1"></i> <?= l('admin_images.menu') ?>
    </button>

    <div class="collapse" id="images_container">
        <div class="form-group custom-control custom-switch">
            <input id="images_is_enabled" name="images_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->images_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="images_is_enabled"><?= l('admin_settings.aix.images_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="images_display_latest_on_index" name="images_display_latest_on_index" type="checkbox" class="custom-control-input" <?= settings()->aix->images_display_latest_on_index ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="images_display_latest_on_index"><?= l('admin_settings.aix.images_display_latest_on_index') ?></label>
        </div>

        <div class="form-group">
            <label for="images_available_artists"><?= l('admin_settings.aix.images_available_artists') ?></label>
            <textarea id="images_available_artists" type="text" name="images_available_artists" class="form-control" rows="5"><?= implode(',', settings()->aix->images_available_artists ?? []) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.aix.images_available_artists_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#transcriptions_container" aria-expanded="false" aria-controls="transcriptions_container">
        <i class="fas fa-fw fa-microphone-alt fa-sm mr-1"></i> <?= l('admin_transcriptions.menu') ?>
    </button>

    <div class="collapse" id="transcriptions_container">
        <div class="form-group custom-control custom-switch">
            <input id="transcriptions_is_enabled" name="transcriptions_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->transcriptions_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="transcriptions_is_enabled"><?= l('admin_settings.aix.transcriptions_is_enabled') ?></label>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#chats_container" aria-expanded="false" aria-controls="chats_container">
        <i class="fas fa-fw fa-comments fa-sm mr-1"></i> <?= l('admin_chats.menu') ?>
    </button>

    <div class="collapse" id="chats_container">
        <div class="form-group custom-control custom-switch">
            <input id="chats_is_enabled" name="chats_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->chats_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="chats_is_enabled"><?= l('admin_settings.aix.chats_is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="chats_assistant_name"><?= l('admin_settings.aix.chats_assistant_name') ?></label>
            <input id="chats_assistant_name" type="text" name="chats_assistant_name" value="<?= settings()->aix->chats_assistant_name ?>" class="form-control" />
        </div>

        <div class="form-group">
            <label for="chats_avatar"><i class="fas fa-fw fa-sm fa-user-circle text-muted mr-1"></i> <?= l('admin_settings.aix.chats_avatar') ?></label>
            <?php if(!empty(settings()->aix->chats_avatar)): ?>
                <div class="m-1">
                    <img src="<?= \SeeGap\Uploads::get_full_url('chats_assistants') . settings()->aix->chats_avatar ?>" class="img-fluid" style="max-height: 2.5rem;height: 2.5rem;" />
                </div>
                <div class="custom-control custom-checkbox my-2">
                    <input id="chats_avatar_remove" name="chats_avatar_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#chats_avatar').classList.add('d-none') : document.querySelector('#chats_avatar').classList.remove('d-none')">
                    <label class="custom-control-label" for="chats_avatar_remove">
                        <span class="text-muted"><?= l('global.delete_file') ?></span>
                    </label>
                </div>
            <?php endif ?>
            <input id="chats_avatar" type="file" name="chats_avatar" accept="<?= \SeeGap\Uploads::get_whitelisted_file_extensions_accept('chats_assistants') ?>" class="form-control-file seegap-file-input" />
            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::get_whitelisted_file_extensions_accept('chats_assistants')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#syntheses_container" aria-expanded="false" aria-controls="syntheses_container">
        <i class="fas fa-fw fa-voicemail fa-sm mr-1"></i> <?= l('admin_syntheses.menu') ?>
    </button>

    <div class="collapse" id="syntheses_container">
        <div class="form-group custom-control custom-switch">
            <input id="syntheses_is_enabled" name="syntheses_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->syntheses_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="syntheses_is_enabled"><?= l('admin_settings.aix.syntheses_is_enabled') ?></label>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#receipt_analysis_container" aria-expanded="false" aria-controls="receipt_analysis_container">
        <i class="fas fa-fw fa-receipt fa-sm mr-1"></i> Receipt Analysis
    </button>

    <div class="collapse" id="receipt_analysis_container">
        <div class="form-group custom-control custom-switch">
            <input id="receipt_analysis_is_enabled" name="receipt_analysis_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->receipt_analysis_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="receipt_analysis_is_enabled">Enable Receipt Analysis</label>
            <small class="form-text text-muted">Allow AI-powered analysis of receipt images uploaded through forms</small>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <h6 class="text-muted">AI Providers</h6>
                
                <div class="form-group custom-control custom-switch">
                    <input id="receipt_openai_is_enabled" name="receipt_openai_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->receipt_openai_is_enabled ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="receipt_openai_is_enabled">OpenAI GPT-4 Vision</label>
                </div>

                <div class="form-group">
                    <label for="receipt_openai_model">OpenAI Model</label>
                    <select id="receipt_openai_model" name="receipt_openai_model" class="form-control">
                        <option value="gpt-4-vision-preview" <?= settings()->aix->receipt_openai_model == 'gpt-4-vision-preview' ? 'selected' : '' ?>>GPT-4 Vision Preview</option>
                        <option value="gpt-4o" <?= settings()->aix->receipt_openai_model == 'gpt-4o' ? 'selected' : '' ?>>GPT-4o</option>
                        <option value="gpt-4o-mini" <?= settings()->aix->receipt_openai_model == 'gpt-4o-mini' ? 'selected' : '' ?>>GPT-4o Mini</option>
                    </select>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="receipt_google_is_enabled" name="receipt_google_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->receipt_google_is_enabled ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="receipt_google_is_enabled">Google Gemini Vision</label>
                </div>

                <div class="form-group">
                    <label for="receipt_google_api_key">Google API Key</label>
                    <input id="receipt_google_api_key" type="password" name="receipt_google_api_key" value="<?= settings()->aix->receipt_google_api_key ?? '' ?>" class="form-control" />
                    <small class="form-text text-muted">Required for Google Gemini Vision analysis</small>
                </div>

                <div class="form-group">
                    <label for="receipt_google_model">Google Model</label>
                    <select id="receipt_google_model" name="receipt_google_model" class="form-control">
                        <option value="gemini-pro-vision" <?= (settings()->aix->receipt_google_model ?? 'gemini-pro-vision') == 'gemini-pro-vision' ? 'selected' : '' ?>>Gemini Pro Vision</option>
                        <option value="gemini-1.5-pro" <?= (settings()->aix->receipt_google_model ?? '') == 'gemini-1.5-pro' ? 'selected' : '' ?>>Gemini 1.5 Pro</option>
                    </select>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="receipt_anthropic_is_enabled" name="receipt_anthropic_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->receipt_anthropic_is_enabled ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="receipt_anthropic_is_enabled">Anthropic Claude Vision</label>
                </div>

                <div class="form-group">
                    <label for="receipt_anthropic_api_key">Anthropic API Key</label>
                    <input id="receipt_anthropic_api_key" type="password" name="receipt_anthropic_api_key" value="<?= settings()->aix->receipt_anthropic_api_key ?? '' ?>" class="form-control" />
                    <small class="form-text text-muted">Required for Claude Vision analysis</small>
                </div>

                <div class="form-group">
                    <label for="receipt_anthropic_model">Anthropic Model</label>
                    <select id="receipt_anthropic_model" name="receipt_anthropic_model" class="form-control">
                        <option value="claude-3-opus-20240229" <?= (settings()->aix->receipt_anthropic_model ?? 'claude-3-opus-20240229') == 'claude-3-opus-20240229' ? 'selected' : '' ?>>Claude 3 Opus</option>
                        <option value="claude-3-sonnet-20240229" <?= (settings()->aix->receipt_anthropic_model ?? '') == 'claude-3-sonnet-20240229' ? 'selected' : '' ?>>Claude 3 Sonnet</option>
                        <option value="claude-3-haiku-20240307" <?= (settings()->aix->receipt_anthropic_model ?? '') == 'claude-3-haiku-20240307' ? 'selected' : '' ?>>Claude 3 Haiku</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-6">
                <h6 class="text-muted">Processing Settings</h6>
                
                <div class="form-group">
                    <label for="receipt_default_provider">Default Provider</label>
                    <select id="receipt_default_provider" name="receipt_default_provider" class="form-control">
                        <option value="openai" <?= (settings()->aix->receipt_default_provider ?? 'openai') == 'openai' ? 'selected' : '' ?>>OpenAI</option>
                        <option value="google" <?= (settings()->aix->receipt_default_provider ?? '') == 'google' ? 'selected' : '' ?>>Google</option>
                        <option value="anthropic" <?= (settings()->aix->receipt_default_provider ?? '') == 'anthropic' ? 'selected' : '' ?>>Anthropic</option>
                    </select>
                    <small class="form-text text-muted">Primary provider to use for receipt analysis</small>
                </div>

                <div class="form-group">
                    <label for="receipt_max_retries">Max Retries</label>
                    <input id="receipt_max_retries" type="number" name="receipt_max_retries" value="<?= settings()->aix->receipt_max_retries ?? 3 ?>" class="form-control" min="1" max="10" />
                    <small class="form-text text-muted">Number of retry attempts if analysis fails</small>
                </div>

                <div class="form-group">
                    <label for="receipt_timeout">Timeout (seconds)</label>
                    <input id="receipt_timeout" type="number" name="receipt_timeout" value="<?= settings()->aix->receipt_timeout ?? 30 ?>" class="form-control" min="10" max="120" />
                    <small class="form-text text-muted">Maximum time to wait for AI response</small>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="receipt_queue_processing_is_enabled" name="receipt_queue_processing_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->receipt_queue_processing_is_enabled ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="receipt_queue_processing_is_enabled">Background Processing</label>
                    <small class="form-text text-muted">Process receipts in background queue for better performance</small>
                </div>

                <div class="form-group">
                    <label for="receipt_max_concurrent_jobs">Max Concurrent Jobs</label>
                    <input id="receipt_max_concurrent_jobs" type="number" name="receipt_max_concurrent_jobs" value="<?= settings()->aix->receipt_max_concurrent_jobs ?? 5 ?>" class="form-control" min="1" max="20" />
                    <small class="form-text text-muted">Maximum number of receipts to process simultaneously</small>
                </div>

                <h6 class="text-muted mt-4">Data Extraction</h6>
                
                <div class="form-group custom-control custom-switch">
                    <input id="receipt_extract_items" name="receipt_extract_items" type="checkbox" class="custom-control-input" <?= (settings()->aix->receipt_extract_items ?? true) ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="receipt_extract_items">Extract Items & Prices</label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="receipt_extract_totals" name="receipt_extract_totals" type="checkbox" class="custom-control-input" <?= (settings()->aix->receipt_extract_totals ?? true) ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="receipt_extract_totals">Extract Totals & Tax</label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="receipt_extract_merchant" name="receipt_extract_merchant" type="checkbox" class="custom-control-input" <?= (settings()->aix->receipt_extract_merchant ?? true) ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="receipt_extract_merchant">Extract Merchant Info</label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="receipt_extract_date" name="receipt_extract_date" type="checkbox" class="custom-control-input" <?= (settings()->aix->receipt_extract_date ?? true) ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="receipt_extract_date">Extract Date & Time</label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="receipt_extract_payment" name="receipt_extract_payment" type="checkbox" class="custom-control-input" <?= (settings()->aix->receipt_extract_payment ?? true) ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="receipt_extract_payment">Extract Payment Method</label>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
