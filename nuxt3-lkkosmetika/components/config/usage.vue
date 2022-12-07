<template>
  <div>
    <div
      v-if="config && 'usages' in config"
      id="section-usages"
      class="section"
    >
      <div class="title">Použití</div>
      <div class="content">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Název</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item of config.usages">
              <td>{{ item.id }}</td>
              <td>{{ item.title }}</td>
              <td class="controls">
                <button
                  @click="openModalEdit(item)"
                  title="upravit"
                  class="btn btn-outline-warning btn-sm"
                >
                  ✎
                </button>
                <button
                  @click="del(item)"
                  title="smazat"
                  class="btn btn-outline-danger btn-sm"
                >
                  ✘
                </button>
                <button
                  @click="up(item)"
                  title="posunout nahoru"
                  class="btn btn-outline-dark btn-sm"
                >
                  🡱
                </button>
                <button
                  @click="down(item)"
                  title="posunout dolu"
                  class="btn btn-outline-dark btn-sm"
                >
                  🡳
                </button>
              </td>
              <td>
                <span class="matches" v-if="productsCount(item) > 0">
                  {{ productsCount(item) }} produktů
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <button @click="openModalAdd()" style="font-size: 120%;" class="btn btn-outline-success btn-sm">+</button>
    </div>

    <div v-if="dialogType" class="modal" tabindex="-1">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            {{ dialogType == "add" ? "Pridat použítí" : "Upravit použití" }}
          </div>
          <div class="modal-content">
            <div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Název: </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" v-model="catTitle" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">ID </label>
                <div class="col-sm-9">
                  <input
                    :disabled="productsCount(newObj) > 0 && dialogType== 'edit'"
                    type="text"
                    class="form-control"
                    v-model="newObj.id"
                  />
                </div>
                <div><small v-if="productsCount(newObj) > 0 && dialogType== 'edit'" class="warning-small" style="padding-left: 15px;"> ID nelze změnit, protože má vazbu na produkt</small></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
              <button @click="dialogType = null" class="btn btn-danger">zrušit</button>
              <button v-if="dialogType == 'add'" @click="add()" class="btn btn-success">
                přidat
              </button>
              <button v-else @click="edit()" class="btn btn-success ">upravit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>

  export default {
    props: ["products", "config", "utils"],
    data() {
      return {
        editedItem: null,
        dialogType: null,
        newObj: null,
      };
    },
    computed: {
      catTitle: {
        get() {
          return this.newObj?.title;
        },
        set(newVal) {
          this.newObj.title = newVal;
          if (this.newObj && this.dialogType == "add")
            this.newObj.id = this.utils.createStrId(this.newObj.title);
        },
      },
    },
    methods: {
      configSaveAndReload() {
        this.$emit('configSaveAndReload');
      },
      productsCount(usageObj) {
        if (!this.products) 
          return 0;

        if (!usageObj.id)
          return 0;
  
        const relProducts = this.products.filter(
          (itm) => { return itm.data.usages?.includes(usageObj.id) }
        );
  
        if (relProducts.length > 0) return relProducts.length;
  
        return 0;
      },
      checkTitle(name, array) {
        if (!name || name.length < 3) {
          alert("Název - minimálně 3 znaky!");
          return false;
        }
        if (array.find((itm) => itm.name == name)) {
          alert("Název již existuje!");
          return false;
        }
  
        return true;
      },
      checkID(id, array) {
        if (!id || id.length < 1) {
          alert("ID - minimálně 1 znak!");
          return false;
        }
        if (array.find((itm) => itm.id == id)) {
          alert("ID již existuje!");
          return false;
        }
        return true;
      },
      openModalAdd() {
        this.dialogType = "add";
        this.newObj = { id: "", title: "" };
      },
      openModalEdit(obj) {
        this.dialogType = "edit";
        this.editedItem = obj;
        this.newObj = { id: obj.id, title: obj.title }; // deep copy
      },
      add() {
        if (!this.checkID(this.newObj.id, this.config.usages)) 
          return;
        if (!this.checkTitle(this.newObj.title, this.config.usages)) 
          return;
        this.config.usages.push(this.newObj);
        this.newObj = null;
        this.dialogType = null;
        this.configSaveAndReload();
      },
      del(item) {
        const product = this.products.find((itm) => itm.data.usages?.includes(item.id));
        if (product) {
          alert("Toto použítí bylo nalezeno u produktu: " + product.data.title);
          return;
        }
        this.config.usages = this.config.usages.filter(
          (itm) => itm.id != item.id
        );
        this.dialogType = null;
        this.configSaveAndReload();
      },
      edit() {
        if (this.editedItem.title != this.newObj.title) {
          if (!this.checkTitle(this.newObj.title, this.config.usages)) 
            return;
        }
  
        if (this.editedItem.id != this.newObj.id) {
          if (!this.checkID(this.newObj.id, this.config.usages)) 
            return;
        }
  
        Object.assign(this.editedItem, this.newObj);
        this.newObj = null;
        this.dialogType = null;
        this.configSaveAndReload();
      },
      up(item) {
        const index = this.config.usages.indexOf(item);
        if (index > 0) {
          const itm1 = this.config.usages[index - 1];
          const itm2 = this.config.usages[index];
  
          this.config.usages[index - 1] = itm2;
          this.config.usages[index] = itm1;
          this.configSaveAndReload();
        }
      },
      down(item) {
        const index = this.config.usages.indexOf(item);
        if (index < this.config.usages.length - 1) {
          const itm1 = this.config.usages[index];
          const itm2 = this.config.usages[index + 1];
  
          this.config.usages[index + 1] = itm1;
          this.config.usages[index] = itm2;
          this.configSaveAndReload();
        }
      },
    },
    mounted() {},
  };
</script>
  
<style scoped>
  @import '/assets/css/modal-custom-style.css';
</style>
