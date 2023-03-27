<template>
  <div>
    <div class="qms3_form__submenu_page__brick_master__app postbox">
      <div class="postbox-header">
        <h2 class="hndle ui-sortable-handle">サンクスメール・通知メール マスター設定</h2>
      </div>
      <!-- /.postbox-header -->

      <div class="inside">
        <MailSettingsTable
          v-model:thanks="mailSettingMain.thanks"
          v-model:notification="mailSettingMain.notification"
          v-model:bcc-notification="mailSettingMain.bccNotification"
        />

        <input
          type="hidden"
          name="qms3_form__submenu_page__brick_master__mail_setting"
          :value="mailSettingMainJson"
        />

        <div
          class="qms3_form__submenu_page__brick_master__debug"
          v-if="debug"
        >
          <pre><code style="display:block;overflow-x:scroll">{{ mailSettingMain }}</code></pre>
        </div>
        <!-- /.qms3_form__submenu_page__brick_master__debug -->
      </div>
      <!-- /.inside -->
    </div>
    <!-- /.qms3_form__submenu_page__brick_master__app -->

    <div class="qms3_form__submenu_page__brick_master__app postbox">
      <div class="postbox-header">
        <h2 class="hndle ui-sortable-handle">SMTP 設定</h2>
      </div>
      <!-- /.postbox-header -->

      <div class="inside">
        <dl>
          <dt>SMTP を有効化</dt>
          <dd>
            <input type="hidden" name="smtp_activate" value="0">
            <input type="checkbox" name="smtp_activate" value="1" :checked="smtp_activate">
          </dd>

          <dt>SMTP Host</dt>
          <dd><input type="text" name="smtp_host" :value="smtp_host" placeholder="ms20.kagoya.net"></dd>

          <dt>SMTP Port</dt>
          <dd><input type="number" name="smtp_port" :value="smtp_port" placeholder="587"></dd>

          <input type="hidden" name="smtp_protocol" :value="smtp_protocol">

          <dt>SMTP User</dt>
          <dd><input type="text" name="smtp_user" :value="smtp_user" placeholder="kir391168.notification"></dd>

          <dt>SMTP Pass</dt>
          <dd><input type="password" name="smtp_pass" :value="smtp_pass"></dd>
        </dl>
      </div>
      <!-- /.inside -->
    </div>
    <!-- /.qms3_form__submenu_page__brick_master__app -->

    <div class="qms3_form__submenu_page__brick_master__app postbox">
      <div class="postbox-header">
        <h2 class="hndle ui-sortable-handle">reCAPTCHA 設定</h2>
      </div>
      <!-- /.postbox-header -->
      <div class="inside">
        <dl>
          <dt>reCAPTCHA を有効化</dt>
          <dd>
            <input type="hidden" name="recaptcha_activate" value="0">
            <input type="checkbox" name="recaptcha_activate" value="1" :checked="recaptcha_activate">
          </dd>

          <dt>サイトキー</dt>
          <dd><input type="text" name="recaptcha_sitekey" :value="recaptcha_sitekey"></dd>

          <dt>シークレットキー</dt>
          <dd><input type="text" name="recaptcha_secret" :value="recaptcha_secret"></dd>
        </dl>
      </div>
      <!-- /.inside -->
    </div>
    <!-- /.qms3_form__submenu_page__brick_master__app -->

    <div class="qms3_form__submenu_page__brick_master__app postbox">
      <div class="postbox-header">
        <h2 class="hndle ui-sortable-handle">ログ設定</h2>
      </div>
      <!-- /.postbox-header -->

      <div class="inside">
        <dl>
          <dt>Log Level</dt>
          <dd>
            <select name="log_level" v-model="log_level">
              <option :value="100">100: DEBUG</option>
              <option :value="200">200: INFO</option>
              <option :value="250">250: NOTICE</option>
              <option :value="300">300: WARNING</option>
              <option :value="400">400: ERROR</option>
              <option :value="500">500: CRITICAL</option>
              <option :value="550">550: ALERT</option>
              <option :value="600">600: EMERGENCY</option>
            </select>
          </dd>
        </dl>
      </div>
      <!-- /.inside -->
    </div>
    <!-- /.qms3_form__submenu_page__brick_master__app -->

    <div class="qms3_form__submenu_page__brick_master__app postbox">
      <div class="postbox-header">
        <h2 class="hndle ui-sortable-handle">権限設定 リセット</h2>
      </div>
      <!-- /.postbox-header -->

      <div class="inside">
        <dl>
          <dt>最後の権限設定</dt>
          <dd class="capability_setting_completed">
            <p>
              <time v-if="!!capability_setting_completed">{{ capability_setting_completed }}</time>
              <span v-else>(権限未設定)</span>
            </p>
            <button name="action" value="reset_capability_setting">権限設定をリセットする</button>
          </dd>
        </dl>
      </div>
      <!-- /.inside -->
    </div>
    <!-- /.qms3_form__submenu_page__brick_master__app -->

    <input type="hidden" name="qms3_form__submenu_page__brick_master__nonce" :value="nonce">
  </div>
</template>

<script>
import { computed, defineComponent, ref } from 'vue';
import MailSettingsTable from './components/MailSettingsTable.vue';

export default defineComponent({
  components: {
    MailSettingsTable,
  },

  setup() {
    const {
      nonce,

      mail_setting,

      smtp_activate,
      smtp_host,
      smtp_port,
      smtp_protocol,
      smtp_user,
      smtp_pass,

      recaptcha_activate,
      recaptcha_sitekey,
      recaptcha_secret,

      log_level,

      capability_setting_completed,
    } = QMS3_FORM__SUBMENU_PAGE__BRICK_MASTER;

    const mailSettingMainRef = ref(mail_setting);
    const mailSettingMainJson = computed(() => JSON.stringify(mailSettingMainRef.value));

    return {
      mailSettingMain: mailSettingMainRef,
      mailSettingMainJson,

      smtp_activate: !!smtp_activate,
      smtp_host,
      smtp_port: +smtp_port,
      smtp_protocol,
      smtp_user,
      smtp_pass,

      recaptcha_activate: !!recaptcha_activate,
      recaptcha_sitekey,
      recaptcha_secret,

      log_level: +log_level,

      capability_setting_completed: capability_setting_completed || null,

      nonce,

      debug: /\bdebug\b/.test(location.search),
    };
  },
})
</script>
