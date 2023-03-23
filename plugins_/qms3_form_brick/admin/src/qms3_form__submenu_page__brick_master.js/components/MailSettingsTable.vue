<template>
  <div
    class="qms3_form__submenu_page__brick_master__table"
    :class="containerClassName"
  />
</template>

<script>
import { defineComponent, getCurrentInstance, ref, onMounted } from 'vue';
import jspreadsheet from 'jspreadsheet-ce';
import { hasKeys } from '../../util';
import { keys, serialize, deserialize } from '../mailSettingOption';
import { contextMenu } from '../contextMenu';
import { text } from '../../lang.ja';

export default defineComponent({
  props: {
    tableName: {
      type: String,
      required: true,
    },

    thanks: {
      type: Object,
      required: true,
      validator: obj => hasKeys(obj, keys),
    },

    notification: {
      type: Object,
      required: true,
      validator: obj => hasKeys(obj, keys),
    },

    bccNotification: {
      type: Object,
      required: true,
      validator: obj => hasKeys(obj, keys),
    },
  },

  setup(props, { emit }) {
    const containerClassName = `qms3_form__meta_box__mail_settings__table__${props.tableName}`;

    const columns = ref([
      { width: 300, wordWrap: true, title: 'サンクスメール' },
      { width: 300, wordWrap: true, title: '先方通知メール' },
      { width: 300, wordWrap: true, title: '社内通知メール' },
    ]);
    const rows = ref([
      { title: '送信先アドレス' },
      { title: '送信元アドレス' },
      { title: '送信者名' },
      { title: '件名' },
      { title: '本文(前半)' },
      { title: 'ブロックフィルター' },
      { title: 'フォーム入力確認' },
      { title: '本文(後半)' },
      { title: '署名' },
    ]);

    const minDimensions = [3, 9];

    onMounted(() => {
      const container = document.querySelector(`.${containerClassName}`);

      const spreadsheet = jspreadsheet(container, {
        data: serialize({
          thanks: props.thanks,
          notification: props.notification,
          bccNotification: props.bccNotification,
        }),

        tableOverflow: true,
        tableWidth: '100%',
        tableHeight: 'calc(100vh - 144px)',
        columns: columns.value,
        rows: rows.value,
        minDimensions,
        defaultColAlign: 'left',
        rowResize: true,
        allowInsertRow: false,
        allowManualInsertRow: false,
        allowInsertColumn: false,
        allowManualInsertColumn: false,
        allowDeleteRow: false,
        allowDeleteColumn: false,
        rowDrag: false,
        columnDrag: false,
        contextMenu,
        text,

        onload(container, { content }) {
          // 行ヘッダーの幅を設定
          content.querySelector('colgroup col:nth-child(1)').width = 140;

          // ドロップシャドウを消去
          content.style.boxShadow = 'none';
        },

        onafterchanges(container, data) {
          const update = {
            thanks: false,
            notification: false,
            bccNotification: false,
          };
          const newData = {
            thanks: props.thanks,
            notification: props.notification,
            bccNotification: props.bccNotification,
          }

          for (const { col, row, newValue } of data) {
            const key = keys[row];

            if (col == 0) {
              update.thanks = true;
              newData.thanks = {
                ...newData.thanks,
                [key]: newValue,
              };
            } else if (col == 1) {
              update.notification = true;
              newData.notification = {
                ...newData.notification,
                [key]: newValue,
              };
            } else if (col == 2) {
              update.bccNotification = true;
              newData.bccNotification = {
                ...newData.bccNotification,
                [key]: newValue,
              };
            }
          }

          if (update.thanks) { emit('update:thanks', newData.thanks); }
          if (update.notification) { emit('update:notification', newData.notification); }
          if (update.bccNotification) { emit('update:bccNotification', newData.bccNotification); }
        },

        onundo(container, historyRecord) {
          const rows = spreadsheet.getData(/* highlighted = */ false);
          const { thanks, notification, bccNotification } = deserialize(rows);
          emit('update:thanks', thanks);
          emit('update:notification', notification);
          emit('update:bccNotification', bccNotification);
        },

        onredo(container, historyRecord) {
          const rows = spreadsheet.getData(/* highlighted = */ false);
          const { thanks, notification, bccNotification } = deserialize(rows);
          emit('update:thanks', thanks);
          emit('update:notification', notification);
          emit('update:bccNotification', bccNotification);
        },
      })

      const instance = getCurrentInstance();
      Object.assign(instance, { spreadsheet });
    });

    return {
      containerClassName,
    };
  },
})
</script>
