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
                <tr>
                  <td>maily <span class="my-info-icon" title="Adresy na které budou chodit nové objednávky. 1 řádek = 1 adresa">i</span></td>
                  <td>
                    <textarea class="form-control" rows="3" v-model="config.mails"></textarea>
                  </td>
                </tr>
                <tr>
                  <td>mail - from <span class="my-info-icon" title="Adresa, ze které přijde mail zákazníkovi">i</span></td>
                  <td>
                    <input class="form-control" type="text" v-model="config.mailFrom"/>
                  </td>
                </tr>
                <tr>
                  <td>FB</td>
                  <td>
                    <input class="form-control" type="text" v-model="config.linkFacebook"/>
                  </td>
                </tr>
                <tr>
                  <td>Instagram</td>
                  <td>
                    <input class="form-control" type="text" v-model="config.linkInstagram"/>
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
      methods: {
        async configSaveAndReload() {
          try {
            const response = await $fetch(this.$config.bUrl + 'postConfig.php', { method: 'POST', body: this.config });
            console.log(response)
            if ('' +  response == 'true')
              alert('Uloženo v pořádku.')
          } catch(exception) {
              alert('Došlo k nějaké chybě při ukládání configu!');
              throw exception;
          }
          await this.fetchConfig();
        },
        async btClickUlozit() {
          this.configSaveAndReload();
        },
        async fetchConfig() {
          try {
            this.config = JSON.parse(await $fetch(this.$config.bUrl + 'getConfig.php'));
          } catch(exception) {
              alert('Došlo k nějaké chybě při načítání configu!');
              throw exception;
          }
        }
      },
      async mounted() {
        try {
          const data = await $fetch(this.$config.bUrl + 'getProducts.php');
          const dataParsered = JSON.parse(data);
          this.productsList = dataParsered;
        } catch(exception) {
            alert('Došlo k nějaké chybě');
            throw exception;
        }
        await this.fetchConfig();
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