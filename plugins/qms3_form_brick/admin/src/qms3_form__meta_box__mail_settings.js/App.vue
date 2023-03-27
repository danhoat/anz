<template>
  <div class="qms3_form__meta_box__mail_settings__app">
    <MailSettingMain
      v-model:table-name="mailSettingMain.name"
      v-model:thanks="mailSettingMain.thanks"
      v-model:notification="mailSettingMain.notification"
      v-model:bcc-notification="mailSettingMain.bccNotification"
    />
    <MailSettingOptional
      v-for="(mailSetting, index) in mailSettings"
      :key="index"
      :index="index"
      v-model:table-name="mailSetting.name"
      v-model:thanks="mailSetting.thanks"
      v-model:notification="mailSetting.notification"
      v-model:bcc-notification="mailSetting.bccNotification"
      @remove="remove"
    />
    <button
      type="button"
      class="add_mail_setting_button"
      title="メール設定を追加する"
      @click.prevent="add"
    >メール設定追加</button>

    <input
      type="hidden"
      name="qms3_form__meta_box__mail_settings"
      :value="mailSettingsJson"
    />

    <div
      class="qms3_form__meta_box__mail_settings__debug"
      v-if="debug"
    >
      <pre><code style="display:block;overflow-x:scroll">{{ [ mailSettingMain, ...mailSettings ] }}</code></pre>
    </div>
    <!-- /.qms3_form__meta_box__mail_settings__debug -->
  </div>
  <!-- /.qms3_form__meta_box__mail_settings__app -->
</template>

<script>
import { computed, defineComponent, ref } from 'vue';
import MailSettingMain from './components/MailSettingMain.vue';
import MailSettingOptional from './components/MailSettingOptional.vue';

export default defineComponent({
  components: {
    MailSettingMain,
    MailSettingOptional,
  },

  setup() {
    const [ mailSettingMain, ...mailSettings ] = QMS3_FORM__META_BOX__MAIL_SETTINGS;

    const mailSettingMainRef = ref(mailSettingMain);
    const mailSettingsRef = ref(mailSettings)

    const mailSettingsJson = computed(() => {
      const mailSettings = [ mailSettingMainRef.value, ...mailSettingsRef.value ];
      return JSON.stringify(mailSettings);
    });

    const add = () => {
      mailSettingsRef.value.push({
        name: '',
        thanks: { ...mailSettingMainRef.value.thanks },
        notification: { ...mailSettingMainRef.value.notification },
        bccNotification: { ...mailSettingMainRef.value.bccNotification },
      })
    }

    const remove = index => {
      mailSettingsRef.value.splice(index, 1);
    }

    return {
      mailSettingMain: mailSettingMainRef,
      mailSettings: mailSettingsRef,
      mailSettingsJson,

      add,
      remove,

      debug: /\bdebug\b/.test(location.search),
    };
  },
})
</script>
