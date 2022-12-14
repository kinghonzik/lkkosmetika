export default defineEventHandler((event) => {
    return {
        header: {
            title: 'BROW & LASH BAR - Barvení obočí biohennou a lash lifting bomb',
            meta: [
                {
                  hid: `description`, /* HID je pro override kdyz mam dynamicky generovany starnky */
                  name: 'description',
                  content: 'Správná úprava obočí se stala velice významným trendem, přijďte do mého salónu vybrat správný tvar pomocí architektury obočí. Lash lifting je speciální úprava přirozených řas, které se natočí, opticky prodlouží, obarví a vyživí.'
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  content: 'KBio hena, Lash lifting bomb, Rasy, Uprava, Oboci'
                }
            ],
        }
    }
  })