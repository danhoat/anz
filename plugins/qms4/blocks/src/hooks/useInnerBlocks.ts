import { type BlockInstance } from '@wordpress/blocks';
import { useEffect } from '@wordpress/element';
import { useDispatch, useSelect } from '@wordpress/data';

export function useInnerBlocks(
	clientId: string,
	createInnerBlocks: (
		attributes: readonly any[],
		blocks: BlockInstance[]
	) => BlockInstance[] | void,
	attributes: readonly any[]
): void {
	const blocks: BlockInstance[] = useSelect( ( select ) =>
		select( 'core/block-editor' ).getBlocks( clientId )
	);

	const replaceInnerBlocks =
		useDispatch( 'core/block-editor' ).replaceInnerBlocks;

	useEffect( () => {
		const innerBlocks = createInnerBlocks( attributes, blocks );
		innerBlocks && replaceInnerBlocks( clientId, innerBlocks );
	}, attributes );
}
