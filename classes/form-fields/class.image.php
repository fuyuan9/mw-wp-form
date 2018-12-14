<?php
/**
 * Name       : MW WP Form Field Image
 * Version    : 2.0.0
 * Author     : Takashi Kitajima
 * Author URI : https://2inc.org
 * Created    : May 17, 2013
 * Modified   : May 30, 2017
 * License    : GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class MW_WP_Form_Field_Image extends MW_WP_Form_Abstract_Form_Field {

	/**
	 * Types of form type.
	 * input|select|button|input_button|error|other
	 * @var string
	 */
	public $type = 'input';

	/**
	 * Set shortcode_name and display_name
	 * Overwrite required for each child class
	 *
	 * @return array(shortcode_name, display_name)
	 */
	protected function set_names() {
		return array(
			'shortcode_name' => 'mwform_image',
			'display_name'   => __( 'Image', 'mw-wp-form' ),
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
		$error = $this->get_error( $this->atts['name'] );
		$valid = is_null( $error );
		$class = apply_filters( 'mwform_form_fields_validation_class', $this->atts['class'], $valid );
		$options = array(
			'id'    => $this->atts['id'],
			'class' => $class,
			'valid' => $valid,
			'error' => $error,
		);
		$_ret = $this->Form->file( $this->atts['name'], $options );
		$value = $this->Data->get_raw( $this->atts['name'] );

		$upload_file_keys = $this->Data->get_post_value_by_key( MWF_Config::UPLOAD_FILE_KEYS );
		if ( ! empty( $value ) && is_array( $upload_file_keys ) && in_array( $this->atts['name'], $upload_file_keys ) ) {
			$filepath = MWF_Functions::fileurl_to_path( $value );
			if ( file_exists( $filepath ) ) {
				$image_holder_html = apply_filters(
					'mwform_form_fields_image_holder_html', 
					'<div class="%s_image"><img src="%s" alt="" />%s</div>'
				);
				$_ret .= sprintf(
					$image_holder_html,
					esc_attr( MWF_Config::NAME ),
					esc_attr( $value ),
					$this->Form->hidden( $this->atts['name'], $value )
				);
			}
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
		$value = $this->Data->get_raw( $this->atts['name'] );
		if ( $value ) {
			$filepath = MWF_Functions::fileurl_to_path( $value );
			if ( file_exists( $filepath ) ) {
				$image_holder_html = apply_filters(
					'mwform_form_fields_image_holder_html', 
					'<div class="%s_image"><img src="%s" alt="" />%s</div>'
				);
				return sprintf(
					$image_holder_html,
					esc_attr( MWF_Config::NAME ),
					esc_attr( $value ),
					$this->Form->hidden( $this->atts['name'], $value )
				);
			}
		}
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
			<strong><?php esc_html_e( 'Display error', 'mw-wp-form' ); ?></strong>
			<?php $show_error = $this->get_value_for_generator( 'show_error', $options ); ?>
			<label><input type="checkbox" name="show_error" value="false" <?php checked( 'false', $show_error ); ?> /> <?php esc_html_e( 'Don\'t display error.', 'mw-wp-form' ); ?></label>
		</p>
		<?php
	}
}
