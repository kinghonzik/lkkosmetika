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
        const data = await $fetch('/api/data_uvod');
        this.headerParams = data.header;
        this.tiles = data.tiles;
        const gallery = await $fetch('/api/gallery');
        this.galleryImages = gallery.images;
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