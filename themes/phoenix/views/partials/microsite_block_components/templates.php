<?php defined('SEEGAP') || die() ?>

<?php
/**
 * Intelligent Templates Component for Microsite Blocks
 * Provides smart template presets that automatically configure background, border, shadow, animation, and shape settings
 * 
 * @param string $block_id - Unique identifier for the block
 * @param object $settings - Current block settings
 * @param string $block_type - Type of block (avatar, image, etc.) for context-specific templates
 */

$block_id = $block_id ?? 'default';
$settings = $settings ?? (object)[];
$block_type = $block_type ?? 'avatar';

// Define block-specific template presets
if ($block_type === 'socials') {
    // Social Icons specific templates
    $template_presets = [
        'modern_minimal' => [
            'name' => 'Modern Minimal',
            'description' => 'Clean, minimal design perfect for professional profiles',
            'preview_icon' => 'fab fa-twitter',
            'settings' => [
                'template' => 'modern_minimal',
                'size' => 28,
                'color' => '#333333',
                'background_color' => '#FFFFFF00',
                'border_radius' => 'rounded',
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => '#000000',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 0,
                'shadow_blur' => 0,
                'shadow_spread' => 0,
                'shadow_color' => '#000000',
                'shadow_inset' => false
            ]
        ],
        'brand_colors' => [
            'name' => 'Brand Colors',
            'description' => 'Icons with authentic brand colors and subtle backgrounds',
            'preview_icon' => 'fab fa-facebook',
            'settings' => [
                'template' => 'brand_colors',
                'size' => 32,
                'color' => '#ffffff',
                'background_color' => '#1877f2',
                'border_radius' => 'rounded',
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => '#000000',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 2,
                'shadow_blur' => 4,
                'shadow_spread' => 0,
                'shadow_color' => '#00000020',
                'shadow_inset' => false
            ]
        ],
        'circular_filled' => [
            'name' => 'Circular Filled',
            'description' => 'Filled circular backgrounds with white icons',
            'preview_icon' => 'fab fa-instagram',
            'settings' => [
                'template' => 'circular_filled',
                'size' => 24,
                'color' => '#ffffff',
                'background_color' => '#6c757d',
                'border_radius' => 'round',
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => '#000000',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 2,
                'shadow_blur' => 6,
                'shadow_spread' => 0,
                'shadow_color' => '#00000015',
                'shadow_inset' => false
            ]
        ],
        'outlined_hover' => [
            'name' => 'Outlined Hover',
            'description' => 'Clean outlines that fill on hover',
            'preview_icon' => 'fab fa-linkedin',
            'settings' => [
                'template' => 'outlined_hover',
                'size' => 26,
                'color' => '#0077b5',
                'background_color' => '#FFFFFF00',
                'border_radius' => 'rounded',
                'border_width' => 2,
                'border_style' => 'solid',
                'border_color' => '#0077b5',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 0,
                'shadow_blur' => 0,
                'shadow_spread' => 0,
                'shadow_color' => '#000000',
                'shadow_inset' => false
            ]
        ],
        'gradient_modern' => [
            'name' => 'Gradient Modern',
            'description' => 'Modern gradient backgrounds with subtle shadows',
            'preview_icon' => 'fab fa-youtube',
            'settings' => [
                'template' => 'gradient_modern',
                'size' => 30,
                'color' => '#ffffff',
                'background_color' => '#ff0000',
                'border_radius' => 'rounded',
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => '#000000',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 4,
                'shadow_blur' => 12,
                'shadow_spread' => 0,
                'shadow_color' => '#ff000030',
                'shadow_inset' => false
            ]
        ],
        'neon_glow' => [
            'name' => 'Neon Glow',
            'description' => 'Futuristic glowing effect perfect for dark themes',
            'preview_icon' => 'fab fa-discord',
            'settings' => [
                'template' => 'neon_glow',
                'size' => 28,
                'color' => '#7289da',
                'background_color' => '#FFFFFF00',
                'border_radius' => 'rounded',
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => '#000000',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 0,
                'shadow_blur' => 15,
                'shadow_spread' => 0,
                'shadow_color' => '#7289da60',
                'shadow_inset' => false
            ]
        ],
        'glass_morphism' => [
            'name' => 'Glass Morphism',
            'description' => 'Trendy glass effect with backdrop blur',
            'preview_icon' => 'fab fa-tiktok',
            'settings' => [
                'template' => 'glass_morphism',
                'size' => 26,
                'color' => '#000000',
                'background_color' => '#ffffff40',
                'border_radius' => 'rounded',
                'border_width' => 1,
                'border_style' => 'solid',
                'border_color' => '#ffffff60',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 8,
                'shadow_blur' => 32,
                'shadow_spread' => 0,
                'shadow_color' => '#00000010',
                'shadow_inset' => false
            ]
        ],
        'retro_square' => [
            'name' => 'Retro Square',
            'description' => 'Vintage square design with bold colors',
            'preview_icon' => 'fab fa-pinterest',
            'settings' => [
                'template' => 'retro_square',
                'size' => 24,
                'color' => '#ffffff',
                'background_color' => '#bd081c',
                'border_radius' => 'straight',
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => '#000000',
                'shadow_offset_x' => 3,
                'shadow_offset_y' => 3,
                'shadow_blur' => 0,
                'shadow_spread' => 0,
                'shadow_color' => '#00000040',
                'shadow_inset' => false
            ]
        ],
        'soft_pastel' => [
            'name' => 'Soft Pastel',
            'description' => 'Gentle pastel colors with soft shadows',
            'preview_icon' => 'fab fa-snapchat',
            'settings' => [
                'template' => 'soft_pastel',
                'size' => 26,
                'color' => '#333333',
                'background_color' => '#fff3cd',
                'border_radius' => 'rounded',
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => '#000000',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 2,
                'shadow_blur' => 8,
                'shadow_spread' => 0,
                'shadow_color' => '#00000008',
                'shadow_inset' => false
            ]
        ],
        'monochrome_bold' => [
            'name' => 'Monochrome Bold',
            'description' => 'High contrast black and white design',
            'preview_icon' => 'fab fa-github',
            'settings' => [
                'template' => 'monochrome_bold',
                'size' => 30,
                'color' => '#ffffff',
                'background_color' => '#000000',
                'border_radius' => 'round',
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => '#000000',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 0,
                'shadow_blur' => 0,
                'shadow_spread' => 0,
                'shadow_color' => '#000000',
                'shadow_inset' => false
            ]
        ],
        'floating_cards' => [
            'name' => 'Floating Cards',
            'description' => 'Elevated card design with deep shadows',
            'preview_icon' => 'fab fa-whatsapp',
            'settings' => [
                'template' => 'floating_cards',
                'size' => 28,
                'color' => '#25d366',
                'background_color' => '#ffffff',
                'border_radius' => 'rounded',
                'border_width' => 0,
                'border_style' => 'solid',
                'border_color' => '#000000',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 6,
                'shadow_blur' => 20,
                'shadow_spread' => 0,
                'shadow_color' => '#00000015',
                'shadow_inset' => false
            ]
        ],
        'cyberpunk' => [
            'name' => 'Cyberpunk',
            'description' => 'Futuristic design with electric colors',
            'preview_icon' => 'fab fa-twitch',
            'settings' => [
                'template' => 'cyberpunk',
                'size' => 26,
                'color' => '#00ff88',
                'background_color' => '#0f0f23',
                'border_radius' => 'straight',
                'border_width' => 1,
                'border_style' => 'solid',
                'border_color' => '#00ff88',
                'shadow_offset_x' => 0,
                'shadow_offset_y' => 0,
                'shadow_blur' => 10,
                'shadow_spread' => 0,
                'shadow_color' => '#00ff8860',
                'shadow_inset' => false
            ]
        ]
    ];
} else {
    // Default avatar/image templates
    $template_presets = [
    'classic_circle' => [
        'name' => 'Classic Circle',
        'description' => 'Clean circular design with subtle shadow',
        'preview_shape' => 'circle',
        'settings' => [
            'template' => 'classic',
            'avatar_shape' => 'circle',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 0,
            'border_color' => '#ffffff',
            'border_radius' => 'rounded',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 2,
            'border_shadow_blur' => 4,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000010',
            'animation' => 'false',
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0
        ]
    ],
    'classic_square' => [
        'name' => 'Classic Square',
        'description' => 'Clean square design with rounded corners',
        'preview_shape' => 'square',
        'settings' => [
            'template' => 'classic',
            'avatar_shape' => 'square',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 0,
            'border_color' => '#ffffff',
            'border_radius' => 'rounded',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 2,
            'border_shadow_blur' => 4,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000010',
            'animation' => 'false',
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0
        ]
    ],
    'gradient_ring_circle' => [
        'name' => 'Gradient Ring Circle',
        'description' => 'Animated gradient border with circular shape',
        'preview_shape' => 'circle',
        'settings' => [
            'template' => 'gradient_ring',
            'avatar_shape' => 'circle',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 3,
            'border_color' => '#ff6b6b',
            'border_radius' => 'round',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000000',
            'animation' => 'pulse',
            'animation_runs' => 'infinite',
            'animation_delay' => 0
        ]
    ],
    'gradient_ring_square' => [
        'name' => 'Gradient Ring Square',
        'description' => 'Animated gradient border with square shape',
        'preview_shape' => 'square',
        'settings' => [
            'template' => 'gradient_ring',
            'avatar_shape' => 'square',
            'square_border_radius' => 12,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 3,
            'border_color' => '#4ecdc4',
            'border_radius' => 'rounded',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000000',
            'animation' => 'pulse',
            'animation_runs' => 'infinite',
            'animation_delay' => 0
        ]
    ],
    'professional_circle' => [
        'name' => 'Professional Circle',
        'description' => 'Business-ready with elegant shadow',
        'preview_shape' => 'circle',
        'settings' => [
            'template' => 'professional',
            'avatar_shape' => 'circle',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 2,
            'border_color' => '#ffffff',
            'border_radius' => 'round',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 4,
            'border_shadow_blur' => 8,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000015',
            'animation' => 'false',
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0
        ]
    ],
    'professional_square' => [
        'name' => 'Professional Square',
        'description' => 'Business-ready square with clean borders',
        'preview_shape' => 'square',
        'settings' => [
            'template' => 'professional',
            'avatar_shape' => 'square',
            'square_border_radius' => 6,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 1,
            'border_color' => '#dee2e6',
            'border_radius' => 'rounded',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 4,
            'border_shadow_blur' => 8,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000015',
            'animation' => 'false',
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0
        ]
    ],
    'creative_circle' => [
        'name' => 'Creative Circle',
        'description' => 'Playful design with bounce animation',
        'preview_shape' => 'circle',
        'settings' => [
            'template' => 'creative',
            'avatar_shape' => 'circle',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 2,
            'border_color' => '#ff9a9e',
            'border_radius' => 'round',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 3,
            'border_shadow_color' => '#ff9a9e40',
            'animation' => 'bounce',
            'animation_runs' => 'repeat-2',
            'animation_delay' => 500
        ]
    ],
    'creative_square' => [
        'name' => 'Creative Square',
        'description' => 'Playful square with colorful styling',
        'preview_shape' => 'square',
        'settings' => [
            'template' => 'creative',
            'avatar_shape' => 'square',
            'square_border_radius' => 16,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 2,
            'border_color' => '#fecfef',
            'border_radius' => 'rounded',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 3,
            'border_shadow_color' => '#fecfef40',
            'animation' => 'wobble',
            'animation_runs' => 'repeat-1',
            'animation_delay' => 1000
        ]
    ],
    'minimalist_circle' => [
        'name' => 'Minimalist Circle',
        'description' => 'Clean and simple circular design',
        'preview_shape' => 'circle',
        'settings' => [
            'template' => 'minimalist',
            'avatar_shape' => 'circle',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 1,
            'border_color' => '#dee2e6',
            'border_radius' => 'round',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000000',
            'animation' => 'false',
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0
        ]
    ],
    'minimalist_square' => [
        'name' => 'Minimalist Square',
        'description' => 'Clean and simple square design',
        'preview_shape' => 'square',
        'settings' => [
            'template' => 'minimalist',
            'avatar_shape' => 'square',
            'square_border_radius' => 4,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 1,
            'border_color' => '#dee2e6',
            'border_radius' => 'straight',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000000',
            'animation' => 'false',
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0
        ]
    ],
    'neon_glow_circle' => [
        'name' => 'Neon Glow Circle',
        'description' => 'Futuristic glowing circular effect',
        'preview_shape' => 'circle',
        'settings' => [
            'template' => 'neon_glow',
            'avatar_shape' => 'circle',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 0,
            'border_color' => '#ffffff',
            'border_radius' => 'round',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 20,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#667eea60',
            'animation' => 'pulse',
            'animation_runs' => 'infinite',
            'animation_delay' => 0
        ]
    ],
    'neon_glow_square' => [
        'name' => 'Neon Glow Square',
        'description' => 'Futuristic glowing square effect',
        'preview_shape' => 'square',
        'settings' => [
            'template' => 'neon_glow',
            'avatar_shape' => 'square',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 0,
            'border_color' => '#ffffff',
            'border_radius' => 'rounded',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 20,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#764ba260',
            'animation' => 'flash',
            'animation_runs' => 'infinite',
            'animation_delay' => 0
        ]
    ],
    'double_ring_circle' => [
        'name' => 'Double Ring Circle',
        'description' => 'Elegant double border circular design',
        'preview_shape' => 'circle',
        'settings' => [
            'template' => 'double_ring',
            'avatar_shape' => 'circle',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 2,
            'border_color' => '#007bff',
            'border_radius' => 'round',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 4,
            'border_shadow_color' => '#007bff20',
            'animation' => 'false',
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0
        ]
    ],
    'double_ring_square' => [
        'name' => 'Double Ring Square',
        'description' => 'Elegant double border square design',
        'preview_shape' => 'square',
        'settings' => [
            'template' => 'double_ring',
            'avatar_shape' => 'square',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 2,
            'border_color' => '#007bff',
            'border_radius' => 'rounded',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 4,
            'border_shadow_color' => '#007bff20',
            'animation' => 'false',
            'animation_runs' => 'repeat-1',
            'animation_delay' => 0
        ]
    ],
    'status_ring_circle' => [
        'name' => 'Status Ring Circle',
        'description' => 'Active status indicator with pulsing effect',
        'preview_shape' => 'circle',
        'settings' => [
            'template' => 'status_ring',
            'avatar_shape' => 'circle',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 3,
            'border_color' => '#28a745',
            'border_radius' => 'round',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000000',
            'animation' => 'pulse',
            'animation_runs' => 'infinite',
            'animation_delay' => 0
        ]
    ],
    'status_ring_square' => [
        'name' => 'Status Ring Square',
        'description' => 'Active status square with smooth animation',
        'preview_shape' => 'square',
        'settings' => [
            'template' => 'status_ring',
            'avatar_shape' => 'square',
            'square_border_radius' => 8,
            'background_color' => '#ffffff',
            'block_background_color' => '#00000000',
            'text_alignment' => 'center',
            'border_width' => 3,
            'border_color' => '#20c997',
            'border_radius' => 'rounded',
            'border_style' => 'solid',
            'border_shadow_offset_x' => 0,
            'border_shadow_offset_y' => 0,
            'border_shadow_blur' => 0,
            'border_shadow_spread' => 0,
            'border_shadow_color' => '#00000000',
            'animation' => 'rubberBand',
            'animation_runs' => 'repeat-1',
            'animation_delay' => 500
        ]
    ]
];
}

