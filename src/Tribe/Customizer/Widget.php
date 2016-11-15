<?php
// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * The Events Calendar Customizer Section Class
 * Widgets
 *
 * @package The Events Calendar
 * @subpackage Customizer
 * @since 4.4
 */
final class Tribe__Events__Customizer__Widget extends Tribe__Customizer__Section {
	/**
	 * PHP 5.2 method of creating "instances" of an abstract require this
	 *
	 * Note: This is the only required method for a Connector to work
	 *
	 * @return self The dynamic instance of this Class
	 */
	public static function instance() {
		return tribe( 'tec.customizer.widget' );
	}

	/**
	 * Grab the CSS rules template
	 *
	 * @return string
	 */
	public function get_css_template( $template ) {
		$customizer = Tribe__Customizer::instance();

		if ( $customizer->has_option( $this->ID, 'featured_bg_color' ) ) {
			$template .= '
				.tribe-events-list-widget .tribe-event-featured,
				.tribe-events-venue-widget .tribe-event-featured,
				.tribe-mini-calendar-list-wrapper .tribe-event-featured,
				.tribe-events-adv-list-widget .tribe-event-featured {
					background-color: <%= widget.featured_bg_color %>;
				}
			';
		}

		if ( $customizer->has_option( $this->ID, 'featured_title_color' ) ) {
			$template .= '
				.tribe-events-list-widget .tribe-event-featured a,
				.tribe-events-venue-widget .tribe-event-featured a,
				.tribe-mini-calendar-list-wrapper .tribe-event-featured .tribe-events-title a,
				.tribe-events-adv-list-widget .tribe-event-featured .tribe-events-title a {
					color: <%= widget.featured_title_color %>;
				}

				.tribe-events-list-widget .tribe-event-featured .tribe-events-title a:active,
				.tribe-events-list-widget .tribe-event-featured .tribe-events-title a:hover,
				.tribe-events-venue-widget .tribe-event-featured .tribe-events-title a:active,
				.tribe-events-venue-widget .tribe-event-featured .tribe-events-title a:hover,
				.tribe-mini-calendar-list-wrapper .tribe-event-featured .tribe-events-title a:active,
				.tribe-mini-calendar-list-wrapper .tribe-event-featured .tribe-events-title a:hover,
				.tribe-events-adv-list-widget .tribe-event-featured .tribe-events-title a:active,
				.tribe-events-adv-list-widget .tribe-event-featured .tribe-events-title a:hover {
					color: <%= widget.featured_title_color_active %>;
				}
			';
		}

		if ( $customizer->has_option( $this->ID, 'featured_text_color' ) ) {
			$template .= '
				.tribe-events-list-widget .tribe-event-featured,
				.tribe-events-venue-widget .tribe-event-featured,
				.tribe-mini-calendar-list-wrapper .tribe-event-featured,
				.tribe-events-adv-list-widget .tribe-event-featured {
					color: <%= widget.featured_text_color %>;
				}
			';
		}

		if ( ! $customizer->get_option( array( 'widget', 'featured_show_images' ) ) ) {
			$template .= '
				.tribe-events-list-widget .tribe-event-featured .tribe-event-image,
				.tribe-events-venue-widget .tribe-event-featured .tribe-event-image,
				.tribe-events-adv-list-widget .tribe-event-featured .tribe-event-image,
				.tribe-mini-calendar-list-wrapper .tribe-event-featured .tribe-event-image {
					display: none;
				}
			';
		}

		return $template;
	}

	public function create_ghost_settings( $settings = array() ) {

		if ( ! empty( $settings['featured_bg_color'] ) ) {
			$featured_bg_color = new Tribe__Utils__Color( $settings['featured_bg_color'] );

			$settings['featured_bg_color'] = '#' . $featured_bg_color->getHex();
		}

		if ( ! empty( $settings['featured_title_color'] ) ) {
			$featured_title_color = new Tribe__Utils__Color( $settings['featured_title_color'] );

			$settings['featured_title_color'] = '#' . $featured_title_color->getHex();
			$settings['featured_title_color_active'] = '#' . $featured_title_color->lighten( 8 );
		}

		if ( ! empty( $settings['featured_text_color'] ) ) {
			$featured_text_color = new Tribe__Utils__Color( $settings['featured_text_color'] );

			$settings['featured_text_color'] = '#' . $featured_text_color->getHex();
		}

		return $settings;
	}

