<template>
  <div class="loading" v-if="app.loading">
    <Loading />
  </div>
  <div class="container" v-if="app.isReady">
    <SearchForm />
    <SearchResult />
  </div>
</template>

<script>
import { computed, onBeforeMount } from "@vue/runtime-core";
import { useStore } from "vuex";

import Loading from "./components/Loading";
import SearchForm from "./components/SearchForm";
import SearchResult from "./components/SearchResult";

export default {
  name: "App",
  components: { Loading, SearchForm, SearchResult },
  setup() {
    const store = useStore();
    const app = computed(() => store.getters.app);

    onBeforeMount(() => {
      store.dispatch("initApp");
    });

    return { app };
  },
};
</script>