<?php defined('SEEGAP') || die() ?>

<?php
/* Get the avatar image URL */
$avatar_image_url = !empty($data->link->settings->image) ? \SeeGap\Uploads::get_full_url('avatars') . $data->link->settings->image : null;

/* Get the cover image URL */
$cover_image_url = !empty($data->link->settings->cover_image) ? \SeeGap\Uploads::get_full_url('backgrounds') . $data->link->settings->cover_image : null;

/* Template-based styling */
$template_class = 'avatar-template-' . ($data->link->settings->template ?? 'classic');
$size = $data->link->settings->size ?? 100;
$avatar_shape = $data->link->settings->avatar_shape ?? 'circle';
$square_border_radius = $data->link->settings->square_border_radius ?? 8;

/* Cover image settings */
$cover_position = $data->link->settings->cover_position ?? 'center';
$cover_blur = $data->link->settings->cover_blur ?? 0;
$cover_overlay_color = $data->link->settings->cover_overlay_color ?? '#000000';
$cover_overlay_opacity = $data->link->settings->cover_overlay_opacity ?? 0;

/* Background color for transparent images */
$background_color = $data->link->settings->background_color ?? '#ffffff';
$is_transparent_image = $avatar_image_url && (pathinfo($data->link->settings->image, PATHINFO_EXTENSION) == 'png' || pathinfo($data->link->settings->image, PATHINFO_EXTENSION) == 'svg');

/* Verified badge settings */
$verified_badge = $data->link->settings->verified_badge ?? (object)['enabled' => false];

/* Hover effect */
$hover_effect = $data->link->settings->hover_effect ?? 'none';
?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <div class="d-flex flex-column align-items-center">
        <?php if($data->link->location_url): ?>
        <a href="<?= $data->link->location_url . $data->link->utm_query ?>" data-track-microsite-block-id="<?= $data->link->microsite_block_id ?>" target="<?= ($data->link->settings->open_in_new_tab ?? false) ? '_blank' : '_self' ?>" class="avatar-link-wrapper">
        <?php endif ?>

            <div class="avatar-block-container <?= $cover_image_url ? 'has-cover-image' : '' ?>" 
                 style="<?= $cover_image_url ? '--cover-image: url(' . $cover_image_url . '); --cover-blur: ' . $cover_blur . 'px; --cover-overlay-color: ' . $cover_overlay_color . '; --cover-overlay-opacity: ' . ($cover_overlay_opacity / 100) . ';' : '' ?>">
                
                <?php if($cover_image_url): ?>
                <!-- Cover Image Background -->
                <div class="cover-image-background"></div>
                <div class="cover-image-overlay"></div>
                <?php endif ?>
                
                <!-- Avatar Container -->
                <div class="avatar-container <?= $template_class ?> <?= 'avatar-size-' . $size ?> <?= 'avatar-shape-' . $avatar_shape ?> <?= 'avatar-position-' . $cover_position ?>" 
                     data-hover-effect="<?= $hover_effect ?>"
                     data-template="<?= $data->link->settings->template ?? 'classic' ?>"
                     style="<?= $avatar_shape == 'square' ? '--square-border-radius: ' . $square_border_radius . 'px;' : '' ?> <?= $is_transparent_image ? '--background-color: ' . $background_color . ';' : '' ?>">
                    
                    <?php if($avatar_image_url): ?>
                    <!-- Main Avatar Image -->
                    <img src="<?= $avatar_image_url ?>" 
                         class="avatar-image <?= $data->link->location_url ? ($data->microsite->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->microsite->settings->hover_animation ?? 'smooth') : null : null ?>" 
                         alt="<?= $data->link->settings->image_alt ?? '' ?>" 
                         loading="lazy" 
                         data-avatar />
                    <?php else: ?>
                    <!-- Avatar Placeholder -->
                    <div class="avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                    <?php endif ?>

                    <!-- Verified Badge -->
                    <?php if(($verified_badge->enabled ?? false)): ?>
                    <div class="verified-badge 
                               <?= 'badge-style-' . ($verified_badge->style ?? 'checkmark') ?> 
                               <?= 'badge-position-' . ($verified_badge->position ?? 'bottom_right') ?> 
                               <?= 'badge-size-' . ($verified_badge->size ?? 'medium') ?>"
                         style="color: <?= $verified_badge->color ?? '#1da1f2' ?>;">
                        <?php 
                        $badge_style = $verified_badge->style ?? 'checkmark';
                        switch($badge_style) {
                            case 'star':
                                echo '<i class="fas fa-star"></i>';
                                break;
                            case 'crown':
                                echo '<i class="fas fa-crown"></i>';
                                break;
                            case 'shield':
                                echo '<i class="fas fa-shield-alt"></i>';
                                break;
                            default:
                                echo '<i class="fas fa-check-circle"></i>';
                                break;
                        }
                        ?>
                    </div>
                    <?php endif ?>

                    <!-- Template-specific decorative elements -->
                    <div class="template-decorations"></div>
                </div>
            </div>

        <?php if($data->link->location_url): ?>
        </a>
        <?php endif ?>
    </div>
