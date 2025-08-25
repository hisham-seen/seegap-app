<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Reusable Form Block Form Panel
 * 
 * This component provides the complete form structure for form blocks,
 * including primary tabs (Content, Style, Integrations, Metadata, Display) 
 * and secondary tabs within Style.
 * 
 * @param string $block_id - Unique identifier for the block (e.g., 'create' or actual block ID)
 * @param object $settings - Block settings object with default values
 * @param object $row - Block row data (for update form) or mock object (for create modal)
 * @param string $form_type - 'create' or 'update' to determine form behavior
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$form_type = $form_type ?? 'update';
$row = $row ?? (object)['microsite_block_id' => $block_id, 'settings' => $settings];

// Set up default settings for create form
if ($form_type === 'create') {
    $default_settings = (object) [
        'name' => '',
        'display_mode' => 'inline',
        'form_heading' => '',
        'form_text' => '',
        'questions' => [],
        'button_text' => l('global.submit'),
        'success_text' => l('global.success_message.basic'),
        'thank_you_url' => '',
        'email_notification' => '',
        'webhook_url' => '',
        'show_agreement' => false,
        'agreement_text' => '',
        'agreement_url' => '',
        'image' => '',
        'icon' => '',
        'text_color' => '#000000',
        'background_color' => '#ffffff',
        'text_alignment' => 'center',
        'border_radius' => 0,
        'border_width' => 0,
        'border_style' => 'solid',
        'border_color' => '#000000',
        'border_shadow_offset_x' => 0,
        'border_shadow_offset_y' => 0,
        'border_shadow_blur' => 0,
        'border_shadow_spread' => 0,
        'border_shadow_color' => '#00000010',
        'animation' => false,
        'animation_runs' => 'repeat-1',
        'animation_delay' => 0,
        'metadata_capture' => new \stdClass(),
        'data_retention_days' => 365,
        'anonymize_after_days' => 90,
        'gdpr_consent_required' => false,
        'display_continents' => [],
        'display_countries' => [],
        'display_cities' => [],
        'display_devices' => [],
        'display_languages' => [],
        'display_operating_systems' => [],
        'display_browsers' => []
    ];
    
    // Merge with any provided settings
    foreach ($default_settings as $key => $value) {
        if (!isset($settings->$key)) {
            $settings->$key = $value;
        }
    }
    $row->settings = $settings;
}

// Generate unique IDs based on block_id
$unique_id = $form_type === 'create' ? 'create' : $row->microsite_block_id;
?>

<?php
// Define primary tabs for the form block
$primary_tabs = [
    [
        'id' => 'content',
        'title' => 'Content',
        'icon' => 'fas fa-edit'
    ],
    [
        'id' => 'style',
        'title' => 'Style',
        'icon' => 'fas fa-palette'
    ],
    [
        'id' => 'integrations',
        'title' => 'Integrations',
        'icon' => 'fas fa-plug'
    ],
    [
        'id' => 'metadata',
        'title' => 'Metadata',
        'icon' => 'fas fa-database'
    ],
    [
        'id' => 'display',
        'title' => 'Display',
        'icon' => 'fas fa-eye'
    ]
];

// Set the block_id for the primary tab component
$primary_tab_block_id = 'form-' . $unique_id;

// Temporarily set variables for primary tabs
$block_id = $primary_tab_block_id;
$tabs = $primary_tabs;

// Include the reusable tab navigation
include THEME_PATH . 'views/partials/microsite_block_tabs.php';
?>

