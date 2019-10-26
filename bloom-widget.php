<?php

if ( ! defined( 'ABSPATH' ) ) {
	die('not allowed');
	exit; // Exit if accessed directly
}

class BloomWidget extends \WP_Widget
{
	function __construct(){
		$widget_ops = array( 'description' => __( 'Bloom widget, please configure all the settings in Bloom control panel', 'bloom' ) );
		parent::__construct( false, $name = __( 'Bloom', 'bloom' ), $widget_ops );
	}

	/* Displays the Widget in the front-end */
	function widget( $args, $instance ){
		extract($args);

		$title = apply_filters( 'et_bloom_widget_title', empty( $instance['title'] )
			? ''
			: $instance['title']
		);

		$optin_id = $instance['optin_id'];

		echo $before_widget;

		if ( $title ) {
			echo $before_title . esc_html( $title ) . $after_title;
		}

		echo \ET_Bloom::display_widget( $optin_id );

		echo $after_widget;
	}

	/* Saves the settings. */
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['optin_id'] = sanitize_text_field( $new_instance['optin_id'] );

		return $instance;
	}

	/* Creates the form for the widget in the back-end. */
	function form( $instance ){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => esc_html__( 'Subscribe', 'bloom' ), 'optin_id' => 'empty' ) );

		$title = $instance['title'];
		$optin_id_saved = $instance['optin_id'];

		# Title
		printf(
			'<p>
				<label for="%1$s">%2$s: </label>
				<input class="widefat" id="%1$s" name="%4$s" type="text" value="%3$s" />
			</p>',
			attr( $this->getTitle() ),
			__( 'Title', 'bloom' ),
			attr( $title ),
			attr( $this->getFieldTitle() )
		);

		$optins_set = \ET_Bloom::widget_optins_list();
		$optins_formatted = '';
		foreach ( $optins_set as $optin_id => $name ) {
			$optins_formatted .= sprintf(
				'<option value="%1$s" %2$s>%3$s</option>',
				esc_attr( $optin_id ),
				selected( $optin_id, $optin_id_saved, false ),
				esc_html( $name )
			);
		}

		printf(
			'<p>
				<label for="%1$s">%2$s: </label>
				<select class="widefat" id="%1$s" name="%4$s" type="text">%5$s</select>
			</p>',
			attr( $this->optinId() ),
			__( 'Select Optin', 'bloom' ),
			attr( $title ),
			attr( $this->optinId() ),
			$optins_formatted
		);
	}
}
