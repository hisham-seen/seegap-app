<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers;

use SeeGap\Meta;
use SeeGap\Models\Domain;

defined('SEEGAP') || die();

class Index extends Controller {

    public function index() {
        
        // Redirect to login page directly
        header('Location: ' . url('login'));
        die();

        /* The code below will not be executed due to the redirect above */

        /* Custom index redirect if set */
        if(!empty(settings()->main->index_url)) {
            header('Location: ' . settings()->main->index_url); die();
        }

        /* Plans View */
        $view = new \SeeGap\View('partials/plans', (array) $this);
        $this->add_view_content('plans', $view->run());

        /* Check if the cache exists */
        $cache_instance = cache()->getItem('index_stats');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            $total_users = database()->query("SELECT MAX(`user_id`) AS `total` FROM `users`")->fetch_object()->total ?? 0;
            $total_links = database()->query("SELECT MAX(`link_id`) AS `total` FROM `links`")->fetch_object()->total ?? 0;
            $total_qr_codes = database()->query("SELECT MAX(`qr_code_id`) AS `total` FROM `qr_codes`")->fetch_object()->total ?? 0;
            $total_track_links = database()->query("SELECT MAX(`id`) AS `total` FROM `track_links`")->fetch_object()->total ?? 0;
            if(\SeeGap\Plugin::is_active('aix')) {
                if(settings()->aix->documents_is_enabled) {
                    $total_documents = database()->query("SELECT MAX(`document_id`) AS `total` FROM `documents`")->fetch_object()->total ?? 0;
                }

                if(settings()->aix->images_is_enabled && settings()->aix->images_display_latest_on_index) {
                    $total_images = database()->query("SELECT MAX(`image_id`) AS `total` FROM `images`")->fetch_object()->total ?? 0;
                    $images = db()->orderBy('image_id', 'DESC')->get('images', 16);
                }
            }
            $stats = [
                'total_users' => $total_users,
                'total_links' => $total_links,
                'total_qr_codes' => $total_qr_codes,
                'total_track_links' => $total_track_links,
                'total_documents' => $total_documents ?? null,
                'total_images' => $total_images ?? null,
                'images' => $images ?? [],
            ];

            /* Save to cache */
            cache()->save($cache_instance->set($stats)->expiresAfter(3600));

        } else {

            /* Get cache */
            $stats = $cache_instance->get();
            extract($stats);

        }

        if(settings()->main->display_index_latest_blog_posts) {
            $language = \SeeGap\Language::$name;

            /* Blog posts query */
            $blog_posts_result_query = "
                SELECT * 
                FROM `blog_posts`
                WHERE (`language` = '{$language}' OR `language` IS NULL) AND `is_published` = 1 
                ORDER BY `blog_post_id` DESC
                LIMIT 3
            ";

            $blog_posts = \SeeGap\Cache::cache_function_result('blog_posts?hash=' . md5($blog_posts_result_query), 'blog_posts', function() use ($blog_posts_result_query) {
                $blog_posts_result = database()->query($blog_posts_result_query);

                /* Iterate over the blog posts */
                $blog_posts = [];

                while($row = $blog_posts_result->fetch_object()) {
                    /* Transform content if needed */
                    $row->content = json_decode($row->content) ? convert_editorjs_json_to_html($row->content) : nl2br($row->content);

                    $blog_posts[] = $row;
                }

                return $blog_posts;
            });
        }

        /* Get the available domains to use */
        $domains = (new Domain())->get_available_additional_domains();

        /* Main View */
        $view = new \SeeGap\View('index/index', (array) $this);
        $this->add_view_content('content', $view->run([
            'total_users' => $total_users,
            'total_links' => $total_links,
            'total_qr_codes' => $total_qr_codes,
            'total_track_links' => $total_track_links,
            'total_documents' => $total_documents ?? null,
            'total_images' => $total_images ?? null,
            'images' => $images ?? null,
            'blog_posts' => $blog_posts ?? [],
            'domains' => $domains,
        ]));

    }

}
