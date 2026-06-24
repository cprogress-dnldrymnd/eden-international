# Eden International

WordPress child theme based on the **Motto** parent theme by WebGeniusLab.

## Files

| File | Purpose |
|------|---------|
| `style.css` | Theme declaration (name, author, template pointer). Add custom CSS here. |
| `functions.php` | Enqueues parent stylesheet; registers widget assets and Elementor hooks. |
| `screenshot.png` | Theme preview image shown in the WP admin. |
| `assets/css/logo-marquee.css` | CSS for the Logo Marquee Elementor widget (registered, not enqueued globally). |
| `inc/elementor/class-logo-marquee-widget.php` | `Eden_Logo_Marquee_Widget` — custom Elementor widget class. |

## Key conventions

- **Parent theme slug:** `motto` — the `Template:` header in `style.css` must stay `motto` or the child theme breaks.
- **Text domain:** `motto-child` — use this in any `__()` / `_e()` translation calls.
- **Enqueue pattern:** parent style is loaded via `get_template_directory_uri()` (not `get_stylesheet_directory_uri()`), which is correct for child themes that need the parent's CSS.
- **Widget assets:** registered with `wp_register_style()` (not enqueued globally); the widget pulls the style in via `get_style_depends()` so it only loads on pages that use it.

## Custom Elementor widgets

Widgets live under `inc/elementor/` and are registered via the `elementor/widgets/register` hook in `functions.php`.

### Logo Marquee (`eden_logo_marquee`)

A CSS-only infinite-scroll ticker for client/partner logos.

- **Category:** `eden-international` (registered in `functions.php`)
- **Controls:** repeater of logo images + optional links; direction (left/right), speed (seconds), pause-on-hover, logo height, gap, opacity, edge-fade width, grayscale-on-hover toggle.
- **Animation:** pure CSS `translateX(-50%)` loop using two identical `__group` copies inside a `__track` wrapper. Respects `prefers-reduced-motion` (wraps logos instead of scrolling).
- **Style handle:** `eden-logo-marquee` → `assets/css/logo-marquee.css`

## Deployment

Drop the theme folder into `wp-content/themes/` on the WordPress install and activate via WP Admin → Appearance → Themes. No build step required.
