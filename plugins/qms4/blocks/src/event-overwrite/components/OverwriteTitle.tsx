import React from 'react';
import type { FC } from 'react';
import { TextControl } from '@wordpress/components';

interface Props {
	className?: string;
	parentTitle: string;
	title: string;
	setTitle: ( title: string ) => void;
}

export const OverwriteTitle: FC< Props > = ( {
	className,
	parentTitle,
	title,
	setTitle,
} ) => {
	return (
		<div className={ className }>
			<dl>
				<dt>イベントタイトル</dt>
				<dd>
					<TextControl
						value={ title }
						onChange={ setTitle }
						placeholder={ parentTitle }
					/>
				</dd>
			</dl>
		</div>
	);
};
