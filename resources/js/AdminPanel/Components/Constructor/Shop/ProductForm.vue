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
        <form
            v-on:submit.prevent="submit"
            class="row">
            <div class="col-md-8 d-flex align-items-center">
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

            <div class="col-md-4 d-flex justify-content-end">
                <a href="#" class="btn btn-link" @click="clearForm">Новый товар</a>
            </div>

            <div class="col-md-12 mb-3">
                <span class="badge bg-info mr-1 mb-1 cursor-pointer" v-for="(cat, index) in productCategories"
                      @click="removeProductCategory(index)">{{ prepareCategoryName(cat) }}</span>
                <div class="dropdown">
                    <button type="button" class="btn btn-outline-info w-100" data-bs-toggle="dropdown"
                            data-bs-display="static" aria-expanded="false">
                        <p class="mb-0">Выберите категорию</p>
                    </button>
                    <ul class="dropdown-menu w-100" style="overflow-y:scroll; height:300px;">

                        <div class="p-3">
                            <label for="exampleDropdownFormEmail1" class="form-label">Новая категория</label>
                            <input type="text" class="form-control"
                                   v-model="categoryForm.label"
                                   id="exampleDropdownFormEmail1"
                                   placeholder="Название категории">
                        </div>

                        <div class="p-3">
                            <button class="btn btn-primary w-100" type="button" @click="addCategory">Добавить
                                категорию
                            </button>
                        </div>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li v-for="item in filteredCategories">
                            <button class="dropdown-item" type="button" @click="selectCategory(item)">{{ item.label }}
                            </button>
                        </li>
                    </ul>
                </div>
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
                           v-model="productForm.frontpad_article"
                           class="form-control" id="article" placeholder="name@example.com">
                    <label for="article">Артикул товара FrontPad</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text"
                           v-model="productForm.iiko_article"
                           class="form-control" id="article" placeholder="name@example.com">
                    <label for="article">Артикул товара в IIKO</label>
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
                            v-if="productForm.variants">({{ productForm.variants.length }})</span></h6>
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
                                                   placeholder="name@example.com" required>
                                            <label :for="'variant-key-'+index">Ключ</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   v-model="productForm.variants[index].value"
                                                   class="form-control" :id="'variant-value-'+index"
                                                   placeholder="name@example.com" required>
                                            <label :for="'variant-value-'+index">Значение</label>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button"
                                                class="btn btn-outline-danger"
                                                @click="removeVariant(index)"><i class="fa-solid fa-trash-can"></i>
                                        </button>
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
                                            id="button-addon2">Добавить секцию
                                    </button>
                                </form>


                                <span class="badge bg-info mr-1"
                                      v-for="(section, index) in sections">{{ section }} <strong
                                    @click="removeSection(index)"><i class="fa-solid fa-xmark"></i></strong></span>

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
                                            <label :for="'option-title-'+index">Название характеристики</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   v-model="productForm.options[index].value"
                                                   class="form-control" :id="'option-value-'+index"
                                                   placeholder="name@example.com">
                                            <label :for="'option-value-'+index">Значение характеристики</label>

                                        </div>

                                        <div class="form-floating mb-3">

                                            <select class="form-select"
                                                    v-model="productForm.options[index].section"
                                                    :id="'option-section-'+index"
                                                    aria-label="Floating label select example">
                                                <option :value="null">Без секции</option>
                                                <option :value="section" v-for="section in sections">{{
                                                        section
                                                    }}
                                                </option>
                                            </select>
                                            <label :for="'option-section-'+index">Секция товара</label>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button"
                                                class="btn btn-outline-danger"
                                                @click="removeOption(index)"><i class="fa-solid fa-trash-can"></i>
                                        </button>
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
                        <img v-lazy="img">
                        <div class="remove">
                            <a @click="removeImage(index)">Удалить</a>
                        </div>
                    </div>

                    <div class="mb-2 img-preview" style="margin-right: 10px;"
                         v-for="(img, index) in photos"
                         v-if="photos.length>0">
                        <img v-lazy="getPhoto(img).imageUrl">
                        <div class="remove">
                            <a @click="removePhoto(index)">Удалить</a>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-12">
                <button
                    type="submit"
                    class="btn btn-outline-primary w-100 p-3">Сохранить
                </button>
            </div>
        </form>

    </div>

    <div v-if="tab===1">
        Отзывы
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot", "item"],
    data() {
        return {
            sectionForm: {
                section: null,
            },
            categoryForm: {
                label: null
            },
            tab: 0,
            types: [
                {
                    title: 'Готовый продукт',
                    value: 1,
                },
                {
                    title: 'Товар на вес',
                    value: 2,
                },
                {
                    title: 'Конструктор товара',
                    value: 3,
                },
            ],

            productCategories: [],
            sections: ["Общие характеристики", "Дополнительная информация"],
            photos: [],
            categories: [],
            removed_options: [],
            productForm: {
                article: null,
                vk_product_id: null,
                frontpad_article: null,
                iiko_article: null,

            title: null,
                description: null,
                images: [],
                type: 1,
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
    computed: {
        ...mapGetters(['getProductCategories']),
        filteredCategories() {
            if (this.productCategories.length === 0)
                return this.categories

            return this.categories.filter(item => this.productCategories.indexOf(item.value) === -1)
        }
    },
    mounted() {
        this.loadProductCategories();

        if (this.item) {
            this.$nextTick(() => {
                this.productForm = {
                    id: this.item.id || null,
                    article: this.item.article || null,
                    vk_product_id: this.item.vk_product_id || null,
                    frontpad_article: this.item.frontpad_article || null,
                    iiko_article: this.item.iiko_article || null,
                    title: this.item.title || null,
                    description: this.item.description || null,
                    images: this.item.images || null,
                    type: this.item.type || 1,
                    old_price: this.item.old_price || null,
                    current_price: this.item.current_price || null,
                    variants: this.item.variants || null,
                    in_stop_list_at: this.item.in_stop_list_at || null,
                    bot_id: this.item.bot_id || null,
                    options: this.item.options || null,
                    reviews: this.item.reviews || null,
                    // categories: this.item.categories || null,
                }

                this.options = []
                this.item.categories.forEach(category => {
                    this.productCategories.push(category.id)

                    // this.options.push({value: category.id, label: category.title})
                })

            })
        }
    },
    methods: {
        prepareCategoryName(category) {
            let cat = this.categories.find(item => item.value === category)

            console.log(cat)
            return cat ? cat.label : category
        },
        addCategory() {
            const category = this.categoryForm.label
            this.productCategories.push(category)
            this.categoryForm.label = null
        },
        selectCategory(item) {
            this.productCategories.push(item.value)
        },
        submit() {
            let data = new FormData();
            Object.keys(this.productForm)
                .forEach(key => {
                    const item = this.productForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            if (this.bot)
                data.append("bot_id", this.bot.id)

            if (this.photos.length > 0)
                for (let i = 0; i < this.photos.length; i++) {
                    data.append('photos[]', this.photos[i]);
                }

            if (this.removed_options.length > 0)
                data.append("removed_options", JSON.stringify(this.removed_options))

            if (this.productCategories.length > 0)
                data.append("categories", JSON.stringify(this.productCategories))

            this.$store.dispatch("saveProduct", {
                productForm: data
            }).then((response) => {
                this.load = true

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Продукт успешно сохранен!",
                    type: 'success'
                });

                this.clearForm()
                this.$emit("refresh")
            }).catch(err => {

            })

        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.photos.push(files[i])
        },
        loadProductCategories() {
            this.$store.dispatch("loadProductCategories", {
                dataObject: {
                    bot_id: this.bot.id
                }
            }).then(() => {
                const categories = this.getProductCategories
                categories.forEach(item => {
                    this.categories.push({value: item.id, label: item.title})
                })

            })
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removeSection(index) {
            this.sections.splice(index, 1)
        },
        addSection() {
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

        addVariant() {
            if (!this.productForm.variants)
                this.productForm.variants = [];

            this.productForm.variants.push({
                key: null,
                value: null
            })
        },
        removeProductCategory(index) {
            this.productCategories.splice(index, 1)
        },
        removeVariant(index) {
            this.productForm.variants.splice(index, 1)
        },
        removeOption(index) {
            if (this.productForm.options[index].id)
                this.removed_options.push(this.productForm.options[index].id)
            this.productForm.options.splice(index, 1)
        },
        removeImage(index) {
            this.productForm.images.splice(index, 1)
        },
        removePhoto(index) {
            this.photos.splice(index, 1)
        },
        clearForm() {
            const formData = {
                article: null,
                vk_product_id: null,
                title: null,
                description: null,
                images: [],
                type: 1,
                old_price: null,
                current_price: null,
                variants: [],
                in_stop_list_at: null,
                bot_id: null,
                options: [],
                reviews: [],
                categories: [],
            }
            this.photos = []
            this.removed_options = []
            this.productCategories = []
            this.productForm = formData
        }
    }
}
</script>
