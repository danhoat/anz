wp.blocks.registerBlockVariation( 'brick/restricted-area', [
  {
    name: 'brick/restricted-area/pc',
    title: 'PC のみ表示',
    description: 'このブロック内のコンテンツは PC で見たときだけ表示されます',
    isDefault: true,
    attributes: {
      screen: 'pc',
    },
    scope: [ 'inserter', 'transform' ],
  },
  {
    name: 'brick/restricted-area/sp',
    title: 'SP のみ表示',
    description: 'このブロック内のコンテンツはスマホで見たときだけ表示されます',
    isDefault: true,
    attributes: {
      screen: 'sp',
    },
    scope: [ 'inserter', 'transform' ],
  },
] );
