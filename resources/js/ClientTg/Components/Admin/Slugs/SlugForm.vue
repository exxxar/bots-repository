<template>
    <form id="slugForm" v-on:submit.prevent="submit">
        <div class="form-floating mb-2">
            <label for="floatingInput">Команда</label>
            <input type="text"
                   v-model="slugForm.command"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>

        </div>

        <div class="form-floating mb-2">
            <label for="floatingInput">Описание скрипта</label>
            <textarea
                v-model="slugForm.comment"
                style="min-height: 200px;"
                class="form-control font-12" id="floatingInput" placeholder="name@example.com" required>
            </textarea>

        </div>


        <h6>
            Параметры скрипта
        </h6>


        <p class="mb-2">Фильтры:
            <span class="badge mb-1 bg-gray-100 cursor-pointer mr-1"
                  @click="toggleFilter(item.type)"
                  v-bind:class="{'bg-info text-white': filters.indexOf(item.type)>=0}"
                  v-for="(item, index) in configTypes"> {{ item.type }}</span>
        </p>

        <hr>
        <div v-if="filteredConfigs.length>0">
            <div class="mb-1"
                 v-for="(item, index) in filteredConfigs">


                <div class="form-floating mb-1"
                     v-if="filteredConfigs[index].type==='text' || filteredConfigs[index].type==='channel'">
                    <label :for="'field-input-'+index">Значение для
                        <strong>{{ filteredConfigs[index].key }}</strong></label>
                    <input type="text" class="form-control" :id="'field-input-'+index"
                           v-model="filteredConfigs[index].value"
                           placeholder="name@example.com" required>

                </div>

                <div
                    v-if="filteredConfigs[index].type==='boolean'"
                    class="form-check mb-1 mt-2">
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
                    class="form-floating mb-1 mt-2">
                    <label :for="'filtered-config-'+index+'-geo'">Значение для
                        <strong>{{ filteredConfigs[index].key }}</strong></label>
                    <input class="form-control"
                           v-mask="'##.######,##.######'"
                           placeholder="##.######,##.######"
                           v-model="filteredConfigs[index].value"
                           type="text" :id="'filtered-config-'+index+'-geo'">

                </div>

                <div
                    v-if="filteredConfigs[index].type==='phone'"
                    class="form-floating mb-1 mt-2">
                    <label :for="'filtered-config-'+index+'-phone'">Значение для
                        <strong>{{ filteredConfigs[index].key }}</strong></label>
                    <input class="form-control"
                           v-mask="'+7(###)###-##-##'"
                           placeholder="+7(###)###-##-##"
                           v-model="filteredConfigs[index].value"
                           type="text" :id="'filtered-config-'+index+'-phone'">

                </div>


                <div
                    v-if="filteredConfigs[index].type==='color'"
                    class="form-check mb-1 mt-2">
                    <label :for="'filtered-config-'+index+'-color'">
                        <p v-bind:style="{'color':filteredConfigs[index].value}">Цвет:
                            {{ filteredConfigs[index].value }} для <strong>{{ filteredConfigs[index].key }}</strong></p>
                    </label>
                    <input class="form-control"
                           v-model="filteredConfigs[index].value"
                           type="color" :id="'filtered-config-'+index+'-color'">

                </div>

                <div class="form-floating mb-3" v-if="filteredConfigs[index].type==='large-text'">
                    <label :for="'field-input-'+index">Значение для
                        <strong>{{ filteredConfigs[index].key }}</strong></label>
                    <textarea class="form-control font-12" :id="'field-input-'+index"
                              v-model="filteredConfigs[index].value"
                              style="min-height: 200px;"
                              placeholder="name@example.com" required>
                            </textarea>

                </div>

                <div class="form-floating mb-3" v-if="filteredConfigs[index].type==='image'">
                    <label :for="'field-input-'+index">URL ссылка на изображение для
                        <strong>{{ filteredConfigs[index].key }}</strong></label>
                    <input class="form-control" :id="'field-input-'+index"
                           type="url"
                           pattern="^(http(s)?:\/\/)+[\w\-\._~:\/?#[\]@!\$&'\(\)\*\+,;=.]+$"
                           v-model="filteredConfigs[index].value"
                           placeholder="name@example.com" required>

                </div>

                <div class="divider divider-small my-3 bg-highlight "></div>
            </div>


        </div>
        <div class="mb-2" v-else>

            <p class="mb-0">Параметры скрипта еще не добавлены</p>

        </div>

        <button class="btn btn-m btn-full mb-2 rounded-xs text-uppercase font-900 shadow-s bg-mint-dark w-100">
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

                  {
                      title: 'JSON',
                      type: 'json',
                  }
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
