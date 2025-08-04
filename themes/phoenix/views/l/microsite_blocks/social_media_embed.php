<?php defined('SEEGAP') || die() ?>

<?php
/* Generate all styles based on settings - following Image Block pattern exactly */
$all_styles = [];
$animation_class = '';

// Get social media embed settings
$embed_settings = $data->link->settings;

// Get embed settings
$platform = $embed_settings->platform ?? 'youtube';
$embed_type = $embed_settings->embed_type ?? 'video';
$embed_data = $embed_settings->embed_data ?? (object)[];
$responsive = $embed_settings->responsive ?? true;
$open_in_new_tab = $embed_settings->open_in_new_tab ?? false;

// Handle background color
if (isset($embed_settings->background_color) && $embed_settings->background_color !== '#00000000') {
    $all_styles[] = 'background-color: ' . $embed_settings->background_color;
} else {
    $all_styles[] = 'background-color: #0000001A';
}

// Handle border - following exact image block pattern
if (isset($embed_settings->border_width) && $embed_settings->border_width > 0) {
    $border_width = $embed_settings->border_width;
    $border_color = $embed_settings->border_color ?? '#dee2e6';
    $border_style = $embed_settings->border_style ?? 'solid';
    $all_styles[] = 'border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color;
}

// Handle border radius - following exact image block pattern
if (isset($embed_settings->border_radius)) {
    switch ($embed_settings->border_radius) {
        case 'straight':
            $all_styles[] = 'border-radius: 0';
            break;
        case 'round':
            $all_styles[] = 'border-radius: 50px';
            break;
        case 'rounded':
            $all_styles[] = 'border-radius: 0.25rem';
            break;
        case 'rounded-sm':
            $all_styles[] = 'border-radius: 0.125rem';
            break;
        case 'rounded-lg':
            $all_styles[] = 'border-radius: 0.5rem';
            break;
        case 'rounded-xl':
            $all_styles[] = 'border-radius: 0.75rem';
            break;
        case 'rounded-2xl':
            $all_styles[] = 'border-radius: 1rem';
            break;
        case 'rounded-3xl':
            $all_styles[] = 'border-radius: 1.5rem';
            break;
        case 'rounded-full':
            $all_styles[] = 'border-radius: 9999px';
            break;
    }
} else if (isset($embed_settings->border_radius) && is_numeric($embed_settings->border_radius)) {
    $all_styles[] = 'border-radius: ' . $embed_settings->border_radius . 'px';
}

// Handle shadow - following exact image block pattern
if (isset($embed_settings->border_shadow_blur) && $embed_settings->border_shadow_blur > 0) {
    $shadow_x = $embed_settings->border_shadow_offset_x ?? 0;
    $shadow_y = $embed_settings->border_shadow_offset_y ?? 0;
    $shadow_blur = $embed_settings->border_shadow_blur ?? 0;
    $shadow_spread = $embed_settings->border_shadow_spread ?? 0;
    $shadow_color = $embed_settings->border_shadow_color ?? '#00000010';
    $all_styles[] = 'box-shadow: ' . $shadow_x . 'px ' . $shadow_y . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color;
} else if (isset($embed_settings->box_shadow)) {
    $all_styles[] = 'box-shadow: ' . $embed_settings->box_shadow;
}

// Handle animation - following exact image block pattern
if (isset($embed_settings->animation) && $embed_settings->animation && $embed_settings->animation !== 'false') {
    $animation_class = 'animate__animated animate__' . $embed_settings->animation;
    if (isset($embed_settings->animation_runs) && $embed_settings->animation_runs !== 'repeat-1') {
        $animation_class .= ' animate__' . $embed_settings->animation_runs;
    }
    if (isset($embed_settings->animation_delay) && $embed_settings->animation_delay > 0) {
        $delay_class = 'animate__delay-' . ($embed_settings->animation_delay / 1000) . 's';
        $animation_class .= ' ' . $delay_class;
    }
}

