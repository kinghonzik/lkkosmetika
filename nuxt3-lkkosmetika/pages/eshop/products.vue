<template>
    <div>
      <div v-if="config">
        <cart></cart>
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav mr-auto" style="display: inline-block">
              <li :class="['nav-item', 'dropdown', !selectedMan  ? 'selected' : '']">
                <a :class="['nav-link', 'my-nav-link']"  href="#" @click="unselectAll(man)">Vše</a>
              </li>
              <li :class="['nav-item', 'dropdown', selectedMan == man.id ? 'selected' : '']" 
              v-for="man in config.manufacturers.filter(itm => itm.id != 'bez-vyrobce')"
              @mouseover="hoverItem = man.id" @mouseleave="hoverItem = null">
                  <template v-if="config.categories?.filter(itm => itm.manufacturer == man.id)?.length">
                      <a class="nav-link dropdown-toggle my-nav-link" href="#" @click="changeMan(man)" role="button" aria-expanded="true">
                          {{man.title}}
                      </a>
                      <div v-if="hoverItem == man.id" class="dropdown-menu" style="display: block">
                          <a class="dropdown-item my-dr-item" v-for="cat in config.categories.filter(itm => itm.manufacturer == man.id)" @click="changeCat(man, cat)" href="#">{{cat.title}}</a>
                      </div>
                  </template>
                  <template v-else>
                      <a :class="['nav-link', 'my-nav-link']"  href="#" @click="changeMan(man)">{{man.title}}</a>
                  </template>
              </li>
          </ul>
        </nav>

            <div>
                <span :class="['dropdown', !selectedMan  ? 'selected' : '']">
                  <a :class="['my-nav-link']"  href="#" @click="unselectAll(man)">Vše</a>
                </span>
                <span :class="['dropdown', selectedMan == man.id ? 'selected' : '']" 
                v-for="man in config.manufacturers.filter(itm => itm.id != 'bez-vyrobce')"
                @mouseover="hoverItem = man.id" @mouseleave="hoverItem = null">
                    <template v-if="config.categories?.filter(itm => itm.manufacturer == man.id)?.length">
                        <a class="dropdown-toggle my-nav-link" href="#" @click="changeMan(man)" role="button" aria-expanded="true">
                            {{man.title}}
                        </a>
                        <div v-if="hoverItem == man.id" class="dropdown-menu" style="display: block">
                            <a class="dropdown-item my-dr-item" v-for="cat in config.categories.filter(itm => itm.manufacturer == man.id)" @click="changeCat(man, cat)" href="#">{{cat.title}}</a>
                        </div>
                    </template>
                    <template v-else>
                        <a :class="['my-nav-link']"  href="#" @click="changeMan(man)">{{man.title}}</a>
                    </template>
                </span>
            </div>

        <div style="padding: 15px">
          <div class="title" style="padding-bottom: 5px;">Použití</div>
          <div class="form-check form-check-inline" v-for="item in config.usages">
            <input type="checkbox" class="form-check-input" :checked="selectedUsages.includes(item.id)" @change="changeUsage(item)">
            <label class="form-check-label">{{item.title}}</label>
          </div>
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

            this.filterProducts();
        },
        filterProducts() {
          this.filteredProducts = this.productsList.filter(item => { 
            if(!item.data.published)
              return false;

            if (this.selectedMan && item.data.manufacturer != this.selectedMan)
              return false;
            
            if (this.selectedCat && (!item.data.categories || !item.data.categories.includes(this.selectedCat)))
              return false;

            if (this.selectedUsages.length > 0) { 
              let hasMin1 = false;
              for (const selUsage of this.selectedUsages) {
                if(item.data.usages.includes(selUsage))
                hasMin1 = true;
              }

              if (!hasMin1)
                return false;
            }
              
            return true; 
          })
        }
      },
      async mounted() {
        let route = useRoute();
        this.selectedCat = route.query.category ?? null;
        this.selectedMan = route.query.manufacturer ?? null;
        this.selectedUsages = route.query.usages?.split(';') ?? [];

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

        this.filterProducts();
      }
    })
</script>

<style scoped>

  .navbar {
    background: white;
  }

  .navbar .nav-item  {
    padding: 10px 20px;
    background: white;
    border: 1px solid #bdb76b;
    /* border-radius: 50px; */
    margin-right: 8px;
    color: #bdb76b;
    font-weight: 500;
    font-size: 120%;
  }

  .navbar .nav-item.selected  {
    background: #bdb76b;
    border: 1px solid #bdb76b;
    /* border-radius: 50px; */
    color: white;
  }

  .my-nav-link {
    color: #bdb76b !important;
  }

  .selected .my-nav-link {
    color: white !important;
  }

  .my-dr-item {
    color: #bdb76b !important;
  }

</style>