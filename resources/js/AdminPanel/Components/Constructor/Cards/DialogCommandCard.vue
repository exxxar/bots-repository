<script setup>
import BotDialogCommandForm from "@/AdminPanel/Components/Constructor/Dialogs/BotDialogCommandForm.vue";
</script>
<template>
    <div

        class="p-2 dialog-command-card">
        <p>
            <span class="badge bg-success">#{{ item.id }}</span>
            <span class="ml-1 mr-1"><i class="fa-solid fa-arrow-right"></i></span>
            <span v-if="item.next_bot_dialog_command_id"
                  class="badge bg-primary">#{{ item.next_bot_dialog_command_id || '-' }}</span>
            <span v-else>не связан</span>
        </p>

        <h6>Текст диалога:</h6>
        <p>{{ item.pre_text || '-' }}</p>
        <div class="w-100 d-flex justify-between" v-if="!simple">

            <div class="dropdown">
                <button
                    :disabled="loading"
                    class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a
                        @click="selectDialog"
                        title="Выбрать диалог"
                        class="dropdown-item cursor-pointer"><i class="fa-solid fa-arrow-left mr-1"></i> Выбрать диалог </a></li>
                    <hr>
                    <li><a
                        data-bs-toggle="modal" :data-bs-target="'#dialog-command-modal-'+item.id"
                        title="Редактирование параметров карточки"
                        class="dropdown-item cursor-pointer"> <i class="fa-solid fa-sliders mr-1"></i> Настройка диалога </a></li>
                    <li><a
                        @click="linkEvent"
                        title="Диалог связывания с другой командой"
                        class="dropdown-item cursor-pointer"> <i class="fa-solid fa-arrows-turn-to-dots mr-1"></i> Редактирование связей </a></li>
                    <li><a
                        @click="changeGroup"
                        title="Диалог смены группы команды"
                        class="dropdown-item cursor-pointer">  <i class="fa-solid fa-arrows-up-down mr-1"></i> Смена диалоговой группы </a></li>
                    <li><a
                        title="Дублирование команды"
                        @click="duplicate"
                        class="dropdown-item cursor-pointer"> <i class="fa-solid fa-clone mr-1"></i> Дублирование диалога </a></li>
                    <li><a
                        @click="unlinkCommand"
                        title="Убирает связь команды с другой командой по цепочке"
                        class="dropdown-item cursor-pointer"> <i class="fa-solid fa-link-slash mr-1"></i> Удаление связей диалога </a></li>

                    <li><a
                        @click="removeCommand"
                        title="Удаление команды"
                        class="dropdown-item cursor-pointer"> <i class="fa-solid fa-trash-can mr-1"></i> Удаление диалога </a></li>
                </ul>
            </div>


        </div>

    </div>


    <div class="modal fade" :id="'dialog-command-modal-'+item.id"
         data-bs-backdrop="static"
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        <span class="badge bg-success">#{{ item.id }}</span>
                        <span class="ml-1 mr-1"><i class="fa-solid fa-arrow-right"></i></span>
                        <span v-if="item.next_bot_dialog_command_id"
                              class="badge bg-primary">#{{ item.next_bot_dialog_command_id || '-' }}</span>
                        <span v-else>не связан</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotDialogCommandForm
                        :bot="bot"
                        :item="item"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
export default {
    props: ["item", "simple","bot"],
    data() {
        return {
            loading: false
        }
    },
   methods:{
        unlinkCommand(){

            this.loading = true

            this.$store.dispatch("unlinkDialogCommand", {
                dataObject: {
                    dialogCommandId:this.item.id
                }
            }).then((response) => {
                this.loading = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Диалоговая команда успешно продублирована!",
                    type: 'success'
                });

                this.$emit("callback")
            }).catch(err => {
                this.loading = false
            })
        },
        removeCommand(){
            this.loading = true

            this.$store.dispatch("removeDialogCommand", {
                dataObject: {
                    dialogCommandId:this.item.id
                }
            }).then((response) => {
                this.loading = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Диалоговая команда успешно удалена!",
                    type: 'success'
                });

                this.$emit("callback")
            }).catch(err => {
                this.loading = false
            })
        },
        selectDialog(){
            this.$emit("select", this.item)
        },

        duplicate(){
            this.loading = true

            this.$store.dispatch("duplicateDialogCommand", {
                dataObject: {
                    dialogCommandId:this.item.id
                }
            }).then((response) => {
                this.loading = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Диалоговая команда успешно продублирована!",
                    type: 'success'
                });

                this.$emit("callback")
            }).catch(err => {
                this.loading = false
            })
        },
        changeGroup(){
            this.$emit("swap", {
                command_id: this.item.id,
                group_id: this.item.bot_dialog_group_id,
            })
        },
        linkEvent(){
            this.$emit("link", {
                id: this.item.id,
                group_id: this.item.bot_dialog_group_id,
                current_next_id: this.item.next_bot_dialog_command_id
            })
        }
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
