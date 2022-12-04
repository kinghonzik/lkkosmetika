<template>
    <div>
        <div v-if="config">
            <div class="product-list">
                <div class="flex-container">
                    <div class="product flex-item" v-for="product in productsList" title="Kliknutím zobrazíte detail produktu." @click="btClickProduct(product)">
                        <div class="flex-container-2nd">
                            <div class="flex-item-2nd" style="flex: 1 1 auto; -ms-flex: 1 1 auto; -webkit-flex: 1 1 auto;">
                                <div class="title">{{product.data.title}}</div>
                                <div class="img-border"><img :src="product.data.image?.src ? '/img/products/' + product.data.image.src : '#'" :alt="product.data.id"/></div>
                                <div class="description-short" v-if="product.data.descriptionShort" v-html="product.data.descriptionShort"></div>
                            </div>
                            <div class="flex-item-2nd">
                                <div>
                                    <div class="price">{{product.data.price}} {{config?.priceUnit}}</div>
                                    <div v-if="product.data.variants?.length" class="stock-status">
                                        <span v-if="product.data.variants.filter(item => item.stockStatus == 'skladem')?.length == product.data.variants.length" class="green"> skladem všechny varianty </span>
                                        <span v-else-if="product.data.variants.some(item => item.stockStatus == 'skladem')" class="green"> skladem některé varianty </span>
                                        <span v-else class="orange"> {{ product.data.variants[0].stockStatus }} </span>
                                    </div>
                                    <div v-else class="stock-status">
                                        <span :class="[product.data.stockStatus == 'skladem' ? 'green' : 'orange']">{{product.data.stockStatus}}</span>
                                    </div>
                                    <div class="href-button" v-if="product.data.variants?.length"><a :id="'product-id-' + product.id" :href="'/eshop/product/' + product.data.id">Vybrat variantu</a></div>
                                    <div class="href-button" v-else><a :id="'product-id-' + product.id" :href="'/eshop/product/' + product.data.id">Přejít na detail</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default defineComponent({
        props: ['productsList', 'config'],
        data() {
            return {
                eaderParams: null,
            }
        },
        methods: {
            replace(text) {
                 return text.replace(/[<>&\n]/g, function(x) {
                    return {
                        '<': '&lt;',
                        '>': '&gt;',
                        '&': '&amp;',
                    '\n': '<br />'
                    }[x];
                });
            },
            btClickProduct(product) {
                const elm = document.getElementById('product-id-' + product.id);
                elm.click();
            }
        }
    })
</script>

<style scoped>
    .product {
        /*width: 280px;*/
        max-width: 374px;
        cursor: pointer;
        padding: 10px;
        margin:10px;
        border: light;
        box-shadow: 3px 0 20px rgb(97 40 0 / 12%);
        position: relative;
    }

    .product:hover {
        box-shadow: 3px 0 20px rgb(97 40 0 / 32%);
    }
    
    .flex-container {
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-justify-content: flex-start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        -webkit-align-content: flex-start;
        -ms-flex-line-pack: start;
        align-content: flex-start;
        -webkit-align-items: flex-start;
        -ms-flex-align: start;
        align-items: flex-start;
    }

    .flex-item {
        -webkit-order: 1;
        -ms-flex-order: 1;
        order: 1;
        -webkit-flex: 1 2 auto;
        -ms-flex: 1 2 auto;
        flex: 1 2 auto;
        -webkit-align-self: center;
        -ms-flex-item-align: center;
        align-self: flex-start;
    }

    .flex-container-2nd {
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-justify-content: flex-start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        -webkit-align-content: stretch;
        -ms-flex-line-pack: stretch;
        align-content: stretch;
        -webkit-align-items: flex-start;
        -ms-flex-align: start;
        align-items: flex-start;
        height: 100%;
        width: 100%;
    }

    .flex-item-2nd {
        -webkit-order: 0;
        -ms-flex-order: 0;
        order: 0;
        -webkit-flex: 0 1 auto;
        -ms-flex: 0 1 auto;
        flex: 0 1 auto;
        -webkit-align-self: auto;
        -ms-flex-item-align: auto;
        align-self: auto;
        width: inherit;
    }

    .product .title {
        padding: 20px;
        font-size: 120%;
        font-weight: 700;
    }

    .product .img-border {
        padding-bottom: 15px;
    }

    .product img {
        max-width: 250px;
        max-height: 250px;
        display: block;
        margin: auto;
    }

    .href-button a {
        background-color: #bdb76b;
        padding: 8px 20px;
        color: white !important;
        font-weight: bold;
        text-align: center;
        margin-top: 5px;
        border-radius: 25px;
        transition: 0.5s;
        border: 0;
        font-weight: 500;
        display: block;
        margin: 15px;
        text-decoration: none; 
        color: inherit;
    }

    .href-button a:hover {
        background-color: #151515;
    }

    .price {
        text-align: center;
        padding: 10px;
        font-weight: 600;
        font-size: 115%;
    }

    .description-short {
        padding: 10px;
    }

    .stock-status {
        font-weight: 600;
        text-align: center;
        padding: 10px;
        font-size: 100%;
    }

    .stock-status .orange {
        color: Orange;
    }
    .stock-status .green {
        color: MediumSeaGreen;
    }

</style>