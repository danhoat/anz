<?php
/**
 * Plugin Name: Advanced Post Block
 * Description: Advanced Post Block - Display posts in a beautiful way!
 * Version: 1.8.3
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: advanced-post-block
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'AP_BLOCK_PLUGIN_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.8.3' );
define( 'AP_BLOCK_ASSETS_DIR', plugin_dir_url( __FILE__ ) . 'assets/' );

// Advanced Post Block
class APBAdvancedPostBlock{
	function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'wp_loaded', [$this, 'onLoaded'] );
		add_action( 'rest_api_init', [$this, 'customRestAPI'] );
		// add_filter( 'excerpt_more', [$this, 'excerptMore'] );
		register_activation_hook( __FILE__, [$this, 'onPluginActivate'] );

		if ( version_compare( $GLOBALS['wp_version'], '5.8-alpha-1', '<' ) ) {
			add_filter( 'block_categories', [$this, 'blockCategories'] );
		} else { add_filter( 'block_categories_all', [$this, 'blockCategories'] ); }
	}

	function onPluginActivate(){
		if ( is_plugin_active( 'advanced-post-block-pro/plugin.php' ) ){
			deactivate_plugins( 'advanced-post-block-pro/plugin.php' );
		}
	}

	function enqueueBlockAssets(){
		wp_enqueue_script( 'swiperJS', AP_BLOCK_ASSETS_DIR . 'js/swiper-bundle.min.js', [], '7.0.3', true );
		wp_enqueue_script( 'easyTicker', AP_BLOCK_ASSETS_DIR . 'js/easy-ticker.min.js', [], '3.2.1', true );
	}

	function blockCategories( $categories ){
		return array_merge( [ [
			'slug'	=> 'APBlock',
			'title'	=> 'Advanced Post Block'
		] ], $categories );
	} // Categories

	function onLoaded(){
		wp_register_style( 'ap-block-posts-editor-style', plugins_url( 'dist/editor.css', __FILE__ ), [ 'wp-edit-blocks' ], AP_BLOCK_PLUGIN_VERSION ); // Backend Style
		wp_register_style( 'ap-block-posts-style', plugins_url( 'dist/style.css', __FILE__ ), [ 'dashicons' ], AP_BLOCK_PLUGIN_VERSION ); // Frontend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'ap-block-posts-editor-style',
			'style'				=> 'ap-block-posts-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'ap-block-posts-editor-script', 'advanced-post-block', plugin_dir_path( __FILE__ ) . 'languages' ); // Translate
	} // Register

	function render( $attributes ) {
		extract( $attributes );

		$className = $className ?? '';
		$apbBlockClassName = 'wp-block-ap-block-posts apbAdvancedPosts ' . $className . ' align' . $align;

		// All Posts
		$defaultPostFilter = 'post' === $postType ? [
			'category__in'	=> $selectedCategories
		] : [];

		$posts = get_posts( array_merge( [
			'post_type'			=> 'fair',
			'posts_per_page'	=> $isPostsPerPageAll ? -1 : $postsPerPage,
			'orderby'			=> $postsOrderBy,
			'order'				=> $postsOrder
		], $defaultPostFilter ) );

		ob_start(); ?>
		<div class='<?php echo esc_attr( $apbBlockClassName ); ?>' id='apbAdvancedPosts-<?php echo esc_attr( $cId ); ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'>
			<div class='apbStyle'></div>
			<style>
				<?php foreach ( $posts as $post ) {
					$imgUrl = preg_replace( '/<[^>]+>/', '', get_the_post_thumbnail_url( $post->ID, 'large' ) );
					$displayCSS = $isFImg && $imgUrl ? 'grid' : 'flex';

					$sideImgCSS = "#apbAdvancedPosts-$cId .apbPostSideImage.apbPost-$post->ID{ display: $displayCSS; }";
					$fImgCSS = $isFImg && $imgUrl ? "#apbAdvancedPosts-$cId .apbPostOverlay.apbPost-$post->ID, #apbAdvancedPosts-$cId .apbPost .apbPostFImg-$post->ID{ background-image: url( $imgUrl ); }" : '';

					echo esc_html( $sideImgCSS . $fImgCSS );
				} ?>
			</style>

			<?php if( 'grid' === $layout ){ ?>
				<div class='apbGridPosts columns-<?php echo esc_attr( $columns['desktop'] ); ?> columns-tablet-<?php echo esc_attr( $columns['tablet'] ); ?> columns-mobile-<?php echo esc_attr( $columns['mobile'] ); ?>'>
					<?php echo $this->foreachPosts( $attributes, $posts ); ?>
				</div>
			<?php }else if( 'masonry' === $layout ){ ?>
				<div class='apbMasonryPosts cols-<?php echo esc_attr( $columns['desktop'] ); ?> cols-tablet-<?php echo esc_attr( $columns['tablet'] ); ?> cols-mobile-<?php echo esc_attr( $columns['mobile'] ); ?>'>
					<?php $this->foreachPosts( $attributes, $posts ); ?>
				</div>
			<?php }else if ( 'slider' === $layout ){ ?>
				<div class='apbSliderPosts'>
					<div class='swiper-wrapper'>
						<?php $this->foreachPosts( $attributes, $posts ); ?>
					</div>

					<?php echo $sliderIsPage ? "<div class='swiper-pagination'></div>" : ''; ?>
					<?php echo $sliderIsPrevNext ? "<div class='swiper-button-prev'></div><div class='swiper-button-next'></div>" : ''; ?>
				</div>
			<?php }else if ( 'ticker' === $layout ){ ?>
				<div class='apbTickerPosts'>
					<div>
						<?php $this->foreachPosts( $attributes, $posts ); ?>
					</div>
				</div>
			<?php }else{ echo ''; } ?>
		</div>
		<?php return ob_get_clean();
	} // Render


	// ForEach Posts
	function foreachPosts( $attributes, $posts ){
		extract( $attributes );

		foreach ( $posts as $post ) {
			if ( 'default' === $subLayout || 'title-meta' === $subLayout ) {
				echo $this->defaultLayout( $attributes, $post );
			} else if ( 'left-image' === $subLayout || 'right-image' === $subLayout ) {
				echo $this->sideImgLayout( $attributes, $post );
			} else if ( 'overlay-content' === $subLayout || 'overlay-content-hover' === $subLayout || 'overlay-box' === $subLayout ) {
				echo $this->overlayLayout( $attributes, $post );
			} else { ?><p><?php _e( 'Please, select a sub layout', 'advanced-post-block' ); ?></p><?php }
		}
	}

	// Layout Components
	function defaultLayout( $attributes, $post ) {
		extract( $attributes );

		$titleMetaFilter = 'title-meta' !== $subLayout ? $this->postExcerpt( $attributes, $post ) . $this->postReadMore( $attributes, $post ) : '';

		ob_start(); ?>
		<article class='apbPost apbPost-<?php echo esc_attr( $post->ID ); ?> apbPostDefault <?php echo 'slider' === $layout ? 'swiper-slide' : ''; ?>'>
			<?php echo $this->postFeatureImg( $attributes, $post ); ?>

			<div class='apbPostText'>
				<?php echo $this->postTitle( $attributes, $post ) . $this->postMetaData( $attributes, $post ) . $titleMetaFilter; ?>
			</div>
		</article>
		<?php return ob_get_clean();
	} // Default

	function sideImgLayout( $attributes, $post ) {
		extract( $attributes );

		ob_start(); ?>
		<article class='apbPost apbPost-<?php echo esc_attr( $post->ID ); ?> apbPostSideImage <?php echo 'left-image' === $subLayout ? 'leftImage' : ( 'right-image' === $subLayout ? 'rightImage' : '' ); ?> <?php echo 'slider' === $layout ? 'swiper-slide' : ''; ?>'>
			<?php echo 'left-image' === $subLayout ? $this->postFeatureImg( $attributes, $post ) : ''; ?>

			<div class='apbPostText'>
				<?php echo $this->postTitle( $attributes, $post ) . $this->postMetaData( $attributes, $post ) . $this->postExcerpt( $attributes, $post ) . $this->postReadMore( $attributes, $post ); ?>
			</div>

			<?php echo 'right-image' === $subLayout ? $this->postFeatureImg( $attributes, $post ) : ''; ?>
		</article>
		<?php return ob_get_clean();
	} // Side Image

	function overlayLayout( $attributes, $post ) {
		extract( $attributes );

		$imgUrl = get_the_post_thumbnail_url( $post->ID );

		ob_start(); ?>
		<article class='apbPost apbPost-<?php echo esc_attr( $post->ID ); ?> apbPostOverlay <?php echo 'overlay-content-hover' === $subLayout && $imgUrl ? 'apbPostOverlayHover' : ''; ?> <?php echo 'overlay-box' === $subLayout ? 'apbPostOverlayBox' : ''; ?> <?php echo 'slider' === $layout ? 'swiper-slide' : ''; ?>'>
			<div class='apbPostText'>
				<?php echo $this->postTitle( $attributes, $post ) . $this->postMetaData( $attributes, $post ); ?>

				<?php echo 'overlay-box' !== $subLayout ? $this->postExcerpt( $attributes, $post ) . $this->postReadMore( $attributes, $post ) : ''; ?>
			</div>
		</article>
		<?php return ob_get_clean();
	} // Overlay

	// Single Components
	function postFeatureImg( $attributes, $post ) {
		extract( $attributes );

		$imgUrl = get_the_post_thumbnail_url( $post->ID );
		$tab = $isLinkNewTab ? '_blank' : '_self';

		if( $isFImg && $imgUrl ){
			ob_start(); ?>
			<figure class='apbPostFImg apbPostFImg-<?php echo esc_attr( $post->ID ); ?>'>
				<?php echo $isFImgLink ? "<a href=". esc_url( get_permalink( $post ) ) ." target='$tab' rel='noreferrer'></a>" : ''; ?>

				<?php echo $isMeta && $isMetaCategory && 'image' === $metaCategoryIn ? "<div class='apbPostFImgCats'>". get_the_category_list( ' ', '', $post->ID ) ."</div>" : ''; ?>
			</figure>
		<?php return ob_get_clean();
		}else{
			return '';
		}
	} // Feature Image

	function postTitle( $attributes, $post ) {
		extract( $attributes );

		$tab = $isLinkNewTab ? '_blank' : '_self';

		if ( $isTitle ) {
			ob_start(); ?>
			<h2 class='apbPostTitle'>
				<?php echo $isTitleLink ? "<a href=". esc_url( get_permalink( $post ) ) ." target='$tab' rel='noreferrer'>$post->post_title</a>" : $post->post_title; ?>
			</h2>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Title

	function postMetaData( $attributes, $post ) {
		extract( $attributes );

		if ( $isMeta ) {
			ob_start(); ?>
			<div class='apbPostMeta'>
				<?php echo $this->metaAuthor( $attributes, $post ) . $this->metaDate( $attributes, $post ) . $this->metaCategories( $attributes, $post ) . $this->metaComment( $attributes, $post ); ?>
			</div>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Data

	function metaAuthor( $attributes, $post ) {
		extract( $attributes );

		if ( $isMetaAuthor ) {
			ob_start(); ?>
			<span>
				<span class='dashicon dashicons dashicons-admin-users'></span>
				<span><a href='<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>' rel="author"><?php echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?></a></span>
			</span>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Author
	function metaDate( $attributes, $post ) {
		extract( $attributes );

		if ( $isMetaDate ) {
			ob_start(); ?>
			<span>
				<span class='dashicon dashicons dashicons-calendar'></span>
				<span><?php echo get_the_date( 'M j, Y', $post->ID ); ?></span>
			</span>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Date
	function metaCategories( $attributes, $post ) {
		extract( $attributes );

		if ( $isMetaCategory && 'content' === $metaCategoryIn ) {
			ob_start(); ?>
			<span>
				<span class='dashicon dashicons dashicons-category'></span>
				<span><?php echo get_the_category_list( esc_html__( ', ' ), '', $post->ID ); ?></span>
			</span>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Categories
	function metaComment( $attributes, $post ) {
		extract( $attributes );

		if ( $isMetaComment ) {
			ob_start(); ?>
			<span>
				<span class='dashicon dashicons dashicons-admin-comments'></span>
				<a href='<?php echo esc_url( get_permalink( $post ) ); ?>/#comments' target='_blank' rel='noreferrer'><?php echo wp_count_comments( $post->ID )->total_comments; ?></a>
			</span>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Comment

	function postExcerpt( $attributes, $post ) {
		extract( $attributes );

		$excerptContent = get_the_excerpt( $post->ID );

		if ( $isExcerpt ) {
			ob_start(); ?>
			<div class='apbPostExcerpt apbInnerContent'>
				<?php echo implode( ' ', array_slice(
					explode( ' ', wp_kses( preg_replace('/(<([^>]+)>)/', '', $excerptContent), [] ) ),
				0, $excerptLength ) ); ?>

				<p class='read-more'>
					<a href='<?php echo esc_url( get_permalink( $post ) ); ?>'><?php echo __( 'Read More &raquo;', 'advanced-post-block' ); ?></a>
				</p>
			</div>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Excerpt

	function postReadMore( $attributes, $post ) {
		extract( $attributes );

		$tab = $isLinkNewTab ? '_blank' : '_self';

		if ( $isReadMore ) {
			ob_start(); ?>
			<div class='apbPostReadMore'>
				<a href='<?php echo esc_url( get_permalink( $post ) ); ?>' target='<?php echo esc_attr( $tab ); ?>' rel='noreferrer'><?php echo esc_html( $readMoreLabel ); ?></a>
			</div>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Read More

	function post_types() {
		$post_types = get_post_types( [
			'public'		=> true,
			'show_in_rest'	=> true,
		], 'objects' );

		$options = [];
		foreach ( $post_types as $post_type ) {
			if ( 'product' === $post_type->name ) { continue; }
			if ( 'attachment' === $post_type->name ) { continue; }
			if ( 'page' === $post_type->name ) { continue; }

			$options[] = [
				'value'	=> $post_type->name,
				'label'	=> $post_type->label
			];
		}
		return $options;
	} // Post Types

	function customRestAPI() {
		$post_type = $this->post_types();
		foreach ( $post_type as $key => $value ) {
			register_rest_field( $value['value'], 'wbAuthor', [
				'get_callback'	=> function ( $obj ) {
					$author['name'] = get_the_author_meta( 'display_name', isset( $obj['author'] ) ? $obj['author'] : '' );
					$author['link'] = get_author_posts_url( isset( $obj['author'] ) ? $obj['author'] : '' );
					return $author;
				},
				'schema'	=> [
					'description'	=> __( 'Author name and link', 'advanced-post-block' ),
					'type'			=> 'string'
				]
			] );

			register_rest_field( $value['value'], 'wbDate', [
				'get_callback'	=> function ( $obj ) {
					return get_the_date( 'M j, Y', $obj['id'] );
				},
				'schema'	=> [
					'description'	=> __( 'Author name and link', 'advanced-post-block' ),
					'type'			=> 'string'
				]
			] );

			register_rest_field( $value['value'], 'wbCategories', [
				'get_callback'	=> function ( $obj ) {
					$catsLink['space'] = get_the_category_list( esc_html__( ' ' ), '', $obj['id'] );
					$catsLink['coma'] = get_the_category_list( esc_html__( ', ' ), '', $obj['id'] );
					return $catsLink;
				},
				'schema'	=> [
					'description'	=> __( 'Category link lists', 'advanced-post-block' ),
					'type'			=> 'string'
				]
			] );

			register_rest_field( $value['value'], 'wbComment', [
				'get_callback'	=> function ( $obj ) {
					return wp_count_comments( $obj['id'] )->total_comments;
				},
				'schema'	=> [
					'description'	=> __( 'Comment', 'advanced-post-block' ),
					'type'			=> 'number'
				]
			] );
		}
	} // Custom rest

	function excerptMore( $more ) {
		return "<p class='read-more'><a href=". esc_url( get_permalink( get_the_ID() ) ) .">". __( 'Read More &raquo;', 'b-blocks' ) ."</a></p>";
	} // Excerpt More
}
new APBAdvancedPostBlock;