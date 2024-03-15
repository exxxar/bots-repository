<script setup>
import InlineQueryForm from "@/AdminPanel/Components/Constructor/InlineQuery/InlineQueryForm.vue";
import InlineQueryTable from "@/AdminPanel/Components/Constructor/InlineQuery/InlineQueryTable.vue";
</script>
<template>
    <h1>Inline Query</h1>
    <InlineQueryForm :bot="bot"
                     :inline-query="selectedInlineQuery">

    </InlineQueryForm>

    <InlineQueryTable v-on:select="selectInlineQuery"></InlineQueryTable>

</template>
<script>
export default {
    props:["bot"],
    data(){
        return {
            selectedInlineQuery:null
        }
    },
    mounted() {
        this.loadInlineQueries()
    },
    methods:{
        selectInlineQuery(item){
          this.selectedInlineQuery = item
        },
        loadInlineQueries(){
            this.$store.dispatch("loadInlineQueries",{
                dataObject: {
                    bot_id: this.bot.id || null,
                }
            }).then(resp=>{
                this.categories = resp
            })
        },
    }
}
</script>
