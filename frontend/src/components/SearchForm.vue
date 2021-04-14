<template>
  <el-card class="box-card">
    <div class="searchLine">
      <el-input
        placeholder="Type name..."
        prefix-icon="el-icon-search"
        v-model="filters.name"
        clearable
        class="name"
      />
      Price
      <el-input-number v-model="filters.price_from" clearable :step="step" />
      -
      <el-input-number v-model="filters.price_to" clearable :step="step" />
    </div>
    <div class="selects">
      Bedrooms
      <el-select
        v-model="filters.bedrooms"
        placeholder="Select Bedrooms"
        clearable
        class="mr"
      >
        <el-option
          v-for="(item, idx) in dictionaries.bedrooms"
          :key="idx"
          :label="item"
          :value="item"
        >
        </el-option>
      </el-select>
      Bathrooms
      <el-select
        v-model="filters.bathrooms"
        placeholder="Select Bathrooms"
        class="mr"
      >
        <el-option
          v-for="(item, idx) in dictionaries.bathrooms"
          :key="idx"
          :label="item"
          :value="item"
        >
        </el-option>
      </el-select>
      Storeys
      <el-select
        v-model="filters.storeys"
        placeholder="Select Storeys"
        class="mr"
      >
        <el-option
          v-for="(item, idx) in dictionaries.storeys"
          :key="idx"
          :label="item"
          :value="item"
        >
        </el-option>
      </el-select>
      Garages
      <el-select
        v-model="filters.garages"
        placeholder="Select Garages"
        class="mr"
      >
        <el-option
          v-for="(item, idx) in dictionaries.garages"
          :key="idx"
          :label="item"
          :value="item"
        >
        </el-option>
      </el-select>
    </div>

    <div class="tags" v-if="tags.length">
      Filters:
      <el-tag
        v-for="tag in tags"
        :key="tag.key"
        closable
        @close="deleteTagHandler(tag)"
      >
        {{ tag.name }}
      </el-tag>
      <el-tag
        class="cupo ml"
        @click="clearAllFiltersHandler"
        type="danger"
        effect="dark"
      >
        Clear all filters!
      </el-tag>
    </div>

    <div class="actions">
      <el-button type="primary" :disabled="!canSearch" @click="searchHandler"
        >Search</el-button
      >
    </div>
  </el-card>
</template>

<script>
import { computed, reactive, watch } from "vue";
import { useStore } from "vuex";

export default {
  name: "SearchForm",
  setup() {
    const filters = reactive({
      name: null,
      bedrooms: null,
      bathrooms: null,
      storeys: null,
      garages: null,
      price_from: 0,
      price_to: 0,
    });
    const step = 1000;
    watch(
      () => filters.price_from,
      (val) => {
        if (filters.price_from !== null && filters.price_from > 0) {
          if (filters.price_to <= val) {
            filters.price_to = val + step;
          }
        }
      }
    );
    const tags = computed(() => {
      const ret = Object.keys(filters)
        .filter((k) => {
          if (k === "price_from" || k === "price_to") {
            return filters[k] > 0;
          }
          return filters[k] !== null && String(filters[k]).length;
        })
        .map((key) => {
          return { name: key + ": " + filters[key], key };
        });
      return ret;
    });
    const store = useStore();
    const dictionaries = computed(() => store.getters.dictionaries);
    const finded = computed(() => store.getters.finded);
    const canSearch = computed(() => tags.value.length);
    const clearAllFiltersHandler = () => {
      Object.keys(filters).forEach((key) => {
        if (key === "price_from" || key === "price_to") {
          filters[key] = 0;
          return;
        }
        filters[key] = null;
      });

      store.dispatch("clearSearchResult");
    };
    const deleteTagHandler = (tag) => {
      filters[tag.key] = null;
    };
    const searchHandler = () => {
      store.dispatch("search", filters);
    };

    return {
      step,
      filters,
      finded,
      dictionaries,
      searchHandler,
      tags,
      canSearch,
      clearAllFiltersHandler,
      deleteTagHandler,
    };
  },
};
</script>