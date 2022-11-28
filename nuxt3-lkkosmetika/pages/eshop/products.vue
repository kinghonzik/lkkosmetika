<template>
    <div>
      <cart></cart>
      <products-grid :productsList="productsList" :config="config"></products-grid>
    </div>
</template>

<script>
    export default defineComponent({
      data() {
        return {
          headerParams: null,
          productsList: [],
          config: null,
        }
      },
      methods: {
      },
      async mounted() {
        const config = await $fetch('/api/data_config');
        this.config = JSON.parse(config);
        console.log(this.config);

        console.log('mounted')
        const data = await $fetch('/api/data_products');
        const dataParsered = JSON.parse(data);
        this.productsList = dataParsered;
        console.log(this.productsList);
      }
    })
</script>

<style scoped>
</style>