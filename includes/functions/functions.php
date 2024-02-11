<?php
/**
 * Plugin functions
 *
 * @package    Configure 8 Options
 * @subpackage Includes
 * @since      1.0.0
 */

namespace CFE_Plugin;

/**
 * Plugin object
 *
 * Gets this plugin's core class.
 *
 * @since  1.0.0
 * @global object $site The Site class.
 * @return mixed Returns the class object or false.
 */
function plugin() {

	// Access global variables.
	global $site;

	if ( getPlugin( $site->theme() ) ) {
		return getPlugin( $site->theme() );
	} else {
		return false;
	}
}

/**
 * Plugin sidebars count
 *
 * This counts plugins with the `adminSidebar()`
 * method implemented. Theme options plugin is
 * excluded from the count because this theme
 * adds a link for theme options if a theme plugin
 * is available. So if the theme options plugin
 * is the only plugin activated with a sidebar link
 * then the plugin links section is not printed.
 *
 * @since  1.0.0
 * @global array $plugins Array of active plugins.
 * @return integer Returns a number of plugins.
 */
function plugin_sidebars_count() {

	// Access global variables.
	global $plugins;

	if ( empty( $plugins['adminSidebar'] ) ) {
		return 0;
	}

	$count = 0;
	foreach ( $plugins['adminSidebar'] as $link ) {
		if ( 'theme' != $link->type() ) {
			$count++;
		}
	}
	return $count;
}

/**
 * Get SVG files
 *
 * @since  1.0.0
 * @param  string $filename Name of the SVG file.
 * @return mixed Returns the contents of the SVG file or
 *               returns null if the filename is not found.
 */
function get_svg_icon( $filename = '' ) {

	// Access global variables.
	global $site;

	$path = plugin()->phpPath() . 'assets' . DS . 'images' . DS . $filename . '.svg';
	$args = [
		'svg',
		'g',
		'path'
	];

	if ( is_file( $path ) && is_readable( $path ) ) {

		$file = strip_tags( $path, $args );
		return file_get_contents( $file );

	} else {
		return $path;
	}
}

/**
 * SVG icon
 *
 * Prints the contents of a given SVG file.
 *
 * @since  1.0.0
 * @param  string $filename Name of the SVG file.
 * @param  boolean $wrap Wraps the icon in HTML if true.
 * @param  string $class Additional class names for the wrapper.
 * @param  string $title Contents of the title attribute if
 *                       $wrap is true.
 * @return void
 */
function svg_icon( $filename, $wrap = true, $class = '' ) {

	if ( ! empty( $class ) ) {
		$class = ' ' . $class;
	}

	if ( true == $wrap ) {
		printf(
			'<span class="svg-icon%s">%s</span>',
			$class,
			get_svg_icon( $filename )
		);
	} else {
		echo get_svg_icon( $filename );
	}
}

/**
 * Asset file suffix
 *
 * Gets minified file if not in debug mode.
 * Third party (e.g. jQuery) may be exempted.
 *
 * @since  1.0.0
 * @return string Returns an empty string or
 *                `.min` string.
 */
function asset_min() {

	// Get non-minified file if in debug mode.
	if ( defined( 'DEBUG_MODE' ) && DEBUG_MODE ) {
		return '';
	}
	return '.min';
}

/**
 * Plugin domain
 *
 * @since  1.0.0
 * @param  string $dir The URL directory.
 * @return string Returns the URL.
 */
function domain( $dir = '/' ) {

	// Prepared directory variables.
	if ( 'css' === $dir ) {
		$dir = '/assets/css/';
	} elseif ( 'schemes' === $dir ) {
		$dir = '/assets/css/schemes/';
	} elseif ( 'css_colors' === $dir ) {
		$dir = '/assets/css/schemes/colors/';
	} elseif ( 'css_fonts' === $dir ) {
		$dir = '/assets/css/schemes/fonts/';
	} elseif ( 'js' === $dir ) {
		$dir = '/assets/js/';
	} elseif ( 'images' === $dir ) {
		$dir = '/assets/images/';
	} elseif ( 'fonts' === $dir ) {
		$dir = '/assets/fonts/';
	}
	return DOMAIN_PLUGINS . plugin()->className() . $dir;
}

