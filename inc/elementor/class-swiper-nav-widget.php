<?php
/**
 * Swiper Navigation Elementor widget.
 *
 * A standalone prev/next control that syncs with the nearest Swiper instance
 * on the page (Elementor Loop Carousel, Loop Grid carousel, Image Carousel,
 * Testimonial Carousel, or any widget that initialises Swiper). Drop it
 * anywhere near the slider — no slider settings required.
 *
 * @package motto-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;

class Eden_Swiper_Nav_Widget extends Widget_Base {

	public function get_name() {
		return 'eden_swiper_nav';
	}

	public function get_title() {
		return esc_html__( 'Swiper Navigation', 'motto-child' );
	}

	public function get_icon() {
		return 'eicon-ellipsis-h';
	}

	public function get_categories() {
		return array( 'eden-international' );
	}

	public function get_keywords() {
		return array( 'swiper', 'slider', 'carousel', 'navigation', 'arrows', 'next', 'prev', 'loop' );
	}

	public function get_style_depends() {
		return array( 'eden-swiper-nav' );
	}

	public function get_script_depends() {
		return array( 'eden-swiper-nav' );
	}

	protected function register_controls() {

		/* ---------------------------------------------------------------- Content: Navigation */
		$this->start_controls_section(
			'section_nav',
			array(
				'label' => esc_html__( 'Navigation', 'motto-child' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'prev_icon',
			array(
				'label'                  => esc_html__( 'Previous Icon', 'motto-child' ),
				'type'                   => Controls_Manager::ICONS,
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'svg' ),
				'default'                => array(
					'value'   => 'eicon-chevron-left',
					'library' => 'eicons',
				),
			)
		);

		$this->add_control(
			'next_icon',
			array(
				'label'                  => esc_html__( 'Next Icon', 'motto-child' ),
				'type'                   => Controls_Manager::ICONS,
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'svg' ),
				'default'                => array(
					'value'   => 'eicon-chevron-right',
					'library' => 'eicons',
				),
			)
		);

		$this->add_control(
			'target_mode',
			array(
				'label'     => esc_html__( 'Target Swiper', 'motto-child' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'auto',
				'separator' => 'before',
				'options'   => array(
					'auto'   => esc_html__( 'Auto (nearest slider)', 'motto-child' ),
					'custom' => esc_html__( 'Custom CSS selector', 'motto-child' ),
				),
				'description' => esc_html__( 'Auto controls the closest Swiper to this widget. Use a CSS selector to target a specific one.', 'motto-child' ),
			)
		);

		$this->add_control(
			'target_selector',
			array(
				'label'       => esc_html__( 'Swiper Selector', 'motto-child' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => false,
				'placeholder' => '#my-carousel, .elementor-element-abc123',
				'description' => esc_html__( 'A selector for the slider container (or any element wrapping it). The widget looks for the Swiper inside.', 'motto-child' ),
				'condition'   => array( 'target_mode' => 'custom' ),
			)
		);

		$this->add_control(
			'disable_ends',
			array(
				'label'        => esc_html__( 'Disable at Start / End', 'motto-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motto-child' ),
				'label_off'    => esc_html__( 'No', 'motto-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'description'  => esc_html__( 'Greys out the arrow when the slider reaches its first/last slide. Ignored on looping sliders.', 'motto-child' ),
			)
		);

		$this->add_control(
			'hide_if_empty',
			array(
				'label'        => esc_html__( 'Hide If No Slider Found', 'motto-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motto-child' ),
				'label_off'    => esc_html__( 'No', 'motto-child' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- Style: Layout */
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'motto-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'motto-child' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'motto-child' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'motto-child' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'motto-child' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_gap',
			array(
				'label'      => esc_html__( 'Gap Between Arrows', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eden-swiper-nav' => '--eden-nav-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_size',
			array(
				'label'      => esc_html__( 'Button Size', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 120,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 44,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eden-swiper-nav' => '--eden-nav-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 18,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eden-swiper-nav' => '--eden-nav-icon-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'disabled_opacity',
			array(
				'label'     => esc_html__( 'Disabled Opacity', 'motto-child' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					),
				),
				'default'   => array(
					'size' => 0.35,
				),
				'condition' => array( 'disable_ends' => 'yes' ),
				'selectors' => array(
					'{{WRAPPER}} .eden-swiper-nav' => '--eden-nav-disabled-opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- Style: Buttons */
		$this->start_controls_section(
			'section_buttons',
			array(
				'label' => esc_html__( 'Buttons', 'motto-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		/* Normal */
		$this->start_controls_tab(
			'tab_button_normal',
			array( 'label' => esc_html__( 'Normal', 'motto-child' ) )
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'motto-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .eden-swiper-nav' => '--eden-nav-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg',
			array(
				'label'     => esc_html__( 'Background', 'motto-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,20,0.4)',
				'selectors' => array(
					'{{WRAPPER}} .eden-swiper-nav' => '--eden-nav-bg: {{VALUE}};',
				),
			)
		);

		// Direct properties (not a group control) so we can force them past the
		// parent theme's <button> styling with !important.
		$this->add_control(
			'border_style',
			array(
				'label'     => esc_html__( 'Border Type', 'motto-child' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'none'   => esc_html__( 'None', 'motto-child' ),
					'solid'  => esc_html__( 'Solid', 'motto-child' ),
					'dashed' => esc_html__( 'Dashed', 'motto-child' ),
					'dotted' => esc_html__( 'Dotted', 'motto-child' ),
					'double' => esc_html__( 'Double', 'motto-child' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .eden-swiper-nav__btn' => 'border-style: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 12,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 1,
				),
				'condition'  => array( 'border_style!' => 'none' ),
				'selectors'  => array(
					'{{WRAPPER}} .eden-swiper-nav__btn' => 'border-width: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'motto-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2D2D2D',
				'condition' => array( 'border_style!' => 'none' ),
				'selectors' => array(
					'{{WRAPPER}} .eden-swiper-nav__btn' => 'border-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		/* Hover */
		$this->start_controls_tab(
			'tab_button_hover',
			array( 'label' => esc_html__( 'Hover', 'motto-child' ) )
		);

		$this->add_control(
			'button_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'motto-child' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eden-swiper-nav' => '--eden-nav-color-hover: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_hover',
			array(
				'label'     => esc_html__( 'Background', 'motto-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.08)',
				'selectors' => array(
					'{{WRAPPER}} .eden-swiper-nav' => '--eden-nav-bg-hover: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'motto-child' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eden-swiper-nav__btn:hover' => 'border-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'motto-child' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'before',
				'default'    => array(
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eden-swiper-nav__btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_shadow',
				'selector' => '{{WRAPPER}} .eden-swiper-nav__btn',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$selector = ( 'custom' === $settings['target_mode'] && ! empty( $settings['target_selector'] ) )
			? trim( $settings['target_selector'] )
			: '';

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'             => 'eden-swiper-nav',
				'data-target'       => $selector,
				'data-disable-ends' => 'yes' === $settings['disable_ends'] ? 'yes' : 'no',
				'data-hide-empty'   => 'yes' === $settings['hide_if_empty'] ? 'yes' : 'no',
			)
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<button type="button" class="eden-swiper-nav__btn eden-swiper-nav__btn--prev" aria-label="<?php echo esc_attr__( 'Previous slide', 'motto-child' ); ?>">
				<?php $this->render_nav_icon( $settings['prev_icon'] ); ?>
			</button>
			<button type="button" class="eden-swiper-nav__btn eden-swiper-nav__btn--next" aria-label="<?php echo esc_attr__( 'Next slide', 'motto-child' ); ?>">
				<?php $this->render_nav_icon( $settings['next_icon'] ); ?>
			</button>
		</div>
		<?php
	}

	/**
	 * Output a navigation icon, hidden from assistive tech (the button carries the label).
	 *
	 * @param array $icon Elementor ICONS control value.
	 */
	protected function render_nav_icon( $icon ) {
		if ( empty( $icon['value'] ) ) {
			return;
		}

		echo '<span class="eden-swiper-nav__icon">';
		Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
		echo '</span>';
	}
}
