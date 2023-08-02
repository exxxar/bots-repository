<script setup>
import Multiselect from '@vueform/multiselect'
import "@vueform/multiselect/themes/default.css"

</script>
<template>


    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link "
               @click="tab=0"
               v-bind:class="{'active':tab===0}"
               aria-current="page" href="#product-form-info">Информация о товаре</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=1"
               v-bind:class="{'active':tab===1}"
               href="#product-reviews">Комментарии</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=2"
               v-bind:class="{'active':tab===2}"
               href="#product-reviews">Заказы</a>
        </li>

    </ul>

    <div v-if="tab===0">
        <div

            class="row">
            <div class="col-md-12">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="productForm.in_stop_list_at"
                           type="checkbox"
                           value="false" id="in-stop-list">
                    <label class="form-check-label" for="in-stop-list">
                        Товар находится в стоп-листе
                    </label>
                </div>
            </div>

            <div class="col-md-12">
                <label for="product-category">Категории товара
                    <span v-for="(category, index) in productForm.categories"
                          class="badge bg-info mr-1">{{ category.label || category }}</span>
                </label>
                <Multiselect
                    placeholder="Выберите или создайте новую категорию"
                    id="product-category"
                    v-model="productForm.categories"
                    :options="options"
                    mode="multiple"
                    class="mb-3 mt-1"
                    :create-option="true"
                    :on-create="createCategory"
                    :searchable="true"
                />
            </div>

            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text"
                           v-model="productForm.article"
                           class="form-control" id="article" placeholder="name@example.com">
                    <label for="article">Артикул товара</label>
                </div>
            </div>
            <div class="col-md-6">

                <div class="form-floating mb-3">
                    <input type="text"
                           v-model="productForm.vk_product_id"
                           :disabled="true" class="form-control" id="vk-product-id"
                           placeholder="name@example.com">
                    <label for="vk-product-id">Идентификатор товара VK</label>
                </div>

            </div>

            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text"
                           v-model="productForm.title"
                           class="form-control" id="title" placeholder="name@example.com">
                    <label for="title">Названи товара</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating  mb-3">
                    <select class="form-select"
                            v-model="productForm.type"
                            id="type" aria-label="Floating label select example">
                        <option :value="type.value" v-for="(type, index) in types">{{ type.title }}</option>
                    </select>
                    <label for="type">Тип товара</label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-floating mb-3">
                <textarea class="form-control"
                          v-model="productForm.description"
                          placeholder="Leave a comment here" id="description"></textarea>
                    <label for="description">Описание товара</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="number"
                           v-model="productForm.current_price"
                           class="form-control" id="current-price" placeholder="name@example.com">
                    <label for="current-price">Актуальная цена</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="number"
                           v-model="productForm.old_price"
                           class="form-control" id="old-price" placeholder="name@example.com">
                    <label for="old-price">Старая цена</label>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6>Варианты товара <span
                            v-if="productForm.variants.length>0">({{ productForm.variants.length }})</span></h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <button
                                    type="button"
                                    class="btn btn-outline-info w-100" @click="addVariant">Добавить вариант
                                </button>
                            </div>
                            <div class="col-md-6 mb-3" v-for="(variant, index) in productForm.variants">
                                <div class="card w-100">
                                    <div class="card-body">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   v-model="productForm.variants[index].key"
                                                   class="form-control" :id="'variant-key-'+index"
                                                   placeholder="name@example.com">
                                            <label :for="'variant-key-'+index">Ключ</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   v-model="productForm.variants[index].value"
                                                   class="form-control" :id="'variant-value-'+index"
                                                   placeholder="name@example.com">
                                            <label :for="'variant-value-'+index">Значение</label>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button"
                                                class="btn btn-outline-danger"
                                                @click="removeVariant(index)"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6>Характеристики товара</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">

                            <div class="col-12">
                                <form
                                    v-on:submit.prevent="addSection"
                                    class="input-group mb-3">
                                    <input type="text"
                                           v-model="sectionForm.section"
                                           class="form-control border-info"
                                           placeholder="Название секции"
                                           aria-label="Recipient's username"
                                           aria-describedby="button-addon2" required>
                                    <button class="btn btn-outline-info" type="submit"
                                            id="button-addon2">Добавить секцию</button>
                                </form>



                                <span class="badge bg-info mr-1"
                                      v-for="(section, index) in sections">{{section}} <strong @click="removeSection(index)"><i class="fa-solid fa-xmark"></i></strong></span>

                            </div>
                           </div>
                        <div class="row">
                            <div class="col-md-4  mb-3" v-for="(option, index) in productForm.options">
                                <div class="card w-100">
                                    <div class="card-body">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   v-model="productForm.options[index].title"
                                                   class="form-control" :id="'option-title-'+index"
                                                   placeholder="name@example.com">
                                            <label :for="'option-title-'+index">Значение</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   v-model="productForm.options[index].value"
                                                   class="form-control" :id="'option-value-'+index"
                                                   placeholder="name@example.com">
                                            <label :for="'option-value-'+index">Значение</label>

                                        </div>

                                        <div class="form-floating mb-3">

                                            <select class="form-select"
                                                    v-model="productForm.options[index].section"
                                                    :id="'option-section-'+index"
                                                    aria-label="Floating label select example">
                                                <option :value="null">Без секции</option>
                                                <option :value="section" v-for="section in sections">{{ section }}</option>
                                            </select>
                                            <label :for="'option-section-'+index">Секция товара</label>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button"
                                                class="btn btn-outline-danger"
                                                @click="removeOption(index)"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button
                                    type="button"
                                    class="btn btn-outline-info w-100" @click="addOption">Добавить характеристику
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <h6>Фотографии товара</h6>
                <div class="photo-preview d-flex justify-content-start flex-wrap w-100">
                    <label for="location-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                        <span>+</span>
                        <input type="file" id="location-photos" multiple accept="image/*"
                               @change="onChangePhotos"
                               style="display:none;"/>

                    </label>
                    <div class="mb-2 img-preview" style="margin-right: 10px;"
                         v-for="(img, index) in productForm.images"
                         v-if="productForm.images">
                        <img v-lazy="getPhoto(img).imageUrl">
                        <div class="remove">
                            <a @click="removePhoto(index)">Удалить</a>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <div v-if="tab===1">
        Отзывы
    </div>