// Get current template based on block type
if ($block_type === 'socials') {
    $current_template = $settings->template ?? 'modern_minimal';
    $current_preset = $current_template;
} else {
    $current_template = $settings->template ?? 'classic';
    $current_shape = $settings->avatar_shape ?? 'circle';
    $current_preset = $current_template . '_' . $current_shape;
}
?>

<div class="form-group">
    <?php if ($block_type === 'socials'): ?>
        <label><i class="fas fa-palette fa-fw fa-sm text-muted mr-1"></i> Social Icon Templates</label>
        <p class="text-muted small mb-3">Choose a preset optimized for social media icons that automatically configures size, colors, borders, and shadows</p>
    <?php else: ?>
        <label><i class="fas fa-palette fa-fw fa-sm text-muted mr-1"></i> Template & Shape</label>
        <p class="text-muted small mb-3">Choose a preset that automatically configures shape, borders, shadows, and animations</p>
    <?php endif ?>
    
    <div class="templates-grid">
        <?php foreach($template_presets as $preset_key => $preset): ?>
            <label class="template-preset-option">
                <input type="radio" name="template_preset" value="<?= $preset_key ?>" <?= $preset_key === $current_preset ? 'checked' : '' ?> class="d-none template-preset-input">
                <div class="template-preset-card <?= $preset_key === $current_preset ? 'active' : '' ?>" data-preset="<?= $preset_key ?>" title="<?= $preset['name'] ?> - <?= $preset['description'] ?>">
                    <?php if ($block_type === 'socials'): ?>
                        <!-- Social icon template demo -->
                        <div class="social-template-demo" 
                             data-template="<?= $preset['settings']['template'] ?>"
                             style="
                                color: <?= $preset['settings']['color'] ?>;
                                background-color: <?= $preset['settings']['background_color'] ?>;
                                font-size: <?= min(20, $preset['settings']['size']) ?>px;
                                width: 40px;
                                height: 40px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                <?= $preset['settings']['border_radius'] === 'round' ? 'border-radius: 50%;' : ($preset['settings']['border_radius'] === 'rounded' ? 'border-radius: 8px;' : 'border-radius: 0;') ?>
                                <?= $preset['settings']['border_width'] > 0 ? 'border: ' . $preset['settings']['border_width'] . 'px ' . $preset['settings']['border_style'] . ' ' . $preset['settings']['border_color'] . ';' : '' ?>
                                <?= ($preset['settings']['shadow_blur'] > 0 || $preset['settings']['shadow_spread'] > 0) ? 'box-shadow: ' . ($preset['settings']['shadow_inset'] ? 'inset ' : '') . $preset['settings']['shadow_offset_x'] . 'px ' . $preset['settings']['shadow_offset_y'] . 'px ' . $preset['settings']['shadow_blur'] . 'px ' . $preset['settings']['shadow_spread'] . 'px ' . $preset['settings']['shadow_color'] . ';' : '' ?>
                             ">
                            <i class="<?= $preset['preview_icon'] ?>"></i>
                        </div>
                    <?php else: ?>
                        <!-- Avatar/image template demo -->
                        <div class="template-demo <?= $preset['preview_shape'] ?>-demo <?= $preset['settings']['template'] ?>-style" 
                             data-animation="<?= $preset['settings']['animation'] ?>"
                             style="<?= $preset['settings']['border_width'] > 0 ? 'border: ' . $preset['settings']['border_width'] . 'px ' . $preset['settings']['border_style'] . ' ' . $preset['settings']['border_color'] . ';' : '' ?>
                                    <?= ($preset['settings']['border_shadow_blur'] > 0 || $preset['settings']['border_shadow_spread'] > 0) ? 'box-shadow: ' . $preset['settings']['border_shadow_offset_x'] . 'px ' . $preset['settings']['border_shadow_offset_y'] . 'px ' . $preset['settings']['border_shadow_blur'] . 'px ' . $preset['settings']['border_shadow_spread'] . 'px ' . $preset['settings']['border_shadow_color'] . ';' : '' ?>">
                        </div>
                    <?php endif ?>
                </div>
            </label>
        <?php endforeach ?>
    </div>
    
    <!-- Hidden inputs for all template settings -->
    <?php foreach(['template', 'avatar_shape', 'square_border_radius', 'background_color', 'block_background_color', 'text_alignment', 'border_width', 'border_color', 'border_radius', 'border_style', 'border_shadow_offset_x', 'border_shadow_offset_y', 'border_shadow_blur', 'border_shadow_spread', 'border_shadow_color', 'animation', 'animation_runs', 'animation_delay'] as $field): ?>
        <input type="hidden" name="<?= $field ?>" id="template_<?= $field ?>" value="<?= $settings->$field ?? '' ?>">
    <?php endforeach ?>
