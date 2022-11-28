<template>
    <div class="page-content"> 
      <div v-if="config">
        <config-manufacturer 
          :products="productsList" :config="config" :utils="utils" 
          @configSaveAndReload="configSaveAndReload()">
        </config-manufacturer>

        <config-category
          :products="productsList" :config="config" :utils="utils" 
          @configSaveAndReload="configSaveAndReload()">
        </config-category>

        <config-usage
          :products="productsList" :config="config" :utils="utils" 
          @configSaveAndReload="configSaveAndReload()">
        </config-usage>
        <!-- Sekce Ostatní -->
        <div v-if="config" id="section-other" class="section col-sm-6">
          <div class="title">Ostatní</div>
          <div class="content">
            <table class="table">
              <tbody>
                <tr>
                  <td>DPH - %</td><td><input class="form-control" type="number" v-model="config.dph"/></td>
                </tr>
                <tr>
                  <td>měna</td>
                  <td>
                    <input class="form-control" type="text" v-model="config.priceUnit"/>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div v-if="config" class="coltrol-panel">
          <button @click="btClickUlozit()" class="btn btn-success"> Uložit konfiguraci </button>
        </div>
      </div>
    </div>
</template>

<script>
    export default defineComponent({
      data() {
        return {
          utils: null,
          manForm: null,
          catForm: null,
          usageForm: null,
          manDialog: null,
          manObj: null,
          newTitle: '',
          newCatName: '',
          newUsageName: '',
          config: null,
          productsList: [],
          manufacturers: null,
          usages: null,
          categories: null,
        }
      },
      computed: {
        manTitle: {
          get() { return this.manObj?.title; },
          set(newVal) { 
            this.manObj.title = newVal;
            if (this.manObj && this.manDialog == 'add')
              this.manObj.id = this.utils.createStrId(this.manObj.title)
          }
        },
      },
      methods: {
        async configSaveAndReload() {
          var response = await $fetch('/api/data_config_put', {
              method: 'POST',
              body: { config: this.config }
          } );
          this.config = JSON.parse(await $fetch('/api/data_config'));
        },
        async btClickUlozit() {
          this.configSaveAndReload();
        },
      },
      async mounted() {
        const data = await $fetch('/api/data_products');
        const dataParsered = JSON.parse(data);
        this.productsList = dataParsered;
        this.config = JSON.parse(await $fetch('/api/data_config'));
        this.utils = useUtils();
      }

    })
</script>

<style scoped>
  @import '/assets/css/modal-custom-style.css';

  .page-content {
    padding-bottom: 30px;
  }

  #section-other table td {
    border: 0;
  }

</style>