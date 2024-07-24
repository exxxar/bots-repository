<script setup>
import KeyboardConstructor from "@/ClientTg/Components/V1/Admin/Keyboards/KeyboardConstructor.vue";
</script>
<template>

    <div v-if="selectMode" class="mb-2 px-1">
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
            class="btn  mr-2"
            v-bind:class="{'btn-outline-primary have-change':is_edited,'btn-outline-secondary':!is_edited}"
        >
            <i class="fa-regular fa-floppy-disk"></i>
        </button>
    </div>

    <div v-if="!selectMode" class="d-flex justify-content-between">

        <div>
            <strong class="mr-2">#{{ keyboard.id }}</strong>
            <button
                data-bs-toggle="modal"
                @click="editMode=!editMode"
                :disabled="load"
                type="button"
                title="Редактировать клавиатуру"
                class="btn btn-outline-success mr-2"
            >
                <i v-if="editMode" class="fa-solid fa-check"></i>
                <i v-else class="fa-regular fa-pen-to-square"></i>
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
                class="btn mr-2"
                v-bind:class="{'btn-outline-primary have-change':is_edited,'btn-outline-secondary':!is_edited}"
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


    <div v-if="!selectMode">

        <p class="mb-0">Тип клавиатуры:
            <strong v-if="keyboard.type==='reply'">Нижняя клавиатура</strong>
            <strong v-if="keyboard.type==='inline'">Встроенная клавиатура</strong>
        </p>

        <p class="mb-0">Мнемоническое имя: <strong>{{ keyboard.slug }}</strong></p>
    </div>

    <div class="mb-1 d-flex justify-content-between"
         v-if="!editMode"
         v-for="(row, rowIndex) in keyboard.menu">


        <div class="mb-1 px-1 w-100" v-for="(col, colIndex) in row">
            <button

                type="button"
                class="btn btn-outline-primary w-100 font-10 p-2">
                {{ col.text }}
            </button>
        </div>


    </div>

    <KeyboardConstructor
        v-if="keyboardForm&&editMode"
        v-on:save="saveKeyboard"
        :edited-keyboard="keyboardForm"/>

    <div class="divider divider-small my-3 bg-highlight "></div>
</template>
<script>

import {v4 as uuidv4} from "uuid";


export default {
    props: ["keyboard", "selectMode"],
    data: () => ({
        is_edited: false,
        editMode: false,
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

                this.$botNotification.success(
                    "Конструктор клавитатуры",
                    "Клавиатура успешно удалена!",
                );

                this.$emit("callback", this.keyboardForm.id)
            }).catch(() => {
                this.$emit("callback", this.keyboardForm.id)

                this.$botNotification.warning(
                    "Конструктор клавитатуры",
                    "Ошибка удаления клавиатуры",
                );
            })
        },

        saveKeyboard(keyboard) {
            this.keyboardForm.menu = keyboard

            this.$botNotification.notification(
                "Конструктор клавитатуры",
                "Клавиатура успешно выбрана!",
            );
        },

        selectCard() {
            this.$emit("select", this.keyboardForm)
        },
        duplicateKeyboard() {

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

                this.$botNotification.success(
                    "Конструктор ботов",
                    "Меню успешно продублировано!",
                );

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

                this.$botNotification.success(
                    "Конструктор клавитатуры",
                    "Клавиатура успешно сохранена!",
                );

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
