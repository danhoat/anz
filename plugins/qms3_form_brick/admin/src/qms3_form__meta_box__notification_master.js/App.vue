<template>
  <div class="qms3_form__meta_box__notification_master__app">
    <div class="qms3_form__meta_box__notification_master__thanks_setting">
      <ThanksSettingTable
        v-model:from="thanks.from"
        v-model:from_name="thanks.from_name"
        v-model:subject_template="thanks.subject_template"
        v-model:main_text_template="thanks.main_text_template"
        v-model:after_text_template="thanks.after_text_template"
        v-model:signature_template="thanks.signature_template"
      />
    </div>

    <div class="qms3_form__meta_box__notification_master__notification_setting">
      <NotificationSettingTable
        v-model:to="notification.to"
        v-model:from="notification.from"
        v-model:from_name="notification.from_name"
        v-model:subject_template="notification.subject_template"
      />
    </div>

    <input
      type="hidden"
      name="qms3_form__meta_box__notification_master"
      :value="settings_json"
    />

    <div
      class="qms3_form__meta_box__notification_master__debug"
      v-if="debug"
    >
      <pre><code style="display:block;overflow-x:scroll">{{ [ thanks, notification ] }}</code></pre>
    </div>
    <!-- /.qms3_form__meta_box__mail_settings__debug -->
  </div>
  <!-- /.qms3_form__meta_box__mail_settings__app -->
</template>

<script>
import { computed, defineComponent, ref } from 'vue';
import ThanksSettingTable from './components/ThanksSettingTable.vue';
import NotificationSettingTable from './components/NotificationSettingTable.vue';

export default defineComponent({
  components: {
    ThanksSettingTable,
    NotificationSettingTable,
  },

  setup() {
    const { thanks, notification } = QMS3_FORM__META_BOX__NOTIFICATION_MASTER;

    const thanks_ref = ref(thanks);
    const notification_ref = ref(notification);

    const settings_json = computed(() => {
      return JSON.stringify({
        thanks: thanks_ref.value,
        notification: notification_ref.value,
      });
    });

    return {
      thanks: thanks_ref,
      notification: notification_ref,

      settings_json,

      debug: /\bdebug\b/.test(location.search),
    };
  },
})
</script>
