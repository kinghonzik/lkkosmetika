<template>
    <div class="page-content">
        <eshop-info-msg :config="config"></eshop-info-msg>
        <cart></cart>
        <div v-if="modalFailMsg" class="modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: #dc3545;color: white;">
                        <h5 class="modal-title" style="margin: auto"><span style="font-size: 150%; font-weight: 1000;"> ⚠ </span> Něco se pokazilo! </h5>
                    </div>
                    <div class="modal-body">
                        <div>{{modalFailMsg}}</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" @click="modalFailMsg = ''">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="successSentMsg" tabindex="-1">
            <div class="success-sent-message"> {{successSentMsg}}</div>
        </div>
        <div v-else style="text-align: center;">
            <div v-if="!documentReady" class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div v-else style="text-align: left;">
                <div v-if="!matchedProducts?.length">
                    <div class="section-title">V košíku nemáte zatím žádný produkt.</div>
                </div>
                <div v-else>
                    <div class="section">
                        <div class="section-title">Produkty v košíku</div>
                        <div class="products-table-frame">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 1%">Obrázek</th>
                                        <th>Produkt</th>
                                        <th style="width: 1%">Množství</th>
                                        <th style="width: 1%">Cena</th>
                                        <th style="width: 1%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in matchedProducts">
                                        <template v-if="item.stockStatus == 'skladem'">
                                            <td>
                                                <img class="product-image" :src="'/img/products/' + item.imageSrc"/>
                                            </td>
                                            <td>
                                                <div class="product-title">{{item.title}}</div>
                                                <div v-if="item.variant" style="font-size: 80%; font-style: italic; font-weight: 500; padding: 10px 0 0 0"> {{item.variant}} </div>
                                            </td>
                                            <td>
                                                <div class="product-quantity">
                                                    <input :class="['form-control', {'is-invalid': item.count == 0}]" 
                                                        style="min-width: 30px;"
                                                        type="number" v-model="item.count" min="1" pattern="[0-9]+" @change="quantityUpdated(item)" />
                                                </div>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <div class="product-price">{{(+item.price) * (+item.count)}} {{config.priceUnit}}</div>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-danger" style="border: 0" title="Odebrat z kosiku" @click="btClickRemoveFromChart(item)">
                                                    ✘
                                                </button>
                                            </td>
                                        </template>
                                        <template v-else>
                                            <td>
                                                <img class="product-image" :src="'/img/products/' + item.imageSrc"/>
                                            </td>
                                            <td>
                                                <div class="product-title">{{item.title}}</div>
                                                <div v-if="item.variant" style="font-size: 80%; font-style: italic; font-weight: 500; padding: 10px 0 0 0"> {{item.variant}} </div>
                                            </td>
                                            <td colspan="2">
                                                <div style="font-weight: 500; color: indianred"> Produkt nemáme skladem</div>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-danger" style="border: 0" title="Odebrat z kosiku" @click="btClickRemoveFromChart(item)">
                                                    ✘
                                                </button>
                                            </td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="products-summary">
                            <div class="summarisation">Celkem: <span style="padding: 0 8px;">{{productsPrice}}</span> {{config.priceUnit}}</div>
                        </div>
                    </div>
                    <div class="section">
                        <div v-if="bllingAddressSame" class="section-title">Doručovací a fakturační údaje</div>
                        <div v-else class="section-title">Doručovací údaje</div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3" title="Povinné pole">
                                <label>Jméno *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(contact.firstname)}]" 
                                    v-model="contact.firstname" aria-describedby="firstnameFeedback" maxlength="128" required autocomplete="given-name">
                                <div id="firstnameFeedback" class="invalid-feedback">
                                    {{inputTextError(contact.firstname)}}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3" title="Povinné pole">
                                <label>Přijmení *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(contact.lastname)}]" 
                                    v-model="contact.lastname" aria-describedby="lastnameFeedback" maxlength="128" required autocomplete="family-name">
                                <div id="lastnameFeedback" class="invalid-feedback">
                                    {{inputTextError(contact.lastname)}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-5 mb-3" title="Povinné pole">
                                <label>Email *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputEmailError(contact.email)}]" 
                                    v-model="contact.email" aria-describedby="emailFeedback" maxlength="128" required autocomplete="email">
                                <div id="emailFeedback" class="invalid-feedback">
                                    {{inputEmailError(contact.email)}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3" title="Povinné pole">
                                <label>Telefon *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputPhoneError(contact.phone)}]" 
                                    v-model="contact.phone" aria-describedby="phoneFeedback" maxlength="32" required autocomplete="phone">
                                <div id="phoneFeedback" class="invalid-feedback">
                                    {{inputPhoneError(contact.phone)}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Firma</label>
                                <input type="text" :class="['form-control']" 
                                    v-model="contact.company" aria-describedby="companyFeedback" maxlength="128" autocomplete="organization">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3" title="Povinné pole">
                                <label>Město *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(contact.city)}]" 
                                    v-model="contact.city" aria-describedby="cityFeedback" maxlength="64" required autocomplete="address-level2">
                                <div id="cityFeedback" class="invalid-feedback">
                                    {{inputTextError(contact.city)}}
                                </div>
                            </div>
                            <div class="col-md-2 mb-3" title="Povinné pole">
                                <label>PSČ *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputZipError(contact.zip)}]" 
                                    v-model="contact.zip" aria-describedby="zipFeedback" maxlength="16" required autocomplete="postal-code">
                                <div id="zipFeedback" class="invalid-feedback">
                                    {{inputZipError(contact.zip)}}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3" title="Povinné pole">
                                <label>Adresa *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(contact.address)}]" 
                                    v-model="contact.address" aria-describedby="addressFeedback" maxlength="128" required autocomplete="address-line1">
                                <div id="addressFeedback" class="invalid-feedback">
                                    {{inputTextError(contact.address)}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input :class="['form-check-input']" type="checkbox" value="" id="billingAdressSame" v-model="bllingAddressSame">
                                <label class="form-check-label" for="billingAdressSame">Fakturační adresa je stejná</label>
                            </div>
                        </div>
                    </div>
                    <div class="section" v-if="!bllingAddressSame">
                        <div class="section-title">Fakturační údaje</div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3" title="Povinné pole">
                                <label>Jméno *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(billingContact.firstname)}]" 
                                    v-model="billingContact.firstname" aria-describedby="firstnameBillingFeedback" maxlength="128" required autocomplete="given-name-2">
                                <div id="firstnameBillingFeedback" class="invalid-feedback">
                                    {{inputTextError(billingContact.firstname)}}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3" title="Povinné pole">
                                <label>Přijmení *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(billingContact.lastname)}]" 
                                    v-model="billingContact.lastname" aria-describedby="lastnameBillingFeedback" maxlength="128" required autocomplete="family-name-2">
                                <div id="lastnameBillingFeedback" class="invalid-feedback">
                                    {{inputTextError(billingContact.lastname)}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label>Firma</label>
                                <input type="text" :class="['form-control']" 
                                    v-model="billingContact.company" aria-describedby="companyBillingFeedback" maxlength="128" autocomplete="company-2">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3" title="Povinné pole">
                                <label>Město *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(billingContact.city)}]" 
                                    v-model="billingContact.city" aria-describedby="cityBillingFeedback" maxlength="64" required autocomplete="city-2">
                                <div id="cityBillingFeedback" class="invalid-feedback">
                                    {{inputTextError(billingContact.city)}}
                                </div>
                            </div>
                            <div class="col-md-2 mb-3" title="Povinné pole">
                                <label>PSČ *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputZipError(billingContact.zip)}]" 
                                    v-model="billingContact.zip" aria-describedby="zipBillingFeedback" maxlength="16" required autocomplete="zip-2">
                                <div id="zipBillingFeedback" class="invalid-feedback">
                                    {{inputZipError(billingContact.zip)}}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3" title="Povinné pole">
                                <label>Adresa *</label>
                                <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(billingContact.address)}]" 
                                    v-model="billingContact.address" aria-describedby="addressBillingFeedback" maxlength="128" required autocomplete="address-2">
                                <div id="addressBillingFeedback" class="invalid-feedback">
                                    {{inputTextError(billingContact.address)}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section section-shipping">
                        <div class="section-title">Doprava</div>
                        <table>
                            <template v-for="(item, index) in shippingOptions">
                                <tr v-if="item.allowed" style="margin-bottom: 15px">
                                    <td>
                                        <div :class="['custom-control', 'custom-radio', {'is-invalid': showErrors && shipping == ''}]">
                                            <input type="radio" :id="'shipping_' + index" name="shipping-radio" class="custom-control-input" :value="item.id" v-model="shipping" aria-describedby="shippingFeedback">
                                            <label class="custom-control-label" :for="'shipping_' + index">{{item.title}}</label>
                                        </div>
                                    </td>
                                    <td class="price">
                                        - {{item.price}} {{config.priceUnit}}
                                    </td>
                                </tr>
                            </template>
                        </table>
                        <div id="shippingFeedback" :style="{'display': showErrors && shipping == '' ? 'block' : 'none' }" class="invalid-feedback">Je nutné vybrat způsob dopravy</div>
                    </div>
                    <div class="section section-payment">
                        <div class="section-title">Platba</div>
                        <table>
                            <template v-for="(item, index) in paymentOptions">
                                <tr style="margin-bottom: 15px" v-if="item.allowed">
                                    <td>
                                        <div :class="['custom-control', 'custom-radio', {'is-invalid': showErrors && payment == ''}]">
                                            <input type="radio" :id="'payment_' + index" name="payment-radio" class="custom-control-input" :value="item.id" v-model="payment" aria-describedby="paymentFeedback">
                                            <label class="custom-control-label" :for="'payment_' + index">
                                                {{item.title}}
                                            </label>
                                        </div>
                                        <small v-if="(item.hint && item.id == payment)" style="padding-left: 25px;">{{item.hint}}</small>
                                    </td>
                                    <td class="price">
                                        - {{item.price}} {{config.priceUnit}}
                                    </td>
                                </tr>
                            </template>
                        </table>
                        <div id="paymentFeedback" :style="{'display': showErrors && payment == '' ? 'block' : 'none' }"  class="invalid-feedback">Je nutné vybrat způsob platby</div>
                    </div>
                    <div class="section">
                        <div class="section-title">Odsouhlasení podmínek</div>
                        <div class="form-group">
                            <div class="form-check">
                            <input :class="['form-check-input', {'is-invalid': showErrors && !consentBusinessConditions}]" 
                                type="checkbox" value="" id="businessCond" aria-describedby="businessCondFeedback" 
                                v-model="consentBusinessConditions" required>
                            <label class="form-check-label" for="businessCond">
                                
                                <a href="/obchodni-podminky" target="_blank" alt="obdhodní podmínky" rel="nofollow" style="color: black; padding-left: 5px;" title="Zobrazit obchodní podmínky">
                                    Souhlasím s obchodními podmínkami
                                </a>
                            </label>
                            <div id="businessCondFeedback" class="invalid-feedback">
                                Bez souhlasu nelze zboží objednat
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                            <input :class="['form-check-input', {'is-invalid': showErrors && !consentGDPR}]" 
                                    type="checkbox" value="" id="gdpr" aria-describedby="gdprFeedback" 
                                v-model="consentGDPR" required>
                            <label class="form-check-label" for="gdpr">
                                <a href="/gdpr" target="_blank" alt="GDPR" rel="nofollow" style="color: black; padding-left: 5px;" title="Zobrazit GDPR" >Souhlasím se zpracováním osobních údajů (GDPR)</a>
                            </label>
                            <div  id="gdpr3Feedback" class="invalid-feedback">
                                Bez souhlasu nelze zboží objednat
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="section">
                        <div class="form-group">
                            <label style="font-size: 95%;">Poznámka k objednávce <small>(max 1000 znaků)</small></label>
                            <textarea class="form-control" rows="5" maxlength='1000' v-model="customerDescription"></textarea>
                        </div>
                        <div style="font-weight: 500; font-size: 130%; padding: 5px 0 20px 0;"> Celkem zaplatíte: {{totalPrice}} {{config.priceUnit}}</div>
                        <button :disabled="!config.newOrdersAllowed" :title="config.newOrdersAllowed ? '' : 'Objednávání není zatím povoleno.'" class="btn btn-success" @click="btClickTrySend()" type="submit">Odeslat objednávku</button>
                    </div>
                </div>
            </div>
        </div>
        <mail-template 
            v-show="false" 
            :order="order" 
            :config="config" 
            :mailToCustomerDesc="null"
            :type="'new_order'"
            id="mailContent">
        </mail-template>
</div>
</template>

<script>

export default {
    data() {
        return {
            documentReady: false,
            showErrors: false,
            cookie: null,
            token: '',
            contact: {
                firstname: '',
                lastname: '',
                city: '',
                zip: '',
                address: '',
                email: '',
                phone: '',
                company: '',
            },
            billingContact: {
                firstname: '',
                lastname: '',
                city: '',
                zip: '',
                address: '',
                company: '',
            },
            bllingAddressSame: true,
            consentGDPR: false,
            consentBusinessConditions: false,
            customerDescription: '',
            productsList: [],
            matchedProducts: [],
            config: null,
            shipping: '',
            payment: '',
            successSentMsg: '',
            modalFailMsg: '',
        }
    },
    computed: {
        shippingOptions() { return this.config?.shippingOptions},
        paymentOptions() { return this.config?.paymentOptions},
        productsPrice() {
            var price = 0;
            for (const item of this.matchedProducts) {
                if (item.stockStatus != 'skladem')
                    continue;
                price += (+item.count) * (+item.price); 
            }
            return price;
        },
        totalPrice() {
            var price = this.productsPrice;

            if (this.shipping)
                price += this.shippingOptions?.find(itm => itm.id == this.shipping)?.price;

            if (this.payment)
                price += this.paymentOptions?.find(itm => itm.id == this.payment)?.price;

            return price;
        },
        order() {
            return {
                data: {
                    products: this.matchedProducts.filter(itm => itm.stockStatus == 'skladem'),
                    contact: this.contact,
                    billingContact: this.billingContact,
                    bllingAddressSame: this.bllingAddressSame,
                    customerDescription: this.customerDescription,
                    consentGDPR: this.consentGDPR,
                    consentBusinessConditions: this.consentBusinessConditions
                },
                shipping: this.shipping,
                shippingPrice: this.shippingOptions?.find(itm => itm.id == this.shipping)?.price,
                payment: this.payment,
                paymentPrice: this.paymentOptions?.find(itm => itm.id == this.payment)?.price,
                productsPrice: this.productsPrice,
                totalPrice: this.totalPrice,
            }
        }
    },
    methods: {
        btClickRemoveFromChart(item) {
            this.matchedProducts.splice(this.matchedProducts.indexOf(item), 1);
            this.cookie.removeProduct(item.id, item.variant);
        },
        quantityUpdated(item) {
            this.cookie.updateProduct(item.id, item.count, item.variant);
        },
        async btClickTrySend() {
            this.showErrors = true;
            const invalidElements = await document.getElementsByClassName('is-invalid');

            if (invalidElements?.length) {
                this.modalFailMsg = 'Zkontrolujte prosím formulář, něco je špatně vyplněné.';
                return;
            }

            const obj2send = JSON.parse(JSON.stringify(this.order));
            obj2send.mailHtml = document.getElementById('mailContent').innerHTML;

            try {
                const response = await fetch(this.$config.bUrl + 'postOrder.php', { method: 'POST', body: JSON.stringify({order: obj2send, token: this.token})});
                if (response.status == 200) {
                    this.cookie.clear();
                    this.successSentMsg = 'Objednávka přijata, rekapitulaci objednávky dostanene na zadanou emailovou adresu.';
                } else {
                    if (response.status == 400) {
                        this.modalFailMsg = 'Platnost formuláře vypršela. Obnovte stránku (zmáčknutím F5) a odešlete prosím formulář znovu.';
                    } 
                    else {
                        this.modalFailMsg = 'Došlo k nějaké chybě, zkuste to prosím poději nebo nám prosím napište email. Děkujeme.';
                    }
                }
            } catch(exception) {
                this.modalFailMsg = 'Došlo k nějaké chybě';         
                console.error(exception);
            }
        },
        btClickRedirect() {
            window.location('/eshop');
        },
        inputTextError(text) {
            if (!text)
                return 'Vyplňte prosím pole.'
            if (text.length < 2)
                return 'Nějak málo znaků, minimálně 2.'
            const regex = /^[a-zA-ZÀ-ÖÙ-öù-ÿĀ-žḀ-ỿ0-9 ]+$/;
            if(!regex.test(text) )
                return 'Obsahuje nevalidní znaky!';
            return null;
        },
        inputZipError(text) {
            if (!text)
                return 'Vyplňte prosím pole.'
            if (text.length < 2)
                return 'Nějak málo znaků. PSČ má obvykle 5.'
            const regex = /^[0-9 ]+$/;
            if(!regex.test(text))
                return 'Obsahuje nevalidní znaky!';
            return null;
        },
        inputPhoneError(text) {
            if (!text)
                return 'Vyplňte prosím pole.'
            if (text.length < 5)
                return 'Nějak málo znaků.'
            const regex = /^[0-9 +]+$/;
            if(!regex.test(text))
                return 'Obsahuje nevalidní znaky!';
            return null;
        },
        inputEmailError(text) {
            if (!text)
                return 'Vyplňte prosím pole.'
            if (text.length < 2)
                return 'Nějak málo znaků, minimálně 2.'
            const regex = /^[a-zA-ZÀ-ÖÙ-öù-ÿĀ-žḀ-ỿ0-9 @.]+$/;
            if(!regex.test(text) )
                return 'Obsahuje nevalidní znaky!';
            return null;
        }
    }, 
    async mounted() {
        try {
            this.token = await $fetch(this.$config.bUrl + 'getCRSF.php');
            this.config = JSON.parse(await $fetch(this.$config.bUrl + 'getConfig.php'));
            const data = await $fetch(this.$config.bUrl + 'getProducts.php');
            const dataParsered = JSON.parse(data);
            this.productsList = dataParsered;
        } catch(exception) {
            alert('Došlo k nějaké chybě');
            throw exception;
        }

        this.cookie = await useCookie();
        const cookieProducts = this.cookie.getProducts();

        // vyselelektovani validnich produktu
        const invalidProducts = [];
        for (const cItem of cookieProducts) {
            const product = this.productsList.find(itm => itm.id == cItem.id);
            if (!product) {
                invalidProducts.push(cItem);
                continue;
            }
            if (cItem.variant) {
                if (!product.data.variants?.find(itm => itm.title == cItem.variant)) {
                    invalidProducts.push(cItem);
                    continue;
                }
            }

            // product deepCopy
            const mProduct = JSON.parse(JSON.stringify(cItem));
            mProduct.imageSrc = product.data.image?.src;
            mProduct.title = product.data.title;
            if (mProduct.variant) {
                // nastaveni dostupnosti a ceny dle varianty
                const variant = product.data.variants.find(itm => itm.title == mProduct.variant);
                mProduct.stockStatus = variant.stockStatus;
                mProduct.price = +variant.price;
            } else {
                mProduct.stockStatus = product.data.stockStatus;
                mProduct.price = +product.data.price;
            }
            this.matchedProducts.push(mProduct);
        }

        for (const iProduct of invalidProducts) {
            this.cookie.removeProduct(iProduct.id, iProduct.variant);
        }
        
        this.documentReady = true;
    }
}
</script>

<style scoped>
    .success-sent-message {
        text-align: center;
        font-size: 130%;
        font-weight: 500;
        padding: 10em 0.5em;
        color: MediumSeaGreen;
    }
    .display-block {
        display: block;
    }

    .page-content {
        padding: 20px;
    }
    .section {
        padding-top: 10px;
    }
    .section-title {
        font-size: 140%;
        padding: 15px 0;
        font-weight: 500;
    }

    .section-shipping td,
    .section-payment td {
        padding-bottom: 15px;
    }

    .section-shipping td.price,
    .section-payment td.price
    {
        padding-left: 30px;
        font-weight: 500;
        font-style: italic;
    }

    .product-image {
        max-width: 120px;
        max-height: 120px;
    }

    .products-summary {
        display: block;
        overflow: hidden;
    }

    .products-summary .summarisation {
        float: right;
        font-weight: 500;
        font-size: 120%;
        padding-right: 45px;
    }

    .products-table-frame td {
        vertical-align: middle;
    }

</style>