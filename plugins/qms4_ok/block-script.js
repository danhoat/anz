console.log( 'block-script.js' );

( function () {
	wp.blocks.registerBlockVariation( 'core/group', [
		{
			name: 'title-box',
			title: 'タイトル付きボックス',
			description:
				'コアブロックで構成されるタイトル付きのボックスコンテンツ',
			icon: 'wordpress',
			attributes: {
				className: 'title-box',
			},
			innerBlocks: [
				[
					'core/paragraph',
					{
						className: 'title-box__title',
						placeholder: 'タイトルをここに入力...',
					},
				],
				[
					'core/group',
					{
						className: 'title-box__body',
					},
					[
						[
							'core/paragraph',
							{
								placeholder: 'コンテンツをここに入力...',
							},
						],
					],
				],
			],
			scope: [ 'inserter' ],
			keywords: [ 'title-box' ],
		},
	] );
} )();
