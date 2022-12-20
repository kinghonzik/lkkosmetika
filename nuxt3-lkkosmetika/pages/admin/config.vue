<template>
    <div class="page-content"> 
      <div v-if="config">
        <div v-if="config" class="coltrol-panel">
          <button @click="btClickUlozit()" class="btn btn-success"> Uložit konfiguraci </button>
        </div>
        <!-- Sekce Základ -->
        <div v-if="config" id="section-other" class="section col-sm-12">
          <div class="title">Základní nastavení</div>
          <div class="content">

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-3 col-form-label">DPH - %</label>
              <div class="col-sm-3">
                <input class="form-control" type="number" v-model="config.dph"/>
              </div>
            </div>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-3 col-form-label">Měna</label>
              <div class="col-sm-3">
                <input class="form-control" type="text" v-model="config.priceUnit"/>
              </div>
            </div>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-3 col-form-label">Maily</label>
              <div class="col-sm-3">
                <textarea class="form-control" rows="3" v-model="config.mails"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-3 col-form-label">Mail - from</label>
              <div class="col-sm-3">
                <input class="form-control" type="text" v-model="config.mailFrom"/>
              </div>
            </div>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-3 col-form-label">Příjem objednávek</label>
              <div class="col-sm-9">
                <button v-if="config.newOrdersAllowed" class="btn btn-success btn-sm" @click="config.newOrdersAllowed = !config.newOrdersAllowed"> Povoleno </button>
                <button v-else class="btn btn-danger btn-sm" @click="config.newOrdersAllowed = !config.newOrdersAllowed"> Zakázáno </button>
              </div>
            </div>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-3 col-form-label">Informativní zpráva pro zákazníky</label>
              <div class="col-sm-9">
                <textarea class="form-control" rows="3" v-model="config.infoMsg"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-3 col-form-label">Číslo účtu (platba předem apod...)</label>
              <div class="col-sm-3">
                <input class="form-control" type="text" v-model="config.accNumber"/>
              </div>
            </div>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-3 col-form-label">FB link</label>
              <div class="col-sm-9">
                <input class="form-control" type="text" v-model="config.linkFacebook"/>
              </div>
            </div>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-3 col-form-label">Instagram link</label>
              <div class="col-sm-9">
                <input class="form-control" type="text" v-model="config.linkInstagram"/>
              </div>
            </div>
          </div>
        </div>
        <div v-if="config" id="section-shipping" class="section col-sm-12">
          <div class="title">Doprava</div>
          <div class="content">
            <table class="table">
              <thead>
                <tr>
                  <th style="width: 1%">#</th>
                  <th>Název</th>
                  <th style="width: 1%"></th>
                  <th style="width: 1%; min-width: 150px;"> Cena</th>
                  <th>Poznámka</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item of config.shippingOptions">
                  <td style="white-space: nowrap" >{{item.id}}</td>
                  <td><input class="form-control" type="text" v-model="item.title"/></td>
                  <td>
                    <button v-if="item.allowed" class="btn btn-success btn-sm" @click="item.allowed = !item.allowed"> Povoleno </button>
                    <button v-else class="btn btn-danger btn-sm" @click="item.allowed = !item.allowed"> Zakázáno </button>
                  </td>
                  <td><input class="form-control" type="number" min="0" v-model="item.price"/></td>
                  <td><input class="form-control" type="text" v-model="item.hint"/></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-if="config" id="section-payment" class="section col-sm-12">
          <div class="title">Platba</div>
          <div class="content">
            <table class="table">
              <thead>
                <tr>
                  <th style="width: 1%">#</th>
                  <th>Název</th>
                  <th style="width: 1%"></th>
                  <th style="width: 1%; min-width: 150px;"> Cena</th>
                  <th>Poznámka</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item of config.paymentOptions">
                  <td style="white-space: nowrap" >{{item.id}}</td>
                  <td><input class="form-control" type="text" v-model="item.title"/></td>
                  <td>
                    <button v-if="item.allowed" class="btn btn-success btn-sm" @click="item.allowed = !item.allowed"> Povoleno </button>
                    <button v-else class="btn btn-danger btn-sm" @click="item.allowed = !item.allowed"> Zakázáno </button>
                  </td>
                  <td><input class="form-control" type="number" min="0" v-model="item.price"/></td>
                  <td><input class="form-control" type="text" v-model="item.hint"/></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
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
        <div v-if="config" class="coltrol-panel">
          <button @click="btClickUlozit()" class="btn btn-success"> Uložit konfiguraci </button>
        </div>
      </div>
    </div>
</template>

<script>

import DefShippingOptions from '/models/defShippingOptions.ts';
import DefPaymentOptions from '/models/defPaymentOptions.ts';

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
            const obj2send = JSON.stringify({ config: this.config, token: useAuth().value?.token });
            const response = await fetch(this.$config.bUrl + 'postConfig.php', { method: 'POST', body: obj2send })
            const errorStatus = response.status;
            if (errorStatus == 401) {
              alert('Nejsi prihlasen nebo tvé přihlášení expirovalo.')
              window.location.reload();
              return;
            }
            if (errorStatus == 403 || errorStatus == 404) {
              alert('Došlo k nějaké chybě na serveru')
              return;
            }
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
            if (!this.config.shippingOptions) 
              this.config.shippingOptions = DefShippingOptions;
            if (!this.config.paymentOptions) 
              this.config.paymentOptions = DefPaymentOptions;
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

  .coltrol-panel {
    padding-top: 20px; padding-left: 10px;
  }

</style>