<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:ital,wght@1,700&family=Roboto:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="overlay" onclick="toggleMenu()"></div>
    
    <nav class="menu">
        <div class="menu-header">
            <span class="menu-title">Menü</span>
            <button class="close-btn" onclick="toggleMenu()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="menu-content">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'container' => false,
                'menu_class' => 'menu-list',
                'walker' => new Olsberg_Menu_Walker(),
                'fallback_cb' => 'olsberg_fallback_menu'
            ));
            ?>
        </div>
    </nav>

    <header class="header">
        <div class="header-nav">
            <div class="logo"><?php bloginfo('name'); ?></div>
            
            <div class="nav-items">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'header-menu',
                    'container' => false,
                    'menu_class' => 'header-nav-menu',
                    'walker' => new Olsberg_Header_Menu_Walker(),
                    'fallback_cb' => 'olsberg_header_fallback_menu'
                ));
                ?>
                
                <button class="menu-btn" onclick="toggleMenu()">
                    <span>Menü</span>
                    <div class="menu-btn-icon">
                        <svg class="burger-svg" width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect class="svg-line svg-line-1" width="18" height="2" fill="white"/>
                            <rect class="svg-line svg-line-2" y="6" width="18" height="2" fill="white"/>
                            <rect class="svg-line svg-line-3" y="12" width="18" height="2" fill="white"/>
                        </svg>
                        <div class="burger-lines">
                            <span class="line line-1"></span>
                            <span class="line line-2"></span>
                            <span class="line line-3"></span>
                        </div>
                    </div>
                </button>
            </div>
        </div>

        <?php if (has_post_thumbnail() || is_front_page()): ?>
        <?php 
        // Get hero media settings from ACF fields
        $media_type = get_field('hero_media_type') ?: 'image';
        $hero_image = get_field('hero_image');
        $hero_video = get_field('hero_video');
        $video_poster = get_field('hero_video_poster');
        
        // Fallback image if ACF fields are empty
        if (!$hero_image && $media_type === 'image') {
            $hero_image = 'https://picsum.photos/id/1018/1920/1080';
        }
        ?>
        
        <?php if ($media_type === 'video' && $hero_video): ?>
            <!-- Hero Video -->
            <video class="hero-video" autoplay muted loop playsinline <?php if ($video_poster): ?>poster="<?php echo esc_url($video_poster); ?>"<?php endif; ?>>
                <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
                <!-- Fallback image if video fails to load -->
                <?php if ($hero_image): ?>
                    <img src="<?php echo esc_url($hero_image); ?>" alt="Hero fallback" class="hero-image">
                <?php else: ?>
                    <img src="https://picsum.photos/id/1018/1920/1080" alt="Hero fallback" class="hero-image">
                <?php endif; ?>
            </video>
        <?php else: ?>
            <!-- Hero Image -->
            <img src="<?php echo esc_url($hero_image); ?>" alt="Hero image" class="hero-image">
        <?php endif; ?>
        
        <div class="hero-text-container">
            <div class="hero-text hero-text-1">
                <?php bloginfo('name'); ?>
            </div>

            <div class="hero-text hero-text-2">
                neu erleben
            </div>
        </div>
        <?php endif; ?>
    </header>
