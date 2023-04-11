import React from 'react';
import type { FC } from 'react';
import type { BlockInstance } from '@wordpress/blocks';
import { Area } from './Area';
import { Html } from './Html';
import { PostAuthor } from './PostAuthor';
import { PostDate } from './PostDate';
import { PostExcerpt } from './PostExcerpt';
import { PostModified } from './PostModified';
import { PostThumbnail } from './PostThumbnail';
import { PostTitle } from './PostTitle';
import { Terms } from './Terms';

interface Props {
	blocks: BlockInstance[];
}

export const Item: FC< Props > = ( { blocks } ) => {
	return (
		<>
			{ blocks.map( ( block ) =>
				( () => {
					switch ( block.name ) {
						case 'qms4/post-list-area':
							return <Area attributes={ block.attributes } />;
						case 'qms4/post-list-html':
							return <Html attributes={ block.attributes } />;
						case 'qms4/post-list-post-author':
							return (
								<PostAuthor attributes={ block.attributes } />
							);
						case 'qms4/post-list-post-date':
							return <PostDate attributes={ block.attributes } />;
						case 'qms4/post-list-post-excerpt':
							return (
								<PostExcerpt attributes={ block.attributes } />
							);
						case 'qms4/post-list-post-modified':
							return (
								<PostModified attributes={ block.attributes } />
							);
						case 'qms4/post-list-post-thumbnail':
							return (
								<PostThumbnail
									attributes={ block.attributes }
								/>
							);
						case 'qms4/post-list-post-title':
							return (
								<PostTitle attributes={ block.attributes } />
							);
						case 'qms4/post-list-terms':
							return <Terms attributes={ block.attributes } />;
						default:
							return null;
					}
				} )()
			) }
		</>
	);
};
