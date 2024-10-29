<?php
/**
 * Plugin Name: Auktionsscroller widget till Tradera
 * Plugin URI: http://www.auktionsscroller.se/widget
 * Description: Visar och l&auml;nkar till en traderas&auml;ljares auktioner
 * Version: 1.0.0
 * Author: Johan Groth
 * Author URI: https://plus.google.com/100397919438423325494/about
 *
 */

/**
 * @since 1.0
 */
add_action( 'widgets_init', 'load_widgets' );

/**
 * 'Auktionsscroller_Widget' is the widget class used below.
 *
 * @since 1.0
 */
function load_widgets() {
	register_widget( 'Auktionsscroller_Widget' );
}

function genRandomString() {
$length = 5;
$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

//$real_string_length = strlen($characters) – 1;
$string="id_";

for ($p = 0; $p < $length; $p++) {
$string .= $characters[mt_rand(0, strlen($characters)-1)];
}
return $string;
}
/**
 * Auktionsscroller Widget class.
 *
 * @since 1.0
 */
class Auktionsscroller_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Auktionsscroller_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'auktionsscroller', 'description' => __('Visar och länkar till en traderasäljares auktioner på Tradera.', 'auktionsscroller') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'auktionsscroller_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'auktionsscroller_widget', __('Auktionsscroller widget', 'auktionsscroller'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$ftg = $instance['ftg'];
		$riktning = $instance['riktning'];
		$nrofauctions = $instance['nrofauctions'];
                $alias = $instance['alias'];
		$vertikalscroll=false;

		$scrollonoff = isset( $instance['scrollonoff'] ) ? $instance['scrollonoff'] : 'off';


                if ($scrollonoff=='on') {
                  $scrollonoff=1;
                }
                else {
                  $scrollonoff=0;
                }
		if ($riktning=='Vertikal'){
			$vertikalscroll=1;
		}
		else {
			$vertikalscroll=0;
		}

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		$unique = genRandomString();

		echo '<script>';
		echo 'var '.$unique.'userid='.$ftg.';';
		echo 'var '.$unique.'visibleauctions='.$nrofauctions.';';
		echo 'var '.$unique.'verticalscroll='.$vertikalscroll.';';
		echo 'var '.$unique.'scrolling='.$scrollonoff.';';
                echo 'var '.$unique.'alias="'.urlencode($alias).'";';
		echo 'var '.$unique.'description=0;';
		echo '</script>';
		echo '<script src="http://www.auktionsscroller.se/widget/script.php?uid='.$unique.'" type="text/javascript"></script>';
		echo '<div id="'.$unique.'scroller-background">';
		    echo '<div id="'.$unique.'makeMeScrollable" class="'.$unique.'makeMeScrollableclass">';
		    echo '</div>';
		echo '</div>';

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and ftg to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['ftg'] = strip_tags( $new_instance['ftg'] );
                $instance['alias'] = strip_tags( $new_instance['alias'] );
		$instance['nrofauctions'] = strip_tags( $new_instance['nrofauctions'] );

		/* No need to strip tags for sex and show_sex. */
		$instance['riktning'] = $new_instance['riktning'];
		$instance['scrollonoff'] = $new_instance['scrollonoff'];

		return $instance;
	}

	/**
	 *
	 *
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Mina auktioner:', 'auktionsscroller'), 'ftg' => __('', 'auktionsscroller'), 'alias' => __('', 'auktionsscroller'), 'nrofauctions' => __('3', 'auktionsscroller'), 'riktning' => 'horisontell', 'scrollonoff' => 'on' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Rubrik ovanf&ouml;r scrollern:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<!-- Ditt ftg: Säljarens ftg-nummer -->
		<p>
			<label for="<?php echo $this->get_field_id( 'ftg' ); ?>"><?php _e('Ditt ftg-nummer:', 'auktionsscroller'); ?></label>
			<input id="<?php echo $this->get_field_id( 'ftg' ); ?>" name="<?php echo $this->get_field_name( 'ftg' ); ?>" value="<?php echo $instance['ftg']; ?>" style="width:100%;" />
		</p>
		<!-- Ditt ftg: Säljarens alias -->
		<p>
			<label for="<?php echo $this->get_field_id( 'alias' ); ?>"><?php _e('Ditt alias:', 'auktionsscroller'); ?></label>
			<input id="<?php echo $this->get_field_id( 'alias' ); ?>" name="<?php echo $this->get_field_name( 'alias' ); ?>" value="<?php echo $instance['alias']; ?>" style="width:100%;" />
		</p>
		<!-- Bredden/höjden på scrollern(antal auktioner) -->
		<p>
			<label for="<?php echo $this->get_field_id( 'nrofauctions' ); ?>"><?php _e('Hur m&aring;nga auktioner i taget:', 'auktionsscroller'); ?></label>
			<input id="<?php echo $this->get_field_id( 'nrofauctions' ); ?>" name="<?php echo $this->get_field_name( 'nrofauctions' ); ?>" value="<?php echo $instance['nrofauctions']; ?>" style="width:100%;" />
		</p>

		<!-- vertikal/horisontell: Select Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'riktning' ); ?>"><?php _e('Horisontell eller vertikal scrollning:', 'auktionsscroller'); ?></label>
			<select id="<?php echo $this->get_field_id( 'riktning' ); ?>" name="<?php echo $this->get_field_name( 'riktning' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'Vertikal' == $instance['riktning'] ) echo 'selected="selected"'; ?>>Vertikal</option>
				<option <?php if ( 'Horisontell' == $instance['riktning'] ) echo 'selected="selected"'; ?>>Horisontell</option>
			</select>
		</p>

		<!-- Scrolling on/off Checkbox -->
		<p>
                  	<input class="checkbox" type="checkbox" <?php checked( $instance['scrollonoff'], 'on' ); ?> id="<?php echo $this->get_field_id( 'scrollonoff' ); ?>" name="<?php echo $this->get_field_name( 'scrollonoff' ); ?>" />

			<label for="<?php echo $this->get_field_id( 'scrollonoff' ); ?>"><?php _e('Animerad scroller.', 'auktionsscroller'); ?></label>
		</p>


	<?php
	}
}

?>