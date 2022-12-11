<template>
    <div>
        <div v-if="errors?.length">
          <div class="title">Poslednich 1000 erroru ..</div>
          <div class="content">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th style="width: 1%">ID</th>
                  <th style="width: 1%">Datum</th>
                  <th style="width: 1%; min-width: 150px;"> Typ</th>
                  <th>Zpráva</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item of errors">
                  <td style="white-space: nowrap" >{{item.id}}</td>
                  <td style="white-space: nowrap">{{item.datetime}}</td>
                  <td style="white-space: nowrap">{{item.type}}</td>
                  <td>{{item.msg}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </div>
</template>

<script>
    export default defineComponent({
        data() {
            return {
                errors: [],
            }
        },
        async mounted() {
            try {
                const response = await $fetch(this.$config.bUrl + 'getErrors.php');
                const dataParsered = JSON.parse(response);
                console.log(dataParsered);
                this.errors = dataParsered;
            } catch(exception) {
                alert('Došlo k nějaké chybě při načítání dat.');
                throw exception;
            }
        }
    })
</script>

<style scoped>

.title {
    font-size: 120%;
    font-weight: 400;
    padding: 20px;
}
.content {
    padding: 0px 10px 10px 10px;
}
</style>