function css( $files, $base = '' ) {

	if ( ! is_array( $files ) ) {
		$files = [ $files ];
	}
	$base = domain();

	$links = '';
	foreach ( $files as $file ) {
		$links .= '<link rel="stylesheet" type="text/css" href="' . $base . $file . '?version=' . BLUDIT_VERSION . '">' . PHP_EOL;
	}
	return $links;
}

function js( $files, $base = '', $attributes = '' ) {

	if ( ! is_array( $files ) ) {
		$files = [ $files ];
	}
	$base = domain();

	$scripts = '';
	foreach ( $files as $file ) {
		$scripts .= '<script ' . $attributes . ' src="' . $base . $file . '?version=' . BLUDIT_VERSION . '"></script>' . PHP_EOL;
	}

	return $scripts;
}

/**
 * Is RTL language
 *
 * @since  1.0.0
 * @param  mixed $langs Arguments to be passed.
 * @param  array $rtl Default arguments.
 * @global object $L The Language class.
 * @return boolean Returns true if site is in RTL language.
 */
function is_rtl( $langs = null, $rtl = [] ) {

	// Access global variables.
	global $L;

	$rtl = [
		'ar',
		'fa',
		'he',
		'ks',
		'ku',
		'pa',
		'ps',
		'sd',
		'ug',
		'ur'
	];

	// Maybe override defaults.
	if ( is_array( $langs ) && $langs ) {
		$langs = array_merge( $rtl, $langs );
	} else {
		$langs = $rtl;
	}

	$current = $L->currentLanguageShortVersion();

	if ( is_array( $rtl ) && in_array( $current, $rtl ) ) {
		return true;
	}
	return false;
}

/**
 * Is static loop
 *
 * If a static page has the same slug as the loop slug.
 *
 * @since  1.0.0
 * @return boolean Return whether that page exists.
 */
function is_static_loop() {

	if ( static_loop_page() ) {
		return true;
	}
	return false;
}

/**
 * Static loop page
 *
 * @since  1.0.0
 * @return mixed Returns the static loop page object or
 *               returns false if the page doesn't exist.
 */
function static_loop_page() {

	// Access global variables.
	global $site;

	// Get the blog slug setting.
	$loop_field = $site->getField( 'uriBlog' );

	// Remove slashes from field value, if set.
	$loop_key   = str_replace( '/', '', $loop_field );

	// Build a page using blog slug setting.
	$loop_page  = buildPage( $loop_key );

	// Return whether that page exists (could be built).
	if ( $loop_page ) {
		return $loop_page;
	}
	return false;
}

/**
 * System can search
 *
 * Checks if the system has search functionality.
 *
 * @since  1.0.0
 * @return boolean Returns true if a search plugin is activated.
 */
function can_search() {
	if (
		getPlugin( 'Search_Forms' ) ||
		getPlugin( 'pluginSearch' )
	) {
		return true;
	}
	return false;
}

/**
 * Frontend title tag
 *
 * @since  1.0.0
 * @global object $categories The Categories class.
 * @global object $L The Language class.
 * @global object $page The Page class.
 * @global object $site The Site class.
 * @global object $tags The Tags class.
 * @global object $url The Url class.
 * @return string Returns the meta tag.
 */
