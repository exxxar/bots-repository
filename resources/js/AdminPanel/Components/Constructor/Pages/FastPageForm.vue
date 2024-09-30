<script setup>
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
</script>
<template>

    <div class="d-flex justify-content-between btn-group mb-2">
        <button
            @click="variant=0"
            v-bind:class="{'btn-primary':variant===0,'btn-outline-primary':variant!==0}"
            class="btn w-100">
            Через страницы
        </button>
        <button
            @click="variant=1"
            v-bind:class="{'btn-primary':variant===1,'btn-outline-primary':variant!==1}"
            class="btn w-100">
            Через клавиатуру
        </button>
    </div>

    <form
        v-show="variant===0"
        v-on:submit.prevent="submit">
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

    <form
        v-show="variant===1"
        v-on:submit.prevent="submit2">

        <div class="alert alert-light">
            <strong class="fw-bold">Внимание!</strong> Если страница с таким название уже есть, то повторно она не будет создана!
            Каждая создаваемая вами страница получит текущий вариант меню.
        </div>

        <BotMenuConstructor
            :type="'reply'"
            v-on:save-settings="saveSettings"
            v-model="keyboard"/>

        <button
            :disabled="keyboard==null||(keyboard||[]).length===0"
            class="btn btn-primary p-3 w-100">Сохранить страницы</button>
    </form>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data(){
        return {
            variant:0,
            bot:null,
            keyboard:null,
            settings:null,
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
        submit2(){
            this.$store.dispatch("addMultiPagesByKeyboard",{
                bot_id: this.bot.id,
                keyboard: this.keyboard,
                settings: this.settings
            }).then(()=>{
                this.keyboard = null
                this.settings = null

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
        saveSettings(e){
            this.settings = e
           // console.log("settings", e)
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
