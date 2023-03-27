import React from 'react';

export function save( { attributes } ) {
	return <>{ JSON.stringify( { name: 'post-author', attributes } ) }</>;
}