function title_tag() {

	global $categories, $L, $page, $site, $tags, $url;

	// Title separator.
	$sep = plugin()->dbFields['title_sep'];
	if ( 'custom' == plugin()->title_sep() && plugin()->custom_sep() ) {
		$sep = plugin()->custom_sep();
	} elseif ( 'custom' != plugin()->title_sep() ) {
		$sep = plugin()->title_sep();
	}

	// Loop name.
	$loop_name = '';
	if ( 'blog' == $url->whereAmI() ) {
		if ( ! is_static_loop() ) {
			if ( plugin()->loop_title() ) {
				$loop_name = ucwords( plugin()->loop_title() );
			} elseif ( $site->uriBlog() ) {
				$loop_name = ucwords( str_replace( [ '/', '-', '_' ], '', $site->uriBlog() ) );
			} else {
				$loop_name = ucwords( plugin()->loop_type() );
			}
		}
	}

	// Loop page.
	$loop_page = '';
	$loop_sep  = '>';
	if ( is_rtl() ) {
		$loop_sep  = '<';
	}
	if ( isset( $_GET['page'] ) && $_GET['page'] > 1 ) {
		$loop_page = sprintf(
			' %s %s %s',
			$loop_sep,
			$L->get( 'Page' ),
			$_GET['page']
		);
		if ( is_rtl() ) {
			$loop_page = sprintf(
				'%s %s %s ',
				$_GET['page'],
				$L->get( 'Page' ),
				$loop_sep
			);
		}
	}

	// Default title.
	if ( is_rtl() && plugin()->default_ttag_rtl() ) {
		$format = plugin()->default_ttag_rtl();
	} elseif ( plugin()->default_ttag() ) {
		$format = plugin()->default_ttag();
	} else {
		$format = sprintf(
			'%s %s %s',
			$site->title(),
			$site->slogan() ? $sep : '',
			$site->slogan()
		);
		if ( is_rtl() ) {
			$format = sprintf(
				'%s %s %s',
				$site->slogan(),
				$site->slogan() ? $sep : '',
				$site->title()
			);
		}
	}

	// Default 404 page.
	if ( $url->notFound() && ! $site->pageNotFound() ) {

		if ( is_rtl() && plugin()->error_ttag_rtl() ) {
			$format = plugin()->error_ttag_rtl();
		} elseif ( plugin()->error_ttag() ) {
			$format = plugin()->error_ttag();
		} else {
			$format = sprintf(
				'%s %s %s',
				$L->get( 'URL Error: Page Not Found' ),
				$sep,
				$site->title()
			);
			if ( is_rtl() ) {
				$format = sprintf(
					'%s %s %s',
					$site->title(),
					$sep,
					$L->get( 'URL Error: Page Not Found' )
				);
			}
		}

	// Posts loop.
	} elseif ( 'blog' == $url->whereAmI() ) {

		if ( is_rtl() && plugin()->loop_ttag_rtl() ) {
			$format = plugin()->loop_ttag_rtl();
		} elseif ( plugin()->loop_ttag() ) {
			$format = plugin()->loop_ttag();

		} elseif ( is_static_loop() ) {
			$static_loop = static_loop_page();
			$format = sprintf(
				'%s%s %s %s',
				ucwords( $static_loop->title() ),
				$loop_page,
				$sep,
				$site->title()
			);
			if ( is_rtl() ) {
				$format = sprintf(
					'%s %s %s%s',
					$site->title(),
					$sep,
					$loop_page,
					ucwords( $static_loop->title() )
				);
			}
		} else {
			$format = sprintf(
				'%s%s %s %s',
				$loop_name,
				$loop_page,
				$sep,
				$site->title()
			);
			if ( is_rtl() ) {
				$format = sprintf(
					'%s %s %s%s',
					$site->title(),
					$sep,
					$loop_page,
					$loop_name
				);
			}
		}

	// Post or page.
	} elseif ( 'page' == $url->whereAmI() ) {

		// Page (static).
		if ( $page->isStatic() ) {
			if ( is_rtl() && plugin()->page_ttag_rtl() ) {
				$format = plugin()->page_ttag_rtl();
			} elseif ( plugin()->page_ttag() ) {
					$format = plugin()->page_ttag();
			} else {
				$format = sprintf(
					'%s %s %s',
					$page->title(),
					$sep,
					$site->title()
				);
				if ( is_rtl() ) {
					$format = sprintf(
						'%s %s %s',
						$site->title(),
						$sep,
						$page->title()
					);
				}
			}
		} else {
			if ( is_rtl() && plugin()->post_ttag_rtl() ) {
				$format = plugin()->post_ttag_rtl();
			} elseif ( plugin()->post_ttag() ) {
					$format = plugin()->post_ttag();
			} else {
				$format = sprintf(
					'%s %s %s %s %s',
					$page->title(),
					$sep,
					$page->date(),
					$sep,
					$site->title()
				);
				if ( is_rtl() ) {
					$format = sprintf(
						'%s %s %s %s %s',
						$site->title(),
						$sep,
						$page->date(),
						$sep,
						$page->title()
					);
				}
			}
		}
		$format = str_replace( '{{page-title}}', $page->title(), $format );
		$format = str_replace( '{{page-description}}', $page->description(), $format );
		$format = str_replace( '{{published}}', $page->date(), $format );

	// Category loop.
	} elseif ( 'category' == $url->whereAmI() ) {
		try {
			$key    = $url->slug();
			$cat    = new \Category( $key );

			if ( is_rtl() && plugin()->cat_ttag_rtl() ) {
				$format = plugin()->cat_ttag_rtl();
			} elseif ( plugin()->cat_ttag() ) {
					$format = plugin()->cat_ttag();
			} else {
				$format = sprintf(
					'%s %s %s',
					$cat->name(),
					$sep,
					$site->title()
				);
				if ( is_rtl() ) {
					$format = sprintf(
						'%s %s %s',
						$site->title(),
						$sep,
						$cat->name()
					);
				}
			}
			$format = str_replace( '{{category-name}}', $cat->name(), $format );
		} catch ( \Exception $e ) {
			// Category doesn't exist.
		}

	// Tag loop.
	} elseif ( 'tag' == $url->whereAmI() ) {
		try {
			$key    = $url->slug();
			$tag    = new \Tag( $key );

			if ( is_rtl() && plugin()->tag_ttag_rtl() ) {
				$format = plugin()->tag_ttag_rtl();
			} elseif ( plugin()->tag_ttag() ) {
					$format = plugin()->tag_ttag();
			} else {
				$format = sprintf(
					'%s %s %s',
					$tag->name(),
					$sep,
					$site->title()
				);
				if ( is_rtl() ) {
					$format = sprintf(
						'%s %s %s',
						$site->title(),
						$sep,
						$tag->name()
					);
				}
			}
			$format = str_replace( '{{tag-name}}', $tag->name(), $format );
		} catch ( \Exception $e ) {
			// Tag doesn't exist.
		}

	} elseif ( 'search' == $url->whereAmI() ) {

		$slug  = $url->slug();
		$terms = '';
		if ( str_contains( $slug, 'search/' ) ) {
			$terms = str_replace( 'search/', '', $slug );
			$terms = str_replace( '+', ' ', $terms );
		}

		if ( is_rtl() && plugin()->search_ttag_rtl() ) {
			$format = plugin()->search_ttag_rtl();
		} elseif ( plugin()->search_ttag() ) {
				$format = plugin()->search_ttag();
		} else {
			$format = sprintf(
				'%s "%s" %s %s',
				$L->get( 'Searching' ),
				$terms,
				$sep,
				$site->title()
			);
			if ( is_rtl() ) {
				$format = sprintf(
					'%s %s "%s" %s',
					$site->title(),
					$sep,
					$terms,
					$L->get( 'Searching' )
				);
			}
		}
		$format = str_replace( '{{search-terms}}', $terms, $format );
	}

	$format = str_replace( '{{separator}}', $sep, $format );
	$format = str_replace( '{{site-title}}', $site->title(), $format );
	$format = str_replace( '{{site-slogan}}', $site->slogan(), $format );
	$format = str_replace( '{{site-description}}', $site->description(), $format );
	$format = str_replace( '{{loop-type}}', ucwords( plugin()->loop_type() ), $format );
	$format = str_replace( '{{page-number}}', $loop_page, $format );

	$title = sprintf(
		'<title dir="%s">%s</title>',
		is_rtl() ? 'rtl' : 'ltr',
		$format
	);
	return $title;
}

