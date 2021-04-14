<template>
  <div class="houseCard">
    <div class="name">{{ houseData.name }}</div>
    <div class="params" v-for="param in params" :key="param.idx">
      {{ param.key }}:
      <span>{{
        param.key === "price" ? formatedPrice(param.val) : param.val
      }}</span>
    </div>
  </div>
</template>

<script>
import { computed } from "@vue/runtime-core";

export default {
  name: "HouseCard",
  props: {
    houseData: {
      type: Object,
      required: true,
    },
  },
  setup(houseData) {
    const params = computed(() => {
      const data = houseData.houseData;
      const keys = Object.keys(data).filter((k) => k !== "name" && k !== "id");
      const ret = [];
      keys.forEach((key, idx) => {
        ret.push({ idx, key, val: data[key] });
      });

      return ret;
    });

    const formatedPrice = (val) => {
      return `$${new Intl.NumberFormat("en", {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2,
      }).format(val)}`;
    };

    return { params, formatedPrice };
  },
};
</script>