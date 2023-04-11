import { useCallback, useState } from 'react';
import { Attributes } from './types';

export function useAttributes( _attributes: Attributes ) {
	const [ attributes, _setAttributes ] = useState( _attributes );

	const setAttributes = useCallback(
		( newAttributes: Partial< Attributes > ) => {
			_setAttributes( {
				...attributes,
				...newAttributes,
			} );
		},
		[ attributes ]
	);

	return [ attributes, setAttributes ] as const;
}
