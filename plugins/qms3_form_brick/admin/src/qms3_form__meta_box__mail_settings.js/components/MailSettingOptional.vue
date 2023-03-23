<template>
  <div class="qms3_form__meta_box__mail_settings__mail_setting">
    <div class="qms3_form__meta_box__mail_settings__mail_setting__header">
      <input
        type="text"
        v-model="_tableName"
        placeholder="name"
      />
      <button
        type="button"
        title="メール設定を削除する"
        @click.prevent="remove"
      >削除</button>
    </div>
    <!-- /.qms3_form__meta_box__mail_settings__mail_setting__header -->
    <MailSettingsTable
      :table-name="index"
      v-model:thanks="_thanks"
      v-model:notification="_notification"
      v-model:bcc-notification="_bccNotification"
    />
  </div>
  <!-- /.qms3_form__meta_box__mail_settings__mail_setting -->
</template>

<script>
import { defineComponent, computed } from 'vue';
import { hasKeys } from '../../util';
import { keys } from '../mailSettingOption';
import MailSettingsTable from './MailSettingsTable.vue';

export default defineComponent({
  props: {
    index: {
      type: Number,
      required: true,
    },

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

  components: {
    MailSettingsTable,
  },

  setup(props, { emit }) {
    const _tableName = computed({
      get: () => props.tableName,
      set: value => { emit('update:tableName', value) },
    });

    const _thanks = computed({
      get: () => props.thanks,
      set: value => { emit('update:thanks', value) },
    });

    const _notification = computed({
      get: () => props.notification,
      set: value => { emit('update:notification', value) },
    });

    const _bccNotification = computed({
      get: () => props.bccNotification,
      set: value => { emit('update:bccNotification', value) },
    });

    const remove = () => { emit('remove', props.index); }

    return {
      index: props.index,
      _tableName,
      _thanks,
      _notification,
      _bccNotification,
      remove,
    };
  },
})
</script>
