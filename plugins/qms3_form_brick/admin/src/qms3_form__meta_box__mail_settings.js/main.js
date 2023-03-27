import { createApp, h } from 'vue';
// @ts-ignore
import App from './App.vue';


document.addEventListener('DOMContentLoaded', () => {
    createApp({
        render() { return h(App); },
    })
    .mount('.qms3_form__meta_box__mail_settings__container');
});
