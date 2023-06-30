
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


<!--        <div class="form-check">
            <input class="form-check-input"
                   v-model="slugForm.is_global"
                   type="checkbox" value="" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
                Является глобальным
            </label>
        </div>-->


        <div class="card">
            <div class="card-header">
                Параметры скрипта
            </div>
            <div class="card-body">

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

                <div class="row"
                     v-if="slugForm.config.length>0"
                     v-for="(item, index) in slugForm.config">
                    <div class="col-md-5">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   v-model="slugForm.config[index].key"
                                   id="floatingInput"
                                   placeholder="name@example.com" required>
                            <label for="floatingInput">Ключ</label>
                        </div>
                    </div>
                    <div class="col-md-5" v-if="slugForm.config[index].type==='text'">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput"
                                   v-model="slugForm.config[index].value"
                                   placeholder="name@example.com" required>
                            <label for="floatingInput">Значение</label>
                        </div>
                    </div>
                    <div class="col-md-5" v-if="slugForm.config[index].type==='coords'">
                        <p>Координаты</p>
                    </div>
                    <div class="col-md-5" v-if="slugForm.config[index].type==='image'">
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
                    </div>
                    <div class="col-md-2">
                        <button
                            @click="removeConfigItem(index)"
                            class="btn btn-outline-info w-100 p-3" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
                <div class="row" v-else>
                    <div class="col-12">
                        <p>Параметры скрипта еще не добавлены</p>
                    </div>
                </div>
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
            simple: true,
            configTypes: [
                {
                    title: 'Текстовый или числовой параметр',
                    type: 'text',
                },
                /*  {
                      title: 'Изображение',
                      type: 'image',
                  },
                  {
                      title: 'Координаты',
                      type: 'coords',
                  },
                    {
                      title: 'Условие',
                      type: 'condition',
                  }
                  */
            ],
            slugForm: {
                bot_id: null,
                id: null,
                command: null,
                comment: null,
                slug: null,
                config: [],
                is_global: true,
                bot_dialog_command_id: null,
            }
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
            this.slugForm.bot_dialog_command_id = this.item.bot_dialog_command_id
        }

    },

    methods: {

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

                if (this.slugForm.id === null)
                {
                    this.slugForm.id =null
                    this.slugForm.command = null
                    this.slugForm.comment = null
                    this.slugForm.bot_id = null
                    this.slugForm.slug = null
                    this.slugForm.config = []
                    this.slugForm.is_global = true
                    this.slugForm.bot_dialog_command_id = null
                }

                this.$emit("callback")
            }).catch(err => {

            })

        },
        addConfig(type) {
            this.slugForm.config.push({
                key: null,
                value: null,
                type: type || 'text'
            })
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
