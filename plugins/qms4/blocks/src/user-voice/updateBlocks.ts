import { createBlock, type BlockInstance } from '@wordpress/blocks';
import className from 'classnames';

const defaultHeading = () =>
	createBlock( 'core/heading', {
		className: 'qms4__block__user-voice__heading',
		textAlign: 'center',
		content: 'お客様の声',
		level: 2,
		placeholder: 'タイトルを入力',
		style: {
			color: {
				text: '#98ba58',
			},
		},
	} );

const defaultContent = () =>
	createBlock(
		'qms4/user-voice-content',
		{
			className: 'qms4__block__user-voice__content',
		},
		[
			createBlock( 'core/paragraph', {
				content:
					'テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト',
			} ),
		]
	);

export function toLayoutNone( blocks: BlockInstance[] ) {
	const heading = find( 'core/heading', blocks ) ?? defaultHeading();
	const content =
		find( 'qms4/user-voice-content', blocks ) ?? defaultContent();

	return [ heading, content ];
}

export function toLayoutImage( blocks: BlockInstance[] ) {
	// console.log( { blocks } );

	const heading = find( 'core/heading', blocks ) ?? defaultHeading();
	const media = find( 'qms4/user-voice-media', blocks ) ?? defaultContent();
	const content =
		find( 'qms4/user-voice-content', blocks ) ?? defaultContent();

	console.log( { media } );

	return [
		heading,
		createBlock( 'core/columns', { isStackedOnMobile: true }, [
			createBlock(
				'core/column',
				{
					className: 'qms4__block__user-voice__column-media',
					width: '33.33%',
				},
				[
					media.attributes.type === 'image'
						? media
						: createBlock( 'qms4/user-voice-media', {
								className: className(
									'qms4__block__user-voice__media',
									'qms4__block__user-voice__media--type-image'
								),
								type: 'image',
						  } ),
				]
			),
			createBlock(
				'core/column',
				{
					className: 'qms4__block__user-voice__column-content',
					width: '66.66%',
				},
				[ content ]
			),
		] ),
	];
}

export function toLayoutEmbed( blocks: BlockInstance[] ) {
	const heading = find( 'core/heading', blocks ) ?? defaultHeading();
	const media = find( 'qms4/user-voice-media', blocks ) ?? defaultContent();
	const content =
		find( 'qms4/user-voice-content', blocks ) ?? defaultContent();

	return [
		heading,
		createBlock( 'core/columns', { isStackedOnMobile: true }, [
			createBlock(
				'core/column',
				{
					className: 'qms4__block__user-voice__column-media',
					width: '33.33%',
				},
				[
					media.attributes.type === 'embed'
						? media
						: createBlock( 'qms4/user-voice-media', {
								className: className(
									'qms4__block__user-voice__media',
									'qms4__block__user-voice__media--type-embed'
								),
								type: 'embed',
						  } ),
				]
			),
			createBlock(
				'core/column',
				{
					className: 'qms4__block__user-voice__column-content',
					width: '66.66%',
				},
				[ content ]
			),
		] ),
	];
}

function find( name: string, blocks: BlockInstance[] ): BlockInstance | null {
	for ( const block of blocks ) {
		if ( block.name == name ) {
			return block;
		}

		const b = find( name, block.innerBlocks );
		if ( b ) {
			return b;
		}
	}

	return null;
}
