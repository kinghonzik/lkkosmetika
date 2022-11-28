import ProductImage from '@/models/productImage';

class ProductData {
    title: string
    id: string
    price?: number
    stockStatus: string = 'skladem'
    manufacturer?: string
    categories?: [string?] = []
    usages?: [object?] = []
    published: boolean = true
    descriptionShort?: string
    description?: string
    image? : ProductImage = new ProductImage
    created?: string
    additionalImages?: [ProductImage?] = []
    variants?: [object?] = []
    specifications?: [object?] = []
}

export default class Product {
    id: number
    data: ProductData = new ProductData
}