<div class="tab-content" id="form-<?= $unique_id ?>-tabContent">
    
    <!-- Content Tab -->
    <div class="tab-pane fade show active" id="form-<?= $unique_id ?>-content" role="tabpanel" aria-labelledby="form-<?= $unique_id ?>-content-tab">
        
        <div class="form-group">
            <label for="<?= 'form_name_' . $unique_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.name') ?></label>
            <input type="text" id="<?= 'form_name_' . $unique_id ?>" name="name" class="form-control" value="<?= $row->settings->name ?? '' ?>" maxlength="128" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'display_mode_' . $unique_id ?>"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.display_mode') ?></label>
            <select id="<?= 'display_mode_' . $unique_id ?>" name="display_mode" class="form-control">
                <option value="inline" <?= ($row->settings->display_mode ?? 'inline') == 'inline' ? 'selected="selected"' : null ?>><?= l('microsite_form.display_mode.inline') ?></option>
                <option value="button" <?= ($row->settings->display_mode ?? 'inline') == 'button' ? 'selected="selected"' : null ?>><?= l('microsite_form.display_mode.button') ?></option>
            </select>
            <small class="form-text text-muted"><?= l('microsite_form.input.display_mode_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'form_heading_' . $unique_id ?>"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.form_heading') ?></label>
            <input type="text" id="<?= 'form_heading_' . $unique_id ?>" name="form_heading" class="form-control" value="<?= $row->settings->form_heading ?? '' ?>" maxlength="128" placeholder="<?= $form_type === 'create' ? l('microsite_form.input.form_heading_placeholder') : '' ?>" />
            <?php if ($form_type === 'create'): ?>
                <small class="form-text text-muted"><?= l('microsite_form.input.form_heading_help') ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="<?= 'form_text_' . $unique_id ?>"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.form_text') ?></label>
            <textarea id="<?= 'form_text_' . $unique_id ?>" name="form_text" class="form-control" maxlength="2048" rows="3" placeholder="<?= $form_type === 'create' ? l('microsite_form.input.form_text_placeholder') : '' ?>"><?= $row->settings->form_text ?? '' ?></textarea>
            <?php if ($form_type === 'create'): ?>
                <small class="form-text text-muted"><?= l('microsite_form.input.form_text_help') ?></small>
            <?php endif; ?>
        </div>

        <!-- Form Questions (Accordion-style) -->
        <?php
        $questions_block_id = $unique_id;
        $questions = $row->settings->questions ?? [];
        $container_id = 'questions_container_' . $unique_id;
        $max_questions = 50;
        ?>
        
        <div class="form-group">
            <label><i class="fas fa-fw fa-question fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.questions') ?></label>
            <div id="<?= $container_id ?>" data-microsite-block-id="<?= $questions_block_id ?>">
                <?php if(!empty($questions)): ?>
                    <?php foreach($questions as $key => $question): ?>
                        <div class="question-item-wrapper mb-3 border rounded" data-repeater-item>
                            <!-- Drag Handle and Header -->
                            <div class="question-item-header d-flex align-items-center justify-content-between p-3 bg-light border-bottom" style="cursor: pointer;" data-toggle="collapse" data-target="#question-item-content-<?= $questions_block_id ?>-<?= $key ?>">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;" title="Drag to reorder"></i>
                                    <i class="fas fa-chevron-down question-toggle-icon mr-2 text-muted"></i>
                                    <span class="question-item-title font-weight-medium"><?= htmlspecialchars($question->question ?? 'New Question') ?></span>
                                    <small class="text-muted ml-2">(<?= ucfirst($question->type ?? 'text') ?>)</small>
                                    <?php if($question->required ?? false): ?>
                                        <span class="badge badge-danger badge-sm ml-1">Required</span>
                                    <?php endif ?>
                                </div>
                                <button type="button" data-remove="question" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();">
                                    <i class="fas fa-fw fa-times"></i>
                                </button>
                            </div>

                            <!-- Collapsible Content -->
                            <div id="question-item-content-<?= $questions_block_id ?>-<?= $key ?>" class="collapse">
                                <div class="p-3">
                                    <!-- Question Type -->
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.question_type') ?></label>
                                        <select name="question_type[<?= $key ?>]" class="form-control question-type-select">
                                            <option value="text" <?= ($question->type ?? 'text') == 'text' ? 'selected' : '' ?>><?= l('microsite_form.question_type.text') ?></option>
                                            <option value="textarea" <?= ($question->type ?? 'text') == 'textarea' ? 'selected' : '' ?>><?= l('microsite_form.question_type.textarea') ?></option>
                                            <option value="email" <?= ($question->type ?? 'text') == 'email' ? 'selected' : '' ?>><?= l('microsite_form.question_type.email') ?></option>
                                            <option value="phone" <?= ($question->type ?? 'text') == 'phone' ? 'selected' : '' ?>><?= l('microsite_form.question_type.phone') ?></option>
                                            <option value="rating_star" <?= ($question->type ?? 'text') == 'rating_star' ? 'selected' : '' ?>><?= l('microsite_form.question_type.rating_star') ?></option>
                                            <option value="rating_number" <?= ($question->type ?? 'text') == 'rating_number' ? 'selected' : '' ?>><?= l('microsite_form.question_type.rating_number') ?></option>
                                            <option value="rating_emoji" <?= ($question->type ?? 'text') == 'rating_emoji' ? 'selected' : '' ?>><?= l('microsite_form.question_type.rating_emoji') ?></option>
                                            <option value="checkbox" <?= ($question->type ?? 'text') == 'checkbox' ? 'selected' : '' ?>><?= l('microsite_form.question_type.checkbox') ?></option>
                                            <option value="radio" <?= ($question->type ?? 'text') == 'radio' ? 'selected' : '' ?>><?= l('microsite_form.question_type.radio') ?></option>
                                            <option value="dropdown" <?= ($question->type ?? 'text') == 'dropdown' ? 'selected' : '' ?>><?= l('microsite_form.question_type.dropdown') ?></option>
                                            <option value="receipt_upload" <?= ($question->type ?? 'text') == 'receipt_upload' ? 'selected' : '' ?>><?= l('microsite_form.question_type.receipt_upload') ?></option>
                                        </select>
                                    </div>

                                    <!-- Question Text -->
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.question_text') ?></label>
                                        <input type="text" name="question_text[<?= $key ?>]" class="form-control question-text-input" value="<?= htmlspecialchars($question->question ?? '') ?>" placeholder="Enter question text..." maxlength="256" required />
                                    </div>

                                    <!-- Question Description/Help Text -->
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-info-circle fa-sm text-muted mr-1"></i> Question Description (Optional)</label>
                                        <textarea name="question_description[<?= $key ?>]" class="form-control" rows="3" placeholder="Add helpful description or instructions for this question..."><?= $question->description ?? '' ?></textarea>
                                        <small class="form-text text-muted">Optional help text that will appear below the question.</small>
                                    </div>
                                    
                                    <!-- Required Checkbox -->
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="question_required[<?= $key ?>]" value="1" class="custom-control-input question-required-input" id="question_required_<?= $questions_block_id ?>_<?= $key ?>" <?= ($question->required ?? false) ? 'checked' : '' ?> />
                                            <label class="custom-control-label" for="question_required_<?= $questions_block_id ?>_<?= $key ?>">
                                                <i class="fas fa-fw fa-asterisk fa-sm text-danger mr-1"></i> Required Field
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Check this to make this question mandatory for form submission.</small>
                                    </div>

                                    <!-- Conditional Fields for Choice-based Questions -->
                                    <?php if(in_array($question->type ?? 'text', ['checkbox', 'radio', 'dropdown'])): ?>
                                        <div class="form-group question-choices-group">
                                            <label><i class="fas fa-fw fa-list-ul fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.question_choices') ?></label>
                                            <textarea name="question_choices[<?= $key ?>]" class="form-control" rows="4" placeholder="<?= l('microsite_form.input.question_choices_help') ?>"><?= isset($question->options->choices) ? implode("\n", $question->options->choices) : '' ?></textarea>
                                            <small class="form-text text-muted">Enter each choice on a new line. These will be the available options for users to select from.</small>
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" name="question_choices[<?= $key ?>]" value="">
                                    <?php endif ?>

                                    <!-- Conditional Fields for Rating Questions -->
                                    <?php if(in_array($question->type ?? 'text', ['rating_star', 'rating_number'])): ?>
                                        <div class="form-group question-rating-group">
                                            <label><i class="fas fa-fw fa-star fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.question_max_rating') ?></label>
                                            <select name="question_max_rating[<?= $key ?>]" class="form-control">
                                                <?php for($i = 3; $i <= 10; $i++): ?>
                                                    <option value="<?= $i ?>" <?= (isset($question->options->max_rating) && $question->options->max_rating == $i) ? 'selected' : ($i == 5 ? 'selected' : '') ?>><?= $i ?></option>
                                                <?php endfor ?>
                                            </select>
                                            <small class="form-text text-muted">Set the maximum rating value (e.g., 5 for 1-5 stars).</small>
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" name="question_max_rating[<?= $key ?>]" value="5">
                                    <?php endif ?>

                                    <!-- Conditional Fields for Receipt Upload Questions -->
                                    <?php if(($question->type ?? 'text') == 'receipt_upload'): ?>
                                        <div class="form-group question-receipt-group">
                                            <div class="card">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-fw fa-receipt mr-2"></i>
                                                        Receipt Upload Settings
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <!-- AI Analysis Toggle -->
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="question_ai_analysis[<?= $key ?>]" value="1" class="custom-control-input question-ai-analysis-input" id="question_ai_analysis_<?= $questions_block_id ?>_<?= $key ?>" <?= ($question->options->ai_analysis_enabled ?? false) ? 'checked' : '' ?> />
                                                            <label class="custom-control-label" for="question_ai_analysis_<?= $questions_block_id ?>_<?= $key ?>">
                                                                <i class="fas fa-fw fa-brain fa-sm text-primary mr-1"></i> Enable AI Analysis
                                                            </label>
                                                        </div>
                                                        <small class="form-text text-muted">Automatically analyze receipt data using AI when users upload images.</small>
                                                    </div>

                                                    <!-- AI Analysis Settings (shown when enabled) -->
                                                    <div class="ai-analysis-settings" style="display: <?= ($question->options->ai_analysis_enabled ?? false) ? 'block' : 'none' ?>">
                                                        <!-- AI Providers -->
                                                        <div class="form-group">
                                                            <label><i class="fas fa-fw fa-cloud fa-sm text-muted mr-1"></i> AI Providers</label>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="question_ai_providers[<?= $key ?>][]" value="openai" class="custom-control-input" id="ai_provider_openai_<?= $questions_block_id ?>_<?= $key ?>" <?= (isset($question->options->ai_providers) && in_array('openai', $question->options->ai_providers)) ? 'checked' : 'checked' ?> />
                                                                        <label class="custom-control-label" for="ai_provider_openai_<?= $questions_block_id ?>_<?= $key ?>">
                                                                            <strong>OpenAI GPT-4 Vision</strong><br>
                                                                            <small class="text-muted">High accuracy, good for complex receipts</small>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="question_ai_providers[<?= $key ?>][]" value="google" class="custom-control-input" id="ai_provider_google_<?= $questions_block_id ?>_<?= $key ?>" <?= (isset($question->options->ai_providers) && in_array('google', $question->options->ai_providers)) ? 'checked' : '' ?> />
                                                                        <label class="custom-control-label" for="ai_provider_google_<?= $questions_block_id ?>_<?= $key ?>">
                                                                            <strong>Google Gemini Pro Vision</strong><br>
                                                                            <small class="text-muted">Fast processing, multilingual support</small>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="question_ai_providers[<?= $key ?>][]" value="anthropic" class="custom-control-input" id="ai_provider_anthropic_<?= $questions_block_id ?>_<?= $key ?>" <?= (isset($question->options->ai_providers) && in_array('anthropic', $question->options->ai_providers)) ? 'checked' : '' ?> />
                                                                        <label class="custom-control-label" for="ai_provider_anthropic_<?= $questions_block_id ?>_<?= $key ?>">
                                                                            <strong>Anthropic Claude 3</strong><br>
                                                                            <small class="text-muted">Detailed analysis, good reasoning</small>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <small class="form-text text-muted">Select one or more AI providers. Multiple providers provide redundancy and improved accuracy.</small>
                                                        </div>

                                                        <!-- Data Extraction Options -->
                                                        <div class="form-group">
                                                            <label><i class="fas fa-fw fa-extract fa-sm text-muted mr-1"></i> Data to Extract</label>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="question_extract_items[<?= $key ?>]" value="1" class="custom-control-input" id="extract_items_<?= $questions_block_id ?>_<?= $key ?>" <?= ($question->options->extract_items ?? true) ? 'checked' : '' ?> />
                                                                        <label class="custom-control-label" for="extract_items_<?= $questions_block_id ?>_<?= $key ?>">
                                                                            <i class="fas fa-fw fa-list fa-sm mr-1"></i> Items & Prices
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="question_extract_totals[<?= $key ?>]" value="1" class="custom-control-input" id="extract_totals_<?= $questions_block_id ?>_<?= $key ?>" <?= ($question->options->extract_totals ?? true) ? 'checked' : '' ?> />
                                                                        <label class="custom-control-label" for="extract_totals_<?= $questions_block_id ?>_<?= $key ?>">
                                                                            <i class="fas fa-fw fa-calculator fa-sm mr-1"></i> Totals & Subtotals
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="question_extract_merchant[<?= $key ?>]" value="1" class="custom-control-input" id="extract_merchant_<?= $questions_block_id ?>_<?= $key ?>" <?= ($question->options->extract_merchant ?? true) ? 'checked' : '' ?> />
                                                                        <label class="custom-control-label" for="extract_merchant_<?= $questions_block_id ?>_<?= $key ?>">
                                                                            <i class="fas fa-fw fa-store fa-sm mr-1"></i> Merchant Info
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="question_extract_date[<?= $key ?>]" value="1" class="custom-control-input" id="extract_date_<?= $questions_block_id ?>_<?= $key ?>" <?= ($question->options->extract_date ?? true) ? 'checked' : '' ?> />
                                                                        <label class="custom-control-label" for="extract_date_<?= $questions_block_id ?>_<?= $key ?>">
                                                                            <i class="fas fa-fw fa-calendar fa-sm mr-1"></i> Date & Time
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="question_extract_payment_method[<?= $key ?>]" value="1" class="custom-control-input" id="extract_payment_<?= $questions_block_id ?>_<?= $key ?>" <?= ($question->options->extract_payment_method ?? false) ? 'checked' : '' ?> />
                                                                        <label class="custom-control-label" for="extract_payment_<?= $questions_block_id ?>_<?= $key ?>">
                                                                            <i class="fas fa-fw fa-credit-card fa-sm mr-1"></i> Payment Method
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="question_extract_tax[<?= $key ?>]" value="1" class="custom-control-input" id="extract_tax_<?= $questions_block_id ?>_<?= $key ?>" <?= ($question->options->extract_tax ?? false) ? 'checked' : '' ?> />
                                                                        <label class="custom-control-label" for="extract_tax_<?= $questions_block_id ?>_<?= $key ?>">
                                                                            <i class="fas fa-fw fa-percentage fa-sm mr-1"></i> Tax Information
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <small class="form-text text-muted">Choose what information to extract from receipts. More data extraction may take longer to process.</small>
                                                        </div>

                                                        <!-- Processing Settings -->
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><i class="fas fa-fw fa-tachometer-alt fa-sm text-muted mr-1"></i> Processing Priority</label>
                                                                    <select name="question_processing_priority[<?= $key ?>]" class="form-control">
                                                                        <option value="low" <?= ($question->options->processing_priority ?? 'normal') == 'low' ? 'selected' : '' ?>>Low (Slower, cheaper)</option>
                                                                        <option value="normal" <?= ($question->options->processing_priority ?? 'normal') == 'normal' ? 'selected' : '' ?>>Normal (Balanced)</option>
                                                                        <option value="high" <?= ($question->options->processing_priority ?? 'normal') == 'high' ? 'selected' : '' ?>>High (Faster, premium)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><i class="fas fa-fw fa-redo fa-sm text-muted mr-1"></i> Auto-Retry</label>
                                                                    <div class="custom-control custom-switch">
                                                                        <input type="checkbox" name="question_auto_retry[<?= $key ?>]" value="1" class="custom-control-input" id="auto_retry_<?= $questions_block_id ?>_<?= $key ?>" <?= ($question->options->auto_retry ?? true) ? 'checked' : '' ?> />
                                                                        <label class="custom-control-label" for="auto_retry_<?= $questions_block_id ?>_<?= $key ?>">Enable auto-retry on failure</label>
                                                                    </div>
                                                                    <select name="question_max_retries[<?= $key ?>]" class="form-control mt-2">
                                                                        <option value="1" <?= ($question->options->max_retries ?? 3) == 1 ? 'selected' : '' ?>>1 retry</option>
                                                                        <option value="2" <?= ($question->options->max_retries ?? 3) == 2 ? 'selected' : '' ?>>2 retries</option>
                                                                        <option value="3" <?= ($question->options->max_retries ?? 3) == 3 ? 'selected' : '' ?>>3 retries</option>
                                                                        <option value="5" <?= ($question->options->max_retries ?? 3) == 5 ? 'selected' : '' ?>>5 retries</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- File Upload Settings -->
                                                    <div class="form-group">
                                                        <label><i class="fas fa-fw fa-upload fa-sm text-muted mr-1"></i> Upload Settings</label>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label class="small">Max File Size</label>
                                                                <select name="question_max_file_size[<?= $key ?>]" class="form-control">
                                                                    <option value="1" <?= ($question->options->max_file_size ?? 5) == 1 ? 'selected' : '' ?>>1 MB</option>
                                                                    <option value="2" <?= ($question->options->max_file_size ?? 5) == 2 ? 'selected' : '' ?>>2 MB</option>
                                                                    <option value="5" <?= ($question->options->max_file_size ?? 5) == 5 ? 'selected' : '' ?>>5 MB</option>
                                                                    <option value="10" <?= ($question->options->max_file_size ?? 5) == 10 ? 'selected' : '' ?>>10 MB</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="small">Camera Quality</label>
                                                                <select name="question_camera_quality[<?= $key ?>]" class="form-control">
                                                                    <option value="low" <?= ($question->options->camera_quality ?? 'high') == 'low' ? 'selected' : '' ?>>Low (Faster)</option>
                                                                    <option value="medium" <?= ($question->options->camera_quality ?? 'high') == 'medium' ? 'selected' : '' ?>>Medium</option>
                                                                    <option value="high" <?= ($question->options->camera_quality ?? 'high') == 'high' ? 'selected' : '' ?>>High (Better OCR)</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="custom-control custom-switch mt-4">
                                                                    <input type="checkbox" name="question_multiple_uploads[<?= $key ?>]" value="1" class="custom-control-input" id="multiple_uploads_<?= $questions_block_id ?>_<?= $key ?>" <?= ($question->options->multiple_uploads ?? false) ? 'checked' : '' ?> />
                                                                    <label class="custom-control-label" for="multiple_uploads_<?= $questions_block_id ?>_<?= $key ?>">Multiple uploads</label>
                                                                </div>
                                                                <select name="question_max_uploads[<?= $key ?>]" class="form-control mt-1" style="display: <?= ($question->options->multiple_uploads ?? false) ? 'block' : 'none' ?>">
                                                                    <option value="1" <?= ($question->options->max_uploads ?? 3) == 1 ? 'selected' : '' ?>>1 file</option>
                                                                    <option value="2" <?= ($question->options->max_uploads ?? 3) == 2 ? 'selected' : '' ?>>2 files</option>
                                                                    <option value="3" <?= ($question->options->max_uploads ?? 3) == 3 ? 'selected' : '' ?>>3 files</option>
                                                                    <option value="5" <?= ($question->options->max_uploads ?? 3) == 5 ? 'selected' : '' ?>>5 files</option>
                                                                    <option value="10" <?= ($question->options->max_uploads ?? 3) == 10 ? 'selected' : '' ?>>10 files</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <small class="form-text text-muted">Supported formats: JPG, PNG, PDF, HEIC, WebP. Higher quality images provide better AI analysis results.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <div class="question-item-wrapper mb-3 border rounded" data-repeater-item>
                        <!-- Drag Handle and Header -->
                        <div class="question-item-header d-flex align-items-center justify-content-between p-3 bg-light border-bottom" style="cursor: pointer;" data-toggle="collapse" data-target="#question-item-content-<?= $questions_block_id ?>-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;" title="Drag to reorder"></i>
                                <i class="fas fa-chevron-down question-toggle-icon mr-2 text-muted"></i>
                                <span class="question-item-title font-weight-medium">New Question</span>
                                <small class="text-muted ml-2">(Text)</small>
                            </div>
                            <button type="button" data-remove="question" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();">
                                <i class="fas fa-fw fa-times"></i>
                            </button>
                        </div>

                        <!-- Collapsible Content -->
                        <div id="question-item-content-<?= $questions_block_id ?>-0" class="collapse show">
                            <div class="p-3">
                                <!-- Question Type -->
                                <div class="form-group">
                                    <label><i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.question_type') ?></label>
                                    <select name="question_type[0]" class="form-control question-type-select">
                                        <option value="text" selected><?= l('microsite_form.question_type.text') ?></option>
                                        <option value="textarea"><?= l('microsite_form.question_type.textarea') ?></option>
                                        <option value="email"><?= l('microsite_form.question_type.email') ?></option>
                                        <option value="phone"><?= l('microsite_form.question_type.phone') ?></option>
                                        <option value="rating_star"><?= l('microsite_form.question_type.rating_star') ?></option>
                                        <option value="rating_number"><?= l('microsite_form.question_type.rating_number') ?></option>
                                        <option value="rating_emoji"><?= l('microsite_form.question_type.rating_emoji') ?></option>
                                        <option value="checkbox"><?= l('microsite_form.question_type.checkbox') ?></option>
                                        <option value="radio"><?= l('microsite_form.question_type.radio') ?></option>
                                        <option value="dropdown"><?= l('microsite_form.question_type.dropdown') ?></option>
                                        <option value="receipt_upload">Receipt Upload</option>
                                    </select>
                                </div>

                                <!-- Question Text -->
                                <div class="form-group">
                                    <label><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.question_text') ?></label>
                                    <input type="text" name="question_text[0]" class="form-control question-text-input" placeholder="Enter question text..." maxlength="256" required />
                                </div>

                                <!-- Question Description/Help Text -->
                                <div class="form-group">
                                    <label><i class="fas fa-fw fa-info-circle fa-sm text-muted mr-1"></i> Question Description (Optional)</label>
                                    <textarea name="question_description[0]" class="form-control" rows="3" placeholder="Add helpful description or instructions for this question..."></textarea>
                                    <small class="form-text text-muted">Optional help text that will appear below the question.</small>
                                </div>
                                
                                <!-- Required Checkbox -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="question_required[0]" value="1" class="custom-control-input question-required-input" id="question_required_<?= $questions_block_id ?>_0" />
                                        <label class="custom-control-label" for="question_required_<?= $questions_block_id ?>_0">
                                            <i class="fas fa-fw fa-asterisk fa-sm text-danger mr-1"></i> Required Field
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Check this to make this question mandatory for form submission.</small>
                                </div>

                                <!-- Hidden fields for conditional content -->
                                <input type="hidden" name="question_choices[0]" value="">
                                <input type="hidden" name="question_max_rating[0]" value="5">
                            </div>
                        </div>
                    </div>
                <?php endif ?>
            </div>
            <button data-add="form_question" data-microsite-block-id="<?= $questions_block_id ?>" data-max-items="<?= $max_questions ?>" type="button" class="btn btn-outline-success btn-block mt-3">
                <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> Add Question
            </button>
            <small class="form-text text-muted">Create form questions with various input types. You can add up to <?= $max_questions ?> questions.</small>
        </div>

        <!-- Template for new questions -->
        <template id="template_form_question_<?= $questions_block_id ?>">
            <div class="question-item-wrapper mb-3 border rounded" data-repeater-item>
                <!-- Drag Handle and Header -->
                <div class="question-item-header d-flex align-items-center justify-content-between p-3 bg-light border-bottom" style="cursor: pointer;" data-toggle="collapse" data-target="#question-item-content-<?= $questions_block_id ?>-{index}">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;" title="Drag to reorder"></i>
                        <i class="fas fa-chevron-down question-toggle-icon mr-2 text-muted"></i>
                        <span class="question-item-title font-weight-medium">New Question</span>
                        <small class="text-muted ml-2">(Text)</small>
                    </div>
                    <button type="button" data-remove="question" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();">
                        <i class="fas fa-fw fa-times"></i>
                    </button>
                </div>

                <!-- Collapsible Content -->
                <div id="question-item-content-<?= $questions_block_id ?>-{index}" class="collapse show">
                    <div class="p-3">
                        <!-- Question Type -->
                        <div class="form-group">
                            <label><i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.question_type') ?></label>
                            <select name="question_type[]" class="form-control question-type-select">
                                <option value="text" selected><?= l('microsite_form.question_type.text') ?></option>
                                <option value="textarea"><?= l('microsite_form.question_type.textarea') ?></option>
                                <option value="email"><?= l('microsite_form.question_type.email') ?></option>
                                <option value="phone"><?= l('microsite_form.question_type.phone') ?></option>
                                <option value="rating_star"><?= l('microsite_form.question_type.rating_star') ?></option>
                                <option value="rating_number"><?= l('microsite_form.question_type.rating_number') ?></option>
                                <option value="rating_emoji"><?= l('microsite_form.question_type.rating_emoji') ?></option>
                                <option value="checkbox"><?= l('microsite_form.question_type.checkbox') ?></option>
                                <option value="radio"><?= l('microsite_form.question_type.radio') ?></option>
                                <option value="dropdown"><?= l('microsite_form.question_type.dropdown') ?></option>
                                <option value="receipt_upload">Receipt Upload</option>
                            </select>
                        </div>

                        <!-- Question Text -->
                        <div class="form-group">
                            <label><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.question_text') ?></label>
                            <input type="text" name="question_text[]" class="form-control question-text-input" placeholder="Enter question text..." maxlength="256" required />
                        </div>

                        <!-- Question Description/Help Text -->
                        <div class="form-group">
                            <label><i class="fas fa-fw fa-info-circle fa-sm text-muted mr-1"></i> Question Description (Optional)</label>
                            <textarea name="question_description[]" class="form-control" rows="3" placeholder="Add helpful description or instructions for this question..."></textarea>
                            <small class="form-text text-muted">Optional help text that will appear below the question.</small>
                        </div>
                        
                        <!-- Required Checkbox -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="question_required[]" value="1" class="custom-control-input question-required-input" id="question_required_<?= $questions_block_id ?>_{index}" />
                                <label class="custom-control-label" for="question_required_<?= $questions_block_id ?>_{index}">
                                    <i class="fas fa-fw fa-asterisk fa-sm text-danger mr-1"></i> Required Field
                                </label>
                            </div>
                            <small class="form-text text-muted">Check this to make this question mandatory for form submission.</small>
                        </div>

                        <!-- Conditional Fields (Hidden by default) -->
                        <div class="form-group question-choices-group" style="display: none;">
                            <label><i class="fas fa-fw fa-list-ul fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.question_choices') ?></label>
                            <textarea name="question_choices[]" class="form-control" rows="4" placeholder="<?= l('microsite_form.input.question_choices_help') ?>"></textarea>
                            <small class="form-text text-muted">Enter each choice on a new line. These will be the available options for users to select from.</small>
                        </div>
                        
                        <div class="form-group question-rating-group" style="display: none;">
                            <label><i class="fas fa-fw fa-star fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.question_max_rating') ?></label>
                            <select name="question_max_rating[]" class="form-control">
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5" selected>5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                            <small class="form-text text-muted">Set the maximum rating value (e.g., 5 for 1-5 stars).</small>
                        </div>

                        <!-- Hidden fields for non-applicable question types -->
                        <input type="hidden" name="question_choices_hidden[]" value="">
                        <input type="hidden" name="question_max_rating_hidden[]" value="5">
                    </div>
                </div>
            </div>
        </template>

        <div class="form-group">
            <label for="<?= 'button_text_' . $unique_id ?>"><i class="fas fa-fw fa-mouse-pointer fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.button_text') ?></label>
            <input type="text" id="<?= 'button_text_' . $unique_id ?>" name="button_text" class="form-control" value="<?= $row->settings->button_text ?? l('global.submit') ?>" maxlength="64" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'success_text_' . $unique_id ?>"><i class="fas fa-fw fa-check fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.success_text') ?></label>
            <input type="text" id="<?= 'success_text_' . $unique_id ?>" name="success_text" class="form-control" value="<?= $row->settings->success_text ?? l('global.success_message.basic') ?>" maxlength="256" />
        </div>

        <div class="form-group">
            <label for="<?= 'thank_you_url_' . $unique_id ?>"><i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.thank_you_url') ?></label>
            <input type="url" id="<?= 'thank_you_url_' . $unique_id ?>" name="thank_you_url" class="form-control" value="<?= $row->settings->thank_you_url ?? '' ?>" maxlength="2048" />
            <small class="form-text text-muted"><?= l('microsite_form.input.thank_you_url_help') ?></small>
        </div>

        <div class="custom-control custom-switch my-3">
            <input id="<?= 'show_agreement_' . $unique_id ?>" name="show_agreement" type="checkbox" class="custom-control-input" <?= ($row->settings->show_agreement ?? false) ? 'checked="checked"' : null ?>>
            <label class="custom-control-label" for="<?= 'show_agreement_' . $unique_id ?>"><?= l('microsite_form.input.show_agreement') ?></label>
            <small class="form-text text-muted"><?= l('microsite_form.input.show_agreement_help') ?></small>
        </div>

        <div id="<?= 'agreement_container_' . $unique_id ?>" style="display: <?= ($row->settings->show_agreement ?? false) ? 'block' : 'none' ?>">
            <div class="form-group">
                <label for="<?= 'agreement_text_' . $unique_id ?>"><i class="fas fa-fw fa-file-contract fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.agreement_text') ?></label>
                <input type="text" id="<?= 'agreement_text_' . $unique_id ?>" name="agreement_text" class="form-control" value="<?= $row->settings->agreement_text ?? '' ?>" maxlength="256" />
            </div>

            <div class="form-group">
                <label for="<?= 'agreement_url_' . $unique_id ?>"><i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.agreement_url') ?></label>
                <input type="url" id="<?= 'agreement_url_' . $unique_id ?>" name="agreement_url" class="form-control" value="<?= $row->settings->agreement_url ?? '' ?>" maxlength="2048" />
            </div>
        </div>

    </div>

    <!-- Style Tab -->
    <div class="tab-pane fade" id="form-<?= $unique_id ?>-style" role="tabpanel" aria-labelledby="form-<?= $unique_id ?>-style-tab">
        
        <?php
        // Define secondary tabs for the style section - matching Image Block pattern
        $style_tabs = [
            [
                'id' => 'styling',
                'title' => 'Styling',
                'icon' => 'fas fa-paint-brush'
            ],
            [
                'id' => 'background',
                'title' => 'Background',
                'icon' => 'fas fa-fill'
            ],
            [
                'id' => 'border',
                'title' => 'Border',
                'icon' => 'fas fa-border-style'
            ],
            [
                'id' => 'shadow',
                'title' => 'Shadow',
                'icon' => 'fas fa-clone'
            ],
            [
                'id' => 'animation',
                'title' => 'Animation',
                'icon' => 'fas fa-film'
            ]
        ];

        // Set the block_id for the secondary tab component
        $secondary_block_id = 'form-style-' . $unique_id;
        $tabs = $style_tabs; // Use style tabs for the secondary navigation
        $block_id = $secondary_block_id; // Override block_id for secondary tabs
        
        // Include the reusable tab navigation for secondary tabs
        include THEME_PATH . 'views/partials/microsite_block_tabs.php';
        ?>

        <div class="tab-content" id="form-style-<?= $unique_id ?>-tabContent">
            
            <!-- Styling Sub-tab -->
            <div class="tab-pane fade show active" id="form-style-<?= $unique_id ?>-styling" role="tabpanel" aria-labelledby="form-style-<?= $unique_id ?>-styling-tab">
                
                <!-- Image Upload -->
                <?php
                $image_block_id = $unique_id;
                $field_name = 'image';
                $current_image = $row->settings->image ?? '';
                $accept_types = ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'];
                $label = l('microsite_form.input.image');
                $icon = 'fas fa-image';
                
                // Temporarily set block_id for image upload component
                $original_block_id = $block_id ?? null;
                $block_id = $image_block_id;
                include THEME_PATH . 'views/partials/microsite_block_components/image_upload.php';
                $block_id = $original_block_id; // Restore original block_id
                ?>

                <!-- Icon -->
                <div class="form-group">
                    <label for="<?= 'icon_' . $unique_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.icon') ?></label>
                    <input type="text" id="<?= 'icon_' . $unique_id ?>" name="icon" class="form-control" value="<?= $row->settings->icon ?? '' ?>" placeholder="<?= l('microsite_form.input.icon_placeholder') ?>" />
                    <small class="form-text text-muted"><?= l('microsite_form.input.icon_help') ?></small>
                </div>

                <?php
                // Text Color
                $block_id = $unique_id;
                $field_name = 'text_color';
                $label = l('microsite_form.input.text_color');
                $icon = 'fas fa-paint-brush';
                $default_color = '#000000';
                $current_color = $row->settings->text_color ?? $default_color;
                include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                ?>
                
                <!-- Text Alignment -->
                <div class="form-group">
                    <label for="<?= 'block_text_alignment_' . $unique_id ?>"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('microsite_link.text_alignment') ?></label>
                    <div class="row btn-group-toggle" data-toggle="buttons">
                        <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                            <div class="col-6">
                                <label class="btn btn-light btn-block text-truncate <?= ($row->settings->text_alignment ?? 'center') == $text_alignment ? 'active' : '' ?>">
                                    <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($row->settings->text_alignment ?? 'center') == $text_alignment ? 'checked="checked"' : '' ?> onchange="updateCanvasText('<?= $unique_id ?>')" />
                                    <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= l('microsite_link.text_alignment.' . $text_alignment) ?>
                                </label>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <!-- Form Display Mode Customization -->
                <div class="form-group">
                    <label><i class="fas fa-fw fa-cog fa-sm text-muted mr-1"></i> Form Display Customization</label>
                    <div class="card">
                        <div class="card-body">
                            <!-- Inline Form Styling -->
                            <div class="form-group">
                                <label><i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> Inline Form Style</label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <div class="col-6">
                                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->inline_form_style ?? 'card') == 'card' ? 'active' : '' ?>">
                                            <input type="radio" name="inline_form_style" value="card" class="custom-control-input" <?= ($row->settings->inline_form_style ?? 'card') == 'card' ? 'checked="checked"' : '' ?> onchange="updateCanvasFormStyle('<?= $unique_id ?>')" />
                                            <i class="fas fa-fw fa-square fa-sm mr-1"></i> Card Style
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->inline_form_style ?? 'card') == 'minimal' ? 'active' : '' ?>">
                                            <input type="radio" name="inline_form_style" value="minimal" class="custom-control-input" <?= ($row->settings->inline_form_style ?? 'card') == 'minimal' ? 'checked="checked"' : '' ?> onchange="updateCanvasFormStyle('<?= $unique_id ?>')" />
                                            <i class="fas fa-fw fa-minus fa-sm mr-1"></i> Minimal
                                        </label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Choose how the inline form appears: Card style with background/borders, or Minimal with just form fields.</small>
                            </div>

                            <!-- Modal Form Styling -->
                            <div class="form-group">
                                <label><i class="fas fa-fw fa-window-maximize fa-sm text-muted mr-1"></i> Modal Form Style</label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <div class="col-4">
                                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->modal_form_style ?? 'standard') == 'standard' ? 'active' : '' ?>">
                                            <input type="radio" name="modal_form_style" value="standard" class="custom-control-input" <?= ($row->settings->modal_form_style ?? 'standard') == 'standard' ? 'checked="checked"' : '' ?> onchange="updateCanvasFormStyle('<?= $unique_id ?>')" />
                                            <i class="fas fa-fw fa-window-maximize fa-sm mr-1"></i> Standard
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->modal_form_style ?? 'standard') == 'fullscreen' ? 'active' : '' ?>">
                                            <input type="radio" name="modal_form_style" value="fullscreen" class="custom-control-input" <?= ($row->settings->modal_form_style ?? 'standard') == 'fullscreen' ? 'checked="checked"' : '' ?> onchange="updateCanvasFormStyle('<?= $unique_id ?>')" />
                                            <i class="fas fa-fw fa-expand fa-sm mr-1"></i> Fullscreen
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->modal_form_style ?? 'standard') == 'sidebar' ? 'active' : '' ?>">
                                            <input type="radio" name="modal_form_style" value="sidebar" class="custom-control-input" <?= ($row->settings->modal_form_style ?? 'standard') == 'sidebar' ? 'checked="checked"' : '' ?> onchange="updateCanvasFormStyle('<?= $unique_id ?>')" />
                                            <i class="fas fa-fw fa-columns fa-sm mr-1"></i> Sidebar
                                        </label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Choose modal appearance: Standard popup, Fullscreen overlay, or Sidebar slide-in.</small>
                            </div>

                            <!-- Button Trigger Style (for modal mode) -->
                            <div class="form-group">
                                <label><i class="fas fa-fw fa-mouse-pointer fa-sm text-muted mr-1"></i> Button Trigger Style</label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <div class="col-4">
                                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->button_trigger_style ?? 'button') == 'button' ? 'active' : '' ?>">
                                            <input type="radio" name="button_trigger_style" value="button" class="custom-control-input" <?= ($row->settings->button_trigger_style ?? 'button') == 'button' ? 'checked="checked"' : '' ?> onchange="updateCanvasFormStyle('<?= $unique_id ?>')" />
                                            <i class="fas fa-fw fa-square fa-sm mr-1"></i> Button
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->button_trigger_style ?? 'button') == 'link' ? 'active' : '' ?>">
                                            <input type="radio" name="button_trigger_style" value="link" class="custom-control-input" <?= ($row->settings->button_trigger_style ?? 'button') == 'link' ? 'checked="checked"' : '' ?> onchange="updateCanvasFormStyle('<?= $unique_id ?>')" />
                                            <i class="fas fa-fw fa-link fa-sm mr-1"></i> Link
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->button_trigger_style ?? 'button') == 'icon' ? 'active' : '' ?>">
                                            <input type="radio" name="button_trigger_style" value="icon" class="custom-control-input" <?= ($row->settings->button_trigger_style ?? 'button') == 'icon' ? 'checked="checked"' : '' ?> onchange="updateCanvasFormStyle('<?= $unique_id ?>')" />
                                            <i class="fas fa-fw fa-icons fa-sm mr-1"></i> Icon Only
                                        </label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Choose how the modal trigger appears: Standard button, text link, or icon only.</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Background Sub-tab -->
            <div class="tab-pane fade" id="form-style-<?= $unique_id ?>-background" role="tabpanel" aria-labelledby="form-style-<?= $unique_id ?>-background-tab">
                <?php
                // Background Color
                $block_id = $unique_id;
                $field_name = 'background_color';
                $label = l('microsite_form.input.background_color');
                $icon = 'fas fa-fill';
                $default_color = '#ffffff';
                $current_color = $row->settings->background_color ?? $default_color;
                $include_opacity = true;
                include THEME_PATH . 'views/partials/microsite_block_components/color_picker.php';
                ?>
            </div>

            <!-- Border Sub-tab -->
            <div class="tab-pane fade" id="form-style-<?= $unique_id ?>-border" role="tabpanel" aria-labelledby="form-style-<?= $unique_id ?>-border-tab">
                <?php
                // Set up variables for border component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
                ?>
            </div>

            <!-- Shadow Sub-tab -->
            <div class="tab-pane fade" id="form-style-<?= $unique_id ?>-shadow" role="tabpanel" aria-labelledby="form-style-<?= $unique_id ?>-shadow-tab">
                <?php
                // Set up variables for shadow component (without accordion)
                $block_id = $unique_id;
                $settings = $row->settings;
                $use_accordion = false; // Disable accordion when used in tabs
                include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
                ?>
            </div>

            <!-- Animation Sub-tab -->
            <div class="tab-pane fade" id="form-style-<?= $unique_id ?>-animation" role="tabpanel" aria-labelledby="form-style-<?= $unique_id ?>-animation-tab">
                <?php
                // Set up variables for animation component (without accordion)
                $component_block_id = $unique_id;
                $component_settings = $row->settings;
                
                // Include animation settings directly without accordion wrapper
                ?>
                <div class="form-group">
                    <label for="<?= 'animation_' . $component_block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation') ?></label>
                    <select id="<?= 'animation_' . $component_block_id ?>" name="animation" class="form-control" onchange="updateCanvasAnimation('<?= $component_block_id ?>')">
                        <option value="false" <?= (!isset($component_settings->animation) || !$component_settings->animation) ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                        <?php foreach(require APP_PATH . 'includes/microsite_animations.php' as $animation): ?>
                            <option value="<?= $animation ?>" <?= (isset($component_settings->animation) && $component_settings->animation == $animation) ? 'selected="selected"' : null ?>><?= l('microsite_animations.' . $animation) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="<?= 'animation_runs_' . $component_block_id ?>"><i class="fas fa-fw fa-play fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_runs') ?></label>
                    <select id="<?= 'animation_runs_' . $component_block_id ?>" name="animation_runs" class="form-control" onchange="updateCanvasAnimation('<?= $component_block_id ?>')">
                        <option value="repeat-1" <?= (!isset($component_settings->animation_runs) || $component_settings->animation_runs == 'repeat-1') ? 'selected="selected"' : null ?>>1</option>
                        <option value="repeat-2" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'repeat-2') ? 'selected="selected"' : null ?>>2</option>
                        <option value="repeat-3" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'repeat-3') ? 'selected="selected"' : null ?>>3</option>
                        <option value="infinite" <?= (isset($component_settings->animation_runs) && $component_settings->animation_runs == 'infinite') ? 'selected="selected"' : null ?>><?= l('global.infinite') ?></option>
                    </select>
                </div>

                <div class="form-group" data-range-counter data-range-counter-suffix="ms">
                    <label for="<?= 'animation_delay_' . $component_block_id ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_link.animation_delay') ?></label>
                    <input id="<?= 'animation_delay_' . $component_block_id ?>" type="range" min="0" max="5000" step="100" class="form-control-range" name="animation_delay" value="<?= $component_settings->animation_delay ?? 0 ?>" required="required" onchange="updateCanvasAnimation('<?= $component_block_id ?>')" oninput="updateCanvasAnimation('<?= $component_block_id ?>')" />
                </div>
            </div>

        </div>

    </div>

    <!-- Integrations Tab -->
    <div class="tab-pane fade" id="form-<?= $unique_id ?>-integrations" role="tabpanel" aria-labelledby="form-<?= $unique_id ?>-integrations-tab">
        
        <div class="form-group">
            <label for="<?= 'email_notification_' . $unique_id ?>"><i class="fas fa-fw fa-bell fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.email_notification') ?></label>
            <input type="email" id="<?= 'email_notification_' . $unique_id ?>" name="email_notification" class="form-control" value="<?= $row->settings->email_notification ?? '' ?>" maxlength="320" />
            <small class="form-text text-muted"><?= l('microsite_form.input.email_notification_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'webhook_url_' . $unique_id ?>"><i class="fas fa-fw fa-satellite-dish fa-sm text-muted mr-1"></i> <?= l('microsite_form.input.webhook_url') ?></label>
            <input type="url" id="<?= 'webhook_url_' . $unique_id ?>" name="webhook_url" class="form-control" value="<?= $row->settings->webhook_url ?? '' ?>" maxlength="2048" />
            <small class="form-text text-muted"><?= l('microsite_form.input.webhook_url_help') ?></small>
        </div>

    </div>

    <!-- Metadata Tab -->
    <div class="tab-pane fade" id="form-<?= $unique_id ?>-metadata" role="tabpanel" aria-labelledby="form-<?= $unique_id ?>-metadata-tab">
        
        <div class="alert alert-info">
            <i class="fas fa-fw fa-info-circle"></i>
            <?= l('microsite_form.metadata.description') ?>
        </div>

        <!-- Essential Data (Always Enabled) -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="fas fa-fw fa-check-circle mr-2"></i>
                    <?= l('microsite_form.metadata.essential_data') ?>
                    <small class="ml-2"><?= l('microsite_form.metadata.gdpr_safe') ?></small>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" checked disabled>
                            <label class="custom-control-label text-muted">
                                <strong><?= l('microsite_form.metadata.submission_timestamp') ?></strong><br>
                                <small><?= l('microsite_form.metadata.submission_timestamp_desc') ?></small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" checked disabled>
                            <label class="custom-control-label text-muted">
                                <strong><?= l('microsite_form.metadata.form_id') ?></strong><br>
                                <small><?= l('microsite_form.metadata.form_id_desc') ?></small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Data (Legitimate Interest) -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-fw fa-exclamation-triangle mr-2"></i>
                    <?= l('microsite_form.metadata.analytics_data') ?>
                    <small class="ml-2"><?= l('microsite_form.metadata.legitimate_interest') ?></small>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php 
                    $analytics_fields = [
                        'country_alpha3' => l('microsite_form.metadata.country_alpha3_desc'),
                        'region_code' => l('microsite_form.metadata.region_code_desc'),
                        'city_alpha3' => l('microsite_form.metadata.city_alpha3_desc'),
                        'timezone' => l('microsite_form.metadata.timezone_desc'),
                        'browser_name' => l('microsite_form.metadata.browser_name_desc'),
                        'browser_version' => l('microsite_form.metadata.browser_version_desc'),
                        'os_name' => l('microsite_form.metadata.os_name_desc'),
                        'device_type' => l('microsite_form.metadata.device_type_desc'),
                        'screen_resolution' => l('microsite_form.metadata.screen_resolution_desc'),
                        'language' => l('microsite_form.metadata.language_desc'),
                        'referrer_domain' => l('microsite_form.metadata.referrer_domain_desc'),
                        'time_on_page' => l('microsite_form.metadata.time_on_page_desc'),
                        'pages_visited' => l('microsite_form.metadata.pages_visited_desc'),
                    ];
                    foreach($analytics_fields as $field => $description): ?>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="metadata_capture[]" value="<?= $field ?>" id="<?= 'metadata_' . $field . '_' . $unique_id ?>" class="custom-control-input" <?= isset($row->settings->metadata_capture->$field) && $row->settings->metadata_capture->$field ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="<?= 'metadata_' . $field . '_' . $unique_id ?>">
                                    <strong><?= l('microsite_form.metadata.' . $field) ?></strong><br>
                                    <small class="text-muted"><?= $description ?></small>
                                </label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <!-- Data Retention Settings -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-fw fa-clock mr-2"></i>
                    <?= l('microsite_form.metadata.data_retention') ?>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="<?= 'data_retention_days_' . $unique_id ?>"><?= l('microsite_form.metadata.data_retention_days') ?></label>
                            <select id="<?= 'data_retention_days_' . $unique_id ?>" name="data_retention_days" class="form-control">
                                <option value="30" <?= ($row->settings->data_retention_days ?? 365) == 30 ? 'selected' : '' ?>>30 <?= l('global.days') ?></option>
                                <option value="90" <?= ($row->settings->data_retention_days ?? 365) == 90 ? 'selected' : '' ?>>90 <?= l('global.days') ?></option>
                                <option value="180" <?= ($row->settings->data_retention_days ?? 365) == 180 ? 'selected' : '' ?>>180 <?= l('global.days') ?></option>
                                <option value="365" <?= ($row->settings->data_retention_days ?? 365) == 365 ? 'selected' : '' ?>>1 <?= l('global.year') ?></option>
                                <option value="730" <?= ($row->settings->data_retention_days ?? 365) == 730 ? 'selected' : '' ?>>2 <?= l('global.years') ?></option>
                                <option value="1095" <?= ($row->settings->data_retention_days ?? 365) == 1095 ? 'selected' : '' ?>>3 <?= l('global.years') ?></option>
                            </select>
                            <small class="form-text text-muted"><?= l('microsite_form.metadata.data_retention_days_help') ?></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="<?= 'anonymize_after_days_' . $unique_id ?>"><?= l('microsite_form.metadata.anonymize_after_days') ?></label>
                            <select id="<?= 'anonymize_after_days_' . $unique_id ?>" name="anonymize_after_days" class="form-control">
                                <option value="7" <?= ($row->settings->anonymize_after_days ?? 90) == 7 ? 'selected' : '' ?>>7 <?= l('global.days') ?></option>
                                <option value="30" <?= ($row->settings->anonymize_after_days ?? 90) == 30 ? 'selected' : '' ?>>30 <?= l('global.days') ?></option>
                                <option value="90" <?= ($row->settings->anonymize_after_days ?? 90) == 90 ? 'selected' : '' ?>>90 <?= l('global.days') ?></option>
                                <option value="180" <?= ($row->settings->anonymize_after_days ?? 90) == 180 ? 'selected' : '' ?>>180 <?= l('global.days') ?></option>
                            </select>
                            <small class="form-text text-muted"><?= l('microsite_form.metadata.anonymize_after_days_help') ?></small>
                        </div>
                    </div>
                </div>
                
                <div class="custom-control custom-switch">
                    <input id="<?= 'gdpr_consent_required_' . $unique_id ?>" name="gdpr_consent_required" type="checkbox" class="custom-control-input" <?= ($row->settings->gdpr_consent_required ?? false) ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="<?= 'gdpr_consent_required_' . $unique_id ?>"><?= l('microsite_form.metadata.gdpr_consent_required') ?></label>
                    <small class="form-text text-muted"><?= l('microsite_form.metadata.gdpr_consent_required_help') ?></small>
                </div>
            </div>
        </div>

    </div>

    <!-- Display Tab -->
    <div class="tab-pane fade" id="form-<?= $unique_id ?>-display" role="tabpanel" aria-labelledby="form-<?= $unique_id ?>-display-tab">
        
        <div class="form-group custom-control custom-switch">
            <input id="<?= 'schedule_' . $unique_id ?>" name="schedule" type="checkbox" class="custom-control-input" <?= (!empty($row->start_date) && !empty($row->end_date)) ? 'checked="checked"' : null ?>>
            <label class="custom-control-label" for="<?= 'schedule_' . $unique_id ?>"><?= l('link.input.schedule') ?></label>
            <small class="form-text text-muted"><?= l('link.input.schedule_help') ?></small>
        </div>

        <div id="<?= 'schedule_container_' . $unique_id ?>" style="display: <?= (!empty($row->start_date) && !empty($row->end_date)) ? 'block' : 'none' ?>">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="<?= 'start_date_' . $unique_id ?>"><?= l('link.input.start_date') ?></label>
                        <input id="<?= 'start_date_' . $unique_id ?>" type="datetime-local" name="start_date" class="form-control" value="<?= !empty($row->start_date) ? (new \DateTime($row->start_date))->setTimezone(new \DateTimeZone($this->user->timezone ?? 'UTC'))->format('Y-m-d\TH:i:s') : '' ?>" />
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="<?= 'end_date_' . $unique_id ?>"><?= l('link.input.end_date') ?></label>
                        <input id="<?= 'end_date_' . $unique_id ?>" type="datetime-local" name="end_date" class="form-control" value="<?= !empty($row->end_date) ? (new \DateTime($row->end_date))->setTimezone(new \DateTimeZone($this->user->timezone ?? 'UTC'))->format('Y-m-d\TH:i:s') : '' ?>" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'display_continents_' . $unique_id ?>"><i class="fas fa-fw fa-globe-europe fa-sm text-muted mr-1"></i> <?= l('global.continents') ?></label>
            <select id="<?= 'display_continents_' . $unique_id ?>" name="display_continents[]" class="form-control" multiple="multiple">
                <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                    <option value="<?= $continent_code ?>" <?= in_array($continent_code, $row->settings->display_continents ?? []) ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('global.accessibility.whitelisted_continents') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'display_countries_' . $unique_id ?>"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('global.countries') ?></label>
            <select id="<?= 'display_countries_' . $unique_id ?>" name="display_countries[]" class="form-control" multiple="multiple">
                <?php foreach(get_countries_array() as $country => $country_name): ?>
                    <option value="<?= $country ?>" <?= in_array($country, $row->settings->display_countries ?? []) ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('global.accessibility.whitelisted_countries') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'display_cities_' . $unique_id ?>"><i class="fas fa-fw fa-city fa-sm text-muted mr-1"></i> <?= l('global.cities') ?></label>
            <input type="text" id="<?= 'display_cities_' . $unique_id ?>" name="display_cities" class="form-control" value="<?= implode(',', $row->settings->display_cities ?? []) ?>" />
            <small class="form-text text-muted"><?= l('global.accessibility.whitelisted_cities') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'display_devices_' . $unique_id ?>"><i class="fas fa-fw fa-laptop fa-sm text-muted mr-1"></i> <?= l('global.device') ?></label>
            <select id="<?= 'display_devices_' . $unique_id ?>" name="display_devices[]" class="form-control" multiple="multiple">
                <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                    <option value="<?= $device_type ?>" <?= in_array($device_type, $row->settings->display_devices ?? []) ? 'selected="selected"' : null ?>><?= l('global.device.' . $device_type) ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('global.accessibility.whitelisted_devices') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'display_languages_' . $unique_id ?>"><i class="fas fa-fw fa-language fa-sm text-muted mr-1"></i> <?= l('global.languages') ?></label>
            <select id="<?= 'display_languages_' . $unique_id ?>" name="display_languages[]" class="form-control" multiple="multiple">
                <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                    <option value="<?= $locale ?>" <?= in_array($locale, $row->settings->display_languages ?? []) ? 'selected="selected"' : null ?>><?= $language ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('global.accessibility.whitelisted_languages') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'display_operating_systems_' . $unique_id ?>"><i class="fas fa-fw fa-server fa-sm text-muted mr-1"></i> <?= l('global.operating_systems') ?></label>
            <select id="<?= 'display_operating_systems_' . $unique_id ?>" name="display_operating_systems[]" class="form-control" multiple="multiple">
                <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                    <option value="<?= $os_name ?>" <?= in_array($os_name, $row->settings->display_operating_systems ?? []) ? 'selected="selected"' : null ?>><?= $os_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('global.accessibility.whitelisted_operating_systems') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'display_browsers_' . $unique_id ?>"><i class="fas fa-fw fa-window-restore fa-sm text-muted mr-1"></i> <?= l('global.browsers') ?></label>
            <select id="<?= 'display_browsers_' . $unique_id ?>" name="display_browsers[]" class="form-control" multiple="multiple">
                <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                    <option value="<?= $browser_name ?>" <?= in_array($browser_name, $row->settings->display_browsers ?? []) ? 'selected="selected"' : null ?>><?= $browser_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('global.accessibility.whitelisted_browsers') ?></small>
        </div>

    </div>

</div>

<!-- Accordion-style CSS for questions -->
<style>
/* Accordion-style styling for questions */
.question-item-wrapper {
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.question-item-wrapper:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.question-item-header {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6 !important;
}

.question-item-header:hover {
    background-color: #e9ecef !important;
}

.drag-handle:hover {
    color: #007bff !important;
}

.question-toggle-icon {
    transition: transform 0.3s ease;
}

.question-item-header[aria-expanded="true"] .question-toggle-icon {
    transform: rotate(180deg);
}

.question-item-title {
    min-height: 24px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}

.btn-outline-danger:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

/* Sortable.js styling */
.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    transform: scale(1.02);
}

.sortable-drag {
    transform: rotate(5deg);
}

@media (max-width: 576px) {
    .question-item-header {
        flex-direction: column;
        gap: 8px;
    }
    
    .question-item-header .d-flex:first-child {
        width: 100%;
    }
}
</style>

<script>
'use strict';

// Initialize drag and drop functionality
function initializeDragAndDrop(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    new Sortable(container, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            // Update field names after reordering
            updateFieldNames(containerId);
        }
    });
}

