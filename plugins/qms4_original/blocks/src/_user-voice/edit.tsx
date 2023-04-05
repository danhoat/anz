import React from 'react';
import { useBlockProps, RichText } from '@wordpress/block-editor';

import { UserVoiceContentWithImage } from './UserVoiceContentWithImage';
import { UserVoiceContentWithYoutube } from './UserVoiceContentWithYoutube';
import { UserVoiceContent } from './UserVoiceContent';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const { title, layout, imageId, imageUrl, youtubeId, youtubeUrl, content } =
		attributes;

	const blockProps = useBlockProps( {
		className: `qms4__block__user-voice qms4__block__user-voice--layout-${ layout }`,
	} );

	return (
		<div { ...blockProps }>
			<RichText
				className="qms4__block__user-voice__title"
				tagName="h2"
				value={ title }
				onChange={ ( title ) => setAttributes( { title } ) }
				placeholder="タイトルを入力"
			/>
			{ ( () => {
				switch ( layout ) {
					case 'image':
						return (
							<UserVoiceContentWithImage
								imageId={ imageId }
								imageUrl={ imageUrl }
								content={ content }
								setImageId={ ( imageId: number ) =>
									setAttributes( { imageId } )
								}
								setImageUrl={ ( imageUrl: string ) =>
									setAttributes( { imageUrl } )
								}
								setContent={ ( content: string ) =>
									setAttributes( { content } )
								}
							/>
						);

					case 'youtube':
						return (
							<UserVoiceContentWithYoutube
								youtubeId={ youtubeId }
								youtubeUrl={ youtubeUrl }
								content={ content }
								setYoutubeId={ ( youtubeId ) =>
									setAttributes( { youtubeId } )
								}
								setYoutubeUrl={ ( youtubeUrl ) =>
									setAttributes( { youtubeUrl } )
								}
								setContent={ ( content ) =>
									setAttributes( { content } )
								}
							/>
						);

					case 'none':
					default:
						return (
							<UserVoiceContent
								content={ content }
								setContent={ ( content ) =>
									setAttributes( { content } )
								}
							/>
						);
				}
			} )() }
		</div>
	);
}
