export default defineEventHandler((event) => {
    return {
        header: {
            title: 'Depilace - Cukrová pasta, roll-on, cirépil',
            meta: [
                {
                  hid: `description`, /* HID je pro override kdyz mam dynamicky generovany starnky */
                  name: 'description',
                  content: 'Nabízím všechny druhy depilací, dámské i pánské, depiluji jak samostržnými vosky, roll on vosky, tak cukrovou pastou, která je mnohem šetrnější. '
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  content: 'Cukrova pasla, Roll on, Vosk, Brazilska depilace, Cukrova pasta, Cirepil, Samotrzny, Chloupky, Holit'
                }
            ],
        }
    }
  })