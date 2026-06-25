/**
 * Eden International – Swiper Navigation widget.
 *
 * Wires a standalone prev/next control to the nearest Swiper instance on the
 * page, then keeps the arrows in sync with that slider (disabled state at the
 * ends, re-evaluated on slide change / resize). Works with Elementor's Loop
 * Carousel, Image Carousel and any other Swiper-driven widget.
 */
( function ( $ ) {
	'use strict';

	var SWIPER_SELECTOR = '.swiper, .swiper-container';

	/**
	 * Swiper attaches its instance to the container element as `el.swiper`.
	 */
	function getInstance( el ) {
		return el && el.swiper ? el.swiper : null;
	}

	/**
	 * Of a list of candidate Swiper elements, return the one whose centre is
	 * closest to the navigation widget.
	 */
	function pickClosest( navEl, list ) {
		if ( ! list.length ) {
			return null;
		}
		if ( 1 === list.length ) {
			return list[ 0 ];
		}

		var navRect = navEl.getBoundingClientRect();
		var navX = navRect.left + navRect.width / 2;
		var navY = navRect.top + navRect.height / 2;
		var best = null;
		var bestDist = Infinity;

		for ( var i = 0; i < list.length; i++ ) {
			// Skip a Swiper that contains the nav itself (nav sitting inside a slide).
			if ( list[ i ].contains( navEl ) ) {
				continue;
			}
			var r = list[ i ].getBoundingClientRect();
			var dx = r.left + r.width / 2 - navX;
			var dy = r.top + r.height / 2 - navY;
			var dist = dx * dx + dy * dy;
			if ( dist < bestDist ) {
				bestDist = dist;
				best = list[ i ];
			}
		}

		return best || list[ 0 ];
	}

	/**
	 * Resolve the Swiper element this nav should control.
	 *
	 * Custom selector wins. Otherwise walk up the ancestors and pick the
	 * closest Swiper that lives in the same container/section, falling back to
	 * the nearest Swiper anywhere on the page.
	 */
	function findSwiperEl( navEl, selector ) {
		if ( selector ) {
			var target;
			try {
				target = document.querySelector( selector );
			} catch ( e ) {
				target = null;
			}
			if ( target ) {
				if ( target.matches( SWIPER_SELECTOR ) ) {
					return target;
				}
				var inner = target.querySelector( SWIPER_SELECTOR );
				if ( inner ) {
					return inner;
				}
			}
			return null;
		}

		var node = navEl.parentElement;
		while ( node && node !== document.body ) {
			var found = node.querySelectorAll( SWIPER_SELECTOR );
			if ( found.length ) {
				var closest = pickClosest( navEl, found );
				if ( closest ) {
					return closest;
				}
			}
			node = node.parentElement;
		}

		return pickClosest( navEl, document.querySelectorAll( SWIPER_SELECTOR ) );
	}

	function bind( navEl, swiper ) {
		var prevBtn = navEl.querySelector( '.eden-swiper-nav__btn--prev' );
		var nextBtn = navEl.querySelector( '.eden-swiper-nav__btn--next' );
		var disableEnds = 'yes' === navEl.getAttribute( 'data-disable-ends' );

		navEl.classList.add( 'eden-swiper-nav--ready' );

		if ( prevBtn ) {
			prevBtn.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				swiper.slidePrev();
			} );
		}
		if ( nextBtn ) {
			nextBtn.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				swiper.slideNext();
			} );
		}

		function updateState() {
			if ( ! disableEnds || ( swiper.params && swiper.params.loop ) ) {
				return;
			}
			if ( prevBtn ) {
				prevBtn.classList.toggle( 'eden-swiper-nav__btn--disabled', !! swiper.isBeginning );
			}
			if ( nextBtn ) {
				nextBtn.classList.toggle( 'eden-swiper-nav__btn--disabled', !! swiper.isEnd );
			}
		}

		// Cover the events that can change which end we're on.
		[ 'init', 'slideChange', 'reachBeginning', 'reachEnd', 'fromEdge', 'update', 'resize', 'observerUpdate' ].forEach( function ( ev ) {
			swiper.on( ev, updateState );
		} );
		updateState();
	}

	/**
	 * Locate and bind the controlling Swiper, retrying while Elementor finishes
	 * its (asynchronous) Swiper initialisation.
	 */
	function initNav( navEl ) {
		if ( ! navEl || navEl.edenNavInit ) {
			return;
		}
		navEl.edenNavInit = true;

		var selector = navEl.getAttribute( 'data-target' ) || '';
		var attempts = 0;
		var maxAttempts = 80; // ~8s at 100ms.

		( function tryBind() {
			var swiperEl = findSwiperEl( navEl, selector );
			var swiper = getInstance( swiperEl );

			if ( swiper ) {
				bind( navEl, swiper );
				return;
			}

			attempts++;
			if ( attempts < maxAttempts ) {
				window.setTimeout( tryBind, 100 );
			} else if ( 'yes' === navEl.getAttribute( 'data-hide-empty' ) ) {
				navEl.style.display = 'none';
			}
		} )();
	}

	function initAll( root ) {
		var scope = root && root.querySelectorAll ? root : document;
		var navs = scope.querySelectorAll( '.eden-swiper-nav' );
		for ( var i = 0; i < navs.length; i++ ) {
			initNav( navs[ i ] );
		}
	}

	// Frontend bootstrap (also a safety net for the Elementor editor).
	if ( 'loading' !== document.readyState ) {
		initAll();
	} else {
		document.addEventListener( 'DOMContentLoaded', function () {
			initAll();
		} );
	}

	// Elementor editor / re-renders: bind each widget as it becomes ready.
	if ( $ ) {
		$( window ).on( 'elementor/frontend/init', function () {
			if ( window.elementorFrontend && elementorFrontend.hooks ) {
				elementorFrontend.hooks.addAction(
					'frontend/element_ready/eden_swiper_nav.default',
					function ( $scope ) {
						initAll( $scope[ 0 ] );
					}
				);
			}
		} );
	}
} )( window.jQuery );
