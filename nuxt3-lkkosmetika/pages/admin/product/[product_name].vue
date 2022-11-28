<template>
  <div class="page-content">
    <div v-if="product && config">
      <div class="title">{{formType == 'new' ? 'Nový produkt' : 'Produkt id: ' + product.id}}</div>
      <div>
        <button @click="btClickSave()" class="btn btn-success " style="margin: 10px 0 20px 20px"> Uložit a zavřít </button>
        <button @click="btClickGoBack()" class="btn btn-danger " style="margin: 10px 0 20px 5px"> Zavřít bez uložení </button>
      </div>
      <div class="section-inline col-sm-12">
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Název </label>
            <div class="col-sm-6">
              <input type="text" class="form-control" v-model="productTitle" />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Alias </label>
            <div class="col-sm-6">
              <input type="text" class="form-control" v-model="product.data.id"/>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Zveřejněný </label>
            <div class="col-sm-6">
              <button :class="['btn', product.data.published ? 'btn-success' : 'btn-danger']"
               @click="product.data.published = !product.data.published"
               title="kliknutím změníte"> {{product.data.published ? 'Ano' : 'Ne'}}            
              </button>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Cena </label>
            <div v-if="product.data.variants?.length" class="col-sm-3" style="color: indianred">
              {{'dle varianty!'}}
            </div>
            <div v-else class="col-sm-3">
              <input type="number" class="form-control" min="1" v-model="product.data.price"/>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Dostupnost </label>
            <div v-if="product.data.variants?.length" class="col-sm-3" style="color: indianred">
              {{'dle varianty!'}}
            </div>
            <div v-else class="col-sm-3">
              <select class="form-control" v-model="product.data.stockStatus">
                <option v-for="item in stockStatusList" :value="item">{{item}}</option>
              </select>
            </div>        
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Výrobce </label>
            <div class="col-sm-3">
              <select class="form-control" v-model="product.data.manufacturer">
                <option v-for="item in config?.manufacturers" :value="item.id">{{item.id}}</option>
                <option value="">Bez výrobce</option>
              </select>
            </div>
          </div>
        </div>
        <div id="section_images" class="section-inline col-sm-12">
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Hlavní obrázek</label>
            <div class="col-sm-7">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFile" @change="imgUpload($event)">
                <label class="custom-file-label" for="customFile">{{product.data.image.dataBase64String ? product.data.image.name : product.data.image.src }}</label>
              </div>
              <img v-if="product.data.image.src" :src="product.data.image.dataBase64String ? product.data.image.src : '/img/products/' + product.data.image.src"/>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Další obrázky</label>
            <div class="col-sm-7">
              <div style="margin-bottom: 10px;" v-for="(item, index) in product.data.additionalImages">
                <template v-if="item.status != 'delete'">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" :id="'img' + index" @change="imgUpload($event, index)">
                    <label class="custom-file-label" :for="'img' + index">{{item.dataBase64String ? item.name : item.src}}</label>
                  </div>
                  <img v-if="item.src" :src="item.dataBase64String ? item.src : '/img/products/' + item.src"/>
                  <button 
                    v-if="item.src"
                    @click="btClickRemoveImage(item, index)" 
                    class="btn btn-outline-danger btn-sm" 
                    style="margin: 10px 0 0 5px; float: right" title="zrušit obrázek"> ✘ 
                  </button>
                </template>
              </div>         
              <div>
                <button @click="btClickAddAditionalImage()" class="btn btn-outline-success btn-sm" style="margin: 10px 5px 0 0"> Přidat obrázek </button>
              </div>
            </div>
          </div>
        </div>
        <div id="section_variants" class="col-sm-12">
          <div class="title"> Varianty </div>
          <table v-if="product.data.variants?.length" class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Název</th>
                <th>Cena</th>
                <th>Dostupnost</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in product.data.variants">
                <td>{{index + 1}}</td>
                <td><input class="form-control" type="text" v-model="item.title"/></td>
                <td><input class="form-control" type="number" min="1" v-model="item.price"/></td>
                <td>             
                 <select class="form-control" v-model="item.stockStatus">
                    <option v-for="itm in stockStatusList" :value="itm">{{itm}}</option>
                  </select>
                </td>
                <td>
                  <button 
                    @click="product.data.variants.splice(index, 1)" 
                    class="btn btn-outline-danger btn-sm" 
                    style="margin: 10px 0 0 5px; float: right" title="zrušit variantu"> ✘ 
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <div>
            <button @click="product.data.variants.push({title: ('varianta' + (product.data.variants.length + 1)), price: '100', stockStatus: 'skladem'})" 
              class="btn btn-outline-success btn-sm" style="margin: 10px 5px 0 0"> Přidat variantu
            </button>
          </div>
        </div>
        <!-- Specifikace -->
        <div id="section_specifications" class="col-sm-12">
          <div class="title"> Specifikace </div>
          <table v-if="product.data.specifications?.length" class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Název</th>
                <th>Hodnota</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in product.data.specifications">
                <td>{{index + 1}}</td>
                <td><input class="form-control" type="text" v-model="item.title"/></td>
                <td><input class="form-control" type="text" v-model="item.value"/></td>
                <td>
                  <button 
                    @click="product.data.specifications.splice(index, 1)" 
                    class="btn btn-outline-danger btn-sm" 
                    style="margin: 10px 0 0 5px; float: right" title="zrušit položku"> ✘ 
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <div>
            <button @click="product.data.specifications.push({title: 'váha', value: '2kg'})" 
              class="btn btn-outline-success btn-sm" style="margin: 10px 5px 0 0"> Přidat položku
            </button>
          </div>
        </div>
        <div id="section_usages" class="col-sm-12">
          <div class="title"> Použití</div>
          <div class="form-check form-check-inline" v-for="(item, index) in config.usages">
            <input class="form-check-input" type="checkbox" value="" :id="'usage' + index" v-model="usagesChecked[index]" @change="usagesChanged()">
            <label class="form-check-label" :for="'usage' + index">
              {{item.title}}
            </label>
          </div>
        </div>
        <div id="section_categories" class="col-sm-12">
          <div class="title"> Kategorie</div>
          <div class="form-check form-check-inline" v-for="(item, index) in config.categories">
            <input class="form-check-input" type="checkbox" value="" :id="'cat' + index" v-model="categoriesChecked[index]" @change="categoriesChanged()">
            <label class="form-check-label" :for="'cat' + index">
              {{item.title}}
            </label>
          </div>
        </div>
        <div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Krátký popisek (v gridu) </label>
            <div class="col-sm-9">
              <!--textarea class="form-control" id="desc_short" rows="4" v-model="product.data.descriptionShort"></textarea-->
              <editor :init="{ 
                  height: 200, 
                  plugins: [
                    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
                    'media', 'table', 'emoticons', 'template', 'help'
                  ],
                  toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                    'forecolor backcolor emoticons | help',
                  menu: {
                    favs: { title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons' }
                  }
                }" 
                v-model="product.data.descriptionShort" 
              />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Dlouhý popis (v detailu) </label>
            <div class="col-sm-9">
              <!--textarea class="form-control" id="desc_long" rows="15" v-model="product.data.description"></textarea-->
              <editor :init="{ 
                  height: 500, 
                  plugins: [
                    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
                    'media', 'table', 'emoticons', 'template', 'help'
                  ],
                  toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                    'forecolor backcolor emoticons | help',
                  menu: {
                    favs: { title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons' }
                  }
                }" 
                v-model="product.data.description" 
              />
            </div>
          </div>
        </div>
      </div>


    </div>
