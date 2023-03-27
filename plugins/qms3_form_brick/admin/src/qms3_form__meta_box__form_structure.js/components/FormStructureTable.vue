<template>
  <div class="qms3_form__meta_box__form_structure__table">
    <div class="qms3_form__meta_box__form_structure__buttons">
      <button
        type="button"
        class="add_fields_button"
        title="フィールドを追加する"
        v-if="!shownButtons"
        @click="shownButtons = true"
      >フィールド追加</button>
      <dl v-else>
        <dt>汎用フィールド</dt>
        <dd>
          <ul>
            <li><button type="button" @click.prevent="insertRow('汎用テキストフィールド')">テキストフィールド</button></li>
            <li><button type="button" @click.prevent="insertRow('汎用テキストエリア')">テキストエリア</button></li>
            <li><button type="button" @click.prevent="insertRow('汎用チェックボックス')">チェックボックス</button></li>
            <li><button type="button" @click.prevent="insertRow('汎用ラジオボタン')">ラジオボタン</button></li>
            <li><button type="button" @click.prevent="insertRow('汎用プルダウン')">プルダウン</button></li>
            <li><button type="button" @click.prevent="insertRow('汎用デイトピッカー')">デイトピッカー</button></li>
            <li><button type="button" @click.prevent="insertRow('汎用パスワード')">パスワード</button></li>
            <li><button type="button" @click.prevent="insertRow('非表示フィールド')">非表示フィールド</button></li>
            <li><button type="button" @click.prevent="insertRow('タイトル行')">タイトル行</button></li>
          </ul>
        </dd>

        <dt>お名前</dt>
        <dd>
          <ul>
            <li><button type="button" @click.prevent="insertRow('お名前')">お名前</button></li>
            <li><button type="button" @click.prevent="insertRow('お名前・ふりがな')">お名前・ふりがな</button></li>
            <li><button type="button" @click.prevent="insertRow('お名前・フリガナ')">お名前・フリガナ</button></li>
            <li><button type="button" @click.prevent="insertRow('お相手のお名前')">お相手のお名前</button></li>
            <li><button type="button" @click.prevent="insertRow('お相手のお名前・ふりがな')">お相手のお名前・ふりがな</button></li>
            <li><button type="button" @click.prevent="insertRow('お相手のお名前・フリガナ')">お相手のお名前・フリガナ</button></li>
            <li><button type="button" @click.prevent="insertRow('会社名')">会社名</button></li>
            <li><button type="button" @click.prevent="insertRow('部署名')">部署名</button></li>
            <li><button type="button" @click.prevent="insertRow('役職')">役職</button></li>
          </ul>
        </dd>

        <dt>連絡先</dt>
        <dd>
          <ul>
            <li><button type="button" @click.prevent="insertRow('メールアドレス')">メールアドレス</button></li>
            <li><button type="button" @click.prevent="insertRow('メールアドレス・確認')">メールアドレス・確認</button></li>
            <li><button type="button" @click.prevent="insertRow('電話番号')">電話番号</button></li>
            <li><button type="button" @click.prevent="insertRow('携帯番号')">携帯番号</button></li>
            <li><button type="button" @click.prevent="insertRow('郵便番号')">郵便番号</button></li>
            <li><button type="button" @click.prevent="insertRow('都道府県')">都道府県</button></li>
            <li><button type="button" @click.prevent="insertRow('ご住所')">ご住所</button></li>
            <li><button type="button" @click.prevent="insertRow('LINE ID')">LINE ID</button></li>
          </ul>
        </dd>

        <dt>個人情報</dt>
        <dd>
          <ul>
            <li><button type="button" @click.prevent="insertRow('性別')">性別</button></li>
            <li><button type="button" @click.prevent="insertRow('年齢')">年齢</button></li>
            <li><button type="button" @click.prevent="insertRow('生年月日')">生年月日</button></li>
            <li><button type="button" @click.prevent="insertRow('最終学歴')">最終学歴</button></li>
          </ul>
        </dd>

        <dt>内容</dt>
        <dd>
          <ul>
            <li><button type="button" @click.prevent="insertRow('種別')">種別</button></li>
            <li><button type="button" @click.prevent="insertRow('フェア名')">フェア名</button></li>
            <li><button type="button" @click.prevent="insertRow('フェア開催日時')">フェア開催日時</button></li>
            <li><button type="button" @click.prevent="insertRow('イベント名')">イベント名</button></li>
            <li><button type="button" @click.prevent="insertRow('イベント開催日時')">イベント開催日時</button></li>
            <li><button type="button" @click.prevent="insertRow('ご見学希望日')">ご見学希望日</button></li>
            <li><button type="button" @click.prevent="insertRow('ご見学希望時間')">ご見学希望時間</button></li>
            <li><button type="button" @click.prevent="insertRow('ご参加人数')">ご参加人数</button></li>
          </ul>
        </dd>

        <dt>その他</dt>
        <dd>
          <ul>
            <li><button type="button" @click.prevent="insertRow('卒業（予定）年月')">卒業（予定）年月</button></li>
            <li><button type="button" @click.prevent="insertRow('挙式予定時期')">挙式予定時期</button></li>
            <li><button type="button" @click.prevent="insertRow('挙式予定人数')">挙式予定人数</button></li>
            <li><button type="button" @click.prevent="insertRow('きっかけ')">きっかけ</button></li>
            <li><button type="button" @click.prevent="insertRow('連絡の取りやすい時間帯')">連絡の取りやすい時間帯</button></li>
            <li><button type="button" @click.prevent="insertRow('ご希望の連絡方法')">ご希望の連絡方法</button></li>
            <li><button type="button" @click.prevent="insertRow('ご質問・ご要望など')">ご質問・ご要望など</button></li>
            <li><button type="button" @click.prevent="insertRow('添付ファイル')">添付ファイル</button></li>
            <li><button type="button" @click.prevent="insertRow('個人情報の取扱について')">個人情報の取扱について</button></li>
          </ul>
        </dd>
      </dl>
    </div>
    <!-- /.qms3_form__meta_box__form_structure__buttons -->

    <div class="qms3_form__meta_box__form_structure__table" />
  </div>
  <!-- /.qms3_form__meta_box__form_structure__table -->