$style_attribute = !empty($all_styles) ? 'style="' . implode('; ', $all_styles) . ';"' : '';

// Generate embed HTML based on platform and type
$embed_html = '';
$embed_url = $embed_data->url ?? '';

switch($platform) {
    case 'youtube':
        if($embed_type === 'video' && $embed_url) {
            // Extract video ID from URL
            preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|shorts\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})/', $embed_url, $match);
            $video_id = $match[1] ?? null;
            
            if($video_id) {
                $width = $embed_data->width ?? 560;
                $height = $embed_data->height ?? 315;
                
                $embed_html = '<div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" 
                            src="https://www.youtube.com/embed/' . $video_id . '" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                    </iframe>
                </div>';
            }
        }
        break;
        
    case 'instagram':
        if($embed_url && preg_match('/instagram\.com\/p\/([a-zA-Z0-9_-]+)/', $embed_url, $match)) {
            $post_id = $match[1];
            $embed_html = '<blockquote class="instagram-media" 
                                    data-instgrm-permalink="' . htmlspecialchars($embed_url) . '" 
                                    data-instgrm-version="14">
                            <div style="padding: 16px;">
                                <a href="' . htmlspecialchars($embed_url) . '" 
                                   style="background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;" 
                                   target="_blank">
                                    <div style="display: flex; flex-direction: row; align-items: center;">
                                        <div style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;"></div>
                                        <div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;">
                                            <div style="background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;"></div>
                                            <div style="background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;"></div>
                                        </div>
                                    </div>
                                    <div style="padding: 19% 0;"></div>
                                    <div style="display:block; height:50px; margin:0 auto 12px; width:50px;">
                                        <svg width="50px" height="50px" viewBox="0 0 60 60" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-511.000000, -20.000000)" fill="#000000">
                                                    <g>
                                                        <path d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <div style="padding-top: 8px;">
                                        <div style="color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;">View this post on Instagram</div>
                                    </div>
                                </a>
                            </div>
                        </blockquote>';
        }
        break;
        
    case 'twitter':
        if($embed_url && preg_match('/twitter\.com\/\w+\/status\/(\d+)/', $embed_url)) {
            $theme = $embed_data->theme ?? 'light';
            $embed_html = '<blockquote class="twitter-tweet" data-theme="' . $theme . '">
                            <p lang="en" dir="ltr">Loading tweet...</p>
                            <a href="' . htmlspecialchars($embed_url) . '">View Tweet</a>
                        </blockquote>';
        }
        break;
        
    case 'tiktok':
        if($embed_url && preg_match('/tiktok\.com\/@[\w.-]+\/video\/(\d+)/', $embed_url, $match)) {
            $video_id = $match[1];
            $embed_html = '<blockquote class="tiktok-embed" 
                                    cite="' . htmlspecialchars($embed_url) . '" 
                                    data-video-id="' . $video_id . '" 
                                    style="max-width: 605px;min-width: 325px;">
                            <section>
                                <a target="_blank" title="View on TikTok" href="' . htmlspecialchars($embed_url) . '">View on TikTok</a>
                            </section>
                        </blockquote>';
        }
        break;
        
    case 'facebook':
        if($embed_url) {
            $embed_html = '<div class="fb-post" 
                                data-href="' . htmlspecialchars($embed_url) . '" 
                                data-width="auto" 
                                data-show-text="true">
                            <blockquote cite="' . htmlspecialchars($embed_url) . '" class="fb-xfbml-parse-ignore">
                                <a href="' . htmlspecialchars($embed_url) . '">View on Facebook</a>
                            </blockquote>
                        </div>';
        }
        break;
        
    case 'threads':
        if($embed_url) {
            $embed_html = '<blockquote class="text-post-media" data-text-post-permalink="' . htmlspecialchars($embed_url) . '">
                            <a href="' . htmlspecialchars($embed_url) . '">View on Threads</a>
                        </blockquote>';
        }
        break;
        
    case 'telegram':
        if($embed_url && preg_match('/t\.me\/([^\/]+)\/(\d+)/', $embed_url, $match)) {
            $channel = $match[1];
            $post_id = $match[2];
            $embed_html = '<script async src="https://telegram.org/js/telegram-widget.js?22" 
                                  data-telegram-post="' . $channel . '/' . $post_id . '" 
                                  data-width="100%">
                        </script>';
        }
        break;
}

