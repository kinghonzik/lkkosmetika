<template>
    <div class="content">
        <div class="menu">
            <nav class="navbar navbar-expand-lg navbar-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div v-if="route" class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: route.path == '/' }" href="/">Úvod</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: route.name == 'o-mne' }" href="/o-mne">O mě</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: route.name == 'kosmetika' }" href="/kosmetika">Kosmetika</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: route.name == 'pedikura' }" href="/pedikura">Pedikúra</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: route.name == 'mezoterapie' }" href="/mezoterapie">Mezoterapie</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: route.name == 'brow-lash-bar' }" href="/brow-lash-bar">Lash & brow bar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: route.name == 'depilace' }" href="/depilace">Depilace</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: route.name == 'kontakt' }" href="/kontakt">Kontakt</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: route.name.startsWith('eshop') }" href="/eshop/products">Eshop</a>
                    </li>
                </ul>
            </div>
            </nav>
        </div>
    </div>
</template>

<script>
    export default defineComponent({
      data() {
        return {
          route: null,
        }
      },
      methods: {
      },
      async mounted() {
        this.route = useRoute();
      }
    })
</script>

<style scoped>
    /*@import 'bootstrap/dist/css/bootstrap.min.css';*/

    .content {
        box-shadow: 0 0 12px rgb(0 0 0 / 9%);
        background-color: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .menu {
        max-width: 1200px;
        margin: auto;
    }

    #navigation ul.menu li.active a {
        color: #bdb76b;
        border-bottom: 2px solid #bdb76b;
    }

    .nav-item  {
        /*padding: 15px 15px 0 10px;*/
        padding: 16px 2px 0 2px;
    }

    .navbar .active,
    .nav-item a:hover {
        color: #bdb76b;
        border-bottom: 2px solid #bdb76b;
    }

    .nav-item a {
        font-size: 15px;
        text-decoration: none !important;
        text-transform: uppercase !important;
        display: inline-block !important;
        font-weight: 700;
        letter-spacing: 2px;
        line-height: 1.45em;
        height: 40px;
    }

    @media only screen and (max-width: 991px) {
        .nav-item {
            text-align: center;
        }

        .navbar-toggler {
            margin: auto;
        }
    }

</style>