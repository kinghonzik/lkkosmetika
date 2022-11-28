<template>
    <div class="page-content">
        <cart></cart>
        <div v-if="modalSuccess" class="modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: #28a745;color: white;">
                        <h5 class="modal-title" style="margin: auto"><span style="font-size: 150%; font-weight: 1000;">✓</span> Děkujeme za Váš nákup </h5>
                    </div>
                    <div class="modal-body">
                        <div>{{modalSuccess}}</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success" @click="modalSuccess = ''; btClickRedirect()">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="modalFail" class="modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: #dc3545;color: white;">
                        <h5 class="modal-title" style="margin: auto"><span style="font-size: 150%; font-weight: 1000;"> ⚠ </span> Něco se pokazilo! </h5>
                    </div>
                    <div class="modal-body">
                        <div>{{modalFail}}</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" @click="modalFail = ''">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="!documentReady">
            <div class="section-title">Stránka se ještě načítá, prosím čekejte ...</div>
        </div>
        <div v-else>
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
                                v-model="contact.firstname" aria-describedby="firstnameFeedback" maxlength="128" required >
                            <div id="firstnameFeedback" class="invalid-feedback">
                                {{inputTextError(contact.firstname)}}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" title="Povinné pole">
                            <label>Přijmení *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(contact.lastname)}]" 
                                v-model="contact.lastname" aria-describedby="lastnameFeedback" maxlength="128" required >
                            <div id="lastnameFeedback" class="invalid-feedback">
                                {{inputTextError(contact.lastname)}}
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-5 mb-3" title="Povinné pole">
                            <label>Email *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputEmailError(contact.email)}]" 
                                v-model="contact.email" aria-describedby="emailFeedback" maxlength="128" required >
                            <div id="emailFeedback" class="invalid-feedback">
                                {{inputEmailError(contact.email)}}
                            </div>
                        </div>
                        <div class="col-md-3 mb-3" title="Povinné pole">
                            <label>Telefon *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputPhoneError(contact.phone)}]" 
                                v-model="contact.phone" aria-describedby="phoneFeedback" maxlength="32" required >
                            <div id="phoneFeedback" class="invalid-feedback">
                                {{inputPhoneError(contact.phone)}}
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Firma</label>
                            <input type="text" :class="['form-control']" 
                                v-model="contact.company" aria-describedby="companyFeedback" maxlength="128">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3" title="Povinné pole">
                            <label>Město *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(contact.city)}]" 
                                v-model="contact.city" aria-describedby="cityFeedback" maxlength="64" required >
                            <div id="cityFeedback" class="invalid-feedback">
                                {{inputTextError(contact.city)}}
                            </div>
                        </div>
                        <div class="col-md-2 mb-3" title="Povinné pole">
                            <label>PSČ *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputZipError(contact.zip)}]" 
                                v-model="contact.zip" aria-describedby="zipFeedback" maxlength="16" required >
                            <div id="zipFeedback" class="invalid-feedback">
                                {{inputZipError(contact.zip)}}
                            </div>
                        </div>
                        <div class="col-md-4 mb-3" title="Povinné pole">
                            <label>Ulice *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(contact.street)}]" 
                                v-model="contact.street" aria-describedby="streetFeedback" maxlength="128" required >
                            <div id="streetFeedback" class="invalid-feedback">
                                {{inputTextError(contact.street)}}
                            </div>
                        </div>
                        <div class="col-md-2 mb-3" title="Povinné pole">
                            <label>Číslo popisné *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputHouseNumberError(contact.houseNumber)}]" 
                                v-model="contact.houseNumber" aria-describedby="houseNumberFeedback" maxlength="16" required >
                            <div id="houseNumberFeedback" class="invalid-feedback">
                                {{inputHouseNumberError(contact.houseNumber)}}
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
                                v-model="billingContact.firstname" aria-describedby="firstnameBillingFeedback" maxlength="128" required >
                            <div id="firstnameBillingFeedback" class="invalid-feedback">
                                {{inputTextError(billingContact.firstname)}}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" title="Povinné pole">
                            <label>Přijmení *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(billingContact.lastname)}]" 
                                v-model="billingContact.lastname" aria-describedby="lastnameBillingFeedback" maxlength="128" required >
                            <div id="lastnameBillingFeedback" class="invalid-feedback">
                                {{inputTextError(billingContact.lastname)}}
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Firma</label>
                            <input type="text" :class="['form-control']" 
                                v-model="billingContact.company" aria-describedby="companyBillingFeedback" maxlength="128">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3" title="Povinné pole">
                            <label>Město *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(billingContact.city)}]" 
                                v-model="billingContact.city" aria-describedby="cityBillingFeedback" maxlength="64" required >
                            <div id="cityBillingFeedback" class="invalid-feedback">
                                {{inputTextError(billingContact.city)}}
                            </div>
                        </div>
                        <div class="col-md-2 mb-3" title="Povinné pole">
                            <label>PSČ *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputZipError(billingContact.zip)}]" 
                                v-model="billingContact.zip" aria-describedby="zipBillingFeedback" maxlength="16" required >
                            <div id="zipBillingFeedback" class="invalid-feedback">
                                {{inputZipError(billingContact.zip)}}
                            </div>
                        </div>
                        <div class="col-md-4 mb-3" title="Povinné pole">
                            <label>Ulice *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputTextError(billingContact.street)}]" 
                                v-model="billingContact.street" aria-describedby="streetBillingFeedback" maxlength="128" required >
                            <div id="streetBillingFeedback" class="invalid-feedback">
                                {{inputTextError(billingContact.street)}}
                            </div>
                        </div>
                        <div class="col-md-2 mb-3" title="Povinné pole">
                            <label>Číslo popisné *</label>
                            <input type="text" :class="['form-control', {'is-invalid': showErrors && inputHouseNumberError(billingContact.houseNumber)}]" 
                                v-model="billingContact.houseNumber" aria-describedby="houseNumberBillingFeedback" maxlength="16" required >
                            <div id="houseNumberBillingFeedback" class="invalid-feedback">
                                {{inputHouseNumberError(billingContact.houseNumber)}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section section-shipping">
                    <div class="section-title">Doprava</div>
                    <table>
                        <tr v-for="(item, index) in shippingOptions" style="margin-bottom: 15px">
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
                    </table>
                    <div id="shippingFeedback" :style="{'display': showErrors && shipping == '' ? 'block' : 'none' }" class="invalid-feedback">Je nutné vybrat způsob dopravy</div>
                </div>
                <div class="section section-payment">
                    <div class="section-title">Platba</div>
                    <table>
                        <tr v-for="(item, index) in paymentOptions" style="margin-bottom: 15px">
                            <td>
                                <div :class="['custom-control', 'custom-radio', {'is-invalid': showErrors && payment == ''}]">
                                    <input type="radio" :id="'payment_' + index" name="payment-radio" class="custom-control-input" :value="item.id" v-model="payment" aria-describedby="paymentFeedback">
                                    <label class="custom-control-label" :for="'payment_' + index">{{item.title}}</label>
                                </div>
                            </td>
                            <td class="price">
                                - {{item.price}} {{config.priceUnit}}
                            </td>
                        </tr>
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
                            Souhlasím s obchodními podmínkami
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
                            Souhlasím se zpracováním osobních údajů (GDPR)
                        </label>
                        <div  id="gdpr3Feedback" class="invalid-feedback">
                            Bez souhlasu nelze zboží objednat
                        </div>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="form-group">
                        <label style="font-size: 95%;">Poznámka k objednávce</label>
                        <textarea class="form-control" rows="5" v-model="customerDescription"></textarea>
                    </div>
                    <div style="font-weight: 500; font-size: 130%; padding: 5px 0 20px 0;"> Celkem zaplatíte: {{totalPrice}} {{config.priceUnit}}</div>
                    <button class="btn btn-success" @click="btClickTrySend()" type="submit">Odeslat objednávku</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import ShippingOptions from '/models/shippingOptions.ts';
import PaymentOptions from '/models/paymentOptions.ts';

export default {
    data() {
        return {
            documentReady: false,
            showErrors: false,
            cookie: null,
            contact: {
                firstname: '',
                lastname: '',
                city: '',
                zip: '',
                street: '',
                houseNumber: '',
                email: '',
                phone: '',
                company: '',
            },
            billingContact: {
                firstname: '',
                lastname: '',
                city: '',
                zip: '',
                street: '',
                houseNumber: '',
                company: '',
            },
            shippingOptions: ShippingOptions,
            paymentOptions: PaymentOptions,
            bllingAddressSame: true,
            consentGDPR: false,
            consentBusinessConditions: false,
            customerDescription: '',
            productsList: [],
            matchedProducts: [],
            config: null,
            shipping: '',
            payment: '',
            modalSuccess: '',
            modalFail: '',
        }
    },
    computed: {
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
                price += this.shippingOptions.find(itm => itm.id == this.shipping)?.price;

            if (this.payment)
                price += this.paymentOptions.find(itm => itm.id == this.payment)?.price;

            return price;
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
                this.modalFail = 'Zkontrolujte prosím formulář, něco je špatně vyplněné.';
                return;
            }

            const obj2send = {
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
                shippingPrice: this.shippingOptions.find(itm => itm.id == this.shipping)?.price,
                payment: this.payment,
                paymentPrice: this.paymentOptions.find(itm => itm.id == this.payment)?.price,
                productsPrice: this.productsPrice,
                totalPrice: this.totalPrice,
            }

            var response = await $fetch('/api/data_order_post', {
                method: 'POST',
                body: { order: obj2send }
            });

            console.log(response);

            if (response == 'true') {
                this.cookie.clear();
                this.modalSuccess = 'Objednávka úspěšně přijata, rekapitulaci objednávky dostanene emailem.';
            } else {
                this.modalFail = 'Došlo k nějaké chybě, zkuste to prosím poději nebo nám prosím napište email. Děkujeme.';
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
        inputHouseNumberError(text) {
            if (!text || !text.length)
                return 'Vyplňte prosím pole.'
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
        const config = await $fetch('/api/data_config');
        this.config = JSON.parse(config);

        const data = await $fetch('/api/data_products');
        this.productsList = JSON.parse(data);
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