/**
 * Custom fields
 *
 * @since  1.0.0
 * @global object $L The Language class.
 * @return array Returns an array for JSON encoding.
 */
function custom_fields() {

	// Access global variables.
	global $L, $site;

	// Random cover checkbox field.
	$cover_field = [
		'random_cover' => [
			'type'  => 'bool',
			'label' => $L->get( 'Random Cover' ),
			'tip'   => $L->get( 'Display a random cover image from images uploaded to this page. Requires no cover image set.' )
		]
	];

	// Page gallery checkbox field.
	$gallery_field = [
		'page_gallery' => [
			'type'  => 'bool',
			'label' => $L->get( 'Gallery' ),
			'tip'   => $L->get( 'Add a gallery of images uploaded to this page.' )
		]
	];
	$fields = array_merge( $gallery_field, $cover_field );

	// Gallery heading field.
	$heading_field = [
		'gallery_heading' => [
			'type'        => 'string',
			'label'       => $L->get( 'Gallery Heading' ),
			'tip'         => $L->get( 'Text used above this page\'s image gallery.' ),
			'placeholder' => $L->get( 'Image Gallery' )
		]
	];
	$fields = array_merge( $fields, $heading_field );

	// Read more field.
	$more_field = [
		'read_more' => [
			'type'  => 'string',
			'label' => $L->get( 'Read More' ),
			'tip'   => $L->get( 'Text used if this content is linked in the front page slider or when abbreviated in some contexts.' )
		]
	];
	$fields = array_merge( $fields, $more_field );

	return $fields;
}

