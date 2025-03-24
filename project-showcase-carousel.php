<?php
/**
 * Plugin Name: Project Showcase Carousel for Elementor
 * Description: A customizable project showcase carousel widget for Elementor with lightbox gallery support.
 * Version: 1.1.0
 * Author: SPARKWEB Studio
 * Author URI: https://sparkwebstudio.com/
 * Text Domain: project-showcase-carousel
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Main Project Showcase Carousel Class
 */
final class Project_Showcase_Carousel {
    /**
     * Instance of this class.
     *
     * @var Project_Showcase_Carousel
     */
    private static $instance = null;

    /**
     * Get an instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    /**
     * Initialize the plugin.
     */
    public function init() {
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }

        // Add Widget categories
        add_action('elementor/elements/categories_registered', [$this, 'add_widget_categories']);

        // Register widgets
        add_action('elementor/widgets/register', [$this, 'register_widgets']);

        // Register scripts and styles
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts']);
    }

    /**
     * Admin notice
     */
    public function admin_notice_missing_elementor() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requires Elementor to be installed and activated.', 'project-showcase-carousel'),
            '<strong>Project Showcase Carousel</strong>'
        );

        printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
    }

    /**
     * Add widget categories
     */
    public function add_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'project-showcase',
            [
                'title' => esc_html__('Project Showcase', 'project-showcase-carousel'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    /**
     * Register widgets
     */
    public function register_widgets($widgets_manager) {
        require_once(__DIR__ . '/widgets/project-carousel-widget.php');
        $widgets_manager->register(new \Project_Carousel_Widget());
    }

    /**
     * Register scripts and styles
     */
    public function register_scripts() {
        // Swiper CSS
        wp_register_style(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            [],
            '11.0.0'
        );

        // Swiper JS
        wp_register_script(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            [],
            '11.0.0',
            true
        );

        // Custom CSS
        wp_register_style(
            'project-showcase-carousel',
            plugins_url('assets/css/style.css', __FILE__),
            ['swiper'],
            '1.0.0'
        );

        // Custom JS
        wp_register_script(
            'project-showcase-carousel',
            plugins_url('assets/js/script.js', __FILE__),
            ['jquery', 'swiper'],
            '1.0.0',
            true
        );
    }
}

// Initialize the plugin
Project_Showcase_Carousel::get_instance(); 