// Update field names after reordering
function updateFieldNames(containerId) {
    const container = document.getElementById(containerId);
    const items = container.querySelectorAll('.question-item-wrapper');
    
    items.forEach((item, index) => {
        const typeSelect = item.querySelector('select[name^="question_type"]');
        const textInput = item.querySelector('input[name^="question_text"]');
        const descTextarea = item.querySelector('textarea[name^="question_description"]');
        const requiredCheckbox = item.querySelector('input[name^="question_required"]');
        const choicesTextarea = item.querySelector('textarea[name^="question_choices"]');
        const ratingSelect = item.querySelector('select[name^="question_max_rating"]');
        
        if (typeSelect) typeSelect.name = `question_type[${index}]`;
        if (textInput) textInput.name = `question_text[${index}]`;
        if (descTextarea) descTextarea.name = `question_description[${index}]`;
        if (requiredCheckbox) {
            requiredCheckbox.name = `question_required[${index}]`;
            requiredCheckbox.id = `question_required_${containerId.replace('questions_container_', '')}_${index}`;
            const label = item.querySelector(`label[for^="question_required"]`);
            if (label) label.setAttribute('for', requiredCheckbox.id);
        }
        if (choicesTextarea) choicesTextarea.name = `question_choices[${index}]`;
        if (ratingSelect) ratingSelect.name = `question_max_rating[${index}]`;
        
        // Update collapse target and ID
        const collapseContent = item.querySelector('.collapse');
        const toggleButton = item.querySelector('[data-target]');
        
        if (collapseContent && toggleButton) {
            const blockId = containerId.replace('questions_container_', '');
            const newId = `question-item-content-${blockId}-${index}`;
            collapseContent.id = newId;
            toggleButton.setAttribute('data-target', `#${newId}`);
        }
    });
}

