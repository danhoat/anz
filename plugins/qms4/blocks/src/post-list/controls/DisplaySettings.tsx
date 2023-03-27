import React from 'react';
import type { FC } from 'react';
import { PanelBody, RangeControl, TabPanel } from '@wordpress/components';
import {
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';

import type { Attributes } from '../Attributes';

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const DisplaySettings: FC< Props > = ( {
	attributes,
	setAttributes,
} ) => {
	const { numColumnsPc, numColumnsSp, numPostsPc, numPostsSp } = attributes;

	return (
		<TabPanel
			tabs={ [
				{
					title: 'PC 表示',
					name: 'pc',
					content() {
						return (
							<PanelBody>
								<ToggleGroupControl
									label="カラム数"
									value={ numColumnsPc }
									onChange={ ( numColumnsPc ) =>
										setAttributes( { numColumnsPc } )
									}
									isBlock
								>
									<ToggleGroupControlOption
										value={ 1 }
										label="1"
									/>
									<ToggleGroupControlOption
										value={ 2 }
										label="2"
									/>
									<ToggleGroupControlOption
										value={ 3 }
										label="3"
									/>
									<ToggleGroupControlOption
										value={ 4 }
										label="4"
									/>
									<ToggleGroupControlOption
										value={ 5 }
										label="5"
									/>
								</ToggleGroupControl>

								<RangeControl
									label="表示数"
									initialPosition={ 3 }
									min={ 1 }
									max={ 30 }
									value={ numPostsPc }
									onChange={ ( numPostsPc ) =>
										setAttributes( { numPostsPc } )
									}
								/>
							</PanelBody>
						);
					},
				},
				{
					title: 'SP 表示',
					name: 'sp',
					content() {
						return (
							<PanelBody>
								<ToggleGroupControl
									label="カラム数"
									value={ numColumnsSp }
									onChange={ ( numColumnsSp ) =>
										setAttributes( { numColumnsSp } )
									}
									isBlock
								>
									<ToggleGroupControlOption
										value={ 1 }
										label="1"
									/>
									<ToggleGroupControlOption
										value={ 2 }
										label="2"
									/>
								</ToggleGroupControl>

								<RangeControl
									label="表示数"
									initialPosition={ 3 }
									min={ 1 }
									max={ 30 }
									value={ numPostsSp }
									onChange={ ( numPostsSp ) =>
										setAttributes( { numPostsSp } )
									}
								/>
							</PanelBody>
						);
					},
				},
			] }
		>
			{ ( tab ) => tab.content() }
		</TabPanel>
	);
};
