<script setup>
import KeyboardConstructor from "@/ClientTg/Components/Admin/Keyboards/KeyboardConstructor.vue";
import KeyboardCard from "@/ClientTg/Components/Admin/Keyboards/KeyboardCard.vue";
</script>
<template>

    <div v-if="!selectMode">

        <h6>Форма создания шаблона клавиатуры</h6>

        <form v-on:submit.prevent="submitKeyboard">

            <div class="mb-3">
                <label class="form-label" id="bot-domain">Тип</label>
                <select
                    v-model="keyboardForm.type"
                    class="form-control">
                    <option value="reply">Нижняя клавиатура</option>
                    <option value="inline">Встроенная клавиатура</option>
                </select>
            </div>


            <div class="mb-3">
                <label class="form-label d-flex justify-content-between" id="bot-domain">
                    <span>Мнемоническое имя</span>
                    <a
                        @click="generateSlug"
                        href="javascript:void(0)"><i class="fa-solid fa-arrows-rotate"></i></a>
                </label>
                <input type="text" class="form-control"
                       placeholder="Мнемоническое имя"
                       aria-label="Мнемоническое имя"
                       v-model="keyboardForm.slug"
                       maxlength="255"
                       aria-describedby="bot-domain" required>
            </div>


            <div class="col-12">
                <KeyboardConstructor
                    v-if="!load"
                    v-on:save="changeKeyboardFormMenu"
                    :edited-keyboard="keyboardForm"/>
            </div>

            <button class="btn btn-m btn-full mb-0 rounded-xs text-uppercase font-900 shadow-s bg-green1-dark w-100">
                Добавить новый шаблон клавиатуры
            </button>
        </form>
        <div class="divider divider-small my-3 bg-highlight "></div>
    </div>

    <p class="mb-1" v-if="keyboards">В списке клавиатур <span class="badge bg-warning">{{ filteredKeyboard.length }}  ед.</span>
    </p>

    <div class="mb-3"
         v-if="keyboards"
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
                <p>Удаленная клавиатура #{{ keyboard.id }}</p>
            </div>
        </div>
    </div>

    <div class="mb-3" v-if="filteredKeyboard.length===0">
        <div class="alert alert-warning" role="alert">
            <p>Список шаблонов клавиатур пуст!</p>
        </div>

    </div>


</template>
<script>
import {v4 as uuidv4} from "uuid";
import {mapGetters} from "vuex";

export default {
    props: ["selectMode", "type"],
    data() {
        return {
            keyboards: [],
            load: false,
            editedKeyboard: null,
            selectMenuIndex: null,
            keyboardForm: {
                type: 'reply',
                slug: null,
                menu: [],
            },

        }
    },
    computed: {
        filteredKeyboard() {
            if (!this.type)
                return this.keyboards

            return this.keyboards.filter(item => item.type == this.type)
        }
    },
    mounted() {
        this.loadMenusByBotTemplate()

        this.generateSlug();
    },
    methods: {
        selectCard(keyboardCard) {
            this.$emit("select", keyboardCard)
        },
        keyboardCallbackAction(templateId) {
            let keyboard = this.keyboards.find(item => item.id === templateId)

            keyboard.deleted_at = new Date();

            this.loadMenusByBotTemplate();
        },
        loadMenusByBotTemplate() {
            this.$store.dispatch("loadBotKeyboards").then((resp) => {

                this.$nextTick(() => {
                    this.keyboards = resp.data
                })
            })
        },

        submitKeyboard() {


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
