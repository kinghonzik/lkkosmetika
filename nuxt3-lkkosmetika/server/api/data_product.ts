/*export default async () => await $fetch('http://localhost/backend-lkkoksmetika/getproductByID.php?id=');*/

export default defineEventHandler(async (event) => {
    const query = await getQuery(event);
    return await $fetch('http://localhost/backend-lkkoksmetika/getproductByID.php?id=' + query.id);
  })