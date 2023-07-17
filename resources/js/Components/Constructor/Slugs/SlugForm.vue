<script setup>
import TelegramChannelHelper from "@/Components/Constructor/Helpers/TelegramChannelHelper.vue";
</script>
<template>
    <form v-on:submit.prevent="submit">
        <div class="form-floating mb-3">
            <input type="text"
                   v-model="slugForm.command"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Команда</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text"
                   v-model="slugForm.slug"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Мнемоническое имя</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text"
                   v-model="slugForm.comment"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Описание скрипта</label>
        </div>


        <h6>
            Параметры скрипта
        </h6>
        <div class="alert alert-info" role="alert">
            Данные параметры используются для настройки скриптов на стороне сервера
        </div>


        <div class="dropdown">
            <button class="btn btn-outline-info w-100 dropdown-toggle mb-2" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                Добавить
            </button>
            <ul class="dropdown-menu w-100">
                <li v-for="(item, index) in configTypes">
                    <a class="dropdown-item"
                       @click="addConfig(item.type)">{{ item.title || 'Не установлен' }}</a>
                </li>

            </ul>
        </div>


        <div class="row">
            <div class="col-12">
                <p>Фильтры:
                    <span class="badge mb-1 bg-gray-100 cursor-pointer mr-1"
                          @click="toggleFilter(item.type)"
                          v-bind:class="{'bg-info': filters.indexOf(item.type)>=0}"
                          v-for="(item, index) in configTypes"> {{ item.type }}</span>
                </p>

            </div>
        </div>
        <div class="row"
             v-if="filteredConfigs.length>0"
        >
            <div class="col-md-6 mb-1"
                 v-for="(item, index) in filteredConfigs">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <button
                            @click="removeConfigItem(index)"
                            class="btn btn-outline-info" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                        <TelegramChannelHelper
                            v-if="bot&&filteredConfigs[index].type==='channel'"
                            :token="bot.bot_token"
                            v-on:callback="addTextTo(index,$event)"
                        />
                        <div>

                            <div class="dropdown">
                                <button class="btn btn-outline-info dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ filteredConfigs[index].type }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li
                                        v-for="config in configTypes">
                                        <a class="dropdown-item" @click="filteredConfigs[index].type = config.type">{{
                                                config.title || item.type
                                            }}</a>
                                    </li>
                                </ul>
                            </div>

                        </div>


                    </div>
                    <div class="card-body">
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control"
                                   v-model="filteredConfigs[index].key"
                                   id="floatingInput"
                                   placeholder="name@example.com" required>
                            <label for="floatingInput">Ключ</label>
                        </div>

                        <div class="form-floating mb-1"
                             v-if="filteredConfigs[index].type==='text' || filteredConfigs[index].type==='channel'">
                            <input type="text" class="form-control" id="floatingInput"
                                   v-model="filteredConfigs[index].value"
                                   placeholder="name@example.com" required>
                            <label for="floatingInput">Значение</label>
                        </div>


                        <div
                            v-if="filteredConfigs[index].type==='boolean'"
                            class="form-check mb-1 mt-2">
                            <input class="form-check-input"
                                   v-model="filteredConfigs[index].value"
                                   type="checkbox" value="false" :id="'filtered-config-'+index+'-checkbox'">
                            <label class="form-check-label" :for="'filtered-config-'+index+'-checkbox'">
                                {{ filteredConfigs[index].value ? 'Истина' : 'Ложь' }}
                            </label>
                        </div>

                        <div class="form-floating mb-3" v-if="filteredConfigs[index].type==='large-text'">
                            <textarea class="form-control" id="floatingInput"
                                      v-model="filteredConfigs[index].value"
                                      placeholder="name@example.com" required>
                            </textarea>
                            <label for="floatingInput">Значение</label>
                        </div>


                    </div>
                </div>
            </div>


            <!--                    <div class="col-md-5" v-if="slugForm.config[index].type==='image'">
                                    <label :for="'param-photo-'+index+'-item-'+item.id" style="margin-right: 10px;"
                                           class="photo-loader ml-2">
                                        <span>+ </span>
                                        <span
                                            v-if="slugForm.config[index].value">{{
                                                slugForm.config[index].value
                                            }}</span>
                                        <input type="file" :id="'param-photo-'+index+'-item-'+item.id"
                                               accept="image/*"
                                               @change="onChangePhotos($event, index)"
                                               style="display:none;"/>

                                    </label>
                                </div>-->

        </div>
        <div class="row" v-else>
            <div class="col-12">
                <p>Параметры скрипта еще не добавлены</p>
            </div>
        </div>

        <button class="btn btn-outline-primary w-100 mt-2 p-3">
            <span v-if="slugForm.id==null">
                Сохранить команду
            </span>
            <span v-else>
                Обновить команду
            </span>
        </button>

    </form>
</template>
<script>
import {mapGetters} from "vuex";

export default {
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
                /*  {
                      title: 'Изображение',
                      type: 'image',
                  },
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

                  {
                      title: 'JSON',
                      type: 'json',
                  }
                  */
            ],
            bot: null,
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

        this.loadCurrentBot()

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
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        onChangePhotos(e, index) {
            const files = e.target.files
            this.slugForm.config[index].value = files[0]
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

                this.$notify({
                    title: "Конструктор команд",
                    text: "Команда успешно обновлена",
                    type: 'success'
                });

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
    }
}
</script>
