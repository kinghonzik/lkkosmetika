export default defineEventHandler(async (event) => {
  const body = await readBody(event);

  return await $fetch('http://localhost/lkkosmetika/backend-lkkoksmetika/postProduct.php', {
      method: 'POST',
      body: {order: JSON.stringify(body.product) }
      
  });

})