<template>
  <div class="searchResult" v-if="finded.length">
    <HouseCard v-for="house in finded" :key="house.id" :houseData="house" />
  </div>
  <div class="errorWrapper" v-if="!finded.length && app.error">
    <el-alert
      title="Error!"
      type="error"
      effect="dark"
      :closable="false"
      class="error"
    >
      {{ app.error }}
    </el-alert>
  </div>
</template>

<script>
import { computed } from "@vue/runtime-core";
import { useStore } from "vuex";
import HouseCard from "./HouseCard";

export default {
  name: "SearchResult",
  components: { HouseCard },
  setup() {
    const store = useStore();
    const app = computed(() => store.getters.app);
    const finded = computed(() => store.getters.finded);
    return { finded, app };
  },
};
</script>