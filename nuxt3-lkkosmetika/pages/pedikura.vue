<template>
    <div>
        <html-header :params="headerParams"></html-header>
        <div class="content-header">
        <h1 class="title">ZÁKLADNÍ MOKRÁ PEDIKÚRA</h1>
        <div class="desc">
            <p><span><strong>Ke své práci používám kvalitní dermapero značky Dermica of Switzerland, které má bezkonkurenční sílu v&nbsp;motoru. 900 vpichů za sekundu pomocí 12ti jehel zapracují sérum do kůže. Vpichy vytvoří na kůži mikrotraumata, buňky se začnou obnovovat, aby zahojily poraněnou tkáň a nastává tvorba vlastního kolagenu. Procedura reguluje nadměrný pigment, zlepšuje stav akné, zmírňuje a vyhlazuje jemné vrásky, zlepšuje elasticitu a sílu kůže, zlepšuje stav jizev - hlavně po akné/neštovicích ale i strie a celkově omlazuje pleť, krk a dekolt.</strong></span></p>
            <p><span><strong>Po této proceduře se nedoporučuje 24 hod aplikovat make-up a také se na 2-3 dny vyhnout slunění včetně solárií.</strong></span></p>
        </div>
        </div>
        <div class="flex-container">
            <div class="flex-border-image">
                <div class="rectangle-bordered">&nbsp;</div>
                <img src="/img/obr.pedi1.jpg" alt="pedikura" width="474" height="316">
            </div>
            <div class="flex-border-text">
                <h3>ZÁKLADNÍ MOKRÁ PEDIKÚRA</h3>
                <p><span class="text-font-size">Pedikúra kosmetikou Peclavus</span></p>
                <p><span class="text-font-size"><em>Ošetření zahrnuje: solnou lázeň 10 – 20 min, důkladné osušení, dezinfekci, ošetření kůžičky (kutikula), vyčištění nehtového valu a nečistot pod nehtem, zkrácení a následné zapilování a vyleštění nehtů, odstranění odumřelé vrstvy kůže, peeling a masáž chodidel a lýtek&nbsp;</em></span></p>
                <p><span class="text-font-size"><em>Toto ošetření se především zaměřuje na úpravu nehtů, odstranění odumřelé kůže na chodidlech a masáži, která uleví od bolesti a uvolní napětí v končetinách. Peeling odstraní suchou kůži v oblasti bérce a zanechá tak vaše nohy hydratované a omlazené. Zažijete příjemnou relaxační masáž chodidel a lýtek, která trvá minimálně 10 maximálně však 20 minut. Čím pravidelněji mě navštěvujete, tím delší máte masáž. Doporučuji opakovat po čtyřech až šesti týdnech (pro 20ti minutovou masáž chodidel a lýtek)..</em></span></p>
                <p><span class="text-font-size"><em><strong>CCA 60 MINUT – 350 KČ</strong></em></span></p>
                <p><span class="text-font-size"><em><strong>LAKOVÁNÍ NEHTŮ + 50 KČ (Nelze samostatně)</strong></em></span></p>
            </div>
        </div>
        <div class="flex-container">
            <div class="flex-border-text">
            <p><span class="text-font-size">Absolutní kontraindikace – Erysipel (růže), mykózy, bradavice, akutní forma bércového vředu a tromby.</span></p>
            <p class="text-font-size">Částečné kontraindikace – křečové žíly, vysoký krevní tlak, cukrovka a obezřetná jsem i u osob, které užívají léky na ředění krve.</p>
            </div>
            <div class="flex-border-image">
                <div class="rectangle-filled">&nbsp;</div>
                <img src="/img/obr.pedi2.jpg" alt="pedikura-obrazek" width="474" height="316">
            </div>
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
        const data = await $fetch('/api/pedikura');
        this.headerParams = data.header;
      }
    }
</script>

<style scoped>
    @import '~/assets/css/article-content.css';
</style>