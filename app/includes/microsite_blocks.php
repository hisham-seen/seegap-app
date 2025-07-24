<?php
/*
 * Microsite blocks configuration
 * This file defines all available microsite blocks with their properties
 * Only includes blocks that have corresponding modal files
 */

if(!defined("SEEGAP")) {
    exit("Direct access is not allowed.");
}

return [
    /* Standard blocks */
    "text" => [
        "icon" => "fa fa-paragraph",
        "color" => "#28a745",
        "category" => "standard",
        "display_dynamic_name" => "text",
        "has_statistics" => true,
        "type" => "default"
    ],
    "image" => [
        "icon" => "fa fa-image",
        "color" => "#007bff",
        "category" => "standard",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default",
        "whitelisted_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "link" => [
        "icon" => "fa fa-link",
        "color" => "#6f42c1",
        "category" => "standard",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default",
        "whitelisted_thumbnail_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "divider" => [
        "icon" => "fa fa-minus",
        "color" => "#6c757d",
        "category" => "standard",
        "display_dynamic_name" => null,
        "has_statistics" => false,
        "type" => "default"
    ],
    "avatar" => [
        "icon" => "fa fa-user-circle",
        "color" => "#17a2b8",
        "category" => "standard",
        "display_dynamic_name" => null,
        "has_statistics" => false,
        "type" => "default",
        "whitelisted_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "socials" => [
        "icon" => "fa fa-share-alt",
        "color" => "#e83e8c",
        "category" => "standard",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default",
        "themable" => true
    ],

    /* Advanced blocks */
    "big_link" => [
        "icon" => "fa fa-external-link-alt",
        "color" => "#6f42c1",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default",
        "whitelisted_thumbnail_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "feedback_collector" => [
        "icon" => "fa fa-comment-dots",
        "color" => "#17a2b8",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default",
        "whitelisted_thumbnail_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "countdown" => [
        "icon" => "fa fa-clock",
        "color" => "#ffc107",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => false,
        "type" => "default"
    ],
    "cta" => [
        "icon" => "fa fa-hand-pointer",
        "color" => "#dc3545",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default",
        "whitelisted_thumbnail_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "share" => [
        "icon" => "fa fa-share",
        "color" => "#20c997",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default",
        "whitelisted_thumbnail_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "accordion" => [
        "icon" => "fa fa-list-ul",
        "color" => "#17a2b8",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => false,
        "type" => "default"
    ],
    "review" => [
        "icon" => "fa fa-star",
        "color" => "#ffc107",
        "category" => "advanced",
        "display_dynamic_name" => "title",
        "has_statistics" => false,
        "type" => "default",
        "whitelisted_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "alert" => [
        "icon" => "fa fa-exclamation-triangle",
        "color" => "#ffc107",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => false,
        "type" => "default"
    ],
    "custom_html" => [
        "icon" => "fa fa-code",
        "color" => "#6c757d",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => false,
        "type" => "default",
        "max_length" => 10000
    ],
    "form" => [
        "icon" => "fa fa-envelope",
        "color" => "#dc3545",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default"
    ],
    "cover" => [
        "icon" => "fa fa-play-circle",
        "color" => "#ff0000",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default",
        "whitelisted_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "image_grid" => [
        "icon" => "fa fa-th",
        "color" => "#007bff",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default",
        "whitelisted_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "image_slider" => [
        "icon" => "fa fa-images",
        "color" => "#007bff",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default",
        "whitelisted_image_extensions" => ["jpg", "jpeg", "png", "gif", "webp", "svg"]
    ],
    "youtube_feed" => [
        "icon" => "fab fa-youtube",
        "color" => "#ff0000",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default"
    ],

    /* Social Media blocks */
    "youtube" => [
        "icon" => "fab fa-youtube",
        "color" => "#ff0000",
        "category" => "embeds",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default"
    ],
    "instagram_media" => [
        "icon" => "fab fa-instagram",
        "color" => "#e4405f",
        "category" => "embeds",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default"
    ],
    "twitter_tweet" => [
        "icon" => "fab fa-twitter",
        "color" => "#1da1f2",
        "category" => "embeds",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default"
    ],
    "twitter_profile" => [
        "icon" => "fab fa-twitter",
        "color" => "#1da1f2",
        "category" => "embeds",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default"
    ],
    "twitter_video" => [
        "icon" => "fab fa-twitter",
        "color" => "#1da1f2",
        "category" => "embeds",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default"
    ],
    "facebook" => [
        "icon" => "fab fa-facebook",
        "color" => "#1877f2",
        "category" => "embeds",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default"
    ],
    "tiktok_profile" => [
        "icon" => "fab fa-tiktok",
        "color" => "#000000",
        "category" => "embeds",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default"
    ],
    "tiktok_video" => [
        "icon" => "fab fa-tiktok",
        "color" => "#000000",
        "category" => "embeds",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default"
    ],
    "threads" => [
        "icon" => "fa fa-at",
        "color" => "#000000",
        "category" => "embeds",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default"
    ],
    "telegram" => [
        "icon" => "fab fa-telegram",
        "color" => "#0088cc",
        "category" => "embeds",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default"
    ]
];
