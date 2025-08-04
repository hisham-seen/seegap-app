<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Social Media Embed Manager Component for Microsite Blocks
 * Provides unified social media embed functionality with dynamic platform selection
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Block settings object
 * @param bool $collapsed - Whether the section should be collapsed by default (default: false)
 * @param array $custom_platforms - Custom platform configurations (optional)
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$collapsed = $collapsed ?? false;
$custom_platforms = $custom_platforms ?? [];

// Define supported platforms with their configurations
$default_platforms = [
    'youtube' => [
        'name' => 'YouTube',
        'icon' => 'fab fa-youtube',
        'color' => '#FF0000',
        'types' => [
            'video' => 'Single Video',
            'channel' => 'Channel',
            'playlist' => 'Playlist'
        ],
        'fields' => [
            'video' => [
                'url' => ['label' => 'Video URL', 'placeholder' => 'https://www.youtube.com/watch?v=VIDEO_ID', 'required' => true],
                'width' => ['label' => 'Width', 'type' => 'number', 'default' => 560, 'min' => 200, 'max' => 1200],
                'height' => ['label' => 'Height', 'type' => 'number', 'default' => 315, 'min' => 150, 'max' => 800]
            ],
            'channel' => [
                'channel_id' => ['label' => 'Channel ID', 'placeholder' => 'UCxxxxxxxxxxxxxxxxxxxxxxx', 'required' => true],
                'amount' => ['label' => 'Number of Videos', 'type' => 'number', 'default' => 5, 'min' => 1, 'max' => 10]
            ],
            'playlist' => [
                'playlist_id' => ['label' => 'Playlist ID', 'placeholder' => 'PLxxxxxxxxxxxxxxxxxxxxxxx', 'required' => true],
                'amount' => ['label' => 'Number of Videos', 'type' => 'number', 'default' => 5, 'min' => 1, 'max' => 10]
            ]
        ]
    ],
    'instagram' => [
        'name' => 'Instagram',
        'icon' => 'fab fa-instagram',
        'color' => '#E4405F',
        'types' => [
            'post' => 'Post',
            'reel' => 'Reel',
            'story' => 'Story'
        ],
        'fields' => [
            'post' => [
                'url' => ['label' => 'Post URL', 'placeholder' => 'https://www.instagram.com/p/POST_ID/', 'required' => true],
                'width' => ['label' => 'Width', 'type' => 'number', 'default' => 400, 'min' => 320, 'max' => 800]
            ],
            'reel' => [
                'url' => ['label' => 'Reel URL', 'placeholder' => 'https://www.instagram.com/reel/REEL_ID/', 'required' => true],
                'width' => ['label' => 'Width', 'type' => 'number', 'default' => 400, 'min' => 320, 'max' => 800]
            ]
        ]
    ],
    'twitter' => [
        'name' => 'Twitter / X',
        'icon' => 'fab fa-twitter',
        'color' => '#1DA1F2',
        'types' => [
            'tweet' => 'Single Tweet',
            'profile' => 'Profile Timeline',
            'video' => 'Video Tweet'
        ],
        'fields' => [
            'tweet' => [
                'url' => ['label' => 'Tweet URL', 'placeholder' => 'https://twitter.com/username/status/TWEET_ID', 'required' => true],
                'theme' => ['label' => 'Theme', 'type' => 'select', 'options' => ['light' => 'Light', 'dark' => 'Dark'], 'default' => 'light']
            ],
            'profile' => [
                'username' => ['label' => 'Username', 'placeholder' => '@username', 'required' => true],
                'height' => ['label' => 'Height', 'type' => 'number', 'default' => 400, 'min' => 200, 'max' => 800],
                'theme' => ['label' => 'Theme', 'type' => 'select', 'options' => ['light' => 'Light', 'dark' => 'Dark'], 'default' => 'light']
            ]
        ]
    ],
    'tiktok' => [
        'name' => 'TikTok',
        'icon' => 'fab fa-tiktok',
        'color' => '#000000',
        'types' => [
            'video' => 'Single Video',
            'profile' => 'Profile'
        ],
        'fields' => [
            'video' => [
                'url' => ['label' => 'Video URL', 'placeholder' => 'https://www.tiktok.com/@username/video/VIDEO_ID', 'required' => true],
                'width' => ['label' => 'Width', 'type' => 'number', 'default' => 325, 'min' => 250, 'max' => 500]
            ],
            'profile' => [
                'username' => ['label' => 'Username', 'placeholder' => '@username', 'required' => true],
                'width' => ['label' => 'Width', 'type' => 'number', 'default' => 325, 'min' => 250, 'max' => 500]
            ]
        ]
    ],
    'facebook' => [
        'name' => 'Facebook',
        'icon' => 'fab fa-facebook',
        'color' => '#1877F2',
        'types' => [
            'post' => 'Post',
            'page' => 'Page',
            'video' => 'Video'
        ],
        'fields' => [
            'post' => [
                'url' => ['label' => 'Post URL', 'placeholder' => 'https://www.facebook.com/username/posts/POST_ID', 'required' => true],
                'width' => ['label' => 'Width', 'type' => 'number', 'default' => 500, 'min' => 350, 'max' => 750]
            ],
            'page' => [
                'url' => ['label' => 'Page URL', 'placeholder' => 'https://www.facebook.com/PAGE_NAME', 'required' => true],
                'width' => ['label' => 'Width', 'type' => 'number', 'default' => 500, 'min' => 350, 'max' => 750]
            ]
        ]
    ],
    'threads' => [
        'name' => 'Threads',
        'icon' => 'fas fa-at',
        'color' => '#000000',
        'types' => [
            'post' => 'Post',
            'profile' => 'Profile'
        ],
        'fields' => [
            'post' => [
                'url' => ['label' => 'Post URL', 'placeholder' => 'https://www.threads.net/@username/post/POST_ID', 'required' => true],
                'width' => ['label' => 'Width', 'type' => 'number', 'default' => 400, 'min' => 320, 'max' => 600]
            ]
        ]
    ],
    'telegram' => [
        'name' => 'Telegram',
        'icon' => 'fab fa-telegram',
        'color' => '#0088CC',
        'types' => [
            'post' => 'Channel Post',
            'channel' => 'Channel Widget'
        ],
        'fields' => [
            'post' => [
                'url' => ['label' => 'Post URL', 'placeholder' => 'https://t.me/CHANNEL_NAME/POST_ID', 'required' => true],
                'width' => ['label' => 'Width', 'type' => 'number', 'default' => 400, 'min' => 320, 'max' => 600]
            ]
        ]
    ]
];

