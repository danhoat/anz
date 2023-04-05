import React from 'react';
import type { FC } from 'react';
import { RichText } from '@wordpress/block-editor';
import { TextControl, Button } from '@wordpress/components';

interface Props {
	youtubeId: string | null;
	youtubeUrl: string;
	content: string;
	setYoutubeId: ( youtubeId: string | null ) => void;
	setYoutubeUrl: ( youtubeUrl: string ) => void;
	setContent: ( content: string ) => void;
}

export const UserVoiceContentWithYoutube: FC< Props > = ( props ) => {
	const {
		youtubeId,
		youtubeUrl,
		content,
		setYoutubeId,
		setYoutubeUrl,
		setContent,
	} = props;

	return (
		<div className="qms4__block__user-voice__inner">
			<div className="qms4__block__user-voice__column-left">
				<IFrameYoutube
					youtubeId={ youtubeId }
					youtubeUrl={ youtubeUrl }
					setYoutubeId={ setYoutubeId }
					setYoutubeUrl={ setYoutubeUrl }
				/>
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

interface IFrameYoutubeProps {
	youtubeId: string | null;
	youtubeUrl: string;
	setYoutubeId: ( youtubeId: string | null ) => void;
	setYoutubeUrl: ( youtubeUrl: string ) => void;
}

const IFrameYoutube: FC< IFrameYoutubeProps > = ( props ) => {
	const { youtubeId, youtubeUrl, setYoutubeId, setYoutubeUrl } = props;

	const onChange = ( youtubeUrl: string ) => {
		const youtubeId = extractYoutubeId( youtubeUrl );
		youtubeId ?? setYoutubeId( youtubeId );
		setYoutubeUrl( youtubeUrl );
	};

	return youtubeId ? (
		<>
			<iframe
				src={ `https://www.youtube.com/embed/${ youtubeId }` }
				allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
			></iframe>
			<Button onClick={ () => setYoutubeId( null ) }>リセット</Button>
		</>
	) : (
		<div className="qms4__block__user-voice__youtube-frame">
			<TextControl
				className="qms4__block__user-voice__youtube-frame__inner"
				label="YouTube URL を入力"
				value={ youtubeUrl }
				onChange={ onChange }
			/>
		</div>
	);
};

function extractYoutubeId( youtubeUrl: string ): string | null {
	let matches: RegExpMatchArray | null;

	matches = youtubeUrl.match( /\?.*?v=(?<id>[a-zA-Z0-9_\-]+)/u );
	if ( matches && matches.groups && matches.groups.id ) {
		return matches.groups.id;
	}

	matches = youtubeUrl.match( /\/youtu\.be\/(?<id>[a-zA-Z0-9_\-]+)/u );
	if ( matches && matches.groups && matches.groups.id ) {
		return matches.groups.id;
	}

	matches = youtubeUrl.match( /\/embed\/(?<id>[a-zA-Z0-9_\-]+)/u );
	if ( matches && matches.groups && matches.groups.id ) {
		return matches.groups.id;
	}

	return null;
}