</div>

<style>
/* Base Avatar Block Styles */
.avatar-block-container {
    position: relative;
    display: inline-block;
    transition: all 0.3s ease;
}

.avatar-block-container.has-cover-image {
    padding: 40px;
    border-radius: 16px;
    overflow: hidden;
    min-width: 200px;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Cover Image Styles */
.cover-image-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: var(--cover-image);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    filter: blur(var(--cover-blur));
    z-index: 1;
}

.cover-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--cover-overlay-color);
    opacity: var(--cover-overlay-opacity);
    z-index: 2;
}

/* Avatar Container */
.avatar-container {
    position: relative;
    display: inline-block;
    transition: all 0.3s ease;
    z-index: 3;
}

.avatar-image {
    display: block;
    object-fit: cover;
    transition: all 0.3s ease;
    border: 0;
}

.avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 2rem;
}

.avatar-link-wrapper {
    text-decoration: none;
    display: inline-block;
}

/* Avatar Shapes */
.avatar-shape-circle .avatar-image,
.avatar-shape-circle .avatar-placeholder {
    border-radius: 50%;
}

.avatar-shape-square .avatar-image,
.avatar-shape-square .avatar-placeholder {
    border-radius: var(--square-border-radius);
}

/* Background Color for Transparent Images */
.avatar-container[style*="--background-color"] .avatar-image {
    background-color: var(--background-color);
}

/* Avatar Positioning on Cover Images */
.avatar-position-center {
    align-self: center;
    justify-self: center;
}

.avatar-position-top-left {
    align-self: flex-start;
    justify-self: flex-start;
}

.avatar-position-top-right {
    align-self: flex-start;
    justify-self: flex-end;
}

.avatar-position-bottom-left {
    align-self: flex-end;
    justify-self: flex-start;
}

.avatar-position-bottom-right {
    align-self: flex-end;
    justify-self: flex-end;
}

/* Mobile-First Responsive Sizes */
.avatar-size-64 .avatar-image,
.avatar-size-64 .avatar-placeholder { width: 64px; height: 64px; }
.avatar-size-80 .avatar-image,
.avatar-size-80 .avatar-placeholder { width: 80px; height: 80px; }
.avatar-size-96 .avatar-image,
.avatar-size-96 .avatar-placeholder { width: 96px; height: 96px; }
.avatar-size-100 .avatar-image,
.avatar-size-100 .avatar-placeholder { width: 100px; height: 100px; }
.avatar-size-120 .avatar-image,
.avatar-size-120 .avatar-placeholder { width: 120px; height: 120px; }
.avatar-size-128 .avatar-image,
.avatar-size-128 .avatar-placeholder { width: 128px; height: 128px; }
.avatar-size-140 .avatar-image,
.avatar-size-140 .avatar-placeholder { width: 140px; height: 140px; }
.avatar-size-160 .avatar-image,
.avatar-size-160 .avatar-placeholder { width: 160px; height: 160px; }

