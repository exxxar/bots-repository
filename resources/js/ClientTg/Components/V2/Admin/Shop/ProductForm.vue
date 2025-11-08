<template>
    <div class="py-3">

        <form
            v-on:submit.prevent="submit">
            <div class="accordion" id="shop-product-item">
                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Базовая информация о товаре
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#shop-product-item">
                        <div class="accordion-body px-0">
                            <div class="form-check">
                                <input class="form-check-input"
                                       v-model="productForm.in_stop_list_at"
                                       type="checkbox"
                                       value="false" :id="'in-stop-list-'+(productForm.id||'new')">
                                <label class="form-check-label" :for="'in-stop-list-'+(productForm.id||'new')">
                                    Товар находится в стоп-листе
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input"
                                       v-model="productForm.not_for_delivery"
                                       type="checkbox"
                                       value="false" id="not_for_delivery">
                                <label class="form-check-label" for="not_for_delivery">
                                    Не для доставки
                                </label>
                            </div>

                            <div class="form-floating mb-2">
                                <input type="text"
                                       v-on:invalid="openInvalidTab(0)"
                                       v-model="productForm.title"
                                       class="form-control" id="title" placeholder="Название" required/>
                                <label for="title">Название товара</label>
                            </div>


                            <div class="form-floating mb-2">
                                <input type="number"
                                       min="0"
                                       max="5"
                                       step="0.5"
                                       v-model="productForm.rating"
                                       class="form-control" id="rating" placeholder="Рейтинг товара"/>
                                <label for="rating">Рейтинг товара</label>
                            </div>

                            <div class="form-floating mb-2">
                                <textarea class="form-control font-12"
                                          v-model="productForm.description"
                                          style="min-height:200px;"
                                          v-on:invalid="openInvalidTab(0)"
                                          placeholder="Напишите полное описание товара" id="description">

                                </textarea>

                                <label for="description">Описание товара</label>
                            </div>

                            <div class="form-floating mb-2">
                                <textarea class="form-control font-12"
                                          v-model="productForm.delivery_terms"
                                          style="min-height:200px;"
                                          v-on:invalid="openInvalidTab(0)"
                                          placeholder="Укажите особенности доставки товара" id="delivery_terms">

                                </textarea>
                                <label for="description"><i class="fa-solid fa-clock text-primary"></i> Особенности
                                    доставки товара</label>
                            </div>

                            <div class="form-floating mb-2">
                                <input type="text"
                                       v-model="productForm.article"
                                       class="form-control" id="article" placeholder="Название артикула">
                                <label for="article">Артикул товара</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select font-12"
                                        v-model="productForm.type"
                                        id="type" aria-label="Floating label select example">
                                    <option :value="type.value" v-for="(type, index) in types">{{ type.title }}</option>
                                </select>
                                <label for="type">Тип товара</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="number"
                                       v-on:invalid="openInvalidTab(0)"
                                       v-model="productForm.current_price"
                                       class="form-control" id="current-price" placeholder="0 руб." required>
                                <label for="current-price">Актуальная цена, руб</label>
                            </div>

                            <div class="form-floating mb-2">

                                <input type="number"
                                       v-model="productForm.old_price"
                                       class="form-control" id="old-price" placeholder="0 руб">
                                <label for="old-price">Старая цена, руб</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input"
                                       v-model="productForm.is_weight_product"
                                       type="checkbox"
                                       value="false" :id="'is_weight_product-'+(productForm.id||'new')">
                                <label class="form-check-label" :for="'is_weight_product-'+(productForm.id||'new')">
                                    <span v-bind:class="{'fw-bold text-primary':!productForm.is_weight_product}">Поштучный</span>
                                    \
                                    <span
                                        v-bind:class="{'fw-bold text-primary':productForm.is_weight_product}">весовой</span>
                                </label>
                            </div>

                            <template v-if="productForm.is_weight_product">
                                <p class="alert alert-light mb-2">
                                    Оставьте 0, если нет ограничения на максимальный вес
                                </p>
                                <div class="form-floating mb-2">
                                    <input type="number"
                                           v-model="productForm.weight_config.max"
                                           class="form-control" id="weight_config-max" placeholder="0 грамм">
                                    <label for="weight_config-max">Максимально для покупки, грамм</label>
                                </div>
                                <p class="alert alert-light mb-2">
                                    Оставьте 0, если нет ограничения на минимальный вес
                                </p>
                                <div class="form-floating mb-2">
                                    <input type="number"
                                           v-model="productForm.weight_config.min"
                                           class="form-control" id="weight_config-min" placeholder="0 грамм">
                                    <label for="weight_config-min">Минимально для покупки, грамм</label>
                                </div>
                                <p class="alert alert-light mb-2">
                                    Укажите с каким шагом можно купить данный товар.
                                </p>
                                <div class="form-floating mb-2">
                                    <input type="number"
                                           v-model="productForm.weight_config.step"
                                           class="form-control" id="weight_config-step" placeholder="0 грамм">
                                    <label for="weight_config-step">Шаг покупки, грамм</label>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-dimension" aria-expanded="false" aria-controls="collapseFive">
                            Параметры
                        </button>
                    </h2>
                    <div id="collapse-dimension" class="accordion-collapse collapse"
                         data-bs-parent="#shop-product-item">
                        <div class="accordion-body px-0">
                            <div class="form-floating mb-2">
                                <input type="text"
                                       v-model="productForm.dimension.height"
                                       class="form-control" id="vk-product-id"
                                       placeholder="Идентификатор">
                                <label for="vk-product-id">Высота, см</label>
                            </div>

                            <div class="form-floating mb-2">
                                <input type="text"
                                       v-model="productForm.dimension.width"
                                       class="form-control" id="vk-product-id"
                                       placeholder="Идентификатор">
                                <label for="vk-product-id">Ширина, см</label>
                            </div>

                            <div class="form-floating mb-2">
                                <input type="text"
                                       v-model="productForm.dimension.length"
                                       class="form-control" id="vk-product-id"
                                       placeholder="Идентификатор">
                                <label for="vk-product-id">Длина, см</label>
                            </div>

                            <div class="form-floating mb-2">
                                <input type="text"
                                       v-model="productForm.dimension.weight"
                                       class="form-control" id="vk-product-id"
                                       placeholder="Идентификатор">
                                <label for="vk-product-id">Вес, кг</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Изображения к товару
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#shop-product-item">
                        <div class="accordion-body px-0">
                            <div class="photo-preview d-flex justify-content-center flex-wrap w-100">
                                <label for="location-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                                    <span>+</span>
                                    <input type="file"
                                           v-on:invalid="openInvalidTab(1)"
                                           id="location-photos" multiple accept="image/*"
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
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Интеграции
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#shop-product-item">
                        <div class="accordion-body px-0">

                            <div class="form-floating mb-2">
                                <input type="text"
                                       v-model="productForm.vk_product_id"
                                       class="form-control" id="vk-product-id"
                                       placeholder="Идентификатор">
                                <label for="vk-product-id">Идентификатор товара VK</label>
                            </div>

                            <div class="form-floating mb-2">

                                <input type="text"
                                       v-model="productForm.iiko_article"
                                       class="form-control" id="iiko" placeholder="Название артикула IIKO">
                                <label for="iiko">Идентификатор в IIKO</label>
                            </div>

                            <div class="form-floating mb-2">

                                <input type="text"
                                       v-model="productForm.frontpad_article"
                                       class="form-control" id="fronpad" placeholder="Название артикула FrontPad">
                                <label for="fronpad">Идентификатор во FrontPad</label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Категории
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#shop-product-item">
                        <div class="accordion-body px-0">
                            <div class="d-flex flex-wrap mb-2" v-if="categories.length>0">
                <span
                    @click="selectCategory(cat)"
                    class="px-3 badge cursor-pointer mr-2 mb-1"
                    v-bind:class="{'bg-primary':productCategories.indexOf(cat.id)!==-1,'bg-light':productCategories.indexOf(cat.id)===-1}"
                    v-for="(cat, index) in categories"
                >
                 {{ cat.title }}
                </span>


                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour"
                                aria-expanded="false" aria-controls="collapseFour">
                            Характеристики
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#shop-product-item">
                        <div class="accordion-body px-0">
                            <form
                                v-on:submit.prevent="addSection"
                                class="form-floating mb-2">

                                <div class="input-group mb-3">
                                    <div class="form-floating">
                                        <input type="text"
                                               v-model="sectionForm.section"
                                               class="form-control" id="floatingInput" placeholder="name@example.com">
                                        <label for="floatingInput">Название секции</label>
                                    </div>
                                    <button class="btn btn-outline-light text-primary"><i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>


                            </form>


                            <div class="d-flex flex-wrap" v-if="sections.length>0">
                 <span class="badge bg-light p-2 mr-2 mb-1"
                       v-for="(section, index) in sections">{{ section }}   <i
                     @click="removeSection(index)"
                     class="fa-solid fa-trash ml-2 color-red2-dark"></i></span>
                            </div>

                            <div class="form-floating mb-2" v-for="(option, index) in productForm.options">

                                <div class="form-floating mb-2">

                                    <input type="text"
                                           v-model="productForm.options[index].title"
                                           class="form-control" :id="'option-title-'+index"
                                           placeholder="Характеристика">
                                    <label :for="'option-title-'+index"
                                           class="font-12 my-0 w-100 d-flex justify-content-between">
                                        Название характеристики
                                    </label>
                                </div>

                                <div class="form-floating mb-2">

                                    <input type="text"
                                           v-model="productForm.options[index].value"
                                           class="form-control" :id="'option-value-'+index"
                                           placeholder="Значение">
                                    <label :for="'option-value-'+index" class="font-12 my-0">Значение
                                        характеристики</label>

                                </div>

                                <div class="form-floating mb-2">

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
                                    <label :for="'option-section-'+index" class="font-12 my-0">Секция товара</label>
                                </div>


                            </div>

                            <button
                                type="button"
                                class="btn btn-outline-primary w-100"
                                @click="addOption">Добавить характеристику
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!--            <h6>Варианты товара <span
                            v-if="productForm.variants">({{ productForm.variants.length }})</span></h6>


                        <button
                            type="button"
                            class="btn btn-border btn-m btn-full mb-0 rounded-sm text-uppercase font-900 border-green2-dark color-green2-dark bg-theme w-100"
                            @click="addVariant">Добавить вариант
                        </button>

                        <div class="mb-2" v-for="(variant, index) in productForm.variants">

                            <div class="form-floating mb-2">

                                <input type="text"
                                       v-model="productForm.variants[index].key"
                                       class="form-control" :id="'variant-key-'+index"
                                       placeholder="Название варианта" required>
                                <label :for="'variant-key-'+index">Ключ</label>
                            </div>

                            <div class="form-floating mb-2">

                                <input type="text"
                                       v-model="productForm.variants[index].value"
                                       class="form-control" :id="'variant-value-'+index"
                                       placeholder="Величина\значение варианта" required>
                                <label :for="'variant-value-'+index">Значение</label>
                            </div>

                            <button type="button"
                                    class="btn btn-outline-danger w-100"
                                    @click="removeVariant(index)"><i class="fa-solid fa-trash-can"></i>
                                Удалить вариант
                            </button>
                        </div>

                        <hr>-->


            <button
                type="submit"
                class="btn btn-primary w-100 sticky-bottom bottom-0 p-3">
                Сохранить товар
            </button>
        </form>


        <!--        <button

                    @click="removeProduct"
                    v-if="productForm.id"
                    type="button"
                    class="btn btn-link my-3 w-100">Удалить
                    товар
                </button>-->

    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot", "modelValue"],
    data() {
        return {
            sectionForm: {
                section: null,
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
                rating: 5,
                description: null,
                delivery_terms: null,
                images: [],
                type: 1,
                old_price: null,
                current_price: null,
                variants: [],
                in_stop_list_at: null,
                bot_id: null,
                options: [],
                reviews: [],
                not_for_delivery: false,
                is_weight_product: false,
                weight_config: {
                    min:0,
                    max:0,
                    step:0,
                },
                dimension: {
                    width: 0,
                    height: 0,
                    length: 0,
                    weight: 0
                },
                categories: [],
            }
        }
    },
    computed: {
        ...mapGetters(['getCategories']),
    },
    mounted() {
        this.loadProductCategories();

        if (this.modelValue) {
            this.productForm = {
                id: this.modelValue.id || null,
                article: this.modelValue.article || null,
                frontpad_article: this.modelValue.frontpad_article || null,
                iiko_article: this.modelValue.iiko_article || null,
                vk_product_id: this.modelValue.vk_product_id || null,
                title: this.modelValue.title || null,
                rating: this.modelValue.rating || 5,
                description: this.modelValue.description || null,
                delivery_terms: this.modelValue.delivery_terms || null,
                images: this.modelValue.images || null,
                type: this.modelValue.type || 1,
                old_price: this.modelValue.old_price || null,
                current_price: this.modelValue.current_price || null,
                variants: this.modelValue.variants || null,
                in_stop_list_at: this.modelValue.in_stop_list_at || null,
                bot_id: this.modelValue.bot_id || null,
                options: this.modelValue.options || null,
                reviews: this.modelValue.reviews || null,
                not_for_delivery: this.modelValue.not_for_delivery || false,
                is_weight_product: this.modelValue.is_weight_product || false,
                weight_config: {
                    min: this.modelValue.weight_config?.min || 0,
                    max:this.modelValue.weight_config?.max || 0,
                    step:this.modelValue.weight_config?.step || 0,
                },
                dimension: {
                    width: this.modelValue.dimension?.width || 0,
                    height: this.modelValue.dimension?.height || 0,
                    length: this.modelValue.dimension?.length || 0,
                    weight: this.modelValue.dimension?.weight || 0,
                },
                // categories: this.modelValue.categories || null,
            }

            this.options = []
            this.modelValue.categories?.forEach(category => {
                this.productCategories.push(category.id)
            })
        }
    },
    methods: {
        openInvalidTab(tab) {
            let item = document.querySelector(`.accordion-button:nth-child(${tab + 1})`)
            item.classList = "accordion-button";

            let content = document.querySelector(`.accordion-collapse:nth-of-type(${tab + 1})`)
            content.classList = "accordion-collapse collapse show";
        },
        prepareCategoryName(category) {
            let cat = this.categories.find(item => item.value === category)
            return cat ? cat.label : category
        },
        removeProduct() {
            this.$emit("remove-product")
        },
        removeCategory(id) {
            let index = this.categories.findIndex(item => item.id === id) || null

            if (index)
                this.categories.splice(index, 1)

            this.$store.dispatch("removeProductCategory", {
                category_id: id
            }).then(() => {
                this.$notify({
                    title: 'Редактор товара',
                    text: 'Категория успешно удалена',
                    type: 'success'
                })

            }).catch(() => {
                this.$notify({
                    title: 'Редактор товара',
                    text: 'Ошибка удаления категории',
                    type: 'error'
                })

            })
        },

        selectCategory(item) {
            if (!this.productCategories)
                this.productCategories = []

            let index = this.productCategories.indexOf(item.id)
            if (index !== -1)
                this.productCategories.splice(index, 1)
            else
                this.productCategories.push(item.id)
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
                    title: 'Редактор товара',
                    text: 'Товар успешно сохранен',
                    type: 'success'
                })

                this.$emit("callback")
            }).catch(err => {
                this.$notify({
                    title: 'Редактор товара',
                    text: 'Ошибка сохранения товара',
                    type: 'error'
                })
                this.$emit("callback")
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
                delivery_terms: null,
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
                not_for_delivery: false,
                is_weight_product: false,
                weight_config: {
                    min:0,
                    max:0,
                    step:0,
                },
                dimension: {
                    width: 0,
                    height: 0,
                    length: 0,
                    weight: 0,
                },
            }
            this.photos = []
            this.removed_options = []
            this.productCategories = []
            this.productForm = formData
        }
    }
}
</script>
