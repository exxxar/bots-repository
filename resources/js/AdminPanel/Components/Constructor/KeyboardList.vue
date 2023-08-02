<script setup>
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
import KeyboardCard from "@/AdminPanel/Components/Constructor/Cards/KeyboardCard.vue";
</script>
<template>
    <div class="row mb-2" v-if="!selectMode">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6>Форма создания шаблона клавиатуры</h6>
                </div>
                <div class="card-body">
                    <form v-on:submit.prevent="submitKeyboard" class="row">

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label" id="bot-domain">Тип</label>
                                <select
                                    v-model="keyboardForm.type"
                                    class="form-control">
                                    <option value="reply">Нижняя клавиатура</option>
                                    <option value="inline">Встроенная клавиатура</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label d-flex justify-content-between" id="bot-domain">
                                    <span>Мнемоническое имя</span>
                                    <a
                                        @click="generateSlug"
                                        href="#generate"><i class="fa-solid fa-arrows-rotate"></i></a>
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="Мнемоническое имя"
                                       aria-label="Мнемоническое имя"
                                       v-model="keyboardForm.slug"
                                       maxlength="255"
                                       aria-describedby="bot-domain" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <BotMenuConstructor
                                v-if="!load"
                                v-on:save="changeKeyboardFormMenu"
                                :edited-keyboard="keyboardForm"/>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-outline-success w-100 p-3">
                                Добавить новый шаблон клавиатуры
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div
            v-if="keyboards&&bot"
            class="col-12">
            <p>В списке клавиатур <span class="badge bg-warning">{{filteredKeyboard.length}}  ед.</span></p>
        </div>
        <div class="col-12 mb-3"
             v-if="keyboards&&bot"
             v-for="(keyboard, index) in filteredKeyboard">
            <KeyboardCard
                :select-mode="selectMode"
                v-if="!keyboard.deleted_at"
                v-on:select="selectCard"
                v-on:reload="loadMenusByBotTemplate"
                v-on:callback="keyboardCallbackAction"
                :keyboard="keyboard"/>
            <div class="card" v-else>
                <div class="card-body">
                    <p>Удаленная клавиатура #{{keyboard.id}}</p>
                </div>
            </div>
        </div>

        <div class="col-12 mb-3" v-if="filteredKeyboard.length===0">
            <div class="alert alert-warning" role="alert">
                <p>Список шаблонов клавиатур пуст!</p>
            </div>

        </div>
    </div>



</template>
<script>
import {v4 as uuidv4} from "uuid";
import {mapGetters} from "vuex";

export default {
    props:["selectMode", "type"],
    data() {
        return {
            keyboards:[],
            load: false,
            bot: null,
            editedKeyboard: null,
            selectMenuIndex: null,
            keyboardForm: {
                type: 'reply',
                bot_id: null,
                slug: null,
                menu: [],
            },
          /*  editedButton: {
                oldTextVal: null,
                newTextVal: null,
                keyboardIndex: null,
                rowIndex: null,
                colIndex: null,

            }*/
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
        filteredKeyboard(){
            if (!this.type)
                return this.keyboards

            return this.keyboards.filter(item=>item.type==this.type)
        }
    },
    mounted() {
        this.loadCurrentBot().then(()=>{
            this.loadMenusByBotTemplate()

            this.generateSlug();
        })
    },
    methods: {
        selectCard(keyboardCard){
          this.$emit("select", keyboardCard)
        },
        keyboardCallbackAction(templateId){
            let keyboard = this.keyboards.find(item=>item.id===templateId)

            keyboard.deleted_at = new Date();

            this.loadMenusByBotTemplate();
        },
        loadMenusByBotTemplate() {
            this.$store.dispatch("loadBotKeyboards", {
                botId: this.bot.id
            }).then((resp) => {
                this.keyboards = resp
            })
        },
        loadCurrentBot(bot = null) {
           return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        submitKeyboard() {
            this.keyboardForm.bot_id = this.bot.id

            let data = new FormData();
            Object.keys(this.keyboardForm)
                .forEach(key => {
                    const item = this.keyboardForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("createKeyboardTemplate", {
                keyboardForm: data
            }).then((resp) => {

                this.keyboardForm = {
                    type: 'reply',
                    bot_id: null,
                    slug: null,
                    menu: [],
                }

                this.load = true
                this.$nextTick(() => {
                    this.load = false
                })

                this.loadMenusByBotTemplate()

            })
        },
        generateSlug() {
            this.keyboardForm.slug = uuidv4();
        },
        changeKeyboardFormMenu(keyboard) {
            this.keyboardForm.menu = keyboard
        },
      /*  saveKeyboard(keyboard) {
            this.keyboards[this.selectMenuIndex].menu = keyboard
        },*/
      /*  editKeyboard(keyboard, index) {
            this.load = true
            this.$nextTick(() => {
                this.selectMenuIndex = index
                this.editedKeyboard = keyboard
                this.load = false
            })

        },*/
      /*  removeKeyboard(index) {
            this.$emit("remove", index)
        },
        editBtn(keyboardIndex, rowIndex, colIndex) {
            this.editedButton.oldTextVal = this.keyboards[keyboardIndex].menu[rowIndex][colIndex].text
            this.editedButton.colIndex = colIndex
            this.editedButton.rowIndex = rowIndex
            this.editedButton.keyboardIndex = keyboardIndex


            this.$emit("edit", {
                command: this.editedButton.oldTextVal
            })


            /!*  Object
                  .keys(this.keyboards[keyboardIndex].menu[rowIndex][colIndex])
                  .forEach(item=>{
                      console.log("item", this.keyboards[keyboardIndex].menu[rowIndex][colIndex][item])
                  })*!/
            //console.log()
        },*/

    }
}
</script>