/* Template Styles - Shape-aware */
.avatar-template-gradient_ring {
    padding: 3px;
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #ff6b6b);
    background-size: 300% 300%;
    animation: gradientShift 3s ease infinite;
}

.avatar-template-gradient_ring.avatar-shape-circle {
    border-radius: 50%;
}

.avatar-template-gradient_ring.avatar-shape-square {
    border-radius: calc(var(--square-border-radius) + 3px);
}

.avatar-template-gradient_ring .avatar-image,
.avatar-template-gradient_ring .avatar-placeholder {
    border: 3px solid #fff;
}

.avatar-template-professional .avatar-image,
.avatar-template-professional .avatar-placeholder {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border: 2px solid #fff;
}

.avatar-template-creative {
    padding: 4px;
    background: linear-gradient(45deg, #ff9a9e, #fecfef, #fecfef);
    box-shadow: 0 0 0 3px #ff9a9e;
}

.avatar-template-creative.avatar-shape-circle {
    border-radius: 50%;
}

.avatar-template-creative.avatar-shape-square {
    border-radius: calc(var(--square-border-radius) + 4px);
}

.avatar-template-creative .avatar-image,
.avatar-template-creative .avatar-placeholder {
    border: 2px solid #fff;
}

.avatar-template-minimalist .avatar-image,
.avatar-template-minimalist .avatar-placeholder {
    border: 1px solid #dee2e6;
}

.avatar-template-neon_glow .avatar-image,
.avatar-template-neon_glow .avatar-placeholder {
    box-shadow: 0 0 20px rgba(102, 126, 234, 0.6), 0 0 40px rgba(102, 126, 234, 0.4);
    animation: neonPulse 2s ease-in-out infinite alternate;
}

.avatar-template-double_ring {
    padding: 2px;
    background: #007bff;
    box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.2);
}

.avatar-template-double_ring.avatar-shape-circle {
    border-radius: 50%;
}

.avatar-template-double_ring.avatar-shape-square {
    border-radius: calc(var(--square-border-radius) + 2px);
}

.avatar-template-double_ring .avatar-image,
.avatar-template-double_ring .avatar-placeholder {
    border: 2px solid #fff;
}

.avatar-template-status_ring {
    padding: 3px;
    background: linear-gradient(45deg, #28a745, #20c997);
    animation: statusPulse 1.5s ease-in-out infinite;
}

.avatar-template-status_ring.avatar-shape-circle {
    border-radius: 50%;
}

.avatar-template-status_ring.avatar-shape-square {
    border-radius: calc(var(--square-border-radius) + 3px);
}

.avatar-template-status_ring .avatar-image,
.avatar-template-status_ring .avatar-placeholder {
    border: 2px solid #fff;
}

/* Verified Badge Styles */
.verified-badge {
    position: absolute;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    z-index: 10;
}

/* Badge Positions */
.badge-position-bottom_right {
    bottom: 0;
    right: 0;
    transform: translate(25%, 25%);
}

.badge-position-top_right {
    top: 0;
    right: 0;
    transform: translate(25%, -25%);
}

.badge-position-bottom_left {
    bottom: 0;
    left: 0;
    transform: translate(-25%, 25%);
}

.badge-position-center_bottom {
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 50%);
}

/* Badge Sizes */
.badge-size-small {
    width: 20px;
    height: 20px;
    font-size: 10px;
}

.badge-size-medium {
    width: 24px;
    height: 24px;
    font-size: 12px;
}

.badge-size-large {
    width: 28px;
    height: 28px;
    font-size: 14px;
}

