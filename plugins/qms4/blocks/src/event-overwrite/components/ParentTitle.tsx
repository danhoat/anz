import React from 'react';
import type { FC } from 'react';

interface Props {
	className?: string;
	title: string;
}

export const ParentTitle: FC< Props > = ( { className, title } ) => {
	return (
		<div className={ className }>
			<dl>
				<dt>イベントタイトル</dt>
				<dd>{ title }</dd>
			</dl>
		</div>
	);
};
