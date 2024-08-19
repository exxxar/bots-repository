<script setup>
import BotMediaList from "@/ClientTg/Components/V1/BotMediaList.vue";
</script>
<template>
    <form id="slugForm"
          style="padding-bottom:100px;"
          v-on:submit.prevent="submit">
        <div class="form-floating mb-2">
            <input type="text"
                   v-model="slugForm.command"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Команда</label>
        </div>
        <div class="form-floating mb-2">
            <textarea
                v-model="slugForm.comment"
                style="min-height: 100px;"
                class="form-control font-12" id="floatingInput" placeholder="name@example.com" required>
            </textarea>
            <label for="floatingInput">Описание скрипта</label>
        </div>
        <h6>
            Параметры скрипта
        </h6>
        <p
            v-if="(configTypes||[]).length>0"
            class="mb-0">Фильтры:
            <span class="badge mb-1 cursor-pointer mx-1"
                  @click="toggleFilter(item.type)"
                  v-bind:class="{'bg-primary text-white': filters.indexOf(item.type)>=0,'bg-secondary':filters.indexOf(item.type)===-1}"
                  v-for="(item, index) in configTypes"> {{ item.type }}</span>
        </p>
        <div
            v-if="filteredConfigs.length>0">
            <div class="mb-1 alert alert-light py-2 px-2"
                 v-for="(item, index) in filteredConfigs">

<!--
                <div class="dropdown w-100 d-flex justify-content-end">
                    <a href="javascript:void(0)" class=" text-primary p-1 rounded-0" type="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bars"></i>
                    </a>
                    <ul class="dropdown-menu ">
                        <li><a
                            @click="removeConfigItem(index)"
                            class="dropdown-item" href="javascript:void(0)"> <i class="fa-solid fa-trash-can"></i>
                            Удалить</a></li>
                        <li><a class="dropdown-item"
                               @click="move(index, 0)"
                               href="javascript:void(0)"><i class="fa-solid fa-caret-left"></i> Назад</a></li>
                        <li><a class="dropdown-item"
                               @click="move(index, 1)"
                               href="javascript:void(0)"> <i class="fa-solid fa-caret-right"></i> Вперед</a></li>
                    </ul>
                </div>
-->

                <div class="form-floating mb-1"
                     v-if="filteredConfigs[index].type==='text' || filteredConfigs[index].type==='channel'">

                    <input type="text" class="form-control" :id="'field-input-'+index"
                           v-model="filteredConfigs[index].value"
                           placeholder="name@example.com">
                    <label :for="'field-input-'+index">Значение для
                        <strong>{{ filteredConfigs[index].key }}</strong></label>
                </div>
                <div
                    v-if="filteredConfigs[index].type==='boolean'"
                    class="form-check mb-1  form-switch">
                    <input class="form-check-input"
                           v-model="filteredConfigs[index].value"
                           type="checkbox" value="false" :id="'filtered-config-'+index+'-checkbox'">
                    <label class="form-check-label" :for="'filtered-config-'+index+'-checkbox'">
                        {{ filteredConfigs[index].value ? 'Истина' : 'Ложь' }} для
                        <strong>{{ filteredConfigs[index].key }}</strong>
                    </label>
                </div>
                <div
                    v-if="filteredConfigs[index].type==='geo'"
                    class="form-floating mb-1 ">

                    <input class="form-control"
                           v-mask="'##.######,##.######'"
                           placeholder="##.######,##.######"
                           v-model="filteredConfigs[index].value"
                           type="text" :id="'filtered-config-'+index+'-geo'">
                    <label :for="'filtered-config-'+index+'-geo'">Значение для
                        <strong>{{ filteredConfigs[index].key }}</strong></label>
                </div>
                <div
                    v-if="filteredConfigs[index].type==='phone'"
                    class="form-floating mb-1 ">

                    <input class="form-control"
                           v-mask="'+7(###)###-##-##'"
                           placeholder="+7(###)###-##-##"
                           v-model="filteredConfigs[index].value"
                           type="text" :id="'filtered-config-'+index+'-phone'">
                    <label :for="'filtered-config-'+index+'-phone'">Значение для
                        <strong>{{ filteredConfigs[index].key }}</strong></label>
                </div>
                <div
                    v-if="filteredConfigs[index].type==='json'"
                    class="mb-1 ">
                    <label class="form-label" id="bot-domain">JSON-код</label>
                    <Vue3JsonEditor
                        :mode="'code'"
                        v-model="filteredConfigs[index].value"
                        :show-btns="false"
                        :expandedOnStart="true"
                    />
                </div>
                <div
                    v-if="filteredConfigs[index].type==='color'"
                    class="form-check mb-1 ">
                    <label :for="'filtered-config-'+index+'-color'">
                        <p v-bind:style="{'color':filteredConfigs[index].value}">Цвет:
                            {{ filteredConfigs[index].value }} для <strong>{{ filteredConfigs[index].key }}</strong></p>
                    </label>
                    <input class="form-control"
                           v-model="filteredConfigs[index].value"
                           type="color" :id="'filtered-config-'+index+'-color'">

                </div>
                <div class="form-floating mb-1" v-if="filteredConfigs[index].type==='large-text'">

                    <textarea class="form-control font-12" :id="'field-input-'+index"
                              v-model="filteredConfigs[index].value"
                              style="min-height: 200px;"
                              placeholder="name@example.com">
                            </textarea>
                    <label :for="'field-input-'+index">Значение для
                        <strong>{{ filteredConfigs[index].key }}</strong></label>
                </div>
                <template v-if="filteredConfigs[index].type==='image'">
                    <div class="form-floating mb-3">

                        <input class="form-control mb-2" :id="'field-input-'+index"
                               type="text"
                               disabled="true"
                               v-model="filteredConfigs[index].value"
                               placeholder="name@example.com">
                        <label :for="'field-input-'+index">FileId ссылка на изображение</label>


                    </div>

                    <BotMediaList
                        :need-video="false"
                        :need-photo="true"
                        :selected="[filteredConfigs[index].value]"
                        v-on:select="selectPhoto($event, index)"></BotMediaList>

                </template>
                <p class="fst-italic mb-0">{{item.description||'Описания нет'}}</p>
            </div>


        </div>
        <div class="mb-2" v-else>

            <p class="mb-0">Параметры скрипта еще не добавлены</p>

        </div>
        <nav class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
             style="border-radius:10px 10px 0px 0px;">
            <button class="btn btn-primary w-100 p-3 rounded-3 shadow-lg text-center">
            <span v-if="slugForm.id==null">
                Сохранить команду
            </span>
                <span v-else>
                Обновить команду
            </span>
            </button>
        </nav>
    </form>