// Fallback if no embed HTML generated
if(empty($embed_html)) {
    $embed_html = '<div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Unable to load ' . ucfirst($platform) . ' embed. Please check the URL and try again.
                </div>';
}
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" 
     data-microsite-block-id="<?= $data->link->microsite_block_id ?>" 
     data-microsite-block-type="<?= $data->link->type ?>" 
     class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?> <?= $animation_class ?>">
    
    <div class="social-media-embed-container" <?= $style_attribute ?>>
        
        <?php if($responsive): ?>
            <div class="embed-responsive-wrapper">
                <?= $embed_html ?>
            </div>
        <?php else: ?>
            <?= $embed_html ?>
        <?php endif ?>
        
    </div>
    
</div>

<?php
// Load platform-specific scripts
switch($platform) {
    case 'instagram':
        if(!\SeeGap\Event::exists_content_type_key('javascript', 'instagram')): ?>
            <?php ob_start() ?>
            <script async src="//www.instagram.com/embed.js"></script>
            <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'instagram') ?>
        <?php endif;
        break;
        
    case 'twitter':
        if(!\SeeGap\Event::exists_content_type_key('javascript', 'twitter')): ?>
            <?php ob_start() ?>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'twitter') ?>
        <?php endif;
        break;
        
    case 'tiktok':
        if(!\SeeGap\Event::exists_content_type_key('javascript', 'tiktok')): ?>
            <?php ob_start() ?>
            <script async src="https://www.tiktok.com/embed.js"></script>
            <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'tiktok') ?>
        <?php endif;
        break;
        
    case 'facebook':
        if(!\SeeGap\Event::exists_content_type_key('javascript', 'facebook')): ?>
            <?php ob_start() ?>
            <div id="fb-root"></div>
            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0"></script>
            <?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript', 'facebook') ?>
        <?php endif;
        break;
}
?>

<?php if(!\SeeGap\Event::exists_content_type_key('head', 'social_media_embed')): ?>
    <?php ob_start() ?>
    <style>
        .social-media-embed-container {
            overflow: hidden;
            position: relative;
        }
        
        .embed-responsive-wrapper {
            position: relative;
            width: 100%;
        }
        
        .embed-responsive-wrapper iframe,
        .embed-responsive-wrapper blockquote {
            max-width: 100%;
            width: 100%;
        }
        
        /* Instagram specific styles */
        .instagram-media {
            background: #FFF;
            border: 0;
            border-radius: 3px;
            box-shadow: 0 0 1px 0 rgba(0,0,0,0.5), 0 1px 10px 0 rgba(0,0,0,0.15);
            margin: 1px;
            max-width: 540px;
            min-width: 326px;
            padding: 0;
            width: calc(100% - 2px);
        }
        
        /* Twitter specific styles */
        .twitter-tweet {
            margin: 0 auto !important;
        }
        
        /* TikTok specific styles */
        .tiktok-embed {
            margin: 0 auto;
        }
        
        /* Facebook specific styles */
        .fb-post {
            margin: 0 auto;
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .social-media-embed-container {
                margin: 0 -15px;
            }
            
            .embed-responsive-wrapper {
                padding: 0 15px;
            }
        }
    </style>
    <?php \SeeGap\Event::add_content(ob_get_clean(), 'head', 'social_media_embed') ?>
<?php endif ?>
