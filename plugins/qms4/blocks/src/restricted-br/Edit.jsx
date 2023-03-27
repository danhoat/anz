import { insert } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';

export function Edit( { isActive, onChange, value, startIndex, endIndex } ) {
	return (
		<Fragment>
			<RichTextToolbarButton
				icon="editor-code"
				title="改行(PC)"
				onClick={ () => {
					const newValue = insert(
						value,
						'[br_pc]',
						startIndex,
						endIndex
					);
					onChange( newValue );
				} }
				isActive={ isActive }
			/>
			<RichTextToolbarButton
				icon="editor-code"
				title="改行(SP)"
				onClick={ () => {
					const newValue = insert(
						value,
						'[br_sp]',
						startIndex,
						endIndex
					);
					onChange( newValue );
				} }
				isActive={ isActive }
			/>
		</Fragment>
	);
}
