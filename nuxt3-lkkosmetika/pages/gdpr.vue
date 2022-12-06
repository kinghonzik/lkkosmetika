<template>
    <div>
        <div class="content" v-html="GDPR"></div>
    </div>
</template>

<script>
    export default defineComponent({
        data() {
            return {
                GDPR: '',
                conditions: '',
            }
        },
        async mounted() {
            try {
                const response = await $fetch(this.$config.bUrl + 'getDocs.php');
                const dataParsered = JSON.parse(response);
                this.conditions = dataParsered.conditions;
                this.GDPR = dataParsered.GDPR;
            } catch(exception) {
                alert('Došlo k nějaké chybě při načítání dat.');
                throw exception;
            }
        }
    })
</script>

<style scoped>
.content {
    padding: 30px 10px 10px 10px;
}
</style>