</template>

<script>
import { defineComponent, getCurrentInstance, ref, onMounted } from 'vue';
import jspreadsheet from 'jspreadsheet-ce';
import { hasKeys, zip } from '../../util';
import { keys, presets } from '../formStructure';
import { contextMenu } from '../contextMenu';
import { text } from '../../lang.ja';

export default defineComponent({
  props: {
    formStructure: {
      type: Array,
      required: true,
      validator: arr => arr.every(obj => hasKeys(obj, keys)),
    },
  },

  setup(props, { emit }) {
    const types = [
      'text',
      'textarea',
      'select',
      'radio',
      'checkbox',
      'email',
      'tel',
      'zip',
      'pref',
      'address',
      'file',
      'datepicker',
      'password',
      'hidden',
      'title',
    ];

    const columns = [
      { type: 'text'    , width:   60, align:   'left', wordWrap: false, name:         'block', title: 'block' },
      { type: 'text'    , width:  160, align:   'left', wordWrap: true , name:         'label', title: 'label' },
      { type: 'dropdown', width:  100, align:   'left', wordWrap: false, name:          'type', title: 'type', source: types },
      { type: 'text'    , width:   80, align:   'left', wordWrap: false, name:          'name', title: 'name' },
      { type: 'text'    , width:   80, align:   'left', wordWrap:  true, name:       'default', title: 'default' },
      { type: 'text'    , width:   40, align:   'left', wordWrap:  true, name:       'prepend', title: '前' },
      { type: 'text'    , width:   40, align:   'left', wordWrap:  true, name:        'append', title: '後' },
      { type: 'text'    , width:  100, align:   'left', wordWrap:  true, name: 'header_notice', title: '注釈1' },
      { type: 'text'    , width:  100, align:   'left', wordWrap:  true, name:   'body_notice', title: '注釈2' },
      { type: 'text'    , width:  100, align:   'left', wordWrap:  true, name:       'options', title: 'options' },
      { type: 'text'    , width:  160, align:   'left', wordWrap:  true, name:   'placeholder', title: 'placeholder' },
      { type: 'checkbox', width: null, align: 'center', wordWrap: false, name:       'for_bcc', title: 'BCC' },
      { type: 'checkbox', width: null, align: 'center', wordWrap: false, name:     'thanks_to', title: '通知先' },
      { type: 'checkbox', width: null, align: 'center', wordWrap: false, name:      'required', title: '必須' },
      { type: 'text'    , width:  100, align:   'left', wordWrap:  true, name:    'attributes', title: 'attributes' },
    ];
    const column_names = columns.map(column => column.name);

    const minDimensions = [15, 0];

    onMounted(() => {
      const container = document.querySelector('.qms3_form__meta_box__form_structure__table');

      const spreadsheet = jspreadsheet(container, {
        data: props.formStructure,

        tableOverflow: true,
        tableWidth: '100%',
        tableHeight: 'calc(100vh - 144px)',
        columns,
        minDimensions,
        defaultColAlign: 'left',
        rowResize: true,
        allowInsertColumn: false,
        allowManualInsertColumn: false,
        allowDeleteColumn: false,
        rowDrag: true,
        contextMenu,
        text,

        onload(container, { content }) {
          // ドロップシャドウを消去
          content.style.boxShadow = 'none';
        },

        onafterchanges(container, data) {
          const rows = spreadsheet.getData(/* highlighted = */ false);
          const newData = rows.map(row => Object.fromEntries(zip(column_names, row)));
          emit('update:formStructure', newData);
        },

        onmoverow(container, from, to) {
          const rows = spreadsheet.getData(/* highlighted = */ false);
          const newData = rows.map(row => Object.fromEntries(zip(column_names, row)));
          emit('update:formStructure', newData);
        },

        ondeleterow(container, rowNumber, numRows, rowRecords) {
          const newData = [ ...props.formStructure ];
          newData.splice(rowNumber, numRows);
          emit('update:formStructure', newData);
        },

        onundo(container, historyRecord) {
          const rows = spreadsheet.getData(/* highlighted = */ false);
          const newData = rows.map(row => Object.fromEntries(zip(column_names, row)));
          emit('update:formStructure', newData);
        },

        onredo(container, historyRecord) {
          const rows = spreadsheet.getData(/* highlighted = */ false);
          const newData = rows.map(row => Object.fromEntries(zip(column_names, row)));
          emit('update:formStructure', newData);
        },
      })

      const instance = getCurrentInstance();
      Object.assign(instance, { spreadsheet });
    });

    const vm = getCurrentInstance();

    const insertRow = key => {
      if (!presets[key]) { return; }

      const selectedRows = vm.spreadsheet.getSelectedRows(true);
      const rowNumber = selectedRows.length ? selectedRows[0] : -1;
      const insertBefore = !!selectedRows.length;

      for (const row of presets[key]) {
        const data = column_names.map(column_name => row[column_name]);

        vm.spreadsheet.insertRow(data, rowNumber, insertBefore);
      }

      if (rowNumber >= 0) {
        vm.spreadsheet.updateSelectionFromCoords(0, rowNumber + presets[key].length, Infinity);
      }


      const newData = [ ...props.formStructure ];
      if (rowNumber < 0) {
        newData.push(...presets[key]);
      } else {
        newData.splice(rowNumber, 0, ...presets[key]);
      }
      emit('update:formStructure', newData);
    }

    const shownButtonsRef = ref(false);

    return {
      insertRow,

      shownButtons: shownButtonsRef,
    };
  },
})
</script>
