<script setup>
import SimpleProductList from "@/ClientTg/Components/V2/Admin/Shop/SimpleProductList.vue";
</script>
<template>
    <form v-on:submit.prevent="storeCollection" class="pb-5 mb-5">

        <label :for="'photos-'+uuid"
               v-if="!photo&&!collectionForm.image"
               style="font-size:14px;min-height:300px;"
               class="photo-loader my-2 d-flex justify-content-center align-items-center flex-column text-center w-100">
            <i class="fa-solid fa-image text-primary"></i>
            <input type="file" :id="'photos-'+uuid"
                   accept="image/*" @change="onChangePhotos"
                   style="display:none;"/>
            <p class="mt-3 font-16 text-primary"> Нажмите для выбора фотографии</p>
        </label>

        <div
            style="font-size:14px;min-height:300px;"
            class="img-preview w-100 overflow-hidden my-2"
            v-if="collectionForm.image">
        <img
            v-if="collectionForm.image"
            class="w-100 object-fit-cover"
            v-lazy="'/images-by-company-id/'+bot.company_id+'/'+collectionForm.image" alt="">
            <div class="remove">
                <a @click="collectionForm.image = null"
                   class="btn btn-outline-light rounded-5 p-3  shadow-s">
                    <i class="fa-regular fa-trash-can mr-2 text-primary"></i> Удалить фото</a>
            </div>
        </div>

        <div
            style="font-size:14px;min-height:300px;"
            class="img-preview w-100 overflow-hidden my-2"
            v-if="photo&&!collectionForm.image">
            <img v-lazy="getPhoto().imageUrl" class="w-100 object-fit-cover">
            <div class="remove">
                <a @click="removePhoto"
                   class="btn btn-outline-light rounded-5 p-3  shadow-s">
                    <i class="fa-regular fa-trash-can mr-2 text-primary"></i> Удалить фото</a>
            </div>
        </div>

        <div class="form-floating mb-2">
            <input type="text"
                   v-model="collectionForm.title"
                   class="form-control" id="floatingInput"
                   placeholder="name@example.com" required>
            <label for="floatingInput">Название подборки</label>
        </div>

        <div class="form-floating mb-2">
            <textarea class="form-control"
                      v-model="collectionForm.description"
                      placeholder="Leave a comment here"
                      style="min-height:150px;"
                      required
                      id="floatingTextarea"></textarea>
            <label for="floatingTextarea">Описание</label>
        </div>

        <div class="form-floating mb-2">
            <input type="number"
                   min="0"
                   max="100"
                   v-model="collectionForm.discount"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Скидка на подборку, %</label>
        </div>

        <div class="form-floating mb-3">
            <input type="number"
                   v-model="collectionForm.order_position"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Позиция в выдаче</label>
        </div>


        <p class="alert alert-light mb-2">
            Делает подборку доступной для всех пользователей, позволяя им формировать заказ.
        </p>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="collectionForm.is_active"
                   role="switch" :id="'collectionForm_is_active-'+uuid">
            <label class="form-check-label" :for="'collectionForm_is_active-'+uuid">Отображение подборки: <span
                v-bind:class="{'text-primary fw-bold':collectionForm.is_active}">вкл</span> \ <span
                v-bind:class="{'text-primary fw-bold':!collectionForm.is_active}">выкл</span></label>
        </div>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="collectionForm.is_public"
                   role="switch" :id="'collectionForm_is_public-'+uuid">
            <label class="form-check-label" :for="'collectionForm_is_public-'+uuid">Является публичной: <span
                v-bind:class="{'text-primary fw-bold':collectionForm.is_public}">да</span> \ <span
                v-bind:class="{'text-primary fw-bold':!collectionForm.is_public}">нет</span></label>
        </div>


        <button
            type="button"
            @click="show_products = !show_products"
            class="btn btn-outline-primary p-2 w-100 mb-2">
            <i class="fa-solid fa-chevron-up" v-if="!show_products"></i>
            <i class="fa-solid fa-chevron-down" v-else></i>
            Открыть выбор продуктов
        </button>


        <div
            v-if="show_products">

            <p class="alert alert-light">
                При выборе товаров из одной и той же категории пользователь сможет указать один из этих продуктов по
                своему усмотрению.
                Если выбран флаг <span class="text-primary fw-bold">"все в категории"</span>, то у пользователя не будет
                возможности выбирать, ему можно будет заказать всё как есть.
            </p>

            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="collectionForm.config.choose_all_in_category"
                       role="switch" id="collectionForm_choose_all_in_category">
                <label class="form-check-label" for="collectionForm_choose_all_in_category">Все в категории: <span
                    v-bind:class="{'text-primary fw-bold':collectionForm.config.choose_all_in_category}">вкл</span> \
                    <span
                        v-bind:class="{'text-primary fw-bold':!collectionForm.config.choose_all_in_category}">выкл</span></label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="collectionForm.config.can_skip_categories"
                       role="switch" id="collectionForm_choose_all_in_category">
                <label class="form-check-label" for="collectionForm_choose_all_in_category">Разрешить пропуск категорий: <span
                    v-bind:class="{'text-primary fw-bold':collectionForm.config.can_skip_categories}">вкл</span> \
                    <span
                        v-bind:class="{'text-primary fw-bold':!collectionForm.config.can_skip_categories}">выкл</span></label>
            </div>

            <SimpleProductList
                :selected="mappedProducts"
                v-on:select="selectProduct"
            />

        </div>

        <nav class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
             style="border-radius:10px 10px 0px 0px;">
            <button
                class="btn btn-primary w-100 p-3"
                type="submit">
                <span v-if="collectionForm.id">Обновить подборку</span>
                <span v-else>Добавить подборку</span>
            </button>
        </nav>

    </form>

