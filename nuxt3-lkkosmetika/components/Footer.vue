<template>
    <div class="footer">
        <div class="soc_icons_box">
            <ul class="soc_icons">
                <li><a href="https://www.facebook.com/Kosmetika-Pardubice-111444871170600" class="fab fa-facebook" target="_blank" rel="dofollow"></a></li>
                <li><a href="https://www.instagram.com/kosmetika.pardubice/" class="fab fa-instagram" target="_blank" rel="dofollow"></a></li>
            </ul>
        </div>
        <div class="footer-flex-container">
            <div class="footer-flex-item">
                <div class="title">Lucie Kováříková</div>
                <div class="desc">
                <div>Česká Bělá 122, PSČ 582 61 </div>
                </div>
                <div class="desc">
                    <div>info@lkkosmetika.cz</div>
                    <div>Tel: +420 731 481 191</div>
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
                    <div class="a-border"><a href="/mezoterapie" rel="dofollow">MEZOTHERAPY</a></div>
                    <div class="a-border"><a href="/brow-lash-bar" rel="dofollow">BROW &amp; LASH BAR</a></div>
                    <div class="a-border"><a href="/depilace" rel="dofollow">DEPILACE</a></div>
                </div>
            </div>
        </div>
        <div class="copyright">
            LK cosmetics  ©&nbsp;2022&nbsp;Lucie Kováříková
        </div>    
    </div>
</template>

<style scoped>

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

    padding-bottom: 4em;
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
    padding-top: 2em;
}

.footer-flex-container .desc {
    color: #777;
    text-align: center;
    padding: 0.7em;
    font-size: 14px;
    line-height: 1.6667em;
    font-family: Montserrat,sans-serif;
    -webkit-text-size-adjust: 100%;
}

.footer-flex-container .a-border a {
    text-decoration: none;
    color: #777;
    font-size: 14px;
    line-height: 1.6667em;
    font-family: Montserrat,sans-serif;
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