<script setup>
import BotDialogCommandForm from "@/ClientTg/Components/V2/Admin/Dialogs/BotDialogCommandForm.vue";
</script>
<template>

    <div
        data-bs-toggle="modal" :data-bs-target="'#open-dialog-editor-'+item.id"
        class="btn d-flex justify-content-start align-items-start" type="button">
        <span class="badge bg-primary mr-2">#{{item.id}}</span>
        <p class="mb-0" style="line-height:100%; font-size:10px; text-align:left;"> {{ item.pre_text || 'Нет команды' }}</p>
    </div>

    <!-- Modal -->
    <div class="modal fade" :id="'open-dialog-editor-'+item.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotDialogCommandForm
                        :bot="bot"
                        :item="item"/>
                </div>

            </div>
        </div>
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
