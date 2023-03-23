import { VFC } from 'react';
import className from 'classnames';
import nl2br from 'react-nl2br';

import { StructureRow } from '../types/StructureRow';
import { CheckboxField } from './FormField/CheckboxField';
import { FileField } from './FormField/FileField';
import { RadioField } from './FormField/RadioField';
import { SelectField } from './FormField/SelectField';
import { TextareaField } from './FormField/TextareaField';
import { TextField } from './FormField/TextField';


type Props = {
  structure: StructureRow;
};

export const FormField: VFC<Props> = ({ structure }) => {
  switch (structure.type) {
    case 'address':
    case 'datepicker':
    case 'email':
    case 'password':
    case 'tel':
    case 'zip':
      return <TextField structure={ structure } />

    case 'checkbox': return <CheckboxField structure={ structure } />
    case 'file': return <FileField structure={ structure } />
    case 'radio': return <RadioField structure={ structure } />
    case 'select': return <SelectField structure={ structure } />
    case 'textarea': return <TextareaField structure={ structure } />

    default: return <TextField structure={ structure } />
  }
}
