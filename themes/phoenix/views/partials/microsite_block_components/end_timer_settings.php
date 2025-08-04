<?php defined('SEEGAP') || die() ?>

<?php
/**
 * End Timer Settings Component for Microsite Blocks
 * Provides settings for what happens when a timer/countdown ends
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param bool $collapsed - Whether the section should be collapsed by default (default: false)
 * @param string $prefix - Field name prefix (default: '')
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$collapsed = $collapsed ?? false;
$prefix = $prefix ?? '';

// Add prefix to field names if provided
$field_prefix = $prefix ? $prefix . '_' : '';
?>

<?php if($collapsed): ?>
<div class="card mb-3">
    <div class="card-header" data-toggle="collapse" data-target="#end_timer_settings_<?= $block_id ?>" aria-expanded="false" style="cursor: pointer;">
        <h6 class="mb-0">
            <i class="fas fa-fw fa-flag-checkered fa-sm text-muted mr-2"></i>
            <?= l('microsite_countdown.end_action') ?? 'End Timer Settings' ?>
            <i class="fas fa-chevron-down float-right"></i>
        </h6>
    </div>
    <div id="end_timer_settings_<?= $block_id ?>" class="collapse">
        <div class="card-body">
<?php endif ?>

            <!-- End Action Settings -->
            <div class="form-group">
                <label for="<?= $field_prefix ?>end_action_<?= $block_id ?>">
                    <i class="fas fa-fw fa-flag-checkered fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_countdown.end_action') ?? 'End Action' ?>
                </label>
                <select id="<?= $field_prefix ?>end_action_<?= $block_id ?>" name="<?= $field_prefix ?>end_action" class="form-control">
                    <option value="message" <?= ($settings->end_action ?? 'message') == 'message' ? 'selected' : '' ?>>
                        <?= l('microsite_countdown.end_action_message') ?? 'Show Message' ?>
                    </option>
                    <option value="redirect" <?= ($settings->end_action ?? 'message') == 'redirect' ? 'selected' : '' ?>>
                        <?= l('microsite_countdown.end_action_redirect') ?? 'Redirect to URL' ?>
                    </option>
                    <option value="hide" <?= ($settings->end_action ?? 'message') == 'hide' ? 'selected' : '' ?>>
                        <?= l('microsite_countdown.end_action_hide') ?? 'Hide Block' ?>
                    </option>
                </select>
                <small class="form-text text-muted">
                    <?= l('microsite_countdown.end_action_help') ?? 'What should happen when the countdown reaches zero' ?>
                </small>
            </div>

            <!-- End Message Settings (shown when action is 'message') -->
            <div id="<?= $field_prefix ?>end_message_settings_<?= $block_id ?>" class="end-action-settings" data-action="message">
                <div class="form-group">
                    <label for="<?= $field_prefix ?>end_message_<?= $block_id ?>">
                        <i class="fas fa-fw fa-comment fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_countdown.end_message') ?? 'End Message' ?>
                    </label>
                    <textarea 
                        id="<?= $field_prefix ?>end_message_<?= $block_id ?>" 
                        name="<?= $field_prefix ?>end_message" 
                        class="form-control" 
                        rows="3"
                        placeholder="<?= l('microsite_countdown.end_message_placeholder') ?? 'Time\'s up! The countdown has ended.' ?>"
                    ><?= $settings->end_message ?? '' ?></textarea>
                    <small class="form-text text-muted">
                        <?= l('microsite_countdown.end_message_help') ?? 'Message to display when countdown ends' ?>
                    </small>
                </div>

                <!-- End Image -->
                <div class="form-group">
                    <label for="<?= $field_prefix ?>end_image_<?= $block_id ?>">
                        <i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_countdown.end_image') ?? 'End Image' ?>
                    </label>
                    <div class="custom-file">
                        <input type="file" id="<?= $field_prefix ?>end_image_<?= $block_id ?>" name="<?= $field_prefix ?>end_image" class="custom-file-input" accept="<?= \SeeGap\Uploads::get_whitelisted_file_extensions_accept('images') ?>">
                        <label class="custom-file-label" for="<?= $field_prefix ?>end_image_<?= $block_id ?>"><?= l('global.choose_file') ?? 'Choose file' ?></label>
                    </div>
                    <?php if(!empty($settings->end_image)): ?>
                        <div class="mt-2">
                            <img src="<?= $settings->end_image ?>" class="img-fluid" style="max-height: 150px;" />
                            <div class="mt-1">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEndImage<?= $block_id ?>()">
                                    <i class="fas fa-times"></i> <?= l('global.remove') ?? 'Remove' ?>
                                </button>
                            </div>
                        </div>
                    <?php endif ?>
                    <small class="form-text text-muted">
                        <?= l('microsite_countdown.end_image_help') ?? 'Optional image to show when countdown ends' ?>
                    </small>
                </div>

                <!-- End Link Settings -->
                <div class="form-group">
                    <label for="<?= $field_prefix ?>end_link_url_<?= $block_id ?>">
                        <i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_countdown.end_link_url') ?? 'End Link URL' ?>
                    </label>
                    <input 
                        type="url" 
                        id="<?= $field_prefix ?>end_link_url_<?= $block_id ?>" 
                        name="<?= $field_prefix ?>end_link_url" 
                        class="form-control" 
                        value="<?= $settings->end_link_url ?? '' ?>"
                        placeholder="https://example.com"
                    />
                    <small class="form-text text-muted">
                        <?= l('microsite_countdown.end_link_url_help') ?? 'URL to redirect to when countdown ends' ?>
                    </small>
                </div>

                <div class="form-group">
                    <label for="<?= $field_prefix ?>end_link_text_<?= $block_id ?>">
                        <i class="fas fa-fw fa-text-width fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_countdown.end_link_text') ?? 'End Link Text' ?>
                    </label>
                    <input 
                        type="text" 
                        id="<?= $field_prefix ?>end_link_text_<?= $block_id ?>" 
                        name="<?= $field_prefix ?>end_link_text" 
                        class="form-control" 
                        value="<?= $settings->end_link_text ?? '' ?>"
                        placeholder="<?= l('microsite_countdown.end_link_text_placeholder') ?? 'Click here to continue' ?>"
                    />
                    <small class="form-text text-muted">
                        <?= l('microsite_countdown.end_link_text_help') ?? 'Text for the link button' ?>
                    </small>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input 
                            type="checkbox" 
                            id="<?= $field_prefix ?>end_link_new_tab_<?= $block_id ?>" 
                            name="<?= $field_prefix ?>end_link_new_tab" 
                            class="custom-control-input"
                            <?= ($settings->end_link_new_tab ?? false) ? 'checked' : '' ?>
                        />
                        <label class="custom-control-label" for="<?= $field_prefix ?>end_link_new_tab_<?= $block_id ?>">
                            <?= l('microsite_countdown.end_link_new_tab') ?? 'Open in New Tab' ?>
                        </label>
                        <small class="form-text text-muted">
                            <?= l('microsite_countdown.end_link_new_tab_help') ?? 'Open the link in a new browser tab' ?>
                        </small>
                    </div>
                </div>
            </div>

            <!-- End Redirect Settings (shown when action is 'redirect') -->
            <div id="<?= $field_prefix ?>end_redirect_settings_<?= $block_id ?>" class="end-action-settings" data-action="redirect" style="display: none;">
                <div class="form-group">
                    <label for="<?= $field_prefix ?>redirect_url_<?= $block_id ?>">
                        <i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_countdown.end_link_url') ?? 'Redirect URL' ?>
                    </label>
                    <input 
                        type="url" 
                        id="<?= $field_prefix ?>redirect_url_<?= $block_id ?>" 
                        name="<?= $field_prefix ?>redirect_url" 
                        class="form-control" 
                        value="<?= $settings->redirect_url ?? '' ?>"
                        placeholder="https://example.com"
                    />
                    <small class="form-text text-muted">
                        <?= l('microsite_countdown.end_link_url_help') ?? 'URL to redirect to when countdown ends' ?>
                    </small>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input 
                            type="checkbox" 
                            id="<?= $field_prefix ?>auto_redirect_<?= $block_id ?>" 
                            name="<?= $field_prefix ?>auto_redirect" 
                            class="custom-control-input"
                            <?= ($settings->auto_redirect ?? true) ? 'checked' : '' ?>
                        />
                        <label class="custom-control-label" for="<?= $field_prefix ?>auto_redirect_<?= $block_id ?>">
                            <?= l('microsite_countdown.end_auto_redirect') ?? 'Auto Redirect' ?>
                        </label>
                        <small class="form-text text-muted">
                            <?= l('microsite_countdown.end_auto_redirect_help') ?? 'Automatically redirect when countdown ends' ?>
                        </small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="<?= $field_prefix ?>redirect_delay_<?= $block_id ?>">
                        <i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> 
                        <?= l('microsite_countdown.end_redirect_delay') ?? 'Redirect Delay' ?>
                    </label>
                    <input 
                        type="number" 
                        id="<?= $field_prefix ?>redirect_delay_<?= $block_id ?>" 
                        name="<?= $field_prefix ?>redirect_delay" 
                        class="form-control" 
                        value="<?= $settings->redirect_delay ?? 0 ?>"
                        min="0"
                        max="60"
                    />
                    <small class="form-text text-muted">
                        <?= l('microsite_countdown.end_redirect_delay_help') ?? 'Seconds to wait before redirecting (0 for immediate)' ?>
                    </small>
                </div>
            </div>

<?php if($collapsed): ?>
        </div>
    </div>
</div>
<?php endif ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?= $block_id ?>';
    const fieldPrefix = '<?= $field_prefix ?>';
    
    // End action settings toggle
    const endActionSelect = document.getElementById(fieldPrefix + 'end_action_' + blockId);
    if (endActionSelect) {
        endActionSelect.addEventListener('change', function() {
            toggleEndActionSettings<?= $block_id ?>(this.value);
        });
        
        // Initialize on page load
        toggleEndActionSettings<?= $block_id ?>(endActionSelect.value);
    }
    
    // File input label update
    const fileInputs = document.querySelectorAll('.custom-file-input');
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Choose file';
            const label = this.nextElementSibling;
            label.textContent = fileName;
        });
    });
});

function toggleEndActionSettings<?= $block_id ?>(action) {
    const fieldPrefix = '<?= $field_prefix ?>';
    const messageSettings = document.getElementById(fieldPrefix + 'end_message_settings_<?= $block_id ?>');
    const redirectSettings = document.getElementById(fieldPrefix + 'end_redirect_settings_<?= $block_id ?>');
    const redirectUrlInput = document.getElementById(fieldPrefix + 'redirect_url_<?= $block_id ?>');
    
    // Hide all settings first and remove required attributes
    if (messageSettings) messageSettings.style.display = 'none';
    if (redirectSettings) redirectSettings.style.display = 'none';
    if (redirectUrlInput) redirectUrlInput.removeAttribute('required');
    
    // Show relevant settings and add required attributes where needed
    if (action === 'message' && messageSettings) {
        messageSettings.style.display = 'block';
    } else if (action === 'redirect' && redirectSettings) {
        redirectSettings.style.display = 'block';
        // Only make redirect URL required when redirect action is selected
        if (redirectUrlInput) redirectUrlInput.setAttribute('required', 'required');
    }
    // For 'hide' action, no additional settings are shown
}

function removeEndImage<?= $block_id ?>() {
    if (confirm('Are you sure you want to remove this image?')) {
        // Add a hidden input to mark image for removal
        const form = document.querySelector('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_end_image';
        hiddenInput.value = '1';
        form.appendChild(hiddenInput);
        
        // Hide the image preview
        const imageContainer = event.target.closest('.mt-2');
        if (imageContainer) {
            imageContainer.style.display = 'none';
        }
    }
}
</script>
