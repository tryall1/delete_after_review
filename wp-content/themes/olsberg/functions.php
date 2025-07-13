<?php
// Menu registration
function olsberg_register_menus() {
    register_nav_menus(array(
        'main-menu' => 'Main menu',
        'header-menu' => 'Header menu'
    ));
}
add_action('init', 'olsberg_register_menus');


class Olsberg_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="submenu">';
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
        
        if ($depth == 0) {
            $has_children = in_array('menu-item-has-children', $classes);
            
            // Get ACF field for custom icon
            $custom_icon = get_field('menu_icon', $item);
            
            // Fallback
            $icon_map = array(
                'Aktiv & Erleben' => 'fas fa-mountain',
                'Sehenswertes & Highlights' => 'fas fa-map-marker-alt',
                'Gesundheit & Wellness' => 'fas fa-heart',
                'Unterkünfte & Übernachtungen' => 'fas fa-bed',
                'Kontakt & Service' => 'fas fa-envelope'
            );
            
            if (!empty($custom_icon)) {
                $icon = $custom_icon;
            } else {
                $icon = isset($icon_map[$item->title]) ? $icon_map[$item->title] : 'fas fa-mountain';
            }
            
            $output .= '<li class="menu-item">';
            $output .= '<a class="menu-link" href="' . esc_attr($item->url) . '"';
            if ($has_children) {
                $output .= ' onclick="toggleSubmenu(this, event)"';
            }
            $output .= '>';
            $output .= '<div class="menu-link-content">';
            $output .= '<i class="' . esc_attr($icon) . ' menu-icon"></i>';
            $output .= '<span>' . esc_html($item->title) . '</span>';
            $output .= '</div>';
            if ($has_children) {
                $output .= '<i class="fas fa-chevron-down chevron"></i>';
            }
            $output .= '</a>';
        } else {
            $output .= '<li class="submenu-item">';
            $output .= '<a href="' . esc_attr($item->url) . '" class="submenu-link">' . esc_html($item->title) . '</a>';
        }
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}

// Walker for horizontal header menu
class Olsberg_Header_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        // Submenu is not needed in the horizontal menu
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        // Submenu is not needed in the horizontal menu
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if ($depth == 0) {
            // get the icon from ACF field
            $custom_icon = get_field('menu_icon', $item);

            // Fallback SVG icons for horizontal menu
            $icon_map = array(
                'Suche' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>',
                'Stadtpläne' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 6.75V15m6-6v8.25m-6 2.25h12M3 13.5h18" /></svg>',
                'Kontakt' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0-.621.504-1.125 1.125-1.125H20.25M2.25 6.75v9.5c0 .621.504 1.125 1.125 1.125h15v-9.5M2.25 6.75L10.5 13.5 18.75 6.75" /></svg>'
            );
            
            if (!empty($custom_icon)) {
                $icon = '<i class="' . esc_attr($custom_icon) . '"></i>';
            } else {
                $icon = isset($icon_map[$item->title]) ? $icon_map[$item->title] : '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2" /></svg>';
            }
            
            $output .= '<a href="' . esc_attr($item->url) . '" class="nav-link">';
            $output .= $icon;
            $output .= '<span>' . esc_html($item->title) . '</span>';
            $output .= '</a>';
        }
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        // nothing
    }
}

// Include styles and scripts
function olsberg_enqueue_scripts() {
    wp_enqueue_style('olsberg-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'olsberg_enqueue_scripts');

// Support for post thumbnails
add_theme_support('post-thumbnails');

// Automatic creation of ACF fields for menu
function olsberg_create_menu_acf_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_menu_icon',
            'title' => 'Menu Icon customization',
            'fields' => array(
                array(
                    'key' => 'field_menu_icon',
                    'label' => 'Menu icom',
                    'name' => 'menu_icon',
                    'type' => 'text',
                    'instructions' => 'FontAwesome icons.',
                    'placeholder' => 'fas fa-mountain',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'nav_menu_item',
                        'operator' => '==',
                        'value' => 'all',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'olsberg_create_menu_acf_fields');

// ACF fields for hero media (image or video)
function olsberg_create_hero_acf_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_hero_settings',
            'title' => 'Hero Block Settings',
            'fields' => array(
                array(
                    'key' => 'field_hero_media_type',
                    'label' => 'Media Type',
                    'name' => 'hero_media_type',
                    'type' => 'radio',
                    'instructions' => 'Choose whether to display an image or video in the hero section',
                    'choices' => array(
                        'image' => 'Image',
                        'video' => 'Video'
                    ),
                    'default_value' => 'image',
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_hero_image',
                    'label' => 'Hero Image',
                    'name' => 'hero_image',
                    'type' => 'image',
                    'instructions' => 'Select image for hero block. Recommended size: 1920x1080px',
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_hero_media_type',
                                'operator' => '==',
                                'value' => 'image',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_hero_video',
                    'label' => 'Hero Video',
                    'name' => 'hero_video',
                    'type' => 'file',
                    'instructions' => 'Upload video file for hero block. Supported formats: MP4, WebM. Recommended size: 1920x1080px',
                    'return_format' => 'url',
                    'mime_types' => 'mp4,webm,mov',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_hero_media_type',
                                'operator' => '==',
                                'value' => 'video',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_hero_video_poster',
                    'label' => 'Video Poster Image',
                    'name' => 'hero_video_poster',
                    'type' => 'image',
                    'instructions' => 'Optional poster image displayed before video loads',
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_hero_media_type',
                                'operator' => '==',
                                'value' => 'video',
                            ),
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page_type',
                        'operator' => '==',
                        'value' => 'front_page',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'olsberg_create_hero_acf_fields');