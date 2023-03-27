import { insert } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';

export function Edit( { isActive, onChange, value, startIndex, endIndex } ) {
	return (
		<Fragment>
			<RichTextToolbarButton
				icon="editor-code"
				title="投稿タイトル"
				onClick={ () => {
					const newValue = insert(
						value,
						'[post_title]',
						startIndex,
						endIndex
					);
					onChange( newValue );
				} }
				isActive={ isActive }
			/>
			<RichTextToolbarButton
				icon="editor-code"
				title="投稿日時"
				onClick={ () => {
					const newValue = insert(
						value,
						'[post_date]',
						startIndex,
						endIndex
					);
					onChange( newValue );
				} }
				isActive={ isActive }
			/>
			<RichTextToolbarButton
				icon="editor-code"
				title="更新日時"
				onClick={ () => {
					const newValue = insert(
						value,
						'[post_modified]',
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
