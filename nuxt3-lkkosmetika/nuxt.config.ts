export default {
    target: 'static',
    css: [
        '@/assets/css/global.css',
        'bootstrap/dist/css/bootstrap.min.css'
    ],  
    app: {
        baseURL: '/',
        head: {
            script: [
                {
                    src: "/assets/js/jquery-3.2.1.slim.min.js",
                },
                {
                    src: "/assets/js/popper.min.js",
                },
                {
                    src: "/assets/js/bootstrap.min.js",
                }
            ],
        }
    },
    publicRuntimeConfig: {
        bUrl: process.env.NODE_ENV === 'production' ? 'https://lkkosmetika.cz/backend-lkkoksmetika/' : 'http://localhost/lkkosmetika/backend-lkkoksmetika/',
        mailCss: process.env.NODE_ENV === 'production' ? 'https://lkkosmetika.cz/backend-lkkoksmetika/css/mail.css' : 'http://localhost/lkkosmetika/backend-lkkoksmetika/css/mail.css',
    }
}
