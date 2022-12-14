<template>
    <div class="omne">
        <html-header :params="headerParams"></html-header>
        <div class="flex-container">
            <div class="flex-border-text"><br>
                <h1 class="title">Lucie Kováříková</h1>
                <div class="desc">
                    <p><span>Jsem frekventantkou kosmetického institutu v&nbsp;Hradci Králové, kde jsem úspěšně zakončila profesní zkouškou akreditovaný kosmetický kurz pod záštitou ministerstva školství, mládeže a tělovýchovy. Dále jsem se pak vzdělávala v&nbsp;oboru a rozšiřovala si služby dalšími nadstavbovými kurzy jako například kurz depilace cukrovou pastou, mezoterapie, chemický peeling, liftingová masáž obličeje, mikromasáž očního okolí, péče o aknózní pleť, lash lifting a úprava obočí bio Hennou, laminace obočí. Kurzy nekončím, svoji práci miluji a mojí největší vášní je sledovat vývoj tohoto odvětví. Těším se na kurz depilace ve Španělsku, jelikož tam jsou největší mistři tohoto oboru. Chtěla bych se v&nbsp;depilacích zdokonalovat a jednou si třeba v&nbsp;Čechách otevřít depilační centrum, kam budou klienti moct chodit jako k&nbsp;lékaři a na počkání dostat svou službu v&nbsp;příjemném, až lázeňském prostředí, podobně jako tomu je například v&nbsp;Americe, kde se depilacemi proslavily sestry J., ale to už je jiný příběh </span></p>
                </div>
            </div>
            <div class="flex-border-image"><img src="/img/o-me.jpg" alt="" width="451" height="600"></div>
        </div>
    </div>	
</template>

<script>
    export default {
      data() {
        return {
          headerParams: null,
        }
      },
      async mounted() {
        const data = await $fetch('/api/data_o-mne');
        this.headerParams = data.header;
      }
    }
</script>

<style scoped>
    @import '~/assets/css/article-content.css';
</style>