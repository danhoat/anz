<?php

namespace QMS4\Block\Renderer;

use QMS4\Item\Post\Post;
use QMS4\Item\Term\Terms;


class PostListItemRenderer
{
	/** @var    \stdClass[] */
	private $template;

	/** @var    string|null */
	private $post_date_format = null;

	/** @var    string|null */
	private $post_modified_format = null;

	/**
	 * @param    \stdClass[]    $template
	 */
	public function __construct( array $template )
	{
		$this->template = $template;
	}

	/**
	 * @param    string    $ndjson
	 * @return    self
	 */
	public static function from_ndjson( string $ndjson ): PostListItemRenderer
	{
		$lines = explode( "\n", $ndjson );

		$template = array();
		foreach ( $lines as $line ) {
			if ( trim( $line ) == false ) { continue; }
			$template[] = json_decode( $line );
		}

		return new self( $template );
	}

	public static function from_block_instance_array( array $block_instances ): PostListItemRenderer
	{
		$json_strs = array();
		foreach ( $block_instances as $block_instance ) {
			$json_strs[] = $block_instance[ 'originalContent' ];
		}
		return self::from_ndjson( join( "\n", $json_strs ) );
	}

	// ====================================================================== //

	/**
	 * @param    Post    $item
	 * @return    string
	 */
	public function render( Post $item ): string
	{
		$htmls = array();
		foreach ( $this->template as $part ) {
			switch ( $part->name ) {
				case 'area':
					$htmls[] = $this->render_area( $item, $part->attributes );
					break;

				case 'html':
					$htmls[] = $this->render_html( $item, $part->attributes );
					break;

				case 'post-author':
					$htmls[] = $this->render_post_author( $item, $part->attributes );
					break;

				case 'post-date':
					$htmls[] = $this->render_post_date( $item, $part->attributes );
					break;

				case 'post-excerpt':
					$htmls[] = $this->render_post_excerpt( $item, $part->attributes );
					break;

				case 'post-modified':
					$htmls[] = $this->render_post_modified( $item, $part->attributes );
					break;

				case 'post-thumbnail':
					$htmls[] = $this->render_post_thumbnail( $item, $part->attributes );
					break;

				case 'post-title':
					$htmls[] = $this->render_post_title( $item, $part->attributes );
					break;

				case 'terms':
					$htmls[] = $this->render_terms( $item, $part->attributes );
					break;

			}
		}

		return join( "\n", $htmls );
	}

	// ====================================================================== //

