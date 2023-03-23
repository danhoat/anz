import { SelectControl } from '@wordpress/components';


export function SelectBrickControl({ bricks, postId, onChange }) {
  const options = bricks.map(brick => ({
    label: brick.title.rendered,
    value: String(brick.id),
    id: brick.id,
  }));

  return (
    <SelectControl
      label="フォーム選択"
      value={ postId }
      options={ options }
      onChange={ onChange }
    />
  );
}