</template>
<script>
import {mapGetters} from "vuex";
import {Vue3JsonEditor} from 'vue3-json-editor'

export default {
    components: {
        Vue3JsonEditor
    },
    props: ["item"],
    data() {
        return {
            filters: [],
            simple: true,

            configTypes: [
                {
                    title: 'Текстовый или числовой параметр',
                    type: 'text',
                },
                {
                    title: 'Большое текстовое поле',
                    type: 'large-text',
                },
                {
                    title: 'Канал обратной связи',
                    type: 'channel',
                },
                {
                    title: 'Логический оператор',
                    type: 'boolean',
                },
                {
                    title: 'Ссылка на изображение',
                    type: 'image',
                },
                {
                    title: 'Цвет',
                    type: 'color',
                },
                {
                    title: 'Geo',
                    type: 'geo',
                },
                {
                    title: 'Номер телефона',
                    type: 'phone',
                },
                {
                    title: 'JSON',
                    type: 'json',
                }
                /*
                   {
                      title: 'Цвет',
                      type: 'color',
                  },
                  {
                      title: 'Координаты',
                      type: 'coords',
                  },
                    {
                      title: 'Условие',
                      type: 'condition',
                  },
                   {
                      title: 'Нижнее меню',
                      type: 'reply_menu',
                  },


                  */
            ],
            bot: null,

            photos: [],
            slugForm: {
                bot_id: null,
                id: null,
                command: null,
                comment: null,
                slug: null,
                config: [],
                is_global: true,

            }
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
        filteredConfigs() {
            if (this.filters.length == 0)
                return this.slugForm.config

            return this.slugForm.config.filter(item => this.filters.indexOf(item.type) >= 0)
        }
    },

    mounted() {


        if (this.item) {
            this.slugForm.id = this.item.id || null
            this.slugForm.command = this.item.command
            this.slugForm.comment = this.item.comment
            this.slugForm.bot_id = this.item.bot_id
            this.slugForm.slug = this.item.slug
            this.slugForm.config = this.item.config || []
            this.slugForm.is_global = this.item.is_global || false

        }

    },

    methods: {
        move(index, direction = 0) {
            let tmp = this.slugForm.config[index]

            let maxConfigElements = this.slugForm.config.length || 0

            let newIndex = direction === 0 ?
                index - 1 >= 0 ? index - 1 : maxConfigElements - 1 :
                index < maxConfigElements - 1 ? index + 1 : 0

            this.slugForm.config[index] = this.slugForm.config[newIndex]
            this.slugForm.config[newIndex] = tmp

        },
        toggleFilter(type) {
            let index = this.filters.indexOf(type)
            if (index >= 0)
                this.filters.splice(index, 1)
            else
                this.filters.push(type)
        },
        addTextTo(index, object = {text: null}) {
            this.slugForm.config[index].value = object.text;

        },

        submit() {
            let data = new FormData();
            Object.keys(this.slugForm)
                .forEach(key => {
                    const item = this.slugForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            this.$store.dispatch(this.slugForm.id === null ? "createSlug" : "updateSlug", {
                slugForm: data
            }).then((response) => {

                this.$botNotification.notification(
                    "Команды",
                    "Команда успешно обновлена",
                );

                if (this.slugForm.id === null) {
                    this.slugForm.id = null
                    this.slugForm.command = null
                    this.slugForm.comment = null
                    this.slugForm.bot_id = null
                    this.slugForm.slug = null
                    this.slugForm.config = []
                    this.slugForm.is_global = true

                }

                this.$emit("callback")
            }).catch(err => {

            })

        },
        addConfig(type) {

            this.slugForm.config.splice(0, 0, {
                key: null,
                value: null,
                type: type || 'text'
            });


        },
        removeConfigItem(index) {
            try {
                this.slugForm.config.splice(index, 1)
            } catch (e) {
                this.slugForm.config = []
            }

        },
        selectPhoto(event, index) {
            this.filteredConfigs[index].value = event.file_id
        }
    }
}
</script>
