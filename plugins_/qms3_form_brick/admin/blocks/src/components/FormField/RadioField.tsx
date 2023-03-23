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

export const RadioField: VFC<Props> = ({ structure }) => {
  return (
    <div className={ className('brick-form__field-unit', 'brick-form__field-unit-radio', 'brick-form__field-unit--step-input', structure.required ? 'brick-form__field-unit--required' : 'brick-form__field-unit--optional') } >
      { !!structure.prepend && (
        <div className="brick-form__prepend">{ structure.prepend }</div>
      ) }

      <div className="brick-form__field">
        <input type="hidden" name={ structure.name } value="" />
        <div className={ className('brick-form__options', 'brick-form__options-radio', `brick-form__options-name-${structure.name}`) } >
          { parse(structure.options).map((label, index) => (
            <div className="brick-form__options-item brick-form__options-item--radio">
              <input
                id={ `brick-form__options-item-${structure.name}-${index}` }
                type="radio"
                name={ structure.name }
              />
              <label
                htmlFor={ `brick-form__options-item-${structure.name}-${index}` }
              >{ label }</label>
            </div>
          )) }
        </div>
      </div>

      { !!structure.append && (
        <div className="form__append">{ structure.append }</div>
      ) }
    </div>
  );
}
