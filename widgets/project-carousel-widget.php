<?php
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class Project_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'project_carousel';
    }

    public function get_title() {
        return esc_html__('Project Showcase Carousel', 'project-showcase-carousel');
    }

    public function get_icon() {
        return 'eicon-carousel';
    }

    public function get_categories() {
        return ['project-showcase'];
    }

    public function get_keywords() {
        return ['project', 'carousel', 'gallery', 'showcase', 'portfolio'];
    }

    public function get_script_depends() {
        return ['project-showcase-carousel'];
    }

    public function get_style_depends() {
        return ['project-showcase-carousel'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Projects', 'project-showcase-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'project_title',
            [
                'label' => esc_html__('Project Title', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Project Title', 'project-showcase-carousel'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'main_image',
            [
                'label' => esc_html__('Main Image', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'gallery',
            [
                'label' => esc_html__('Gallery Images', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'default' => [],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'projects',
            [
                'label' => esc_html__('Projects', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'project_title' => esc_html__('Project #1', 'project-showcase-carousel'),
                        'main_image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        'gallery' => [],
                    ],
                ],
                'title_field' => '{{{ project_title }}}',
            ]
        );

        $this->end_controls_section();

        // View All Button Section
        $this->start_controls_section(
            'section_view_all',
            [
                'label' => esc_html__('View All Button', 'project-showcase-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_view_all',
            [
                'label' => esc_html__('Show View All Button', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'project-showcase-carousel'),
                'label_off' => esc_html__('Hide', 'project-showcase-carousel'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'view_all_text',
            [
                'label' => esc_html__('Button Text', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('View All', 'project-showcase-carousel'),
                'condition' => [
                    'show_view_all' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'view_all_icon',
            [
                'label' => esc_html__('Icon', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_view_all' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'view_all_icon_align',
            [
                'label' => esc_html__('Icon Position', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__('Before', 'project-showcase-carousel'),
                    'right' => esc_html__('After', 'project-showcase-carousel'),
                ],
                'condition' => [
                    'show_view_all' => 'yes',
                    'view_all_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // Carousel Settings Section
        $this->start_controls_section(
            'section_carousel_settings',
            [
                'label' => esc_html__('Carousel Settings', 'project-showcase-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'project-showcase-carousel'),
                'label_off' => esc_html__('No', 'project-showcase-carousel'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'default' => 3000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => esc_html__('Navigation Arrows', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'project-showcase-carousel'),
                'label_off' => esc_html__('Hide', 'project-showcase-carousel'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => esc_html__('Pagination Dots', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'project-showcase-carousel'),
                'label_off' => esc_html__('Hide', 'project-showcase-carousel'),
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Grid Settings Section
        $this->start_controls_section(
            'section_grid_settings',
            [
                'label' => esc_html__('Grid Settings', 'project-showcase-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'grid_columns',
            [
                'label' => esc_html__('Grid Columns', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => esc_html__('Grid Gap', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'project-showcase-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .project-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .project-title',
            ]
        );

        $this->add_responsive_control(
            'spacing',
            [
                'label' => esc_html__('Spacing', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // View All Button Style Section
        $this->start_controls_section(
            'section_view_all_style',
            [
                'label' => esc_html__('View All Button', 'project-showcase-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_view_all' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'view_all_button_padding',
            [
                'label' => esc_html__('Button Padding', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .view-all-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'view_all_button_margin',
            [
                'label' => esc_html__('Button Margin', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .view-all-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'view_all_button_typography',
                'selector' => '{{WRAPPER}} .view-all-button',
            ]
        );

        $this->add_control(
            'view_all_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .view-all-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'view_all_button_border_width',
            [
                'label' => esc_html__('Border Width', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .view-all-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'view_all_button_border_color',
            [
                'label' => esc_html__('Border Color', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .view-all-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_all_button_hover_color',
            [
                'label' => esc_html__('Hover Color', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .view-all-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_all_button_hover_text_color',
            [
                'label' => esc_html__('Hover Text Color', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .view-all-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_all_button_hover_border_color',
            [
                'label' => esc_html__('Hover Border Color', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .view-all-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_all_button_hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
                'prefix_class' => 'elementor-animation-',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Sanitize settings
        $settings['projects'] = array_map(function($project) {
            return [
                'project_title' => sanitize_text_field($project['project_title']),
                'main_image' => [
                    'id' => absint($project['main_image']['id']),
                    'url' => esc_url($project['main_image']['url']),
                ],
                'gallery' => array_map(function($image) {
                    return [
                        'id' => absint($image['id']),
                        'url' => esc_url($image['url']),
                    ];
                }, $project['gallery']),
            ];
        }, $settings['projects']);

        // Prepare Swiper settings
        $swiper_settings = [
            'slidesPerView' => absint($settings['columns']),
            'spaceBetween' => 30,
            'autoplay' => $settings['autoplay'] === 'yes' ? [
                'delay' => absint($settings['autoplay_speed']),
                'disableOnInteraction' => false,
            ] : false,
            'navigation' => [
                'nextEl' => '.swiper-button-next',
                'prevEl' => '.swiper-button-prev',
            ],
            'pagination' => [
                'el' => '.swiper-pagination',
                'clickable' => true,
            ],
            'breakpoints' => [
                640 => [
                    'slidesPerView' => absint($settings['columns_tablet'] ?? 2),
                ],
                480 => [
                    'slidesPerView' => absint($settings['columns_mobile'] ?? 1),
                ],
            ],
        ];

        // Prepare View All button icon
        $view_all_icon = '';
        if (!empty($settings['view_all_icon']['value'])) {
            $view_all_icon = \Elementor\Icons_Manager::render_icon($settings['view_all_icon'], ['aria-hidden' => 'true']);
        }

        // Render the widget
        ?>
        <div class="project-showcase-wrapper">
            <div class="project-carousel" data-swiper-settings='<?php echo wp_json_encode($swiper_settings); ?>'>
                <div class="swiper-wrapper">
                    <?php foreach ($settings['projects'] as $project) : ?>
                        <div class="swiper-slide">
                            <div class="project-item" data-gallery='<?php echo wp_json_encode($project['gallery']); ?>'>
                                <div class="project-image">
                                    <?php echo wp_get_attachment_image($project['main_image']['id'], 'full'); ?>
                                </div>
                                <h3 class="project-title"><?php echo esc_html($project['project_title']); ?></h3>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($settings['navigation'] === 'yes') : ?>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                <?php endif; ?>
                <?php if ($settings['pagination'] === 'yes') : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
            </div>

            <div class="project-grid" style="display: none;">
                <?php foreach ($settings['projects'] as $project) : ?>
                    <div class="grid-item">
                        <div class="project-item" data-gallery='<?php echo wp_json_encode($project['gallery']); ?>'>
                            <div class="project-image">
                                <?php echo wp_get_attachment_image($project['main_image']['id'], 'full'); ?>
                            </div>
                            <h3 class="project-title"><?php echo esc_html($project['project_title']); ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($settings['show_view_all'] === 'yes') : ?>
                <div class="view-all-button-wrapper">
                    <button class="view-all-button">
                        <?php if ($view_all_icon && $settings['view_all_icon_align'] === 'left') : ?>
                            <?php echo wp_kses_post($view_all_icon); ?>
                        <?php endif; ?>
                        <?php echo esc_html($settings['view_all_text']); ?>
                        <?php if ($view_all_icon && $settings['view_all_icon_align'] === 'right') : ?>
                            <?php echo wp_kses_post($view_all_icon); ?>
                        <?php endif; ?>
                    </button>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
} 