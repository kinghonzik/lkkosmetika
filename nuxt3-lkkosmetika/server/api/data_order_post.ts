export default defineEventHandler(async (event) => {
  const body = await readBody(event);

  return await $fetch('http://localhost/backend-lkkoksmetika/postOrder.php', {
      method: 'POST',
      body: JSON.stringify(body.order)
  });

})