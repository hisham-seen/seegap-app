<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\Models;

defined('ALTUMCODE') || die();

class MicrositeBlock extends Model {

    public function delete($microsite_block_id) {

        if(!$microsite_block = db()->where('microsite_block_id', $microsite_block_id)->getOne('microsites_blocks')) {
            die();
        }

        $blocks_with_storage = [
            'image' => [['path' => 'block_images', 'uploaded_file_key' => 'image']],
            'image_grid' => [['path' => 'block_images', 'uploaded_file_key' => 'image']],
            'link' => [['path' => 'block_thumbnail_images', 'uploaded_file_key' => 'image']],
            'big_link' => [['path' => 'block_thumbnail_images', 'uploaded_file_key' => 'image']],
            'email_collector' => [['path' => 'block_thumbnail_images', 'uploaded_file_key' => 'image']],
            'vcard' => [
                ['path' => 'block_thumbnail_images', 'uploaded_file_key' => 'image'],
                ['path' => 'avatars', 'uploaded_file_key' => 'vcard_avatar'],
            ],
            'audio' => [['path' => 'files', 'uploaded_file_key' => 'file']],
            'video' => [['path' => 'files', 'uploaded_file_key' => 'file']],
            'file' => [['path' => 'files', 'uploaded_file_key' => 'file']],
            'pdf_document' => [['path' => 'files', 'uploaded_file_key' => 'file']],
            'powerpoint_presentation' => [['path' => 'files', 'uploaded_file_key' => 'file']],
            'excel_spreadsheet' => [['path' => 'files', 'uploaded_file_key' => 'file']],
            'avatar' => [['path' => 'avatars', 'uploaded_file_key' => 'image']],
            'review' => [['path' => 'block_images', 'uploaded_file_key' => 'image']],
            'header' => [
                ['path' => 'avatars', 'uploaded_file_key' => 'avatar'],
                ['path' => 'backgrounds', 'uploaded_file_key' => 'background'],
            ],
        ];

        if(array_key_exists($microsite_block->type, $blocks_with_storage)) {
            $microsite_block->settings = json_decode($microsite_block->settings ?? '');

            foreach($blocks_with_storage[$microsite_block->type] as $block_with_storage) {
                if(!empty($microsite_block->settings->{$block_with_storage['uploaded_file_key']})) {
                    /* Offload deleting */
                    if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                        $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                        if($s3->doesObjectExist(settings()->offload->storage_name, 'uploads/' . $block_with_storage['path'] . '/' . $microsite_block->settings->{$block_with_storage['uploaded_file_key']})) {
                            $s3->deleteObject([
                                'Bucket' => settings()->offload->storage_name,
                                'Key' => 'uploads/' . $block_with_storage['path'] . '/' . $microsite_block->settings->{$block_with_storage['uploaded_file_key']},
                            ]);
                        }
                    }

                    /* Local deleting */
                    else {
                        /* Delete current file */
                        if(file_exists(UPLOADS_PATH . $block_with_storage['path'] . '/' . $microsite_block->settings->{$block_with_storage['uploaded_file_key']})) {
                            unlink(UPLOADS_PATH . $block_with_storage['path'] . '/' . $microsite_block->settings->{$block_with_storage['uploaded_file_key']});
                        }
                    }
                }
            }
        }

        /* Image slider special */
        if($microsite_block->type == 'image_slider') {
            $microsite_block->settings = json_decode($microsite_block->settings ?? '');

            foreach($microsite_block->settings->items as $item) {
                \Altum\Uploads::delete_uploaded_file($item->image, 'block_images');
            }
        }

        /* Delete from database */
        db()->where('microsite_block_id', $microsite_block_id)->delete('microsites_blocks');

        /* Clear the cache */
        cache()->deleteItem('microsite_blocks?link_id=' . $microsite_block->link_id);
        cache()->deleteItem('microsite_block?block_id=' . $microsite_block->microsite_block_id . '&type=youtube_feed');

    }
}