/* Hover Effects (Desktop Only) */
@media (hover: hover) {
    .avatar-container[data-hover-effect="zoom"]:hover .avatar-image,
    .avatar-container[data-hover-effect="zoom"]:hover .avatar-placeholder {
        transform: scale(1.05);
    }

    .avatar-container[data-hover-effect="glow"]:hover .avatar-image,
    .avatar-container[data-hover-effect="glow"]:hover .avatar-placeholder {
        box-shadow: 0 0 20px rgba(0, 123, 255, 0.5);
    }

    .avatar-container[data-hover-effect="rotate"]:hover .avatar-image,
    .avatar-container[data-hover-effect="rotate"]:hover .avatar-placeholder {
        transform: rotate(5deg);
    }

    .avatar-container[data-hover-effect="bounce"]:hover .avatar-image,
    .avatar-container[data-hover-effect="bounce"]:hover .avatar-placeholder {
        animation: bounceEffect 0.6s ease;
    }
}

/* Touch Feedback for Mobile */
@media (hover: none) {
    .avatar-link-wrapper:active .avatar-container {
        transform: scale(0.95);
    }
    
    .avatar-link-wrapper:active .avatar-image,
    .avatar-link-wrapper:active .avatar-placeholder {
        opacity: 0.8;
    }
}

/* Animations */
@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes neonPulse {
    from { box-shadow: 0 0 20px rgba(102, 126, 234, 0.6), 0 0 40px rgba(102, 126, 234, 0.4); }
    to { box-shadow: 0 0 30px rgba(102, 126, 234, 0.8), 0 0 60px rgba(102, 126, 234, 0.6); }
}

@keyframes statusPulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.8; }
}

@keyframes bounceEffect {
    0%, 20%, 60%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    80% { transform: translateY(-5px); }
}

/* Responsive Adjustments */
@media (max-width: 576px) {
    .avatar-block-container.has-cover-image {
        padding: 30px;
        min-width: 150px;
        min-height: 150px;
    }
    
    /* Slightly larger badges on mobile for better visibility */
    .badge-size-small { width: 22px; height: 22px; font-size: 11px; }
    .badge-size-medium { width: 26px; height: 26px; font-size: 13px; }
    .badge-size-large { width: 30px; height: 30px; font-size: 15px; }
    
    /* Reduce animation intensity on mobile to save battery */
    .avatar-template-gradient_ring {
        animation-duration: 4s;
    }
    
    .avatar-template-neon_glow .avatar-image,
    .avatar-template-neon_glow .avatar-placeholder {
        animation-duration: 3s;
    }
}

@media (max-width: 320px) {
    .avatar-block-container.has-cover-image {
        padding: 20px;
        min-width: 120px;
        min-height: 120px;
    }
    
    /* Extra small screens - reduce sizes slightly */
    .avatar-size-120 .avatar-image,
    .avatar-size-120 .avatar-placeholder { width: 100px; height: 100px; }
    .avatar-size-140 .avatar-image,
    .avatar-size-140 .avatar-placeholder { width: 120px; height: 120px; }
    .avatar-size-160 .avatar-image,
    .avatar-size-160 .avatar-placeholder { width: 140px; height: 140px; }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .avatar-template-minimalist .avatar-image,
    .avatar-template-minimalist .avatar-placeholder {
        border: 2px solid #000;
    }
    
    .verified-badge {
        border: 1px solid #000;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .avatar-container,
    .avatar-image,
    .avatar-placeholder,
    .verified-badge {
        animation: none !important;
        transition: none !important;
    }
}

/* Print styles */
@media print {
    .verified-badge {
        background: #000 !important;
        color: #fff !important;
    }
    
    .cover-image-background {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Respect user's reduced motion preference
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('.avatar-container').forEach(container => {
            container.style.animation = 'none';
            container.style.transition = 'none';
        });
    }
    
    // Add touch feedback for mobile devices
    if ('ontouchstart' in window) {
        document.querySelectorAll('.avatar-link-wrapper').forEach(link => {
            link.addEventListener('touchstart', function() {
                this.classList.add('touch-active');
            });
            
            link.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touch-active');
                }, 150);
            });
        });
    }
    
    // Lazy load optimization for avatars
    if ('IntersectionObserver' in window) {
        const avatarObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        avatarObserver.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('.avatar-image[data-src]').forEach(img => {
            avatarObserver.observe(img);
        });
    }
});
</script>
