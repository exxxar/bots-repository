<script setup>
import BotMenuConstructor from "@/Components/Constructor/BotMenuConstructor.vue";
import MenuFunctionSwitcher from "@/Components/Constructor/MenuFunctionSwitcher.vue";
import BotSlugList from "@/Components/Constructor/BotSlugList.vue";
</script>
<template>
    <div class="row mb-2">
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


        <div class="col-12 mb-3"
             v-if="keyboards"
             v-for="(keyboard, index) in keyboards">
            <div class="card">
                <div class="card-header">
                    <button
                        @click="removeKeyboard(index)"
                        type="button"
                        class="btn btn-outline-danger mr-2"
                    >
                        Удалить
                    </button>
                    <button
                        data-bs-toggle="modal" data-bs-target="#open-construct"
                        @click="editKeyboard(keyboard, index)"
                        type="button"
                        class="btn btn-outline-success ml-2"
                    >
                        Конструктор
                    </button>

                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label" id="bot-domain">Тип</label>
                                <select
                                    :disabled="true"
                                    v-model="keyboards[index].type"
                                    class="form-control">
                                    <option value="reply">Нижняя клавиатура</option>
                                    <option value="inline">Встроенная клавиатура</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label" id="bot-domain">Мнемоническое имя</label>
                                <input type="text" class="form-control"
                                       placeholder="Мнемоническое имя"
                                       :disabled="true"
                                       aria-label="Мнемоническое имя"
                                       v-model="keyboards[index].slug"
                                       maxlength="255"
                                       aria-describedby="bot-domain" required>
                            </div>
                        </div>

                        <div class=" col-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row" v-for="(row, rowIndex) in keyboards[index].menu">
                                        <div class="col" v-for="(col, colIndex) in row">

                                            <button
                                                type="button"

                                                @click="editBtn(index, rowIndex,colIndex)"
                                                class="btn btn-outline-primary w-100 mb-2">
                                                {{ col.text }}
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade"
         id="open-construct" tabindex="-1" aria-labelledby="open-construct-label" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="open-construct-label">Визуальный редактор клавиатуры</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotMenuConstructor v-if="editedKeyboard&&!load"
                                        v-on:save="saveKeyboard"
                                        :edited-keyboard="editedKeyboard"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>


</template>
<script>
import {v4 as uuidv4} from "uuid";
import {mapGetters} from "vuex";

export default {
    props: ["keyboards"],

    data() {
        return {
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
            editedButton: {
                oldTextVal: null,
                newTextVal: null,
                keyboardIndex: null,
                rowIndex: null,
                colIndex: null,

            }
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
    },
    mounted() {
        this.loadCurrentBot()
    },
    methods: {
        loadCurrentBot(bot = null) {
            this.$store.dispatch("updateCurrentBot", {
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

                this.$emit("update")
            })
        },
        generateSlug() {
            this.keyboardForm.slug = uuidv4();
        },
        changeKeyboardFormMenu(keyboard) {
            this.keyboardForm.menu = keyboard
        },
        saveKeyboard(keyboard) {
            this.keyboards[this.selectMenuIndex].menu = keyboard
        },
        editKeyboard(keyboard, index) {
            this.load = true
            this.$nextTick(() => {
                this.selectMenuIndex = index
                this.editedKeyboard = keyboard
                this.load = false
            })

        },
        removeKeyboard(index) {
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


            /*  Object
                  .keys(this.keyboards[keyboardIndex].menu[rowIndex][colIndex])
                  .forEach(item=>{
                      console.log("item", this.keyboards[keyboardIndex].menu[rowIndex][colIndex][item])
                  })*/
            //console.log()
        },

    }
}
</script>
