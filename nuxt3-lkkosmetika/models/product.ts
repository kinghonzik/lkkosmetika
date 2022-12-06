import ProductImage from '@/models/productImage';

class ProductData {
    title: string
    id: string
    price?: number = 100
    stockStatus: string = 'skladem'
    manufacturer?: string = 'bez-vyrobce'
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
    seoTitle?: string
    seoDesc?: string
    seoKeywords?: string
}

export default class Product {
    id: number
    data: ProductData = new ProductData
}