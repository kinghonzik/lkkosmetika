export default defineEventHandler(async (event) => {
  const body = await readBody(event);

  return await $fetch('http://localhost/backend-lkkoksmetika/delProduct.php', {
      method: 'DELETE',
      body: JSON.stringify(body.product)
      
  });

})