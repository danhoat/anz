import { useBlockProps, InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { TextControl, TabPanel, PanelBody } from '@wordpress/components';
import { WPElement } from '@wordpress/element';
import { SelectBrickControl } from '../components/SelectBrickControl';
import { useBricks } from '../hooks/useBricks';
import { FormBlock } from '../components/FormBlock';

import './editor.scss';


export function Edit({ attributes, setAttributes }): WPElement {
  const { postId, thanksPath, pcThanksPath, spThanksPath } = attributes;

  const bricks = useBricks();

  if ( postId == null && bricks.length ) {
    setAttributes({ postId: bricks[0].id });
  }

  const brick = bricks.find(brick => brick.id == postId);

  const tabs = [
    {
      title: '基本設定',
      name: 'basic',
      content: (
        <PanelBody>
          <TextControl
            label="サンクスページ URL"
            value={ thanksPath }
            onChange={ thanksPath => setAttributes({ thanksPath }) }
          />
        </PanelBody>
      ),
    },
    {
      title: 'PC/SP',
      name: 'advanced',
      content: (
        <PanelBody>
          <TextControl
            label="PC サンクスページ URL"
            value={ pcThanksPath }
            onChange={ pcThanksPath => setAttributes({ pcThanksPath }) }
          />

          <TextControl
            label="SP サンクスページ URL"
            value={ spThanksPath }
            onChange={ spThanksPath => setAttributes({ spThanksPath }) }
          />
        </PanelBody>
      ),
    },
  ];

  return (
    <div { ...useBlockProps() }>
      <InspectorControls>
        <PanelBody>
          <SelectBrickControl
            bricks={ bricks }
            postId={ postId }
            onChange={ postId => setAttributes({ postId }) }
          />
        </PanelBody>
      </InspectorControls>

      <InspectorControls>
        <TabPanel
          tabs={ tabs }
        >{ tab => tab.content }</TabPanel>
      </InspectorControls>

      { brick != null && (
        <div className="brick-form">
          <div className="brick-form__content">
            { brick.structure.map(rows => <FormBlock structure={ rows } />) }
          </div>

          <div><InnerBlocks renderAppender={ () => <InnerBlocks.ButtonBlockAppender /> } /></div>

          <div className="brick-buttons">
            <div className="brick-buttons__button-unit brick-buttons__button-unit-submit">
              <button className="brick-buttons__button-submit">
                <span className="brick-buttons__button-inner">確認画面へ進む</span>
              </button>
            </div>
          </div>
        </div>
      ) }
    </div>
  );
}
