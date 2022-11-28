<template>
    <section>
        <Head v-if="params">
            <Title>{{ params.title }}</Title>
            <Meta v-if="description" name="description" :content="description" />
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
          var descItm = this.params?.meta.find(itm => itm.name == 'description');
          if (!descItm)
            return null;
            
          return descItm.content;
        }
      },
      async mounted() {
        console.log('monted! - HtmlHeader')
        console.log(this.params);
      }
    }
  </script>