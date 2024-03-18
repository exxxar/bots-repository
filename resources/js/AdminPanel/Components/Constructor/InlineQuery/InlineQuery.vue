<script setup>
import InlineQueryForm from "@/AdminPanel/Components/Constructor/InlineQuery/InlineQueryForm.vue";
import InlineQueryTable from "@/AdminPanel/Components/Constructor/InlineQuery/InlineQueryTable.vue";
</script>
<template>

    <div class="my-2">
        <div class="row mb-2" v-if="selectedInlineQuery!=null">
            <div class="col-12">
               <button
                   type="button"
                   @click="selectInlineQuery(null)"
                   class="btn btn-primary"> Добавить новое встроенное меню</button>
            </div>
        </div>
        <InlineQueryForm :bot="bot"
                         v-if="!load"
                         :inline-query="selectedInlineQuery">

        </InlineQueryForm>

        <InlineQueryTable
            :bot="bot"
            v-on:select="selectInlineQuery"></InlineQueryTable>

    </div>

</template>
<script>
export default {
    props:["bot"],
    data(){
        return {
            load:false,
            selectedInlineQuery:null
        }
    },
    mounted() {
        this.loadInlineQueries()
    },
    methods:{
        selectInlineQuery(item){
            this.load = true

            this.$nextTick(()=>{
                this.selectedInlineQuery = item
                this.load = false
            })

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