/**
 * Options list
 *
 * Displays a list of options and their values for the dashboard.
 *
 * @since  1.0.0
 * @param  boolean $list_only Whether to only print the list markup.
 * @global object $L The Language class.
 * @return mixed Returns the widget markup or null.
 */
function options_list( $list_only = false ) {

	// Access global variables.
	global $L;

	// Get the plugin database.
	$db  = new \dbJSON( plugin()->filenameDb );
	$get = $db->getDB();

	// Stop if the plugin database is empty.
	if ( empty( $get ) ) {
		return null;
	}

	// Options list markup.
	$options = '<ul class="dashboard-options-list">';
	foreach ( $get as $key => $value ) {

		// Convert boolean values to "true" or "false" text.
		if ( is_bool( $value ) ) {
			if ( 0 == $value ) {
				$value = $L->get( 'false' );
			}

			if ( 1 == $value ) {
				$value = $L->get( 'true' );
			}
		}

		// Convert empty string values to "empty" text.
		if ( is_string( $value ) && empty( $value ) ) {
			$value = $L->get( 'empty' );
		}

		// Convert empty array to visual array.
		if ( is_array( $value ) && 0 === count( $value ) ) {
			$value = '[ ]';
		} elseif ( is_array( $value ) ) {
			$array = [];
			foreach( $value as $k ) {
				$array[] = sprintf(
					'"%s"',
					$k
				);
			}
			$array = implode( ', ', $array );
			$value = "[{$array}]";
		}

		// Option list item.
		$options .= sprintf(
			'<li><span class="option-name">%s:</span> <span class="option-value">%s</span></li>',
			$key,
			$value
		);
	}
	$options .= '</ul>';

	// Widget markup.
	if ( $list_only ) {
		$html = $options;
	} else {
		$html = sprintf(
			'<div id="dashboard-options"><h2>%s</h2><p>%s</p>%s</div>',
			$L->get( 'Theme Options' ),
			$L->get( 'List of current theme options and their values.' ),
			$options
		);
	}
	return $html;
}

/**
 * Change theme
 *
 * Replaces admin theme value in the site database.
 *
 * @since  1.0.0
 * @return void
 */
function change_theme() {

	// Access global variables.
	global $site;

	// Define database file.
	$db_file = DB_SITE;

	// Get current admin theme.
	$current = '"adminTheme":"' . $site->adminTheme() . '"';
	if ( DEBUG_MODE ) {
		$current = '"adminTheme": "' . $site->adminTheme() . '"';
	}

	// Get database content.
	$content = file_get_contents( $db_file );
	$replace = '"adminTheme":"configureight"';
	if ( DEBUG_MODE ) {
		$replace = '"adminTheme": "configureight"';
	}

	// Change admin theme to Configureight.
	$content = str_replace( $current, $replace, $content );

	// Write theme into the database file.
	file_put_contents( $db_file, $content );
}

/**
 * Default theme
 *
 * Restore admin theme to default in the site database.
 *
 * @since  1.0.0
 * @return void
 */
function default_theme() {

	// Access global variables.
	global $site;

	// Define database file.
	$db_file = DB_SITE;

	// Get current admin theme.
	$current = '"adminTheme":"' . $site->adminTheme() . '"';
	if ( DEBUG_MODE ) {
		$current = '"adminTheme": "' . $site->adminTheme() . '"';
	}

	// Get database content.
	$content = file_get_contents( $db_file );
	$replace = '"adminTheme":"booty"';
	if ( DEBUG_MODE ) {
		$replace = '"adminTheme": "booty"';
	}

	// Change admin theme to default.
	$content = str_replace( $current, $replace, $content );

	// Write theme into the database file.
	file_put_contents ( $db_file, $content );
}

/**
 * Is admin theme directory empty
 *
 * @since  1.0.0
 * @param  string $themes_dir
 * @return mixed
 */
