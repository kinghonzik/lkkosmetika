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
        try {
            this.config = JSON.parse(await $fetch(this.$config.bUrl + 'getConfig.php'));
        } catch(exception) {
            alert('Došlo k nějaké chybě');
            throw exception;
        }
        try {
          const data = await $fetch(this.$config.bUrl + 'getProducts.php');
          const dataParsered = JSON.parse(data);
          this.productsList = dataParsered;
        } catch(exception) {
            alert('Došlo k nějaké chybě');
            throw exception;
        }
      }
    })
</script>

<style scoped>
</style>