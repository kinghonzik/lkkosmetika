import IProduct from '@/models/product'

export default async () => await $fetch('http://localhost/backend-lkkoksmetika/getProducts.php');