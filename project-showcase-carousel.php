<?php
/**
 * Plugin Name: Project Showcase Carousel for Elementor
 * Description: A customizable project showcase carousel widget for Elementor with lightbox gallery support.
 * Version: 1.2.1
 * Author: SPARKWEB Studio
 * Author URI: https://sparkwebstudio.com/
 * Text Domain: project-showcase-carousel
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * @package Project_Showcase_Carousel
 * @author SPARKWEB Studio
 * @copyright 2024 SPARKWEB Studio
 * @license GPL-2.0-or-later
 * 
 * Project Showcase Carousel is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * Project Showcase Carousel is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Project Showcase Carousel. If not, see
 * https://www.gnu.org/licenses/gpl-2.0.html.
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PSC_VERSION', '1.2.1');
define('PSC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PSC_PLUGIN_URL', plugin_dir_url(__FILE__));

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
        
        // Register AJAX handlers
        add_action('wp_ajax_psc_action', [$this, 'handle_ajax_request']);
        add_action('wp_ajax_nopriv_psc_action', [$this, 'handle_ajax_request']);
    }

    /**
     * Handle AJAX requests
     */
    public function handle_ajax_request() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'psc_nonce')) {
            wp_send_json_error([
                'message' => esc_html__('Invalid security token.', 'project-showcase-carousel')
            ]);
        }

        // Get and sanitize action
        $action = isset($_POST['action']) ? sanitize_text_field($_POST['action']) : '';

        // Handle different actions
        switch ($action) {
            case 'psc_action':
                // Process the request
                $response = $this->process_ajax_request($_POST);
                wp_send_json_success($response);
                break;

            default:
                wp_send_json_error([
                    'message' => esc_html__('Invalid action.', 'project-showcase-carousel')
                ]);
        }
    }

    /**
     * Process AJAX request data
     */
    private function process_ajax_request($data) {
        // Sanitize all input data
        $sanitized_data = array_map(function($item) {
            if (is_array($item)) {
                return array_map('sanitize_text_field', $item);
            }
            return sanitize_text_field($item);
        }, $data);

        // Process the request and return response
        return [
            'status' => 'success',
            'data' => $sanitized_data
        ];
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
        
        // Check if widget is used on the page
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'check_widget_usage']);
    }

    /**
     * Admin notice
     */
    public function admin_notice_missing_elementor() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: %s: Plugin name */
            esc_html__('"%1$s" requires Elementor to be installed and activated.', 'project-showcase-carousel'),
            '<strong>' . esc_html__('Project Showcase Carousel', 'project-showcase-carousel') . '</strong>'
        );

        printf(
            '<div class="notice notice-error"><p>%1$s</p></div>',
            wp_kses_post($message)
        );
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
        require_once(PSC_PLUGIN_DIR . 'widgets/project-carousel-widget.php');
        $widgets_manager->register(new \Project_Carousel_Widget());
    }

    /**
     * Register scripts and styles
     */
    public function register_scripts() {
        // Swiper CSS
        wp_register_style(
            'swiper',
            esc_url('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'),
            [],
            '11.0.0'
        );

        // Swiper JS
        wp_register_script(
            'swiper',
            esc_url('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js'),
            [],
            '11.0.0',
            true
        );

        // Custom CSS
        wp_register_style(
            'project-showcase-carousel',
            esc_url(PSC_PLUGIN_URL . 'assets/css/style.css'),
            ['swiper'],
            PSC_VERSION
        );

        // Custom JS
        wp_register_script(
            'project-showcase-carousel',
            esc_url(PSC_PLUGIN_URL . 'assets/js/script.js'),
            ['jquery', 'swiper'],
            PSC_VERSION,
            true
        );

        // Add nonce for AJAX security
        wp_localize_script('project-showcase-carousel', 'pscAjax', [
            'nonce' => wp_create_nonce('psc_nonce'),
            'ajaxurl' => admin_url('admin-ajax.php'),
        ]);
    }

    /**
     * Check if widget is used on the page and enqueue scripts accordingly
     */
    public function check_widget_usage() {
        if (\Elementor\Plugin::$instance->preview->is_preview_mode() || 
            \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            wp_enqueue_style('project-showcase-carousel');
            wp_enqueue_script('project-showcase-carousel');
        } else {
            $post_id = get_the_ID();
            if ($post_id) {
                $document = \Elementor\Plugin::$instance->documents->get($post_id);
                if ($document) {
                    $data = $document->get_elements_data();
                    $widget_used = $this->find_widget_in_data($data);
                    
                    if ($widget_used) {
                        wp_enqueue_style('project-showcase-carousel');
                        wp_enqueue_script('project-showcase-carousel');
                    }
                }
            }
        }
    }

    /**
     * Recursively search for widget in element data
     */
    private function find_widget_in_data($elements) {
        foreach ($elements as $element) {
            if (isset($element['widgetType']) && $element['widgetType'] === 'project_carousel') {
                return true;
            }
            if (isset($element['elements']) && is_array($element['elements'])) {
                if ($this->find_widget_in_data($element['elements'])) {
                    return true;
                }
            }
        }
        return false;
    }
}

// Initialize the plugin
Project_Showcase_Carousel::get_instance(); 