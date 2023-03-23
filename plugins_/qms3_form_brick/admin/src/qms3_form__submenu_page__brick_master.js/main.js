import { createApp, h } from 'vue';
// @ts-ignore
import App from './App.vue';


document.addEventListener('DOMContentLoaded', () => {
    createApp({
        render() { return h(App); },
    })
    .mount('.qms3_form__submenu_page__brick_master__main');
});
