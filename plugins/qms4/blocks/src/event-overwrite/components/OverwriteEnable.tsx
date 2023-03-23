import React from 'react';
import type { FC } from 'react';

interface Props {
	className?: string;
	overwriteEnable: boolean;
	toggleOverwriteEnable: () => void;
}

export const OverwriteEnable: FC< Props > = ( {
	className,
	overwriteEnable,
	toggleOverwriteEnable,
} ) => {
	return (
		<div className={ className }>
			<label>
				<input
					type="checkbox"
					checked={ overwriteEnable }
					onChange={ toggleOverwriteEnable }
				/>
				<span>個別設定を有効にする</span>
			</label>
		</div>
	);
};
