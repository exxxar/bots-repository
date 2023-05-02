<script setup>
import BotMenuConstructor from "@/Components/Constructor/BotMenuConstructor.vue";

import BotSlugList from "@/Components/Constructor/BotSlugList.vue";
</script>
<template>
    <div class="row">

        <div class="col-12 mb-3">
            <div class="alert alert-warning" role="alert">
                Если меняете текст в "Нижней клавиатуре", то найдите и поменяйте его также в разделе "Команды
                бота"
            </div>
        </div>
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
                                                data-bs-toggle="modal" data-bs-target="#open-add-script"
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


export default {
    props: ["keyboards"],

    data() {
        return {
            load: false,
            editedKeyboard: null,
            selectMenuIndex: null,
            editedButton: {
                oldTextVal: null,
                newTextVal: null,
                keyboardIndex: null,
                rowIndex: null,
                colIndex: null,

            }
        }
    },

    methods: {
        saveKeyboard(keyboard) {
            this.keyboards[this.selectMenuIndex].menu = keyboard

            // this.selectMenuIndex = null
            // this.editedKeyboard = null


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