function is_dir_empty( $themes_dir ) {

    if ( ! is_readable( $themes_dir ) ) {
		return null;
	}
    return ( count( scandir( $themes_dir ) ) == 2 );
}

/**
 * Admin theme
 *
 * Checks for the Configure 8 admin theme.
 *
 * @since  1.0.0
 * @return boolean Returns true if the admin theme is
 *                 in hte admin themes directory.
 */
function admin_theme() {

	$themes_dir = PATH_ADMIN_THEMES;

	if ( ! is_readable( $themes_dir ) ) {
		return false;
	}

	$themes = [];
	foreach ( glob( $themes_dir . '*', GLOB_ONLYDIR ) as $theme ) {
		if ( ! is_dir_empty( $theme ) ) {

			// Truncate the theme path and keep the theme name only.
			$theme    = str_replace( $themes_dir, '', $theme );
			$themes[] = $theme;
		}
	}

	if ( is_array( $themes ) && in_array( 'configureight', $themes ) ) {
		return true;
	}
	return false;
}

/**
 * Static pages list
 *
 * @since  1.0.0
 * @param  mixed $args Arguments to be passed.
 * @param  array $defaults Default arguments.
 * @global object $site The Site class.
 * @return string Returns the list markup.
 */
function static_list( $args = null, $defaults = [] ) {

	// Access global variables.
	global $site;

	// Default arguments.
	$defaults = [
		'wrap'      => false,
		'direction' => 'vert',
		'title'     => false,
		'heading'   => 'h2',
		'links'     => true
	];

	// Maybe override defaults.
	if ( is_array( $args ) && $args ) {
		$args = array_merge( $defaults, $args );
	} else {
		$args = $defaults;
	}

	// List classes.
	$classes   = [];
	$classes[] = 'static-list';
	if ( 'vert' == $args['direction'] ) {
		$classes[] = 'static-list-vertical';
	} else {
		$classes[] = 'static-list-horizontal';
	}
	$classes = implode( ' ', $classes );

	// List markup.
	$html = '';
	if ( $args['wrap'] ) {
		$html = '<div class="static-list-wrap">';
	}

	if ( $args['title'] ) {
		$html .= sprintf(
			'<%s>%s</%s>',
			$args['heading'],
			$args['title'],
			$args['heading']
		);
	}

	$html .= sprintf(
		'<ul class="%s">',
		$classes
	);

	$static = buildStaticPages();
	foreach ( $static as $page ) {

		// Item class.
		$classes = [ 'static-page' ];
		if ( $page->hasChildren() ) {
			$classes[] = 'parent-page';
		} elseif ( $page->isChild() ) {
			$classes[] = 'child-page';
		}
		$classes = implode( ' ', $classes );

		if (
			$page->key() != $site->homepage() &&
			$page->key() != $site->pageNotFound()
		) {
			$html .= "<li class='{$classes}'>";

			if ( $args['links'] ) {
				$html .= '<a href="' . $page->permalink() . '">';
			}
			$html .= $page->title();
			if ( $args['links'] ) {
				$html .= '</a>';
			}
			$html .= '</li>';
		}
	}
	$html .= '</ul>';

	if ( $args['wrap'] ) {
		$html .= '</div>';
	}
	return $html;
}

/**
 * Categories list
 *
 * @since  1.0.0
 * @param  mixed $args Arguments to be passed.
 * @param  array $defaults Default arguments.
 * @global object $categories The Categories class.
 * @return string Returns the list markup.
 */
