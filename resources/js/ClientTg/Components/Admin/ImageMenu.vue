<template>

    <div class="card card-style" v-if="bot">
        <div class="content mt-0 mb-0 py-3">
            <h3>Настройка графического меню</h3>
            <form v-on:submit.prevent="addImageMenu">

                <div class="mb-3">
                    <label class="form-label d-flex justify-content-between" id="menu-address">
                        Заголовок меню
                        <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="Название меню"
                           aria-label="Название меню"
                           maxlength="255"
                           v-model="menuForm.title"
                           aria-describedby="menu-address" required>
                </div>


                <div class="mb-3">
                    <label class="form-label d-flex justify-content-between mb-2" id="menu-description">

                        Описание меню
                        <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>


                    </label>

                    <small class="text-gray-400" style="font-size:10px;" v-if="menuForm.description">
                        Длина текста {{ menuForm.description.length }}</small>
                    <textarea type="text" class="form-control"
                              placeholder="Описание меню"
                              aria-label="Описание меню"
                              maxlength="255"
                              v-model="menuForm.description"
                              aria-describedby="menu-description">
                    </textarea>
                </div>


                <div class="mb-3">
                    <label class="form-label" id="menu-address">
                        Ссылка на страницу в
                        <a target="_blank" href="https://telegra.ph">telegra.ph</a>
                    </label>
                    с вашим меню
                    <input type="text" class="form-control"
                           placeholder="Информационная ссылка"
                           aria-label="Информационная ссылка"
                           maxlength="255"
                           v-model="menuForm.info_link"
                           aria-describedby="menu-info-link">
                </div>


                <h6>Картинка меню с позициями</h6>
                <div class="photo-preview d-flex justify-content-center flex-wrap w-100">
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


                <button
                    class="btn btn-border btn-m btn-full rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100"
                    type="submit">Добавить меню
                </button>

            </form>
        </div>
    </div>


    <div class="card card-style" v-if="bot">
        <div class="content mt-0 mb-0">
            <div class="mt-3"
                 v-if="menus.length>0"
                 v-for="(menu, index) in menus">
                <h6  class="d-flex justify-content-between align-items-center">
                    {{ menu.title || 'Не указано' }}
                    <a class="btn btn-link text-danger"
                       @click="removeItem( index)"><i class="fa-solid fa-trash-can"></i></a>
                </h6>

                <p class="mb-2">{{ menu.description || 'Не указано' }}</p>
                <p class="mb-2" v-if="menu.info_link"> ссылка на меню из telegra.ph {{ menu.info_link || 'Не указано' }}</p>
                <div class="w-100 d-flex justify-content-center">
                    <div class="mb-2 img-preview" style="margin-right: 10px;"
                         v-if="menu.image">

                        <img v-if="typeof menu.image =='string' "
                             v-lazy="'/images-by-bot-id/'+bot.id+'/'+menu.image">
                        <img v-else v-lazy="getPhoto(menu.image).imageUrl">
                    </div>
                </div>



                <div class="divider divider-small my-3 bg-highlight "></div>

            </div>
            <div class="mt-3" v-else>
                <p>На текущий момент у заведения нет ни 1 созданного меню</p>
            </div>

            <button
                @click="submitMenus"
                :disabled="menus.length===0"
                class="btn btn-m btn-full mb-0 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100 mb-3">
                Сохранить меню для заведения
            </button>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

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
        this.loadImageMenus()

    },
    methods: {

        loadImageMenus() {
            this.$store.dispatch("loadImageMenus").then(resp => {
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

