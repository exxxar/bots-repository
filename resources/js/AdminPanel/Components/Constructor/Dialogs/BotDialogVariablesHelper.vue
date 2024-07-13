<template>
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="fa-solid fa-spell-check"></i>
        </button>

        <div
            class="dropdown-menu item-with-text cursor-pointer text-muted p-2">

            <input type="search" class="form-control" id="search-description-text"
                   v-model="search"
                   placeholder="Введите текст для поиска">


            <div class="dropdown-divider"></div>
            <p v-for="(item, index) in filteredVariable"
               class="p-1 text-left w-100 mb-1"
               @click="select( item )">{{ item }}</p>
        </div>
    </div>
</template>
<script>
export default {
    props: ["bot"],
    data() {
        return {
            search: null,
            variables: [],
        }
    },
    computed: {
        filteredVariable() {
            if (!this.search)
                return this.variables
            return this.variables.filter(item => item.text.indexOf(this.search) !== -1)
        }
    },
    mounted() {
        this.loadVariables();
    },
    methods: {
        select(text) {
            this.$emit("callback", text)
        },
        loadVariables() {
            this.$store.dispatch("loadDialogVariables", {
                bot_id: this.bot.id
            }).then((resp) => {
                this.variables = resp.data

            })
        },
    }
}
</script>
<style lang="scss">
.item-with-text {
    max-height: 300px;
    max-width: 500px;
    min-width: 500px !important;
    overflow-y: auto;
}
</style>
