<script setup>
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
import KeyboardList from "@/AdminPanel/Components/Constructor/KeyboardList.vue";
</script>
<template>
    <form v-on:submit.prevent="submitForm" class="mb-2">


        <div class="row">
            <div class="col-12 mb-3 d-flex align-items-center">
                <div class="dropdown mr-2">
                    <button
                        class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                            <span v-if="itemForm.type">
                               {{ preparedTypeTile.title }}
                            </span>
                        <span v-else>Тип поля</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li
                            @click="itemForm.type = type.value"
                            v-for="type in types"><a class="dropdown-item" href="#">
                            {{ type.title || 'Не указан' }}
                        </a></li>
                    </ul>

                </div>
                <p class="mb-0"> - тип пункта меню</p>
            </div>
            <div class="col-12 col-md-12 mb-3">
                <label class="form-label" id="service-title">
                    <Popper
                        content="Заголовок">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Заголовок

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="text" class="form-control"
                       placeholder="Заголовок"
                       aria-label="Заголовок"
                       v-model="itemForm.title"
                       aria-describedby="title" required>
            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="service-description">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Краткая информация о команде
                            </div>
                        </template>
                    </Popper>
                    Описание команду
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;"
                           v-if="itemForm.description">
                        Длина текста {{ itemForm.description.length }} / 255</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Описание команды"
                          aria-label="Описание команды"
                          maxlength="255"
                          v-model="itemForm.description"
                          aria-describedby="service-description" required>
                    </textarea>

            </div>
            <div class="col-12 mb-3">


                <label class="form-label " id="service-input_message_content">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Текст, который будет в сообщении после выбора данного пункта меню
                            </div>
                        </template>
                    </Popper>
                    Текст после выбора
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;"
                           v-if="itemForm.input_message_content">
                        Длина текста {{ itemForm.input_message_content.length }} / 255</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Текст поста"
                          aria-label="Текст поста"
                          maxlength="255"
                          v-model="itemForm.input_message_content"
                          aria-describedby="service-input_message_content" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_keyboard"
                           type="checkbox" id="need_keyboard">
                    <label class="form-check-label" for="need_keyboard">
                        Нужна клавиатура к тексту
                    </label>
                </div>
            </div>

            <div class="col-12 mb-3" v-if="need_keyboard">


                <div class="card">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input"
                                   v-model="need_show_inline_template_selector"
                                   type="checkbox" id="need_show_inline_template_selector">
                            <label class="form-check-label" for="need_show_inline_template_selector">
                                Выбрать клавиатуру из шаблона
                            </label>
                        </div>

                        <KeyboardList
                            class="mb-2"
                            :type="'inline'"
                            v-if="need_show_inline_template_selector"
                            v-on:select="selectInlineKeyboard"
                            :select-mode="true"/>

                        <BotMenuConstructor
                            :type="'inline'"
                            v-else
                            v-on:save="saveInlineKeyboard"
                            :edited-keyboard="itemForm.inline_keyboard"/>
                    </div>
                </div>

            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_custom_settings"
                           type="checkbox" id="need_custom_settings">
                    <label class="form-check-label" for="need_custom_settings">
                        Нужны дополнительные настройки
                    </label>
                </div>
            </div>


            <div class="col-12">
                <div class="card mb-2" v-if="need_custom_settings">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Дополнительная конфигурация</h6>
                        <button type="button"
                                @click="addCustomSettings"
                                class="btn btn-outline-primary">Добавить настройку
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="row mb-2"
                             v-if="itemForm.custom_settings.length>0"
                             v-for="(setting, subIndex) in itemForm.custom_settings">
                            <div class="col-5">
                                <label class="form-label" id="service-title">
                                    <Popper
                                        content="Ключ">
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                    </Popper>
                                    Ключ

                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                                </label>
                                <input type="text" class="form-control"
                                       placeholder="Ключ"
                                       aria-label="Ключ"
                                       min="0"
                                       v-model="itemForm.custom_settings[subIndex].key"
                                       aria-describedby="price" required>
                            </div>
                            <div class="col-5">
                                <label class="form-label" id="service-title">
                                    <Popper
                                        content="Значение">
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                    </Popper>
                                    Значение

                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                                </label>
                                <input type="text" class="form-control"
                                       placeholder="Значение"
                                       aria-label="Значение"
                                       min="0"
                                       v-model="itemForm.custom_settings[subIndex].value"
                                       aria-describedby="price" required>
                            </div>
                            <div class="col-2 d-flex flex-column justify-content-end align-items-center">
                                <button
                                    @click="removeCustomSetting(subIndex)"
                                    class="btn btn-outline-danger">Удалить
                                </button>
                            </div>
                        </div>
                        <div class="row mb-2" v-else>
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    Вы еще не добавили ни одной дополнительной настройки
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit"
                        class="btn btn-outline-primary w-100">Добавить пункт меню
                </button>
            </div>
        </div>

    </form>
</template>

<script>
export default {
    props: ["item"],
    data() {
        return {
            need_custom_settings: false,
            need_keyboard: false,
            need_show_inline_template_selector: false,
            types: [
                {
                    title: 'Текст',
                    value: 0
                },
                {
                    title: 'Фото',
                    value: 1
                }
            ],
            itemForm: {
                id: null,
                type: 0,
                title: null,
                description: null,
                input_message_content: null,
                inline_keyboard_id: null,
                inline_keyboard: null,
                custom_settings: []
            }
        }
    },
    computed: {
        preparedTypeTile() {
            return this.types.filter(item => item.value == this.itemForm.type)[0] || null
        }
    },
    watch: {
        serviceForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {

        if (this.item)
            this.$nextTick(() => {
                this.serviceForm = {
                    id: null,
                    type: this.item.type || 0,
                    title: this.item.title || null,
                    description: this.item.description || null,
                    input_message_content: this.item.input_message_content || null,
                    need_keyboard: this.item.title || false,
                    inline_keyboard_id: this.item.inline_keyboard_id || null,
                    inline_keyboard: this.item.inline_keyboard || null,
                    custom_settings: this.item.custom_settings || [],
                }

            })

    },
    methods: {
        submitForm() {
            this.$emit("callback", this.itemForm)

            this.itemForm = {
                id: null,
                type: 0,
                title: null,
                description: null,
                input_message_content: null,
                inline_keyboard_id: null,
                inline_keyboard: null,
                custom_settings: []
            }
        },
        addCustomSettings() {
            this.itemForm.custom_settings.push({
                key: null,
                value: null,
            })
        },
        removeCustomSetting(index) {
            this.itemForm.custom_settings.splice(index, 1)
        },
        selectInlineKeyboard(item) {
            this.itemForm.inline_keyboard = item
            this.need_show_inline_template_selector = false

        }

    }
}
</script>