</template>
<script>

import {v4 as uuidv4} from "uuid";

export default {
    props: ["item"],
    data() {
        return {
            photo: null,
            show_products: false,
            collectionForm: {
                id: null,
                title: null,
                order_position: 0,
                image: null,
                description: null,
                is_public: true,
                is_active: true,
                discount: 0,
                products: [],
                config: {
                    choose_all_in_category: false,
                    can_skip_categories:false,
                    //выбор из нескольких в категории
                }
            },
        }
    },
    computed: {
        bot() {
            return window.currentBot
        },
        mappedProducts() {
            return this.collectionForm.products.map(({id}) => id)
        },
        uuid() {
            const data = uuidv4();
            return data
        }
    },
    mounted() {
        if (this.item) {
            this.$nextTick(() => {
                this.collectionForm.id = this.item.id || null
                this.collectionForm.title = this.item.title || null
                this.collectionForm.order_position = this.item.order_position || 0
                this.collectionForm.image = this.item.image || null
                this.collectionForm.description = this.item.description || null
                this.collectionForm.discount = this.item.discount || 0
                this.collectionForm.is_public = this.item.is_public || true
                this.collectionForm.is_active = this.item.is_active || true
                this.collectionForm.config = this.item.config || {}
                this.collectionForm.products = this.item.products || []

                if ((this.item.products || []).length > 0)
                    this.show_products = true
            })
        }
    },
    methods: {
        removePhoto() {
            this.photo = null
        },
        getPhoto() {
            return {imageUrl: URL.createObjectURL(this.photo)}
        },
        onChangePhotos(e) {
            const files = e.target.files
            this.photo = files[0]
            //this.success = true
        },
        storeCollection() {

            let data = new FormData();

            Object.keys(this.collectionForm)
                .forEach(key => {
                    const item = this.collectionForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.photo)
                data.append('photo', this.photo);

            this.$store.dispatch("storeCollection", {
                collectionForm: data
            }).then(() => {
                this.$notify({
                    title: "Коллекция товаров",
                    text: "Данные успешно сохранены!",
                    type: 'success'
                });

                this.$emit("callback")
                document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            }).catch(() => {
                this.$notify({
                    title: "Коллекция товаров",
                    text: "Ошибка сохранения категории!",
                    type: 'error'
                });

                document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())
            })
        },
        selectProduct(product) {

            let index = this.collectionForm.products.findIndex(item => item.id == product.id)

            if (index === -1)
                this.collectionForm.products.push(product)
            else
                this.collectionForm.products.splice(index, 1)

        },
    }
}
</script>
<style lang="scss" scoped>

.img-preview {
    position: relative;

    .photo-loader {
        width: 100px;
        height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        background: transparent;
        border-radius: 10px;
        border: 1px lightgray solid;
        position: relative;
    }

    .remove {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #0000009e;
    }
}
</style>
