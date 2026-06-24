<?php
/**
 * Logo Marquee Elementor widget.
 *
 * @package motto-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Eden_Logo_Marquee_Widget extends Widget_Base {

	public function get_name() {
		return 'eden_logo_marquee';
	}

	public function get_title() {
		return esc_html__( 'Logo Marquee', 'motto-child' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return array( 'eden-international' );
	}

	public function get_keywords() {
		return array( 'logo', 'marquee', 'carousel', 'slider', 'clients', 'brands', 'ticker' );
	}

	public function get_style_depends() {
		return array( 'eden-logo-marquee' );
	}

	protected function register_controls() {

		/* ---------------------------------------------------------------- Content: Logos */
		$this->start_controls_section(
			'section_logos',
			array(
				'label' => esc_html__( 'Logos', 'motto-child' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'logo_image',
			array(
				'label'   => esc_html__( 'Logo', 'motto-child' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'logo_link',
			array(
				'label'         => esc_html__( 'Link', 'motto-child' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'motto-child' ),
				'show_external' => true,
			)
		);

		$this->add_control(
			'logos',
			array(
				'label'       => esc_html__( 'Logos', 'motto-child' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ logo_link.url }}}',
				'default'     => array(
					array( 'logo_image' => array( 'url' => Utils::get_placeholder_image_src() ) ),
					array( 'logo_image' => array( 'url' => Utils::get_placeholder_image_src() ) ),
					array( 'logo_image' => array( 'url' => Utils::get_placeholder_image_src() ) ),
					array( 'logo_image' => array( 'url' => Utils::get_placeholder_image_src() ) ),
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- Content: Settings */
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'motto-child' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'   => esc_html__( 'Direction', 'motto-child' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'motto-child' ),
					'right' => esc_html__( 'Right', 'motto-child' ),
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'      => esc_html__( 'Speed (seconds per loop)', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 's' ),
				'range'      => array(
					's' => array(
						'min'  => 5,
						'max'  => 120,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 's',
					'size' => 25,
				),
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'        => esc_html__( 'Pause on Hover', 'motto-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motto-child' ),
				'label_off'    => esc_html__( 'No', 'motto-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- Style */
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'motto-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'logo_height',
			array(
				'label'      => esc_html__( 'Logo Height', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 60,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eden-logo-marquee' => '--eden-marquee-logo-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap Between Logos', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eden-logo-marquee' => '--eden-marquee-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'grayscale',
			array(
				'label'        => esc_html__( 'Grayscale (color on hover)', 'motto-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motto-child' ),
				'label_off'    => esc_html__( 'No', 'motto-child' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'logo_opacity',
			array(
				'label'     => esc_html__( 'Logo Opacity', 'motto-child' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					),
				),
				'default'   => array(
					'size' => 1,
				),
				'selectors' => array(
					'{{WRAPPER}} .eden-logo-marquee' => '--eden-marquee-opacity: {{SIZE}};',
				),
			)
		);

		$this->add_responsive_control(
			'fade_edges',
			array(
				'label'      => esc_html__( 'Edge Fade Width', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
					'%'  => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eden-logo-marquee' => '--eden-marquee-fade: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'edge_blur',
			array(
				'label'        => esc_html__( 'Edge Blur (left & right)', 'motto-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motto-child' ),
				'label_off'    => esc_html__( 'No', 'motto-child' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_responsive_control(
			'edge_blur_width',
			array(
				'label'      => esc_html__( 'Blur Width', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 400,
					),
					'%'  => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 120,
				),
				'condition'  => array( 'edge_blur' => 'yes' ),
				'selectors'  => array(
					'{{WRAPPER}} .eden-logo-marquee' => '--eden-marquee-blur-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'edge_blur_strength',
			array(
				'label'      => esc_html__( 'Blur Strength', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 8,
				),
				'condition'  => array( 'edge_blur' => 'yes' ),
				'selectors'  => array(
					'{{WRAPPER}} .eden-logo-marquee' => '--eden-marquee-blur-strength: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['logos'] ) ) {
			return;
		}

		$speed = isset( $settings['speed']['size'] ) && '' !== $settings['speed']['size'] ? $settings['speed']['size'] : 25;

		$classes = array( 'eden-logo-marquee' );
		$classes[] = 'eden-logo-marquee--' . ( 'right' === $settings['direction'] ? 'right' : 'left' );
		if ( 'yes' === $settings['grayscale'] ) {
			$classes[] = 'eden-logo-marquee--grayscale';
		}

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'            => $classes,
				'data-pause-hover' => 'yes' === $settings['pause_on_hover'] ? 'yes' : 'no',
				'style'            => '--eden-marquee-duration:' . floatval( $speed ) . 's;',
			)
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="eden-logo-marquee__track">
				<?php
				// Two identical groups create a seamless -50% loop.
				for ( $copy = 0; $copy < 2; $copy++ ) :
					$hidden = $copy > 0 ? ' aria-hidden="true"' : '';
					?>
					<div class="eden-logo-marquee__group"<?php echo $hidden; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php foreach ( $settings['logos'] as $logo ) : ?>
							<div class="eden-logo-marquee__item">
								<?php $this->render_logo( $logo ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endfor; ?>
			</div>
			<?php if ( 'yes' === $settings['edge_blur'] ) : ?>
				<span class="eden-logo-marquee__blur eden-logo-marquee__blur--left" aria-hidden="true"></span>
				<span class="eden-logo-marquee__blur eden-logo-marquee__blur--right" aria-hidden="true"></span>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Output a single logo, wrapped in a link when one is set.
	 *
	 * @param array $logo Repeater item.
	 */
	protected function render_logo( $logo ) {
		$image_html = $this->get_logo_image_html( $logo );

		if ( '' === $image_html ) {
			return;
		}

		$url = isset( $logo['logo_link']['url'] ) ? $logo['logo_link']['url'] : '';

		if ( '' === $url ) {
			echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		$attrs  = ' href="' . esc_url( $url ) . '"';
		$attrs .= ! empty( $logo['logo_link']['is_external'] ) ? ' target="_blank"' : '';
		$attrs .= ! empty( $logo['logo_link']['nofollow'] ) ? ' rel="nofollow"' : '';

		echo '<a class="eden-logo-marquee__link"' . $attrs . '>' . $image_html . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Build the <img> markup for a logo, preferring the attachment for srcset/alt.
	 *
	 * @param array $logo Repeater item.
	 * @return string
	 */
	protected function get_logo_image_html( $logo ) {
		if ( empty( $logo['logo_image']['url'] ) ) {
			return '';
		}

		if ( ! empty( $logo['logo_image']['id'] ) ) {
			$html = wp_get_attachment_image(
				$logo['logo_image']['id'],
				'full',
				false,
				array( 'class' => 'eden-logo-marquee__img' )
			);
			if ( $html ) {
				return $html;
			}
		}

		return '<img class="eden-logo-marquee__img" src="' . esc_url( $logo['logo_image']['url'] ) . '" alt="" loading="lazy" />';
	}
}