// Update question title in header
function updateQuestionTitle(input) {
    const wrapper = input.closest('.question-item-wrapper');
    const titleSpan = wrapper.querySelector('.question-item-title');
    if (titleSpan) {
        titleSpan.textContent = input.value || 'New Question';
    }
}

// Update question type in header
function updateQuestionType(select) {
    const wrapper = select.closest('.question-item-wrapper');
    const typeSpan = wrapper.querySelector('.question-item-header small');
    if (typeSpan) {
        const typeName = select.options[select.selectedIndex].text;
        typeSpan.textContent = `(${typeName})`;
    }
    
    // Show/hide conditional fields
    const choicesGroup = wrapper.querySelector('.question-choices-group');
    const ratingGroup = wrapper.querySelector('.question-rating-group');
    const receiptGroup = wrapper.querySelector('.question-receipt-group');
    
    if (choicesGroup) {
        choicesGroup.style.display = ['checkbox', 'radio', 'dropdown'].includes(select.value) ? 'block' : 'none';
    }
    
    if (ratingGroup) {
        ratingGroup.style.display = ['rating_star', 'rating_number'].includes(select.value) ? 'block' : 'none';
    }
    
    if (receiptGroup) {
        receiptGroup.style.display = select.value === 'receipt_upload' ? 'block' : 'none';
    }
}

