<template>
    <div class="page-content">
        <div v-if="order && config">
            <div class="title">{{'Objednávka: ' + order.id}}</div>
            <div>
                <button @click="btClickSave()" class="btn btn-success " style="margin: 10px 0 20px 20px"> Uložit</button>
                <button @click="btClickSaveAndClose()" class="btn btn-success " style="margin: 10px 0 20px 5px"> Uložit a zavřít </button>
                <button @click="btClickGoBack()" class="btn btn-danger " style="margin: 10px 0 20px 5px"> Zavřít </button>
                <button  @click="btClickOpenChangeDialog()" class="btn btn-outline-success" style="margin: 10px 0 20px 5px" >Změnit stav</button>
            </div>
            <div class="section section-inline col-sm-6">
                <div class="title"> Základní informace </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Stav </label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" v-model="order.state" disabled />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Vytvořeno </label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" :value="new Date(order.created).toLocaleString()" disabled />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Platba</label>
                    <div class="col-sm-8">
                        <select class="form-control" v-model="payment">
                            <option v-for="item in paymentOptions" :value="item.id">{{item.title}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Doprava</label>
                    <div class="col-sm-8">
                        <select class="form-control" v-model="shipping">
                            <option v-for="item in shippingOptions" :value="item.id">{{item.title}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Platba - cena</label>
                    <div class="col-sm-6">
                        <input type="number" min="0" class="form-control" v-model="order.paymentPrice" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Doprava - cena</label>
                    <div class="col-sm-6">
                        <input type="number" min="0" class="form-control" v-model="order.shippingPrice" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Celková cena</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" v-model="totalPrice" disabled/>
                    </div>
                </div>
            </div>
            <div v-show="order.sentEmails" class="section section-inline col-sm-6" style="max-height: 500px; overflow-x: auto;">
                <div class="title" style="position: sticky;top: 0;background: white;border-bottom: 1px solid lightgray;"> Odeslané maily </div>
                <table v-if="order.sentEmails" class="table table-striped">
                    <thead>
                    <tr>
                        <!--th>#</th-->
                        <th>Datum</th>
                        <th>Stav</th>
                        <th v-if="false">Faktura</th>
                        <th>Poznámka</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in [...order.sentEmails].reverse()">
                        <!--td>{{index + 1}}</td-->
                        <td>{{new Date(item.datetime).toLocaleString()}}</td>
                        <td>{{item.state}}</td>
                        <td v-if="false">{{item.invoice ? 'Ano' : 'Ne'}}</td>
                        <td :title="item.desc">{{formatDesc(item.desc)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="order.data.customerDescription" class="section section-inline col-sm-12">
                <div class="title">Zákazníkova poznámka </div>
                <div> {{order.data.customerDescription}}</div>
            </div>
            <div class="section col-sm-12">
                <div class="title"> Produkty </div>
                <table v-if="order.data?.products" class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Název</th>
                        <th>Varianta</th>
                        <th>Počet</th>
                        <th>Cena (mj)</th>
                        <th>Cena</th>
                        <th>ID</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in order.data.products">
                        <td>{{index + 1}}</td>
                        <td><input class="form-control" type="text" v-model="item.title"></td>
                        <td><input class="form-control" type="text" v-model="item.variant"></td>
                        <td><input class="form-control" style="max-width: 100px;" type="number" min="1" v-model="item.count"/></td>
                        <td><input class="form-control" style="max-width: 130px;" type="number" min="1" v-model="item.price"/></td>
                        <td>{{item.price * item.count}}</td>
                        <td>{{item.id}}</td>
                        <td>
                            <button 
                                @click="btClickRemoveProduct(index)" 
                                class="btn btn-outline-danger btn-sm" 
                                style="margin: 10px 0 0 5px; float: right" title="odebrat produkt"> ✘ 
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div>
                    <button @click="order.data.products.push({title: 'Nový produkt', price: 100, count: 1})" 
                    class="btn btn-outline-success btn-sm" style="margin: 10px 5px 0 0"> Přidat produkt
                    </button>
                </div>
            </div>
            <div class="section section-inline col-sm-6">
                <div v-if="bllingAddressSame" class="title">Doručovací a fakturační údaje</div>
                <div v-else class="title">Doručovací údaje</div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td><label>Jméno</label></td>
                            <td><input type="text" :class="['form-control']" v-model="contact.firstname" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Přijmení</label></td>
                            <td><input type="text" :class="['form-control']" v-model="contact.lastname" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Email</label></td>
                            <td><input type="text" :class="['form-control']" v-model="contact.email" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Telefon</label></td>
                            <td><input type="text" :class="['form-control']" v-model="contact.phone" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Firma</label></td>
                            <td><input type="text" :class="['form-control']" v-model="contact.company" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Město</label></td>
                            <td><input type="text" :class="['form-control']" v-model="contact.city" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>PSČ</label></td>
                            <td><input type="text" :class="['form-control']" v-model="contact.zip" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Ulice</label></td>
                            <td><input type="text" :class="['form-control']" v-model="contact.street" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Číslo popisné</label></td>
                            <td><input type="text" :class="['form-control']" v-model="contact.houseNumber" maxlength="128"/></td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <div class="form-check">
                        <input :class="['form-check-input']" type="checkbox" value="" id="billingAdressSame" v-model="bllingAddressSame">
                        <label class="form-check-label" for="billingAdressSame">Fakturační adresa je stejná</label>
                    </div>
                </div>
            </div>
            <div v-if="!bllingAddressSame" class="section section-inline col-sm-6">
                <div class="title">Fakturační údaje</div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td><label>Jméno</label></td>
                            <td><input type="text" :class="['form-control']" v-model="billingContact.firstname" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Přijmení</label></td>
                            <td><input type="text" :class="['form-control']" v-model="billingContact.lastname" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Firma</label></td>
                            <td><input type="text" :class="['form-control']" v-model="billingContact.company" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Město</label></td>
                            <td><input type="text" :class="['form-control']" v-model="billingContact.city" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>PSČ</label></td>
                            <td><input type="text" :class="['form-control']" v-model="billingContact.zip" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Ulice</label></td>
                            <td><input type="text" :class="['form-control']" v-model="billingContact.street" maxlength="128"/></td>
                        </tr>
                        <tr>
                            <td><label>Číslo popisné</label></td>
                            <td><input type="text" :class="['form-control']" v-model="billingContact.houseNumber" maxlength="128"/></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Modal v-if="stateDialog" style="z-index: 10">
                <div class="modal-title">Změna statusu</div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Stav objednávky</label>
                    <div class="col-sm-8">
                        <select class="form-control" v-model="newState">
                            <option v-for="item in orderStates" :value="item.title">{{item.title}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="padding-left: 15px;">
                    <div class="form-check">
                        <input :class="['form-check-input']" 
                            type="checkbox" id="sendMsgCustomer" 
                            v-model="mailToCustomer" required>
                        <label class="form-check-label" for="sendMsgCustomer">Odeslat zprávu o stavu objednáky zákazníkovi</label>
                    </div>
                </div>
                <!--div class="form-group row" v-if="mailToCustomer">
                    <label class="col-sm-12 col-form-label"> Mail</label>
                    <div class="col-sm-12">
                    <editor :init="{ 
                        height: 500, 
                        plugins: [
                            'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                            'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
                            'media', 'table', 'emoticons', 'template', 'help'
                        ],
                        toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                            'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                            'forecolor backcolor emoticons | help',
                        menu: {
                            favs: { title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons' }
                        }
                        }" 
                        v-model="mailHtml" 
                    >
                    
                    </editor>
                    </div>
                </div-->
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Poznámka</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" v-model="mailToCustomerDesc" :disabled="mailToCustomer == false"></textarea>
                    </div>
                </div>
                <div class="form-group row" style="padding-left: 15px;">
                    <div class="form-check">
                        <input :class="['form-check-input']" 
                            type="checkbox" id="sendMsgCustomer" 
                            v-model="mailToCustomerInvoice" required>
                        <label class="form-check-label" for="sendMsgCustomer">Odeslat zákazníkovi fakturu v příloze (ještě nemáme doprogramovaný)</label>
                    </div>
                </div>
                <button @click="stateDialog = false" class="btn btn-danger">Zavřít</button>
                <button @click="btClickChangeState()" class="btn btn-success" style="margin-left: 10px;">Ok</button>
            </Modal>
            <div v-show="false" id="mailContent">
                <div class="mail-content">
                    <div class='title'> Dobrý den vážený zákazníku </div>
                    <div class='data-row'> Stav vaší objednávky byl změněn na: <b>{{order.state}}</b></div>
                    <div class='data-row'>Objednávka číslo: {{order.id}} </div>
                    <div class='data-row' >{{mailToCustomerDesc}}</div>
                    <div class='data-row'> Rekapitulace objednávky: </div>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Název</th>
                                <th>Počet</th>
                                <th>Cena (mj)</th>
                                <th>Cena</th>
                            </tr>
                        </thead>
                        <tbody v-for="(item, index) in order.data.products">
                            <tr>
                                <td>{{index + 1}}</td>
                                <td>{{item.title}}</td>
                                <td>{{item.count}}</td>
                                <td>{{item.price}} {{config.priceUnit}}</td>
                                <td>{{item.price * item.count}} {{config.priceUnit}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class='data-row'> Doprava:  {{shippingOptions.find(itm => itm.id == order.shipping)?.title}} - {{order.shippingPrice}} {{config.priceUnit}}</div>
                    <div class='data-row'> Platba: {{paymentOptions.find(itm => itm.id == order.payment)?.title}} -  {{order.paymentPrice}} {{config.priceUnit}}</div>
                    <div class='data-row'><b>Celková cena: {{order.totalPrice}} {{config.priceUnit}}</b></div>
                    <div class="mail-contact">
                        <div class='data-row' ><b>{{ bllingAddressSame ? 'Doručovací údaje' : 'Doručovací a fakturační údaje'}}</b></div>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Zákazník</td>
                                    <td>{{order.data.contact.firstname}} {{order.data.contact.lastname}}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{order.data.contact.email}}</td>
                                </tr>
                                <tr>
                                    <td>Telefon</td>
                                    <td>{{order.data.contact.phone}}</td>
                                </tr>
                                <tr v-if="order.data.contact.company">
                                    <td>Firma</td>
                                    <td>{{order.data.contact.company}}</td>
                                </tr>
                                <tr>
                                    <td>Adresa</td>
                                    <td>{{order.data.contact.city}} {{order.data.contact.zip}} {{order.data.contact.street}} {{order.data.contact.houseNumber}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="!bllingAddressSame" class="mail-contact">
                        <div class='data-row'><b>{{'Fakturační údaje'}}</b></div>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Zákazník</td>
                                    <td>{{billingContact.firstname}} {{billingContact.lastname}}</td>
                                </tr>
                                <tr v-if="billingContact.company">
                                    <td>Firma</td>
                                    <td>{{billingContact.company}}</td>
                                </tr>
                                <tr>
                                    <td>Adresa</td>
                                    <td>{{billingContact.city}} {{billingContact.zip}} {{billingContact.street}} {{billingContact.houseNumber}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class='data-row'> S přáním hezkého dne </div>
                    <div class='data-row'> Tým LKkosmetika.cz</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import ShippingOptions from '/models/shippingOptions.ts';
import PaymentOptions from '/models/paymentOptions.ts';
import OrderStates from '/models/OrderStates.ts';
import Editor from '@tinymce/tinymce-vue'

  export default {
    data() {
        return {
            mailDialog: false,
            stateDialog: false,
            newState: '',
            mailToCustomer: false,
            mailToCustomerDesc: '',
            mailToCustomerInvoice: false,
            config: null,
            order: null,
            shippingOptions: ShippingOptions,
            paymentOptions: PaymentOptions,
            orderStates: OrderStates,
            mailHtml: '',
        }
    },
    components: {
          'editor': Editor
        },
    computed: {
        shipping: {
            get() { return this.order.shipping},
            set(newVal) { const obj = this.shippingOptions.find(itm => itm.id == newVal); this.order.shipping = obj.id; this.order.shippingPrice = obj.price }
        },
        payment: {
            get() { return this.order.payment},
            set(newVal) { const obj = this.paymentOptions.find(itm => itm.id == newVal); this.order.payment = obj.id; this.order.paymentPrice = obj.price; console.log(this.order)}
        },
        contact() {
            return this.order.data.contact
        },
        billingContact() {
            return this.order.data.billingContact
        },
        bllingAddressSame: {
            get() { return this.order.data.bllingAddressSame },
            set(newVal) { this.order.data.bllingAddressSame = newVal }
        },
        productsPrice() {
            var result = 0;
            for (const item of this.order.data.products)
                result += item.price * item.count; 
            return result;
        },
        totalPrice() {
             return this.productsPrice + +this.order.shippingPrice + +this.order.paymentPrice;
        }
    }, 
    methods: {
        formatDesc(desc) {
            if (!desc)
                return '';
            const maxLength = 20;

            if (desc.length > maxLength) {
                return desc.substring(0, maxLength - 1) + '...';
            }
            return desc;
        },
        async save() {
            this.order.productsPrice = this.productsPrice;
            this.order.totalPrice = this.totalPrice;

            try {
                const obj2send = this.order;
                const response = await $fetch(this.$config.bUrl + 'putOrder.php', {
                    method: 'POST',
                    body: { order: obj2send }
                });

                if(response == 'true') {
                    return true;
                }
            } catch (exception) {
                alert('Uložení neproběhlo - došlo k nějaké chybě!');
                throw exception;
            }

            return false;
        },
        async btClickSave() {
            const result = await this.save();
            if (result) {
                window.alert('Úspěšně uloženo!');
            }
        },
        async btClickSaveAndClose() {
            const result = await this.save();
            if (result) {
                indow.history.back();
            }
        },
        btClickGoBack() {
            window.history.back();
        },
        btClickOpenChangeDialog() {
            this.mailHtml = document.getElementById('mailContent').innerHTML;
            this.newState = this.order.state;
            this.stateDialog = true;
        },
        btClickRemoveProduct(index) {
            if (window.confirm('Opravdu si přejete odebrat produkt z objednávky?'))
                this.order.data.products.splice(index, 1)
        },
        async btClickChangeState() {
            const obj2send = JSON.parse(JSON.stringify(this.order));
            obj2send.state = this.newState;
            obj2send.mailToCustomer = this.mailToCustomer;
            obj2send.mailToCustomerDesc = this.mailToCustomerDesc?.trim();
            obj2send.mailToCustomerInvoice = this.mailToCustomerInvoice;
            obj2send.mailHtml = document.getElementById('mailContent').innerHTML;

            try {
                let response = await $fetch(this.$config.bUrl + 'putOrderState.php', {
                    method: 'POST',
                    body: { order: obj2send }
                });
                const result = JSON.parse(response);
                console.log(result);
                if (result.mailSent == true) {
                    alert('Email byl úspěšně odeslán.')
                    this.stateDialog = false
                    await this.fetchOrderData()
                    this.mailToCustomer = false
                    this.mailToCustomerDesc = ''
                    this.mailToCustomerInvoice = false
                }
            } catch (exception) {
                console.log(exception);
                alert('Došlo k nějaké chybě');
            }
        },
        async fetchOrderData() {
            const route = useRoute();
            const orderId = route.params.order_id;
            var resposne = await $fetch(this.$config.bUrl + 'getOrderByID.php?id=' + orderId);
            console.log(JSON.parse(resposne))
            this.order = JSON.parse(resposne);
        },
    },
    async mounted() {
        try {
            this.config = JSON.parse(await $fetch(this.$config.bUrl + 'getConfig.php'));
        } catch(exception) {
            alert('Došlo k nějaké chybě');
            throw exception;
        }

        await this.fetchOrderData();
    }
  }
</script>

<style scoped>

@import '~/assets/css/mail.css';

    .page-content {
        padding-bottom: 50px;
    }

    .title {
      font-size: 180%;
      text-align: center;
      padding: 30px;
    }

    .modal-title {
      font-size: 150%;
      text-align: center;
        padding: 15px;
        border-bottom: 1px solid lightgrey;
        margin-bottom: 30px;
    }

    .section-inline {
        display: inline-grid;
    }

    .section .title {
      padding: 10px;
      padding-left: 0;
      padding-top: 20px;
      font-size: 160%;
      text-align: left;
    }
    
</style>