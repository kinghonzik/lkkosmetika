export default {
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
        bUrl: process.env.NODE_ENV === 'production' ? 'http://localhost/lkkosmetika/backend-lkkoksmetika/' : 'http://localhost:3080/lkkosmetika/backend-lkkoksmetika/',
    }
}
