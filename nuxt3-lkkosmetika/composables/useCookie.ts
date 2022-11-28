export default function () {

  function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

    class Cookie {

      private products;
      private expirationDays = 1;

      constructor() {
        const productFromCookie = getCookie('products');
        if (productFromCookie)
          this.products = JSON.parse(productFromCookie);
        else 
          this.products = [];
      }

      getProducts() {
        return [...this.products];
      }

      setProducts(productsList) {
        if (!productsList || !productsList.length) {
          this.clearProducts();
          return;
        }

        this.products = [...productsList];
        this.saveProductsCookie();
      }

      addProduct(id, count, variant = null) {
        const fProduct = this.products.find(itm => itm.id == id && itm.variant == variant);
        if (fProduct) {
          fProduct.count += count;
        } else {
          this.products.push({id: id, count: count, variant: variant})
        }
        this.saveProductsCookie();
      }

      removeProduct(id, variant = null) {
        const product  = this.products.find(itm => itm.id == id && variant == itm.variant)
        this.products.splice(this.products.indexOf(product), 1);
        this.saveProductsCookie();
      }

      updateProduct(id, count, variant = null) {
        const product = this.products.find(itm => itm.id == id && variant == itm.variant)
        if (!product)
          return;
          product.count = count;
        this.saveProductsCookie();
      }

      clear() {
        this.products.splice(0, this.products.length);
        setCookie('products', JSON.stringify(this.products), this.expirationDays)
      }

      saveProductsCookie() {
        setCookie('products', JSON.stringify(this.products), this.expirationDays)
      }

      clearProducts() {
        this.products = [];
        this.saveProductsCookie();
      }

    }

    const cookie = new Cookie();
    return useState('cookie', () => cookie)
  }