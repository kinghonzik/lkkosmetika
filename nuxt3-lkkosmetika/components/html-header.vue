<template>
    <section>
        <Head v-if="params">
            <Title>{{ params.title }}</Title>
            <Meta v-if="description" id="metaDescription" name="description" :content="description" />
            <Meta v-if="keywords" id="keywords" name="keywords" :content="keywords" />
        </Head>
    </section>
</template>

<script>
    
    export default {
        props: ["params"],
      data() {
        return {
        }
      },
      methods: {
      },
      computed: {
        description() {
          return this.params?.meta.find(itm => itm.name == 'description')?.content ?? null;
        },
        keywords() {
          return this.params?.meta.find(itm => itm.name == 'keywords')?.content ?? null;
        }
      },
      async mounted() {
        console.log('monted! - HtmlHeader')
        console.log(this.params);
      }
    }
  </script>