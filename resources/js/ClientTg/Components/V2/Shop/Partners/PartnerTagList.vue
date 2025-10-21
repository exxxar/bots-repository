<template>
    <div class="d-flex flex-wrap">

        <div v-for="item in items">
            <h6 class="fw-bold my-3">{{item.bot.title}}</h6>
            <span class="badge btn-primary m-1" v-for="category in item.categories">{{category.title}}</span>
        </div>

    </div>
</template>
<script>
export default {
    data(){
      return {
          items:[],
      }
    },
    mounted() {
        this.listOfPartnersCategories()
    },
    methods:{
        listOfPartnersCategories() {
            this.$store.dispatch("listOfPartnersCategories").then((resp) => {
                this.items = resp
            })

        },
    }
}
</script>
