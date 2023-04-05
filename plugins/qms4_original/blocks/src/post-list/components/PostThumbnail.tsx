import React from 'react';
import type { FC } from 'react';

interface Attributes {
	aspectRatio: '4:3' | '3:2' | '16:9' | '1:1' | '9:16' | '2:3' | '3:4';
	objectFit: 'cover' | 'contain';
}

interface Props {
	attributes: Attributes;
}

export const PostThumbnail: FC< Props > = ( { attributes } ) => {
	const { aspectRatio, objectFit } = attributes;

	return (
		<div
			className="qms4__post-list__post-thumbnail"
			data-aspect-ratio={ aspectRatio }
			data-object-fit={ objectFit }
		>
			<img src="https://picsum.photos/id/905/400/300/" alt="" />
		</div>
	);
};
