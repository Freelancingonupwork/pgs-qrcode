<?php
/**
 * Adds PGS QR Code widget.
 *
 * @package    Pgs_Qrcode
 * @subpackage Pgs_Qrcode/includes
 */

/**
 * Class Pgs_Qrcode_Widget
 */
class Pgs_Qrcode_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$widget_id   = 'pgs_qrcode_widget';
		$widget_name = esc_html__( 'PGS QR Code', 'pgs-qrcode' );
		$widget_ops  = array(
			'classname'                   => 'pgs_qr_code',
			'description'                 => esc_html__( 'QR code generator widget. You can add this widget to sidebar to allow your users get quick access from their devices.', 'pgs-qrcode' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( $widget_id, $widget_name, $widget_ops );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title       = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$size        = ! empty( $instance['size'] ) ? $instance['size'] : 256;
		$qrstyle     = ! empty( $instance['qrstyle'] ) ? $instance['qrstyle'] : 'inline';
		$linktype    = ! empty( $instance['linktype'] ) ? $instance['linktype'] : 'guid';
		$label       = ! empty( $instance['label'] ) ? $instance['label'] : '';
		$description = ! empty( $instance['description'] ) ? $instance['description'] : '';

		/**
		 * Pgs_Qrcode::get_qrcode
		 *
		 * Fetch qrcode image
		 *
		 * Parameters:
		 * size : Image size in pixels.
		 * qr_content : Content to generate QR Code.
		 * post : Post ID if want to generate loink directly
		 * link_type : Link type to generate QR Code based on post.
		 */
		$qr_code = Pgs_Qrcode::get_qrcode( $size, '', 0, $linktype );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$title = apply_filters( 'widget_title', $title );

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		?>
		<div class="pgs-qrcode-wrapper pgs-qrcode-style-<?php echo esc_attr( $qrstyle ); ?>">
			<?php
			if ( 'popup' === $qrstyle ) {
				?>
				<a class="pgs-qrcode-popup-link" href="<?php echo esc_attr( $qr_code ); ?>">
					<div class="pgs-qrcode-popup-link-inner">
				<?php
			}
			if ( 'popup' !== $qrstyle ) {
				?>
				<div class="pgs-qrcode-image">
					<img class="pgs-qrcode" src="<?php echo esc_attr( $qr_code ); ?>" alt="Scan the QR Code." />
				</div>
				<?php
			}
			if ( $label ) {
				?>
					<h5 class="pgs-qrcode-label">
					<?php echo esc_html( $label ); ?>
					</h5>
					<?php
			}
			if ( $description ) {
				?>
					<div class="pgs-qrcode-desc">
						<p><?php echo esc_html( $description ); ?></p>
					</div>
					<?php
			}
			if ( 'popup' === $qrstyle ) {
				?>
					</div>
				</a>
				<?php
			}
			?>

		</div>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title       = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$size        = ! empty( $instance['size'] ) ? $instance['size'] : 256;
		$qrstyle     = ! empty( $instance['qrstyle'] ) ? $instance['qrstyle'] : 'inline';
		$linktype    = ! empty( $instance['linktype'] ) ? $instance['linktype'] : 'guid';
		$label       = ! empty( $instance['label'] ) ? $instance['label'] : '';
		$description = ! empty( $instance['description'] ) ? $instance['description'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'pgs-qrcode' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'qrstyle' ) ); ?>"><?php esc_html_e( 'Style', 'pgs-qrcode' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'qrstyle' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'qrstyle' ) ); ?>">
				<option value="inline" <?php selected( $qrstyle, 'inline' ); ?>><?php esc_html_e( 'Inline', 'pgs-qrcode' ); ?></option>
				<option value="popup" <?php selected( $qrstyle, 'popup' ); ?>><?php esc_html_e( 'Popup', 'pgs-qrcode' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'linktype' ) ); ?>"><?php esc_html_e( 'Link Type', 'pgs-qrcode' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'linktype' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'linktype' ) ); ?>">
				<option value="guid" <?php selected( $linktype, 'guid' ); ?>><?php esc_html_e( 'Post Global Unique Identifier (guid)', 'pgs-qrcode' ); ?></option>
				<option value="permalink" <?php selected( $linktype, 'permalink' ); ?>><?php esc_html_e( 'Permalink', 'pgs-qrcode' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image size' ); ?>:</label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $size ); ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>"><?php esc_html_e( 'Label', 'pgs-qrcode' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>" type="text" name="<?php echo esc_attr( $this->get_field_name( 'label' ) ); ?>" value="<?php echo esc_attr( $label ); ?>">
			<em><?php esc_html_e( 'Enter label to display below QR Code. i.e. Scan the code.', 'pgs-qrcode' ); ?></em>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description', 'pgs-qrcode' ); ?>:</label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"  name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_html( $description ); ?></textarea>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

}
