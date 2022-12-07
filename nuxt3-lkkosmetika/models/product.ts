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
    additionalImages?: Array<ProductImage> = []
    variants?: [object?] = []
    specifications?: [object?] = []
    seoTitle?: string
    seoDesc?: string
    seoKeywords?: string
}

export default class Product {
    id: number
    data: ProductData = new ProductData

    constructor(obj = null) {
        if (obj) {           
            // inicializace nekterych objektu
            Object.assign(this, obj);

            if (this.data.image) {
                this.data.image = new ProductImage(this.data.image);
            }

            if (this.data.additionalImages?.length) {
                const arr = [];
                console.log('par')
                for (let img of this.data.additionalImages) {
                    arr.push(new ProductImage(img));
                }
                this.data.additionalImages = arr;
            }
        }
    }
}