// Handle AI analysis toggle for receipt upload questions
function toggleAIAnalysis(checkbox) {
    const wrapper = checkbox.closest('.question-receipt-group');
    const aiSettings = wrapper.querySelector('.ai-analysis-settings');
    
    if (aiSettings) {
        aiSettings.style.display = checkbox.checked ? 'block' : 'none';
    }
}

// Handle multiple uploads toggle
function toggleMultipleUploads(checkbox) {
    const wrapper = checkbox.closest('.question-receipt-group');
    const maxUploadsSelect = wrapper.querySelector('select[name*="question_max_uploads"]');
    
    if (maxUploadsSelect) {
        maxUploadsSelect.style.display = checkbox.checked ? 'block' : 'none';
    }
}

// Update required badge in header
function updateRequiredBadge(checkbox) {
    const wrapper = checkbox.closest('.question-item-wrapper');
    const header = wrapper.querySelector('.question-item-header .d-flex');
    let badge = header.querySelector('.badge');
    
    if (checkbox.checked) {
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'badge badge-danger badge-sm ml-1';
            badge.textContent = 'Required';
            header.appendChild(badge);
        }
    } else {
        if (badge) {
            badge.remove();
        }
    }
}

// Form question management
if (typeof window.form_question_add === 'undefined') {
    window.form_question_add = function(event) {
    let microsite_block_id = event.currentTarget.getAttribute('data-microsite-block-id');
    let clone = document.querySelector(`#template_form_question_${microsite_block_id}`).content.cloneNode(true);
    let count = document.querySelectorAll(`[id="questions_container_${microsite_block_id}"] .question-item-wrapper`).length;

    if(count >= <?= $max_questions ?>) {
        alert('Maximum <?= $max_questions ?> questions allowed.');
        return;
    }

    // Update IDs and targets in the cloned template
    const collapseContent = clone.querySelector('.collapse');
    const toggleButton = clone.querySelector('[data-target]');
    const newId = `question-item-content-${microsite_block_id}-${count}`;
    
    if (collapseContent) collapseContent.id = newId;
    if (toggleButton) toggleButton.setAttribute('data-target', `#${newId}`);

    // Update field names with index
    const typeSelect = clone.querySelector('select[name="question_type[]"]');
    const textInput = clone.querySelector('input[name="question_text[]"]');
    const descTextarea = clone.querySelector('textarea[name="question_description[]"]');
    const choicesTextarea = clone.querySelector('textarea[name="question_choices[]"]');
    const ratingSelect = clone.querySelector('select[name="question_max_rating[]"]');
    
    if (typeSelect) typeSelect.setAttribute('name', `question_type[${count}]`);
    if (textInput) textInput.setAttribute('name', `question_text[${count}]`);
    if (descTextarea) descTextarea.setAttribute('name', `question_description[${count}]`);
    if (choicesTextarea) choicesTextarea.setAttribute('name', `question_choices[${count}]`);
    if (ratingSelect) ratingSelect.setAttribute('name', `question_max_rating[${count}]`);
    
    const requiredCheckbox = clone.querySelector('input[name="question_required[]"]');
    if (requiredCheckbox) {
        requiredCheckbox.setAttribute('name', `question_required[${count}]`);
        requiredCheckbox.id = `question_required_${microsite_block_id}_${count}`;
        const label = clone.querySelector('label[for*="question_required"]');
        if (label) label.setAttribute('for', requiredCheckbox.id);
    }

    // Add event listeners for the new item
    const titleInput = clone.querySelector('.question-text-input');
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            updateQuestionTitle(this);
        });
    }
    
    const typeSelectNew = clone.querySelector('.question-type-select');
    if (typeSelectNew) {
        typeSelectNew.addEventListener('change', function() {
            updateQuestionType(this);
        });
    }
    
    const requiredInput = clone.querySelector('.question-required-input');
    if (requiredInput) {
        requiredInput.addEventListener('change', function() {
            updateRequiredBadge(this);
        });
    }

    document.querySelector(`[id="questions_container_${microsite_block_id}"]`).appendChild(clone);

    // Re-initialize drag and drop
    setTimeout(function() {
        initializeDragAndDrop(`questions_container_${microsite_block_id}`);
    }, 100);

        form_question_remove_initiator();
    };
}