function categories_list( $args = null, $defaults = [] ) {

	// Access global variables.
	global $categories;

	// Default arguments.
	$defaults = [
		'wrap'      => false,
		'direction' => 'horz',
		'buttons'   => false,
		'title'     => false,
		'heading'   => 'h2',
		'links'     => true,
		'count'     => false
	];

	// Maybe override defaults.
	if ( is_array( $args ) && $args ) {
		$args = array_merge( $defaults, $args );
	} else {
		$args = $defaults;
	}

	// List classes.
	$classes   = [];
	$classes[] = 'categories-list';
	if ( 'vert' == $args['direction'] ) {
		$classes[] = 'categories-list-vertical';
	} else {
		$classes[] = 'categories-list-horizontal';
	}
	if ( $args['buttons'] ) {
		$classes[] = 'categories-list-buttons';
	}
	$classes = implode( ' ', $classes );

	// List markup.
	$html = '';
	if ( $args['wrap'] ) {
		$html = '<div class="categories-list-wrap">';
	}

	if ( $args['title'] ) {
		$html .= sprintf(
			'<%s>%s</%s>',
			$args['heading'],
			$args['title'],
			$args['heading']
		);
	}
	$html .= sprintf(
		'<ul class="%s">',
		$classes
	);

	// By default the database of categories are alphanumeric sorted.
	foreach ( $categories->db as $key => $fields ) {

		$get_count = count( $fields['list'] );
		$get_name  = $fields['name'];

		$name = $get_name;
		if ( $args['count'] ) {
			$name = sprintf(
				'%s (%s)',
				$get_name,
				$get_count
			);
		}

		if ( $get_count > 0 ) {
			$html .= '<li>';
			if ( $args['links'] ) {
				$html .= '<a href="' . DOMAIN_CATEGORIES . $key . '">';
			}
			$html .= $name;
			if ( $args['links'] ) {
				$html .= '</a>';
			}
			$html .= '</li>';
		}
	}
	$html .= '</ul>';

	if ( $args['wrap'] ) {
		$html .= '</div>';
	}

	return $html;
}

/**
 * Tags list
 *
 * @since  1.0.0
 * @param  mixed $args Arguments to be passed.
 * @param  array $defaults Default arguments.
 * @global object $tags The Tags class.
 * @return string Returns the list markup.
 */
function tags_list( $args = null, $defaults = [] ) {

	// Access global variables.
	global $tags;

	// Default arguments.
	$defaults = [
		'wrap'      => false,
		'direction' => 'horz',
		'buttons'   => false,
		'title'     => false,
		'heading'   => 'h2',
		'links'     => true,
		'count'     => false
	];

	// Maybe override defaults.
	if ( is_array( $args ) && $args ) {
		$args = array_merge( $defaults, $args );
	} else {
		$args = $defaults;
	}

	// List classes.
	$classes   = [];
	$classes[] = 'tags-list';
	if ( 'vert' == $args['direction'] ) {
		$classes[] = 'tags-list-vertical';
	} else {
		$classes[] = 'tags-list-horizontal';
	}
	if ( $args['buttons'] ) {
		$classes[] = 'tags-list-buttons';
	}
	$classes = implode( ' ', $classes );

	// List markup.
	$html = '';
	if ( $args['wrap'] ) {
		$html = '<div class="tags-list-wrap">';
	}

	if ( $args['title'] ) {
		$html .= sprintf(
			'<%s>%s</%s>',
			$args['heading'],
			$args['title'],
			$args['heading']
		);
	}
	$html .= sprintf(
		'<ul class="%s">',
		$classes
	);

	// By default the database of tags are alphanumeric sorted.
	foreach ( $tags->db as $key => $fields ) {

		$get_count = $tags->numberOfPages( $key );
		$get_name  = $fields['name'];

		$name = $get_name;
		if ( $args['count'] ) {
			$name = sprintf(
				'%s (%s)',
				$get_name,
				$get_count
			);
		}
		$html .= '<li>';
		if ( $args['links'] ) {
			$html .= '<a href="' . DOMAIN_TAGS . $key . '">';
		}
		$html .= $name;
		if ( $args['links'] ) {
			$html .= '</a>';
		}
		$html .= '</li>';
	}
	$html .= '</ul>';

	if ( $args['wrap'] ) {
		$html .= '</div>';
	}
	return $html;
}

/**
 * Search error display
 *
 * @since  1.0.0
 * @return array Returns an array of modified arguments.
 */
