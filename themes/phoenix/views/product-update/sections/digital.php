<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-globe text-primary me-2"></i>
            <?= l('products.digital_integration_section') ?>
        </h5>
        <p class="text-muted small mb-0"><?= l('products.digital_integration_description') ?></p>
    </div>
    <div class="card-body">
        <!-- Product URLs & Links -->
        <h6 class="text-primary mb-3">
            <i class="fas fa-link me-2"></i>
            <?= l('products.product_urls_links') ?>
        </h6>
        <div class="row">
            <!-- Product Website URL -->
            <div class="col-lg-6 mb-3">
                <label for="product_url" class="form-label">
                    <?= l('products.product_url') ?>
                </label>
                <input 
                    type="url" 
                    id="product_url" 
                    name="product_url" 
                    class="form-control" 
                    value="<?= $data->product->product_url ?? '' ?>"
                    placeholder="<?= l('products.product_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.product_url_help') ?></div>
            </div>

            <!-- Manufacturer Website -->
            <div class="col-lg-6 mb-3">
                <label for="manufacturer_url" class="form-label">
                    <?= l('products.manufacturer_url') ?>
                </label>
                <input 
                    type="url" 
                    id="manufacturer_url" 
                    name="manufacturer_url" 
                    class="form-control" 
                    value="<?= $data->product->manufacturer_url ?? '' ?>"
                    placeholder="<?= l('products.manufacturer_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.manufacturer_url_help') ?></div>
            </div>

            <!-- Product Information URL (AI 8200) -->
            <div class="col-lg-6 mb-3">
                <label for="product_info_url" class="form-label">
                    <?= l('products.product_info_url') ?>
                    <span class="text-muted small">(AI 8200)</span>
                </label>
                <input 
                    type="url" 
                    id="product_info_url" 
                    name="product_info_url" 
                    class="form-control" 
                    value="<?= $data->product->product_info_url ?? '' ?>"
                    placeholder="<?= l('products.product_info_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.product_info_url_help') ?></div>
            </div>

            <!-- Sustainability Information URL -->
            <div class="col-lg-6 mb-3">
                <label for="sustainability_url" class="form-label">
                    <?= l('products.sustainability_url') ?>
                </label>
                <input 
                    type="url" 
                    id="sustainability_url" 
                    name="sustainability_url" 
                    class="form-control" 
                    value="<?= $data->product->sustainability_url ?? '' ?>"
                    placeholder="<?= l('products.sustainability_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.sustainability_url_help') ?></div>
            </div>

            <!-- Recycling Information URL -->
            <div class="col-lg-6 mb-3">
                <label for="recycling_url" class="form-label">
                    <?= l('products.recycling_url') ?>
                </label>
                <input 
                    type="url" 
                    id="recycling_url" 
                    name="recycling_url" 
                    class="form-control" 
                    value="<?= $data->product->recycling_url ?? '' ?>"
                    placeholder="<?= l('products.recycling_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.recycling_url_help') ?></div>
            </div>

            <!-- Safety Information URL -->
            <div class="col-lg-6 mb-3">
                <label for="safety_url" class="form-label">
                    <?= l('products.safety_url') ?>
                </label>
                <input 
                    type="url" 
                    id="safety_url" 
                    name="safety_url" 
                    class="form-control" 
                    value="<?= $data->product->safety_url ?? '' ?>"
                    placeholder="<?= l('products.safety_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.safety_url_help') ?></div>
            </div>
        </div>

        <!-- Social Media & Marketing -->
        <hr class="my-4">
        <h6 class="text-primary mb-3">
            <i class="fas fa-share-alt me-2"></i>
            <?= l('products.social_media_marketing') ?>
        </h6>
        <div class="row">
            <!-- Facebook Page -->
            <div class="col-lg-6 mb-3">
                <label for="facebook_url" class="form-label">
                    <?= l('products.facebook_url') ?>
                </label>
                <input 
                    type="url" 
                    id="facebook_url" 
                    name="facebook_url" 
                    class="form-control" 
                    value="<?= $data->product->facebook_url ?? '' ?>"
                    placeholder="<?= l('products.facebook_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.facebook_url_help') ?></div>
            </div>

            <!-- Instagram Page -->
            <div class="col-lg-6 mb-3">
                <label for="instagram_url" class="form-label">
                    <?= l('products.instagram_url') ?>
                </label>
                <input 
                    type="url" 
                    id="instagram_url" 
                    name="instagram_url" 
                    class="form-control" 
                    value="<?= $data->product->instagram_url ?? '' ?>"
                    placeholder="<?= l('products.instagram_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.instagram_url_help') ?></div>
            </div>

            <!-- Twitter/X Page -->
            <div class="col-lg-6 mb-3">
                <label for="twitter_url" class="form-label">
                    <?= l('products.twitter_url') ?>
                </label>
                <input 
                    type="url" 
                    id="twitter_url" 
                    name="twitter_url" 
                    class="form-control" 
                    value="<?= $data->product->twitter_url ?? '' ?>"
                    placeholder="<?= l('products.twitter_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.twitter_url_help') ?></div>
            </div>

            <!-- YouTube Channel -->
            <div class="col-lg-6 mb-3">
                <label for="youtube_url" class="form-label">
                    <?= l('products.youtube_url') ?>
                </label>
                <input 
                    type="url" 
                    id="youtube_url" 
                    name="youtube_url" 
                    class="form-control" 
                    value="<?= $data->product->youtube_url ?? '' ?>"
                    placeholder="<?= l('products.youtube_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.youtube_url_help') ?></div>
            </div>
        </div>

        <!-- E-commerce & Purchase -->
        <hr class="my-4">
        <h6 class="text-primary mb-3">
            <i class="fas fa-shopping-cart me-2"></i>
            <?= l('products.ecommerce_purchase') ?>
        </h6>
        <div class="row">
            <!-- Purchase URL -->
            <div class="col-lg-6 mb-3">
                <label for="purchase_url" class="form-label">
                    <?= l('products.purchase_url') ?>
                </label>
                <input 
                    type="url" 
                    id="purchase_url" 
                    name="purchase_url" 
                    class="form-control" 
                    value="<?= $data->product->purchase_url ?? '' ?>"
                    placeholder="<?= l('products.purchase_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.purchase_url_help') ?></div>
            </div>

            <!-- Amazon ASIN -->
            <div class="col-lg-6 mb-3">
                <label for="amazon_asin" class="form-label">
                    <?= l('products.amazon_asin') ?>
                </label>
                <input 
                    type="text" 
                    id="amazon_asin" 
                    name="amazon_asin" 
                    class="form-control" 
                    value="<?= $data->product->amazon_asin ?? '' ?>"
                    placeholder="<?= l('products.amazon_asin_placeholder') ?>"
                    maxlength="10"
                >
                <div class="form-text"><?= l('products.amazon_asin_help') ?></div>
            </div>

            <!-- eBay Item ID -->
            <div class="col-lg-6 mb-3">
                <label for="ebay_item_id" class="form-label">
                    <?= l('products.ebay_item_id') ?>
                </label>
                <input 
                    type="text" 
                    id="ebay_item_id" 
                    name="ebay_item_id" 
                    class="form-control" 
                    value="<?= $data->product->ebay_item_id ?? '' ?>"
                    placeholder="<?= l('products.ebay_item_id_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.ebay_item_id_help') ?></div>
            </div>

            <!-- Price Comparison URL -->
            <div class="col-lg-6 mb-3">
                <label for="price_comparison_url" class="form-label">
                    <?= l('products.price_comparison_url') ?>
                </label>
                <input 
                    type="url" 
                    id="price_comparison_url" 
                    name="price_comparison_url" 
                    class="form-control" 
                    value="<?= $data->product->price_comparison_url ?? '' ?>"
                    placeholder="<?= l('products.price_comparison_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.price_comparison_url_help') ?></div>
            </div>
        </div>

        <!-- Documentation & Support -->
        <hr class="my-4">
        <h6 class="text-primary mb-3">
            <i class="fas fa-file-alt me-2"></i>
            <?= l('products.documentation_support') ?>
        </h6>
        <div class="row">
            <!-- User Manual URL -->
            <div class="col-lg-6 mb-3">
                <label for="manual_url" class="form-label">
                    <?= l('products.manual_url') ?>
                </label>
                <input 
                    type="url" 
                    id="manual_url" 
                    name="manual_url" 
                    class="form-control" 
                    value="<?= $data->product->manual_url ?? '' ?>"
                    placeholder="<?= l('products.manual_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.manual_url_help') ?></div>
            </div>

            <!-- Support URL -->
            <div class="col-lg-6 mb-3">
                <label for="support_url" class="form-label">
                    <?= l('products.support_url') ?>
                </label>
                <input 
                    type="url" 
                    id="support_url" 
                    name="support_url" 
                    class="form-control" 
                    value="<?= $data->product->support_url ?? '' ?>"
                    placeholder="<?= l('products.support_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.support_url_help') ?></div>
            </div>

            <!-- FAQ URL -->
            <div class="col-lg-6 mb-3">
                <label for="faq_url" class="form-label">
                    <?= l('products.faq_url') ?>
                </label>
                <input 
                    type="url" 
                    id="faq_url" 
                    name="faq_url" 
                    class="form-control" 
                    value="<?= $data->product->faq_url ?? '' ?>"
                    placeholder="<?= l('products.faq_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.faq_url_help') ?></div>
            </div>

            <!-- Video Tutorial URL -->
            <div class="col-lg-6 mb-3">
                <label for="tutorial_url" class="form-label">
                    <?= l('products.tutorial_url') ?>
                </label>
                <input 
                    type="url" 
                    id="tutorial_url" 
                    name="tutorial_url" 
                    class="form-control" 
                    value="<?= $data->product->tutorial_url ?? '' ?>"
                    placeholder="<?= l('products.tutorial_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.tutorial_url_help') ?></div>
            </div>
        </div>

        <!-- API & Integration -->
        <hr class="my-4">
        <h6 class="text-primary mb-3">
            <i class="fas fa-code me-2"></i>
            <?= l('products.api_integration') ?>
        </h6>
        <div class="row">
            <!-- API Endpoint -->
            <div class="col-lg-6 mb-3">
                <label for="api_endpoint" class="form-label">
                    <?= l('products.api_endpoint') ?>
                </label>
                <input 
                    type="url" 
                    id="api_endpoint" 
                    name="api_endpoint" 
                    class="form-control" 
                    value="<?= $data->product->api_endpoint ?? '' ?>"
                    placeholder="<?= l('products.api_endpoint_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.api_endpoint_help') ?></div>
            </div>

            <!-- Webhook URL -->
            <div class="col-lg-6 mb-3">
                <label for="webhook_url" class="form-label">
                    <?= l('products.webhook_url') ?>
                </label>
                <input 
                    type="url" 
                    id="webhook_url" 
                    name="webhook_url" 
                    class="form-control" 
                    value="<?= $data->product->webhook_url ?? '' ?>"
                    placeholder="<?= l('products.webhook_url_placeholder') ?>"
                >
                <div class="form-text"><?= l('products.webhook_url_help') ?></div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong><?= l('products.digital_note_title') ?>:</strong>
            <?= l('products.digital_note_description') ?>
        </div>

        <!-- Save Button -->
        <div class="mt-4">
            <button type="submit" name="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>
                <?= l('global.update') ?>
            </button>
        </div>
    </div>
</div>