</div>

<style>
.templates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
    gap: 10px;
    margin-bottom: 20px;
}

.template-preset-option {
    cursor: pointer;
    display: block;
}

.template-preset-card {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 8px;
    text-align: center;
    background: #f8f9fa;
    height: 60px;
    width: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
}

.template-preset-card:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.15);
}

.template-preset-option input:checked + .template-preset-card,
.template-preset-card.active {
    border-color: #007bff;
    background: #e7f3ff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.template-demo {
    width: 32px;
    height: 32px;
    position: relative;
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    /* Default fallback background - should be overridden by template-specific styles */
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.circle-demo {
    border-radius: 50% !important;
}

.square-demo {
    border-radius: 8px !important;
}

/* Template-specific styling for demos - ensure all templates show their shapes with high specificity */
.template-demo.classic-style {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.template-demo.gradient_ring-style {
    background: linear-gradient(white, white) padding-box, linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1) border-box !important;
    border: 3px solid transparent !important;
}

.template-demo.professional-style {
    background: #6c757d !important;
}

.template-demo.creative-style {
    background: linear-gradient(45deg, #ff9a9e, #fecfef, #fecfef) !important;
}

.template-demo.minimalist-style {
    background: #ffffff !important;
    border: 1px solid #dee2e6 !important;
}

.template-demo.neon_glow-style {
    background: #667eea !important;
}

.template-demo.double_ring-style {
    background: #007bff !important;
}

.template-demo.status_ring-style {
    background: linear-gradient(45deg, #28a745, #20c997) !important;
}

/* Ensure all template demos are always visible regardless of other CSS */
.template-preset-card .template-demo {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    width: 32px !important;
    height: 32px !important;
}

/* Animation previews on hover only - prevent click interference */
.template-preset-card:hover:not(.active) .template-demo[data-animation="pulse"] {
    animation: demoAnimation 1s ease-in-out;
}

.template-preset-card:hover:not(.active) .template-demo[data-animation="bounce"] {
    animation: demoBounce 1s ease-in-out;
}

.template-preset-card:hover:not(.active) .template-demo[data-animation="flash"] {
    animation: demoFlash 1s ease-in-out;
}

.template-preset-card:hover:not(.active) .template-demo[data-animation="wobble"] {
    animation: demoWobble 1s ease-in-out;
}

.template-preset-card:hover:not(.active) .template-demo[data-animation="rubberBand"] {
    animation: demoRubberBand 1s ease-in-out;
}

/* Prevent animations during click/active state */
.template-preset-card.active .template-demo,
.template-preset-card:active .template-demo {
    animation: none !important;
}

@keyframes demoAnimation {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes demoBounce {
    0%, 20%, 60%, 100% { transform: translateY(0); }
    40% { transform: translateY(-5px); }
    80% { transform: translateY(-2px); }
}

@keyframes demoFlash {
    0%, 50%, 100% { opacity: 1; }
    25%, 75% { opacity: 0.7; }
}

@keyframes demoWobble {
    0% { transform: translateX(0%); }
    15% { transform: translateX(-10%) rotate(-2deg); }
    30% { transform: translateX(8%) rotate(1deg); }
    45% { transform: translateX(-6%) rotate(-1deg); }
    60% { transform: translateX(4%) rotate(1deg); }
    75% { transform: translateX(-2%) rotate(-0.5deg); }
    100% { transform: translateX(0%); }
}

@keyframes demoRubberBand {
    0% { transform: scale(1); }
    30% { transform: scaleX(1.15) scaleY(0.85); }
    40% { transform: scaleX(0.85) scaleY(1.15); }
    50% { transform: scaleX(1.05) scaleY(0.95); }
    65% { transform: scaleX(0.98) scaleY(1.02); }
    75% { transform: scaleX(1.02) scaleY(0.98); }
    100% { transform: scale(1); }
}

@media (max-width: 768px) {
    .templates-grid {
        grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
        gap: 8px;
    }
    
    .template-preset-card {
        height: 50px;
        width: 50px;
        padding: 6px;
    }
    
    .template-demo {
        width: 26px;
        height: 26px;
    }
}

@media (max-width: 576px) {
    .templates-grid {
        grid-template-columns: repeat(8, 1fr);
        gap: 6px;
    }
    
    .template-preset-card {
        height: 45px;
        width: 45px;
        padding: 4px;
    }
    
    .template-demo {
        width: 24px;
        height: 24px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const templatePresets = <?= json_encode($template_presets) ?>;
    const templateInputs = document.querySelectorAll('.template-preset-input');
    const templateCards = document.querySelectorAll('.template-preset-card');
    
    // Add click handlers to template cards
    templateCards.forEach(card => {
        card.addEventListener('click', function() {
            const presetKey = this.dataset.preset;
            const radioInput = this.parentElement.querySelector('.template-preset-input');
            
            if (radioInput && templatePresets[presetKey]) {
                // Check the radio button
                radioInput.checked = true;
                
                // Remove active class from all cards
                templateCards.forEach(c => c.classList.remove('active'));
                
                // Add active class to selected card
                this.classList.add('active');
                
                // Apply all preset settings
                const preset = templatePresets[presetKey];
                Object.keys(preset.settings).forEach(key => {
                    const value = preset.settings[key];
                    
                    // Update hidden template input
                    const hiddenInput = document.getElementById('template_' + key);
                    if (hiddenInput) {
                        hiddenInput.value = value;
                    }
                    
                    // Handle different field types based on the key
                    if (key.includes('color')) {
                        // Handle color picker fields (hidden inputs with Pickr)
                        const colorInputs = document.querySelectorAll(`input[name="${key}"][type="hidden"]`);
                        colorInputs.forEach(input => {
                            input.value = value;
                            // Trigger change event for Pickr
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        });
                        
                        // Update Pickr instances if they exist
                        if (typeof window[key + '_pickr'] !== 'undefined') {
                            window[key + '_pickr'].setColor(value);
                        }
                    } else if (key === 'border_radius' || key === 'border_style' || key === 'animation_runs') {
                        // Handle radio button groups
                        const radioOption = document.querySelector(`input[name="${key}"][value="${value}"]`);
                        if (radioOption) {
                            // Uncheck all radio buttons in this group first
                            document.querySelectorAll(`input[name="${key}"]`).forEach(radio => {
                                radio.checked = false;
                                radio.parentElement.classList.remove('active');
                            });
                            
                            // Check the correct radio button
                            radioOption.checked = true;
                            radioOption.parentElement.classList.add('active');
                            radioOption.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    } else if (key === 'border_width' || key.includes('shadow_') || key === 'animation_delay') {
                        // Handle range inputs
                        const rangeInputs = document.querySelectorAll(`input[name="${key}"][type="range"]`);
                        rangeInputs.forEach(rangeInput => {
                            rangeInput.value = value;
                            
                            // Update range counter display
                            const rangeCounter = rangeInput.parentElement.querySelector('[data-range-counter]');
                            if (rangeCounter) {
                                const suffix = rangeInput.parentElement.getAttribute('data-range-counter-suffix') || '';
                                rangeCounter.textContent = value + suffix;
                            }
                            
                            rangeInput.dispatchEvent(new Event('input', { bubbles: true }));
                        });
                    } else if (key === 'animation') {
                        // Handle animation select dropdown
                        const animationSelects = document.querySelectorAll(`select[name="${key}"]`);
                        animationSelects.forEach(select => {
                            select.value = value;
                            select.dispatchEvent(new Event('change', { bubbles: true }));
                        });
                    } else if (key === 'avatar_shape' || key === 'text_alignment') {
                        // Handle other radio button groups
                        const radioOption = document.querySelector(`input[name="${key}"][value="${value}"]`);
                        if (radioOption) {
                            // Uncheck all radio buttons in this group first
                            document.querySelectorAll(`input[name="${key}"]`).forEach(radio => {
                                radio.checked = false;
                                radio.parentElement.classList.remove('active');
                            });
                            
                            // Check the correct radio button
                            radioOption.checked = true;
                            radioOption.parentElement.classList.add('active');
                            radioOption.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    } else {
                        // Handle regular inputs
                        const regularInputs = document.querySelectorAll(`input[name="${key}"]:not([type="hidden"]):not([type="radio"]):not([type="range"]), select[name="${key}"]`);
                        regularInputs.forEach(input => {
                            input.value = value;
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        });
                    }
                });
                
                // Trigger change event for external listeners
                radioInput.dispatchEvent(new Event('change', { bubbles: true }));
                
                // Show success message
                showTemplateAppliedMessage(preset.name);
            }
        });
    });
    
    // Handle radio button changes directly
    templateInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                // Remove active class from all cards
                templateCards.forEach(card => {
                    card.classList.remove('active');
                });
                
                // Add active class to selected card
                const selectedCard = this.parentElement.querySelector('.template-preset-card');
                if (selectedCard) {
                    selectedCard.classList.add('active');
                }
            }
        });
    });
    
    let lastMessageTime = 0;
    let lastTemplateName = '';
    
    function showTemplateAppliedMessage(templateName) {
        const now = Date.now();
        
        // Prevent duplicate messages within 1 second for the same template
        if (now - lastMessageTime < 1000 && templateName === lastTemplateName) {
            return;
        }
        
        lastMessageTime = now;
        lastTemplateName = templateName;
        
        // Remove any existing messages first
        const existingMessages = document.querySelectorAll('.template-applied-message');
        existingMessages.forEach(msg => msg.remove());
        
        // Create and show a temporary success message
        const message = document.createElement('div');
        message.className = 'alert alert-success alert-dismissible fade show mt-2 template-applied-message';
        message.innerHTML = `
            <i class="fas fa-check-circle mr-2"></i>
            <strong>${templateName}</strong> template applied! All style settings have been configured.
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        // Insert after the templates grid
        const templatesGrid = document.querySelector('.templates-grid');
        if (templatesGrid) {
            templatesGrid.parentNode.insertBefore(message, templatesGrid.nextSibling);
            
            // Auto-remove after 3 seconds
            setTimeout(() => {
                if (message.parentNode) {
                    message.remove();
                }
            }, 3000);
        }
    }
});
</script>