function error_search_display() {

	// Get search plugin object,
	$search = getPlugin( 'Search_Forms' );
	if ( ! $search ) {
		return null;
	}

	// Set up arguments array.
	$args = [];

	if ( ! plugin()->error_search_label() ) {
		$args = array_merge( $args, [ 'label' => false ] );
	} elseif ( plugin()->error_search_label() ) {
		$args = array_merge( $args, [ 'label' => plugin()->error_search_label() ] );
	}
	if ( ! plugin()->error_search_heading() ) {
		$args = array_merge( $args, [ 'label_wrap' => false ] );
	}
	if ( plugin()->error_search_holder() ) {
		$args = array_merge( $args, [ 'placeholder' => plugin()->error_search_holder() ] );
	} elseif ( ! plugin()->error_search_holder() ) {
		$args = array_merge( $args, [ 'placeholder' => false ] );
	}
	if ( ! plugin()->error_search_btn() ) {
		$args = array_merge( $args, [ 'button' => false ] );
	}
	if ( plugin()->error_search_btn_text() ) {
		$args = array_merge( $args, [ 'button_text' => plugin()->error_search_btn_text() ] );
	}
	return $args;
}

/**
 * Static pages error display
 *
 * @since  1.0.0
 * @return array Returns an array of modified arguments.
 */
function error_static_display() {

	// Set up arguments array.
	$args = [];
	$args['wrap'] = true;

	// List heading element.
	if ( plugin()->error_static_heading() ) {
		$args['heading'] = plugin()->error_static_heading();
	}

	// List heading text.
	if ( plugin()->error_static_title() ) {
		$args['title'] = plugin()->error_static_title();
	}

	// List direction.
	if ( plugin()->error_static_dir() ) {
		$args['direction'] = plugin()->error_static_dir();
	}
	return $args;
}

/**
 * Categories error display
 *
 * @since  1.0.0
 * @return array Returns an array of modified arguments.
 */
function error_cats_display() {

	// Set up arguments array.
	$args = [];
	$args['wrap'] = true;

	// List heading element.
	if ( plugin()->error_cats_heading() ) {
		$args['heading'] = plugin()->error_cats_heading();
	}

	// List heading text.
	if ( plugin()->error_cats_title() ) {
		$args['title'] = plugin()->error_cats_title();
	}

	// List direction.
	if ( plugin()->error_cats_dir() ) {
		$args['direction'] = plugin()->error_cats_dir();
	}
	return $args;
}

/**
 * Tags error display
 *
 * @since  1.0.0
 * @return array Returns an array of modified arguments.
 */
function error_tags_display() {

	// Set up arguments array.
	$args = [];
	$args['wrap'] = true;

	// List heading element.
	if ( plugin()->error_tags_heading() ) {
		$args['heading'] = plugin()->error_tags_heading();
	}

	// List heading text.
	if ( plugin()->error_tags_title() ) {
		$args['title'] = plugin()->error_tags_title();
	}

	// List direction.
	if ( plugin()->error_tags_dir() ) {
		$args['direction'] = plugin()->error_tags_dir();
	}
	return $args;
}

/**
 * File size format
 *
 * Converts a number of bytes to the largest unit the bytes will fit into.
 * Taken from WordPress.
 *
 * It is easier to read 1 KB than 1024 bytes and 1 MB than 1048576 bytes. Converts
 * number of bytes to human readable number by taking the number of that unit
 * that the bytes will go into it. Supports YB value.
 *
 * Please note that integers in PHP are limited to 32 bits, unless they are on
 * 64 bit architecture, then they have 64 bit size. If you need to place the
 * larger size then what PHP integer type will hold, then use a string. It will
 * be converted to a double, which should always have 64 bit length.
 *
 * Technically the correct unit names for powers of 1024 are KiB, MiB etc.
 *
 * @since  1.0.0
 * @param  integer|string $bytes Number of bytes. Note max integer size for integers.
 * @param  integer $decimals Optional. Precision of number of decimal places. Default 0.
 * @return mixed Number string on success, false on failure.
 */
function size_format( $bytes, $decimals = 0 ) {

	// Read bytes in chunks.
	$KB = 1024;
	$MB = 1024 * $KB;
	$GB = 1024 * $MB;
	$TB = 1024 * $GB;

	// Assign relevant units.
	$units = [
		'TB' => $TB,
		'GB' => $GB,
		'MB' => $MB,
		'KB' => $KB,
		'B'  => 1,
	];

	// Return 0 bytes if so.
	if ( 0 === $bytes ) {
		return '0 B';
	}

	// Return the size in relevant units.
	foreach ( $units as $unit => $mag ) {
		if ( (float) $bytes >= $mag ) {
			return number_format( $bytes / $mag, abs( (int) $decimals ) ) . ' ' . $unit;
		}
	}
	return false;
}