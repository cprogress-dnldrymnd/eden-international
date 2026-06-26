<?php
/**
 * Breadcrumbs Elementor widget.
 *
 * Outputs a context-aware breadcrumb trail for the current page (front page,
 * singular posts/pages with ancestors, taxonomy/term archives, author/date
 * archives, post type archives, search results and 404). Uses a "|" separator
 * by default. Falls back to the active SEO plugin's breadcrumb (Yoast / Rank
 * Math) when "Use SEO plugin trail" is enabled and one is available.
 *
 * @package motto-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Eden_Breadcrumbs_Widget extends Widget_Base {

	public function get_name() {
		return 'eden_breadcrumbs';
	}

	public function get_title() {
		return esc_html__( 'Breadcrumbs', 'motto-child' );
	}

	public function get_icon() {
		return 'eicon-product-breadcrumbs';
	}

	public function get_categories() {
		return array( 'eden-international' );
	}

	public function get_keywords() {
		return array( 'breadcrumbs', 'breadcrumb', 'navigation', 'trail', 'path', 'yoast', 'rank math' );
	}

	public function get_style_depends() {
		return array( 'eden-breadcrumbs' );
	}

	protected function register_controls() {

		/* ---------------------------------------------------------------- Content */
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Breadcrumbs', 'motto-child' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'   => esc_html__( 'Separator', 'motto-child' ),
				'type'    => Controls_Manager::TEXT,
				'ai'      => false,
				'default' => '|',
			)
		);

		$this->add_control(
			'show_home',
			array(
				'label'        => esc_html__( 'Show Home', 'motto-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motto-child' ),
				'label_off'    => esc_html__( 'No', 'motto-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'home_label',
			array(
				'label'     => esc_html__( 'Home Label', 'motto-child' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Home', 'motto-child' ),
				'condition' => array( 'show_home' => 'yes' ),
			)
		);

		$this->add_control(
			'show_current',
			array(
				'label'        => esc_html__( 'Show Current Page', 'motto-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motto-child' ),
				'label_off'    => esc_html__( 'No', 'motto-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_taxonomy',
			array(
				'label'        => esc_html__( 'Show Post Category', 'motto-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motto-child' ),
				'label_off'    => esc_html__( 'No', 'motto-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Include the primary category/term in the trail on single posts.', 'motto-child' ),
			)
		);

		$this->add_control(
			'use_seo_plugin',
			array(
				'label'        => esc_html__( 'Use SEO Plugin Trail', 'motto-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motto-child' ),
				'label_off'    => esc_html__( 'No', 'motto-child' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'description'  => esc_html__( 'If Yoast SEO or Rank Math is active, use its breadcrumb output instead of the built-in trail.', 'motto-child' ),
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
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'motto-child' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'motto-child' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'motto-child' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .eden-breadcrumbs__list' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'motto-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 8,
				),
				'selectors'  => array(
					'{{WRAPPER}} .eden-breadcrumbs' => '--eden-bc-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .eden-breadcrumbs__list',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- Style: Colors */
		$this->start_controls_section(
			'section_colors',
			array(
				'label' => esc_html__( 'Colors', 'motto-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'motto-child' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eden-breadcrumbs' => '--eden-bc-link: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_color_hover',
			array(
				'label'     => esc_html__( 'Link Hover Color', 'motto-child' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eden-breadcrumbs' => '--eden-bc-link-hover: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'current_color',
			array(
				'label'     => esc_html__( 'Current Page Color', 'motto-child' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eden-breadcrumbs' => '--eden-bc-current: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => esc_html__( 'Separator Color', 'motto-child' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eden-breadcrumbs' => '--eden-bc-sep: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings  = $this->get_settings_for_display();
		$separator = '' !== trim( $settings['separator'] ) ? $settings['separator'] : '|';

		// Optionally hand off to an active SEO plugin's breadcrumb.
		if ( 'yes' === $settings['use_seo_plugin'] ) {
			$seo = $this->get_seo_breadcrumb();
			if ( '' !== $seo ) {
				echo '<nav class="eden-breadcrumbs eden-breadcrumbs--seo" aria-label="' . esc_attr__( 'Breadcrumb', 'motto-child' ) . '">' . $seo . '</nav>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin output is already escaped.
				return;
			}
		}

		$items = $this->get_breadcrumb_items( $settings );

		if ( empty( $items ) ) {
			return;
		}

		$total = count( $items );
		?>
		<nav class="eden-breadcrumbs" aria-label="<?php echo esc_attr__( 'Breadcrumb', 'motto-child' ); ?>">
			<ol class="eden-breadcrumbs__list">
				<?php
				foreach ( $items as $index => $item ) :
					$is_last = ( $index === $total - 1 );
					?>
					<li class="eden-breadcrumbs__item">
						<?php if ( ! empty( $item['url'] ) && ! $is_last ) : ?>
							<a class="eden-breadcrumbs__link" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
						<?php else : ?>
							<span class="eden-breadcrumbs__current"<?php echo $is_last ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $item['text'] ); ?></span>
						<?php endif; ?>

						<?php if ( ! $is_last ) : ?>
							<span class="eden-breadcrumbs__sep" aria-hidden="true"><?php echo esc_html( $separator ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ol>
		</nav>
		<?php
	}

	/**
	 * Build the breadcrumb trail for the current query.
	 *
	 * @param array $settings Widget settings.
	 * @return array[] List of { text, url } items. The last item is the current page.
	 */
	protected function get_breadcrumb_items( $settings ) {
		$items = array();

		// Home crumb.
		if ( 'yes' === $settings['show_home'] && ! is_front_page() ) {
			$items[] = array(
				'text' => '' !== trim( (string) $settings['home_label'] ) ? $settings['home_label'] : esc_html__( 'Home', 'motto-child' ),
				'url'  => home_url( '/' ),
			);
		}

		if ( is_front_page() ) {
			$items[] = array(
				'text' => '' !== trim( (string) $settings['home_label'] ) ? $settings['home_label'] : esc_html__( 'Home', 'motto-child' ),
				'url'  => '',
			);
		} elseif ( is_home() ) {
			// Blog posts index (when a static front page is set).
			$blog_id = (int) get_option( 'page_for_posts' );
			if ( $blog_id ) {
				$items[] = array(
					'text' => get_the_title( $blog_id ),
					'url'  => '',
				);
			}
		} elseif ( is_singular() ) {
			$items = array_merge( $items, $this->get_singular_items( $settings ) );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$items = array_merge( $items, $this->get_term_items() );
		} elseif ( is_post_type_archive() ) {
			$items[] = array(
				'text' => post_type_archive_title( '', false ),
				'url'  => '',
			);
		} elseif ( is_author() ) {
			$items[] = array(
				/* translators: %s: author display name. */
				'text' => sprintf( esc_html__( 'Author: %s', 'motto-child' ), get_the_author() ),
				'url'  => '',
			);
		} elseif ( is_search() ) {
			$items[] = array(
				/* translators: %s: search query. */
				'text' => sprintf( esc_html__( 'Search results for: %s', 'motto-child' ), get_search_query() ),
				'url'  => '',
			);
		} elseif ( is_404() ) {
			$items[] = array(
				'text' => esc_html__( 'Page not found', 'motto-child' ),
				'url'  => '',
			);
		} elseif ( is_year() || is_month() || is_day() ) {
			$items = array_merge( $items, $this->get_date_items() );
		} elseif ( is_archive() ) {
			$items[] = array(
				'text' => get_the_archive_title(),
				'url'  => '',
			);
		}

		// Honor "show current page": drop the trailing (current) crumb when off,
		// unless it is the only crumb left.
		if ( 'yes' !== $settings['show_current'] && count( $items ) > 1 ) {
			$last = end( $items );
			if ( empty( $last['url'] ) ) {
				array_pop( $items );
			}
		}

		return $items;
	}

	/**
	 * Crumbs for a singular post/page: ancestors, optional category, then the item.
	 *
	 * @param array $settings Widget settings.
	 * @return array[]
	 */
	protected function get_singular_items( $settings ) {
		$items   = array();
		$post_id = get_queried_object_id();

		// Page ancestors (hierarchical) or the post's primary term (posts).
		if ( is_page() ) {
			$ancestors = array_reverse( get_post_ancestors( $post_id ) );
			foreach ( $ancestors as $ancestor_id ) {
				$items[] = array(
					'text' => get_the_title( $ancestor_id ),
					'url'  => get_permalink( $ancestor_id ),
				);
			}
		} elseif ( 'yes' === $settings['show_taxonomy'] ) {
			$items = array_merge( $items, $this->get_post_term_items( $post_id ) );
		}

		$items[] = array(
			'text' => get_the_title( $post_id ),
			'url'  => '',
		);

		return $items;
	}

	/**
	 * Resolve the primary term chain for a post (deepest term + its ancestors).
	 *
	 * @param int $post_id Post ID.
	 * @return array[]
	 */
	protected function get_post_term_items( $post_id ) {
		$items     = array();
		$post_type = get_post_type( $post_id );

		// Pick a sensible taxonomy: category for posts, otherwise the first public one.
		$taxonomy = 'category';
		if ( 'post' !== $post_type ) {
			$taxonomies = get_object_taxonomies( $post_type, 'names' );
			$taxonomies = array_values( array_filter( $taxonomies, 'is_taxonomy_hierarchical' ) );
			$taxonomy   = $taxonomies ? $taxonomies[0] : '';
		}

		if ( ! $taxonomy ) {
			return $items;
		}

		$terms = get_the_terms( $post_id, $taxonomy );
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return $items;
		}

		// Prefer the deepest term so the trail reflects the full hierarchy.
		$primary = $terms[0];
		foreach ( $terms as $term ) {
			if ( count( get_ancestors( $term->term_id, $taxonomy ) ) > count( get_ancestors( $primary->term_id, $taxonomy ) ) ) {
				$primary = $term;
			}
		}

		$chain = array_reverse( get_ancestors( $primary->term_id, $taxonomy ) );
		$chain[] = $primary->term_id;

		foreach ( $chain as $term_id ) {
			$term = get_term( $term_id, $taxonomy );
			if ( $term && ! is_wp_error( $term ) ) {
				$items[] = array(
					'text' => $term->name,
					'url'  => get_term_link( $term ),
				);
			}
		}

		return $items;
	}

	/**
	 * Crumbs for a taxonomy/category/tag archive: term ancestors then the term.
	 *
	 * @return array[]
	 */
	protected function get_term_items() {
		$items = array();
		$term  = get_queried_object();

		if ( ! $term || empty( $term->term_id ) ) {
			return $items;
		}

		$ancestors = array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) );
		foreach ( $ancestors as $ancestor_id ) {
			$ancestor = get_term( $ancestor_id, $term->taxonomy );
			if ( $ancestor && ! is_wp_error( $ancestor ) ) {
				$items[] = array(
					'text' => $ancestor->name,
					'url'  => get_term_link( $ancestor ),
				);
			}
		}

		$items[] = array(
			'text' => $term->name,
			'url'  => '',
		);

		return $items;
	}

	/**
	 * Crumbs for date archives (year / month / day), linking the parent levels.
	 *
	 * @return array[]
	 */
	protected function get_date_items() {
		$items = array();
		$year  = get_query_var( 'year' );
		$month = get_query_var( 'monthnum' );
		$day   = get_query_var( 'day' );

		$is_month = is_month() || is_day();
		$is_day   = is_day();

		$items[] = array(
			'text' => $year,
			'url'  => ( $is_month ) ? get_year_link( $year ) : '',
		);

		if ( $is_month ) {
			$items[] = array(
				'text' => date_i18n( 'F', mktime( 0, 0, 0, $month, 1, $year ) ),
				'url'  => $is_day ? get_month_link( $year, $month ) : '',
			);
		}

		if ( $is_day ) {
			$items[] = array(
				'text' => date_i18n( 'j', mktime( 0, 0, 0, $month, $day, $year ) ),
				'url'  => '',
			);
		}

		return $items;
	}

	/**
	 * Get the breadcrumb markup from an active SEO plugin, if available.
	 *
	 * @return string Empty string when no supported plugin is active.
	 */
	protected function get_seo_breadcrumb() {
		// Yoast SEO.
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			return yoast_breadcrumb( '', '', false );
		}

		// Rank Math.
		if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
			$crumbs = rank_math_get_breadcrumbs();
			if ( $crumbs ) {
				return $crumbs;
			}
		}

		return '';
	}
}
