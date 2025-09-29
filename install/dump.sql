CREATE TABLE `users` (
`user_id` int NOT NULL AUTO_INCREMENT,
`email` varchar(320) NOT NULL,
`name` varchar(64) NOT NULL,
`login_token` varchar(64) DEFAULT NULL,
`login_token_expiry` datetime DEFAULT NULL,
`login_token_ip` varchar(64) DEFAULT NULL,
`login_attempts` int DEFAULT '0',
`login_attempts_datetime` datetime DEFAULT NULL,
`billing` text,
`api_key` varchar(32) DEFAULT NULL,
`token_code` varchar(32) DEFAULT NULL,
`one_time_login_code` varchar(32) DEFAULT NULL,
`pending_email` varchar(128) DEFAULT NULL,
`email_activation_code` varchar(32) DEFAULT NULL,
`anti_phishing_code` varchar(8) DEFAULT NULL,
`type` tinyint NOT NULL DEFAULT '0',
`status` tinyint NOT NULL DEFAULT '0',
`is_newsletter_subscribed` tinyint NOT NULL DEFAULT '0',
`has_pending_internal_notifications` tinyint NOT NULL DEFAULT '0',
`plan_id` varchar(16) NOT NULL DEFAULT '',
`plan_expiration_date` datetime DEFAULT NULL,
`plan_settings` text,
`plan_trial_done` tinyint(4) DEFAULT '0',
`plan_expiry_reminder` tinyint(4) DEFAULT '0',
`payment_subscription_id` varchar(64) DEFAULT NULL,
`payment_processor` varchar(16) DEFAULT NULL,
`payment_total_amount` float DEFAULT NULL,
`payment_currency` varchar(4) DEFAULT NULL,
`referral_key` varchar(32) DEFAULT NULL,
`referred_by` varchar(32) DEFAULT NULL,
`referred_by_has_converted` tinyint(4) DEFAULT '0',
`language` varchar(32) DEFAULT 'english',
`currency` varchar(4) DEFAULT NULL,
`timezone` varchar(32) DEFAULT 'UTC',
`preferences` text,
`extra` text,
`datetime` datetime DEFAULT NULL,
`next_cleanup_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
`ip` varchar(64) DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country` varchar(8) DEFAULT NULL,
`city_name` varchar(32) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`browser_language` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`last_activity` datetime DEFAULT NULL,
`total_logins` int DEFAULT '0',
`user_deletion_reminder` tinyint(4) DEFAULT '0',
`source` varchar(32) DEFAULT 'direct',
`aix_words_current_month` int(11) NOT NULL DEFAULT '0',
`aix_images_current_month` int(11) NOT NULL DEFAULT '0',
`aix_chats_current_month` int(11) NOT NULL DEFAULT '0',
`documents_default_order_by` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'document_id',
`chats_default_order_by` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'chat_id',
PRIMARY KEY (`user_id`),
KEY `plan_id` (`plan_id`),
KEY `api_key` (`api_key`),
KEY `token_code` (`token_code`),
KEY `login_token` (`login_token`),
KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `codes` (
`code_id` int NOT NULL AUTO_INCREMENT,
`name` varchar(64) NOT NULL,
`type` varchar(16) NOT NULL,
`days` int DEFAULT NULL,
`code` varchar(32) NOT NULL,
`discount` int NOT NULL,
`quantity` int NOT NULL DEFAULT '1',
`redeemed` int NOT NULL DEFAULT '0',
`plans_ids` text,
`datetime` datetime NOT NULL,
PRIMARY KEY (`code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `users` (`user_id`, `email`, `api_key`, `token_code`, `referral_key`, `name`, `type`, `status`, `plan_id`, `plan_expiration_date`, `plan_settings`, `datetime`, `ip`, `last_activity`, `preferences`, `anti_phishing_code`)
VALUES (1,'admin@seegap.com', md5(rand()), md5(rand()), md5(rand()), 'SeeGap Admin',1,1,'custom','2030-01-01 12:00:00', '{"url_minimum_characters":1,"url_maximum_characters":64,"additional_domains":["69"],"microsites_templates":[],"microsites_themes":["33","1","2","3","4","5","32","34"],"custom_url":true,"deep_links":true,"no_ads":true,"white_labeling_is_enabled":true,"export":{"pdf":true,"csv":true,"json":true},"removable_branding":true,"custom_branding":true,"statistics":true,"temporary_url_is_enabled":true,"cloaking_is_enabled":true,"app_linking_is_enabled":true,"targeting_is_enabled":true,"seo":true,"utm":true,"fonts":true,"sensitive_content":true,"leap_link":true,"api_is_enabled":true,"dofollow_is_enabled":true,"custom_pwa_is_enabled":true,"microsite_blocks_limit":-1,"projects_limit":-1,"splash_pages_limit":-1,"pixels_limit":-1,"qr_codes_limit":-1,"qr_codes_bulk_limit":-1,"microsites_limit":-1,"links_limit":-1,"files_limit":-1,"events_limit":-1,"static_limit":-1,"domains_limit":-1,"payment_processors_limit":-1,"signatures_limit":-1,"teams_limit":-1,"team_members_limit":-1,"gs1_links_limit":-1,"products_limit":-1,"affiliate_commission_percentage":10,"track_links_retention":999,"custom_css_is_enabled":true,"custom_js_is_enabled":true,"enabled_microsite_blocks":{"link":true,"text":true,"avatar":true,"image":true,"socials":true,"email_collector":true,"soundcloud":true,"spotify":true,"twitch":true,"vimeo":true,"paypal":true,"phone_collector":true,"contact_collector":true,"feedback_collector":true,"form":true,"map":true,"applemusic":true,"tidal":true,"mixcloud":true,"kick":true,"anchor":true,"pinterest_profile":true,"snapchat":true,"rss_feed":true,"custom_html":true,"image_grid":true,"divider":true,"list":true,"big_link":true,"faq":true,"typeform":true,"calendly":true,"reddit":true,"audio":true,"video":true,"iframe":true,"file":true,"countdown":true,"external_item":true,"coupon":true,"timeline":true,"review":true,"image_slider":true,"pdf_document":true,"powerpoint_presentation":true,"excel_spreadsheet":true,"markdown":true,"donation":true,"product":true,"service":true,"social_media_embed":true,"accordion":true,"cover":true},"exclusive_personal_api_keys":false,"documents_model":"gpt-4","documents_per_month_limit":-1,"words_per_month_limit":-1,"images_api":"dall-e-2","images_per_month_limit":-1,"transcriptions_per_month_limit":-1,"transcriptions_file_size_limit":2,"chats_model":"gpt-4","chats_per_month_limit":-1,"chat_messages_per_chat_limit":-1,"chat_image_size_limit":2,"syntheses_api":"openai_audio","syntheses_per_month_limit":-1,"synthesized_characters_per_month_limit":-1,"force_splash_page_on_link":false,"force_splash_page_on_microsite":false,"force_splash_page_on_file":false,"force_splash_page_on_static":false,"force_splash_page_on_event":false}', NOW(),'',NOW(), '{"default_results_per_page":100,"default_order_type":"DESC","links_default_order_by":"link_id","qr_codes_default_order_by":"qr_code_id","openai_api_key":"","clipdrop_api_key":""}', SUBSTRING(MD5(RAND()), 1, 8));

-- SEPARATOR --

CREATE TABLE `users_logs` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`type` varchar(64) DEFAULT NULL,
`ip` varchar(64) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`city_name` varchar(32) DEFAULT NULL,
`browser_language` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `users_logs_user_id` (`user_id`),
KEY `users_logs_ip_type_datetime_index` (`ip`,`type`,`datetime`),
CONSTRAINT `users_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `taxes` (
`tax_id` int NOT NULL AUTO_INCREMENT,
`name` varchar(64) NOT NULL,
`description` varchar(256) DEFAULT NULL,
`value` float NOT NULL,
`value_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
`type` enum('inclusive','exclusive') NOT NULL DEFAULT 'inclusive',
`billing_type` enum('personal','business','both') NOT NULL DEFAULT 'both',
`countries` text,
`datetime` datetime NOT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `plans` (
`plan_id` int NOT NULL AUTO_INCREMENT,
`name` varchar(64) NOT NULL DEFAULT '',
`description` varchar(256) NOT NULL DEFAULT '',
`translations` text NOT NULL,
`prices` text NOT NULL,
`trial_days` int unsigned NOT NULL DEFAULT '0',
`settings` longtext NOT NULL,
`taxes_ids` text,
`color` varchar(16) DEFAULT NULL,
`status` tinyint(4) NOT NULL,
`order` int(10) unsigned DEFAULT '0',
`datetime` datetime NOT NULL,
PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- SEPARATOR --





CREATE TABLE `broadcasts` (
`broadcast_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) DEFAULT NULL,
`subject` varchar(128) DEFAULT NULL,
`content` text,
`segment` varchar(64) DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`users_ids` longtext CHARACTER SET utf8mb4,
`sent_users_ids` longtext,
`sent_emails` int unsigned DEFAULT '0',
`total_emails` int unsigned DEFAULT '0',
`status` varchar(16) DEFAULT NULL,
`views` bigint unsigned DEFAULT '0',
`clicks` bigint unsigned DEFAULT '0',
`last_sent_email_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`broadcast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts_statistics` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`broadcast_id` bigint unsigned DEFAULT NULL,
`type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`target` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `broadcast_id` (`broadcast_id`),
KEY `broadcasts_statistics_user_id_broadcast_id_type_index` (`broadcast_id`,`user_id`,`type`),
CONSTRAINT `broadcasts_statistics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `broadcasts_statistics_ibfk_2` FOREIGN KEY (`broadcast_id`) REFERENCES `broadcasts` (`broadcast_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `internal_notifications` (
`internal_notification_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`for_who` varchar(16) DEFAULT NULL,
`from_who` varchar(16) DEFAULT NULL,
`icon` varchar(64) DEFAULT NULL,
`title` varchar(128) DEFAULT NULL,
`description` varchar(1024) DEFAULT NULL,
`url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`is_read` tinyint unsigned DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`read_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`internal_notification_id`),
KEY `user_id` (`user_id`),
KEY `users_notifications_for_who_idx` (`for_who`) USING BTREE,
CONSTRAINT `internal_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `settings` (
`id` int NOT NULL AUTO_INCREMENT,
`key` varchar(64) NOT NULL DEFAULT '',
`value` longtext NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

SET @cron_key = MD5(RAND());

-- SEPARATOR --

CREATE TABLE `projects` (
`project_id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`name` varchar(64) NOT NULL DEFAULT '',
`color` varchar(16) DEFAULT '#000000',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`project_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;


-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`)
VALUES
('main', '{"title":"Your title","default_language":"english","default_theme_style":"light","default_timezone":"UTC","index_url":"","terms_and_conditions_url":"","privacy_policy_url":"","not_found_url":"","ai_scraping_is_allowed":true,"se_indexing":true,"display_index_plans":true,"display_index_testimonials":true,"display_index_faq":true,"display_index_latest_blog_posts":true,"default_results_per_page":100,"default_order_type":"DESC","auto_language_detection_is_enabled":true,"blog_is_enabled":false,"api_is_enabled":true,"theme_style_change_is_enabled":true,"logo_light":"","logo_dark":"","logo_email":"","opengraph":"","favicon":"","openai_api_key":"","openai_model":"gpt-4o","force_https_is_enabled":false,"broadcasts_statistics_is_enabled":true,"breadcrumbs_is_enabled":true,"display_pagination_when_no_pages":false,"chart_cache":12,"chart_days":30}'),
('languages', '{"english":{"status":"active"}}'),
('users', '{"email_confirmation":false,"welcome_email_is_enabled":false,"register_is_enabled":true,"register_display_newsletter_checkbox":false,"login_rememberme_checkbox_is_checked":true,"login_rememberme_cookie_days":90,"auto_delete_unconfirmed_users":3,"auto_delete_inactive_users":30,"user_deletion_reminder":0,"blacklisted_domains":[],"blacklisted_countries":[],"login_lockout_is_enabled":true,"login_lockout_max_retries":3,"login_lockout_time":10,"register_lockout_is_enabled":true,"register_lockout_max_registrations":3,"register_lockout_time":10}'),
('ads', '{"ad_blocker_detector_is_enabled":true,"ad_blocker_detector_lock_is_enabled":false,"ad_blocker_detector_delay":5,"header":"","footer":"","header_microsite":"","footer_microsite":"","header_splash":"","footer_splash":""}'),
('captcha', '{"type":"basic","recaptcha_public_key":"","recaptcha_private_key":"","login_is_enabled":0,"register_is_enabled":0,"lost_password_is_enabled":0,"resend_activation_is_enabled":0}'),
('cron', concat('{\"key\":\"', @cron_key, '\"}')),
('email_notifications', '{"emails":"","new_user":false,"delete_user":false,"new_payment":false,"new_domain":false,"new_affiliate_withdrawal":false,"contact":false}'),
('internal_notifications', '{"users_is_enabled":true,"admins_is_enabled":true,"new_user":true,"delete_user":true,"new_newsletter_subscriber":true,"new_payment":true,"new_affiliate_withdrawal":true}'),
('content', '{}'),
('sso', '{"is_enabled":true,"display_menu_items":true,"websites":{}}'),
('facebook', '{"is_enabled":false,"app_id":"","app_secret":""}'),
('google', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('twitter', '{"is_enabled":false,"consumer_api_key":"","consumer_api_secret":""}'),
('discord', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('linkedin', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('microsoft', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('plan_custom', '{"plan_id":"custom","name":"Custom","description":"Contact us for enterprise pricing.","price":"Custom","custom_button_url":"mailto:sample@example.com","color":null,"status":2,"settings":{}}'),
('plan_free', '{"plan_id":"free","name":"Free","days":null,"status":1,"settings":{"additional_global_domains":true,"custom_url":true,"deep_links":true,"no_ads":true,"export": {"pdf": true,"csv": true,"json": true},"removable_branding":true,"custom_branding":true,"custom_colored_links":true,"statistics":true,"custom_backgrounds":true,"verified":true,"temporary_url_is_enabled":true,"seo":true,"utm":true,"socials":true,"fonts":true,"password":true,"sensitive_content":true,"leap_link":true,"api_is_enabled":true,"affiliate_is_enabled":true,"projects_limit":10,"pixels_limit":10,"microsites_limit":15,"links_limit":25,"domains_limit":1,"enabled_microsite_blocks":{"link":true,"text":true,"image":true,"mail":true,"soundcloud":true,"spotify":true,"youtube":true,"twitch":true,"vimeo":true,"tiktok":true,"applemusic":true,"tidal":true,"anchor":true,"twitter_tweet":true,"instagram_media":true,"rss_feed":true,"custom_html":true,"image_grid":true,"divider":true}}}'),
('payment', '{"is_enabled":false,"type":"both","default_payment_frequency":"monthly","currencies":{"USD":{"code":"USD","symbol":"$","default_payment_processor":"offline_payment"}},"default_currency":"USD","codes_is_enabled":true,"taxes_and_billing_is_enabled":true,"invoice_is_enabled":true,"user_plan_expiry_reminder":0,"user_plan_expiry_checker_is_enabled":0,"currency_exchange_api_key":""}'),
('paypal', '{\"is_enabled\":\"0\",\"mode\":\"sandbox\",\"client_id\":\"\",\"secret\":\"\"}'),
('stripe', '{\"is_enabled\":\"0\",\"publishable_key\":\"\",\"secret_key\":\"\",\"webhook_secret\":\"\"}'),
('offline_payment', '{"is_enabled":true,"instructions":"Your offline/manual payment instructions go here, which the user will see when paying via this method.","proof_size_limit":2}'),
('coinbase', '{"is_enabled":false,"api_key":"","webhook_secret":"","currencies":["USD"]}'),
('payu', '{"is_enabled":false,"mode":"sandbox","merchant_pos_id":"","signature_key":"","oauth_client_id":"","oauth_client_secret":"","currencies":["USD"]}'),
('iyzico', '{"is_enabled":false,"mode":"live","api_key":"","secret_key":"","currencies":["USD"]}'),
('paystack', '{"is_enabled":false,"public_key":"","secret_key":"","currencies":["USD"]}'),
('razorpay', '{"is_enabled":false,"key_id":"","key_secret":"","webhook_secret":"","currencies":["USD"]}'),
('mollie', '{"is_enabled":false,"api_key":""}'),
('yookassa', '{"is_enabled":false,"shop_id":"","secret_key":""}'),
('crypto_com', '{"is_enabled":false,"publishable_key":"","secret_key":"","webhook_secret":""}'),
('paddle', '{"is_enabled":false,"mode":"sandbox","vendor_id":"","api_key":"","public_key":"","currencies":["USD"]}'),
('mercadopago', '{"is_enabled":false,"access_token":"","currencies":["USD"]}'),
('midtrans', '{"is_enabled":false,"server_key":"","mode":"sandbox","currencies":["USD"]}'),
('flutterwave', '{"is_enabled":false,"secret_key":"","currencies":["USD"]}'),
('lemonsqueezy', '{"is_enabled":false,"api_key":"","signing_secret":"","store_id":"","one_time_monthly_variant_id":"","one_time_annual_variant_id":"","one_time_lifetime_variant_id":"","recurring_monthly_variant_id":"","recurring_annual_variant_id":"","currencies":["USD"]}'),
('myfatoorah', '{"is_enabled":1,"api_endpoint":"apitest.myfatoorah.com","api_key":"","secret_key":"","currencies":["KWD"]}'),
('smtp', '{"from_name":"SeeGap","from":"","reply_to_name":"","reply_to":"","cc":"","bcc":"","host":"","encryption":"tls","port":"","auth":0,"username":"","password":"","display_socials":false,"company_details":""}'),
('email_templates', '{"login_subject":"Your secure login link for {{SITE_TITLE}}","login_body":"<h2>Secure Login Request</h2>\\n<p>Hello {{USER_NAME}},</p>\\n<p>We received a request to log in to your account at {{SITE_TITLE}}.</p>\\n<p><strong>Login Details:</strong></p>\\n<ul>\\n<li>Email: {{USER_EMAIL}}</li>\\n<li>IP Address: {{USER_IP}}</li>\\n<li>Device: {{USER_DEVICE}}</li>\\n<li>Security Code: {{SECURITY_CODE}}</li>\\n</ul>\\n<p>Click the button below to securely log in to your account:</p>\\n<p><a href=\\"{{LOGIN_LINK}}\\" style=\\"background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\\">Login to {{SITE_TITLE}}</a></p>\\n<p>This link will expire in 15 minutes for your security.</p>\\n<p>If you did not request this login, please ignore this email and consider changing your account password.</p>\\n<p>Best regards,<br>The {{SITE_TITLE}} Team</p>","welcome_subject":"Welcome to {{SITE_TITLE}}!","welcome_body":"<h2>Welcome to {{SITE_TITLE}}!</h2>\\n<p>Hello {{USER_NAME}},</p>\\n<p>Welcome to {{SITE_TITLE}}! We are excited to have you as part of our community.</p>\\n<p>Your account has been successfully created and you can now start using all our features.</p>\\n<p><a href=\\"{{SITE_URL}}\\" style=\\"background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\\">Get Started</a></p>\\n<p>If you have any questions or need assistance, feel free to contact our support team.</p>\\n<p>Best regards,<br>The {{SITE_TITLE}} Team</p>","account_delete_subject":"Confirm account deletion for {{SITE_TITLE}}","account_delete_body":"<h2>Account Deletion Request</h2>\\n<p>Hello {{USER_NAME}},</p>\\n<p>We received a request to delete your account at {{SITE_TITLE}}.</p>\\n<p><strong>Account Details:</strong></p>\\n<ul>\\n<li>Email: {{USER_EMAIL}}</li>\\n<li>Request from IP: {{USER_IP}}</li>\\n<li>Device: {{USER_DEVICE}}</li>\\n</ul>\\n<p>If you want to proceed with deleting your account, please click the button below:</p>\\n<p><a href=\\"{{SITE_URL}}\\" style=\\"background-color: #dc3545; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\\">Confirm Account Deletion</a></p>\\n<p><strong>Warning:</strong> This action cannot be undone. All your data will be permanently deleted.</p>\\n<p>If you did not request this deletion, please ignore this email and consider changing your account password.</p>\\n<p>Best regards,<br>The {{SITE_TITLE}} Team</p>"}'),
('custom', '{"body_content":"","head_js":"","head_css":"","head_js_microsite":"","head_css_microsite":"","body_content_microsite":"","head_js_splash_page":"","head_css_splash_page":"","body_content_splash_page":""}'),
('theme', '{"light_is_enabled": false, "dark_is_enabled": false}'),
('socials', '{"threads":"","youtube":"","facebook":"","x":"","instagram":"","tiktok":"","linkedin":"","whatsapp":"","email":""}'),
('announcements', '{"guests_is_enabled":0,"guests_id":"035cc337f6de075434bc24807b7ad9af","guests_content":"","guests_text_color":"#000000","guests_background_color":"#000000","users_is_enabled":0,"users_id":"035cc337f6de075434bc24807b7ad9af","users_content":"","users_text_color":"#000000","users_background_color":"#000000","translations":{"english":{"guests_content":"","users_content":""}}}'),
('business', '{\"invoice_is_enabled\":\"0\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"\",\"email\":\"\",\"phone\":\"\",\"tax_type\":\"\",\"tax_id\":\"\",\"custom_key_one\":\"\",\"custom_value_one\":\"\",\"custom_key_two\":\"\",\"custom_value_two\":\"\"}'),
('webhooks', '{"user_new":"","user_delete":"","payment_new":"","code_redeemed":"","contact":"","cron_start":"","cron_end":"","domain_new":"","domain_update":""}'),
('cookie_consent', '{"is_enabled":false,"logging_is_enabled":false,"necessary_is_enabled":true,"analytics_is_enabled":true,"targeting_is_enabled":true,"layout":"bar","position_y":"middle","position_x":"center"}'),
('links', '{"available_microsite_blocks":{"link":true,"text":true,"avatar":true,"image":true,"socials":true,"email_collector":true,"soundcloud":true,"spotify":true,"twitch":true,"vimeo":true,"paypal":true,"phone_collector":true,"contact_collector":true,"feedback_collector":true,"form":true,"map":true,"applemusic":true,"tidal":true,"anchor":true,"pinterest_profile":true,"snapchat":true,"rss_feed":true,"custom_html":true,"image_grid":true,"divider":true,"list":true,"big_link":true,"faq":true,"typeform":true,"reddit":true,"audio":true,"video":true,"iframe":true,"file":true,"countdown":true,"external_item":true,"timeline":true,"review":true,"image_slider":true,"pdf_document":true,"markdown":true,"donation":true,"product":true,"service":true,"social_media_embed":true,"accordion":true,"cover":true},"example_url":"","random_url_length":5,"branding":"Powered by Seegap","shortener_is_enabled":1,"microsites_is_enabled":1,"microsites_templates_is_enabled":1,"microsites_themes_is_enabled":"on","microsites_new_blocks_position":"bottom","microsites_default_active_tab":"settings","default_microsite_theme_id":null,"default_microsite_template_id":null,"files_is_enabled":1,"events_is_enabled":1,"static_is_enabled":1,"pixels_is_enabled":1,"splash_page_is_enabled":1,"splash_page_auto_redirect":1,"splash_page_link_unlock_seconds":3,"directory_is_enabled":1,"directory_access":"everyone","directory_display":"all","domains_is_enabled":1,"additional_domains_is_enabled":1,"main_domain_is_enabled":1,"domains_custom_main_ip":"","blacklisted_domains":[],"blacklisted_keywords":[],"google_safe_browsing_is_enabled":0,"google_safe_browsing_api_key":"","google_static_maps_is_enabled":0,"google_static_maps_api_key":"","avatar_size_limit":2,"background_size_limit":2,"favicon_size_limit":2,"seo_image_size_limit":2,"thumbnail_image_size_limit":2,"image_size_limit":2,"audio_size_limit":2,"video_size_limit":2,"file_size_limit":2,"product_file_size_limit":2,"static_size_limit":2,"whitelisted_image_extensions":["jpg","jpeg","png","gif","webp","svg"],"whitelisted_audio_extensions":["mp3","wav","ogg","m4a"],"whitelisted_video_extensions":["mp4","webm","ogg","avi","mov"],"whitelisted_file_extensions":["pdf","doc","docx","txt","zip","rar"]}'),
('codes', '{"qr_codes_is_enabled":1,"logo_size_limit":1,"background_size_limit":1,"available_qr_codes":{"text":true,"url":true,"phone":true,"sms":true,"email":true,"whatsapp":true,"facetime":true,"location":true,"wifi":true,"event":true,"vcard":true,"crypto":true,"paypal":true,"upi":true,"epc":true,"pix":true},"qr_codes_branding_logo":"","qr_codes_default_image":""}'),
('gs1_links', '{"gs1_links_is_enabled":true,"gtin_validation_is_enabled":true,"gtin_format_validation":"disabled","require_target_url":false,"default_target_url":"","domains_is_enabled":true,"projects_is_enabled":true,"pixels_is_enabled":true,"analytics_is_enabled":true,"auto_generate_qr_codes":false,"branding":"","random_gtin_length":"14","blacklisted_gtins":[],"allowed_gtin_prefixes":[]}'),
('products', '{"products_is_enabled":true,"gtin_validation_is_enabled":true,"gtin_format_validation":"disabled","require_product_name":true,"require_brand_name":false,"auto_generate_gs1_links":false,"auto_generate_qr_codes":false,"projects_is_enabled":true,"image_size_limit":5,"max_images_per_product":10,"allowed_categories":[],"required_fields":["product_name","gtin"],"export_formats":["csv","json"],"bulk_import_is_enabled":true,"analytics_is_enabled":true}'),
('aix', '{"is_enabled":true,"openai_api_key":"","openai_model":"gpt-4","openai_max_tokens":4096,"google_api_key":"","google_model":"gemini-pro","anthropic_api_key":"","anthropic_model":"claude-3-sonnet-20240229","documents_is_enabled":true,"images_is_enabled":true,"chats_is_enabled":true,"templates_is_enabled":true,"receipt_analysis_is_enabled":true,"receipt_analysis_providers":["openai","google","anthropic"],"receipt_analysis_timeout":30,"receipt_analysis_max_retries":3,"receipt_analysis_auto_process":true,"receipt_analysis_extract_items":true,"receipt_analysis_extract_totals":true,"receipt_analysis_extract_merchant":true,"receipt_analysis_extract_date":true,"receipt_analysis_extract_payment":true}'),
('license', '{\"license\":\"BYPASSED-LICENSE\",\"type\":\"SPECIAL\"}'),
('product_info', '{\"version\":\"56.0.0\", \"code\":\"5600\"}'),
('support', '{\"key\":\"BYPASSED-SUPPORT\",\"expiry_datetime\":\"2099-12-31 23:59:59\"}');

-- SEPARATOR --

CREATE TABLE `splash_pages` (
`splash_page_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`name` varchar(64) NOT NULL,
`title` varchar(256) DEFAULT NULL,
`description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`link_unlock_seconds` int unsigned DEFAULT '5',
`auto_redirect` tinyint unsigned DEFAULT '0',
`settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`splash_page_id`),
KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `microsites_themes` (
`microsite_theme_id` int NOT NULL AUTO_INCREMENT,
`name` varchar(64) NOT NULL,
`settings` text,
`is_enabled` tinyint NOT NULL DEFAULT '1',
`order` int DEFAULT '0',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`microsite_theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `microsites_themes` (`microsite_theme_id`, `name`, `settings`, `is_enabled`, `order`, `last_datetime`, `datetime`) VALUES
(1, 'Paris', '{\"additional\":{\"custom_css\":\"\",\"custom_js\":\"\"},\"microsite\":{\"background_type\":\"preset\",\"background\":\"zero\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"default\",\"font_size\":\"16\",\"background_blur\":0,\"background_brightness\":100,\"width\":8,\"block_spacing\":2,\"hover_animation\":\"smooth\"},\"microsite_block\":{\"text_color\":\"#FFFFFF\",\"description_color\":\"#FFFFFFC9\",\"background_color\":\"#0000004A\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"},\"microsite_block_socials\":{\"color\":\"#FFFFFF\",\"background_color\":\"#FFFFFF00\",\"border_radius\":\"straight\"},\"microsite_block_paragraph\":{\"text_color\":\"#FFFFFFD4\"},\"microsite_block_heading\":{\"text_color\":\"#FFFFFF\"}}', 1, 1, '2025-03-24 23:42:49', '2024-09-07 16:36:29'),
(2, 'Tokyo', '{\"additional\":{\"custom_css\":\"\",\"custom_js\":\"<script>\\r\\n\\r\\ndocument.body.style.backgroundImage = `url(\'${site_url}themes\\/phoenix\\/assets\\/images\\/microsites\\/leaves.svg\'), linear-gradient(0deg, #FFDEE9 0%, #B5FFFC 100%)`;\\r\\n\\r\\n<\\/script>\"},\"microsite\":{\"background_type\":\"preset\",\"background\":\"ten\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"default\",\"font_size\":\"16\",\"background_blur\":0,\"background_brightness\":100,\"width\":8,\"block_spacing\":2,\"hover_animation\":\"smooth\"},\"microsite_block\":{\"text_color\":\"#000000\",\"description_color\":\"#383838\",\"background_color\":\"#FFFFFF\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"round\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"},\"microsite_block_socials\":{\"color\":\"#131313\",\"background_color\":\"#FFFFFF99\",\"border_radius\":\"round\"},\"microsite_block_paragraph\":{\"text_color\":\"#40455B\"},\"microsite_block_heading\":{\"text_color\":\"#000000\"}}', 1, 2, '2025-03-25 02:49:48', '2024-09-07 16:36:29'),
(3, 'Sydney', '{\"additional\":{\"custom_css\":\"\",\"custom_js\":\"\"},\"microsite\":{\"background_type\":\"preset\",\"background\":\"thirteen\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"default\",\"font_size\":\"16\",\"background_blur\":0,\"background_brightness\":100,\"width\":8,\"block_spacing\":2,\"hover_animation\":\"smooth\"},\"microsite_block\":{\"text_color\":\"#ffffff\",\"description_color\":\"#ffffff\",\"background_color\":\"#21007ABD\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"straight\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"},\"microsite_block_socials\":{\"color\":\"#FFFFFF\",\"background_color\":\"#FFFFFF45\",\"border_radius\":\"straight\"},\"microsite_block_paragraph\":{\"text_color\":\"#FFFFFFCF\"},\"microsite_block_heading\":{\"text_color\":\"#FFFFFF\"}}', 1, 3, '2025-03-25 01:25:53', '2024-09-07 16:36:29'),
(4, 'London', '{\"additional\":{\"custom_css\":\"\",\"custom_js\":\"\"},\"microsite\":{\"background_type\":\"preset\",\"background\":\"four\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"trebuchet_ms\",\"font_size\":\"16\",\"background_blur\":1,\"background_brightness\":100,\"width\":8,\"block_spacing\":2,\"hover_animation\":\"smooth\"},\"microsite_block\":{\"text_color\":\"#FFFFFF\",\"description_color\":\"#F2F2F2\",\"background_color\":\"#94008B30\",\"border_width\":\"2\",\"border_color\":\"#6700601C\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"3\",\"border_shadow_offset_y\":\"3\",\"border_shadow_blur\":\"15\",\"border_shadow_spread\":\"2\",\"border_shadow_color\":\"#88888800\"},\"microsite_block_socials\":{\"color\":\"#FFFFFF\",\"background_color\":\"#E95FA1\",\"border_radius\":\"round\"},\"microsite_block_paragraph\":{\"text_color\":\"#FFFFFF\"},\"microsite_block_heading\":{\"text_color\":\"#FFFFFF\"}}', 1, 4, '2025-03-25 01:27:33', '2024-09-07 16:36:29'),
(5, 'Antalya', '{\"additional\":{\"custom_css\":\"\",\"custom_js\":\"\"},\"microsite\":{\"background_type\":\"preset_abstract\",\"background\":\"seven\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"montserrat\",\"font_size\":\"16\",\"background_blur\":5,\"background_brightness\":100,\"width\":8,\"block_spacing\":2,\"hover_animation\":\"smooth\"},\"microsite_block\":{\"text_color\":\"#00644B\",\"description_color\":\"#00AC8B\",\"background_color\":\"#2CFFD5\",\"border_width\":\"0\",\"border_color\":\"#67006000\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"10\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#3DFFB359\"},\"microsite_block_socials\":{\"color\":\"#217361\",\"background_color\":\"#89FFE5\",\"border_radius\":\"rounded\"},\"microsite_block_paragraph\":{\"text_color\":\"#FFFFFF\"},\"microsite_block_heading\":{\"text_color\":\"#FFFFFF\"}}', 1, 5, '2025-03-25 01:30:24', '2024-09-07 16:36:29'),
(6, 'Zermatt', '{\"additional\":{\"custom_css\":\"#snowfall-element {\\r\\n\\tposition: fixed;\\r\\n\\ttop: 0;\\r\\n\\tleft: 0;\\r\\n\\twidth: 100vw;\\r\\n\\theight: 100vh;\\r\\n\\tpointer-events: none;\\r\\n\\tz-index: 0; \\/* Not too negative this time *\\/\\r\\n\\tbackground: transparent;\\r\\n}\",\"custom_js\":\"<script>\\r\\nconst snowfall = {};\\r\\n\\r\\n\\/\\/ Automatically create and insert the canvas element just after <body>\\r\\nsnowfall.canvas = document.createElement(\\\"canvas\\\");\\r\\nsnowfall.canvas.id = \\\"snowfall-element\\\";\\r\\ndocument.documentElement.appendChild(snowfall.canvas); \\/\\/ Append to <html>\\r\\n\\r\\n\\/\\/ Continue as before\\r\\nsnowfall.context = snowfall.canvas.getContext(\\\"2d\\\");\\r\\n\\r\\n\\/\\/ Snowflake constructor\\r\\nsnowfall.snowflake = function () {\\r\\n\\tthis.size = Math.random() * 2 + 2;\\r\\n\\tthis.x = (Math.random() * snowfall.canvas.width - this.size - 1) + this.size + 1;\\r\\n\\tthis.baseX = this.x;\\r\\n\\tthis.distance = Math.random() * 50 + 1;\\r\\n\\tthis.opacity = Math.random();\\r\\n\\tthis.radians = Math.random() * Math.PI * 2;\\r\\n\\tthis.fall_speed = Math.random() * 1.5 + 0.5;\\r\\n\\tthis.y = (Math.random() * snowfall.canvas.height - this.size - 1) + this.size + 1;\\r\\n\\tthis.draw = () => {\\r\\n\\t\\tif(this.y > snowfall.canvas.height + this.size) {\\r\\n\\t\\t\\tthis.y = -this.size;\\r\\n\\t\\t} else {\\r\\n\\t\\t\\tthis.y += this.fall_speed;\\r\\n\\t\\t}\\r\\n\\t\\tthis.radians += 0.02;\\r\\n\\t\\tthis.x = this.baseX + this.distance * Math.sin(this.radians);\\r\\n\\t\\tsnowfall.context.fillStyle = `rgba(255,255,255,${this.opacity})`;\\r\\n\\t\\tsnowfall.context.fillRect(this.x, this.y, this.size, this.size);\\r\\n\\t};\\r\\n};\\r\\n\\r\\n\\/\\/ Initial setup function\\r\\nsnowfall.setup = () => {\\r\\n\\tsnowfall.canvas.width = snowfall.context.canvas.clientWidth;\\r\\n\\tsnowfall.canvas.height = snowfall.context.canvas.clientHeight;\\r\\n\\tsnowfall.flakes = [];\\r\\n\\tconst flake_count = Math.ceil((snowfall.canvas.width * snowfall.canvas.height) \\/ 12000);\\r\\n\\tfor (let i = 0; i < flake_count; i++) {\\r\\n\\t\\tsnowfall.flakes.push(new snowfall.snowflake());\\r\\n\\t}\\r\\n};\\r\\n\\r\\nwindow.addEventListener(\\\"resize\\\", snowfall.setup);\\r\\n\\r\\n\\/\\/ Animation loop function\\r\\nsnowfall.animate = () => {\\r\\n\\trequestAnimationFrame(snowfall.animate);\\r\\n\\tsnowfall.context.clearRect(0, 0, snowfall.canvas.width, snowfall.canvas.height);\\r\\n\\tfor (let snowflake of snowfall.flakes) {\\r\\n\\t\\tsnowflake.draw();\\r\\n\\t}\\r\\n};\\r\\n\\r\\n\\/\\/ Let it snow!\\r\\nsnowfall.setup();\\r\\nsnowfall.animate();<\\/script>\"},\"microsite\":{\"background_type\":\"image\",\"background\":\"78564ffadd816470639d7f68149ee338.webp\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"karla\",\"font_size\":\"16\",\"background_blur\":2,\"background_brightness\":100,\"width\":8,\"block_spacing\":2,\"hover_animation\":\"smooth\"},\"microsite_block\":{\"text_color\":\"#ffffff\",\"description_color\":\"#FFF0F0\",\"background_color\":\"#02343B\",\"border_width\":\"1\",\"border_color\":\"#FFFFFF4A\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"},\"microsite_block_socials\":{\"color\":\"#FFFFFFBA\",\"background_color\":\"#228694A1\",\"border_radius\":\"rounded\"},\"microsite_block_paragraph\":{\"text_color\":\"#FFFFFFD9\"},\"microsite_block_heading\":{\"text_color\":\"#FFFFFF\"}}', 1, 6, '2025-03-25 01:31:19', '2025-03-23 04:31:05'),
(7, 'Seattle', '{\"additional\":{\"custom_css\":\"\",\"custom_js\":\"<script>\\r\\n    const computed_styles = getComputedStyle(document.body);\\r\\n\\r\\n    const original_background_image = computed_styles.backgroundImage;\\r\\n    const original_background_size = computed_styles.backgroundSize;\\r\\n    const original_background_position = computed_styles.backgroundPosition;\\r\\n    const original_background_repeat = computed_styles.backgroundRepeat;\\r\\n\\r\\n    const rain_images = [\\r\\n        `url(\'${site_url}themes\\/phoenix\\/assets\\/images\\/microsites\\/rain.svg\')`,\\r\\n        `url(\'${site_url}themes\\/phoenix\\/assets\\/images\\/microsites\\/rain.svg\')`,\\r\\n        `url(\'${site_url}themes\\/phoenix\\/assets\\/images\\/microsites\\/rain.svg\')`\\r\\n    ];\\r\\n\\r\\n    const rain_sizes = [\'60%\', \'45%\', \'30%\'];\\r\\n    const rain_positions = [\'left top\', \'center top\', \'center top\'];\\r\\n    const rain_repeats = [\'repeat\', \'repeat\', \'repeat\'];\\r\\n\\r\\n    const all_images = rain_images.concat(original_background_image);\\r\\n    const all_sizes = rain_sizes.concat(original_background_size);\\r\\n    const all_positions = rain_positions.concat(original_background_position);\\r\\n    const all_repeats = rain_repeats.concat(original_background_repeat);\\r\\n\\r\\n    document.body.style.setProperty(\'background-image\', all_images.join(\', \'));\\r\\n    document.body.style.setProperty(\'background-size\', all_sizes.join(\', \'), \'important\');\\r\\n    document.body.style.setProperty(\'background-position\', all_positions.join(\', \'), \'important\');\\r\\n    document.body.style.setProperty(\'background-repeat\', all_repeats.join(\', \'), \'important\');\\r\\n<\\/script>\"},\"microsite\":{\"background_type\":\"image\",\"background\":\"25b10743f5d934e70250ffd557cee0a6.webp\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"inconsolata\",\"font_size\":\"16\",\"background_blur\":2,\"background_brightness\":100,\"width\":8,\"block_spacing\":2,\"hover_animation\":\"smooth\"},\"microsite_block\":{\"text_color\":\"#ffffff\",\"description_color\":\"#ffffff\",\"background_color\":\"#000000B5\",\"border_width\":\"0\",\"border_color\":\"\",\"border_radius\":\"straight\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"},\"microsite_block_socials\":{\"color\":\"#ffffff\",\"background_color\":\"#00000000\",\"border_radius\":\"rounded\"},\"microsite_block_paragraph\":{\"text_color\":\"#5DC5D5\"},\"microsite_block_heading\":{\"text_color\":\"#FFFFFF\"}}', 1, 7, '2025-03-25 06:24:02', '2025-03-25 04:39:59'),
(8, 'Kyoto', '{\"additional\":{\"custom_css\":\"\",\"custom_js\":\"<script>\\r\\n\\r\\ndocument.body.style.backgroundImage = `url(\'${site_url}themes\\/phoenix\\/assets\\/images\\/microsites\\/autumn_leaves.svg\'), ${document.body.style.backgroundImage}`;\\r\\n\\r\\n<\\/script>\"},\"microsite\":{\"background_type\":\"image\",\"background\":\"0fc5e5a6b52b9d58ffc6ecfb112c76df.webp\",\"background_color_one\":null,\"background_color_two\":null,\"font\":\"default\",\"font_size\":\"16\",\"background_blur\":3,\"background_brightness\":100,\"width\":8,\"block_spacing\":2,\"hover_animation\":\"smooth\"},\"microsite_block\":{\"text_color\":\"#ffffff\",\"description_color\":\"#ffffff\",\"background_color\":\"#BC5101ED\",\"border_width\":\"1\",\"border_color\":\"\",\"border_radius\":\"rounded\",\"border_style\":\"solid\",\"border_shadow_offset_x\":\"0\",\"border_shadow_offset_y\":\"0\",\"border_shadow_blur\":\"20\",\"border_shadow_spread\":\"0\",\"border_shadow_color\":\"#00000010\"},\"microsite_block_socials\":{\"color\":\"#FFE8C0\",\"background_color\":\"#0000007D\",\"border_radius\":\"round\"},\"microsite_block_paragraph\":{\"text_color\":\"#FFFFFF\"},\"microsite_block_heading\":{\"text_color\":\"#FFFFFF\"}}', 1, 8, '2025-03-26 02:46:32', '2025-03-26 02:20:40');

-- SEPARATOR --

CREATE TABLE `links` (
`link_id` int NOT NULL AUTO_INCREMENT,
`project_id` int DEFAULT NULL,
`splash_page_id` bigint unsigned DEFAULT NULL,
`user_id` int NOT NULL,
`microsite_theme_id` int DEFAULT NULL,
`domain_id` int DEFAULT '0',
`pixels_ids` text,
`type` varchar(32) NOT NULL DEFAULT '',
`subtype` varchar(32) DEFAULT NULL,
`url` varchar(256) NOT NULL DEFAULT '',
`location_url` varchar(2048) DEFAULT NULL,
`clicks` int NOT NULL DEFAULT '0',
`settings` text,
`additional` text,
`start_date` datetime DEFAULT NULL,
`end_date` datetime DEFAULT NULL,
`is_verified` tinyint DEFAULT '0',
`is_enabled` tinyint NOT NULL DEFAULT '1',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`link_id`),
KEY `project_id` (`project_id`),
KEY `user_id` (`user_id`),
KEY `url` (`url`),
KEY `links_subtype_index` (`subtype`),
KEY `links_type_index` (`type`),
KEY `links_microsites_themes_microsite_theme_id_fk` (`microsite_theme_id`),
KEY `links_splash_page_id_index` (`splash_page_id`),
CONSTRAINT `links_microsites_themes_microsite_theme_id_fk` FOREIGN KEY (`microsite_theme_id`) REFERENCES `microsites_themes` (`microsite_theme_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `links_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `links_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `links_splash_pages_splash_page_id_fk` FOREIGN KEY (`splash_page_id`) REFERENCES `splash_pages` (`splash_page_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `microsites_templates` (
`microsite_template_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`link_id` int DEFAULT NULL,
`name` varchar(64) NOT NULL,
`url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`is_enabled` tinyint NOT NULL DEFAULT '1',
`total_usage` bigint unsigned DEFAULT '0',
`order` int DEFAULT '0',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`microsite_template_id`),
KEY `link_id` (`link_id`),
CONSTRAINT `microsites_templates_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `track_links` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `link_id` int DEFAULT NULL,
  `microsite_block_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `country_code` varchar(8) DEFAULT NULL,
  `continent_code` varchar(8) DEFAULT NULL,
  `city_name` varchar(128) DEFAULT NULL,
  `os_name` varchar(16) DEFAULT NULL,
  `browser_name` varchar(32) DEFAULT NULL,
  `referrer_host` varchar(256) DEFAULT NULL,
  `referrer_path` varchar(1024) DEFAULT NULL,
  `device_type` varchar(16) DEFAULT NULL,
  `browser_language` varchar(16) DEFAULT NULL,
  `utm_source` varchar(128) DEFAULT NULL,
  `utm_medium` varchar(128) DEFAULT NULL,
  `utm_campaign` varchar(128) DEFAULT NULL,
  `is_unique` tinyint DEFAULT '0',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `link_id` (`link_id`),
  KEY `track_links_date_index` (`datetime`),
  KEY `track_links_project_id_index` (`project_id`),
  KEY `track_links_users_user_id_fk` (`user_id`),
  KEY `track_links_microsite_block_id_index` (`microsite_block_id`),
  CONSTRAINT `track_links_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `track_links_links_project_id_fk` FOREIGN KEY (`project_id`) REFERENCES `links` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `track_links_projects_project_id_fk` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `track_links_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- SEPARATOR --

CREATE TABLE `microsites_blocks` (
`microsite_block_id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`link_id` int DEFAULT NULL,
`type` varchar(32) NOT NULL DEFAULT '',
`location_url` varchar(512) DEFAULT NULL,
`clicks` int NOT NULL DEFAULT '0',
`settings` text,
`order` int NOT NULL DEFAULT '0',
`start_date` datetime DEFAULT NULL,
`end_date` datetime DEFAULT NULL,
`is_enabled` tinyint(4) NOT NULL DEFAULT '1',
`datetime` datetime NOT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`microsite_block_id`),
KEY `user_id` (`user_id`),
KEY `links_type_index` (`type`),
KEY `links_links_link_id_fk` (`link_id`),
CONSTRAINT `microsites_blocks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `microsites_blocks_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `pixels` (
`pixel_id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`type` varchar(64) NOT NULL,
`name` varchar(64) NOT NULL,
`pixel` varchar(64) NOT NULL,
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`pixel_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `pixels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `domains` (
`domain_id` int NOT NULL AUTO_INCREMENT,
`link_id` int DEFAULT NULL,
`user_id` int DEFAULT NULL,
`scheme` varchar(8) NOT NULL DEFAULT '',
`host` varchar(128) NOT NULL DEFAULT '',
`custom_index_url` varchar(256) DEFAULT NULL,
`custom_not_found_url` varchar(256) DEFAULT NULL,
`type` tinyint(11) DEFAULT '1',
`is_enabled` tinyint(4) DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`domain_id`),
KEY `user_id` (`user_id`),
KEY `host` (`host`),
KEY `type` (`type`),
KEY `domains_links_link_id_fk` (`link_id`),
CONSTRAINT `domains_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `domains_links_link_id_fk` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `data` (
`datum_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`microsite_block_id` int DEFAULT NULL,
`link_id` int DEFAULT NULL,
`project_id` int DEFAULT NULL,
`user_id` int NOT NULL,
`type` varchar(32) DEFAULT NULL,
`data` text,
`datetime` datetime NOT NULL,
PRIMARY KEY (`datum_id`),
UNIQUE KEY `datum_id` (`datum_id`),
KEY `link_id` (`link_id`),
KEY `project_id` (`project_id`),
KEY `user_id` (`user_id`),
KEY `microsite_block_id` (`microsite_block_id`),
CONSTRAINT `data_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `data_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `data_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `data_ibfk_4` FOREIGN KEY (`microsite_block_id`) REFERENCES `microsites_blocks` (`microsite_block_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `gs1_links` (
  `gs1_link_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `domain_id` int DEFAULT '0',
  `pixels_ids` text,
  `gtin` varchar(14) NOT NULL,
  `target_url` varchar(2048) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `description` text,
  `clicks` int NOT NULL DEFAULT '0',
  `settings` text,
  `is_enabled` tinyint NOT NULL DEFAULT '1',
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`gs1_link_id`),
  UNIQUE KEY `gtin_domain` (`gtin`, `domain_id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`),
  KEY `gtin` (`gtin`),
  CONSTRAINT `gs1_links_users_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gs1_links_projects_fk` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `qr_codes` (
  `qr_code_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `link_id` int DEFAULT NULL,
  `gs1_link_id` int DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code_logo` varchar(64) DEFAULT NULL,
  `qr_code_foreground` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code_background` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code` varchar(64) NOT NULL,
  `settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `embedded_data` text COLLATE utf8mb4_unicode_ci,
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`qr_code_id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`),
  KEY `qr_codes_links_link_id_fk` (`link_id`),
  KEY `qr_codes_gs1_links_gs1_link_id_fk` (`gs1_link_id`),
  CONSTRAINT `qr_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `qr_codes_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `qr_codes_links_link_id_fk` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `qr_codes_gs1_links_gs1_link_id_fk` FOREIGN KEY (`gs1_link_id`) REFERENCES `gs1_links` (`gs1_link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `links` (`link_id`, `project_id`, `user_id`, `domain_id`, `pixels_ids`, `type`, `url`, `location_url`, `clicks`, `settings`, `start_date`, `end_date`, `is_verified`, `is_enabled`, `datetime`) VALUES (1, NULL, 1, 0, '[]', 'microsite',  'example', NULL, 0, '{\"verified_location\":\"top\",\"background_type\":\"preset\",\"background\":\"six\",\"favicon\":null,\"text_color\":\"#fff\",\"display_branding\":true,\"branding\":{\"name\":\"\",\"url\":\"\"},\"seo\":{\"block\":false,\"title\":\"\",\"meta_description\":\"\",\"image\":\"\"},\"utm\":{\"medium\":\"\",\"source\":\"\"},\"font\":\"arial\",\"font_size\":16,\"password\":null,\"sensitive_content\":false,\"leap_link\":\"\"}', NULL, NULL, 1, 1, '2021-12-20 18:05:36');

-- SEPARATOR --

INSERT INTO `microsites_blocks` (`user_id`, `link_id`, `type`, `location_url`, `clicks`, `settings`, `order`, `start_date`, `end_date`, `is_enabled`, `datetime`) VALUES (1, 1, 'text', NULL, 0, '{\"content\":\"<h2>Welcome to Our Platform</h2><p>Discover amazing features and connect with our community. This is a sample text block with rich content formatting.</p>\",\"text_color\":\"#ffffff\",\"text_alignment\":\"center\",\"animation\":false,\"animation_runs\":\"repeat-1\",\"animation_delay\":0,\"background_color\":\"#00000000\",\"border_width\":0,\"border_color\":\"#ffffff\",\"border_radius\":4,\"border_style\":\"solid\",\"border_shadow_offset_x\":0,\"border_shadow_offset_y\":0,\"border_shadow_blur\":0,\"border_shadow_spread\":0,\"border_shadow_color\":\"#00000000\",\"display_continents\":[],\"display_countries\":[],\"display_cities\":[],\"display_devices\":[],\"display_languages\":[],\"display_operating_systems\":[],\"display_browsers\":[]}', 2, NULL, NULL, 1, '2021-12-20 18:07:15');

-- SEPARATOR --

INSERT INTO `microsites_blocks` (`user_id`, `link_id`, `type`, `location_url`, `clicks`, `settings`, `order`, `start_date`, `end_date`, `is_enabled`, `datetime`) VALUES (1, 1, 'link', 'https://seegap.com', 0, '{\"name\":\"Visit SeeGap\",\"open_in_new_tab\":false,\"text_color\":\"#000000\",\"text_alignment\":\"center\",\"background_color\":\"#ffffff\",\"border_shadow_offset_x\":0,\"border_shadow_offset_y\":0,\"border_shadow_blur\":20,\"border_shadow_spread\":0,\"border_shadow_color\":\"#00000010\",\"border_width\":0,\"border_style\":\"solid\",\"border_color\":\"#ffffff\",\"border_radius\":4,\"animation\":false,\"animation_runs\":\"repeat-1\",\"animation_delay\":0,\"icon\":\"\",\"image\":\"\",\"display_continents\":[],\"display_countries\":[],\"display_cities\":[],\"display_devices\":[],\"display_languages\":[],\"display_operating_systems\":[],\"display_browsers\":[]}', 3, NULL, NULL, 1, '2021-12-20 18:08:30');

-- SEPARATOR --

CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `plan_id` varchar(16) DEFAULT NULL,
  `payment_id` varchar(64) DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `processor` varchar(16) DEFAULT NULL,
  `type` varchar(16) DEFAULT NULL,
  `frequency` varchar(16) DEFAULT NULL,
  `billing` text,
  `taxes_ids` text,
  `base_amount` float DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `discount_amount` float DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `total_amount_default_currency` float DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `track_gs1_links` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `gs1_link_id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `gtin` varchar(14) NOT NULL,
  `country_code` varchar(8) DEFAULT NULL,
  `continent_code` varchar(8) DEFAULT NULL,
  `city_name` varchar(128) DEFAULT NULL,
  `os_name` varchar(16) DEFAULT NULL,
  `browser_name` varchar(32) DEFAULT NULL,
  `referrer_host` varchar(256) DEFAULT NULL,
  `referrer_path` varchar(1024) DEFAULT NULL,
  `device_type` varchar(16) DEFAULT NULL,
  `browser_language` varchar(16) DEFAULT NULL,
  `utm_source` varchar(128) DEFAULT NULL,
  `utm_medium` varchar(128) DEFAULT NULL,
  `utm_campaign` varchar(128) DEFAULT NULL,
  `is_unique` tinyint DEFAULT '0',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gs1_link_id` (`gs1_link_id`),
  KEY `gtin` (`gtin`),
  KEY `user_id` (`user_id`),
  KEY `track_gs1_links_date_index` (`datetime`),
  CONSTRAINT `track_gs1_links_gs1_fk` FOREIGN KEY (`gs1_link_id`) REFERENCES `gs1_links` (`gs1_link_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `track_gs1_links_users_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `reports` (
  `report_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text,
  `superset_embed_code` text,
  `assigned_user_ids` text,
  `is_enabled` tinyint NOT NULL DEFAULT '1',
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`report_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `form_submissions` (
  `form_submission_id` int NOT NULL AUTO_INCREMENT,
  `microsite_block_id` int NOT NULL,
  `link_id` int NOT NULL,
  `form_type` varchar(32) NOT NULL DEFAULT 'custom',
  `responses` longtext,
  `metadata` longtext,
  `receipt_images` longtext NULL COMMENT 'JSON array of uploaded receipt image paths',
  `ai_analysis_data` longtext NULL COMMENT 'JSON object containing AI analysis results from all providers',
  `ai_analysis_status` ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending' COMMENT 'Status of AI analysis processing',
  `ai_providers_used` varchar(255) NULL COMMENT 'Comma-separated list of AI providers used for analysis',
  `ip` varchar(64) DEFAULT NULL,
  `user_agent` text,
  `submitted_at` datetime NOT NULL,
  PRIMARY KEY (`form_submission_id`),
  KEY `microsite_block_id` (`microsite_block_id`),
  KEY `link_id` (`link_id`),
  KEY `submitted_at` (`submitted_at`),
  KEY `idx_form_submissions_ai_status` (`ai_analysis_status`),
  KEY `idx_form_submissions_receipt_images` (`receipt_images`(255)),
  CONSTRAINT `form_submissions_ibfk_1` FOREIGN KEY (`microsite_block_id`) REFERENCES `microsites_blocks` (`microsite_block_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `form_submissions_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `links` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `gtin` varchar(14) NOT NULL COMMENT 'GS1 AI 01 - Global Trade Item Number',
  `brand_name` varchar(128) DEFAULT NULL,
  `product_name` varchar(256) NOT NULL,
  `product_description` text,
  `category` varchar(128) DEFAULT NULL,
  `subcategory` varchar(128) DEFAULT NULL,
  `manufacturer` varchar(256) DEFAULT NULL,
  `country_of_origin` varchar(3) DEFAULT NULL COMMENT 'GS1 AI 422 - Country of Origin (ISO 3166-1 alpha-3)',
  `net_weight` varchar(64) DEFAULT NULL COMMENT 'GS1 AI 310n - Net Weight (kg)',
  `dimensions` varchar(128) DEFAULT NULL COMMENT 'Length x Width x Height',
  `ingredients` text,
  `nutritional_info` text,
  `allergen_info` text,
  `certifications` text,
  `product_images` text,
  `packaging_info` text,
  `storage_instructions` text,
  `usage_instructions` text,
  `target_url` varchar(2048) DEFAULT NULL,
  `gs1_link_id` int DEFAULT NULL,
  `gln` varchar(13) DEFAULT NULL COMMENT 'GS1 AI 413 - Global Location Number',
  `batch_lot_number` varchar(20) DEFAULT NULL COMMENT 'GS1 AI 10 - Batch/Lot Number',
  `serial_number` varchar(20) DEFAULT NULL COMMENT 'GS1 AI 21 - Serial Number',
  `production_date` date DEFAULT NULL COMMENT 'GS1 AI 11 - Production Date (YYMMDD)',
  `best_before_date` date DEFAULT NULL COMMENT 'GS1 AI 15 - Best Before Date (YYMMDD)',
  `use_by_date` date DEFAULT NULL COMMENT 'GS1 AI 17 - Use By Date (YYMMDD)',
  `sell_by_date` date DEFAULT NULL COMMENT 'GS1 AI 16 - Sell By Date (YYMMDD)',
  `pack_date` date DEFAULT NULL COMMENT 'GS1 AI 13 - Pack Date (YYMMDD)',
  `due_date` date DEFAULT NULL COMMENT 'GS1 AI 12 - Due Date (YYMMDD)',
  `variant_number` varchar(20) DEFAULT NULL COMMENT 'GS1 AI 20 - Variant Number',
  `route_code` varchar(30) DEFAULT NULL COMMENT 'GS1 AI 403 - Route Code',
  `ship_to_gln` varchar(13) DEFAULT NULL COMMENT 'GS1 AI 410 - Ship To Location GLN',
  `bill_to_gln` varchar(13) DEFAULT NULL COMMENT 'GS1 AI 411 - Bill To Location GLN',
  `purchase_from_gln` varchar(13) DEFAULT NULL COMMENT 'GS1 AI 412 - Purchase From Location GLN',
  `gross_weight` varchar(20) DEFAULT NULL COMMENT 'GS1 AI 330n - Gross Weight (kg)',
  `net_volume_liters` varchar(20) DEFAULT NULL COMMENT 'GS1 AI 315n - Net Volume (L)',
  `net_volume_cubic` varchar(20) DEFAULT NULL COMMENT 'GS1 AI 316n - Net Volume (m³)',
  `area_square_meters` varchar(20) DEFAULT NULL COMMENT 'GS1 AI 314n - Area (m²)',
  `length_meters` varchar(20) DEFAULT NULL COMMENT 'GS1 AI 311n - Length (m)',
  `width_meters` varchar(20) DEFAULT NULL COMMENT 'GS1 AI 312n - Width (m)',
  `height_meters` varchar(20) DEFAULT NULL COMMENT 'GS1 AI 313n - Height (m)',
  `processing_country` varchar(3) DEFAULT NULL COMMENT 'GS1 AI 423 - Country of Processing (ISO 3166-1 alpha-3)',
  `disassembly_country` varchar(3) DEFAULT NULL COMMENT 'GS1 AI 424 - Country of Disassembly (ISO 3166-1 alpha-3)',
  `full_process_country` varchar(3) DEFAULT NULL COMMENT 'GS1 AI 425 - Country of Full Process Chain (ISO 3166-1 alpha-3)',
  `process_covering_country` varchar(3) DEFAULT NULL COMMENT 'GS1 AI 426 - Country Covering Process (ISO 3166-1 alpha-3)',
  `customer_part_number` varchar(30) DEFAULT NULL COMMENT 'GS1 AI 241 - Customer Part Number',
  `made_to_order_variation` varchar(30) DEFAULT NULL COMMENT 'GS1 AI 242 - Made-to-Order Variation Number',
  `packaging_configuration` varchar(30) DEFAULT NULL COMMENT 'GS1 AI 243 - Packaging Configuration',
  `secondary_serial` varchar(30) DEFAULT NULL COMMENT 'GS1 AI 250 - Secondary Serial Number',
  `reference_source_entity` varchar(30) DEFAULT NULL COMMENT 'GS1 AI 251 - Reference to Source Entity',
  `gdti` varchar(30) DEFAULT NULL COMMENT 'GS1 AI 253 - Global Document Type Identifier',
  `settings` text,
  `is_enabled` tinyint NOT NULL DEFAULT '1',
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `gtin_user` (`gtin`, `user_id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`),
  KEY `gtin` (`gtin`),
  KEY `gs1_link_id` (`gs1_link_id`),
  KEY `brand_name` (`brand_name`),
  KEY `category` (`category`),
  KEY `idx_products_gln` (`gln`),
  KEY `idx_products_batch_lot` (`batch_lot_number`),
  KEY `idx_products_serial` (`serial_number`),
  KEY `idx_products_production_date` (`production_date`),
  KEY `idx_products_best_before` (`best_before_date`),
  KEY `idx_products_variant` (`variant_number`),
  KEY `idx_products_ship_to_gln` (`ship_to_gln`),
  KEY `idx_products_bill_to_gln` (`bill_to_gln`),
  KEY `idx_products_processing_country` (`processing_country`),
  CONSTRAINT `products_users_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_projects_fk` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_gs1_links_fk` FOREIGN KEY (`gs1_link_id`) REFERENCES `gs1_links` (`gs1_link_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `receipt_analysis_queue` (
  `queue_id` int NOT NULL AUTO_INCREMENT,
  `form_submission_id` int NOT NULL,
  `image_path` varchar(512) NOT NULL,
  `ai_providers` JSON NOT NULL COMMENT 'Array of AI providers to use for analysis',
  `status` ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
  `attempts` int DEFAULT 0 COMMENT 'Number of processing attempts',
  `analysis_results` longtext NULL COMMENT 'JSON object with results from each provider',
  `error_message` text NULL COMMENT 'Error message if processing failed',
  `created_at` datetime NOT NULL,
  `processed_at` datetime NULL,
  `priority` int DEFAULT 0 COMMENT 'Processing priority (higher = more urgent)',
  PRIMARY KEY (`queue_id`),
  KEY `form_submission_id` (`form_submission_id`),
  KEY `status` (`status`),
  KEY `priority_created` (`priority` DESC, `created_at` ASC),
  CONSTRAINT `receipt_analysis_queue_ibfk_1` FOREIGN KEY (`form_submission_id`) REFERENCES `form_submissions` (`form_submission_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Queue for background processing of receipt AI analysis';

-- SEPARATOR --

CREATE TABLE `receipt_analysis_providers` (
  `provider_id` int NOT NULL AUTO_INCREMENT,
  `provider_name` varchar(50) NOT NULL COMMENT 'Provider name (openai, google, anthropic)',
  `api_key` varchar(512) NULL COMMENT 'Encrypted API key',
  `api_endpoint` varchar(255) NULL COMMENT 'Custom API endpoint if needed',
  `is_enabled` tinyint(1) DEFAULT 1 COMMENT 'Whether this provider is active',
  `priority` int DEFAULT 0 COMMENT 'Provider priority (higher = preferred)',
  `rate_limit_per_minute` int DEFAULT 60 COMMENT 'API rate limit per minute',
  `cost_per_request` decimal(10,6) DEFAULT 0.000000 COMMENT 'Cost per API request in USD',
  `settings` JSON NULL COMMENT 'Provider-specific settings',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`provider_id`),
  UNIQUE KEY `provider_name` (`provider_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AI provider configuration for receipt analysis';

-- SEPARATOR --

INSERT INTO `receipt_analysis_providers` (`provider_name`, `is_enabled`, `priority`, `rate_limit_per_minute`, `cost_per_request`, `settings`, `created_at`, `updated_at`) VALUES
('openai', 1, 3, 60, 0.002000, JSON_OBJECT('model', 'gpt-4-vision-preview', 'max_tokens', 4096), NOW(), NOW()),
('google', 1, 2, 60, 0.001500, JSON_OBJECT('model', 'gemini-pro-vision', 'safety_settings', 'BLOCK_NONE'), NOW(), NOW()),
('anthropic', 1, 1, 60, 0.003000, JSON_OBJECT('model', 'claude-3-sonnet-20240229', 'max_tokens', 4096), NOW(), NOW());

-- SEPARATOR --

CREATE TABLE `receipt_analysis_logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `queue_id` int NOT NULL,
  `provider_name` varchar(50) NOT NULL,
  `status` ENUM('started', 'completed', 'failed') NOT NULL,
  `processing_time` decimal(8,3) NULL COMMENT 'Processing time in seconds',
  `tokens_used` int NULL COMMENT 'Number of tokens consumed',
  `cost` decimal(10,6) NULL COMMENT 'Cost of this request in USD',
  `confidence_score` decimal(3,2) NULL COMMENT 'AI confidence score (0.00-1.00)',
  `error_message` text NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `queue_id` (`queue_id`),
  KEY `provider_name` (`provider_name`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `receipt_analysis_logs_ibfk_1` FOREIGN KEY (`queue_id`) REFERENCES `receipt_analysis_queue` (`queue_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Logs for receipt analysis processing';

-- SEPARATOR --

CREATE OR REPLACE VIEW `receipt_analysis_summary` AS
SELECT 
    fs.`form_submission_id`,
    fs.`microsite_block_id`,
    fs.`link_id`,
    fs.`form_type`,
    fs.`ai_analysis_status`,
    fs.`ai_providers_used`,
    fs.`submitted_at`,
    JSON_LENGTH(fs.`receipt_images`) as `image_count`,
    raq.`queue_id`,
    raq.`status` as `queue_status`,
    raq.`attempts`,
    raq.`created_at` as `queued_at`,
    raq.`processed_at`,
    COUNT(ral.`log_id`) as `processing_logs_count`
FROM `form_submissions` fs
LEFT JOIN `receipt_analysis_queue` raq ON fs.`form_submission_id` = raq.`form_submission_id`
LEFT JOIN `receipt_analysis_logs` ral ON raq.`queue_id` = ral.`queue_id`
WHERE fs.`receipt_images` IS NOT NULL
GROUP BY fs.`form_submission_id`, raq.`queue_id`;

-- SEPARATOR --

CREATE TABLE `templates_categories` (
  `template_category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `settings` text,
  `icon` varchar(64) DEFAULT 'fas fa-folder',
  `emoji` varchar(8) DEFAULT '📁',
  `color` varchar(16) DEFAULT '#000000',
  `background` varchar(16) DEFAULT '#ffffff',
  `order` int DEFAULT 0,
  `is_enabled` tinyint(1) DEFAULT 1,
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`template_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AIX template categories';

-- SEPARATOR --

INSERT INTO `templates_categories` (`name`, `icon`, `emoji`, `color`, `background`, `order`, `is_enabled`, `datetime`) VALUES
('General', 'fas fa-star', '⭐', '#ffffff', '#3b82f6', 1, 1, NOW()),
('Business', 'fas fa-briefcase', '💼', '#ffffff', '#10b981', 2, 1, NOW()),
('Creative', 'fas fa-palette', '🎨', '#ffffff', '#f59e0b', 3, 1, NOW()),
('Technical', 'fas fa-code', '💻', '#ffffff', '#8b5cf6', 4, 1, NOW()),
('Marketing', 'fas fa-bullhorn', '📢', '#ffffff', '#ef4444', 5, 1, NOW());

-- SEPARATOR --

CREATE TABLE `templates` (
  `template_id` int NOT NULL AUTO_INCREMENT,
  `template_category_id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `prompt` text NOT NULL,
  `settings` text,
  `icon` varchar(64) DEFAULT 'fas fa-file-alt',
  `order` int DEFAULT 0,
  `total_usage` bigint unsigned DEFAULT 0,
  `is_enabled` tinyint(1) DEFAULT 1,
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`template_id`),
  KEY `template_category_id` (`template_category_id`),
  KEY `total_usage` (`total_usage`),
  CONSTRAINT `templates_ibfk_1` FOREIGN KEY (`template_category_id`) REFERENCES `templates_categories` (`template_category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AIX document templates';

-- SEPARATOR --

INSERT INTO `templates` (`template_category_id`, `name`, `prompt`, `icon`, `order`, `is_enabled`, `datetime`) VALUES
(1, 'Blog Post', 'Write a comprehensive blog post about [TOPIC]. Include an engaging introduction, main points with supporting details, and a compelling conclusion. Make it SEO-friendly and engaging for readers.', 'fas fa-blog', 1, 1, NOW()),
(1, 'Email Template', 'Create a professional email template for [PURPOSE]. Include a clear subject line, greeting, main message, call-to-action, and professional closing.', 'fas fa-envelope', 2, 1, NOW()),
(2, 'Business Plan', 'Create a comprehensive business plan for [BUSINESS_TYPE]. Include executive summary, market analysis, marketing strategy, financial projections, and implementation timeline.', 'fas fa-chart-line', 1, 1, NOW()),
(2, 'Meeting Minutes', 'Generate professional meeting minutes template including date, attendees, agenda items, key decisions, action items, and next steps.', 'fas fa-clipboard-list', 2, 1, NOW()),
(3, 'Creative Story', 'Write an engaging creative story about [THEME/TOPIC]. Include compelling characters, interesting plot development, vivid descriptions, and a satisfying conclusion.', 'fas fa-feather-alt', 1, 1, NOW()),
(3, 'Social Media Content', 'Create engaging social media content for [PLATFORM] about [TOPIC]. Include hashtags, call-to-action, and platform-specific formatting.', 'fas fa-share-alt', 2, 1, NOW());

-- SEPARATOR --

CREATE TABLE `documents` (
  `document_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `template_id` int DEFAULT NULL,
  `template_category_id` int DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(32) DEFAULT 'custom',
  `content` longtext,
  `words` int DEFAULT 0,
  `model` varchar(64) DEFAULT 'gpt-4',
  `api_response_time` decimal(8,3) DEFAULT NULL,
  `settings` text,
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`document_id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`),
  KEY `template_id` (`template_id`),
  KEY `template_category_id` (`template_category_id`),
  KEY `datetime` (`datetime`),
  CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `documents_ibfk_3` FOREIGN KEY (`template_id`) REFERENCES `templates` (`template_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `documents_ibfk_4` FOREIGN KEY (`template_category_id`) REFERENCES `templates_categories` (`template_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AIX generated documents';

-- SEPARATOR --

CREATE TABLE `images` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `input` text NOT NULL,
  `image` varchar(64) DEFAULT NULL,
  `style` varchar(64) DEFAULT NULL,
  `artist` varchar(64) DEFAULT NULL,
  `lighting` varchar(64) DEFAULT NULL,
  `mood` varchar(64) DEFAULT NULL,
  `size` varchar(16) DEFAULT '1024x1024',
  `settings` text,
  `api` varchar(32) DEFAULT 'dall-e-2',
  `api_response_time` decimal(8,3) DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`image_id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`),
  KEY `datetime` (`datetime`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `images_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AIX generated images';

-- SEPARATOR --

CREATE TABLE `chats_assistants` (
  `chat_assistant_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `prompt` text NOT NULL,
  `settings` text,
  `image` varchar(64) DEFAULT NULL,
  `order` int DEFAULT 0,
  `total_usage` bigint unsigned DEFAULT 0,
  `is_enabled` tinyint(1) DEFAULT 1,
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`chat_assistant_id`),
  KEY `total_usage` (`total_usage`),
  KEY `order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AIX chat assistants';

-- SEPARATOR --

INSERT INTO `chats_assistants` (`name`, `prompt`, `order`, `is_enabled`, `datetime`) VALUES
('General Assistant', 'You are a helpful AI assistant. Provide accurate, helpful, and friendly responses to user questions and requests.', 1, 1, NOW()),
('Business Advisor', 'You are a business advisor AI. Help users with business strategy, planning, marketing, and professional advice.', 2, 1, NOW()),
('Creative Writer', 'You are a creative writing assistant. Help users with storytelling, creative content, and writing improvement.', 3, 1, NOW()),
('Technical Expert', 'You are a technical expert AI. Assist users with programming, technology questions, and technical problem-solving.', 4, 1, NOW());

-- SEPARATOR --

CREATE TABLE `chats` (
  `chat_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `chat_assistant_id` int DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `total_messages` int DEFAULT 0,
  `used_tokens` int DEFAULT 0,
  `settings` text,
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`chat_id`),
  KEY `user_id` (`user_id`),
  KEY `chat_assistant_id` (`chat_assistant_id`),
  KEY `datetime` (`datetime`),
  CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`chat_assistant_id`) REFERENCES `chats_assistants` (`chat_assistant_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AIX chat conversations';

-- SEPARATOR --

INSERT INTO `products` (`user_id`, `project_id`, `gtin`, `brand_name`, `product_name`, `product_description`, `category`, `subcategory`, `manufacturer`, `country_of_origin`, `net_weight`, `dimensions`, `ingredients`, `nutritional_info`, `allergen_info`, `certifications`, `product_images`, `packaging_info`, `storage_instructions`, `usage_instructions`, `target_url`, `batch_lot_number`, `serial_number`, `production_date`, `best_before_date`, `use_by_date`, `sell_by_date`, `pack_date`, `variant_number`, `settings`, `is_enabled`, `datetime`) VALUES 
(1, NULL, '01234567890123', 'Organic Valley', 'Premium Organic Whole Milk', 'Fresh organic whole milk from grass-fed cows, rich in natural nutrients and free from artificial additives. Perfect for drinking, cooking, and baking.', 'Food & Beverages', 'Dairy Products', 'Organic Valley Cooperative', 'USA', '1.0', '10.2 x 6.4 x 25.4 cm', 'Organic Grade A Milk, Vitamin D3', 'Per 240ml: Calories 150, Total Fat 8g, Saturated Fat 5g, Cholesterol 35mg, Sodium 125mg, Total Carbs 12g, Sugars 12g, Protein 8g, Calcium 280mg, Vitamin D 2.5mcg', 'Contains: Milk. May contain traces of soy.', 'USDA Organic, Non-GMO Project Verified, Certified Humane', '["organic_milk_front.jpg", "organic_milk_nutrition.jpg"]', 'Recyclable paperboard carton with plastic cap', 'Keep refrigerated at 4°C or below. Do not freeze.', 'Shake well before use. Best served chilled.', 'https://organicvalley.coop/products/milk/whole-milk/', 'LOT2024001', 'MK240001', '2024-01-15', '2024-02-15', '2024-02-12', '2024-02-10', '2024-01-15', 'WM001', '{"organic": true, "grass_fed": true, "local_sourcing": true}', 1, NOW()),

(1, NULL, '02345678901234', 'Apple', 'iPhone 15 Pro', 'The most advanced iPhone yet, featuring the powerful A17 Pro chip, titanium design, and professional camera system with 5x telephoto zoom.', 'Electronics', 'Smartphones', 'Apple Inc.', 'CHN', '187g', '14.67 x 7.08 x 0.83 cm', 'Titanium, Glass, Aluminum, Rare Earth Elements', 'Display: 6.1-inch Super Retina XDR, Chip: A17 Pro, Storage: 128GB, Camera: 48MP Main, 12MP Ultra Wide, 12MP Telephoto, Battery: Up to 23 hours video playback', 'Contains small parts. Not suitable for children under 3 years.', 'CE Marking, FCC Approved, Energy Star Certified', '["iphone15pro_titanium.jpg", "iphone15pro_camera.jpg", "iphone15pro_box.jpg"]', 'Premium recyclable packaging with minimal plastic', 'Store in dry place at room temperature. Avoid extreme temperatures.', 'Charge before first use. See user manual for complete setup instructions.', 'https://apple.com/iphone-15-pro/', 'APL240156', 'IP15P240001', '2024-03-10', NULL, NULL, NULL, '2024-03-10', 'TI128', '{"warranty_months": 12, "color": "Natural Titanium", "storage": "128GB", "carrier": "Unlocked"}', 1, NOW()),

(1, NULL, '03456789012345', 'L\'Oréal Paris', 'Revitalift Anti-Aging Day Cream', 'Advanced anti-aging moisturizer with Pro-Retinol and Centella Asiatica to reduce wrinkles and firm skin. Suitable for all skin types.', 'Beauty & Personal Care', 'Skincare', 'L\'Oréal S.A.', 'FRA', '50ml', '7.5 x 7.5 x 5.2 cm', 'Aqua/Water, Glycerin, Dimethicone, Isohexadecane, Alcohol Denat., Isopropyl Isostearate, PEG-10 Dimethicone, Retinyl Palmitate, Centella Asiatica Extract, Adenosine, Ammonium Polyacryloyldimethyl Taurate, Caprylyl Glycol, Carbomer, Disodium EDTA, Hydroxyethylcellulose, Phenoxyethanol, Triethanolamine, Parfum/Fragrance', 'Active Ingredients: Pro-Retinol (Retinyl Palmitate) 0.1%, Centella Asiatica Extract 0.05%', 'For external use only. Avoid contact with eyes. Discontinue use if irritation occurs. Patch test recommended.', 'Dermatologically Tested, Hypoallergenic, Non-Comedogenic', '["revitalift_jar.jpg", "revitalift_texture.jpg", "revitalift_ingredients.jpg"]', 'Glass jar with aluminum cap in recyclable cardboard box', 'Store in cool, dry place away from direct sunlight. Use within 12 months of opening.', 'Apply to clean face and neck morning and evening. Use sunscreen during the day.', 'https://loreal-paris.com/revitalift-anti-aging-cream', 'LOR240089', 'RA240001', '2024-02-20', '2027-02-20', NULL, NULL, '2024-02-20', 'DAY50', '{"spf": false, "skin_type": "all", "age_group": "35+", "fragrance": true}', 1, NOW()),

(1, NULL, '04567890123456', 'Johnson & Johnson', 'Tylenol Extra Strength', 'Fast-acting pain reliever and fever reducer. Each caplet contains 500mg of acetaminophen for effective relief of headaches, muscle aches, and fever.', 'Health & Wellness', 'Over-the-Counter Medicine', 'Johnson & Johnson Consumer Inc.', 'USA', '100 caplets', '11.4 x 6.4 x 4.1 cm', 'Active Ingredient: Acetaminophen 500mg per caplet. Inactive Ingredients: Croscarmellose Sodium, Magnesium Stearate, Microcrystalline Cellulose, Povidone, Pregelatinized Starch, Sodium Starch Glycolate, Stearic Acid', 'Each caplet contains 500mg acetaminophen. Maximum daily dose: 3000mg (6 caplets) in 24 hours for adults.', 'Keep out of reach of children. Do not exceed recommended dose. Consult doctor if pregnant or breastfeeding.', 'FDA Approved, USP Verified, Good Manufacturing Practice (GMP)', '["tylenol_bottle.jpg", "tylenol_caplets.jpg", "tylenol_label.jpg"]', 'Child-resistant bottle with tamper-evident seal', 'Store at room temperature 20-25°C. Protect from moisture and light.', 'Adults: Take 2 caplets every 6 hours as needed. Do not exceed 6 caplets in 24 hours.', 'https://tylenol.com/products/tylenol-extra-strength', 'TYL240067', 'ES240001', '2024-01-08', '2027-01-08', NULL, NULL, '2024-01-08', 'ES500', '{"drug_class": "analgesic", "prescription_required": false, "age_restriction": "12+"}', 1, NOW()),

(1, NULL, '05678901234567', 'Levi\'s', '501 Original Fit Jeans', 'The original blue jean since 1873. Classic straight fit with button fly, made from 100% cotton denim. A timeless wardrobe essential.', 'Apparel & Accessories', 'Jeans', 'Levi Strauss & Co.', 'MEX', '650g', 'W32 x L34 inches', '100% Cotton Denim (14oz)', 'Fabric Weight: 14oz, Fit: Straight, Rise: Mid, Leg Opening: 16.5 inches', 'May contain traces of nickel in metal components. Check care label for washing instructions.', 'OEKO-TEX Standard 100, Better Cotton Initiative (BCI)', '["levis501_front.jpg", "levis501_back.jpg", "levis501_detail.jpg"]', 'Recyclable hang tag and packaging made from sustainable materials', 'Store in cool, dry place. Avoid prolonged exposure to direct sunlight.', 'Machine wash cold with like colors. Tumble dry medium. Iron if needed.', 'https://levi.com/products/501-original-fit-jeans', 'LEV240123', 'LJ240001', '2024-03-05', NULL, NULL, NULL, '2024-03-05', 'W32L34', '{"size": "32x34", "color": "Medium Stonewash", "fit": "Original", "material": "100% Cotton"}', 1, NOW());

-- SEPARATOR --
