<?php
namespace GV\Widgets;
/**
 * Widget to display page size
 *
 * @since 2.1
 *
 * @extends GV\Widget
 */
class Page_Size extends \GV\Widget {

	/**
	 * Does this get displayed on a single entry?
	 * @var boolean
	 */
	protected $show_on_single = false;

	function __construct() {

		$this->widget_description = __( 'Allow users to modify the number of results shown per page.', 'gravityview' );

		$default_values = array(
			'header' => 1,
			'footer' => 1,
		);

		$settings = array();

		if ( ! $this->is_registered() ) {
			add_action( 'gravityview/view/get', array( $this, 'override_view_page_size' ) );
		}

		parent::__construct( __( 'Page Size', 'gravityview' ), 'page_size', $default_values, $settings );
	}

	/**
	 * Get an array of page sizes.
	 *
	 * @param \GV\Template_Context|string $context The context, if available
	 *
	 * @return array The page sizes in an array with `value` and `text` keys.
	 */
	public static function get_page_sizes( $context ) {

		$default_size = 25;

		if ( $context instanceof \GV\Template_Context ) {
			$default_size = (int) $context->view->settings->get( 'page_size' );
		}

		$sizes = array( 10, 25, $default_size, 50, 100 );

		$sizes = array_unique( array_filter( $sizes ) );

		sort( $sizes );

		$page_sizes = array();
		foreach ( $sizes as $size ) {
			$page_sizes [] = array(
				'value' => $size,
				'text'  => $size
			);
		}

		/**
		 * @filter `gravityview/widgets/page_size/page_sizes` Filter the available page sizes as needed
		 * @param[in,out] array $sizes The sizes, with `value` and `text` keys. `text` key used as HTML option label.
		 * @param \GV\Context $context The context.
		 */
		$page_sizes = apply_filters( 'gravityview/widgets/page_size/page_sizes', $page_sizes, $context );

		return $page_sizes;
	}

	/**
	 * Render the page size widget
     *
	 * @param array $widget_args The Widget shortcode args.
	 * @param string $content The content.
	 * @param string|\GV\Template_Context $context The context, if available.
	 */
	public function render_frontend( $widget_args, $content = '', $context = null ) {

		$search_field = array(
			'label'   => __( 'Page Size', 'gravityview' ),
			'choices' => self::get_page_sizes( $context ),
			'value'   => (int) \GV\Utils::_GET( 'page_size', $context->view->settings->get( 'page_size' ) ),
		);

		$default_option = __( 'Results Per Page', 'gravityview' );

		?>
        <div class="gv-widget-page-size">
            <label for="gv-page_size"><?php echo esc_html( $search_field['label'] ); ?></label>
            <form method="get" action="" onchange="this.submit();">
                <div>
                    <select name="page_size" id="gv-page_size">
                        <option value="" <?php gv_selected( '', $search_field['value'], true ); ?>><?php echo esc_html( $default_option ); ?></option>
						<?php
						foreach ( $search_field['choices'] as $choice ) { ?>
                            <option value='<?php echo esc_attr( $choice['value'] ); ?>'<?php gv_selected( esc_attr( $choice['value'] ), esc_attr( $search_field['value'] ), true ); ?>><?php echo esc_html( $choice['text'] ); ?></option>
						<?php } ?>
                    </select>
                    <input type="submit" value="Submit" style="display: none"/>
                </div>
            </form>
        </div>
		<?php
	}

	/**
	 * Override the View settings and inject the needed page size.
	 *
	 * This might be too early, seeing that there's lack of full context, but we should
	 * be fine for now.
	 *
	 * @param \GV\View $view The View.
	 */
	public function override_view_page_size( &$view ) {

		if ( ! $view->widgets->by_id( 'page_size' )->count() ) {
			return;
		}

		$page_size = \GV\Utils::_GET( 'page_size' );

		if ( empty( $page_size ) ) {
			return;
		}

		// Already overridden
		if ( (int) $page_size === (int) $view->settings->get( 'page_size' ) ) {
			return;
		}

		$context = \GV\Template_Context::from_template( array(
			'view' => $view,
		) );

		if ( ! in_array( (int) $page_size, wp_list_pluck( self::get_page_sizes( $context ), 'value' ), true ) ) {
			gravityview()->log->warning( 'The passed page size is not allowed: {page_size}. Not modifying result.', array( 'page_size' => $page_size ) );
			return;
		}

		$view->settings->update( array( 'page_size' => $page_size ) );
	}
}

new Page_Size;
