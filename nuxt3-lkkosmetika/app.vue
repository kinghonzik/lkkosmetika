<template>
  <div>
    <template v-if="ready">
      <template v-if="route?.path.startsWith('/admin')">
        <template v-if="auth.isAuthenticated">
          <AdminMenu/>
          <div class="page-content-admin">
            <NuxtPage />
          </div>
        </template>
        <template v-else>
          <Menu />
          <div class="page-content-admin">
            <login />
          </div>
          <Footer />
        </template>
      </template>
      <template v-else>
        <Menu />
        <div class="page-content">
          <NuxtPage />
        </div>
        <Footer />
      </template>
    </template>
  </div>
</template>

<script>
export default defineComponent({

  data() {
    return {
      route: null,
      ready: false,
      auth: null,
    }
  },
  head() {
    return {
      script: [],
      link: [
        {
          rel: "icon",
          type: "image/x-icon",
          href: "assets/img/favicon.ico",
        },
      ],
    };
  },
  async mounted() {
    this.route = await useRoute();
    this.auth = await useAuth();

    // ziskani noveho tokenu pokud se expirace snizi pod pulku casu
    if (this.route?.path.startsWith('/admin') && this.auth.isAuthenticated) {
      if (this.auth.timeRemaining <= this.auth.expLength / 2) {
        try {
          await this.auth.getNewToken();
        } catch (exception) {
          this.auth.logout();
          alert('Vyskytl se problém s získáním autentizačního tokenu. Přihlašte se prosím znovu.')
          console.error(exception);
        }
      }
    }

    this.ready = true;
  }
});
</script>

<style scoped>
.page-content {
  max-width: 1200px;
  margin: auto;
  padding-left: 8px;
  padding-right: 8px;
}
.page-content-admin {
  padding-left: 10px;
  padding-right: 10px;
}
</style>
