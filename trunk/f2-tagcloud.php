<?php
/****************************************************************************\
Plugin Name: F2 Tag Cloud Widget
Plugin URI: http://www.fsquared.co.uk/software/f2-tagcloud/
Version: 0.3.1
Author: fsquared limited
Author URI: http://www.fsquared.co.uk
Licence: GPL2
Description: Tag cloud widget which exposes more of the internal Wordpress tagcloud options.

Copyright(c)2012 fsquared limited. http://www.fsquared.co.uk

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

\****************************************************************************/

/****************************************************************************\
|*                                                                          *|
|* Main Tag Cloud widget class; extends the standard WP_Widget class.       *|
|*                                                                          *|
|* This is a fairly simple widget, derived from the standard WP provided    *|
|* version. However, in this case we expose more of the options available   *|
|* to the wp_tag_cloud() function.                                          *|
|*                                                                          *|
\****************************************************************************/

class F2_Tag_Cloud_Widget extends WP_Widget {

	/************************************************************************\
	|* Tag cloud defaults; used in various functions, thus the class defn.  *|
	\************************************************************************/
	private $m_defaults = array( 
		'title'      => 'Tags',
		'smallest'   => 8,
		'largest'    => 22,
		'number'     => 45,
		'format'     => 'flat',
		'orderby'    => 'name',
		'order'      => 'ASC',
		'alignment'  => 'default',
		'padding'    => '0',
		'taxonomy'   => 'post_tag',
		'exclude'    => null,
		'include'    => null
	);

	/************************************************************************\
	|* Constructor - registers widget to the system.                        *|
	\************************************************************************/
	public function __construct() {

		/* Build the array of widget options. */
		$l_options = array( 'description' => __( 'Tag cloud widget which exposes more the available Wordpress tag cloud options.' ) );

		/* And simply call the parental constructor. */
		parent::__construct(
			'f2-tagcloud',
			__( 'F2 Tag Cloud' ),
			$l_options
		);
	}

	/************************************************************************\
	|* Widget display logic.                                                *|
	\************************************************************************/
	public function widget( $p_args, $p_instance ) {

		/* Expand out the arguments into local variables. */
		extract( $p_args, EXTR_PREFIX_ALL, 'l_args' );

		/* Get the title, applying a sensible default. */
		if ( ! empty( $p_instance['title'] ) ) {
			$l_title = $p_instance['title'];
		} else {
			$l_current_tax = get_taxonomy( 
				$this->_get_current_taxonomy( $p_instance ) );
			$l_title = __( 'Tags' );
			$l_title = $l_current_tax->labels->name;
		}
		$l_title = apply_filters( 'widget_title', $l_title );

		/* And then generate the actual output - header first. */
		echo $l_args_before_widget;
		echo $l_args_before_title . $l_title . $l_args_after_title;

		/* The actual content. */
		$l_tag_params = wp_parse_args( $p_instance, $this->m_defaults );
		$l_tag_params["echo"] = 0;
		$l_tag_cloud_text = wp_tag_cloud( apply_filters('widget_tag_cloud_args', $l_tag_params ) );

		/* Render the tagcloud, applying alignment if required. */
		echo '<div class="tagcloud"';
		if ( 'default' != $l_tag_params["alignment"] ) {
			echo ' style="text-align: ' . $l_tag_params["alignment"] . ';"';
		}
		echo '>';

		/* Output the tagcloud, adding in any padding requirements. */
		echo preg_replace( '/style=\'/', 'style=\'padding: ' . 
			$l_tag_params["padding"] . 'px; ', $l_tag_cloud_text );
		echo '</div>';

		/* And then the footer. */
		echo $l_args_after_widget;
	}

