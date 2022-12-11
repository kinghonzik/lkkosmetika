<template>
    <div class="page-content"> 
        <div>
            <button @click="btClickSave()" class="btn btn-success " style="margin: 10px 0 20px 20px"> Uložit </button>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-form-label"> Obchodní podmínky </label>
            <div class="col-sm-12">
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
                v-model="conditions" 
              />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-form-label"> GDPR </label>
            <div class="col-sm-12">
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
                v-model="GDPR" 
              />
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
    export default defineComponent({
      data() {
        return {
          conditions: '',
          GDPR: '',
        }
      },
      components: {
        'editor': Editor
      },
      methods: {
        async btClickSave() {
            try {
                const obj2send = JSON.stringify({ conditions: this.conditions, GDPR: this.GDPR, token: useAuth().value?.token });
                const response = await fetch(this.$config.bUrl + 'putDocs.php', { method: 'PUT', body: obj2send })
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
                alert('Úspěšně uloženo.')
            } catch(exception) {
                alert('Došlo k nějaké chybě');
                throw exception;
            }
          },
      },
      async mounted() {
        try {
          const response = await $fetch(this.$config.bUrl + 'getDocs.php');
          const dataParsered = JSON.parse(response);
          this.conditions = dataParsered.conditions;
          this.GDPR = dataParsered.GDPR;
        } catch(exception) {
            alert('Došlo k nějaké chybě při načítání dat.');
            throw exception;
        }
      }

    })
</script>

<style scoped>
  @import '/assets/css/modal-custom-style.css';

  .page-content {
    padding: 30px 0;
    max-width: 1200px;
    margin: auto;
  }

  #section-other table td {
    border: 0;
  }

  .col-form-label {
    font-weight: 500;
  }

</style>