<template>
  <div>
    <html-header :params="headerParams"></html-header>
    <eshop-info-msg :config="config"></eshop-info-msg>
    <cart></cart>
      <div class="hrefbutton" @click="window.history.back()" style="width: fit-content">Zpět na seznam produktů </div>
    
    <div v-if="!ready" class="spinner-border" role="status">
      <span class="sr-only">Loading...</span>
    </div>

    <div v-if="product && config" class="product">
      <div v-if="true" class="title"><h1 style="font-size: 2rem">{{product.data.title}}</h1></div>
      <div class="flex-container">
        <div class="flex-item">
          <div @click="btClickLightbox(0)">
            <img :src="product.data.image?.src ? '/img/products/' + product.data.image.src : '#'" :alt="product.data.image?.alt ? product.data.image.alt : product.data.id"/>
          </div>
          <div>
            <div><img v-for="(aImg, index) in product.data.additionalImages" :key="aImg" @click="btClickLightbox(index + 1)" class="gallery-small" :src="'/img/products/' + aImg.src" :alt="aImg.alt ?? aImg.src"/></div>
          </div>
        </div>
        <div class="flex-item">
          <div style="min-width: 320px; max-width: 400px; width=auto; margin: auto;">
            <div v-if="false" style="text-align: left;" class="title"><h1 style="font-size: 2rem">{{product.data.title}}</h1></div>
            <div class="flex-container-2nd">
              <div class="flex-item-2nd" style="/*flex: 1 1 auto; -ms-flex: 1 1 auto; -webkit-flex: 1 1 auto;*/">
                <table class="par-table">
                  <tr v-if="product.data.manufacturer">
                    <td>výrobce: </td> <td style="font-style: italic;">{{config.manufacturers.find(itm => itm.id == product.data.manufacturer).title ?? 'není znám'}}</td>
                  </tr>
                  <tr v-for="spec in product.data.specifications">
                    <td>{{spec.title}}: </td> <td style="font-style: italic;">{{spec.value}}</td>
                  </tr>
                </table>
              </div>
              <div class="flex-item-2nd" style="padding-bottom: 30px;">
                <div v-if="product.data.variants?.length">
                  <small style="font-style: italic;">Varianta:</small>
                  <select class="form-control" v-model="variant">
                    <option v-for="pVar in product.data.variants" :value="pVar">{{pVar.title}}</option>
                  </select>
                  <div v-if="variant" class="stock-status">
                    <span :class="[variant.stockStatus == 'skladem' ? 'green' : 'orange']">{{variant.stockStatus}}</span>
                  </div>
                  <div class="price">{{variant.price ?? product.data.price}} {{config.priceUnit}}</div>
                  <div class="row" v-if="variant.stockStatus == 'skladem'"> 
                    <div class="form-group col-md-4">
                      <label for="pocet"><small style="font-style: italic;">počet:</small></label>
                      <input type="number" class="form-control" id="pocet" v-model="count">
                    </div>
                      <div class="hrefbutton col-md-7" @click="btClickInsertToChart()">Vložit do košíku</div>
                  </div>
                </div>
                <div v-else>
                  <div class="stock-status">
                    <span :class="[product.data.stockStatus == 'skladem' ? 'green' : 'orange']">{{product.data.stockStatus}}</span>
                </div>
                  <div class="price">{{product.data.price}} {{config.priceUnit}}</div>
                  <div class="row" v-if="product.data.stockStatus == 'skladem'"> 
                    <div class="form-group col-md-4">
                      <label for="pocet"><small style="font-style: italic;">počet:</small></label>
                      <input type="number" class="form-control" id="pocet" v-model="count">
                    </div>
                      <div class="hrefbutton col-md-7" @click="btClickInsertToChart()">Vložit do košíku</div>
                  </div>
                </div>
                <div v-if="showSuccessInsertMsg" class="successMsg">
                  Produkt máte v košíku <span style="font-size: 200%">☺</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="product.data.usages?.length">
        <div style="text-align: left; font-weight: 500; font-size: 120%;"> Použití </div>
        <div v-for="item of product.data.usages" style="width: fit-content; padding: 5px 10px 0 0; display: inline-block;">
          {{config.usages.find(itm => itm.id == item)?.title}} 
        </div>
      </div>
      <div class="flex-container">
        <div class="flex-item description" v-html="product.data.description"></div>
        <div class="flex-item"></div>
      </div>
      <light-box v-if="galleryImages?.length && lightBoxVisible" 
        :imagesList="galleryImages" :startIndex="lightBoxStartIndex" 
        @close="lightBoxVisible = false">
      </light-box>
    </div>
  </div>
</template>

