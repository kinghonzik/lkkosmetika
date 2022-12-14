export default defineEventHandler((event) => {
    return {
        header: {
            title: 'Lucie Kovaříková kosmetika',
            meta: [
                {
                  hid: `description`, /* HID je pro override kdyz mam dynamicky generovany starnky */
                  name: 'description',
                  content: 'Lucie Kováříková je frekventantkou kosmetického institutu z Hradce Králové, kde zakončila profesní zkouškou akreditovaný kurz kosmetička pod záštitou ministerstva školství, mládeže a tělovýchovy.'
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  keywords: 'Lucie Kovarikova, Kosmetika, O mne'
                }
            ],
        },
        title: 'Lucie Kováříková',
        imageSrc: null,
        imageAlt: '',
        description: 'Jsem frekventantkou kosmetického institutu v Hradci Králové, kde jsem úspěšně zakončila profesní zkouškou akreditovaný kosmetický kurz pod záštitou ministerstva školství, mládeže a tělovýchovy. Dále jsem se pak vzdělávala v oboru a rozšiřovala si služby dalšími nadstavbovými kurzy jako například kurz depilace cukrovou pastou, mezoterapie, chemický peeling, liftingová masáž obličeje, mikromasáž očního okolí, péče o aknózní pleť, lash lifting a úprava obočí bio Hennou, laminace obočí. Kurzy nekončím, svoji práci miluji a mojí největší vášní je sledovat vývoj tohoto odvětví. Těším se na kurz depilace ve Španělsku, jelikož tam jsou největší mistři tohoto oboru. Chtěla bych se v depilacích zdokonalovat a jednou si třeba v Čechách otevřít depilační centrum, kam budou klienti moct chodit jako k lékaři a na počkání dostat svou službu v příjemném, až lázeňském prostředí, podobně jako tomu je například v Americe, kde se depilacemi proslavily sestry J., ale to už je jiný příběh',
    }
  })