<script setup>
import BotDialogCommandForm from "@/ClientTg/Components/V1/Admin/Dialogs/BotDialogCommandForm.vue";
</script>
<template>

    <div class="btn w-100 text-left border-blue2-light" type="button">
        <p @click="showMenu=!showMenu" class="w-100 mb-0">
            <i v-if="showMenu" class="fa-solid fa-chevron-up"></i>
            <i v-else class="fa-solid fa-chevron-down"></i>
            {{ item.pre_text || 'Нет команды' }}
        </p>


        <BotDialogCommandForm
            v-if="showMenu"
            :bot="bot"
            :item="item"/>

    </div>






</template>
<script>
export default {
    props: ["item", "bot"],
    data() {
        return {
            showMenu:false,
        }
    },
   methods:{

        selectDialog(){
            this.$emit("select", this.item)
        },



    }
}
</script>
<style>
.dialog-command-card {
    border-radius: 5px;
    border: 1px green solid;
    min-height: 150px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
}
</style>
