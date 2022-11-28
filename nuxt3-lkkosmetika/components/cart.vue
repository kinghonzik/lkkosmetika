<template>
    <div>
        <div class="cart"> 
          <a href="/eshop/checkout" rel="nofollow" > 
            <span class="icon-border"> <img src= "/assets/img/cart-30.png" /></span>
            <span class="count">{{productsCount}} </span>
          </a> 
        </div>
    </div>
</template>

<script>

  export default {
    data() {
      return {
        cooObj: null,
        cookie: null,
      }
    },
    computed: {
      productsCount() {
        if (!this.cookie)
          return 0;
        let count = 0;
        for (const pr of this.cookie.getProducts()) {
          count += pr.count;
        }
        return count;
      }
    },
    mounted() {
      this.cookie = useCookie();
    }
  }
</script>

<style scoped>
  .cart {
    text-align: right; 
    padding: 30px 20px 10px 20px;
  }

  .cart img {
    max-width: 40px;
  }

  .cart a {
    text-decoration: none;
    color: inherit;
  }

  .count {
    translate: 0 -50%;
    float: right;
    font-size: 160%;
    font-weight: 600;
    color: #fff !important;
    background: #ff9100 !important;
    font-weight: bold !important;
    font-size: small !important;
    border-radius: 12px !important;
    padding: 2px 6px !important;
    left: -1em !important;
  }
</style>