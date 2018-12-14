<?php
/**
 * Name       : MW WP Form Field Select
 * Version    : 2.0.0
 * Author     : Takashi Kitajima
 * Author URI : https://2inc.org
 * Created    : December 14, 2012
 * Modified   : May 30, 2017
 * License    : GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class MW_WP_Form_Field_Select extends MW_WP_Form_Abstract_Form_Field {

	/**
	 * Types of form type.
	 * input|select|button|input_button|error|other
	 * @var string
	 */
	public $type = 'select';

	/**
	 * Set shortcode_name and display_name
	 * Overwrite required for each child class
	 *
	 * @return array(shortcode_name, display_name)
	 */
	protected function set_names() {
		return array(
			'shortcode_name' => 'mwform_select',
			'display_name'   => __( 'Select', 'mw-wp-form' ),
		);
	}

	/**
	 * Set default attributes
	 *
	 * @return array defaults
	 */
	protected function set_defaults() {
		return array(
			'name'         => '',
			'id'           => null,
			'class'        => null,
			'children'     => '',
			'value'        => '',
			'post_raw'     => 'false',
			'show_error'   => 'true',
			'custom_error' => 'false',
		);
	}

	/**
	 * Callback of add shortcode for input page
	 *
	 * @param array $atts
	 * @param string $element_content
	 * @return string HTML
	 */
	protected function input_page() {
		$value = $this->Data->get_raw( $this->atts['name'] );
		if ( is_null( $value ) ) {
			$value = $this->atts['value'];
		}
		$children = $this->get_children( $this->atts['children'] );

		$error = $this->get_error( $this->atts['name'] );
		$valid = is_null( $error );
		$class = apply_filters( 'mwform_form_fields_validation_class', $this->atts['class'], $valid );
		$options =array(
			'id'    => $this->atts['id'],
			'class' => $class,
			'value' => $value,
			'valid' => $valid,
			'error' => $error,
		);
		$_ret = $this->Form->select( $this->atts['name'], $children, $options );
		if ( 'false' === $this->atts['post_raw'] ) {
			$_ret .= $this->Form->children( $this->atts['name'], $children );
		}
		if ( 'false' !== $this->atts['show_error'] && 'true' !== $this->atts['custom_error'] ) {
			$_ret .= $error;
		}
		return $_ret;
	}

	/**
	 * Callback of add shortcode for confirm page
	 *
	 * @param array $atts
	 * @param string $element_content
	 * @return string HTML
	 */
	protected function confirm_page() {
		$children     = $this->get_children( $this->atts['children'] );
		$value        = $this->Data->get( $this->atts['name'], $children );
		$posted_value = $this->Data->get_raw( $this->atts['name'] );
		$_ret         = esc_html( $value );
		$_ret        .= $this->Form->hidden( $this->atts['name'], $posted_value );
		if ( 'false' === $this->atts['post_raw'] ) {
			$_ret .= $this->Form->children( $this->atts['name'], $children );
		}
		return $_ret;
	}

	/**
	 * Display tag generator dialog
	 * Overwrite required for each child class
	 *
	 * @param array $options
	 * @return void
	 */
	public function mwform_tag_generator_dialog( array $options = array() ) {
		?>
		<p>
			<strong>name<span class="mwf_require">*</span></strong>
			<?php $name = $this->get_value_for_generator( 'name', $options ); ?>
			<input type="text" name="name" value="<?php echo esc_attr( $name ); ?>" />
		</p>
		<p>
			<strong>id</strong>
			<?php $id = $this->get_value_for_generator( 'id', $options ); ?>
			<input type="text" name="id" value="<?php echo esc_attr( $id ); ?>" />
		</p>
		<p>
			<strong>class</strong>
			<?php $class = $this->get_value_for_generator( 'class', $options ); ?>
			<input type="text" name="class" value="<?php echo esc_attr( $class ); ?>" />
		</p>
		<p>
			<strong><?php esc_html_e( 'Choices', 'mw-wp-form' ); ?><span class="mwf_require">*</span></strong>
			<?php $children = "\n" . $this->get_value_for_generator( 'children', $options ); ?>
			<textarea name="children"><?php echo esc_attr( $children ); ?></textarea>
			<span class="mwf_note">
				<?php esc_html_e( 'Input one line about one item.', 'mw-wp-form' ); ?>
				<?php esc_html_e( 'Example: value1&crarr;value2 or key1:value1&crarr;key2:value2', 'mw-wp-form' ); ?><br />
				<?php esc_html_e( 'You can split the post value and display value by ":". But display value is sent in e-mail.', 'mw-wp-form' ); ?><br />
				<?php esc_html_e( 'When you want to use ":", please enter "::".', 'mw-wp-form' ); ?>
			</span>
		</p>
		<p>
			<strong><?php esc_html_e( 'Send value by e-mail', 'mw-wp-form' ); ?></strong>
			<?php $value = $this->get_value_for_generator( 'value', $options ); ?>
			<?php $post_raw = $this->get_value_for_generator( 'post_raw', $options ); ?>
			<label><input type="checkbox" name="post_raw" value="true" <?php checked( 'true', $post_raw ); ?> /> <?php esc_html_e( 'Send post value when you split the post value and display value by ":" in choices.', 'mw-wp-form' ); ?></label>
		</p>
		<p>
			<strong><?php esc_html_e( 'Default value', 'mw-wp-form' ); ?></strong>
			<?php $value = $this->get_value_for_generator( 'value', $options ); ?>
			<input type="text" name="value" value="<?php echo esc_attr( $value ); ?>" />
		</p>
		<p>
			<strong><?php esc_html_e( 'Display error', 'mw-wp-form' ); ?></strong>
			<?php $show_error = $this->get_value_for_generator( 'show_error', $options ); ?>
			<label><input type="checkbox" name="show_error" value="false" <?php checked( 'false', $show_error ); ?> /> <?php esc_html_e( 'Don\'t display error.', 'mw-wp-form' ); ?></label>
		</p>
		<?php
	}
}
