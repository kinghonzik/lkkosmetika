import { FetchError } from 'ohmyfetch'
/*import { createClient } from '@supabase/supabase-js'*/

export default defineEventHandler(async (event) => {
  // Ukazka jak osetrit chyby
  /*const url = 'https://google.com/404' // or: baseURL+'/company?bookingAppApiKey='+config.apiSecret

  try {
    return await $fetch(url)
  } catch (err) {
    throw createError({
      statusCode: 444,
      message: 'Oh no!',
      data: {
        statusCode: (err as FetchError).response?.status,
        responseBody: (err as FetchError).data,
      },
    })
  }*/


  const body = await readBody(event);
  /*const SUPABASE_KEY = 'key123'
  const SUPABASE_URL = 'url.supabase.co'
  const supabase = createClient(SUPABASE_URL, SUPABASE_KE*/

  return await $fetch('http://localhost/backend-lkkoksmetika/postConfig.php', {
      method: 'POST',
      body: JSON.stringify(body.config)
  });

})