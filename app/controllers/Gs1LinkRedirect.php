<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers;

use Altum\Models\Domain;
use Altum\Models\Gs1Link;

defined('ALTUMCODE') || die();

class Gs1LinkRedirect extends Controller {

    public function index() {
        
        /* Check if we have a valid GS1 Digital Link URL pattern */
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        $parsed_gs1 = parse_gs1_digital_link($request_uri);
        
        if (!$parsed_gs1) {
            redirect();
        }
        
        $gtin = $parsed_gs1['gtin'];
        
        /* Determine the domain */
        $domain_id = 0;
        $domain = null;
        
        if (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
            $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
            
            /* Check if this is a custom domain */
            $domain_result = database()->query("SELECT * FROM `domains` WHERE `host` = '{$host}' AND `is_enabled` = 1");
            if ($domain_result && $domain_row = $domain_result->fetch_object()) {
                $domain_id = $domain_row->domain_id;
                $domain = $domain_row;
            }
        }
        
        /* Get the GS1 link */
        $gs1_link_model = new Gs1Link();
        $gs1_link = $gs1_link_model->get_gs1_link_by_gtin($gtin, $domain_id);
        
        if (!$gs1_link) {
            redirect();
        }
        
        /* Track the click */
        $this->track_gs1_click($gs1_link);
        
        /* Increment click counter */
        $gs1_link_model->increment_click($gs1_link->gs1_link_id);
        
        /* Handle pixels if any */
        if (!empty($gs1_link->pixels_ids)) {
            foreach ($gs1_link->pixels_ids as $pixel_id) {
                /* Get pixel details and fire it */
                $pixel = database()->query("SELECT * FROM `pixels` WHERE `pixel_id` = {$pixel_id}")->fetch_object();
                if ($pixel) {
                    /* Add pixel to the page */
                    \Altum\Event::add_content($this->create_pixel_html($pixel), 'head');
                }
            }
        }
        
        /* Redirect to target URL */
        header('Location: ' . $gs1_link->target_url);
        die();
    }
    
    private function track_gs1_click($gs1_link) {
        /* Detect extra details about the user */
        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT'] ?? '');
        
        /* Detect the location */
        try {
            $maxmind = (new \MaxMind\Db\Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get(get_ip());
        } catch(\Exception $exception) {
            /* :) */
            $maxmind = false;
        }
        
        /* Process referrer */
        $referrer = $_SERVER['HTTP_REFERER'] ?? '';
        $referrer_host = '';
        $referrer_path = '';
        
        if (!empty($referrer)) {
            $referrer_parsed = parse_url($referrer);
            $referrer_host = $referrer_parsed['host'] ?? '';
            $referrer_path = $referrer_parsed['path'] ?? '';
        }
        
        /* Check for UTM parameters */
        $utm_source = $_GET['utm_source'] ?? null;
        $utm_medium = $_GET['utm_medium'] ?? null;
        $utm_campaign = $_GET['utm_campaign'] ?? null;
        
        /* Check if this is a unique visit */
        $is_unique = 0;
        $cookie_name = 'gs1_' . $gs1_link->gs1_link_id;
        
        if (!isset($_COOKIE[$cookie_name])) {
            $is_unique = 1;
            /* Set cookie for 24 hours */
            setcookie($cookie_name, '1', time() + 86400, '/');
        }
        
        /* Insert tracking data */
        $stmt = database()->prepare("
            INSERT INTO `track_gs1_links` 
            (`user_id`, `gs1_link_id`, `project_id`, `gtin`, `country_code`, `continent_code`, `city_name`, 
             `os_name`, `browser_name`, `referrer_host`, `referrer_path`, `device_type`, `browser_language`, 
             `utm_source`, `utm_medium`, `utm_campaign`, `is_unique`, `datetime`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $gs1_link->user_id,
            $gs1_link->gs1_link_id,
            $gs1_link->project_id,
            $gs1_link->gtin,
            $maxmind && isset($maxmind['country']['iso_code']) ? $maxmind['country']['iso_code'] : null,
            $maxmind && isset($maxmind['continent']['code']) ? $maxmind['continent']['code'] : null,
            $maxmind && isset($maxmind['city']['names']['en']) ? $maxmind['city']['names']['en'] : null,
            $whichbrowser->os->name ?? null,
            $whichbrowser->browser->name ?? null,
            $referrer_host ?: null,
            $referrer_path ?: null,
            get_device_type($_SERVER['HTTP_USER_AGENT'] ?? ''),
            $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null,
            $utm_source,
            $utm_medium,
            $utm_campaign,
            $is_unique
        ]);
    }
    
    private function create_pixel_html($pixel) {
        $html = '';
        
        switch($pixel->type) {
            case 'facebook':
                $html = "
                <!-- Facebook Pixel Code -->
                <script>
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '{$pixel->pixel}');
                fbq('track', 'PageView');
                </script>
                <noscript><img height=\"1\" width=\"1\" style=\"display:none\"
                src=\"https://www.facebook.com/tr?id={$pixel->pixel}&ev=PageView&noscript=1\"
                /></noscript>
                <!-- End Facebook Pixel Code -->
                ";
                break;
                
            case 'google_analytics':
                $html = "
                <!-- Global site tag (gtag.js) - Google Analytics -->
                <script async src=\"https://www.googletagmanager.com/gtag/js?id={$pixel->pixel}\"></script>
                <script>
                  window.dataLayer = window.dataLayer || [];
                  function gtag(){dataLayer.push(arguments);}
                  gtag('js', new Date());
                  gtag('config', '{$pixel->pixel}');
                </script>
                ";
                break;
                
            case 'google_tag_manager':
                $html = "
                <!-- Google Tag Manager -->
                <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','{$pixel->pixel}');</script>
                <!-- End Google Tag Manager -->
                ";
                break;
                
            default:
                $html = $pixel->pixel;
                break;
        }
        
        return $html;
    }
}
