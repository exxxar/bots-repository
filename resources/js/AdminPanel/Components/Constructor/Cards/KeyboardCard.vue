<script setup>
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
</script>
<template>
    <div class="card">

        <div v-if="selectMode" class="card-header d-flex justify-content-between align-items-center">
          <div>
              <button
                  @click="selectCard"
                  :disabled="load"
                  type="button"
                  class="btn btn-outline-success mr-2"
              >
                  <i class="fa-solid fa-arrow-left"></i>
              </button>

              <button
                  data-bs-toggle="modal" :data-bs-target="'#open-construct-'+uuid"
                  :disabled="load"
                  type="button"
                  title="Редактировать клавиатуру"
                  class="btn btn-outline-success mr-2"
              >
                  <i class="fa-regular fa-pen-to-square"></i>
              </button>

              <button
                  @click="updateKeyboard"
                  type="button"
                  title="Обновить клавиатуру"
                  :disabled="load||!is_edited"
                  class="btn btn-outline-primary mr-2"
                  v-bind:class="{'have-change':is_edited}"
              >
                  <i class="fa-regular fa-floppy-disk"></i>
              </button>
          </div>

        </div>
        <div v-if="!selectMode" class="card-header d-flex justify-content-between align-items-center">

            <div>
                <strong class="mr-2">#{{keyboard.id}}</strong>
                <button
                    data-bs-toggle="modal" :data-bs-target="'#open-construct-'+uuid"
                    :disabled="load"
                    type="button"
                    title="Редактировать клавиатуру"
                    class="btn btn-outline-success mr-2"
                >
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button
                    @click="duplicateKeyboard"
                    type="button"
                    title="Дублировать клавиатуру"
                    :disabled="load"
                    class="btn btn-outline-primary mr-2"
                >
                    <i class="fa-regular fa-clone"></i>
                </button>
                <button
                    @click="updateKeyboard"
                    type="button"
                    title="Обновить клавиатуру"
                    :disabled="load||!is_edited"
                    class="btn btn-outline-primary mr-2"
                    v-bind:class="{'have-change':is_edited}"
                >
                    <i class="fa-regular fa-floppy-disk"></i>
                </button>
            </div>


            <button
                @click="removeKeyboard"
                type="button"
                :disabled="load"
                title="Удалить клавиатуру"
                class="btn btn-outline-danger mr-2"
            >
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row" v-if="!selectMode">

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-domain">Тип</label>
                        <select
                            :disabled="true"
                            v-model="keyboard.type"
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
                               v-model="keyboard.slug"
                               maxlength="255"
                               aria-describedby="bot-domain" required>
                    </div>
                </div>


            </div>

            <div class="row">
                <div class=" col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="row" v-for="(row, rowIndex) in keyboard.menu">
                                <div class="col" v-for="(col, colIndex) in row">

                                    <button
                                        type="button"
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

    <!-- Modal -->
    <div class="modal fade"
         :id="'open-construct-'+uuid" tabindex="-1" aria-labelledby="open-construct-label" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="open-construct-label">Визуальный редактор клавиатуры</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotMenuConstructor
                        v-if="keyboardForm"
                        v-on:save="saveKeyboard"
                        :edited-keyboard="keyboardForm"/>
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


export default {
    props: ["keyboard", "selectMode"],
    data: () => ({
        is_edited: false,
        load: false,
        keyboardForm: null,
    }),
    computed: {
        uuid() {
            return uuidv4();
        }
    },
    watch: {
        'keyboardForm': {
            handler(val) {
                this.is_edited = true
            },
            deep: true
        }
    },
    mounted() {
        const tmpKeyboard = this.keyboard

        this.keyboardForm = tmpKeyboard
        this.$nextTick(() => {
            this.is_edited = false
        })

    },
    methods: {
        removeKeyboard() {
            this.$store.dispatch("removeKeyboardTemplate", {
                templateId: this.keyboardForm.id
            }).then((resp) => {

                this.load = true
                this.$nextTick(() => {
                    this.load = false
                })

                this.$emit("callback", this.keyboardForm.id)
            }).catch(() => {
                this.$emit("callback", this.keyboardForm.id)
            })
        },

        saveKeyboard(keyboard) {
            this.keyboardForm.menu = keyboard
        },

        selectCard() {
            this.$emit("select", this.keyboardForm)
        },
        duplicateKeyboard(){

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

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Меню успешно продублировано!",
                    type: 'success'
                });

                this.load = true
                this.$nextTick(() => {
                    this.load = false

                })

                this.$emit("reload")
            })
        },
        updateKeyboard() {

            let data = new FormData();
            Object.keys(this.keyboardForm)
                .forEach(key => {
                    const item = this.keyboardForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            this.$store.dispatch("editKeyboardTemplate", {
                keyboardForm: data
            }).then((resp) => {

                this.load = true
                this.$nextTick(() => {
                    this.load = false
                    this.is_edited = false
                })


                this.$emit("callback")
            }).catch(() => {
                this.is_edited = true
            })
        },
    }
}
</script>
<style lang="scss">
.have-change {
    position: relative;

    &:after {
        content: '';
        width: 8px;
        height: 8px;
        background-color: red;
        border-radius: 50%;
        position: absolute;
        top: 2px;
        right: 2px;
    }
}
</style>