// Merge custom platforms if provided
$platforms = array_merge($default_platforms, $custom_platforms);

// Get current values
$current_platform = $settings->platform ?? 'youtube';
$current_type = $settings->embed_type ?? 'video';
$current_data = $settings->embed_data ?? (object)[];
?>

<?php if($collapsed): ?>
<div class="card mb-3">
    <div class="card-header" data-toggle="collapse" data-target="#social_embed_<?= $block_id ?>" aria-expanded="false" style="cursor: pointer;">
        <h6 class="mb-0">
            <i class="fas fa-fw fa-share-alt fa-sm text-muted mr-2"></i>
            <?= l('microsite_social_embed.settings') ?? 'Social Media Embed Settings' ?>
            <i class="fas fa-chevron-down float-right"></i>
        </h6>
    </div>
    <div id="social_embed_<?= $block_id ?>" class="collapse">
        <div class="card-body">
<?php endif ?>

            <!-- Platform Selection -->
            <div class="form-group">
                <label for="platform_<?= $block_id ?>">
                    <i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_social_embed.platform') ?? 'Social Media Platform' ?>
                </label>
                <select id="platform_<?= $block_id ?>" name="platform" class="custom-select" onchange="updatePlatformFields('<?= $block_id ?>')">
                    <?php foreach($platforms as $key => $platform): ?>
                        <option value="<?= $key ?>" <?= $current_platform == $key ? 'selected' : '' ?> data-icon="<?= $platform['icon'] ?>" data-color="<?= $platform['color'] ?>">
                            <?= $platform['name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <small class="form-text text-muted">
                    <?= l('microsite_social_embed.platform_help') ?? 'Choose the social media platform you want to embed content from' ?>
                </small>
            </div>

            <!-- Platform-Specific Content -->
            <?php foreach($platforms as $platform_key => $platform): ?>
                <div id="platform_content_<?= $platform_key ?>_<?= $block_id ?>" class="platform-content" style="display: <?= $current_platform == $platform_key ? 'block' : 'none' ?>;">
                    
                    <!-- Platform Header -->
                    <div class="d-flex align-items-center mb-3 p-3 rounded" style="background-color: <?= $platform['color'] ?>15; border-left: 4px solid <?= $platform['color'] ?>;">
                        <i class="<?= $platform['icon'] ?> fa-2x mr-3" style="color: <?= $platform['color'] ?>;"></i>
                        <div>
                            <h6 class="mb-0"><?= $platform['name'] ?> Embed</h6>
                            <small class="text-muted">Configure your <?= $platform['name'] ?> embed settings</small>
                        </div>
                    </div>

                    <!-- Embed Type Selection -->
                    <?php if(count($platform['types']) > 1): ?>
                        <div class="form-group">
                            <label for="embed_type_<?= $platform_key ?>_<?= $block_id ?>">
                                <i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> 
                                <?= l('microsite_social_embed.embed_type') ?? 'Embed Type' ?>
                            </label>
                            <select id="embed_type_<?= $platform_key ?>_<?= $block_id ?>" name="embed_type" class="custom-select" onchange="updateEmbedTypeFields('<?= $block_id ?>', '<?= $platform_key ?>')">
                                <?php foreach($platform['types'] as $type_key => $type_name): ?>
                                    <option value="<?= $type_key ?>" <?= $current_type == $type_key ? 'selected' : '' ?>>
                                        <?= $type_name ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    <?php endif ?>

                    <!-- Dynamic Fields for Each Type -->
                    <?php foreach($platform['types'] as $type_key => $type_name): ?>
                        <div id="type_fields_<?= $platform_key ?>_<?= $type_key ?>_<?= $block_id ?>" class="type-fields" style="display: <?= $current_type == $type_key ? 'block' : 'none' ?>;">
                            <?php if(isset($platform['fields'][$type_key])): ?>
                                <?php foreach($platform['fields'][$type_key] as $field_key => $field_config): ?>
                                    <div class="form-group">
                                        <label for="<?= $field_key ?>_<?= $platform_key ?>_<?= $type_key ?>_<?= $block_id ?>">
                                            <i class="fas fa-fw fa-<?= $field_key == 'url' ? 'link' : ($field_key == 'width' || $field_key == 'height' ? 'arrows-alt' : 'cog') ?> fa-sm text-muted mr-1"></i>
                                            <?= $field_config['label'] ?>
                                            <?php if($field_config['required'] ?? false): ?>
                                                <span class="text-danger">*</span>
                                            <?php endif ?>
                                        </label>
                                        
                                        <?php if(($field_config['type'] ?? 'text') == 'select'): ?>
                                            <select id="<?= $field_key ?>_<?= $platform_key ?>_<?= $type_key ?>_<?= $block_id ?>" name="embed_data[<?= $field_key ?>]" class="custom-select" <?= ($field_config['required'] ?? false) ? 'required' : '' ?>>
                                                <?php foreach($field_config['options'] as $option_key => $option_label): ?>
                                                    <option value="<?= $option_key ?>" <?= ($current_data->{$field_key} ?? $field_config['default'] ?? '') == $option_key ? 'selected' : '' ?>>
                                                        <?= $option_label ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </select>
                                        <?php elseif(($field_config['type'] ?? 'text') == 'number'): ?>
                                            <input 
                                                id="<?= $field_key ?>_<?= $platform_key ?>_<?= $type_key ?>_<?= $block_id ?>" 
                                                type="number" 
                                                name="embed_data[<?= $field_key ?>]" 
                                                class="form-control" 
                                                value="<?= $current_data->{$field_key} ?? $field_config['default'] ?? '' ?>"
                                                placeholder="<?= $field_config['placeholder'] ?? '' ?>"
                                                min="<?= $field_config['min'] ?? '' ?>"
                                                max="<?= $field_config['max'] ?? '' ?>"
                                                <?= ($field_config['required'] ?? false) ? 'required' : '' ?>
                                            />
                                        <?php else: ?>
                                            <input 
                                                id="<?= $field_key ?>_<?= $platform_key ?>_<?= $type_key ?>_<?= $block_id ?>" 
                                                type="text" 
                                                name="embed_data[<?= $field_key ?>]" 
                                                class="form-control" 
                                                value="<?= $current_data->{$field_key} ?? '' ?>"
                                                placeholder="<?= $field_config['placeholder'] ?? '' ?>"
                                                <?= ($field_config['required'] ?? false) ? 'required' : '' ?>
                                            />
                                        <?php endif ?>
                                        
                                        <?php if(isset($field_config['help'])): ?>
                                            <small class="form-text text-muted"><?= $field_config['help'] ?></small>
                                        <?php endif ?>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    <?php endforeach ?>

                </div>
            <?php endforeach ?>

            <!-- Common Settings -->
            <hr class="my-4">
            <h6 class="text-muted mb-3">
                <i class="fas fa-fw fa-cogs fa-sm mr-1"></i>
                <?= l('microsite_social_embed.common_settings') ?? 'Common Settings' ?>
            </h6>

            <!-- Open in New Tab -->
            <div class="form-group custom-control custom-switch">
                <input
                    id="open_in_new_tab_<?= $block_id ?>"
                    name="open_in_new_tab" 
                    type="checkbox"
                    class="custom-control-input"
                    <?= ($settings->open_in_new_tab ?? false) ? 'checked="checked"' : null ?>
                >
                <label class="custom-control-label" for="open_in_new_tab_<?= $block_id ?>">
                    <i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_link.open_in_new_tab') ?? 'Open in New Tab' ?>
                </label>
                <small class="form-text text-muted">
                    <?= l('microsite_social_embed.open_in_new_tab_help') ?? 'Open embedded content links in a new tab/window' ?>
                </small>
            </div>

            <!-- Responsive -->
            <div class="form-group custom-control custom-switch">
                <input
                    id="responsive_<?= $block_id ?>"
                    name="responsive" 
                    type="checkbox"
                    class="custom-control-input"
                    <?= ($settings->responsive ?? true) ? 'checked="checked"' : null ?>
                >
                <label class="custom-control-label" for="responsive_<?= $block_id ?>">
                    <i class="fas fa-fw fa-mobile-alt fa-sm text-muted mr-1"></i> 
                    <?= l('microsite_social_embed.responsive') ?? 'Responsive' ?>
                </label>
                <small class="form-text text-muted">
                    <?= l('microsite_social_embed.responsive_help') ?? 'Make the embed responsive to screen size' ?>
                </small>
            </div>

<?php if($collapsed): ?>
        </div>
    </div>
</div>
<?php endif ?>

<!-- Hidden Platform Data -->
<script type="application/json" id="platforms-data-<?= $block_id ?>">
<?= json_encode($platforms) ?>
</script>

<style>
.social-media-embed-manager .platform-content {
    transition: all 0.3s ease;
}

.social-media-embed-manager .type-fields {
    transition: all 0.3s ease;
}

.social-media-embed-manager .form-group:last-child {
    margin-bottom: 0;
}

.social-media-embed-manager .custom-select option {
    padding: 0.5rem;
}

@media (max-width: 576px) {
    .social-media-embed-manager .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .social-media-embed-manager .d-flex .fa-2x {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?= $block_id ?>';
    
    // Initialize the embed manager
    initializeSocialEmbedManager(blockId);
});

function initializeSocialEmbedManager(blockId) {
    // Set initial state
    updatePlatformFields(blockId);
}

function updatePlatformFields(blockId) {
    const platformSelect = document.getElementById('platform_' + blockId);
    const selectedPlatform = platformSelect.value;
    
    // Hide all platform content
    const allPlatformContent = document.querySelectorAll('[id^="platform_content_"][id$="_' + blockId + '"]');
    allPlatformContent.forEach(content => {
        content.style.display = 'none';
    });
    
    // Show selected platform content
    const selectedContent = document.getElementById('platform_content_' + selectedPlatform + '_' + blockId);
    if (selectedContent) {
        selectedContent.style.display = 'block';
        
        // Update embed type fields for this platform
        updateEmbedTypeFields(blockId, selectedPlatform);
    }
}

function updateEmbedTypeFields(blockId, platform) {
    const typeSelect = document.getElementById('embed_type_' + platform + '_' + blockId);
    if (!typeSelect) return;
    
    const selectedType = typeSelect.value;
    
    // Hide all type fields for this platform
    const allTypeFields = document.querySelectorAll('[id^="type_fields_' + platform + '_"][id$="_' + blockId + '"]');
    allTypeFields.forEach(fields => {
        fields.style.display = 'none';
    });
    
    // Show selected type fields
    const selectedFields = document.getElementById('type_fields_' + platform + '_' + selectedType + '_' + blockId);
    if (selectedFields) {
        selectedFields.style.display = 'block';
    }
}

function validateEmbedUrl(blockId, platform, type, url) {
    // Basic URL validation based on platform
    const platformsData = JSON.parse(document.getElementById('platforms-data-' + blockId).textContent);
    
    if (!url) return false;
    
    // Platform-specific validation patterns
    const patterns = {
        youtube: {
            video: /(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/,
            channel: /youtube\.com\/(?:channel\/|c\/|user\/)([a-zA-Z0-9_-]+)/,
            playlist: /youtube\.com\/playlist\?list=([a-zA-Z0-9_-]+)/
        },
        instagram: {
            post: /instagram\.com\/p\/([a-zA-Z0-9_-]+)/,
            reel: /instagram\.com\/reel\/([a-zA-Z0-9_-]+)/
        },
        twitter: {
            tweet: /twitter\.com\/\w+\/status\/(\d+)/,
            profile: /twitter\.com\/(\w+)/
        },
        tiktok: {
            video: /tiktok\.com\/@[\w.-]+\/video\/(\d+)/,
            profile: /tiktok\.com\/@([\w.-]+)/
        },
        facebook: {
            post: /facebook\.com\/.*\/posts\/(\d+)/,
            page: /facebook\.com\/([\w.-]+)/
        }
    };
    
    if (patterns[platform] && patterns[platform][type]) {
        return patterns[platform][type].test(url);
    }
    
    return true; // Default to valid if no pattern defined
}
</script>
