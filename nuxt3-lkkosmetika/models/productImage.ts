
export default class ProductImage {
    src: string
    dataBase64String: string
    alt: string
    name: string
    status: string = null
    savedOnServer: boolean = false

    constructor(obj = null) {
        if (obj)
            Object.assign(this, obj);
    }
}