<template>
    <form v-on:submit.prevent="submit">
        <div class="alert alert-light">
            <strong class="fw-bold">Внимание!</strong> Если страница с таким название уже есть, то повторно она не будет создана!
        </div>

        <template v-for="(page, index) in pages">
            <div class="input-group mb-3">

                <div class="form-floating">
                    <input
                        v-model="pages[index]"
                        type="text" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                    <label for="floatingInput">Название страницы #{{index+1}}</label>
                </div>
                <a
                   @click="removePage(index)"
                    href="javascript:void(0)" class="input-group-text" id="basic-addon1">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </div>

        </template>

        <button
            type="button"
            @click="addPage"
            class="btn btn-outline-primary p-3 w-100 mb-2">Добавить еще страницу</button>
        <button
            :disabled="pages.length===0"
            class="btn btn-primary p-3 w-100">Сохранить страницы</button>
    </form>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data(){
        return {
            bot:null,
            pages:[]
        }
    },
    computed: {
        ...mapGetters([ 'getCurrentBot']),
    },
    mounted() {
        this.loadCurrentBot()
    },
    methods:{
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot,
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        submit(){
            this.$store.dispatch("addMultiPages",{
                bot_id: this.bot.id,
                pages: this.pages
            }).then(()=>{
                this.pages = []

                this.$notify({
                    title: "Конструктор страниц",
                    text: "Вы успешно добавили страницы!",
                    type: 'success'
                });

                this.$emit("callback")
            }).catch(()=>{
                this.$notify({
                    title: "Конструктор страниц",
                    text: "Ошибка добавления страниц",
                    type: 'error'
                });
            })
        },
        addPage(){
          this.pages.push("")
        },
        removePage(index){
            this.pages.splice(index, 1)
        }
    }
}
</script>