<script>
    export default {
      generate: {
        crawler: true
      },
      data() {
        return {
          headerParams: null,
          product: null,
          config: null,
          variant: null,
          count: 1,
          lightBoxStartIndex: 0,
          lightBoxVisible: false,
          cookie: null,
          showSuccessInsertMsg: false,
          ready: false,
        }
      },
      computed: {
        window() { return window },
        title() {return this.product?.data.title ?? 'title-empty'},
        galleryImages() {
          const arr = [];
          if (this.product.data.image?.src)
            arr.push({src: '/img/products/' + this.product.data.image.src});

          if (this.product.data.additionalImages?.length)
            for (const img of this.product.data.additionalImages)
              arr.push({src: '/img/products/' + img.src});

          return arr;
        }
      },
      methods: {
        btClickInsertToChart() {
          // vlozi do cookie pocet produktu
          try {
            if (isNaN(this.count) || this.count < 1)
              this.count = 1;
              
            this.cookie.addProduct(this.product.id, this.count, this.variant?.title)
            this.showSuccessInsertMsg = true;
          } catch (exception)
          {
            alert('Oops, něco se nepovedlo, nahlašte nám to prosím na informace. Děkujeme.')
            console.error(exception)
          }
          setTimeout(() => { this.showSuccessInsertMsg = false}, 3000)
        },
        btClickLightbox(index) {
          this.lightBoxStartIndex = index;
          this.lightBoxVisible = true;
        }
      },
      async mounted() {
        try {
            this.config = JSON.parse(await $fetch(this.$config.bUrl + 'getConfig.php'));
        } catch(exception) {
            alert('Došlo k nějaké chybě');
            throw exception;
        }

        try {
          const route = useRoute();
          const productName = route.query.name; //route.params.product_name;

          // nas hosting zrejme neumi vyhledavani v JSONU :D
          //const data = await $fetch(this.$config.bUrl + 'getProductByID.php?id=' + productName);
          //const dataParsered = JSON.parse(data);
          //this.product = dataParsered;

          const data = await $fetch(this.$config.bUrl + 'getProducts.php');
          const productsList= JSON.parse(data);
          this.product = productsList?.find(itm => itm.data.id == productName);
            if (!this.product)
              throw ('Produkt ' + productName + ' nenalezen!')

        } catch(exception) {
            alert('Došlo k nějaké chybě');
            throw exception;
        }

        this.ready = true;

        // specifikace pro testovaci ucely
        //this.product.data.specifications = [{title: "mnozstvi", value: "15ml"},{title: "spec2", value: "18ks"}]

        if (this.product.data.variants?.length) {
          this.variant = this.product.data.variants.find(itm => itm.stockStatus == 'skladem')
          if (!this.variant) {
            this.variant = this.product.data.variants[0];
          }
        }
        this.cookie = useCookie();

        // vygenerovani hlavicky
        this.headerParams = {
            title: this.product.data.seoTitle ?? this.product.data.title,
            meta: [
                {
                  hid: `description`,
                  name: 'description',
                  content: this.product.data.seoDesc
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  content: this.product.data.seoKeywords
                }
            ],
        }
      }
    }
</script>

<style scoped>
  .product {
    padding: 10px;
    }


    .product .par-table {
      border: 0;
      margin-bottom: 10px;
    }

    .product .par-table td {
      padding: 5px 10px 5px 0;
    }
    .product .par-table tr:tnth-child(1) {
      font-style: italic;
    }

    .product .description {
      padding: 15px 0;
    }
    
    .flex-container {
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-justify-content: flex-start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        -webkit-align-content: flex-start;
        -ms-flex-line-pack: start;
        align-content: flex-start;
        -webkit-align-items: flex-start;
        -ms-flex-align: start;
        align-items: flex-start;
    }

    .flex-item {
        -webkit-order: 1;
        -ms-flex-order: 1;
        order: 1;
        -webkit-flex: 1 2 auto;
        -ms-flex: 1 2 auto;
        flex: 1 2 auto;
        -webkit-align-self: center;
        -ms-flex-item-align: center;
        align-self: stretch;
    }

    .flex-container-2nd {
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-justify-content: flex-start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        -webkit-align-content: stretch;
        -ms-flex-line-pack: stretch;
        align-content: stretch;
        -webkit-align-items: flex-start;
        -ms-flex-align: start;
        align-items: flex-start;
        height: 100%;
        width: 100%;
    }

    .flex-item-2nd {
        -webkit-order: 0;
        -ms-flex-order: 0;
        order: 0;
        -webkit-flex: 0 1 auto;
        -ms-flex: 0 1 auto;
        flex: 0 1 auto;
        -webkit-align-self: auto;
        -ms-flex-item-align: auto;
        align-self: auto;
        width: inherit;
    }

    .product .title {
        padding: 20px;
        font-size: 100%;
        font-weight: 700;
        text-align: center;
        padding-bottom: 40px;
    }

    .product img {
        max-width: 350px;
        max-height: 350px;
        display: block;
        margin: auto;
        cursor: pointer;
    }

    .product img.gallery-small {
        max-width: 170px;
        max-height: 170px;
        display: inline-block;
        cursor: pointer;
        margin-right: 2px;
        margin-top: 2px;
    }

    .hrefbutton {
        align-self: end;
        background-color: #bdb76b;
        padding: 8px 20px;
        color: white;
        font-weight: bold;
        text-align: center;
        margin-top: 5px;
        border-radius: 2px;
        transition: 0.5s;
        border: 0;
        text-decoration: none;
        font-weight: 500;
        display: block;
        margin: 15px;
        cursor: pointer;
    }

    .hrefbutton:hover {
        background-color: #151515;
    }

    .price {
        text-align: center;
        padding: 10px;
        font-weight: 600;
        font-size: 115%;
    }

    .description-short {
        padding: 10px;
    }

    .stock-status {
      font-weight: 600;
      text-align: center;
      padding: 10px;
      font-size: 100%;
    }

    .stock-status .orange {
        color: Orange;
    }
    
    .stock-status .green {
        color: MediumSeaGreen;
    }

    .successMsg {
      text-align: center;
      line-height: 20px;
      color: MediumSeaGreen;
      font-weight: 500;
      border: 1px solid mediumseagreen;
      padding: 10px;
    }
</style>