<script setup lang="ts">
import { useFeatureFlag} from '@/plugins/growthbook-vue/GrowthbookVue';
import { ref } from "vue";
const FF = useFeatureFlag();
const qaMode = ref(window.getCookie("QA") === "1");

function onQAModeChanged() {
  console.log(qaMode.value);
  document.cookie = qaMode.value ? "QA=1" : "QA=0";
  window.location.reload();

}
</script>

<template>
  <h2>Simulates Production environment</h2>
  <h3>User attributes settings</h3>
  Are you QA ? <input style="vertical-align: center" type="checkbox" id="qaMode" v-model="qaMode" @change="onQAModeChanged" /><br/>
  <p style="font-style: italic">(QA mode switch will reload page after sets cookie value)</p>
  <br/>
  <h3>App Features:</h3>
  <hr/>
  <br/>
  <br/>
  <h3><b>Some text without flag:</b> <i class="green blob">Start profiting from Progressive Deliver</i></h3>
  <br/>
  <h3 v-show="FF('feature_1')">
    <b>feature_1 enabled:</b> <i class="green blob">Ship fast, stay safe, and stay in control</i>
  </h3>
  <br/>
  <h3 v-show="FF('feature_2')">
    <b>As QA and feature_2 enabled:</b> <i class="green blob">Test in production. That is the way of Progressive Delivery.</i>
  </h3>
</template>
