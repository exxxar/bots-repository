<script setup>
import TelegramChannelHelper from "@/AdminPanel/Components/Constructor/Helpers/TelegramChannelHelper.vue";
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
import BotSlugList from "@/AdminPanel/Components/Constructor/Slugs/BotSlugSimpleTableList.vue";
import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
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

              <p v-if="slugForm.parent_id">Вы можете подгрузить отсутствующие параметры конфигурации</p>
                <button
                    type="button"
                    @click="loadSlugParentParams"
                    class="btn btn-outline-info"><i class="fa-solid fa-rotate mr-2"></i>Загрузить</button>
            </div>
        </div>
        <div class="row"
             v-if="filteredConfigs.length>0"
        >
            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Ключ</th>
                        <th scope="col">Значение</th>


                    </tr>
                    </thead>
                    <tbody>
                    <template  v-for="(item, index) in filteredConfigs">

                        <tr v-bind:class="{'table-light':index%2===0}">

                            <td colspan="2">

                                <div class="w-100 d-flex align-items-center justify-content-between">
                                    <div class="dropdown mr-2">
                                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-bars"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                   @click="removeConfigItem(index)"
                                                   href="javascript:void(0)"><i class="fa-solid fa-trash-can"></i> Удалить</a></li>
                                            <li><a class="dropdown-item"
                                                   @click="move(index, 0)"
                                                   href="javascript:void(0)">  <i class="fa-solid fa-caret-left"></i> Вверх</a></li>
                                            <li><a class="dropdown-item"
                                                   @click="move(index, 1)"
                                                   href="javascript:void(0)"><i class="fa-solid fa-caret-right"></i> Вниз</a></li>

                                        </ul>


                                    </div>
                                    <p class="mb-0 font-italic  font-bold" v-if="filteredConfigs[index].description" v-html="filteredConfigs[index].description"></p>
                                    <p class="mb-0 font-bold" v-else>без описания</p>

                                    <div class="dropdown ">
                                        <button class="btn btn-outline-primary dropdown-toggle w-100 d-flex justify-content-between align-items-center" type="button"
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

                            </td>
                        </tr>
                        <tr v-bind:class="{'table-light':index%2===0}">
                            <td style="width:300px;">
                                <div class="form-floating">
                                    <input type="text" class="form-control"
                                           v-model="filteredConfigs[index].key"
                                           :id="'field-input-key-'+index"
                                           placeholder="name@example.com" >
                                    <label :for="'field-input-key-'+index">Ключ</label>
                                </div>
                            </td>
                            <td>

                                <div class="form-floating"
                                     v-if="filteredConfigs[index].type==='text' || filteredConfigs[index].type==='channel'">
                                    <input type="text" class="form-control" :id="'field-input-'+index"
                                           v-model="filteredConfigs[index].value"
                                           placeholder="name@example.com" >
                                    <label :for="'field-input-'+index">Значение</label>
                                </div>


                                <div
                                    v-if="filteredConfigs[index].type==='boolean'"
                                    class="form-check">
                                    <input class="form-check-input"
                                           v-model="filteredConfigs[index].value"
                                           type="checkbox" value="false" :id="'filtered-config-'+index+'-checkbox'">
                                    <label class="form-check-label" :for="'filtered-config-'+index+'-checkbox'">
                                        {{ filteredConfigs[index].value ? 'Истина' : 'Ложь' }}
                                    </label>
                                </div>

                                <div
                                    v-if="filteredConfigs[index].type==='geo'"
                                    class="form-floating">
                                    <input class="form-control"
                                           v-mask="'##.######,##.######'"
                                           placeholder="##.######,##.######"
                                           v-model="filteredConfigs[index].value"
                                           type="text" :id="'filtered-config-'+index+'-geo'">
                                    <label :for="'filtered-config-'+index+'-geo'">Значение</label>
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-outline-light text-primary w-100 py-3"
                                    v-if="filteredConfigs[index].type==='script'"
                                    @click="item.content_show = !item.content_show">
                                    <i v-if="!item.content_show" class="fa-solid fa-eye mr-2"></i>
                                    <i v-else class="fa-solid fa-eye-slash mr-2"></i>
                                    <span v-if="!item.content_show">Показать выбор скриптов</span>
                                    <span v-else>Скрыть выбор скриптов</span>
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-outline-light text-primary w-100 py-3"
                                    v-if="filteredConfigs[index].type==='page'"
                                    @click="item.content_show = !item.content_show">
                                    <i v-if="!item.content_show" class="fa-solid fa-eye mr-2"></i>
                                    <i v-else class="fa-solid fa-eye-slash mr-2"></i>
                                    <span v-if="!item.content_show">Показать выбор страницы</span>
                                    <span v-else>Скрыть выбор страницы</span>
                                </button>


                                <div
                                    v-if="filteredConfigs[index].type==='phone'"
                                    class="form-floating">
                                    <input class="form-control"
                                           v-mask="'+7(###)###-##-##'"
                                           placeholder="+7(###)###-##-##"
                                           v-model="filteredConfigs[index].value"
                                           type="text" :id="'filtered-config-'+index+'-phone'">
                                    <label :for="'filtered-config-'+index+'-phone'">Значение</label>
                                </div>

                                <button

                                    type="button"
                                    class="btn btn-outline-light text-primary w-100 py-3"
                                    v-if="filteredConfigs[index].type==='json'"
                                    @click="item.content_show = !item.content_show">
                                    <i v-if="!item.content_show" class="fa-solid fa-eye mr-2"></i>
                                    <i v-else class="fa-solid fa-eye-slash mr-2"></i>
                                    <span v-if="!item.content_show">Показать редактор JSON</span>
                                    <span v-else>Скрыть редактор JSON</span>
                                </button>

                                <div
                                    v-if="filteredConfigs[index].type==='color'"
                                    class="form-check">
                                    <input class="form-control"
                                           v-model="filteredConfigs[index].value"
                                           type="color" :id="'filtered-config-'+index+'-color'">
                                    <label :for="'filtered-config-'+index+'-color'">
                                        <p v-bind:style="{'color':filteredConfigs[index].value}">Цвет: {{filteredConfigs[index].value}}</p>
                                    </label>
                                </div>

                                <div class="form-floating" v-if="filteredConfigs[index].type==='large-text'">
                            <textarea class="form-control" :id="'field-input-'+index"
                                      v-model="filteredConfigs[index].value"
                                      placeholder="name@example.com" >
                            </textarea>
                                    <label :for="'field-input-'+index">Значение</label>
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-outline-light text-primary w-100 py-3"
                                    v-if="filteredConfigs[index].type==='image'"
                                    @click="item.content_show = !item.content_show">
                                    <i v-if="!item.content_show" class="fa-solid fa-eye mr-2"></i>
                                    <i v-else class="fa-solid fa-eye-slash mr-2"></i>
                                    <span v-if="!item.content_show">Показать выбор изображений</span>
                                    <span v-else>Скрыть выбор изображений</span>
                                </button>

                            </td>


                        </tr>
                        <tr v-bind:class="{'table-light':index%2===0}"
                            v-if="item.content_show">
                            <td colspan="2">
                                <div class="form-floating mb-3" v-if="filteredConfigs[index].type==='image'">

                                    <input class="form-control mb-2" :id="'field-input-'+index"
                                           type="text"
                                           disabled="true"
                                           v-model="filteredConfigs[index].value"
                                           placeholder="name@example.com" >
                                    <label :for="'field-input-'+index">FileId ссылка на изображение</label>

                                    <BotMediaList
                                        :need-video="false"
                                        :need-photo="true"
                                        :selected="[filteredConfigs[index].value]"
                                        v-on:select="selectPhoto($event, index)"></BotMediaList>
                                </div>

                                <div
                                    v-if="filteredConfigs[index].type==='json'"
                                    class="form-floating">
                                    <label class="form-label" id="bot-domain">JSON-код</label>
                                    <Vue3JsonEditor
                                        :mode="'form'"
                                        v-model="filteredConfigs[index].value"
                                        :show-btns="false"
                                        :expandedOnStart="true"
                                        @json-change="onJsonChange($event, index)"
                                    />
                                </div>


                                <div
                                    v-if="filteredConfigs[index].type==='page'"
                                    class="form-floating">
                                    <input class="form-control mb-2"
                                           v-model="filteredConfigs[index].value"
                                           type="text" :id="'filtered-config-'+index+'-script'">
                                    <label :for="'filtered-config-'+index+'-script'">Значение</label>

                                    <PagesList
                                        v-on:callback="selectSlug($event, index)"
                                        :editor="false"/>

                                </div>

                                <div
                                    v-if="filteredConfigs[index].type==='script'"
                                    class="form-floating">
                                    <input class="form-control mb-2"
                                           v-model="filteredConfigs[index].value"
                                           type="text" :id="'filtered-config-'+index+'-script'">
                                    <label :for="'filtered-config-'+index+'-script'">Значение</label>

                                    <BotSlugList v-on:select="selectSlug($event, index)"/>
                                </div>
                            </td>
                        </tr>
                    </template>


                    </tbody>
                </table>
            </div>



        </div>
        <div class="row" v-else>
            <div class="col-12">
                <p>Параметры скрипта еще не добавлены</p>
            </div>
        </div>

        <button class="btn btn-primary text-white w-100 mt-2 p-3">
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
import {Vue3JsonEditor} from 'vue3-json-editor'
export default {
    props: ["item"],
    components: {
        Vue3JsonEditor
    },
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
                    title: 'ID скрипта',
                    type: 'script',
                },
                {
                    title: 'ID страницы',
                    type: 'page',
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

            photos:[],
            slugForm: {
                bot_id: null,
                id: null,
                command: null,
                comment: null,
                slug: null,
                parent_id: null,
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
            this.slugForm.parent_id = this.item.parent_id || null
            this.slugForm.config = this.item.config || []
            this.slugForm.is_global = this.item.is_global || false

        }

    },

    methods: {
        loadSlugParentParams(){
            this.$store.dispatch("loadSlugParams", {
                parentSlugId:this.slugForm.parent_id
            }).then((response) => {
                let config= response.config

                let attachedParamCount = 0;
                config.forEach(item=>{
                    let findIndex = this.slugForm.config.findIndex(c=>c.key===item.key)

                    if (findIndex===-1) {
                        attachedParamCount++
                        this.slugForm.config.unshift(item)
                    }

                })

                this.$notify({
                    title: "Конструктор команд",
                    text: `Параметры успешно подгружены: Обновлено ${attachedParamCount} параметров`,
                    type: 'success'
                });
            })
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
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
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
        onJsonChange(e, index) {
            this.filteredConfigs[index].value = e
        },
        addConfig(type) {

            this.slugForm.config.splice(0, 0, {
                key: null,
                value: null,
                type: type || 'text'
            });


        },
        move(index, direction = 0){
            let tmp = this.slugForm.config[index]

            let maxConfigElements = this.slugForm.config.length || 0

            let newIndex = direction === 0 ?
                index -1 >= 0 ?index - 1 : maxConfigElements-1 :
                index < maxConfigElements-1 ? index + 1 : 0

            this.slugForm.config[index] = this.slugForm.config[newIndex]
            this.slugForm.config[newIndex] = tmp

        },
        removeConfigItem(index) {
            try {
                this.slugForm.config.splice(index, 1)
            } catch (e) {
                this.slugForm.config = []
            }

        },
        selectSlug(event, index){
            this.filteredConfigs[index].value = event.id
        },
        selectPhoto(event, index){
            this.filteredConfigs[index].value = event.file_id
        }
    }
}
</script>
