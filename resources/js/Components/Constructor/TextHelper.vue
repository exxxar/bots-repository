<template>
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="fa-solid fa-spell-check"></i>
        </button>


        <div class="dropdown-menu item-with-text cursor-pointer   text-muted p-2">

                <input type="search" class="form-control" id="search-description-text"
                       v-model="search"
                       placeholder="Введите текст для поиска">


            <div class="dropdown-divider"></div>
            <p v-for="(item, index) in filteredDescription"
               class="p-1 text-left w-100"
               @click="select( item.text )">{{ item.text }}</p>
        </div>
    </div>
</template>
<script>
export default {
    props: ["param"],
    data() {
        return {
            search:null,
            descriptions: [],
        }
    },
    computed:{
      filteredDescription(){
          if (!this.search)
              return this.descriptions
          return this.descriptions.filter(item=>item.text.indexOf(this.search)!==-1)
      }
    },
    mounted() {
        this.loadDescription();
    },
    methods: {
        select(text) {
            this.$emit("callback", {
                param: this.param,
                text: text
            })
        },
        loadDescription() {
            this.$store.dispatch("loadDescription").then((resp) => {
                this.descriptions = resp.data

            })
        },
    }
}
</script>
<style>
.item-with-text {
    max-height: 300px;
    max-width: 500px;
    min-width: 500px;
    overflow-y: auto;
}
</style>
