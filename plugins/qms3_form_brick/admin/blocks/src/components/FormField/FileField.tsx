import { VFC } from 'react';
import className from 'classnames';

import { StructureRow } from '../../types/StructureRow';


type Props = {
  structure: StructureRow;
};

export const FileField: VFC<Props> = ({ structure }) => {
  return (
    <div className={ className('brick-form__field-unit', 'brick-form__field-unit-file', 'brick-form__field-unit--step-input', structure.required ? 'brick-form__field-unit--required' : 'brick-form__field-unit--optional') } >
      { !!structure.prepend && (
        <div className="brick-form__prepend">{ structure.prepend }</div>
      ) }

      <div className="brick-form__field">
        <input
          id={ `brick-form__field-name-${structure.name}` }
          className={ `brick-form__field-name-${structure.name}` }
          type="file"
          name={ structure.name }
          required={ structure.required }
        />
      </div>

      { !!structure.append && (
        <div className="brick-form__append">{ structure.append }</div>
      ) }
    </div>
  );
}
