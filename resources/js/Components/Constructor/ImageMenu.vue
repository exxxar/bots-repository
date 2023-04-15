<template>
    <div class="card">
        <div class="card-body">

            <form v-on:submit.prevent="addImageMenu">
                <h6>Графическое Меню к боту #{{botId||'Не установлен'}}</h6>
                <div class="row">

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label" id="menu-address">Название меню</label>
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
                            <label class="form-label" id="menu-description">Описание меню</label>
                            <textarea type="text" class="form-control"
                                      placeholder="Описание меню"
                                      aria-label="Описание меню"
                                      maxlength="255"
                                      v-model="menuForm.description"
                                      aria-describedby="menu-description" required>
                    </textarea>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label" id="menu-address">Ссылка на страницу в <a target="_blank" href="https://telegra.ph">telegra.ph</a></label>
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
                                    <img v-lazy="getPhoto(menu.image).imageUrl">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <button
                        @click="submitMenus"
                        :disabled="menus.length===0"
                        class="btn btn-outline-primary p-3 w-100">Сохранить меню для заведения</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:["botId"],
    data() {
        return {
            menus:[],
            menuForm: {
                title:null,
                description:null,
                image:null,
                info_link:null,
                bot_id:null,
            }
        }
    },
    methods: {
        getPhoto(imgObject){
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto() {
            this.menuForm.image = null
        },
        removeItem(index) {
            this.menus.splice(index, 1)
        },
        submitMenus(){
            this.menus.forEach(menu=>{
                let data = new FormData();
                Object.keys(menu)
                    .forEach(key => {
                        const item = menu[key] || ''
                        if (typeof item === 'object')
                            data.append(key, JSON.stringify(item))
                        else
                            data.append(key, item)
                    });


                data.append('preview', menu.image);

                data.delete("image")

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
            this.menuForm.bot_id = this.botId
            this.menus.push(this.menuForm);
            this.$notify("Меню успешно добавлено в список");
            this.menuForm = {
                title:null,
                description:null,
                image:null,
                info_link:null,
                bot_id:null,
            }
        },
        onChangePhotos(e) {
            const file = e.target.files[0]
            this.menuForm.image = file
        },
    }
}
</script>

