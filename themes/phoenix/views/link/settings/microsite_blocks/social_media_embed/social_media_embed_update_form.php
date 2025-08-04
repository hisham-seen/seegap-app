<?php defined('SEEGAP') || die() ?>

<form name="update_microsite_block" method="post" role="form">
    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="social_media_embed" />
    <input type="hidden" name="microsite_block_id" value="<?= $row->microsite_block_id ?>" />

    <div class="notification-container"></div>

    <?php
    // Define tabs for the Social Media Embed block
    $tabs = [
        [
            'id' => 'content',
            'title' => 'Content',
            'icon' => 'fas fa-share-alt'
        ],
        [
            'id' => 'style',
            'title' => 'Style',
            'icon' => 'fas fa-palette'
        ],
        [
            'id' => 'display',
            'title' => 'Display',
            'icon' => 'fas fa-eye'
        ]
    ];

    // Set the block_id for the tab component
    $block_id = 'social-media-embed-' . $row->microsite_block_id;
    
    // Include the reusable tab navigation
    include THEME_PATH . 'views/partials/microsite_block_tabs.php';
    ?>

    <div class="tab-content" id="social-media-embed-<?= $row->microsite_block_id ?>-tabContent">
        
        <!-- Content Tab -->
        <div class="tab-pane fade show active" id="social-media-embed-<?= $row->microsite_block_id ?>-content" role="tabpanel" aria-labelledby="social-media-embed-<?= $row->microsite_block_id ?>-content-tab">
            
            <!-- Social Media Embed Manager -->
            <?php
            $block_id = $row->microsite_block_id;
            $settings = $row->settings;
            $collapsed = false; // Show expanded by default in content tab
            include THEME_PATH . 'views/partials/microsite_block_components/social_media_embed_manager.php';
            ?>

            <!-- Platform Information -->
            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Supported Platforms:</strong>
                <ul class="mb-0 mt-2">
                    <li><strong>YouTube:</strong> Videos, channels, and playlists</li>
                    <li><strong>Instagram:</strong> Posts and reels</li>
                    <li><strong>Twitter/X:</strong> Tweets, profiles, and video tweets</li>
                    <li><strong>TikTok:</strong> Videos and profiles</li>
                    <li><strong>Facebook:</strong> Posts, pages, and videos</li>
                    <li><strong>Threads:</strong> Posts and profiles</li>
                    <li><strong>Telegram:</strong> Channel posts and widgets</li>
                </ul>
            </div>

            <!-- Quick Tips -->
            <div class="card">
                <div class="card-header" data-toggle="collapse" data-target="#embed-tips-<?= $row->microsite_block_id ?>" aria-expanded="false" style="cursor: pointer;">
                    <h6 class="mb-0">
                        <i class="fas fa-fw fa-lightbulb fa-sm text-muted mr-2"></i>
                        Quick Tips & Examples
                        <i class="fas fa-chevron-down float-right"></i>
                    </h6>
                </div>
                <div id="embed-tips-<?= $row->microsite_block_id ?>" class="collapse">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">URL Examples</h6>
                                <ul class="list-unstyled small">
                                    <li><strong>YouTube:</strong> <code>youtube.com/watch?v=dQw4w9WgXcQ</code></li>
                                    <li><strong>Instagram:</strong> <code>instagram.com/p/ABC123/</code></li>
                                    <li><strong>Twitter:</strong> <code>twitter.com/user/status/123456</code></li>
                                    <li><strong>TikTok:</strong> <code>tiktok.com/@user/video/123456</code></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Best Practices</h6>
                                <ul class="list-unstyled small">
                                    <li>• Use public content only</li>
                                    <li>• Test embeds before publishing</li>
                                    <li>• Consider mobile responsiveness</li>
                                    <li>• Check platform terms of service</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Style Tab -->
        <div class="tab-pane fade" id="social-media-embed-<?= $row->microsite_block_id ?>-style" role="tabpanel" aria-labelledby="social-media-embed-<?= $row->microsite_block_id ?>-style-tab">
            
            <?php
            // Set up variables for shared components
            $block_id = $row->microsite_block_id;
            $settings = $row->settings;
            
            // Background Settings
            include THEME_PATH . 'views/partials/microsite_block_components/background_settings.php';
            
            // Border Settings
            include THEME_PATH . 'views/partials/microsite_block_components/border_settings.php';
            
            // Shadow Settings
            include THEME_PATH . 'views/partials/microsite_block_components/shadow_settings.php';
            
            // Animation Settings
            include THEME_PATH . 'views/partials/microsite_block_components/animation_settings.php';
            ?>

        </div>

        <!-- Display Tab -->
        <div class="tab-pane fade" id="social-media-embed-<?= $row->microsite_block_id ?>-display" role="tabpanel" aria-labelledby="social-media-embed-<?= $row->microsite_block_id ?>-display-tab">
            
            <?php include THEME_PATH . 'views/partials/microsite_block_components/display_settings.php'; ?>

        </div>

    </div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
