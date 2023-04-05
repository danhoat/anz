import React from 'react';
import type { FC } from 'react';
import {
	RichText,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

interface Props {
	imageId: number;
	imageUrl: string;
	content: string;
	setImageId: ( imageId: number ) => void;
	setImageUrl: ( imageUrl: string ) => void;
	setContent: ( content: string ) => void;
}

export const UserVoiceContentWithImage: FC< Props > = ( props ) => {
	const { imageId, imageUrl, content, setImageId, setImageUrl, setContent } =
		props;

	return (
		<div className="qms4__block__user-voice__inner">
			<div className="qms4__block__user-voice__column-left">
				<div className="qms4__block__user-voice__image-frame">
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ ( media ) => {
								setImageId( media.id );
								setImageUrl( media.url );
							} }
							allowedTypes={ [ 'image' ] }
							value={ imageId }
							render={ ( { open } ) => (
								<ButtonSelect
									open={ open }
									imageId={ imageId }
									imageUrl={ imageUrl }
								/>
							) }
						/>
					</MediaUploadCheck>
					{ imageId != 0 && (
						<MediaUploadCheck>
							<button
								className="qms4__block__user-voice__remove-image"
								onClick={ () => {
									setImageId( 0 );
									setImageUrl( '' );
								} }
								title="リセット"
								aria-label="リセット"
							>
								×
							</button>
						</MediaUploadCheck>
					) }
				</div>
			</div>
			<div className="qms4__block__user-voice__column-right">
				<RichText
					className="qms4__block__user-voice__content"
					tagName="p"
					value={ content }
					onChange={ setContent }
					placeholder="テキストを入力"
				/>
			</div>
		</div>
	);
};

interface ButtonSelectProps {
	open: () => void;
	imageId: number;
	imageUrl: string;
}

const ButtonSelect: FC< ButtonSelectProps > = ( props ) => {
	const { open, imageId, imageUrl } = props;

	return imageId == 0 ? (
		<button
			className="qms4__block__user-voice__button-select"
			onClick={ open }
		>
			<div
				className="
				button
				button-large
				qms4__block__user-voice__button-select__inner
			"
			>
				画像を選択
			</div>
		</button>
	) : (
		<button
			className="
			qms4__block__user-voice__button-select
			qms4__block__user-voice__button-select--selected
		"
			onClick={ open }
		>
			<img src={ imageUrl } alt="" />
		</button>
	);
};
