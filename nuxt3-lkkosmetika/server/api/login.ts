export default defineEventHandler(async (event) => {
  const body = await readBody(event);
    return await $fetch('http://localhost/lkkosmetika/backend-lkkoksmetika/JWT/token-get.php', {
        method: 'POST',
        body: JSON.stringify(body.credentials)
    });

})