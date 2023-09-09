<template>

    <div class="card card-style bg-theme pb-0">
        <div class="content">
            <form
                v-on:submit.prevent="submit">
                <div class="d-flex justify-content-between flex-wrap">

                    <a href="javascript:void(0)"
                       class="btn btn-m btn-full mb-2 rounded-xs text-uppercase font-900 shadow-s bg-green2-light w-100"
                       @click="clearForm">
                        <i class="fa-solid fa-plus mr-1"></i> Новый товар
                    </a>

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

                <div class="divider divider-small my-3 bg-highlight "></div>

                <div class="d-flex flex-wrap" v-if="categories.length>0">
                       <p class="px-3 font-10 rounded mr-1 mb-1 cursor-pointer"
                             v-bind:class="{'bg-info text-white':productCategories.indexOf(cat.id)!=-1}"
                             v-for="(cat, index) in categories"
                            >
                           <span @click="selectCategory(cat)">{{ cat.title }}</span>
                           <i
                           @click="removeCategory(cat.id)"
                           class="fa-solid fa-trash ml-2 color-red2-dark"></i>

                       </p>


                </div>


                <div class="mb-2">

                    <label for="exampleDropdownFormEmail1" class="form-label">Новая категория</label>
                    <input type="text" class="form-control"
                           v-model="categoryForm.label"
                           id="exampleDropdownFormEmail1"
                           placeholder="Название категории">

                </div>

                <button class="btn btn-border btn-m btn-full mb-0 rounded-sm text-uppercase font-900 border-green2-dark color-green2-dark bg-theme w-100" type="button" @click="addCategory">Добавить
                    категорию
                </button>

                <div class="divider divider-small my-3 bg-highlight "></div>

                <div class="mb-2">
                    <label for="vk-product-id">Идентификатор товара VK</label>
                    <input type="text"
                           v-model="productForm.vk_product_id"
                           :disabled="true" class="form-control" id="vk-product-id"
                           placeholder="Идентификатор">

                </div>

                <div class="mb-2">
                    <label for="article">Артикул товара</label>
                    <input type="text"
                           v-model="productForm.article"
                           class="form-control" id="article" placeholder="Название артикула">
                </div>



                <div class=" mb-2">
                    <label for="title">Названи товара</label>
                    <input type="text"
                           v-model="productForm.title"
                           class="form-control" id="title" placeholder="Название"/>
                </div>

                <div class="mb-2">
                    <label for="type">Тип товара</label>
                    <select class="form-control font-12"
                            v-model="productForm.type"
                            id="type" aria-label="Floating label select example">
                        <option :value="type.value" v-for="(type, index) in types">{{ type.title }}</option>
                    </select>
                </div>
                <div class=" mb-2">
                    <label for="description">Описание товара</label>
                    <textarea class="form-control font-12"
                              v-model="productForm.description"
                              placeholder="Напишие полное описание товара" id="description"></textarea>

                </div>

                <div class="mb-2">
                    <label for="current-price">Актуальная цена, руб</label>
                    <input type="number"
                           v-model="productForm.current_price"
                           class="form-control" id="current-price" placeholder="0 руб." required>

                </div>

                <div class="mb-2">
                    <label for="old-price">Старая цена, руб</label>
                    <input type="number"
                           v-model="productForm.old_price"
                           class="form-control" id="old-price" placeholder="0 руб">
                </div>
                <div class="divider divider-small my-3 bg-highlight "></div>
                <h6>Варианты товара <span
                    v-if="productForm.variants">({{ productForm.variants.length }})</span></h6>


                <button
                    type="button"
                    class="btn btn-border btn-m btn-full mb-0 rounded-sm text-uppercase font-900 border-green2-dark color-green2-dark bg-theme w-100" @click="addVariant">Добавить вариант
                </button>

                <div class="mb-2" v-for="(variant, index) in productForm.variants">

                    <div class="mb-2">
                        <label :for="'variant-key-'+index">Ключ</label>
                        <input type="text"
                               v-model="productForm.variants[index].key"
                               class="form-control" :id="'variant-key-'+index"
                               placeholder="Название варианта" required>

                    </div>

                    <div class="mb-2">
                        <label :for="'variant-value-'+index">Значение</label>
                        <input type="text"
                               v-model="productForm.variants[index].value"
                               class="form-control" :id="'variant-value-'+index"
                               placeholder="Величина\значение варианта" required>

                    </div>

                    <button type="button"
                            class="btn btn-outline-danger w-100"
                            @click="removeVariant(index)"><i class="fa-solid fa-trash-can"></i>
                        Удалить вариант
                    </button>
                </div>

                <div class="divider divider-small my-3 bg-highlight "></div>
                <h6>Характеристики товара</h6>

                <div class="mb-2">


                    <form
                        v-on:submit.prevent="addSection"
                        class="mb-2">
                        <input type="text"
                               v-model="sectionForm.section"
                               class="form-control  mb-2"
                               placeholder="Название секции"
                               aria-label="Recipient's username"
                               aria-describedby="button-addon2" required>
                        <button class="btn btn-border btn-m btn-full mb-0 rounded-sm text-uppercase font-900 border-green2-dark color-green2-dark bg-theme w-100" type="submit"
                                id="button-addon2">Добавить секцию
                        </button>
                    </form>



                    <div class="d-flex flex-wrap" v-if="sections.length>0">
                        <p class="mb-0">Для удаления характеристики - нажми на неё</p>
 <span @click="removeSection(index)" class="px-3 text-white rounded bg-info mr-1 mb-1 cursor-pointer"
       v-for="(section, index) in sections">{{ section }}</span>
                    </div>


                </div>

                <div class="mb-2" v-for="(option, index) in productForm.options">

                    <div class="form-floating mb-2">
                        <input type="text"
                               v-model="productForm.options[index].title"
                               class="form-control" :id="'option-title-'+index"
                               placeholder="Характеристика">
                        <label :for="'option-title-'+index">Название характеристики</label>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="text"
                               v-model="productForm.options[index].value"
                               class="form-control" :id="'option-value-'+index"
                               placeholder="Значение">
                        <label :for="'option-value-'+index">Значение характеристики</label>

                    </div>

                    <div class="form-floating mb-2">

                        <select class="form-control font-12"
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

                    <button type="button"
                            class="btn btn-outline-danger w-100"
                            @click="removeOption(index)">
                        <i class="fa-solid fa-trash-can mr-1"></i>
                        Удалить секцию товара
                    </button>


                </div>

                <button
                    type="button"
                    class="btn btn-border btn-m btn-full mb-0 rounded-sm text-uppercase font-900 border-green2-dark color-green2-dark bg-theme w-100" @click="addOption">Добавить характеристику
                </button>

                <div class="divider divider-small my-3 bg-highlight "></div>
                <div class=" mb-2">
                    <h6>Фотографии товара</h6>
                    <div class="photo-preview d-flex justify-content-center flex-wrap w-100">
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
                                <a href="javascript:void(0)" @click="removeImage(index)">Удалить</a>
                            </div>
                        </div>

                        <div class="mb-2 img-preview" style="margin-right: 10px;"
                             v-for="(img, index) in photos"
                             v-if="photos.length>0">
                            <img v-lazy="getPhoto(img).imageUrl">
                            <div class="remove">
                                <a href="javascript:void(0)" @click="removePhoto(index)">Удалить</a>
                            </div>
                        </div>

                    </div>

                </div>

                <button
                    type="submit"
                    class="btn btn-m btn-full mb-0 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark w-100">Сохранить
                </button>
            </form>
        </div>
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
        ...mapGetters(['getCategories']),


    },
    mounted() {
        this.loadProductCategories();

        if (this.item) {
            this.$nextTick(() => {

                console.log("Test")
                this.productForm = {
                    id: this.item.id || null,
                    article: this.item.article || null,
                    vk_product_id: this.item.vk_product_id || null,
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
        removeCategory(id){
            let index  = this.categories.findIndex(item=>item.id===id)||null

            if (index)
                this.categories.splice(index, 1)

            this.$store.dispatch("removeProductCategory",{
                category_id: id
            }).then(()=>{
                this.loadProductCategories()
            })
        },
        addCategory() {
            const category = this.categoryForm.label
            this.categoryForm.label = null
            this.$store.dispatch("addProductCategory",{
                category: category
            }).then(()=>{
                this.loadProductCategories()
            })
        },
        selectCategory(item) {
            this.productCategories.push(item.id)
            console.log("productCategories",this.productCategories)
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

                this.$botNotification.notification(
                    "Конструктор ботов",
                    "Продукт успешно сохранен!",
                );

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
            this.$store.dispatch("loadCategories").then(() => {
                this.categories = this.getCategories
                /* const categories = this.getProductCategories
                 categories.forEach(item => {
                     this.categories.push({value: item.id, label: item.title})
                 })*/

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
