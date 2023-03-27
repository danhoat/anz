import { VFC } from 'react';
import className from 'classnames';
import nl2br from 'react-nl2br';

import { StructureRow } from '../types/StructureRow';
import { FormField } from './FormField';


type Props = {
  structure: StructureRow[];
};

export const FormRow: VFC<Props> = ({ structure }) => {
  const rowRequired = !!structure.find(row => row.required);

  const headerNotices = structure.map(row => row.header_notice).filter(Boolean);
  const bodyNotices = structure.map(row => row.body_notice).filter(Boolean);

  return (
    <div className={ className('brick-form__row', 'brick-form__row--step-input', rowRequired ? 'brick-form__row--required' : 'brick-form__row--optional') }>
      <div className="brick-form__row-header">
        <h4 className="brick-form__label">
          <span>{ nl2br(structure[0].label) }</span>
        </h4>

        { headerNotices.length > 0 && (
          <div className="brick-form__header-notice">
            { headerNotices.map(notice => (
              <div className="brick-form__header-notice-item">{ nl2br(notice) }</div>
            )) }
          </div>
        ) }
      </div>

      <div className="brick-form__row-body">
        <div className="brick-form__field-group">
          { structure.map(row => <FormField structure={ row }></FormField>) }
        </div>

        { bodyNotices.length > 0 && (
          <div className="brick-form__header-notice">
            { bodyNotices.map(notice => (
              <div className="brick-form__body-notice-item">{ nl2br(notice) }</div>
            )) }
          </div>
        ) }
      </div>
    </div>
  );
}
