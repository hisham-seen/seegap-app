<?php defined('SEEGAP') || die() ?>

<?php
// Load splash page backgrounds for rendering
$splash_page_backgrounds = require APP_PATH . 'includes/splash_page_backgrounds.php';

// Get background settings
$background_type = $data->splash_page->settings->background_type ?? 'preset';
$background = $data->splash_page->settings->background ?? 'ocean';

// Generate background CSS
$background_css = '';
if ($background_type == 'preset' && isset($splash_page_backgrounds['preset'][$background])) {
    $background_css = $splash_page_backgrounds['preset'][$background];
} elseif ($background_type == 'solid') {
    $solid_color = $data->splash_page->settings->background ?? '#667eea';
    $background_css = "background: {$solid_color};";
} elseif ($background_type == 'gradient') {
    $color_one = $data->splash_page->settings->background_color_one ?? '#667eea';
    $color_two = $data->splash_page->settings->background_color_two ?? '#764ba2';
    $background_css = "background: linear-gradient(135deg, {$color_one} 0%, {$color_two} 100%);";
} elseif ($background_type == 'image' && !empty($data->splash_page->settings->background)) {
    $overlay_color = $data->splash_page->settings->background_overlay_color ?? '#000000';
    $overlay_opacity = ($data->splash_page->settings->background_overlay_opacity ?? 50) / 100;
    $background_size = $data->splash_page->settings->background_size ?? 'cover';
    $background_position = $data->splash_page->settings->background_position ?? 'center';
    $image_url = \SeeGap\Uploads::get_full_url('backgrounds') . $data->splash_page->settings->background;
    
    // Convert hex to rgb
    $r = hexdec(substr($overlay_color, 1, 2));
    $g = hexdec(substr($overlay_color, 3, 2));
    $b = hexdec(substr($overlay_color, 5, 2));
    
    $background_css = "background: linear-gradient(rgba({$r},{$g},{$b},{$overlay_opacity}), rgba({$r},{$g},{$b},{$overlay_opacity})), url('{$image_url}'); background-size: {$background_size}; background-position: {$background_position}; background-attachment: fixed;";
} elseif ($background_type == 'video' && !empty($data->splash_page->settings->background_video_url)) {
    $overlay_color = $data->splash_page->settings->background_overlay_color ?? '#000000';
    $overlay_opacity = ($data->splash_page->settings->background_overlay_opacity ?? 50) / 100;
    
    // Convert hex to rgb
    $r = hexdec(substr($overlay_color, 1, 2));
    $g = hexdec(substr($overlay_color, 3, 2));
    $b = hexdec(substr($overlay_color, 5, 2));
    
    $background_css = "background: linear-gradient(rgba({$r},{$g},{$b},{$overlay_opacity}), rgba({$r},{$g},{$b},{$overlay_opacity})), #000000;";
} else {
    // Default background
    $background_css = 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);';
}

// Generate button styles
function generateButtonStyle($settings, $button_type) {
    $use_primary = $button_type === 'secondary' && ($settings->secondary_use_primary_settings ?? false);
    $prefix = $use_primary ? 'primary' : $button_type;
    
    $bg_color = $settings->{$prefix . '_button_bg_color'} ?? ($button_type === 'primary' ? '#007bff' : '#6c757d');
    $text_color = $settings->{$prefix . '_button_text_color'} ?? '#ffffff';
    $border_color = $settings->{$prefix . '_button_border_color'} ?? ($button_type === 'primary' ? '#007bff' : '#6c757d');
    $style = $settings->{$prefix . '_button_style'} ?? ($button_type === 'primary' ? 'solid' : 'outline');
    $shape = $settings->{$prefix . '_button_shape'} ?? 'rounded';
    $size = $settings->{$prefix . '_button_size'} ?? 'medium';
    
    // Generate CSS
    $css = '';
    
    // Style
    if ($style === 'solid') {
        $css .= "background-color: {$bg_color}; color: {$text_color}; border-color: {$border_color};";
    } elseif ($style === 'outline') {
        $css .= "background-color: transparent; color: {$bg_color}; border: 2px solid {$bg_color};";
    } elseif ($style === 'gradient') {
        $css .= "background: linear-gradient(135deg, {$bg_color} 0%, {$border_color} 100%); color: {$text_color}; border: none;";
    }
    
    // Shape
    if ($shape === 'square') {
        $css .= " border-radius: 4px;";
    } elseif ($shape === 'rounded') {
        $css .= " border-radius: 8px;";
    } elseif ($shape === 'pill') {
        $css .= " border-radius: 25px;";
    }
    
    // Size
    if ($size === 'small') {
        $css .= " padding: 8px 16px; font-size: 14px;";
    } elseif ($size === 'medium') {
        $css .= " padding: 12px 24px; font-size: 16px;";
    } elseif ($size === 'large') {
        $css .= " padding: 16px 32px; font-size: 18px;";
    }
    
    return $css;
}

