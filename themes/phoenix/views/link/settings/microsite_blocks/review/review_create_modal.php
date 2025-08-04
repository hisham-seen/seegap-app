<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_review" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_review.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_review" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="review" />

                    <div class="notification-container"></div>

                    <div class="alert alert-info">
                        <i class="fas fa-fw fa-info-circle mr-1"></i>
                        <strong>Enhanced Review Block:</strong> Create a professional review slider with multiple customer testimonials. You can add more reviews and configure slider behavior after creation.
                    </div>

                    <div class="form-group">
                        <label for="review_title"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> First Review Title</label>
                        <input id="review_title" type="text" name="title" class="form-control" placeholder="Great product!" maxlength="128" />
                        <small class="form-text text-muted">Optional: Add a title for this review</small>
                    </div>

                    <div class="form-group">
                        <label for="review_description"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> Review Description</label>
                        <textarea id="review_description" name="description" class="form-control" rows="3" placeholder="I really loved this product. It exceeded my expectations..." maxlength="1024"></textarea>
                        <small class="form-text text-muted">The main review content</small>
                    </div>

                    <div class="form-group">
                        <label for="review_author_name"><i class="fas fa-fw fa-user fa-sm text-muted mr-1"></i> <?= l('microsite_review.author_name') ?></label>
                        <input id="review_author_name" type="text" name="author_name" class="form-control" placeholder="John Smith" maxlength="128" required="required" />
                        <small class="form-text text-muted">Required: The reviewer's name</small>
                    </div>

                    <div class="form-group">
                        <label for="review_author_description"><i class="fas fa-fw fa-user-tag fa-sm text-muted mr-1"></i> <?= l('microsite_review.author_description') ?></label>
                        <input id="review_author_description" type="text" name="author_description" class="form-control" placeholder="Verified Customer" maxlength="128" />
                        <small class="form-text text-muted">Optional: Customer status or description</small>
                    </div>

                    <div class="form-group">
                        <label for="review_stars"><i class="fas fa-fw fa-star fa-sm text-muted mr-1"></i> Star Rating</label>
                        <div class="star-rating-create" data-rating="5">
                            <i class="fas fa-star star-input active" data-rating="1"></i>
                            <i class="fas fa-star star-input active" data-rating="2"></i>
                            <i class="fas fa-star star-input active" data-rating="3"></i>
                            <i class="fas fa-star star-input active" data-rating="4"></i>
                            <i class="fas fa-star star-input active" data-rating="5"></i>
                            <input id="review_stars" type="hidden" name="stars" value="5" required="required" />
                        </div>
                        <small class="form-text text-muted">Click stars to set rating (1-5 stars)</small>
                    </div>

                    <div class="form-group">
                        <label for="review_image"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> Author Image</label>
                        <input id="review_image" type="file" name="image" accept="<?= \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['review']['whitelisted_image_extensions'] ?? ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']) ?>" class="form-control-file seegap-file-input" data-crop data-aspect-ratio="1" />
                        <small class="form-text text-muted">Optional: Upload the reviewer's photo. <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \SeeGap\Uploads::array_to_list_format($data->microsite_blocks['review']['whitelisted_image_extensions'] ?? ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?></small>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle star rating clicks in create modal
    document.querySelectorAll('.star-rating-create .star-input').forEach(function(star) {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            const container = this.closest('.star-rating-create');
            const hiddenInput = container.querySelector('input[type="hidden"]');
            const stars = container.querySelectorAll('.star-input');
            
            // Update hidden input
            hiddenInput.value = rating;
            
            // Update star display
            stars.forEach(function(s, i) {
                if (i < rating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
    });
});
</script>

<style>
/* Star rating styles for create modal */
.star-rating-create {
    font-size: 1.5rem;
    cursor: pointer;
}

.star-rating-create .star-input {
    color: #ddd;
    transition: color 0.2s ease;
    margin-right: 0.25rem;
}

.star-rating-create .star-input:hover,
.star-rating-create .star-input.active {
    color: #ffc107;
}

.star-rating-create .star-input:hover {
    transform: scale(1.1);
}
</style>
