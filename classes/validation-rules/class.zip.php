<?php
/**
 * Name       : MW WP Form Validation Rule Zip
 * Description: 値が郵便番号
 * Version    : 1.1.3
 * Author     : Takashi Kitajima
 * Author URI : https://2inc.org
 * Created    : July 21, 2014
 * Modified   : September 23, 2015
 * License    : GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class MW_WP_Form_Validation_Rule_Zip extends MW_WP_Form_Abstract_Validation_Rule {

	/**
	 * バリデーションルール名を指定
	 * @var string
	 */
	protected $name = 'zip';

	/**
	 * バリデーションチェック
	 *
	 * @param string $key name属性
	 * @param array $option
	 * @return string エラーメッセージ
	 */
	public function rule( $key, array $options = array() ) {
		$value = $this->Data->get( $key );

		if ( MWF_Functions::is_empty( $value ) ) {
			return;
		}

		$defaults = array(
			'message' => __( 'This is not the format of a zip code.', 'mw-wp-form' )
		);
		$options = array_merge( $defaults, $options );
		if ( preg_match( '/^\d{3}-\d{4}$/', $value )
			|| preg_match( '/^\d{7}$/', $value ) ) {

			return;
		}

		return $options['message'];
	}

	/**
	 * 設定パネルに追加
	 *
	 * @param numeric $key バリデーションルールセットの識別番号
	 * @param array $value バリデーションルールセットの内容
	 */
	public function admin( $key, $value ) {
		?>
		<label><input type="checkbox" <?php checked( $value[ $this->getName() ], 1 ); ?> name="<?php echo MWF_Config::NAME; ?>[validation][<?php echo $key; ?>][<?php echo esc_attr( $this->getName() ); ?>]" value="1" /><?php esc_html_e( 'Zip Code', 'mw-wp-form' ); ?></label>
		<?php
	}

	/**
	 * @param array $validation_rules MW_WP_Form_Abstract_Validation_Rule を継承したオブジェクトの一覧
	 * @return array $validation_rules
	 */
	public function _mwform_validation_rules( array $validation_rules ) {
		$validation_rules = parent::_mwform_validation_rules( $validation_rules );

		if ( 'ja' === get_locale() ) {
			return $validation_rules;
		}

		$validation_rules[ $this->getName() ] = '';
		return $validation_rules;
	}
}
