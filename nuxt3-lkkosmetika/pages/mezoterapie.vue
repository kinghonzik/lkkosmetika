<template>
    <div>
        <html-header :params="headerParams"></html-header>
        <div class="content-header">
            <h1 class="title">MEZOTERAPIE</h1>
            <div class="desc">
                <p><span><strong>Ke své práci používám kvalitní dermapero značky Dermica of Switzerland, které má bezkonkurenční sílu v&nbsp;motoru. 900 vpichů za sekundu pomocí 12ti jehel zapracují sérum do kůže. Vpichy vytvoří na kůži mikrotraumata, buňky se začnou obnovovat, aby zahojily poraněnou tkáň a nastává tvorba vlastního kolagenu. Procedura reguluje nadměrný pigment, zlepšuje stav akné, zmírňuje a vyhlazuje jemné vrásky, zlepšuje elasticitu a sílu kůže, zlepšuje stav jizev - hlavně po akné/neštovicích ale i strie a celkově omlazuje pleť, krk a dekolt.</strong></span></p>
                <p><span><strong>Po této proceduře se nedoporučuje 24 hod aplikovat make-up a také se na 2-3 dny vyhnout slunění včetně solárií.</strong></span></p>
            </div>
        </div>
        <div class="flex-container">
            <div class="flex-border-image">
                <div class="rectangle-bordered">&nbsp;</div>
                <img style="box-shadow: 0 0 2px rgb(0 0 0 / 9%);" src="/img/goldpen.jpg" alt="Goldpen - dermapero" width="474" height="316">
            </div>
            <div class="flex-border-text">
                <h3>GOLDPEN</h3>
                <p><span class="text-font-size">Mezoterapie kvalitním švýcarským dermaperem – goldpen</span></p>
                <p><span class="text-font-size"><em>Ošetření zahrnuje: čištění a tonizace pleti,&nbsp; první zapracování kys.hyaluronové dermaperem, druhé a třetí zapracování vhodného séra MD:ceutikals, závěrečná hojivá maska a pozákrokový krém SPF 50 MD:ceutikals.&nbsp;</em></span></p>
                <p><span class="text-font-size"><em>Je vyžadováno opakování po 14ti dnech ve třech až čtyřech cyklech pro ideální výsledky. U strií osm až deset opakování.</em></span></p>
                <p><span class="text-font-size"><em><strong>CCA 60 MINUT 1 ošetření – 2500 KČ</strong></em></span></p>
                <p><span class="text-font-size"><em><strong>Kúra 3 ošetření – 6500 KČ</strong></em></span></p>
            </div>
        </div>
        <div class="flex-container">
            <div class="flex-border-text">
                <p><span class="text-font-size">Pro ošetření dermaperem používám britskou značku MD ceutikals,&nbsp; která byla vyvinuta farmakology, dermatology a lékaři estetické a anti-agingové medicíny vědeckého oddělení společnosti Scientist Skinlab. Vlastní výrobní linky a výzkumná laboratoř společnosti se nacházejí ve městě Stockport, hrabství Cheshire ve Velké Británii, při výrobě se přísně dodržují světoznámé standardy britské kvality. Řada MD Complex™ se skládá z produktů pro péči o starší kůži obličeje a těla obsahujících ty nejaktivnější a nejnezbytnější látky, které mají antioxidační, ochranné, hydratační, obnovující, posílující a anti-agingové účinky.</span></p>
                <p><span class="text-font-size">Přípravky pro korekci hyperpigmentace, proti padání vlasů a lokálním tukovým nahromaděním obsahují vysoké koncentrace aktivních složek, jejichž kombinace byly vyvinuty speciálně pro tuto značku.</span></p>
                <p class="text-font-size">Kontraindikace k ošetření jsou jakákoliv infekce v obličeji, otevřené a nezhojené rány v obličeji, chirurgický nebo dermatologický zákrok v obličeji (v krátkém období), při špatné srážlivosti krve, těhotenství, autoimunitní onemocnění, cukrovka, akné v aktivní fázi, herpes simplex a herpes zoster a při užívání retinolu (72h před procedurou), opar, nachlazení a kašel.</p>
            </div>
            <div class="flex-border-image">
            <div class="rectangle-filled">&nbsp;</div>
            <img src="/img/mezoterapie2.jpg" alt="Ošetření dermaperem - MD ceutikals" width="474" height="316"></div>
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
        this.headerParams = {
            title: 'Mezoterapie - dermapero',
            meta: [
                {
                  hid: `description`, /* HID je pro override kdyz mam dynamicky generovany starnky */
                  name: 'description',
                  content: 'Jedná se o nejvyhledávanější neinvazivní metodu, pro dosažení mladšího vzhledu. Metoda účinně stimuluje tvorbu vlastního kolagenu a elastinu.'
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  content: 'Goldpen, Mezoterapie, Cisteni pleti, Tonizace pleti, Dermapero, Hyperpigmentace, Padani vlasu, anti age'
                }
            ],
        };
      }
    }
</script>

<style scoped>
    @import '~/assets/css/article-content.css';
</style>