	/************************************************************************\
	|* Widget setup form.                                                   *|
	\************************************************************************/
	public function form( $p_instance ) {

		/* And parse in any that we've been passed. */
		$l_instance = wp_parse_args( $p_instance, $this->m_defaults );

		/* Now render the form. */
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'title' ) . '">' .
			__( 'Title:' ) . '</label>';
		echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) .
			'" name="' . $this->get_field_name( 'title' ) . '" type="text" ' .
			'value="' . esc_attr( $l_instance['title'] ) . '" />';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'smallest' ) . '">' .
			__( 'Minimum tag size:' ) . '</label>';
		echo '<input class="widefat" id="' . $this->get_field_id( 'smallest' ) .
			'" name="' . $this->get_field_name( 'smallest' ) . 
			'" type="text" ' . 'value="' . 
			esc_attr( $l_instance['smallest'] ) . '" />';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'largest' ) . '">' .
			__( 'Maximum tag size:' ) . '</label>';
		echo '<input class="widefat" id="' . $this->get_field_id( 'largest' ) .
			'" name="' . $this->get_field_name( 'largest' ) . '" type="text" ' .
			'value="' . esc_attr( $l_instance['largest'] ) . '" />';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'number' ) . '">' .
			__( 'Maximum tag count (0 for no limit):' ) . '</label>';
		echo '<input class="widefat" id="' . $this->get_field_id( 'number' ) .
			'" name="' . $this->get_field_name( 'number' ) . '" type="text" ' .
			'value="' . esc_attr( $l_instance['number'] ) . '" />';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'format' ) . '">' .
			__( 'Tag cloud format:' ) . '</label>';
		echo '<select class="widefat" id="' . $this->get_field_id( 'format' ) . 
			'" name="' . $this->get_field_name( 'format' ) . '">';
		echo '<option ' . selected( 'flat', $l_instance['format'], false ) .
			' value="flat">flat</option>';
		echo '<option ' . selected( 'list', $l_instance['format'],false ) .
			' value="list">list</option>';
		echo '</select>';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'orderby' ) . '">' .
			__( 'Order tags by:' ) . '</label>';
		echo '<select class="widefat" id="' . 
			$this->get_field_id( 'orderby' ) .  '" name="' . 
			$this->get_field_name( 'orderby' ) . '">';
		echo '<option ' . selected( 'name', $l_instance['orderby'], false ) .
			' value="name">name</option>';
		echo '<option ' . selected( 'count', $l_instance['orderby'], false ) .
			' value="count">count</option>';
		echo '</select>';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'order' ) . '">' .
			__( 'Tag order direction:' ) . '</label>';
		echo '<select class="widefat" id="' . $this->get_field_id( 'order' ) . 
			'" name="' . $this->get_field_name( 'order' ) . '">';
		echo '<option ' . selected( 'ASC', $l_instance['order'], false ) .
			' value="ASC">ascending</option>';
		echo '<option ' . selected( 'DESC', $l_instance['order'], false ) .
			' value="DESC">descending</option>';
		echo '<option ' . selected( 'RAND', $l_instance['order'], false ) .
			' value="RAND">random</option>';
		echo '</select>';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'alignment' ) . '">' .
			__( 'Tag cloud alignment:' ) . '</label>';
		echo '<select class="widefat" id="' . $this->get_field_id( 'alignment' ) . 
			'" name="' . $this->get_field_name( 'alignment' ) . '">';
		echo '<option ' . selected( 'default', $l_instance['alignment'], false ) .
			' value="default">theme default</option>';
		echo '<option ' . selected( 'left', $l_instance['alignment'], false ) .
			' value="left">left</option>';
		echo '<option ' . selected( 'center', $l_instance['alignment'], false ) .
			' value="center">center</option>';
		echo '<option ' . selected( 'right', $l_instance['alignment'], false ) .
			' value="right">right</option>';
		echo '</select>';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'padding' ) . '">' .
			__( 'Padding between tags:' ) . '</label>';
		echo '<input class="widefat" id="' . $this->get_field_id( 'padding' ) .
			'" name="' . $this->get_field_name( 'padding' ) . '" type="text" ' .
			'value="' . esc_attr( $l_instance['padding'] ) . '" />';
		echo '</p>';

		$l_current_tax = $this->_get_current_taxonomy( $p_instance );
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'taxonomy' ) . '">' .
			__( 'Taxonomy:' ) . '</label>';
		echo '<select class="widefat" id="' . 
			$this->get_field_id( 'taxonomy' ) . '" name="' . 
			$this->get_field_name( 'taxonomy' ) . '">';
		foreach( get_taxonomies() as $l_taxonomy ) {
			$l_tax = get_taxonomy( $l_taxonomy );
			if ( !$l_tax->show_tagcloud || empty( $l_tax->labels->name ) ) {
				continue;
			}
			echo '<option ' . selected( $l_taxonomy, $l_current_tax, false ) .
				' value="' . esc_attr( $l_taxonomy ) . '">' .
				$l_tax->labels->name . '</option>';
		}
		echo '</select>';
		echo '</p>';
	}

	/************************************************************************\
	|* Form input sanitiser.                                                *|
	\************************************************************************/
	public function update( $p_new_instance, $p_old_instance ) {

		/* Construct valid values, based on user inputs. */
		$l_instance['title'] = strip_tags( stripslashes( $p_new_instance['title'] ) );

		/* Numeric ones; make sure they are numeric! */
		if ( is_numeric( $p_new_instance['smallest'] ) ) {
			/* Force it to be a number. */
			$l_instance['smallest'] = $p_new_instance['smallest'] + 0;
		} else {
			/* Return to the original value. */
			$l_instance['smallest'] = $p_old_instance['smallest'] + 0;
		}

		if ( is_numeric( $p_new_instance['largest'] ) ) {
			/* Force it to be a number. */
			$l_instance['largest'] = $p_new_instance['largest'] + 0;
		} else {
			/* Return to the original value. */
			$l_instance['largest'] = $p_old_instance['largest'] + 0;
		}

		if ( is_numeric( $p_new_instance['number'] ) ) {
			/* Force it to be a number. */
			$l_instance['number'] = $p_new_instance['number'] + 0;
		} else {
			/* Return to the original value. */
			$l_instance['number'] = $p_old_instance['number'] + 0;
		}

		/* Select options next. */
		if ( 'flat' == $p_new_instance['format'] ) {
			$l_instance['format'] = 'flat';
		} else if ( 'list' == $p_new_instance['format'] ) {
			$l_instance['format'] = 'list';
		} else {
			$l_instance['format'] = $p_old_instance['format'];
		}

		if ( 'name' == $p_new_instance['orderby'] ) {
			$l_instance['orderby'] = 'name';
		} else if ( 'count' == $p_new_instance['orderby'] ) {
			$l_instance['orderby'] = 'count';
		} else {
			$l_instance['orderby'] = $p_old_instance['orderby'];
		}

		if ( 'ASC' == $p_new_instance['order'] ) {
			$l_instance['order'] = 'ASC';
		} else if ( 'DESC' == $p_new_instance['order'] ) {
			$l_instance['order'] = 'DESC';
		} else if ( 'RAND' == $p_new_instance['order'] ) {
			$l_instance['order'] = 'RAND';
		} else {
			$l_instance['order'] = $p_old_instance['order'];
		}

		if ( 'default' == $p_new_instance['alignment'] ) {
			$l_instance['default'] = 'default';
		} else if ( 'left' == $p_new_instance['alignment'] ) {
			$l_instance['alignment'] = 'left';
		} else if ( 'center' == $p_new_instance['alignment'] ) {
			$l_instance['alignment'] = 'center';
		} else if ( 'right' == $p_new_instance['alignment'] ) {
			$l_instance['alignment'] = 'right';
		} else {
			$l_instance['alignment'] = $p_old_instance['alignment'];
		}

		if ( is_numeric( $p_new_instance['padding'] ) ) {
			/* Force it to be a number. */
			$l_instance['padding'] = $p_new_instance['padding'] + 0;
		} else {
			/* Return to the original value. */
			$l_instance['padding'] = $p_old_instance['padding'] + 0;
		}

		if ( "" != 
			get_taxonomy( stripslashes( $p_new_instance['taxonomy'] ) ) ) {
			$l_instance['taxonomy'] = 
				stripslashes( $p_new_instance['taxonomy'] );
		}
/*
			'unit'       => 'pt',
			'separator'  => "\n",
			'exclude'    => null,
			'include'    => null,
			'link'       => 'view'
*/
		/* Return the freshly built list of options. */
		return $l_instance;
	}

	/************************************************************************\
	|* Fetch current taxonomy.                                              *|
	\************************************************************************/
	function _get_current_taxonomy( $p_instance ) {

		/* Return the instance taxonomy if we have one. */
		if ( !empty( $p_instance['taxonomy'] ) && 
			 taxonomy_exists( $p_instance['taxonomy'] ) ) {
			return $p_instance['taxonomy'];
		}

		/* Otherwise, return the default. */
		return $this->m_defaults['taxonomy'];
	}
}

/* Last step, add this widget into the init action. */
add_action( 'widgets_init', create_function( '', 'register_widget( "F2_Tag_Cloud_Widget" );' ) );
?>