$primary_button_style = generateButtonStyle($data->splash_page->settings, 'primary');
$secondary_button_style = generateButtonStyle($data->splash_page->settings, 'secondary');
?>

<div class="splash-page-container" style="<?= $background_css ?> min-height: 100vh; display: flex; align-items: center; justify-content: center; position: relative;">
    
    <!-- Video Background (if enabled) -->
    <?php if ($background_type == 'video' && !empty($data->splash_page->settings->background_video_url)): ?>
        <?php
        $video_url = $data->splash_page->settings->background_video_url;
        $video_id = '';
        $video_platform = '';
        
        // Extract video ID and platform
        if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
            $video_platform = 'youtube';
            if (strpos($video_url, 'youtu.be') !== false) {
                $video_id = substr(parse_url($video_url, PHP_URL_PATH), 1);
            } else {
                parse_str(parse_url($video_url, PHP_URL_QUERY), $query);
                $video_id = $query['v'] ?? '';
            }
        } elseif (strpos($video_url, 'vimeo.com') !== false) {
            $video_platform = 'vimeo';
            $video_id = substr(parse_url($video_url, PHP_URL_PATH), 1);
        }
        
        if ($video_id && $video_platform):
            $autoplay = $data->splash_page->settings->background_video_autoplay ?? true ? 1 : 0;
            $loop = $data->splash_page->settings->background_video_loop ?? true ? 1 : 0;
            $mute = $data->splash_page->settings->background_video_mute ?? true ? 1 : 0;
            $controls = $data->splash_page->settings->background_video_controls ?? false ? 1 : 0;
        ?>
        <div class="video-background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden;">
            <?php if ($video_platform === 'youtube'): ?>
                <iframe 
                    src="https://www.youtube.com/embed/<?= $video_id ?>?autoplay=<?= $autoplay ?>&loop=<?= $loop ?>&mute=<?= $mute ?>&controls=<?= $controls ?>&playlist=<?= $video_id ?>&rel=0&showinfo=0&modestbranding=1"
                    style="position: absolute; top: 50%; left: 50%; width: 100vw; height: 56.25vw; min-height: 100vh; min-width: 177.77vh; transform: translate(-50%, -50%);"
                    frameborder="0" 
                    allow="autoplay; encrypted-media" 
                    allowfullscreen>
                </iframe>
            <?php elseif ($video_platform === 'vimeo'): ?>
                <iframe 
                    src="https://player.vimeo.com/video/<?= $video_id ?>?autoplay=<?= $autoplay ?>&loop=<?= $loop ?>&muted=<?= $mute ?>&controls=<?= $controls ?>&background=1"
                    style="position: absolute; top: 50%; left: 50%; width: 100vw; height: 56.25vw; min-height: 100vh; min-width: 177.77vh; transform: translate(-50%, -50%);"
                    frameborder="0" 
                    allow="autoplay; fullscreen; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            <?php endif ?>
        </div>
        <?php endif ?>
    <?php endif ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 py-6">
                <?= \SeeGap\Alerts::output_alerts() ?>

                <div class="text-center text-white">
                    <?php if($data->splash_page->settings->logo): ?>
                    <div class="d-flex flex-column align-items-center mb-4">
                        <img src="<?= \SeeGap\Uploads::get_full_url('splash_pages') . $data->splash_page->settings->logo ?>" class="link-image link-avatar-round" style="max-width: 120px; max-height: 80px; object-fit: contain;" />
                    </div>
                    <?php endif ?>

                    <h1 class="h2 mb-3 font-weight-bold" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                        <?= $data->splash_page->title ?? l('link.splash.header') ?>
                    </h1>
                    
                    <p class="lead mb-4" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3); max-width: 400px; margin: 0 auto;">
                        <?= $data->splash_page->description ?? l('link.splash.subheader') ?>
                    </p>

                    <div class="splash-buttons-container mt-4" style="max-width: 300px; margin: 0 auto;">
                        <!-- Primary Button (Continue) -->
                        <a href="#" id="link_continue" class="btn btn-block mb-3 disabled" style="<?= $primary_button_style ?> font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2); text-decoration: none;">
                            <i class="fas fa-fw fa-sm fa-link mr-1"></i> <?= l('link.splash.continue') ?>
                        </a>
                        
                        <!-- Secondary Button (if enabled) -->
                        <?php if(!empty($data->splash_page->settings->secondary_button_name)): ?>
                        <a href="<?= $data->splash_page->settings->secondary_button_url ?? url() ?>" class="btn btn-block" style="<?= $secondary_button_style ?> text-decoration: none;">
                            <?php if(!$data->splash_page->settings->secondary_button_name): ?>
                            <i class="fas fa-fw fa-sm fa-home mr-1"></i>
                            <?php endif ?>
                            <?= $data->splash_page->settings->secondary_button_name ?? l('link.splash.home') ?>
                        </a>
                        <?php endif ?>
                    </div>

                    <div class="text-white mt-3" id="link_unlock_seconds" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                        <?= sprintf(l('link.splash.link_unlock_seconds'), $data->splash_page->link_unlock_seconds ?? settings()->links->splash_page_link_unlock_seconds) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    let link_unlock_seconds = <?= $data->splash_page->link_unlock_seconds ?? settings()->links->splash_page_link_unlock_seconds ?>;
    let splash_page_auto_redirect = <?= json_encode((bool) ($data->splash_page->auto_redirect ?? settings()->links->splash_page_auto_redirect)) ?>;

    let link_unlock_seconds_remaining = link_unlock_seconds;

    let countdown = setInterval(() => {
        document.querySelector('#link_unlock_seconds').innerHTML = <?= json_encode(l('link.splash.link_unlock_seconds')) ?>.replace('%s', link_unlock_seconds_remaining);

        link_unlock_seconds_remaining -= 1;

        if(link_unlock_seconds_remaining < 0) {
            clearInterval(countdown);
            document.querySelector('#link_unlock_seconds').classList.add('d-none');
            
            const continueButton = document.querySelector('#link_continue');
            continueButton.classList.remove('disabled');
            continueButton.href = <?= json_encode($this->link->full_url) ?>;
            
            // Add hover effects
            continueButton.style.cursor = 'pointer';
            continueButton.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 6px 20px rgba(0,0,0,0.3)';
            });
            continueButton.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
            });

            if(splash_page_auto_redirect) {
                window.location.replace(<?= json_encode($this->link->full_url) ?>);
            }

            set_cookie(<?= json_encode('link_unlocked_' . $this->link->link_id) ?>, <?= json_encode(md5($this->link->link_id . $this->link->link_id)) ?>, 1, <?= json_encode(COOKIE_PATH) ?>);
        }
    }, 1000);
</script>
<?php \SeeGap\Event::add_content(ob_get_clean(), 'javascript') ?>
