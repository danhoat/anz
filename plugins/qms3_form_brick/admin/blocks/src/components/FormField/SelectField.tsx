import { VFC } from 'react';
import className from 'classnames';

import { StructureRow } from '../../types/StructureRow';


type Props = {
  structure: StructureRow;
};

function parse(optionsStr: string) {
  const lines = optionsStr.split('\n').map(line => line.trim()).filter(Boolean);

  const options: string[] = [];
  for (const line of lines) {
    const pairs = line.split('=>');
    options.push(
      pairs.length == 2
        ? pairs[1].trim()
        : pairs[0].trim()
    );
  }

  return options;
}

export const SelectField: VFC<Props> = ({ structure }) => {
  return (
    <div className={ className('brick-form__field-unit', 'brick-form__field-unit-select', 'brick-form__field-unit--step-input', structure.required ? 'brick-form__field-unit--required' : 'brick-form__field-unit--optional') } >
      { !!structure.prepend && (
        <div className="brick-form__prepend">{ structure.prepend }</div>
      ) }

      <div className="brick-form__field">
        <select
          id={ `brick-form__options-${structure.name}` }
          className={ className('brick-form__options', 'brick-form__options-select', `brick-form__options-name-${structure.name}`) }
          name={ structure.name }
        >
          { parse(structure.options).map((label, index) => (
            <option
              className={ `brick-form__options-item-${structure.name}-${index}` }
            >{ label }</option>
          )) }
        </select>
      </div>

      { !!structure.append && (
        <div className="brick-form__append">{ structure.append }</div>
      ) }
    </div>
  );
}
