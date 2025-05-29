<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers;

defined('ALTUMCODE') || die();

class GuestPaymentDownload extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('payment-blocks')) {
            redirect();
        }

        $_GET['guest_payment_id'] = (int) $_GET['guest_payment_id'];
        $_GET['key'] = input_clean($_GET['key']);

        /* Make sure the transaction exists */
        if(!$guest_payment = db()->where('guest_payment_id', $_GET['guest_payment_id'])->getOne('guests_payments')) {
            redirect();
        }

        /* Make sure the key is correct */
        if(md5($guest_payment->payment_id) != $_GET['key']) {
            redirect();
        }

        /* Get the microsite block */
        if(!$microsite_block = db()->where('microsite_block_id', $guest_payment->microsite_block_id)->getOne('microsites_blocks')) {
            redirect();
        }
        $microsite_block->settings = json_decode($microsite_block->settings ?? '');

        if(!$microsite_block->settings->file) {
            redirect();
        }

        /* Download the file */
        $file_url = \Altum\Uploads::get_full_url('products_files') . $microsite_block->settings->file;
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="' . basename($file_url) . '"');
        readfile($file_url);
    }

}
