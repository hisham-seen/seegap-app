<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_countdown" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />
    <input type="hidden" name="block_type" value="countdown" />

    <div class="notification-container"></div>

    <?php
    $block_id = $row->microsite_block_id;
    $settings = $row->settings;
    $form_type = 'update';
    include THEME_PATH . 'views/partials/microsite_block_components/countdown_block_form_panel.php';
    ?>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<script>
<?php
// Log frontend debug info to server log
error_log("=== COUNTDOWN UPDATE FORM LOADED ===");
error_log("Update form loaded for block_id: " . ($row->microsite_block_id ?? 'unknown'));
error_log("User ID: " . (\SeeGap\Authentication::$user->user_id ?? 'guest'));
?>

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[name="update_microsite_countdown"]');
    const submitButton = form.querySelector('button[type="submit"]');
    
    console.log('=== COUNTDOWN UPDATE FORM DEBUG ===');
    console.log('Form found:', form);
    console.log('Submit button found:', submitButton);
    
    // Add form submit event listener
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('=== UPDATE FORM SUBMIT EVENT TRIGGERED ===');
            
            const formData = new FormData(form);
            const formFields = {};
            for (let [key, value] of formData.entries()) {
                formFields[key] = value;
            }
            console.log('Form data at submit:', formFields);
            
            // Send debug info to server via existing client debug handler
            fetch('<?= SITE_URL ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'client_debug': '1',
                    'message': 'COUNTDOWN UPDATE FORM SUBMIT',
                    'block_id': 'countdown_update_<?= $row->microsite_block_id ?? 'unknown' ?>',
                    'timestamp': new Date().toISOString(),
                    'debug_data': JSON.stringify(formFields)
                })
            }).catch(err => console.error('Failed to send debug info:', err));
        });
    }
    
    // Add click event to submit button
    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            console.log('=== UPDATE SUBMIT BUTTON CLICKED ===');
            
            // Check form validity
            const dateInput = form.querySelector('input[name="counter_end_date"]');
            const themeInput = form.querySelector('input[name="theme"]:checked');
            
            console.log('Date input:', dateInput);
            console.log('Date value:', dateInput ? dateInput.value : 'not found');
            console.log('Theme input:', themeInput);
            console.log('Theme value:', themeInput ? themeInput.value : 'not found');
            
            // Send validation info to server
            const validationInfo = {
                dateInputFound: !!dateInput,
                dateValue: dateInput ? dateInput.value : 'not found',
                themeInputFound: !!themeInput,
                themeValue: themeInput ? themeInput.value : 'not found'
            };
            
            fetch('<?= SITE_URL ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'client_debug': '1',
                    'message': 'COUNTDOWN UPDATE BUTTON CLICK',
                    'block_id': 'countdown_update_<?= $row->microsite_block_id ?? 'unknown' ?>',
                    'timestamp': new Date().toISOString(),
                    'debug_data': JSON.stringify(validationInfo)
                })
            }).catch(err => console.error('Failed to send debug info:', err));
        });
    }
});
</script>
