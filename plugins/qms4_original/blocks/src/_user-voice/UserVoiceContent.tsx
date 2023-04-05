import React from 'react';
import type { FC } from 'react';
import { RichText } from '@wordpress/block-editor';
import { TextControl, Button } from '@wordpress/components';

interface Props {
	content: string;
	setContent: ( content: string ) => void;
}

export const UserVoiceContent: FC< Props > = ( props ) => {
	const { content, setContent } = props;

	return (
		<div className="qms4__block__user-voice__inner">
			<RichText
				className="qms4__block__user-voice__content"
				tagName="p"
				value={ content }
				onChange={ setContent }
				placeholder="テキストを入力"
			/>
		</div>
	);
};
