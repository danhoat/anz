export function contextMenu(obj, x, y, e) {
  const items = [];

  if (obj.options.allowDeleteRow == true && x === null && y !== null) {
    items.push({
      title: obj.options.text.deleteSelectedRows,
      onclick() {
        obj.deleteRow(obj.getSelectedRows().length ? undefined : parseInt(y));
      },
    });
  }

  items.push({
    title: obj.options.text.copy,
    shortcut: 'Ctrl + C',
    onclick() {
      obj.copy(true);
    },
  });

  if (navigator && navigator.clipboard) {
    items.push({
      title: obj.options.text.paste,
      shortcut:'Ctrl + V',
      onclick() {
        if (obj.selectedCell) {
          navigator.clipboard.readText().then(text => {
            if (text) {
              jexcel.current.paste(obj.selectedCell[0], obj.selectedCell[1], text);
            }
          });
        }
      },
    });
  }

  return items;
}
