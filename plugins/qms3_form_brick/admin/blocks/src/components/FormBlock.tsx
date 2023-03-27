import { VFC } from 'react';
import className from 'classnames';
import nl2br from 'react-nl2br';

import { StructureRow } from '../types/StructureRow';
import { FormRow } from './FormRow';


type Props = {
  structure: StructureRow[][];
};

export const FormBlock: VFC<Props> = ({ structure }) => {
  return (
    <div className="brick-form__block">
      { structure.map(rows => <FormRow structure={ rows } />) }
    </div>
  );
}
