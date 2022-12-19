<template>
    <div class="footer">
        <div v-if="config" class="soc_icons_box"  style="text-align: center; padding-top: 10px;">
            <div v-if="config.linkFacebook" class="logo-border">
                <a :href="config.linkFacebook" target="_blank" rel="dofollow" title="Facebook profil">
                    <img class="logo" src="~/assets/img/facebook.png" alt="Facebook logo" width="30" height="30"/>
                </a>
            </div>
            <div v-if="config.linkInstagram" class="logo-border">
                <a :href="config.linkInstagram" target="_blank" rel="dofollow" title="Instagram profil">
                    <img class="logo" src="~/assets/img/instagram2.png" alt="Instagram logo" width="30" height="30"/>
                </a>
            </div>
        </div>
        <div v-else style="text-align: center; padding-top: 40px;">
        </div>
        <div class="footer-flex-container">
            <div class="footer-flex-item">
                <div class="title">Lucie Kováříková</div>
                <div class="desc">
                <div>Česká Bělá 122, PSČ 582 61 </div>
                </div>
                <div class="desc">
                    <div>info@lkkosmetika.cz</div>
                </div>
                <div class="desc">
                    <div>Tel: +420 731 481 191</div>
                </div>
                <div style="color: white; text-align: center;" >
                    <div class="col-sm-12" style="display: inline-block; padding-top: 10px;">
                        <a href="/gdpr" target="_blank" alt="GDPR" rel="nofollow" style="color: white">Zpracování osobních údajů</a>
                    </div>
                    <div class="col-sm-12" style="display: inline-block; padding-top: 10px">
                        <a href="/obchodni-podminky" target="_blank" alt="obchodní podmínky" rel="nofollow" style="color: white">Obchodní podmínky</a>
                    </div>
                </div>
            </div>
            <div style="text-align: center" class="footer-flex-item">
                <a href="/">
                    <img class="logo" src="~/assets/img/Logo-bile.png" alt="kosmetika logo" width="auto" height="200px">
                </a>
            </div>
            <div class="footer-flex-item">
                <div class="title">Služby</div>
                <div class="desc">
                    <div class="a-border"><a href="/kosmetika" rel="dofollow">KOSMETICKÉ SLUŽBY</a></div>
                    <div class="a-border"><a href="/pedikura" rel="dofollow">PEDIKÚRA</a></div>
                    <div class="a-border"><a href="/mezoterapie" rel="dofollow">MEZOTHERAPIE</a></div>
                    <div class="a-border"><a href="/brow-lash-bar" rel="dofollow">BROW &amp; LASH BAR</a></div>
                    <div class="a-border"><a href="/depilace" rel="dofollow">DEPILACE</a></div>
                </div>
            </div>
        </div>
        <div class="copyright">
            LK cosmetics  ©&nbsp;{{currentYear}}&nbsp;Lucie Kováříková
        </div>    
    </div>
</template>

<script>
    export default defineComponent({
      data() {
        return {
          config: null,
          currentYear: new Date().getFullYear(),
        }
      },
      methods: {
      },
      async mounted() {
        try {
            this.config = JSON.parse(await $fetch(this.$config.bUrl + 'getConfig.php'));
        } catch(exception) {
            // potrebujeme aby web nerusene bezel i pokud se nepripojime k DB
            this.config = null;
            //alert('Došlo k nějaké chybě při načítání dat.');
            throw exception;
        }
      }

    })
</script>

<style scoped>

.title {
    padding-top: 0;
}
.logo-border {
    width: fit-content; 
    display: inline-block; 
    padding: 5px
}

.logo-border:hover {
    zoom: 110%
}

.footer {
    background: #151515;
}
.copyright {
    color: white;
    padding: 5px;
    padding-bottom: 20px;
    text-align: center;
}
.footer-flex-container {
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-flex-direction: row;
    -ms-flex-direction: row;
    flex-direction: row;
    -webkit-flex-wrap: nowrap;
    -ms-flex-wrap: nowrap;
    flex-wrap: nowrap;
    -webkit-justify-content: flex-start;
    -ms-flex-pack: start;
    justify-content: flex-start;
    -webkit-align-content: stretch;
    -ms-flex-line-pack: stretch;
    align-content: stretch;
    -webkit-align-items: flex-start;
    -ms-flex-align: start;
    align-items: flex-start;

    padding-bottom: 1em;
    max-width: 1200px;
    margin: auto;
}

.footer-flex-item {
    -webkit-order: 0;
    -ms-flex-order: 0;
    order: 0;
    -webkit-flex: 1 1 0;
    -ms-flex: 1 1 0;
    flex: 1 1 0;
    -webkit-align-self: stretch;
    -ms-flex-item-align: stretch;
    align-self: stretch;
}

.footer-flex-container .title {
    color: white;
    font-weight: bold;
    text-align: center;
    padding: 0.7em;;
    font-size: 16px;
    padding-top: 0;
}

.footer-flex-container .desc {
    color: rgb(172, 166, 166);
    text-align: center;
    padding: 0.2em;
    font-size: 1em;
    line-height: 1.6667em;
    -webkit-text-size-adjust: 100%;
}

.footer-flex-container .a-border {
    padding-bottom: 0.5em;
}

.footer-flex-container .a-border a {
    text-decoration: none;
    color: rgb(172, 166, 166);
    font-size: 0.95em;
    line-height: 1.6667em;
    -webkit-text-size-adjust: 100%;
}

.footer-flex-container .a-border a:hover {
    text-decoration: underline;
}

.footer-flex-container .logo {
    padding: 1em;
    background: transparent;
}

@media (max-width: 684px) {
    .footer-flex-container {
        display: block;
    }
}
</style>