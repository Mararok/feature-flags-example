import { createApp } from "vue";

import { createPinia } from "pinia";
import router from "./router";
import App from "./App.vue";

import { GrowthBookVue } from "@/plugins/growthbook-vue/GrowthbookVue";

import "./assets/main.css";

async function run() {
    const app = createApp(App);
    app.use(
        await GrowthBookVue({
            apiHost: import.meta.env.VITE_GROWTHBOOK_API_HOST,
            clientKey: import.meta.env.VITE_GROWTHBOOK_CLIENT_KEY,
            autoRefresh: true,
        })
    );
    app.use(createPinia());
    app.use(router);

    app.mount("#app");
}

run();
