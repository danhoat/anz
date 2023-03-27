<template>
  <div class="qms3_form__meta_box__notification_master__thanks_setting_table" />
</template>

<script>
import { defineComponent, getCurrentInstance, ref, onMounted } from 'vue';
import jspreadsheet from 'jspreadsheet-ce';
import { contextMenu } from '../contextMenu';
import { text } from '../../lang.ja';

export default defineComponent({
  props: {
    from: {
      type: String,
      required: true,
    },

    from_name: {
      type: String,
      required: true,
    },

    subject_template: {
      type: String,
      required: true,
    },

    main_text_template: {
      type: String,
      required: true,
    },

    after_text_template: {
      type: String,
      required: true,
    },

    signature_template: {
      type: String,
      required: true,
    },
  },

  setup(props, { emit }) {
    const containerClassName = 'qms3_form__meta_box__notification_master__thanks_setting_table';

    const keys = [
      'from',
      'from_name',
      'subject_template',
      'main_text_template',
      'after_text_template',
      'signature_template',
    ];

    const columns = ref([
      { width: 800, wordWrap: true, title: 'サンクスメール' },
    ]);
    const rows = ref([
      { title: '送信元アドレス' },
      { title: '送信者名' },
      { title: '件名' },
      { title: '本文(前半)' },
      { title: '本文(後半)' },
      { title: '署名' },
    ]);

    const minDimensions = [1, 6];

    onMounted(() => {
      const container = document.querySelector(`.${containerClassName}`);

      const spreadsheet = jspreadsheet(container, {
        data: [
          props.from,
          props.from_name,
          props.subject_template,
          props.main_text_template,
          props.after_text_template,
          props.signature_template,
        ].map(v => [v]),

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
          for (const { row, newValue } of data) {
            const key = keys[row];

            emit(`update:${key}`, newValue);
          }
        },

        onundo(container, historyRecord) {
          const rows = spreadsheet.getData(/* highlighted = */ false);

          for (let i = 0, len = rows.length; i < len; i++) {
            const [value] = rows[i];
            const key = keys[i];

            emit(`update:${key}`, value);
          }
        },

        onredo(container, historyRecord) {
          const rows = spreadsheet.getData(/* highlighted = */ false);

          for (let i = 0, len = rows.length; i < len; i++) {
            const [value] = rows[i];
            const key = keys[i];

            emit(`update:${key}`, value);
          }
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
