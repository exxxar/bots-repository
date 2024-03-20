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
                         v-on:callback="reloadTable"
                         :inline-query="selectedInlineQuery">

        </InlineQueryForm>

        <InlineQueryTable
            :bot="bot"
            v-if="!load"
            v-on:select="selectInlineQuery"></InlineQueryTable>

        <div class="spinner-border"
             v-if="load"
             role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
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

    },
    methods:{
        selectInlineQuery(item){
            this.load = true

            this.$nextTick(()=>{
                this.selectedInlineQuery = item
                this.load = false
            })

        },
        reloadTable(){
            this.load = true
            this.$nextTick(()=>{
                this.load = false
            })
        }

    }
}
</script>
