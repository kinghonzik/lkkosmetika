export default defineEventHandler(async (event) => {
    const query = await getQuery(event);
    return await $fetch('http://localhost/backend-lkkoksmetika/getOrders.php?options=' + query.options);
  })