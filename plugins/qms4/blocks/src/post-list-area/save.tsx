import React from 'react';

export function save( { attributes } ) {
	return <>{ JSON.stringify( { name: 'area', attributes } ) }</>;
}
