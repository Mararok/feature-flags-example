<script setup lang="ts">
import { ref } from 'vue';
import { RouterLink, RouterView } from 'vue-router'
import ForcedReloadAppModal from './components/ForcedReloadAppModal.vue';
import { addFeatureFlagRefreshEventListener } from './plugins/growthbook-vue/GrowthbookVue';

const reload = ref(false);
addFeatureFlagRefreshEventListener(function () {
  reload.value = true;
});
</script>

<template>
  <ForcedReloadAppModal v-show="reload"></ForcedReloadAppModal>
  <RouterView />
</template>

