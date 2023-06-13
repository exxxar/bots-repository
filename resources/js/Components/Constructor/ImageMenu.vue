<template>
    <div class="card" v-if="bot">
        <div class="card-body">

            <div class="row ">
                <div class="col-md-8">
                    <form v-on:submit.prevent="addImageMenu">
                        <h6>Графическое Меню к боту #{{ bot.id || 'Не установлен' }}</h6>
                        <div class="row">

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" id="menu-address">
                                        Заголовок меню
                                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                                    </label>
                                    <input type="text" class="form-control"
                                           placeholder="Название меню"
                                           aria-label="Название меню"
                                           maxlength="255"
                                           v-model="menuForm.title"
                                           aria-describedby="menu-address" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" id="menu-description">
                                        Описание меню
                                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>

                                        <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="menuForm.description">
                                            Длина текста {{ menuForm.description.length }}</small>
                                    </label>
                                    <textarea type="text" class="form-control"
                                              placeholder="Описание меню"
                                              aria-label="Описание меню"
                                              maxlength="255"
                                              v-model="menuForm.description"
                                              aria-describedby="menu-description">
                    </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" id="menu-address">Ссылка на страницу в <a target="_blank"
                                                                                                        href="https://telegra.ph">telegra.ph</a></label> с вашим меню
                                    <input type="text" class="form-control"
                                           placeholder="Информационная ссылка"
                                           aria-label="Информационная ссылка"
                                           maxlength="255"
                                           v-model="menuForm.info_link"
                                           aria-describedby="menu-info-link">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <h5>Картинка меню с позициями</h5>
                                <div class="photo-preview d-flex justify-content-start flex-wrap w-100">
                                    <label for="menu-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                                        <span>+</span>
                                        <input type="file" id="menu-photos" accept="image/*"
                                               @change="onChangePhotos"
                                               style="display:none;"/>

                                    </label>
                                    <div class="mb-2 img-preview" style="margin-right: 10px;"
                                         v-if="menuForm.image">
                                        <img v-lazy="getPhoto(menuForm.image).imageUrl">
                                        <div class="remove">
                                            <a @click="removePhoto()">Удалить</a>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-outline-success w-100"
                                        type="submit">Добавить меню
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-12 mt-3"
                             v-if="menus.length>0"
                             v-for="(menu, index) in menus">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h6>Название <strong>{{ menu.title || 'Не указано' }}</strong></h6>
                                    <a class="cursor-pointer"
                                       @click="removeItem( index)">Удалить</a>
                                </div>
                                <div class="card-body">
                                    <p>{{ menu.description || 'Не указано' }}</p>
                                    <p> ссылка на меню из telegra.ph {{ menu.info_link || 'Не указано' }}</p>
                                    <div class="w-100 d-flex">
                                        <div class="mb-2 img-preview" style="margin-right: 10px;"
                                             v-if="menu.image">

                                            <img v-if="typeof menu.image =='string' "
                                                 v-lazy="'/images-by-bot-id/'+bot.id+'/'+menu.image">
                                            <img v-else v-lazy="getPhoto(menu.image).imageUrl">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3" v-else>
                            <p>На текущий момент у заведения нет ни 1 созданного меню</p>
                        </div>
                    </div>
                </div>

            </div>




            <div class="row mt-3">
                <div class="col-12">
                    <button
                        @click="submitMenus"
                        :disabled="menus.length===0"
                        class="btn btn-outline-primary p-3 w-100">Сохранить меню для заведения
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["bot"],
    data() {
        return {
            menus: [],
            deletedMenus: [],
            menuForm: {
                title: null,
                description: null,
                image: null,
                info_link: null,
                bot_id: null,
            }
        }
    },
    mounted() {
        this.loadMenuByBotId()
    },
    methods: {
        loadMenuByBotId() {
            this.$store.dispatch("loadMenuByBotId", {
                botId: this.bot.id
            }).then(resp => {
                this.menus = resp
            }).catch(() => {

            })
        },

        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto() {
            this.menuForm.image = null
        },
        removeItem(index) {
            if (this.menus[index].id) {
                this.deletedMenus.push(this.menus[index].id)
            }
            this.menus.splice(index, 1)
        },
        submitMenus() {
            this.menus.forEach(menu => {
                let data = new FormData();
                Object.keys(menu)
                    .forEach(key => {
                        const item = menu[key] || ''
                        if (typeof item === 'object')
                            data.append(key, JSON.stringify(item))
                        else
                            data.append(key, item)
                    });

                if (this.deletedMenus.length > 0) {
                    data.append("deleted_menus", JSON.stringify(this.deletedMenus))
                }

                if (typeof menu.image != "string") {
                    data.append('preview', menu.image);
                    data.delete("image")
                }

                this.$store.dispatch("createImageMenu", {
                    menuForm: data
                }).then((response) => {
                    this.$emit("callback", response.data)
                    this.$notify("Меню успешно создано и сохранено");
                }).catch(err => {

                })
            })
        },
        addImageMenu() {
            this.menuForm.bot_id = this.bot.id
            this.menus.push(this.menuForm);
            this.$notify("Меню успешно добавлено в список");
            this.menuForm = {
                title: null,
                description: null,
                image: null,
                info_link: null,
                bot_id: null,
            }
        },
        onChangePhotos(e) {
            const file = e.target.files[0]
            this.menuForm.image = file
        },
    }
}
</script>

