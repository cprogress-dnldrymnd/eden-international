# Eden International

WordPress child theme based on the **Motto** parent theme by WebGeniusLab.

## Files

| File | Purpose |
|------|---------|
| `style.css` | Theme declaration + global utility CSS (aspect-ratio helpers, icon/paragraph fixes). Add custom CSS here. |
| `functions.php` | Enqueues parent stylesheet; registers widget assets and Elementor hooks. |
| `screenshot.png` | Theme preview image shown in the WP admin. |
| `assets/css/logo-marquee.css` | CSS for the Logo Marquee Elementor widget (registered, not enqueued globally). |
| `assets/css/swiper-nav.css` | CSS for the Swiper Navigation Elementor widget (registered, not enqueued globally). |
| `assets/css/breadcrumbs.css` | CSS for the Breadcrumbs Elementor widget (registered, not enqueued globally). |
| `assets/js/swiper-nav.js` | Front-end logic for the Swiper Navigation widget (finds + syncs the nearest Swiper). |
| `inc/elementor/class-logo-marquee-widget.php` | `Eden_Logo_Marquee_Widget` — custom Elementor widget class. |
| `inc/elementor/class-swiper-nav-widget.php` | `Eden_Swiper_Nav_Widget` — custom Elementor widget class. |
| `inc/elementor/class-breadcrumbs-widget.php` | `Eden_Breadcrumbs_Widget` — custom Elementor widget class. |
| `single.php` | Minimal single-post template: header → content → footer (bypasses parent theme's default single layout). |

## Key conventions

- **Parent theme slug:** `motto` — the `Template:` header in `style.css` must stay `motto` or the child theme breaks.
- **Text domain:** `motto-child` — use this in any `__()` / `_e()` translation calls.
- **Enqueue pattern:** parent style is loaded via `get_template_directory_uri()` (not `get_stylesheet_directory_uri()`), which is correct for child themes that need the parent's CSS.
- **Widget assets:** registered with `wp_register_style()` (not enqueued globally); the widget pulls the style in via `get_style_depends()` so it only loads on pages that use it.
- **Fonts:** no self-hosted webfonts — use Elementor's built-in Google Fonts picker instead.

## Custom Elementor widgets

Widgets live under `inc/elementor/` and are registered via the `elementor/widgets/register` hook in `functions.php`.

### Logo Marquee (`eden_logo_marquee`)

A CSS-only infinite-scroll ticker for client/partner logos.

- **Category:** `eden-international` (registered in `functions.php`)
- **Controls:** repeater of logo images + optional links; direction (left/right), speed (seconds), pause-on-hover, logo height, gap, opacity, edge-fade width, grayscale-on-hover toggle, edge-blur toggle (width + strength).
- **Animation:** pure CSS `translateX(-50%)` loop using two identical `__group` copies inside a `__track` wrapper. Optional frosted-glass edge overlays via `backdrop-filter: blur()` (`.eden-logo-marquee__blur--left/right`), conditionally rendered when `edge_blur = yes`. Respects `prefers-reduced-motion` (wraps logos instead of scrolling).
- **Render helpers:** `render_logo()` outputs a single logo item with an optional `<a>` wrapper; `get_logo_image_html()` builds the `<img>` tag, preferring `wp_get_attachment_image()` for srcset/alt when an attachment ID is available.
- **Style handle:** `eden-logo-marquee` → `assets/css/logo-marquee.css`

### Swiper Navigation (`eden_swiper_nav`)

Standalone prev/next arrows that control the **nearest Swiper instance** on the page — drop it anywhere near a Loop Carousel, Image Carousel, Testimonial Carousel, or any Swiper-driven widget. The slider needs no configuration.

- **Category:** `eden-international`
- **Controls:** prev/next icon (Elementor `ICONS`); target mode (auto-nearest / custom CSS selector); disable-at-start/end toggle; hide-if-no-slider toggle. Style: alignment, gap, button size, icon size, disabled opacity, and Normal/Hover colours + border + radius + box-shadow.
- **Sync logic (`assets/js/swiper-nav.js`):** finds the controlling Swiper by walking up the nav's ancestors and picking the geometrically closest `.swiper`/`.swiper-container` in the same container/section (custom selector overrides). Reads the live instance off `el.swiper`, retries while Elementor finishes its async Swiper init, then binds clicks to `slidePrev()`/`slideNext()` and toggles `--disabled` on `slideChange`/`reachBeginning`/`reachEnd`/`update`/`resize`. Disable state is skipped on looping sliders. Re-binds per widget via the `frontend/element_ready/eden_swiper_nav.default` hook (editor + frontend), guarded against double-init.
- **Style handle:** `eden-swiper-nav` → `assets/css/swiper-nav.css`; **script handle:** `eden-swiper-nav` → `assets/js/swiper-nav.js` (depends on `jquery`). Both pulled in via `get_style_depends()` / `get_script_depends()`.

### Breadcrumbs (`eden_breadcrumbs`)

A context-aware breadcrumb trail rendered server-side from the current query. Default separator is `|`.

- **Category:** `eden-international`
- **Controls:** separator text (default `|`); show-home toggle + home label; show-current toggle; show-post-category toggle; use-SEO-plugin toggle. Style: alignment, gap, typography, and link / link-hover / current / separator colours.
- **Trail logic (PHP):** `get_breadcrumb_items()` branches on the WP conditional tags — front page, blog index (`page_for_posts`), singular (page ancestors via `get_post_ancestors`; posts get the deepest primary term chain via `get_post_term_items()`), term archives (with term ancestors), post-type archives, author/date/search/404. Date archives link the parent year/month levels. The last item renders as the non-linked current crumb with `aria-current="page"`.
- **SEO hand-off:** when *Use SEO Plugin Trail* is on, delegates to `yoast_breadcrumb()` or `rank_math_get_breadcrumbs()` if active, wrapping it in `.eden-breadcrumbs--seo`; otherwise falls back to the built-in trail.
- **Markup/a11y:** `<nav aria-label="Breadcrumb">` → `<ol class="eden-breadcrumbs__list">`; separators are `aria-hidden` spans.
- **Style handle:** `eden-breadcrumbs` → `assets/css/breadcrumbs.css`, pulled in via `get_style_depends()`.

## Deployment

Drop the theme folder into `wp-content/themes/` on the WordPress install and activate via WP Admin → Appearance → Themes. No build step required.
