<template>
    <div>
        <div class="control-panel">
            <div class="col-sm-3 item">
                <label> Seřadit podle: </label>
                <select class="form-control form-control-sm" v-model="orderBy" @change="orderChanged()">
                    <option value="id asc">Číslo vzestupně</option>
                    <option value="id desc">Číslo sestupně</option>
                    <option value="created asc">Nejmladší</option>
                    <option value="created desc">Nejstarší</option>
                    <option value="state asc">Stav vzestupně</option>
                    <option value="state desc">Stav sestupně</option>
                    <!--option value="customer asc">Zákazník vzestupně</option>
                    <option value="customer desc">Zákazník sestupně</option>
                    <option value="email asc">Email vzestupně</option>
                    <option value="email desc">Email sestupně</option-->
                    <option value="totalPrice asc">Celková cena vzestupně</option>
                    <option value="totalPrice desc">Celková cena sestupně</option>
                    <option value="shipping asc">Doprava vzestupně</option>
                    <option value="shipping desc">Doprava sestupně</option>
                    <option value="payment asc">Platba vzestupně</option>
                    <option value="payment desc">Platba sestupně</option>
                </select>
            </div>
            <div class="col-sm-3 item">
                <label> Filtr - stav </label>
                <select class="form-control form-control-sm" v-model="filterState" @change="filterStateChanged()">
                    <option value="*">Zobrazit vše</option>
                    <option v-for="item in orderStates" :value="item.title">{{item.title}}</option>
                </select>
            </div>
        </div>
        <div v-if="orderList"> 
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Číslo</th>
                        <th>Datum</th>
                        <th>Stav</th>
                        <th>Zákaznik</th>
                        <th>Email</th>
                        <th>Celková cena</th>
                        <th>Doprava</th>
                        <th>Platba</th>
                        <th>Produktů</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in orderList" @click="btClickOrderDetail(item)" style="cursor: pointer" title="přejít na detail">
                        <td>{{item.id}}</td>
                        <td>{{new Date(item.created).toLocaleString()}}</td>
                        <td :style="{'background': getStateColor(item.state), 'text-align': 'center'}"><span style="font-weight: 500; color: white">{{item.state}}</span></td>
                        <td>{{item.data?.contact?.firstname}} {{item.data?.contact?.lastname}}</td>
                        <td>{{item.data?.contact?.email}}</td>
                        <td>{{item.totalPrice}}</td>
                        <td>{{shippingOptions.find(itm => itm.id == item.shipping)?.title}}</td>
                        <td>{{paymentOptions.find(itm=> itm.id == item.payment)?.title}}</td>
                        <td>{{item.data?.products?.length}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="pagination" v-if="totalCount > limit">
            <button @click="goToPage(1)" v-if="page > 2" class="btn btn-secondary" style="rotate: 180deg" title="první stránka"> ➤ </button>
            <button @click="goToPage(page - 2)" v-if="page > 2" class="btn btn-secondary">{{ page - 2 }}</button>
            <button @click="goToPage(page - 1)" v-if="page > 1" class="btn btn-secondary">{{ page - 1 }}</button>
            <button class="btn btn-success">{{ page }}</button>
            <button @click="goToPage(page + 1)" v-if="maxPage > page" class="btn btn-secondary">{{ page + 1 }}</button>
            <button @click="goToPage(page + 2)" v-if="maxPage - page > 1" class="btn btn-secondary">{{ page + 2 }}</button>
            <button @click="goToPage(maxPage)" v-if="maxPage - page > 1" class="btn btn-secondary"  title="poslední stránka"> ➤ </button>
        </div>
    </div>
</template>

<script>
import OrderStates from '/models/OrderStates.ts';
  export default {
    middleware: 'auth',
    data() {
        return {
            orderBy: 'id desc',
            filterState: '*',
            productsList: [],
            products2View: [],
            config: null,
            limit: 40,
            offset: 0,
            totalCount: 0,
            orderList: null,
            orderStates: OrderStates
        }
    },
    computed: {
        shippingOptions() { return this.config?.shippingOptions},
        paymentOptions() { return this.config?.paymentOptions},
        page() { return parseInt(this.offset / this.limit) + 1},
        maxPage() { return parseInt(this.totalCount / this.limit) + 1}
    },
    methods: {
        async goToPage(page) {
            this.offset = (page - 1) * this.limit;
            this.updateUrl();
            await this.fetchOrders();  
        },
        async orderChanged() {
            this.updateUrl();
            await this.fetchOrders();
        },
        async filterStateChanged() {
            this.updateUrl();
            await this.fetchOrders();
        },
        getStateColor(state) {
            const item = this.orderStates.find(itm => itm.title == state)
            if (item)
                return item.color;
            return 'black';
        },
        getOptionsObj() {
            return {
                limit: this.limit,  
                offset: this.offset,
                orderBy: this.orderBy,
                filterState: this.filterState == '*' ? null : this.filterState, 
            }
        },
        updateUrl() {
            history.pushState({},null,this.$route.path + '?options=' + JSON.stringify(this.getOptionsObj()));
        },
        async fetchOrders() {
            try {
                var resposne = await $fetch(this.$config.bUrl + 'getOrders.php?options=' + JSON.stringify(this.getOptionsObj()));
                const parsedResp = JSON.parse(resposne);
                this.orderList = parsedResp.orders;
                this.totalCount = parsedResp.totalCount;
            } catch(exception) {
                alert('Došlo k nějaké chybě');
                throw exception;
            }
        },
        btClickOrderDetail(item) {
            //window.location.href = '/admin/order/' + item.id;
            window.location.href = '/admin/order?id=' + item.id;
        }
    },
    async mounted() {
        if(this.$route.query?.options) {
            const obj = JSON.parse(this.$route.query.options);
            this.limit = obj.limit;
            this.offset = obj.offset;
            this.orderBy = obj.orderBy;
            this.filterState = obj.filterState == null ? '*' : obj.filterState;
        }
        try {
            this.config = JSON.parse(await $fetch(this.$config.bUrl + 'getConfig.php'));
        } catch(exception) {
            alert('Došlo k nějaké chybě');
            throw exception;
        }
        await this.fetchOrders();
    }
  }
</script>

<style>
    .control-panel .item {
        display: inline-block;
        padding: 0 5px;
    }

    .control-panel .item label {
        font-size: 90%;
        font-style: italic;
    }

    .control-panel {
        padding: 20px 0;
    }

    #pagination {
        text-align: center;
    }
    #pagination button {
        margin: 2px;

    }
</style>