</template>
  
<script>
import Product from '/models/product.ts';
import ProductImage from '/models/productImage.ts';
import StockStatusList from '/models/stockStatusList';
import Editor from '@tinymce/tinymce-vue'

    export default defineComponent({
        data() {
            return {
              product: null,
              config: null,
              formType: 'new',
              usagesChecked: [],
              categoriesChecked: [],
              utils: null,
              productlIST: [],
            }
        },
        
        components: {
          'editor': Editor
        },
        computed: {
          stockStatusList() {
            return StockStatusList;
          },
          productTitle: {
            get() {
              return this.product.data.title;
            },
            set(newVal) {
              this.product.data.title = newVal;
              if (this.formType == "new")
                this.product.data.id = this.utils.createStrId(this.product.data.title);
            },
          },
        },
        methods: {
          async btClickSave() {
            if (this.formType == 'edit') {
              var response = await $fetch('/api/data_product_put', {
                method: 'PUT',
                body: { product: this.product }
              });

              if (response == 'true') {
                window.history.back();
              } else{
                alert('Něco se nepovedlo: ' + response);
                console.log(response)
                throw response;
              }
            }
            if (this.formType == 'new') {
              var response = await $fetch('/api/data_product_post', {
                method: 'POST',
                body: { product: this.product }
              });

              if (response == 'true') {
                window.history.back();
              } else{
                alert('Něco se nepovedlo: ' + response);
                console.log(response)
                throw response;
              }
            }
          },
          btClickGoBack() {
            window.history.back();
          },
          usagesChanged() {
            const newArr = [];
            for(const index in this.config.usages) {
              const item = this.config.usages[index];
              if (this.usagesChecked[index])
                newArr.push(item.id);
            }
            this.product.data.usages = newArr;
          },
          categoriesChanged() {
            const newArr = [];
            for(const index in this.config.categories) {
              const item = this.config.categories[index];
              if (this.categoriesChecked[index])
                newArr.push(item.id);
            }
            this.product.data.categories = newArr;
          },
          btClickAddAditionalImage() {
            if (!this.product.data.additionalImages)
              this.product.data.additionalImages = [];
            this.product.data.additionalImages.push(new ProductImage);
          },
          btClickRemoveImage(item, index) {
            if(item.savedOnServer)
              item.status="delete";
            else {
              this.product.data.additionalImages.splice(index, 1);
            }
          },
          async imgUpload(event, arrIndex = undefined) {
            if (!event.target.files.length)
              return;
            const blobFile = event.target.files[0];
            if (arrIndex == undefined) {
              this.product.data.image.dataBase64String = await this.utils.blobToBase64(blobFile);
              this.product.data.image.name = blobFile.name;
              this.product.data.image.src = URL.createObjectURL(blobFile);
              this.product.data.image.status = 'update';
              this.product.data.image.savedOnServer = false;
            } else {
              this.product.data.additionalImages[arrIndex].dataBase64String = await this.utils.blobToBase64(blobFile);
              this.product.data.additionalImages[arrIndex].name = blobFile.name;
              this.product.data.additionalImages[arrIndex].src = URL.createObjectURL(blobFile);
              this.product.data.additionalImages[arrIndex].status = 'update';
              this.product.data.additionalImages[arrIndex].savedOnServer = false;
            }
            console.log(this.product)
          }
        },
        async mounted() {
            const config = await $fetch('/api/data_config');
            this.config = JSON.parse(config);

            this.utils = useUtils();
            const data = await $fetch('/api/data_products');
            const dataParsered = JSON.parse(data);
            this.productsList = dataParsered;

            const route = useRoute();
            const productName = route.params.product_name;
            if (productName == 'newProduct') {
              this.product = new Product;
            } else {
                try {
                const productObj = this.productsList.find(itm => itm.data.id == productName);
                if (!productObj)
                  throw ('Produkt ' + productName + ' nenalezen!')
                
                this.product = new Product; 
                this.product = Object.assign(this.product, productObj);
                
                this.formType = 'edit';
                } catch(error) {
                    alert(error)
                    throw error;
                }
            }
            
            // usages
            const usages = [];
            for(const item of this.config.usages) 
              usages.push(this.product.data.usages.includes(item.id))
            this.usagesChecked = usages;

            // categories
            const cats = [];
            for(const item of this.config.categories) 
              cats.push(this.product.data.categories.includes(item.id))
            this.categoriesChecked = cats;
        }
    })
</script>
  
<style scoped>

    .page-content {
      padding: 20px 0;
    }

    .title {
      font-size: 180%;
      text-align: center;
      padding: 30px;
    }

    .section-inline {
      display: inline-grid;
    }

    .form-group,
    .form-check {
      margin: 10px 0;
    }

    .stock-status .orange {
        color: Orange;
    }
    .stock-status .green {
        color: MediumSeaGreen;
    }

    #section_images img {
      padding: 5px 0;
      max-width: 150px;
      max-height: 150px;
    }

    #section_usages {
      padding: 10px;
    }

    #section_usages .title,
    #section_categories .title,
    #section_variants .title,
    #section_specifications .title {
      padding: 10px;
      padding-left: 0;
      padding-top: 20px;
      font-size: 160%;
      text-align: left;
    }

    .form-check {
      margin-right: 20px;
    }
</style>