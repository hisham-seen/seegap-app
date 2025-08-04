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
    // Standard blocks
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
    "socials" => [
        "icon" => "fa fa-share-alt",
        "color" => "#e83e8c",
        "category" => "standard",
        "display_dynamic_name" => null,
        "has_statistics" => true,
        "type" => "default",
        "themable" => true
    ],

    // Advanced blocks
    "countdown" => [
        "icon" => "fa fa-clock",
        "color" => "#ffc107",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => false,
        "type" => "default"
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
    "form" => [
        "icon" => "fa fa-envelope",
        "color" => "#dc3545",
        "category" => "advanced",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default"
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
    "social_media_embed" => [
        "icon" => "fa fa-share-alt",
        "color" => "#6f42c1",
        "category" => "embeds",
        "display_dynamic_name" => "name",
        "has_statistics" => true,
        "type" => "default"
    ]
];
