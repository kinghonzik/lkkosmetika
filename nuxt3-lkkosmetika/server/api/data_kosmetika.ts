export default defineEventHandler((event) => {
    return {
        header: {
            title: 'Kosmetické ošetření - základní, aknózní, liftingové ',
            meta: [
                {
                  hid: `description`, /* HID je pro override kdyz mam dynamicky generovany starnky */
                  name: 'description',
                  content: 'Pracuji s italskou farmaceutickou kosmetikou PhysioNatura, která využívá pro výrobu své kosmetiky extrakty z červených hroznů odrůd Negroamaro a Primitivo a extrakty z odrůdy oliv Croatia.'
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  content: 'Kosmeticke osetreni, Lifting, Liftingove osetreni, Chemicky peeling, Akne, Osetreni aknozni pleti, Rasy, Physionatura, Hystorie kosmetiky'
                }
            ],
        }
    }
  })