import React from 'react';
import type { FC, ReactNode } from 'react';
import {
	Button,
	Flex,
	FlexItem,
	Placeholder,
	TextControl,
} from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';

interface Attributes {
	initialized: boolean;
	filepath: string;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
	children: ReactNode;
}

export const Initializer: FC< Props > = ( {
	attributes,
	setAttributes,
	children,
} ) => {
	const { initialized } = attributes;

	const [ filepath, setFilepath ] = useState( attributes.filepath );

	useEffect( () => {
		if ( ! attributes.filepath ) {
			setFilepath( '' );
			setAttributes( { initialized: false } );
		}
	}, [ attributes.filepath ] );

	return initialized ? (
		<>{ children }</>
	) : (
		<Placeholder
			label="インクルードブロック"
			instructions="指定したファイルをインクルードして表示します"
		>
			<Flex justify="flex-start">
				<FlexItem>
					<TextControl
						hideLabelFromVision={ true }
						value={ filepath }
						onChange={ ( filepath ) => setFilepath( filepath ) }
						placeholder="path/to/file.php"
					/>
				</FlexItem>
				<FlexItem>
					<Button
						variant="primary"
						onClick={ () =>
							setAttributes( { filepath, initialized: true } )
						}
					>
						選択
					</Button>
				</FlexItem>
			</Flex>
		</Placeholder>
	);
};
