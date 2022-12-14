export default defineEventHandler((event) => {
    return {
        header: {
            title: 'Pedikúra',
            meta: [
                {
                  hid: `description`, /* HID je pro override kdyz mam dynamicky generovany starnky */
                  name: 'description',
                  content: 'Úprava nehtů, odstranění odumřelé kůže na chodidlech a masáži, která uleví od bolesti a uvolní napětí v končetinách. Peeling odstraní suchou kůži v oblasti bérce a zanechá tak vaše nohy hydratované a omlazené.'
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  content: 'kosmeticke sluzby, brow and lash bar, goldpen, mezoteraphy, depilace, akne, kosmeticke osetreni, PHYSIONATURA, chemicky peeling, lifting, dermapero, bio hena, lash lifting bomb, oboci, roll on vosk, cirepil, cukrova pasta'
                }
            ],
        }
    }
  })