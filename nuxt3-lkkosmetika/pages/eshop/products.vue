<template>
    <div>
      <div v-if="config">
        <cart></cart>
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav mr-auto" style="display: inline-block">
              <li :class="['nav-item', 'dropdown', !selectedMan  ? 'selected' : '']">
                <a :class="['nav-link', 'my-nav-link']"  href="#" rel="nofollow" @click="unselectAll(man)">Vše</a>
              </li>
              <template v-for="man in config.manufacturers.filter(itm => itm.id != 'bez-vyrobce')">
                <li :class="['nav-item', 'dropdown', selectedMan == man.id ? 'selected' : '']" @mouseover="hoverItem = man.id" @mouseleave="hoverItem = null">
                    <template v-if="config.categories?.filter(itm => itm.manufacturer == man.id)?.length">
                        <a class="nav-link dropdown-toggle my-nav-link" href="#" role="button" rel="nofollow" aria-expanded="true" @click="changeMan(man)">
                            {{man.title}}
                        </a>
                        <div v-if="hoverItem == man.id" class="dropdown-menu" style="display: block">
                            <a class="nav-link dropdown-item my-nav-link" v-for="cat in config.categories.filter(itm => itm.manufacturer == man.id)" @click.self="changeCat(man, cat); hoverItem = null" href="#">{{cat.title}}</a>
                        </div>
                    </template>
                    <template v-else>
                        <a :class="['nav-link', 'my-nav-link']"  href="#" rel="nofollow" @click="changeMan(man)">{{man.title}}</a>
                    </template>
                </li>
              </template>
          </ul>
        </nav>
        <div class="usages" v-if="usages.some(itm => itm.matches)">
          <div class="title" style="font-weight: 500">Použití</div>
          <template v-for="item in usages">
            <div class="form-check form-check-inline" v-if="item.matches">
              <input type="checkbox" class="form-check-input" :id="item.id" :checked="selectedUsages.includes(item.id)" @change="changeUsage(item)">
              <label class="form-check-label" :for="item.id">{{item.title}} ({{item.matches}})</label>
            </div>
          </template>
        </div>
        <div v-if="category" class="cat-info">
          <div class="title">{{category.title}}</div>
          <div class="desc" style="padding-bottom: 5px;" v-if="category.description" v-html="category.description"></div>
        </div>
        <products-grid :productsList="filteredProducts" :config="config"></products-grid>
      </div>
    </div>
</template>

<script>

    export default defineComponent({
      data() {
        return {
          headerParams: null,
          productsList: [],
          config: null,
          selectedUsages: [],
          selectedMan: null,
          selectedCat: null,
          filteredProducts: [],
          hoverItem: null,
          usages: []
        }
      },
      computed: {
        category() {
          if (this.selectedCat && this.config) {
            const fItem = this.config.categories.find(itm => itm.id == this.selectedCat);
            return fItem;
          }
        }
      },
      methods: {
        changeUsage(item) {
          if (this.selectedUsages.includes(item.id)) {
            this.selectedUsages.splice(this.selectedUsages.indexOf(item.id), 1);
          } else {
            this.selectedUsages.push(item.id);
          }
          this.updateUrl();
        },
        unselectAll() {
          this.selectedMan = null;
          this.selectedCat = null;
          this.selectedUsages = [];
          this.updateUrl();
        },
        changeMan(item) {
          this.selectedMan = item.id;
          this.selectedCat = null;
          this.selectedUsages = [];
          this.updateUrl();
        },
        changeCat(man, item) {
          this.selectedMan = man.id;
          this.selectedCat = item.id;
          this.selectedUsages = [];
          this.updateUrl();
        },
        updateUrl() {
            const arr = [];
            if (this.selectedMan)
              arr.push('manufacturer=' + this.selectedMan);
            if (this.selectedCat)
              arr.push('category=' + this.selectedCat);
            if (this.selectedUsages.length > 0)
              arr.push('usages=' + this.selectedUsages.join(';') + '');

            if (arr.length == 0)
              history.pushState({},null,this.$route.path);
            else 
              history.pushState({},null,this.$route.path + '?' + arr.join('&'));

            this.filteredProducts = this.getFilteredProducts();
            this.updateUsagesMatches();
        },
        updateUsagesMatches() {
          const fProducts = this.getFilteredProducts(false); // pocitame jen produkty zverejnene, filtrovane dle vyrobce a kategorie
          for (let usage of this.usages) {
            if (!fProducts || !fProducts.length) {
              usage.matches = 0
              continue;
            }
            const matchesCount = fProducts.filter(itm => itm.data.usages?.includes(usage.id))?.length ?? 0
            usage.matches = matchesCount;
          }
        },
        getFilteredProducts(evaluateUsages = true) {
          const fProducts = this.productsList.filter(item => { 
            if(!item.data.published)
              return false;

            if (this.selectedMan && item.data.manufacturer != this.selectedMan)
              return false;
            
            if (this.selectedCat && (!item.data.categories || !item.data.categories.includes(this.selectedCat)))
              return false;

            if (evaluateUsages) {
              if (this.selectedUsages.length > 0) { 
                let hasMin1 = false;
                for (const selUsage of this.selectedUsages) {
                  if(item.data.usages.includes(selUsage))
                  hasMin1 = true;
                }
                if (!hasMin1)
                  return false;
              }     
            }      
            return true; 
          })
          return fProducts;
        }
      },
      async mounted() {
        let route = useRoute();
        this.selectedCat = route.query.category ?? null;
        this.selectedMan = route.query.manufacturer ?? null;
        this.selectedUsages = route.query.usages?.split(';') ?? [];
        try {
            this.config = JSON.parse(await $fetch(this.$config.bUrl + 'getConfig.php'));
            this.usages = JSON.parse(JSON.stringify(this.config.usages)); // deep copy
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
        this.filteredProducts = this.getFilteredProducts();
        this.updateUsagesMatches();
      }
    })
</script>

<style scoped>

  .dropdown-item {
    background-color: white;
  }

  .dropdown-item:hover {
    color: white !important;
    background: #bdb76b;
  }

  .cat-info,
  .usages {
    padding: 15px 20px;
  }

  .cat-info .title,
  .usages .title {
    padding-bottom: 5px;
  }

  .cat-info .title {
    font-size: 140%;
    font-weight: 500;
  }

  .navbar {
    background: white;
  }

  .navbar .nav-item  {
    padding: 10px 20px;
    background: white;
    border: 1px solid #bdb76b;
    margin-right: 8px;
    color: #bdb76b;
    font-weight: 500;
    font-size: 120%;
    float: left;
    border-radius: 2px;
  }

  .navbar .nav-item.selected,
  .nav-item:hover  {
    background: #bdb76b;
    color: white;
  }

  .my-nav-link,
  .nav-link {
    color: inherit !important;
  }



</style>