// Remove form question
if (typeof window.form_question_remove === 'undefined') {
    window.form_question_remove = function(event) {
    const wrapper = event.currentTarget.closest('.question-item-wrapper');
    const container = wrapper.parentNode;
    
    // Don't allow removing the last question
    if (container.querySelectorAll('.question-item-wrapper').length <= 1) {
        alert('At least one question is required.');
        return;
    }
    
    if (confirm('Are you sure you want to delete this question?')) {
        wrapper.remove();
        
        // Update field names after removal
        updateFieldNames(container.id);
    }
    };
}

if (typeof window.form_question_remove_initiator === 'undefined') {
    window.form_question_remove_initiator = function() {
    document.querySelectorAll('[id^="questions_container_"] [data-remove]').forEach(function(element) {
        element.removeEventListener('click', form_question_remove);
        element.addEventListener('click', form_question_remove);
    });
    
    // Add event listeners for existing items
    document.querySelectorAll('[id^="questions_container_"] .question-text-input').forEach(function(input) {
        input.removeEventListener('input', updateQuestionTitle);
        input.addEventListener('input', function() {
            updateQuestionTitle(this);
        });
    });
    
    document.querySelectorAll('[id^="questions_container_"] .question-type-select').forEach(function(select) {
        select.removeEventListener('change', updateQuestionType);
        select.addEventListener('change', function() {
            updateQuestionType(this);
        });
    });
    
    document.querySelectorAll('[id^="questions_container_"] .question-required-input').forEach(function(checkbox) {
        checkbox.removeEventListener('change', updateRequiredBadge);
        checkbox.addEventListener('change', function() {
            updateRequiredBadge(this);
        });
    });
    };
}

