<template>
    <div>
        <html-header :params="headerParams"></html-header>
        <div class="bg-image-container" style="background-image: url('/assets/img/uvod-2.png')">
        <br/>
        <br/>
        <img style="display: block; margin-left: auto; margin-right: auto;" src="/assets/img/Logo-cerne.png" width="156" height="200">
        <br/>
        <h2 style="text-align: center;">LUCIE KOVÁŘÍKOVÁ</h2>
        <h1 style="text-align: center; font-size: 20px;">KOSMETIKA·LASH&amp;BROW BAR·DEPILACE</h1>
        <br/>
        </div>
        <div class="flex-container">
            <div class="flex-item" v-for="tile in tiles">
            <div class="title">{{ tile.title }}</div>
            <div class="desc">{{ tile.description }}</div>
            <div class="button-frame"><a :href="tile.link">Více informací</a></div>
            </div>
        </div>
        
        <br/>
        <h3 style="text-align: center;">Ráda Vás přivítám ve svém salonu v České Bělé</h3>
        <br/>
        <div class="gallery">
          <div class="frame" v-for="(gImg, index) in galleryImages" :key="gImg" @mouseenter="gMouseEnter(index)" @mouseleave="gMouseLeave(index)" @click="gMouseClick(index)">
            <div class="image-bg-div" :style="{'background-image': 'url(' + gImg.src + ')'}"></div>
            <div v-if="gImg.zoomVisible" class="gallery-zoom"></div>
          </div>
        </div>
        <p style="padding-left: 30px;"><span style="font-size: 12pt;"><span style="font-size: 10pt;">Kontraindikace k ošetření jsou jakákoliv infekce v obličeji, otevřené a nezhojené rány v obličeji, chirurgický nebo dermatologický zákrok v obličeji (v krátkém období), opar, nachlazení a kašel.&nbsp;</span></span></p>
        <p style="padding-left: 30px;"><span style="font-size: 12pt;"><span style="font-size: 10pt;">Lucie Kováříková je frekventantkou kosmetického institutu z Hradce Králové, kde zakončila profesní zkouškou akreditovaný kurz kosmetička pod záštitou ministerstva školství, mládeže a tělovýchovy</span>.&nbsp;&nbsp;</span></p>
        <light-box v-if="galleryImages?.length && lightBoxVisible" 
          :imagesList="galleryImages" :startIndex="lightBoxStartIndex" 
          @close="lightBoxVisible = false">
        </light-box>
      </div>
</template>

