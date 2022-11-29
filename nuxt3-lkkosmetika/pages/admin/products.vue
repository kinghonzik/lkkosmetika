<template>
  <div class="page-content" v-show="ready">
    <div class="control-panel">  
      <div class="col-sm-3 item">
        <label> Seřadit podle: </label>
        <select class="form-control form-control-sm" v-model="orderBy" @change="orderChanged()">
          <option value="title asc">Název vzestupně</option>
          <option value="title desc">Název sestupně</option>
          <option value="price asc">Cena vzestupně</option>
          <option value="price desc">Cena sestupně</option>
          <!--option value="views asc">Views vzestupně</option>
          <option value="views desc">Views sestupně</option-->
          <option value="id asc">ID vzestupně</option>
          <option value="id desc">ID sestupně</option>
        </select>
      </div>
      <div class="col-sm-3 item">
        <label> Filtr - zveřejnění </label>
        <select class="form-control form-control-sm" v-model="filterPublished" @change="filterPublishChanged()">
          <option value="*">Zobrazit vše</option>
          <option value="1">Zobrazit veřejné</option>
          <option value="0">Zobrazit skryté</option>
        </select>
      </div>
      <div class="col-sm-3 item">
        <label> Filtr - dostupnost </label>
        <select class="form-control form-control-sm" v-model="filterAvailability" @change="filterAvailabilityChanged()">
          <option value="*">Zobrazit vše</option>
          <option value="skladem">Skladem</option>
          <option value="ne-skladem">Jiné než 'skladem'</option>
        </select>
      </div>
      <div class="col-sm-3 item">
        <label> Filtr - výrobce </label>
        <select class="form-control form-control-sm" v-model="filterManufacturer" @change="filterManufacturerChanged()">
          <option value="*">Všichni výrobci</option>
          <option value="null">Bez výrobce</option>
          <option v-for="item in config?.manufacturers" :value="item.id">{{item.id}}</option>
        </select>
      </div>
      <button @click="btClickRemoveFilters()" class="btn btn-outline-dark btn-sm" style="margin: 10px 0 0 5px"> Zrušít filtry </button>
      <button @click="btClickAddProduct()" class="btn btn-outline-success btn-sm" style="margin: 10px 0 0 5px"> Přidat produkt </button>
    </div>
    <table class="table table-hover">
      <thead>
        <tr>
          <th></th>
          <th>Název</th>
          <th>Cena</th>
          <th>Zveřejněný</th>
          <th>Dostupnost</th>
          <th>Views</th>
          <th>ID</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item of products2View" class="product" :id="item.data.id" style="cursor: pointer" title="Kliknutím přejdete do editace" @click="btClickEditProduct(item)">
          <td>
            <div>
              <img :src="item.data.image ? '/img/products/' + item.data.image.src : '#'"/>
            </div>
          </td>
          <td>
            <div>{{item.data.title}} </div>
            <div v-if="item.data.manufacturer" style="font-size: 85%; font-style: italic; font-weight: 400;"> {{item.data.manufacturer}}</div>
            <div v-if="item.data.variants?.length" style="font-size: 75%; font-style: italic; font-weight: bolder; padding: 10px 0 0 0"> {{item.data.variants.length}} varianty</div>
          </td>
          <td>{{item.data.price}}</td>
          <td align="center"> <span :class="[item.data.published ? 'icon-ok' : 'icon-bad']"> {{item.data.published ? '✓' : '✕'}} </span></td>
          <td>
            <div v-if="item.data.variants?.length">
              {{getNumbVariantsInStocks(item)}} / {{item.data.variants.length}} skladem
            </div>
            <div v-else>{{item.data.stockStatus}}</div>
          </td>
          <td></td>
          <td>{{item.id}}</td>
          <td class="controls">
            <button
              @click="btClickEditProduct(item)"
              title="upravit"
              class="btn btn-outline-warning btn-sm"
            >
              ✎
            </button>
            <button
              @click="btClickDelete(item)"
              title="smazat"
              class="btn btn-outline-danger btn-sm"
            >
              ✘
            </button>
        </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>

  export default defineComponent({
      data() {
          return {
            orderBy: 'id asc',
            filterPublished: '*',
            filterAvailability: '*',
            filterManufacturer: '*',
            productsList: [],
            products2View: [],
            config: null,
            ready: false,
          }
      },
      methods: {
        orderChanged() {
          this.applyProductsOrdering();
          this.updateUrl();
        },
        filterPublishChanged() {
          this.filterPublishing();
          this.updateUrl();
        },
        filterAvailabilityChanged() {
          this.filterAvailabilityMethod();
          this.updateUrl();
        },
        filterManufacturerChanged() {
          this.filterManufacturerMethod();
          this.updateUrl();
        },
        btClickRemoveFilters() {
          this.orderBy = 'id asc';
          this.filterPublished = '*';
          this.filterAvailability = '*';
          this.filterManufacturer = '*';

          this.applyProductsOrdering();
          this.filterPublishing();
          this.filterAvailabilityMethod();
          this.filterManufacturerMethod();
          this.updateUrl();
        },
        btClickAddProduct() {
          navigateTo('/admin/product/newProduct')
        },
        btClickEditProduct(product) {
          navigateTo('/admin/product/' + product.data.id)
        },
        async btClickDelete(product) {
          if (!window.confirm('Opravdu si přejete smazat produkt? Bude navždy pryč.'))
            return;
          try {
            const response = await $fetch(this.$config.bUrl + 'delProduct.php', {method: 'DELETE',body: JSON.stringify(product)});
            if (response == 'true') {
              await this.reloadProducts();
              alert('Produkt byl smazán.')
            } else {
              alert('Smazání se nezdařilo. ' + response)
            }
          } catch(exception) {
              alert('Došlo k nějaké chybě');
              throw exception;
          }
        },
        filterManufacturerMethod() {
          this.products2View = this.productsList.filter(itm => {
            if (this.filterManufacturer == '*')
              return true;
            
            if (this.filterManufacturer == 'null')
              return !itm.manufacturer;
            
            return itm.data.manufacturer == this.filterManufacturer;
            
          });
        },
        filterAvailabilityMethod() {
          this.products2View = this.productsList.filter(itm => {
            if (this.filterAvailability == '*')
              return true;
            
            if (this.filterAvailability == 'skladem') {
              if (itm.variants?.length)
                return this.getNumbVariantsInStocks(itm) == item.data.variants.length;
              
              return itm.data.stockStatus == 'skladem';
            } else {
              if (itm.variants?.length)
                return this.getNumbVariantsInStocks(itm) < item.data.variants.length;
              
              return itm.data.stockStatus != 'skladem';
            }
          });
        },
        filterPublishing() {
          this.products2View = this.productsList.filter(itm => { 
            if (this.filterPublished == '*')
              return true;
            
            return itm.data.published == this.filterPublished;
          });
        },
        getNumbVariantsInStocks(item) {
          return item.data.variants.filter(itm => itm.stockStatus == 'skladem').length;
        },
        applyProductsOrdering() {
          if(!this.orderBy)
            return;
          const splited = this.orderBy.split(" ");
          if (splited.length < 2)
            return;

          const param = splited[0];
          const direction = splited[1];

          if (param == 'id') {
            this.products2View.sort((a,b) => {
              return direction == 'asc' ? a.id - b.id : b.id - a.id;
            })
          }
          if (param == 'price') {
            this.products2View.sort((a,b) => {
              return direction == 'asc' ? a.data.price - b.data.price : b.data.price - a.data.price;
            })
          }
          if (param == 'title') {
            this.products2View.sort((a,b) => {
              let fa = a.data.title.toLowerCase(),
                  fb = b.data.title.toLowerCase();

              if (fa < fb) 
                  return direction == 'asc' ? 1 : -1;
              if (fa > fb) 
                  return direction == 'asc' ? -1 : 1;
              return 0;
            })
          }
        },
        async reloadProducts() {
          try {
            const data = await $fetch(this.$config.bUrl + 'getProducts.php');
            const dataParsered = JSON.parse(data);
            this.productsList = dataParsered;
            this.products2View = [...this.productsList];
            this.applyProductsOrdering();
          } catch(exception) {
              alert('Došlo k nějaké chybě');
              throw exception;
          }
        },
        getOptionsObj() {
            return {
                orderBy: this.orderBy,
                filterPublished: this.filterPublished,
                filterAvailability: this.filterAvailability,
                filterManufacturer: this.filterManufacturer,
            }
        },
        updateUrl() {
            history.pushState({},null,this.$route.path + '?options=' + JSON.stringify(this.getOptionsObj()));
        },
      },
      async mounted() {
        if(this.$route.query?.options) {
            const obj = JSON.parse(this.$route.query.options);
            this.orderBy = obj.orderBy;
            this.filterPublished = obj.filterPublished;
            this.filterAvailability = obj.filterAvailability;
            this.filterManufacturer = obj.filterManufacturer;
        }
        try {
            this.config = JSON.parse(await $fetch(this.$config.bUrl + 'getConfig.php'));
        } catch(exception) {
            alert('Došlo k nějaké chybě');
            throw exception;
        }
        await this.reloadProducts();
        this.applyProductsOrdering();
        this.filterPublishing();
        this.filterAvailabilityMethod();
        this.filterManufacturerMethod();
        this.ready = true;
      }
  })
</script>

<style scoped>

  .product img {
    max-width: 120px;
    max-height: 120px;
    display: block;
    margin: auto;
    cursor: pointer;
  }

  .product td {
    vertical-align: middle;
  }

  .icon-ok {
    color: MediumSeaGreen;
    font-size: 140%;
    font-weight: bolder;
  }

  .icon-bad {
    color: indianred;
    font-size: 140%;
    font-weight: bolder;
  }

  .page-content {
    padding: 30px 0;
  }

  .control-panel .item {
    display: inline-block;
    padding: 0 5px;
  }

  .control-panel .item label {
    font-size: 90%;
    font-style: italic;
  }

  .control-panel {
    padding-bottom: 20px;
  }

  .controls button {
    margin: 0 5px;
  }
</style>