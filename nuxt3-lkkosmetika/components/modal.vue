<template>
    <div class="modal">
        <div class="background">
            <div class="frame">
                <slot/>
            </div>
        </div>
    </div>
</template>

<script> 
  export default {
    data() {
      return {}
    },
    methods: {
      close() {
        this.$emit('close');
      }
    },
    mounted() {}
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
  max-width: 1200px;
  max-height: 800px;
  overflow: auto;
}
.modal {
  z-index: 10;
  position: fixed;
  display: block;
  overflow: hidden;
  top:0;
  left:0;
  width: 100%;
  height: 100%;
}

.frame {
  padding: 20px;
  min-width: -webkit-fill-available
}

</style>