<script>

    export default {
      data() {
        return {
          headerParams: null,
          tiles: [],
          galleryImages: [],
          lightBoxStartIndex: 0,
          lightBoxVisible: false,
        }
      },
      methods: {
        gMouseEnter(imgIndex) {
          this.galleryImages[imgIndex].zoomVisible = true;
        },
        gMouseLeave(imgIndex) {
          this.galleryImages[imgIndex].zoomVisible = false;
        },
        gMouseClick(imgIndex) {
          this.lightBoxStartIndex = imgIndex;
          this.lightBoxVisible = true;
        }
      },
      async mounted() {
        this.headerParams = {
            title: 'Kosmetika Česká Bělá  ',
            meta: [
                {
                  hid: `description`, /* HID je pro override kdyz mam dynamicky generovany starnky */
                  name: 'description',
                  content: 'KOSMETICKÉ SLUŽBY, BROW & LASH BAR, MEZOTHERAPIE, DEPILACE. Ráda Váš přivítám ve svém salonu  v České Bělé.'
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  content: 'Kosmetické služby, Kosmetika, Brow & lash bar, Mezoterapie, Depilace, Uprava oboci, Mikrojehlicky, Česká Bělá, Salon'
                }
            ],
        };
        this.tiles = [
            {
                title: 'KOSMETICKÉ SLUŽBY',
                description: 'Pracuji s italskou farmaceutickou kosmetikou PhysioNatura, která využívá pro výrobu své kosmetiky extrakty z červených hroznů odrůd Negroamaro a Primitivo a extrakty z odrůdy oliv Croatia. Ve složení používá bezpečné konzervanty a neobsahuje parabeny a silikony.',
                link: '/kosmetika',
            },
            {
                title: 'PEDIKÚRA',
                description: 'Klasická mokrá pedikúra se stala absolutní špičkou v péči o chodidla a je stále oblíbenou metodou již desítky let. Začíná koupelí nohou v teplé lázni se změkčujícími přísadami. Během této doby dojde k uvolnění nečistot z nehtových lůžek a změkčení otlaků nohou. Součástí celé procedury je i peeling a relaxační masáž chodidel a lýtek.',
                link: '/pedikura',
            },
            {
                title: 'MEZOTHERAPIE',
                description: 'Jedná se o nejvyhledávanější neinvazivní metodu, pro dosažení mladšího vzhledu. Metoda účinně stimuluje tvorbu vlastního kolagenu a elastinu. Mikrojehličky zajistí několikanásobně vyšší vstřebání aktivních látek a výraznější efekt v porovnání s aplikací látek v podobě krémů.',
                link: '/mezoterapie',
            },
            {
                title: 'BROW & LASH BAR',
                description: 'Správná úprava obočí se stala velice významným trendem, přijďte do mého salónu vybrat správný tvar pomocí architektury obočí. Lash lifting je speciální úprava přirozených řas, které se natočí, opticky prodlouží, obarví a vyživí.',
                link: '/brow-lash-bar',
            },
            {
                title: 'DEPILACE',
                description: 'Nabízím všechny druhy depilací, dámské i pánské, depiluji jak samostržnými vosky, roll on vosky, tak cukrovou pastou, která je mnohem šetrnější. Na některé partie mám oblíbený vosk, na některé cukrovou pastu, ale vždy se domluvím s klientem, kterou metodu upřednostňuje.',
                link: '/depilace',
            },
        ];
        this.galleryImages = [
            { src: '/img/gallery/bio-Henna.jpg'},
            { src: '/img/gallery/cotton.jpg'},
            { src: '/img/gallery/depilace-intim-partie2.jpg'},
            { src: '/img/gallery/depilace-neutral2.jpg'},
            { src: '/img/gallery/depilace-neutral3.jpg'},
            { src: '/img/gallery/depilace-pastou.jpg'},
            { src: '/img/gallery/depilace-pastou3.jpg'},
            { src: '/img/gallery/depilace-pastou4.jpg'},
            { src: '/img/gallery/depilace-pastou5.jpg'},
            { src: '/img/gallery/eucaliptus.jpg'},
            { src: '/img/gallery/IMG-20210616-WA0009.jpg'},
            { src: '/img/gallery/IMG-20210616-WA0010.jpg'},
            { src: '/img/gallery/lash-lifting2.jpg'},
            { src: '/img/gallery/liftingove-kosmeticke-osetreni.jpg'},
            { src: '/img/gallery/mezoterapie.jpg'},
            { src: '/img/gallery/neutral.jpg'},
            { src: '/img/gallery/neutral2.jpg'},
            { src: '/img/gallery/neutral3.jpg'},
            { src: '/img/gallery/oboci_laminace.jpg'},
            { src: '/img/gallery/panska-kosmetika.jpg'},
            { src: '/img/gallery/pozadi.jpg'},
            { src: '/img/gallery/pozadi2.jpg'},
            { src: '/img/gallery/pozadi_neutral.jpg'},
            { src: '/img/gallery/uprava-oboci_depilace-voskem.jpg'},
        ];
      }
    }
</script>

<style scoped>
    @import '~/assets/css/article-content.css';

    .frame {
      position: relative;
      width: fit-content;
      overflow: hidden;
      height: fit-content;
      display: inline-block;
      margin: 0 2px;
    }

    .image-bg-div {
      position: relative;
      top: 0;
      left: 0;
      width:360px;
      height:240px;
      z-index: 1;
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
    }
    .gallery-zoom {
      position: absolute;
      top: 0;
      left: 0;
      width:360px;
      height:240px;
      z-index: 2;
      opacity: 0.5;
      background: black;
      cursor: pointer;
      background-image: url('/assets/img/zoom-mag-icon.png');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 100px 100px;
    }

    .flex-item {
      min-width: 340px !important;
    }
    .gallery,
    .gallery-image {
      height: fit-content;
      overflow: hidden;
      width: fit-content;
      display: inline-block;
      text-align: center;
    }
    .gallery-image img {
      width: 360px;
      height: 240px;
      display: block;
      padding: 0;
      margin: 0;
      border: none;
      float: left;
      padding: 1px;
    }

</style>