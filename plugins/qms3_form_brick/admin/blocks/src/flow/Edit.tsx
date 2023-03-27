import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import { WPElement } from '@wordpress/element';

import './editor.scss';


export function Edit({ attributes, setAttributes }): WPElement {
  const { labelInput, labelConfirm, labelThanks } = attributes;

  return (
    <div { ...useBlockProps() }>
      <ol className="brick-flow brick-flow--step-3">
        <li className="brick-flow__step brick-flow__step--current">
          <span>
            <TextControl
              className="text-control__label"
              value={ labelInput }
              onChange={ labelInput => setAttributes({ labelInput }) }
            />
          </span>
        </li>
        <li className="brick-flow__step">
          <span>
            <TextControl
              className="text-control__label"
              value={ labelConfirm }
              onChange={ labelConfirm => setAttributes({ labelConfirm }) }
            />
          </span>
        </li>
        <li className="brick-flow__step">
          <span>
            <TextControl
              className="text-control__label"
              value={ labelThanks }
              onChange={ labelThanks => setAttributes({ labelThanks }) }
            />
          </span>
        </li>
      </ol>
    </div>
  );
}
