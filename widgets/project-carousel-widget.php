<?php
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
        return ['project', 'carousel', 'gallery', 'showcase', 'slider'];
    }

    public function get_script_depends() {
        return ['swiper', 'project-showcase-carousel'];
    }

    public function get_style_depends() {
        return ['swiper', 'project-showcase-carousel'];
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
            ]
        );

        $repeater->add_control(
            'gallery',
            [
                'label' => esc_html__('Gallery Images', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'default' => [],
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
                    [
                        'project_title' => esc_html__('Project #2', 'project-showcase-carousel'),
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
                'return_value' => 'yes',
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
                'label' => esc_html__('Navigation', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'project-showcase-carousel'),
                'label_off' => esc_html__('Hide', 'project-showcase-carousel'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => esc_html__('Pagination', 'project-showcase-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'project-showcase-carousel'),
                'label_off' => esc_html__('Hide', 'project-showcase-carousel'),
                'return_value' => 'yes',
                'default' => 'yes',
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
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="project-carousel" data-swiper-settings='{
            "slidesPerView": <?php echo esc_attr($settings['columns']); ?>,
            "spaceBetween": 30,
            "autoplay": <?php echo $settings['autoplay'] === 'yes' ? 'true' : 'false'; ?>,
            "delay": <?php echo esc_attr($settings['autoplay_speed']); ?>,
            "navigation": <?php echo $settings['navigation'] === 'yes' ? 'true' : 'false'; ?>,
            "pagination": <?php echo $settings['pagination'] === 'yes' ? 'true' : 'false'; ?>,
            "breakpoints": {
                "320": {
                    "slidesPerView": 1
                },
                "768": {
                    "slidesPerView": <?php echo esc_attr($settings['columns_tablet']); ?>
                },
                "1024": {
                    "slidesPerView": <?php echo esc_attr($settings['columns']); ?>
                }
            }
        }'>
            <div class="swiper-wrapper">
                <?php foreach ($settings['projects'] as $project) : ?>
                    <div class="swiper-slide">
                        <div class="project-item" data-gallery='<?php echo esc_attr(json_encode($project['gallery'])); ?>'>
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
        <?php
    }
} 