	/**
	 * @param    Post    $item
	 * @param    \stdClass    $attributes
	 * @return    string
	 */
	private function render_area( Post $item, \stdClass $attributes ): string
	{
		$color = $attributes->color ?? null;

		$area = $item->area;

		if ( empty( $area ) ) { return ''; }

		return trim( '
			<ul class="qms4__post-list__area">
				<li class="qms4__post-list__area__icon">' . $area->title . '</li>
			</ul>
		' );
	}

	/**
	 * @param    Post    $item
	 * @param    \stdClass    $attributes
	 * @return    string
	 */
	private function render_html( Post $item, \stdClass $attributes ): string
	{
		global $post;

		$_post = $post;
		$post = $item->wp_post;

		$content = do_shortcode( $attributes->content );

		$post = $_post;

		return '<div class="qms4__post-list__html">' . $content . '</div>';
	}

	/**
	 * @param    Post    $item
	 * @param    \stdClass    $attributes
	 * @return    string
	 */
	private function render_post_author( Post $item, \stdClass $attributes ): string
	{
		return trim( '
			<div class="qms4__post-list__post-author">' . $item->author->display_name . '</div>
		' );
	}

	/**
	 * @param    Post    $item
	 * @param    \stdClass    $attributes
	 * @return    string
	 */
	private function render_post_date( Post $item, \stdClass $attributes ): string
	{
		if ( is_null( $this->post_date_format ) ) {
			$format_strs = array_filter( array(
				@$attributes->showDate ? get_option( 'date_format' ) : '',
				@$attributes->showTime ? get_option( 'time_format' ) : '',
			) );
			$format = $this->post_date_format = join( ' ', $format_strs );
		} else {
			$format = $this->post_date_format;
		}
		///$format ='Y年m月d日 l';
		//echo $item->post_date(' D');
		//時間
		$show_time = @$attributes->showTime;
		$show_date = @$attributes->showDate;
		$custom_style = $item->custom_style;

		$css = 'item-post-type-'.$item->post_type;

		$html = ' <div class="qms4__post-list__post-date '.$css.'">';

		$df_post_date = $item->post_date;
		if( $show_date ){

			if($item->post_type == 'fair'){


				if(!empty($item->event_date) ){
					$time_stamp = $item->event_time_stamp;
					$html.='<p class="card_date">';

					if($custom_style == 'flat_style'){
					 	$html.='<span class="ym"> ' . wp_date( 'y.m', $time_stamp ).' </span>
						<span class="day"> ' . wp_date( 'd', $time_stamp ).' </span>
						<span class="week"> ' . wp_date( 'l', $time_stamp ) . ' </span>';

					} else{
						//$html.=wp_date('m月d日', $time_stamp).' <span>'.wp_date('l', $time_stamp).'</span>';
						$html.=wp_date($item->date_format, $time_stamp);
					}
				}

			} else {
				$html.=$item->post_date().' <span>'.$item->post_date('l').'</span>';
			}
			$html.='</p>';
		}
		if($show_time){
			if($item->custom_style == 'recommend_style'){
				$qms4__timetable = get_post_meta($item->ID, 'qms4__timetable', true);
				$from = $to = '';
				if( is_array($qms4__timetable)){
					$soon_time = array_shift($qms4__timetable);
					$late_time = array_pop($qms4__timetable);
					$from 	= $soon_time['label'];
					$to 	= isset($late_time['label']) ? ' ~ '.$late_time['label'] : '';
				}
				$html.= '<span class="time"> 時間: <span class="time-around">'.$from.$to.' </span></span>';
			} else{
				$html.= '<span class="time"> '. $item->post_date( 'h:m' ).'</span>';
			}
		}
		$item->post_date = $df_post_date;

		$html.='</div>';

		return $html;
	}

	/**
	 * @param    Post    $item
	 * @param    \stdClass    $attributes
	 * @return    string
	 */
	private function render_post_excerpt( Post $item, \stdClass $attributes ): string
	{
		if ( trim( $item->excerpt ) == false ) { return ''; }
		$excerpt =  $item->excerpt ;

		if($item->excerpt_lenght){
			$exerpt = wp_trim_words($excerpt,$item->excerpt_lenght );
		}
		return trim( '
			<div
				class="qms4__post-list__post-excerpt"
				title="' . esc_attr( $exerpt ) . '"
			>' . $exerpt . '</div>
		' );
	}

	/**
	 * @param    Post    $item
	 * @param    \stdClass    $attributes
	 * @return    string
	 */
	private function render_post_modified( Post $item, \stdClass $attributes ): string
	{
		if ( is_null( $this->post_modified_format ) ) {
			$format_strs = array_filter( array(
				$attributes->showDate ? get_option( 'date_format' ) : '',
				$attributes->showTime ? get_option( 'time_format' ) : '',
			) );
			$format = $this->post_modified_format = join( ' ', $format_strs );
		} else {
			$format = $this->post_modified_format;
		}

		return trim( '
			<div class="qms4__post-list__post-modified">
				' . $item->post_modified( $format ) . '
			</div>
		' );
	}

	/**
	 * @param    Post    $item
	 * @param    \stdClass    $attributes
	 * @return    string
	 */
	private function render_post_thumbnail( Post $item, \stdClass $attributes ): string
	{
		$aspect_ratio = $attributes->aspectRatio;
		$object_fit = $attributes->objectFit;

		return trim( '
			<div
				class="qms4__post-list__post-thumbnail"
				data-aspect-ratio="' . $aspect_ratio . '"
				data-object-fit="' . $object_fit . '"
			>' . $item->img . '</div>
		' );
	}

	/**
	 * @param    Post    $item
	 * @param    \stdClass    $attributes
	 * @return    string
	 */
	private function render_post_title( Post $item, \stdClass $attributes ): string
	{
		if ( trim( $item->title ) == false ) { return ''; }

		$text_align = $attributes->textAlign;
		$num_lines_pc = $attributes->numLinesPc;
		$num_lines_sp = $attributes->numLinesSp;

		return trim( '
			<div
				class="qms4__post-list__post-title"
				data-text-align="' . $text_align . '"
				data-num-lines-pc="' . $num_lines_pc . '"
				data-num-lines-sp="' . $num_lines_sp . '"
				title="' . esc_attr( $item->title ) . '"
			>' . $item->title . '</div>
		' );
	}

	/**
	 * @param    Post    $item
	 * @param    \stdClass    $attributes
	 * @return    string
	 */
	private function render_terms( Post $item, \stdClass $attributes ): string
	{
		$taxonomy = $attributes->taxonomy;
		$color = $attributes->color ?? null;

		$terms = $item->$taxonomy;

		if ( ! ( $terms instanceof Terms ) ) { return ''; }
		if ( count( $terms ) === 0 ) { return ''; }

		$lis = array();
		foreach ( $terms as $term ) {
			if ( $color === 'none' ) {
				$style = '';
			} elseif ( $color === 'text' ) {
				$style = $term->color ? "color:{$term->color};border-color:{$term->color};background-color:transparent" : '';
			} elseif ( $color === 'background' ) {
				$style = $term->color ? "border-color:{$term->color};background-color:{$term->color}" : '';
			}

			$lis[] = '<li class="qms4__post-list__terms__icon" style="' . $style . '">' . esc_html( $term->name ) . '</li>';
		}

		return '<ul class="qms4__post-list__terms qms4__post-list__terms--taxonomy-' . esc_attr( $taxonomy ) . '">' . join( '', $lis ) . '</ul>';
	}
}
