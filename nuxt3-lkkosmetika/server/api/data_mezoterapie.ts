export default defineEventHandler((event) => {
    return {
        header: {
            title: 'Mezoterapie - dermapero',
            meta: [
                {
                  hid: `description`, /* HID je pro override kdyz mam dynamicky generovany starnky */
                  name: 'description',
                  content: 'Jedná se o nejvyhledávanější neinvazivní metodu, pro dosažení mladšího vzhledu. Metoda účinně stimuluje tvorbu vlastního kolagenu a elastinu.'
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  content: 'Goldpen, Mezoterapie, Cisteni pleti, Tonizace pleti, Dermapero, Hyperpigmentace, Padani vlasu, anti age'
                }
            ],
        }
    }
  })