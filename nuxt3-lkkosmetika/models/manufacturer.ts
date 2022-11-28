export default interface IProduct {
    id?: number,
    data: {
        title?: string,
        price?: number,
        priceUnit?: string,
        availability?: string,
        manufacturer?: string,
        category?: string,
        descriptionShort?: string,
        description?: string,
        imageSrc?: string,
        imageAlt?: string,
    }
}