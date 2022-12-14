<template>
    <div>
        <div id="mailContent">
            <div v-if="order && config" class="mail-content">
                <template v-if="type == 'state_change'">
                    <div class='title'> Dobrý den vážený zákazníku </div>
                    <div class='data-row'> Stav vaší objednávky byl změněn na: <b>{{newState ??order.state}}</b></div>
                    <div class='data-row'>Objednávka číslo: {{order.id}} </div>
                </template>
                <template v-if="type == 'new_order' && order.payment != 'bankovni-prevod'">
                    <div class='title'> Dobrý den vážený zákazníku </div>
                    <div class='data-row'> Děkujeme Vám za vaší objednávku. Nyní budeme usilovně pracovat na jejím vyřízení.</div>
                </template>
                <template v-if="type == 'new_order' && order.payment == 'bankovni-prevod'">
                    <div class='title'> Dobrý den vážený zákazníku </div>
                    <div class='data-row'> Děkujeme Vám za vaší objednávku.</div>
                    <div class='data-row'> 
                        <div>Proveďte prosím platbu ve výši <b>{{ order.totalPrice}} {{config.priceUnit}}</b></div>
                        <div>Číslo účtu  <b>{{ config.accNumber}}</b> </div>
                        <div>Variabilní symbol  <b>{{ '{var_symbol}'}} </b></div>
                    </div>
                </template>
                <div v-if="mailToCustomerDesc" class='data-row' >{{mailToCustomerDesc}}</div>
                <div class="recap-section">
                    <div class='data-row'> Rekapitulace objednávky: </div>
                    <table class="table-products">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Název produktu</th>
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
                    <div class='data-row'> Doprava: {{shippingOptions.find(itm => itm.id == order.shipping)?.title}} - {{order.shippingPrice}} {{config.priceUnit}}</div>
                    <div class='data-row'> Platba: {{paymentOptions.find(itm => itm.id == order.payment)?.title}} -  {{order.paymentPrice}} {{config.priceUnit}}</div>
                    <div class='data-row'><b>Celková cena: {{order.totalPrice}} {{config.priceUnit}}</b></div>
                </div>
                <div class="mail-contact">
                    <div class='data-row' ><b>{{ bllingAddressSame ? 'Doručovací údaje' : 'Doručovací a fakturační údaje'}}</b></div>
                    <table class="table-contact">
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
                    <table class="table-contact">
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
                <div class="footer">
                    <div class='data-row'> S přáním hezkého dne </div>
                    <div class='data-row'> Tým LKkosmetika.cz </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default defineComponent({
        props: ['order', 'config', 'mailToCustomerDesc', 'type', 'newState'],
        data() {
            return {
                eaderParams: null,
            }
        },
        computed: {
            shippingOptions() { return this.config?.shippingOptions},
            paymentOptions() { return this.config?.paymentOptions},
            billingContact() { return this.order.data.billingContact },
            bllingAddressSame() { return this.order.data.bllingAddressSame }
        },
        methods: {

        },
        async mounted() {
            // styly pro mail
            const link = document.createElement("link");
                link.href = this.$config.mailCss;
                link.type = "text/css";
                link.rel = "stylesheet";
                link.media = "screen,print";
            document.getElementsByTagName("head")[0].appendChild(link);
        }
    })
</script>