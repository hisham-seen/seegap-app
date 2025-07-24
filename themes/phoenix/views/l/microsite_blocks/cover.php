<?php defined('SEEGAP') || die() ?>

<div id="<?= 'microsite_block_id_' . $data->link->microsite_block_id ?>" data-microsite-block-id="<?= $data->link->microsite_block_id ?>" data-microsite-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->microsite->settings->block_spacing ?? '2' ?>">
    <?php 
    // Check if we have any content to display
    $has_content = !empty($data->link->settings->name) || !empty($data->link->settings->avatar) || !empty($data->link->settings->background);
    $has_background = ($data->link->settings->background_type === 'image' && !empty($data->link->settings->background));
    $has_video = ($data->link->settings->background_type === 'video' && !empty($data->link->settings->video_url) && !empty($data['embed']));
    $has_avatar = !empty($data->link->settings->avatar);
    $has_name = !empty($data->link->settings->name);
    ?>
    
    <div class="cover-block-modern position-relative overflow-hidden" style="
        border-radius: 20px;
        min-height: 280px;
        background: <?= $has_background ? 'transparent' : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' ?>;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: <?= $data->link->settings->border_width ?? 0 ?>px <?= $data->link->settings->border_style ?? 'solid' ?> <?= $data->link->settings->border_color ?? 'transparent' ?>;
    ">
        
        <?php if($has_background): ?>
            <!-- Background Image -->
            <div class="cover-background position-absolute w-100 h-100" style="
                background-image: url('<?= \SeeGap\Uploads::get_full_url('backgrounds') . $data->link->settings->background ?>');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                top: 0;
                left: 0;
                z-index: 1;
            "></div>
            
            <!-- Gradient Overlay -->
            <div class="cover-overlay position-absolute w-100 h-100" style="
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
                top: 0;
                left: 0;
                z-index: 2;
            "></div>
        <?php elseif($has_video): ?>
            <!-- Video Background -->
            <div class="cover-video position-absolute w-100 h-100" style="top: 0; left: 0; z-index: 1;">
                <iframe 
                    src="https://www.youtube.com/embed/<?= $data['embed'] ?>?<?= http_build_query([
                        'controls' => $data->link->settings->video_controls ?? 0,
                        'autoplay' => $data->link->settings->video_autoplay ?? 1,
                        'loop' => $data->link->settings->video_loop ?? 1,
                        'mute' => $data->link->settings->video_muted ?? 1,
                        'playlist' => $data['embed']
                    ]) ?>" 
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    allowfullscreen
                    style="object-fit: cover;"
                ></iframe>
            </div>
            
            <!-- Video Overlay -->
            <div class="cover-overlay position-absolute w-100 h-100" style="
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.7) 0%, rgba(118, 75, 162, 0.7) 100%);
                top: 0;
                left: 0;
                z-index: 2;
            "></div>
        <?php endif ?>
        
        <!-- Content Container -->
        <div class="cover-content position-relative d-flex flex-column align-items-center justify-content-center text-center h-100" style="
            z-index: 3;
            padding: 40px 20px;
            color: white;
        ">
            
            <?php if($has_avatar): ?>
                <!-- Avatar with modern styling -->
                <div class="cover-avatar-container mb-4" style="position: relative;">
                    <div class="cover-avatar-ring" style="
                        width: <?= ($data->link->settings->avatar_size ?? 100) + 8 ?>px;
                        height: <?= ($data->link->settings->avatar_size ?? 100) + 8 ?>px;
                        border-radius: 50%;
                        background: linear-gradient(45deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1));
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        backdrop-filter: blur(10px);
                        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
                    ">
                        <img 
                            src="<?= \SeeGap\Uploads::get_full_url('avatars') . $data->link->settings->avatar ?>" 
                            alt="<?= $data->link->settings->avatar_alt ?? '' ?>"
                            class="cover-avatar-image"
                            style="
                                width: <?= $data->link->settings->avatar_size ?? 100 ?>px;
                                height: <?= $data->link->settings->avatar_size ?? 100 ?>px;
                                border-radius: 50%;
                                object-fit: cover;
                                border: 3px solid rgba(255,255,255,0.3);
                            "
                        />
                    </div>
                </div>
            <?php endif ?>
            
            <?php if($has_name): ?>
                <!-- Name with modern typography -->
                <h1 class="cover-name mb-2" style="
                    font-size: 2.2rem;
                    font-weight: 700;
                    color: white;
                    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
                    margin: 0;
                    letter-spacing: -0.02em;
                ">
                    <?= $data->link->settings->name ?>
                </h1>
            <?php endif ?>
            
            <?php if(!empty($data->link->settings->description)): ?>
                <!-- Description/Subtitle -->
                <p class="cover-description mb-0" style="
                    font-size: 1.1rem;
                    font-weight: 400;
                    color: rgba(255,255,255,0.9);
                    text-shadow: 0 1px 5px rgba(0,0,0,0.2);
                    margin: 0;
                    max-width: 300px;
                ">
                    <?= $data->link->settings->description ?>
                </p>
            <?php endif ?>
            
            <?php if(!$has_content): ?>
                <!-- Placeholder content -->
                <div class="cover-placeholder text-center">
                    <div class="placeholder-icon mb-3" style="
                        width: 80px;
                        height: 80px;
                        border-radius: 50%;
                        background: rgba(255,255,255,0.2);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                        backdrop-filter: blur(10px);
                    ">
                        <i class="fas fa-user" style="font-size: 2rem; color: rgba(255,255,255,0.8);"></i>
                    </div>
                    <h2 style="
                        color: white;
                        margin: 0 0 8px 0;
                        font-size: 1.8rem;
                        font-weight: 600;
                        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
                    ">Cover Block</h2>
                    <p style="
                        color: rgba(255,255,255,0.8);
                        margin: 0;
                        font-size: 1rem;
                        text-shadow: 0 1px 5px rgba(0,0,0,0.2);
                    ">Add a name, avatar, or background to customize</p>
                </div>
            <?php endif ?>
            
        </div>
        
        <!-- Decorative elements -->
        <div class="cover-decoration position-absolute" style="
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            z-index: 4;
            display: flex;
            align-items: center;
            justify-content: center;
        ">
            <i class="fas fa-star" style="color: rgba(255,255,255,0.6); font-size: 1.2rem;"></i>
        </div>
        
    </div>
</div>
