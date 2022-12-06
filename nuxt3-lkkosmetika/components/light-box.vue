<template>
  <div class="lightbox">
    <div class="background" @click.self="btClickClose()">
      <div class="frame">
        <div class="lightbox-img-frame ">
          <img :src="imagesList[imgIndex].src" />
        </div>
      </div>
    </div>
    <div class="controls-top">
      <button 
        style="float: right" 
        class="icon"
        @click="btClickClose()"
        title="zavřít"
      > ✘ </button>
      <span style="float: left; color: white; font-size: 1.5em; padding-top: 15px"> {{(imgIndex + 1) + ' / ' + imagesList.length}}</span>
    </div>
    <div class="controls-middle">
      <button 
        style="float: right"
        class="icon" 
        v-show="imgIndex < imagesList.length - 1" 
        @click="btClickNext()"
        title="další obrázek"
      > ⇾ </button>
      <button 
        style="float: left" 
        v-show="imgIndex > 0" 
        @click="btClickPrev()"
        class="icon"
        title="předchozí obrázek"
      > ⇽ </button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["imagesList", "startIndex"],
  data() {
    return { 
      imgIndex: 0,
    }
  },
  methods: {
    btClickClose() {
      this.$emit('close') // samotnej close se pak provede na parentovi
    },
    btClickNext() {
      this.imgIndex++;
    },
    btClickPrev() {
      this.imgIndex--;
    }
  },
  mounted() {
    if (this.startIndex >= this.imagesList.length)
      this.imgIndex = 0;
    else
      this.imgIndex = this.startIndex;
  }
}
</script>

<style scoped>
.background {
  display: block;
  overflow: hidden;
  top:0;
  left:0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0, 0.5);
}
.frame {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: white;
  padding: 0;
  max-width: 100%;
  max-height: 100%;
  overflow: auto;
}
.lightbox {
  z-index: 10;
  position: fixed;
  display: block;
  overflow: hidden;
  top:0;
  left:0;
  width: 100%;
  height: 100%;
}
.lightbox-img-frame {
  display: block;
  position: relative;
  padding: 0px;
}
.lightbox-img-frame img {
  position: relative;
  max-width: 1400px;
  max-height: 800px;
}

.lightbox .btn-carousel {
  position: relative;
  top: 50%;
  padding: 20px;
  z-index: 11;
  float: right;
}

.controls-top {
  position: fixed;
  top: 0%;
  width: 100%;
  padding: 10px 25px;
  background: rgba(0,0,0, 0.7);
}

.controls-top .icon {
  padding: 0;
  font-size: 3em;
  background: transparent;
  color: white;
  border: 0;
}

.controls-middle {
  position: fixed;
  top: 50%;
  width: 100%;
  padding: 10px 25px;
}

.controls-middle .icon {
  padding: 0;
  font-size: 5em;
  background: transparent;
  color: white;
  border: 0;
  translate: 0 -50%;
}

.icon:hover {
  transform: scale(1.2)
}

button:focus {
  border: none;
  outline: none;
}

</style>