document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?= $unique_id ?>';
    
    // Agreement toggle
    const showAgreementCheckbox = document.getElementById('show_agreement_' + blockId);
    const agreementContainer = document.getElementById('agreement_container_' + blockId);
    
    if (showAgreementCheckbox && agreementContainer) {
        showAgreementCheckbox.addEventListener('change', function() {
            agreementContainer.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // Schedule toggle
    const scheduleCheckbox = document.getElementById('schedule_' + blockId);
    const scheduleContainer = document.getElementById('schedule_container_' + blockId);
    
    if (scheduleCheckbox && scheduleContainer) {
        scheduleCheckbox.addEventListener('change', function() {
            scheduleContainer.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Initialize existing functionality on page load
    initializeDragAndDrop('questions_container_' + blockId);
    
    // Add event listeners for existing items
    document.querySelectorAll('.question-text-input').forEach(function(input) {
        input.addEventListener('input', function() {
            updateQuestionTitle(this);
        });
    });
    
    document.querySelectorAll('.question-type-select').forEach(function(select) {
        select.addEventListener('change', function() {
            updateQuestionType(this);
        });
    });
    
    document.querySelectorAll('.question-required-input').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateRequiredBadge(this);
        });
        // Initialize badge on load
        updateRequiredBadge(checkbox);
    });
});