</template>
<script>
export default {
    props: ["bot", "item"],
    data() {
        return {
            sectionForm:{
                section: null,
            },
            tab:0,
            types: [
                {
                    title: 'Готовый продук',
                    value: 0,
                },
                {
                    title: 'Товар на вес',
                    value: 1,
                },
                {
                    title: 'Конструктор товара',
                    value: 2,
                },
            ],
            productCategories: [],
            sections: ["Общие характеристики", "Дополнительная информация"],
            options: [
                {value: 'test', label: 'test'},
                {value: 'test1', label: 'test1'},
                {value: 'test2', label: 'test2'},
            ],
            productForm: {
                article: null,
                vk_product_id: null,
                title: null,
                description: null,
                images: [],
                type: 0,
                old_price: null,
                current_price: null,
                variants: [],
                in_stop_list_at: null,
                bot_id: null,
                options: [],
                reviews: [],
                categories: [],
            }
        }
    },
    mounted() {
        this.loadProductCategories();

        if (this.item) {
            this.$nextTick(() => {
                this.productForm = {
                    article: this.item.article || null,
                    vk_product_id: this.item.vk_product_id || null,
                    title: this.item.title || null,
                    description: this.item.description || null,
                    images: this.item.images || [],
                    type: this.item.type || 0,
                    old_price: this.item.old_price || null,
                    current_price: this.item.current_price || null,
                    variants: this.item.variants || [],
                    in_stop_list_at: this.item.in_stop_list_at || null,
                    bot_id: this.item.bot_id || null,
                    options: this.item.options || [],
                    reviews: this.item.reviews || [],
                    categories: this.item.categories || [],
                }
            })
        }
    },
    methods: {
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.productForm.images.push(files[i])
        },
        loadProductCategories() {

        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removeSection(index){
            this.sections.splice(index, 1)
        },
        addSection(){
            this.sections.push(this.sectionForm.section)

            this.sectionForm.section = null
        },
        addOption() {
            if (!this.productForm.options)
                this.productForm.options = [];

            this.productForm.options.push({
                id: null,
                key: null,
                value: null,
                title: null,
                section: null,
            })
        },
        createCategory: async (option, select$) => {
            if (!event)
                return false;
           // this.options.push(option)

            // Async request (eg. for validating)
            await new Promise((resolve, reject) => {
                setTimeout(() => {
                    resolve()
                }, 1000)
            })

            // Modifying option label
            option.label = option.label + ' - добавленный'

            return option

        },

        addVariant() {
            if (!this.productForm.variants)
                this.productForm.variants = [];

            this.productForm.variants.push({
                key: null,
                value: null
            })
        },
        removeVariant(index) {
            this.productForm.variants.splice(index, 1)
        },
        removeOption(index) {
            this.productForm.options.splice(index, 1)
        },
        removePhoto(index) {
            this.productForm.images.splice(index, 1)
        },
    }
}
</script>
