<?php defined('SEEGAP') || die() ?>

<?php ob_start() ?>
<div id="local_scanner" class="d-none"></div>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/html5-qrcode.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';

    /* Code to read the QR and make sure its readable */
    let base64_to_file = (base64_string, filename) => {
        const arr = base64_string.split(',');
        const mime = arr[0].match(/:(.*?);/)[1];
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);

        while(n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }

        return new File([u8arr], filename, {type: mime});
    }

    /* Local scanner with file uploading */
    const local_scanner = new Html5Qrcode('local_scanner', {
        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
    });

    let scan_qr_code = (base64_string) => {
        let file = base64_to_file(base64_string, 'data.svg');

        local_scanner.scanFile(file, true)
            .then(decoded_text => {
                document.querySelector('input[name="is_readable"]').value = 1;
                document.querySelector('#is_readable').classList.remove('d-none');
                document.querySelector('#is_not_readable').classList.add('d-none');
            })
            .catch(err => {
                document.querySelector('input[name="is_readable"]').value = 0;
                document.querySelector('#is_readable').classList.add('d-none');
                document.querySelector('#is_not_readable').classList.remove('d-none');
            });
    }
</script>

<script>
    'use strict';

    type_handler('input[name="type"]', 'data-type');
    document.querySelector('input[name="type"]') && document.querySelectorAll('input[name="type"]').forEach(element => {
        element.addEventListener('change', event => {
            $('#type').val(event.currentTarget.value).trigger('change');

            type_handler('input[name="type"]', 'data-type')
        });
    })

    type_handler('select[name="type"]', 'data-type');
    document.querySelector('select[name="type"]') && document.querySelector('select[name="type"]').addEventListener('change', event => {
        type_handler('select[name="type"]', 'data-type');

        $(`input[name="type"][value="${event.currentTarget.value}"]`).click().trigger('change');
    });

    type_handler('[name="foreground_type"]', 'data-foreground-type');
    document.querySelector('[name="foreground_type"]') && document.querySelectorAll('[name="foreground_type"]').forEach(element => {
        element.addEventListener('change', () => { type_handler('[name="foreground_type"]', 'data-foreground-type') });
    })

    type_handler('input[name="custom_eyes_color"]', 'data-custom-eyes-color');
    document.querySelector('input[name="custom_eyes_color"]') && document.querySelector('input[name="custom_eyes_color"]').addEventListener('change', () => { type_handler('input[name="custom_eyes_color"]', 'data-custom-eyes-color') });

    type_handler('input[name="frame_custom_colors"]', 'data-frame-custom-colors');
    document.querySelector('input[name="frame_custom_colors"]') && document.querySelector('input[name="frame_custom_colors"]').addEventListener('change', () => { type_handler('input[name="frame_custom_colors"]', 'data-frame-custom-colors') });

    /* Url Dynamic handler */
    let url_dynamic_handler = () => {
        if(document.querySelector('select[name="type"]')) {
            let type = document.querySelector('select[name="type"]').value;

            if(type != 'url') {
                return;
            }
        }

        if(!document.querySelector('#url')) {
            return;
        }

        let url_dynamic = document.querySelector('#url_dynamic').checked;

        if(url_dynamic) {
            document.querySelector('#url').removeAttribute('required');
            document.querySelector('[data-url]').classList.add('d-none');
            document.querySelector('[data-dynamic-type]').classList.remove('d-none');
            
            // Handle dynamic type selection
            dynamic_type_handler();
        } else {
            document.querySelector('[data-dynamic-type]').classList.add('d-none');
            document.querySelector('[data-dynamic-links]').classList.add('d-none');
            document.querySelector('[data-dynamic-gs1-links]').classList.add('d-none');
            document.querySelector('#url').setAttribute('required', 'required');
            document.querySelector('[data-url]').classList.remove('d-none');
        }
    }

    /* Dynamic Type handler */
    let dynamic_type_handler = () => {
        if(!document.querySelector('#url_dynamic') || !document.querySelector('#url_dynamic').checked) {
            return;
        }

        let dynamic_type = document.querySelector('#dynamic_type').value;
        
        // Hide all dynamic sections first
        document.querySelector('[data-dynamic-links]').classList.add('d-none');
        document.querySelector('[data-dynamic-gs1-links]').classList.add('d-none');
        
        // Remove required attributes
        document.querySelector('#link_id').removeAttribute('required');
        document.querySelector('#gs1_link_id').removeAttribute('required');
        
        if(dynamic_type === 'links') {
            document.querySelector('[data-dynamic-links]').classList.remove('d-none');
            document.querySelector('#link_id').setAttribute('required', 'required');
            
            let link_id_element = document.querySelector('#link_id');
            if(link_id_element.options.length > 1) {
                document.querySelector('#url').value = link_id_element.options[link_id_element.selectedIndex].getAttribute('data-url') || '';
            }
        } else if(dynamic_type === 'gs1_links') {
            document.querySelector('[data-dynamic-gs1-links]').classList.remove('d-none');
            document.querySelector('#gs1_link_id').setAttribute('required', 'required');
            
            let gs1_link_id_element = document.querySelector('#gs1_link_id');
            if(gs1_link_id_element.options.length > 1) {
                document.querySelector('#url').value = gs1_link_id_element.options[gs1_link_id_element.selectedIndex].getAttribute('data-url') || '';
            }
        }
    }

    if(document.querySelector('#url_dynamic')) {
        url_dynamic_handler();
        document.querySelector('#url_dynamic').addEventListener('change', url_dynamic_handler);
        document.querySelector('select[name="type"]') && document.querySelector('select[name="type"]').addEventListener('change', url_dynamic_handler);
        document.querySelector('input[name="type"]') && document.querySelectorAll('input[name="type"]').forEach(element => element.addEventListener('change', url_dynamic_handler));
    }

    /* Dynamic Type handler events */
    if(document.querySelector('#dynamic_type')) {
        dynamic_type_handler();
        document.querySelector('#dynamic_type').addEventListener('change', dynamic_type_handler);
    }

    /* URL Dynamic Link_id handler */
    let link_id_handler = () => {
        let link_id_element = document.querySelector('#link_id');

        if(link_id_element && document.querySelector('#url_dynamic') && document.querySelector('#url_dynamic').checked && document.querySelector('#dynamic_type').value === 'links') {
            document.querySelector('#url').value = link_id_element.options[link_id_element.selectedIndex].getAttribute('data-url') || '';
        }
    }
    document.querySelector('#link_id') && document.querySelector('#link_id').addEventListener('change', link_id_handler);

    /* URL Dynamic GS1 Link_id handler */
    let gs1_link_id_handler = () => {
        let gs1_link_id_element = document.querySelector('#gs1_link_id');

        if(gs1_link_id_element && document.querySelector('#url_dynamic') && document.querySelector('#url_dynamic').checked && document.querySelector('#dynamic_type').value === 'gs1_links') {
            document.querySelector('#url').value = gs1_link_id_element.options[gs1_link_id_element.selectedIndex].getAttribute('data-url') || '';
        }
    }
    document.querySelector('#gs1_link_id') && document.querySelector('#gs1_link_id').addEventListener('change', gs1_link_id_handler);

    /* On change regenerated qr */
    let qr_code_delay_timer = null;

    let apply_reload_qr_code_event_listeners = () => {
        document.querySelectorAll('[data-reload-qr-code]').forEach(element => {
            let events = ['paste', 'keyup', 'change'];
            events.forEach(event_type => {
                element.removeEventListener(event_type, reload_qr_code_event_listener);
                element.addEventListener(event_type, reload_qr_code_event_listener);
            })
        });
    }

    let reload_qr_code_event_listener = event => {

        let targeted_element = event.currentTarget;

        if(sessionStorage.getItem(targeted_element.id) == targeted_element.value) {
            return;
        } else {
            sessionStorage.setItem(targeted_element.id, targeted_element.value);
        }

        /* Add the preloader, hide the QR */
        document.querySelector('#qr_code').classList.add('qr-code-loading');

        /* Disable the submit button */
        if(document.querySelector('button[type="submit"]')) {
            document.querySelector('button[type="submit"]').classList.add('disabled');
            document.querySelector('button[type="submit"]').setAttribute('disabled','disabled');
        }

        clearTimeout(qr_code_delay_timer);

        qr_code_delay_timer = setTimeout(() => {

            /* Send the request to the server */
            let form = document.querySelector('#form');
            let form_data = new FormData(form);
            form_data.delete('qr_code');

            let notification_container = form.querySelector('.notification-container');
            notification_container.innerHTML = '';

            fetch(`${url}qr-code-generator`, {
                method: 'POST',
                body: form_data,
            })
                .then(response => response.ok ? response.json() : Promise.reject(response))
                .then(data => {
                    if(data.status == 'error') {
                        display_notifications(data.message, 'error', notification_container);
                    }

                    else if(data.status == 'success') {
                        display_notifications(data.message, 'success', notification_container);

                        document.querySelector('#qr_code').src = data.details.data;
                        document.querySelector('#download_svg').href = data.details.data;
                        if(document.querySelector('input[name="qr_code"]')) {
                            document.querySelector('input[name="qr_code"]').value = data.details.data;
                        }

                        /* Display embedded data */
                        if(document.querySelector('#embedded_data_container_button')) {
                            document.querySelector('#embedded_data_container_button').classList.remove('d-none');
                            document.querySelector('#embedded_data_display').innerText = data.details.embedded_data;
                            if(document.querySelector('input[name="embedded_data"]')) {
                                document.querySelector('input[name="embedded_data"]').value = data.details.embedded_data;
                            }
                        }

                        /* Enable the submit button */
                        if(document.querySelector('button[type="submit"]')) {
                            document.querySelector('button[type="submit"]').classList.remove('disabled');
                            document.querySelector('button[type="submit"]').removeAttribute('disabled');
                        }

                        /* Check if its readable */
                        scan_qr_code(data.details.data);
                    }

                    /* Hide the preloader, display the QR */
                    document.querySelector('#qr_code').classList.remove('qr-code-loading');
                })
                .catch(error => {
                    console.log(error);
                });

        }, 750);
    }

    apply_reload_qr_code_event_listeners();

    /* SVG to PNG, WEBP, JPG download handler */
    let convert_svg_qr_code_to_others = (svg_data, type, name, size = 1000) => {
        svg_data = !svg_data && document.querySelector('#download_svg') ? document.querySelector('#download_svg').href : svg_data;
        size = document.querySelector('#size') ? document.querySelector('#size').value : size;
        let image = new Image;
        image.crossOrigin = 'anonymous';

        /* Convert SVG data to others */
        image.onload = function() {
            const aspect_ratio = image.naturalHeight / image.naturalWidth;
            const height = size * aspect_ratio;

            let canvas = document.createElement('canvas');

            canvas.width = size;
            canvas.height = height;

            let context = canvas.getContext('2d');
            context.drawImage(image, 0, 0, size, height);

            type = type == 'jpg' ? 'jpeg' : type;
            let data = canvas.toDataURL(`image/${type}`, 1);

            /* Download */
            let link = document.createElement('a');
            link.download = name;
            link.style.opacity = '0';
            document.body.appendChild(link);
            link.href = data;
            link.click();
            link.remove();
        }

        /* Add SVG data */
        image.src = svg_data;
    }

    <?php if(isset($_GET['name'])): ?>
    document.querySelector('select[name="type"]').dispatchEvent(new Event('change'));
    document.querySelector('input[name="reload"]').dispatchEvent(new Event('change'));
    <?php endif ?>

    sessionStorage.clear();
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
