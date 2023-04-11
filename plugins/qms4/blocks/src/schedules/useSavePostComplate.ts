import { select, subscribe } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';

type Callback = ( didPostSaveRequestSucceed: boolean | null ) => void;

/**
 * @see { @link https://github.com/WordPress/gutenberg/issues/17632#issuecomment-1153888435 }
 * @see { @link https://github.com/WordPress/gutenberg/blob/26c8b7149091a315a0da09544cc74cfdc5fbd9c3/docs/reference-guides/data/data-core-editor.md#ispostsavinglocked }
 * @see { @link https://github.com/WordPress/gutenberg/blob/26c8b7149091a315a0da09544cc74cfdc5fbd9c3/docs/reference-guides/data/data-core-editor.md#didpostsaverequestsucceed }
 *
 * @param effect
 */
export function useSavePostComplate( effect: Callback ) {
	const [ isPostSavingLocked, setPostSavingLocked ] = useState( false );
	const [ didPostSaveRequestSucceed, setPostSaveRequestSucceed ] = useState<
		boolean | null
	>( null );

	subscribe( () => {
		const _isPostSavingLocked =
			select( 'core/editor' ).isPostSavingLocked< boolean >();

		if ( _isPostSavingLocked != isPostSavingLocked ) {
			setPostSavingLocked( _isPostSavingLocked );
		}

		if ( ! _isPostSavingLocked ) {
			const _didPostSaveRequestSucceed =
				select( 'core/editor' ).didPostSaveRequestSucceed< boolean >();
			setPostSaveRequestSucceed( _didPostSaveRequestSucceed );
		}
	} );

	useEffect( () => {
		if ( ! isPostSavingLocked ) {
			effect( didPostSaveRequestSucceed );
		}
	}, [ isPostSavingLocked ] );
}
