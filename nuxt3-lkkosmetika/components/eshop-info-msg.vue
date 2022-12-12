<template>
    <section>
        <div v-if="config?.infoMsg" class="info-msg">
            {{config.infoMsg}}
        </div>
    </section>
</template>

<script>
    
    export default {
        props: ["config"],
        data() {
            return {
            }
        }
    }
  </script>

<style scoped>
    .info-msg {
        padding: 40px 10px 35px 10px;
        font-size: 120%;
        font-weight: 500;
        text-align: center;
        background: #bdb76b;
        color: white;
        line-height: 1.8em;
    }
</style>