// Add event listeners
document.querySelectorAll('[data-add="form_question"]').forEach(function(element) {
    element.addEventListener('click', window.form_question_add);
});

window.form_question_remove_initiator();

// Form Block Canvas Update Functions
function updateCanvasText(blockId) {
    const iframe = document.getElementById('canvas-preview');
    if (!iframe || !iframe.contentDocument) return;
    
    const blockElement = iframe.contentDocument.querySelector(`[data-microsite-block-id="${blockId}"]`);
    if (!blockElement) return;
    
    // Update text alignment
    const textAlign = document.querySelector(`input[name="text_alignment"]:checked`)?.value || 'center';
    const textElements = blockElement.querySelectorAll('.form-title, .form-description, .form-label');
    textElements.forEach(element => {
        element.style.textAlign = textAlign;
    });
    
    // Update text color if available
    const textColor = document.querySelector('input[name="text_color"]')?.value;
    if (textColor) {
        textElements.forEach(element => {
            element.style.color = textColor;
        });
    }
}

function updateCanvasFormStyle(blockId) {
    const iframe = document.getElementById('canvas-preview');
    if (!iframe || !iframe.contentDocument) return;
    
    const blockElement = iframe.contentDocument.querySelector(`[data-microsite-block-id="${blockId}"]`);
    if (!blockElement) return;
    
    const formContainer = blockElement.querySelector('.form-container');
    if (!formContainer) return;
    
    // Update inline form style
    const inlineStyle = document.querySelector(`input[name="inline_form_style"]:checked`)?.value || 'card';
    formContainer.classList.remove('form-style-card', 'form-style-minimal');
    formContainer.classList.add(`form-style-${inlineStyle}`);
    
    // Update modal form style
    const modalStyle = document.querySelector(`input[name="modal_form_style"]:checked`)?.value || 'standard';
    formContainer.setAttribute('data-modal-style', modalStyle);
    
    // Update button trigger style
    const buttonStyle = document.querySelector(`input[name="button_trigger_style"]:checked`)?.value || 'button';
    const triggerButton = blockElement.querySelector('.form-trigger-button');
    if (triggerButton) {
        triggerButton.classList.remove('btn-style-button', 'btn-style-link', 'btn-style-icon');
        triggerButton.classList.add(`btn-style-${buttonStyle}`);
    }
}

function updateCanvasAnimation(blockId) {
    const iframe = document.getElementById('canvas-preview');
    if (!iframe || !iframe.contentDocument) return;
    
    const blockElement = iframe.contentDocument.querySelector(`[data-microsite-block-id="${blockId}"]`);
    if (!blockElement) return;
    
    // Get animation settings
    const animationType = document.querySelector('select[name="animation"]')?.value || 'false';
    const animationRuns = document.querySelector('select[name="animation_runs"]')?.value || 'repeat-1';
    const animationDelay = document.querySelector('input[name="animation_delay"]')?.value || '0';
    
    // Remove existing animation classes
    blockElement.classList.remove('animate__animated', 'animate__bounce', 'animate__fadeIn', 'animate__slideInUp', 'animate__zoomIn', 'animate__rotateIn', 'animate__pulse', 'animate__heartBeat', 'animate__infinite', 'animate__repeat-1', 'animate__repeat-2', 'animate__repeat-3');
    
    if (animationType && animationType !== 'false') {
        // Add animation classes
        blockElement.classList.add('animate__animated', `animate__${animationType}`);
        
        // Set animation iteration count
        if (animationRuns === 'infinite') {
            blockElement.classList.add('animate__infinite');
        } else {
            blockElement.classList.add(`animate__${animationRuns}`);
        }
        
        // Set animation delay
        blockElement.style.animationDelay = `${animationDelay}ms`;
        
        // Restart animation by removing and re-adding classes
        setTimeout(() => {
            blockElement.classList.remove('animate__animated', `animate__${animationType}`);
            setTimeout(() => {
                blockElement.classList.add('animate__animated', `animate__${animationType}`);
            }, 10);
        }, 10);
    }
}

function updateCanvasBackground(blockId) {
    const iframe = document.getElementById('canvas-preview');
    if (!iframe || !iframe.contentDocument) return;
    
    const blockElement = iframe.contentDocument.querySelector(`[data-microsite-block-id="${blockId}"]`);
    if (!blockElement) return;
    
    // Update background color
    const backgroundColor = document.querySelector('input[name="background_color"]')?.value;
    if (backgroundColor) {
        blockElement.style.backgroundColor = backgroundColor;
    }
}

function updateCanvasBorder(blockId) {
    // Check if there's a specific border update function available
    if (typeof window.updateCanvasBorderRadius === 'function') {
        window.updateCanvasBorderRadius(blockId);
    }
    if (typeof window.updateCanvasBorderWidth === 'function') {
        window.updateCanvasBorderWidth(blockId);
    }
    
    const iframe = document.getElementById('canvas-preview');
    if (!iframe || !iframe.contentDocument) return;
    
    const blockElement = iframe.contentDocument.querySelector(`[data-microsite-block-id="${blockId}"]`);
    if (!blockElement) return;
    
    // Update border color
    const borderColor = document.querySelector('input[name="border_color"]')?.value;
    if (borderColor) {
        blockElement.style.borderColor = borderColor;
    }
}

function updateCanvasShadow(blockId) {
    // Check if there's a specific shadow update function available
    if (typeof window.updateCanvasShadow === 'function') {
        window.updateCanvasShadow(blockId);
        return;
    }
    
    const iframe = document.getElementById('canvas-preview');
    if (!iframe || !iframe.contentDocument) return;
    
    const blockElement = iframe.contentDocument.querySelector(`[data-microsite-block-id="${blockId}"]`);
    if (!blockElement) return;
    
    // Get shadow settings
    const shadowHorizontal = document.querySelector('input[name="border_shadow_offset_x"]')?.value || '0';
    const shadowVertical = document.querySelector('input[name="border_shadow_offset_y"]')?.value || '0';
    const shadowBlur = document.querySelector('input[name="border_shadow_blur_radius"]')?.value || '0';
    const shadowSpread = document.querySelector('input[name="border_shadow_spread_radius"]')?.value || '0';
    const shadowColor = document.querySelector('input[name="border_shadow_color"]')?.value || 'rgba(0,0,0,0.1)';
    
    // Apply shadow
    const shadowValue = `${shadowHorizontal}px ${shadowVertical}px ${shadowBlur}px ${shadowSpread}px ${shadowColor}`;
    blockElement.style.boxShadow = shadowValue;
}
</script>