	public function setup() {
		$this->defaults = array(
			'calendar_header_color' => '#999',
			'calendar_datebar_color' => '#e0e0e0',
			'featured_show_images' => true,
		);

		$this->arguments = array(
			'priority'    => 70,
			'capability'  => 'edit_theme_options',
			'title'       => esc_html__( 'Widgets', 'the-events-calendar' ),
			'description' => esc_html__( 'Options selected here will override what was selected in the "General Theme" and "Global Elements" sections', 'the-events-calendar' ),
		);
	}

	/**
	 * Create the Fields/Settings for this sections
	 *
	 * @param  WP_Customize_Section $section The WordPress section instance
	 * @param  WP_Customize_Manager $manager [description]
	 *
	 * @return void
	 */
	public function register_settings( WP_Customize_Section $section, WP_Customize_Manager $manager ) {
		$customizer = Tribe__Customizer::instance();

		$manager->add_setting(
			$customizer->get_setting_name( 'calendar_header_color', $section ),
			array(
				'default'              => $this->get_default( 'calendar_header_color' ),
				'type'                 => 'option',

				'sanitize_callback'    => 'sanitize_hex_color',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
			)
		);

		$manager->add_control(
			new WP_Customize_Color_Control(
				$manager,
				$customizer->get_setting_name( 'calendar_header_color', $section ),
				array(
					'label'   => __( 'Calendar Header Color', 'the-events-calendar' ),
					'section' => $section->id,
				)
			)
		);

		$manager->add_setting(
			$customizer->get_setting_name( 'calendar_datebar_color', $section ),
			array(
				'default'              => $this->get_default( 'calendar_datebar_color' ),
				'type'                 => 'option',

				'sanitize_callback'    => 'sanitize_hex_color',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
			)
		);

		$manager->add_control(
			new WP_Customize_Color_Control(
				$manager,
				$customizer->get_setting_name( 'calendar_datebar_color', $section ),
				array(
					'label'   => __( 'Calendar Date Bar Color', 'the-events-calendar' ),
					'section' => $section->id,
				)
			)
		);

		$manager->add_setting(
			$customizer->get_setting_name( 'featured_bg_color', $section ),
			array(
				'default'              => $this->get_default( 'featured_bg_color' ),
				'type'                 => 'option',

				'sanitize_callback'    => 'sanitize_hex_color',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
			)
		);

		$manager->add_control(
			new WP_Customize_Color_Control(
				$manager,
				$customizer->get_setting_name( 'featured_bg_color', $section ),
				array(
					'label'   => __( 'Featured Background Color' ),
					'section' => $section->id,
				)
			)
		);

		$manager->add_setting(
			$customizer->get_setting_name( 'featured_title_color', $section ),
			array(
				'default'              => $this->get_default( 'featured_title_color' ),
				'type'                 => 'option',

				'sanitize_callback'    => 'sanitize_hex_color',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
			)
		);

		$manager->add_control(
			new WP_Customize_Color_Control(
				$manager,
				$customizer->get_setting_name( 'featured_title_color', $section ),
				array(
					'label'   => __( 'Featured Title Color' ),
					'section' => $section->id,
				)
			)
		);

		$manager->add_setting(
			$customizer->get_setting_name( 'featured_text_color', $section ),
			array(
				'default'              => $this->get_default( 'featured_text_color' ),
				'type'                 => 'option',

				'sanitize_callback'    => 'sanitize_hex_color',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
			)
		);

		$manager->add_control(
			new WP_Customize_Color_Control(
				$manager,
				$customizer->get_setting_name( 'featured_text_color', $section ),
				array(
					'label'   => __( 'Featured Text Color' ),
					'section' => $section->id,
				)
			)
		);

		$manager->add_setting(
			$customizer->get_setting_name( 'featured_show_images', $section ),
			array(
				'default'              => $this->get_default( 'featured_show_images' ),
				'type'                 => 'option',
			)
		);

		$manager->add_control(
			new WP_Customize_Control(
				$manager,
				$customizer->get_setting_name( 'featured_show_images', $section ),
				array(
					'label'   => __( 'Show Featured Event Images' ),
					'section' => $section->id,
					'type'    => 'checkbox',
				)
			)
		);

		// Introduced to make Selective Refresh have less code duplication
		$customizer->add_setting_name( $customizer->get_setting_name( 'calendar_header_color', $section ) );
		$customizer->add_setting_name( $customizer->get_setting_name( 'calendar_datebar_color', $section ) );
		$customizer->add_setting_name( $customizer->get_setting_name( 'featured_bg_color', $section ) );
		$customizer->add_setting_name( $customizer->get_setting_name( 'featured_title_color', $section ) );
		$customizer->add_setting_name( $customizer->get_setting_name( 'featured_text_color', $section ) );
		$customizer->add_setting_name( $customizer->get_setting_name( 'featured_show_images', $section ) );
	}
}