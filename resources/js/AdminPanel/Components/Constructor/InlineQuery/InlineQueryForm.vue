<script setup>
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
import KeyboardList from "@/AdminPanel/Components/Constructor/KeyboardList.vue";
</script>
<template>
    <form v-on:submit.prevent="submitForm">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label" id="service-title">
                    <Popper
                        content="Название оказываемой услуги">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Название команды

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="text" class="form-control"
                       placeholder="Название"
                       aria-label="Название"
                       v-model="inlineQueryForm.title"
                       maxlength="255"
                       aria-describedby="service-title" required>
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
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="inlineQueryForm.description">
                        Длина текста {{ inlineQueryForm.description.length }} / 255</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Описание команды"
                          aria-label="Описание команды"
                          maxlength="255"
                          v-model="inlineQueryForm.description"
                          aria-describedby="service-description" required>
                    </textarea>

            </div>


            <div v-if="inlineQueryForm.items.length>0"
                 v-for="(item, index) in inlineQueryForm.items">
                <div class="col-12 col-md-6 mb-3">
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                            <span v-if=" inlineQueryForm.items[index].type">
                                {{ inlineQueryForm.items[index].type.title}}
                            </span>
                            <span v-else>Тип поля</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li
                                @click="inlineQueryForm.items[index].type = type"
                                v-for="type in types"><a class="dropdown-item" href="#">
                                {{type.title||'Не указан'}}
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label class="form-label" id="service-title">
                        <Popper
                            content="Заголовок">
                            <i class="fa-regular fa-circle-question mr-1"></i>
                        </Popper>
                       Заголовок

                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                    </label>
                    <input type="number" class="form-control"
                           placeholder="Цена"
                           aria-label="Цена"
                           min="0"
                           v-model="inlineQueryForm.items[index].title"
                           aria-describedby="price" required>
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
                        <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="inlineQueryForm.items[index].description">
                            Длина текста {{ inlineQueryForm.items[index].description.length }} / 255</small>
                    </label>
                    <textarea type="text" class="form-control"
                              placeholder="Описание команды"
                              aria-label="Описание команды"
                              maxlength="255"
                              v-model="inlineQueryForm.items[index].description"
                              aria-describedby="service-description" required>
                    </textarea>

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
                        <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="inlineQueryForm.items[index].input_message_content">
                            Длина текста {{ inlineQueryForm.items[index].input_message_content.length }} / 255</small>
                    </label>
                    <textarea type="text" class="form-control"
                              placeholder="Описание команды"
                              aria-label="Описание команды"
                              maxlength="255"
                              v-model="inlineQueryForm.items[index].input_message_content"
                              aria-describedby="service-description" required>
                    </textarea>

                </div>



                <div class="col-12 col-md-6 mb-3">
                    <KeyboardList
                        class="mb-2"
                        :type="'inline'"
                        v-if="inlineQueryForm.items[index].need_keyboard"
                        v-on:select="selectInlineKeyboard"
                        :select-mode="true"/>

                    <BotMenuConstructor
                        :type="'inline'"
                        v-else
                        v-on:save="saveInlineKeyboard"
                        :edited-keyboard="inlineQueryForm.items[index].inline_keyboard"/>
                </div>


                <h6>Дополнительная конфигурация</h6>
                <div v-if="inlineQueryForm.items[index].custom_settings" class="col-12">
                    <div class="row" v-for="(setting, subIndex) in inlineQueryForm.items[index].custom_settings">
                        <div class="col-5">
                            <label class="form-label" id="service-title">
                                <Popper
                                    content="Ключ">
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                </Popper>
                                Ключ

                                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                            </label>
                            <input type="number" class="form-control"
                                   placeholder="Ключ"
                                   aria-label="Ключ"
                                   min="0"
                                   v-model="inlineQueryForm.items[index].custom_settings[subIndex].key"
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
                            <input type="number" class="form-control"
                                   placeholder="Значение"
                                   aria-label="Значение"
                                   min="0"
                                   v-model="inlineQueryForm.items[index].custom_settings[subIndex].value"
                                   aria-describedby="price" required>
                        </div>
                        <div class="col-2">
                            <button>Удалить</button>
                        </div>
                    </div>
                </div>
                <button type="button"
                        @click="addCustomSettings(index)"
                        class="btn btn-outline-primary">Добавить настройку</button>


            </div>
            <button type="button"
                    @click="addItem"
                    class="btn btn-outline-primary">Добавить пункт меню</button>
<!--
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" id="service-title">
                    <Popper
                        content="Цена скидки на оказываемую услугу">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Цена скидки на услугу, руб

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="number" class="form-control"
                       placeholder="Цена"
                       aria-label="Цена"
                       min="0"
                       v-model="inlineQueryForm.discount_price"
                       aria-describedby="price" required>
            </div>


            <div class="col-12 mb-3">
                <label class="form-label" id="service-images">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Изображение, которое видит пользователь при выборе услуги
                            </div>
                        </template>
                    </Popper>
                    Изображение к услуге
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <BotMediaList
                    :need-photo="true"
                    :selected="inlineQueryForm.images"
                    v-on:select="selectPhotos"></BotMediaList>
            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="inlineQueryForm.need_prepayment"
                           type="checkbox" id="need_prepayment">
                    <label class="form-check-label" for="need_prepayment">
                        Необходима предоплата
                    </label>
                </div>
            </div>
-->


        </div>
        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="inlineQueryForm.id===null">Создать услугу</span>
                    <span v-else>Обновить услугу</span>
                </button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    props: [ "bot","inlineQuery"],
    data() {
        return {
            load: false,
            need_reset: false,
            need_show_inline_template_selector: false,
            categories: [],
            types:[
                {
                    title:'Текст',
                    value:'article'
                },
                {
                    title:'Фото',
                    value:'photo'
                }
            ],
            inlineQueryForm: {
                id:null,
                command:null,
                description:null,
                items:[

                ]
            }
        }
    },

    watch: {
        inlineQueryForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {


        if (this.inlineQuery)
            this.$nextTick(() => {
                this.inlineQueryForm = {
                    id: this.inlineQuery.id || null,
                    command: this.inlineQuery.command || null,
                    description: this.inlineQuery.description || null,
                    items: this.inlineQuery.items || [],
                }
            })

    },
    methods: {
        addItem(){
            this.inlineQueryForm.items.push({
                type:'article',
                title:null,
                description:null,
                input_message_content:null,
                need_keyboard:false,
                inline_keyboard_id:null,
                inline_keyboard:null,
                custom_settings:[]
            })
        },
        addCustomSettings(index){
          this.inlineQueryForm.items[index].custom_settings.push({
              key:null,
              value:null,
          })
        },
        selectPhotos(item) {
            if (!this.inlineQueryForm.images)
                this.inlineQueryForm.images = []

            let index = this.inlineQueryForm.images.indexOf(item.file_id)

            if (index !== -1)
                this.inlineQueryForm.images.splice(index, 1)
            else
                this.inlineQueryForm.images.push(item.file_id)
        },
        resetForm() {

        },
        submitForm() {
            let data = new FormData();
            Object.keys(this.inlineQueryForm)
                .forEach(key => {
                    const item = this.inlineQueryForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);
            data.append('appointment_event_id', this.eventId);

            this.$store.dispatch(this.inlineQueryForm.id === null ?
                    "addAppointmentService" :
                    "updateAppointmentService",
                {
                    appointmentServiceForm: data
                }).then((response) => {
                this.$emit("callback", response.data)

                this.$notify("Услуги успешно создано");
            }).catch(err => {
                this.$notify("Услуги создания события");
            })

        },

    }
}
</script>


