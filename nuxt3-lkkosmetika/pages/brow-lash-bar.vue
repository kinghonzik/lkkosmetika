<template>
    <div>
        <html-header :params="headerParams"></html-header>
        <div class="content-header">
        <h1 class="title">BROW &amp; LASH BAR</h1>
        <div class="desc">
            <p><span><strong>Jedná se o precizní úpravu obočí pomocí architektury obočí – vyměřování nitěmi a speciálním pravítkem, ale vždy se vychází z&nbsp;Vašeho přirozeného tvaru. Okolní chloupky se doladí depilací voskem.&nbsp;Obočí tvoří rám obličeje, vyjadřuje emoce, a je tak důležitou linií v obličeji.</strong></span></p>
            <p><span><strong>Ráda s&nbsp;Vámi najdu Váš ideální tvar. Henna na kůži vydrží 7 – 10 dní, tam kde kůži chrání chloupky o něco déle. Na chloupcích vydrží 3-4 týdny. Tvar obočí, díky depilaci, vydrží přibližně 3 týdny. 2.-3.týden si klientka pouze jemně dokresluje obočí například tužkou, ale stále máte s&nbsp;Vaším obočím méně práce, než před aplikací a 4.týden už opět příjdete na úpravu. Pro dámy, které z&nbsp;různých důvodů odmítají tetování obočí je úprava bio Hennou ideální řešení.</strong></span></p>
            <p><span><strong>Lash lifting boom je metoda krásné úpravy přirozených řas, která není tak tvrdá jako úmělé řasy. Přitom oči nádherně prokouknou a tak perfektně doplní úpravu obočí. 24 hod po aplikaci se nedoporučuje použít řasenku, aby sérum na řasách působilo co nejdéle. Když pak řasy nalíčíte, věřím, že Vás pohled ohromí.</strong></span></p>
        </div>
        </div>
        <div class="flex-container">
            <div class="flex-border-image">
            <div class="rectangle-bordered">&nbsp;</div>
            <img src="/img/bio-Henna2.jpg" alt="Biohena - architektura obočí" width="474" height="316"></div>
            <div class="flex-border-text">
                <h3>BARVENÍ OBOČÍ BIO HENNOU</h3>
                <p><span class="text-font-size">a precizní úprava pomocí architektury obočí</span></p>
                <p><span class="text-font-size"><em><strong>CCA 90 MINUT – <span style="text-decoration: line-through; text-decoration-color: red; text-decoration-thickness: 2px;">750 KČ</span>&nbsp;&nbsp;<span style="font-size: 12pt;">550 KČ</span></strong></em></span></p>
            </div>
        </div>
        <div class="flex-container">
            <div class="flex-border-text">
            <h3>LASH LIFTING BOMB</h3>
            <p><span class="text-font-size">Vaše řasy budou krásně přirozeně natočené, silnější, hustší a opticky prodloužené / načerněné. Efekt vydrží 6-8 týdnů.&nbsp;</span></p>
            <p><span class="text-font-size">Kontraindikace: folikulitida, oční infekce,&nbsp; ekzém v blízkosti očí, suché nebo vodnaté oči, zánět, poranění v oblasti aplikace, otok, nedávná aplikace, senná rýma. Po laserové operaci počkat 6 týdnů po 2. zákroku. A po eye liftingu počkat 4-6 měsíců</span>.</p>
            <p><span class="text-font-size"><em><strong>CCA 90 MINUT – <span style="text-decoration: line-through; text-decoration-color: red; text-decoration-thickness: 2px;">750 KČ</span>&nbsp;&nbsp;<span style="font-size: 12pt;">550 KČ</span></strong></em></span></p>
            </div>
            <div class="flex-border-image">
                <div class="rectangle-filled">&nbsp;</div>
                <img src="/img/lash-lifting.jpg" alt="Lash lifting bomb - řasy natočené, silnější, prodloužené." width="474" height="316">
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
        this.headerParams = {
            title: 'BROW & LASH BAR - Barvení obočí biohennou a lash lifting bomb',
            meta: [
                {
                  hid: `description`, /* HID je pro override kdyz mam dynamicky generovany starnky */
                  name: 'description',
                  content: 'Správná úprava obočí se stala velice významným trendem, přijďte do mého salónu vybrat správný tvar pomocí architektury obočí. Lash lifting je speciální úprava přirozených řas, které se natočí, opticky prodlouží, obarví a vyživí.'
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  content: 'KBio hena, Lash lifting bomb, Rasy, Uprava, Oboci'
                }
            ],
        }
      }
    }
</script>

<style scoped>
    @import '~/assets/css/article-content.css';
</style>