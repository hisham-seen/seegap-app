<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_social_media_embed" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-fw fa-share-alt fa-sm text-muted mr-2"></i>
                    <?= l('microsite_social_embed.create') ?? 'Create Social Media Embed' ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form name="create_microsite_block" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="block_type" value="social_media_embed" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />

                    <div class="notification-container"></div>

                    <!-- Platform Selection -->
                    <div class="form-group">
                        <label for="platform">
                            <i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_social_embed.platform') ?? 'Social Media Platform' ?>
                        </label>
                        <select id="platform" name="platform" class="custom-select" onchange="updateCreatePlatformFields()" required>
                            <option value=""><?= l('global.choose') ?? 'Choose...' ?></option>
                            <option value="youtube" data-icon="fab fa-youtube" data-color="#FF0000">YouTube</option>
                            <option value="instagram" data-icon="fab fa-instagram" data-color="#E4405F">Instagram</option>
                            <option value="twitter" data-icon="fab fa-twitter" data-color="#1DA1F2">Twitter / X</option>
                            <option value="tiktok" data-icon="fab fa-tiktok" data-color="#000000">TikTok</option>
                            <option value="facebook" data-icon="fab fa-facebook" data-color="#1877F2">Facebook</option>
                            <option value="threads" data-icon="fas fa-at" data-color="#000000">Threads</option>
                            <option value="telegram" data-icon="fab fa-telegram" data-color="#0088CC">Telegram</option>
                        </select>
                        <small class="form-text text-muted">
                            <?= l('microsite_social_embed.platform_help') ?? 'Choose the social media platform you want to embed content from' ?>
                        </small>
                    </div>

                    <!-- Platform-Specific Content -->
                    <div id="platform-fields" style="display: none;">
                        
                        <!-- YouTube Fields -->
                        <div id="youtube-fields" class="platform-fields" style="display: none;">
                            <div class="d-flex align-items-center mb-3 p-3 rounded" style="background-color: #FF000015; border-left: 4px solid #FF0000;">
                                <i class="fab fa-youtube fa-2x mr-3" style="color: #FF0000;"></i>
                                <div>
                                    <h6 class="mb-0">YouTube Embed</h6>
                                    <small class="text-muted">Configure your YouTube embed settings</small>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="youtube_type">
                                    <i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> 
                                    <?= l('microsite_social_embed.embed_type') ?? 'Embed Type' ?>
                                </label>
                                <select id="youtube_type" name="embed_type" class="custom-select">
                                    <option value="video">Single Video</option>
                                    <option value="channel">Channel</option>
                                    <option value="playlist">Playlist</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="youtube_url">
                                    <i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i>
                                    YouTube URL <span class="text-danger">*</span>
                                </label>
                                <input 
                                    id="youtube_url" 
                                    type="url" 
                                    name="embed_data[url]" 
                                    class="form-control" 
                                    placeholder="https://www.youtube.com/watch?v=VIDEO_ID"
                                    required
                                />
                                <small class="form-text text-muted">
                                    Paste the YouTube URL for the video, channel, or playlist you want to embed
                                </small>
                            </div>
                        </div>

                        <!-- Instagram Fields -->
                        <div id="instagram-fields" class="platform-fields" style="display: none;">
                            <div class="d-flex align-items-center mb-3 p-3 rounded" style="background-color: #E4405F15; border-left: 4px solid #E4405F;">
                                <i class="fab fa-instagram fa-2x mr-3" style="color: #E4405F;"></i>
                                <div>
                                    <h6 class="mb-0">Instagram Embed</h6>
                                    <small class="text-muted">Configure your Instagram embed settings</small>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="instagram_type">
                                    <i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> 
                                    <?= l('microsite_social_embed.embed_type') ?? 'Embed Type' ?>
                                </label>
                                <select id="instagram_type" name="embed_type" class="custom-select">
                                    <option value="post">Post</option>
                                    <option value="reel">Reel</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="instagram_url">
                                    <i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i>
                                    Instagram URL <span class="text-danger">*</span>
                                </label>
                                <input 
                                    id="instagram_url" 
                                    type="url" 
                                    name="embed_data[url]" 
                                    class="form-control" 
                                    placeholder="https://www.instagram.com/p/POST_ID/"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Twitter Fields -->
                        <div id="twitter-fields" class="platform-fields" style="display: none;">
                            <div class="d-flex align-items-center mb-3 p-3 rounded" style="background-color: #1DA1F215; border-left: 4px solid #1DA1F2;">
                                <i class="fab fa-twitter fa-2x mr-3" style="color: #1DA1F2;"></i>
                                <div>
                                    <h6 class="mb-0">Twitter / X Embed</h6>
                                    <small class="text-muted">Configure your Twitter embed settings</small>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="twitter_type">
                                    <i class="fas fa-fw fa-list fa-sm text-muted mr-1"></i> 
                                    <?= l('microsite_social_embed.embed_type') ?? 'Embed Type' ?>
                                </label>
                                <select id="twitter_type" name="embed_type" class="custom-select">
                                    <option value="tweet">Single Tweet</option>
                                    <option value="profile">Profile Timeline</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="twitter_url">
                                    <i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i>
                                    Twitter URL <span class="text-danger">*</span>
                                </label>
                                <input 
                                    id="twitter_url" 
                                    type="url" 
                                    name="embed_data[url]" 
                                    class="form-control" 
                                    placeholder="https://twitter.com/username/status/TWEET_ID"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Other platforms would follow similar pattern -->
                        
                    </div>

                    <!-- Common Settings -->
                    <div class="form-group custom-control custom-switch">
                        <input
                            id="open_in_new_tab"
                            name="open_in_new_tab" 
                            type="checkbox"
                            class="custom-control-input"
                            checked
                        >
                        <label class="custom-control-label" for="open_in_new_tab">
                            <i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_link.open_in_new_tab') ?? 'Open in New Tab' ?>
                        </label>
                    </div>

                    <div class="form-group custom-control custom-switch">
                        <input
                            id="responsive"
                            name="responsive" 
                            type="checkbox"
                            class="custom-control-input"
                            checked
                        >
                        <label class="custom-control-label" for="responsive">
                            <i class="fas fa-fw fa-mobile-alt fa-sm text-muted mr-1"></i> 
                            <?= l('microsite_social_embed.responsive') ?? 'Responsive' ?>
                        </label>
                    </div>

                    <div class="row mt-4">
                        <div class="col-6">
                            <button type="button" class="btn btn-block btn-outline-secondary" data-dismiss="modal"><?= l('global.close') ?></button>
                        </div>
                        <div class="col-6">
                            <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.create') ?></button>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

<script>
function updateCreatePlatformFields() {
    const platformSelect = document.getElementById('platform');
    const platformFields = document.getElementById('platform-fields');
    const allPlatformFields = document.querySelectorAll('.platform-fields');
    
    // Hide all platform fields
    allPlatformFields.forEach(field => {
        field.style.display = 'none';
    });
    
    if (platformSelect.value) {
        // Show platform fields container
        platformFields.style.display = 'block';
        
        // Show specific platform fields
        const selectedFields = document.getElementById(platformSelect.value + '-fields');
        if (selectedFields) {
            selectedFields.style.display = 'block';
        }
    } else {
        platformFields.style.display = 'none';
    }
}
</script>
