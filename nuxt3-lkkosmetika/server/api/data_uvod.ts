export default defineEventHandler((event) => {
    return {
        header: {
            title: 'Kosmetika Česká Bělá  ',
            meta: [
                {
                  hid: `description`, /* HID je pro override kdyz mam dynamicky generovany starnky */
                  name: 'description',
                  content: 'KOSMETICKÉ SLUŽBY, BROW & LASH BAR, MEZOTHERAPIE, DEPILACE. Ráda Váš přivítám ve svém salonu  v České Bělé.'
                },
                {
                  hid: `keywords`,
                  name: 'keywords',
                  content: 'Kosmetické služby, Kosmetika, Brow & lash bar, Mezoterapie, Depilace, Uprava oboci, Mikrojehlicky, Česká Bělá, Salon'
                }
            ],
        },
        tiles: [
            {
                title: 'KOSMETICKÉ SLUŽBY',
                description: 'Pracuji s italskou farmaceutickou kosmetikou PhysioNatura, která využívá pro výrobu své kosmetiky extrakty z červených hroznů odrůd Negroamaro a Primitivo a extrakty z odrůdy oliv Croatia. Ve složení používá bezpečné konzervanty a neobsahuje parabeny a silikony.',
                link: '/kosmetika',
            },
            {
                title: 'PEDIKÚRA',
                description: 'Klasická mokrá pedikúra se stala absolutní špičkou v péči o chodidla a je stále oblíbenou metodou již desítky let. Začíná koupelí nohou v teplé lázni se změkčujícími přísadami. Během této doby dojde k uvolnění nečistot z nehtových lůžek a změkčení otlaků nohou. Součástí celé procedury je i peeling a relaxační masáž chodidel a lýtek.',
                link: '/pedikura',
            },
            {
                title: 'MEZOTHERAPIE',
                description: 'Jedná se o nejvyhledávanější neinvazivní metodu, pro dosažení mladšího vzhledu. Metoda účinně stimuluje tvorbu vlastního kolagenu a elastinu. Mikrojehličky zajistí několikanásobně vyšší vstřebání aktivních látek a výraznější efekt v porovnání s aplikací látek v podobě krémů.',
                link: '/mezoterapie',
            },
            {
                title: 'BROW & LASH BAR',
                description: 'Správná úprava obočí se stala velice významným trendem, přijďte do mého salónu vybrat správný tvar pomocí architektury obočí. Lash lifting je speciální úprava přirozených řas, které se natočí, opticky prodlouží, obarví a vyživí.',
                link: '/brow-lash-bar',
            },
            {
                title: 'DEPILACE',
                description: 'Nabízím všechny druhy depilací, dámské i pánské, depiluji jak samostržnými vosky, roll on vosky, tak cukrovou pastou, která je mnohem šetrnější. Na některé partie mám oblíbený vosk, na některé cukrovou pastu, ale vždy se domluvím s klientem, kterou metodu upřednostňuje.',
                link: '/depilace',
            },
        ]
    }
  })