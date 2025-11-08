<script setup>
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
import CategoryForm from "@/ClientTg/Components/V2/Admin/Shop/CategoryForm.vue";
</script>
<template>
    <form class="input-group mb-2"
          v-on:submit.prevent="loadProductCategories(0)">

        <div class="form-floating">
            <input type="search"
                   v-model="search"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Поиск категории товара</label>
        </div>

        <button class="btn btn-outline-light text-primary"
                type="submit"
                id="button-addon2">
            Найти
        </button>
    </form>

    <div class="form-floating my-2">
        <select
            @change="loadProductCategories(0)"
            v-model="sort.param"
            class="form-select" id="floatingSelect" aria-label="Floating label select example">
            <option value="id">По id</option>
            <option value="title">По названию</option>
            >
            <option value="order_position">По позиции выдачи</option>
            <option value="is_active">По статусу</option>
            <option value="updated_at">По дате добавления</option>
        </select>
        <label for="floatingSelect">Сортировать заказы по</label>
    </div>
    <p v-if="sort.param!=null">Направление сортировки:
        <span
            class="fw-bold"
            @click="changeDirection('desc')"
            v-if="sort.direction==='asc'">по возрастанию <i class="fa-solid fa-caret-up"></i></span>
        <span
            class="fw-bold"
            @click="changeDirection('asc')"
            v-if="sort.direction==='desc'">по убыванию <i class="fa-solid fa-caret-down"></i></span>
    </p>

    <div class="form-check form-switch mb-2">
        <input class="form-check-input"
               v-model="need_recommendation_config"
               type="checkbox" role="switch" id="need_recommendation_config">
        <label class="form-check-label"
               for="need_recommendation_config">Режим настройки рекомендаций</label>
    </div>

    <p>Всего категорий: <span v-if="paginate">{{ paginate.meta.total || 0 }}</span></p>

    <template v-if="filteredProductCategories.length>0">
        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 g-2">

            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item"

                        v-for="(item, index)  in filteredProductCategories">
                        <p class="mb-2 d-flex justify-content-between">
                            <span class="fw-bold">  {{ item.title }}</span>

                            <a href="javascript:void(0)"
                               @click="selectProductCategory(item)"
                               class="text-secondary"><i class="fa-regular fa-pen-to-square"></i></a>
                        </p>

                        <div class="input-group">
                            <button type="button"
                                    @click="changeCategoryStatus(item)"
                                    class="btn border-light">
                                <i
                                    v-if="item.is_active"
                                    class="fa-solid fa-eye text-primary"></i>

                                <i
                                    v-if="!item.is_active"
                                    class="fa-solid fa-eye-slash text-light"></i>
                            </button>

                            <div class="form-floating">
                                <input type="number"
                                       @change="updateCategory(item)"
                                       v-model="item.order_position"
                                       class="form-control border-light">
                                <label for="">Позиция в выдаче</label>
                            </div>


                            <button
                                title="Удалить товар"
                                @click="openRemoveModal(item)"

                                class="btn border-light ">
                                <i class="fa-solid fa-trash-can text-danger"></i>
                            </button>
                        </div>

                        <div class="row row-cols-1" v-if="need_recommendation_config">
                            <div class="col">



                                <div class="d-flex mt-2 mb-2 justify-content-center">
                                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio"
                                               @change="changeStatus(item.id, 0)"
                                               class="btn-check"
                                               :name="'config-category-recommendation-'+item.id"
                                               :id="'config-category-recommendation-1-'+item.id" autocomplete="off" checked>
                                        <label class="btn btn-outline-primary"
                                               :for="'config-category-recommendation-1-'+item.id">Отмена</label>

                                        <input type="radio"
                                               @change="changeStatus(item.id, 1)"
                                               :checked="recommendations.indexOf(item.id)!==-1"
                                               class="btn-check"
                                               :name="'config-category-recommendation-'+item.id"
                                               :id="'config-category-recommendation-2-'+item.id" autocomplete="off">
                                        <label class="btn btn-outline-primary"
                                             :for="'config-category-recommendation-2-'+item.id">Рекомендация</label>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </li>
                </ul>
            </div>
        </div>


        <Pagination
            v-on:pagination_page="nextProductCategories"
            v-if="paginate"
            :pagination="paginate"/>
    </template>

    <p class="alert alert-light" v-else>
        К сожалению, вы еще не добавили ни одной категории продуктов!
    </p>


    <nav class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
         style="border-radius:10px 10px 0px 0px;">
        <button
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            data-bs-toggle="modal" data-bs-target="#category-form"
            class="btn btn-primary w-100 p-3 rounded-3 shadow-lg text-center ">

            Добавить категорию
        </button>
    </nav>

    <div class="modal fade" :id="'category-edit-item-form'" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <CategoryForm
                        v-if="selectedCategory"
                        v-on:callback="categoryFormCallback"
                        :item="selectedCategory"></CategoryForm>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" :id="'remove-category-modal'" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Удаление категории</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body" v-if="selectedCategory">
                    Вы действительно хотите удалить <strong
                    class="text-primary fw-bold">{{ selectedCategory.title || '-' }}?</strong>
                </div>
                <div class="modal-footer" v-if="selectedCategory">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть
                    </button>
                    <button type="button"
                            @click="removeProductCategory(selectedCategory)"
                            class="btn btn-primary">Да, удалить
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="category-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <CategoryForm

                        v-on:callback="categoryFormCallback"></CategoryForm>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            selectedCategory: null,
            categoryEditorModal: null,
            removeModal: null,
            need_recommendation_config: false,
            search: null,
            categories: [],
            paginate: null,
            recommendations:[],
            sort: {
                param: 'id',
                direction: 'asc'
            },
        }
    },
    computed: {
        ...mapGetters(['getCategories', 'getCategoriesPaginateObject']),
        bot() {
            return window.currentBot
        },
        recommendations() {
            return this.bot.settings?.recommendation?.categories || []
        },
        filteredProductCategories() {

            if (!this.search)
                return this.categories/*.sort((a, b) =>
                    this.sort.direction === "asc" ?
                        a.order_position - b.order_position :
                        b.order_position - a.order_position)*/

            return this.categories.filter(category => category.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
            /*   .sort((a, b) =>
                   this.sort.direction === "asc" ?
                       a.order_position - b.order_position :
                       b.order_position - a.order_position)*/
        },
    },
    mounted() {
        this.recommendations = this.bot.settings?.recommendation?.categories || []
        this.loadProductCategories()
        this.categoryEditorModal = new bootstrap.Modal(document.getElementById('category-edit-item-form'), {})
        this.removeModal = new bootstrap.Modal(document.getElementById('remove-category-modal'), {})
    },
    methods: {
        changeStatus(categoryId, status) {
            this.$store.dispatch("changeCategoryRecommendationStatus", {
                category_id: categoryId,
                status: status
            }).then((resp) => {
                let data = resp
                this.recommendations = data.categories || []
            }).catch(() => {

            })
        },
        changeDirection(direction) {
            this.sort.direction = direction
            this.loadProductCategories(0)
        },
        updateCategory(item) {

            this.$store.dispatch("storeProductCategory", {
                category: item,
                bot_id: this.bot.id
            }).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Данные сохранены!",
                    type: 'success'
                });

                this.loadProductCategories()
                this.categoryEditorModal.hide()

            }).catch(err => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Ошибка сохранения данных!",
                    type: 'error'
                });

                this.categoryEditorModal.hide()
            })
        },
        changeCategoryStatus(item) {
            let index = this.categories.findIndex(category => item.id === category.id)
            if (index === -1)
                return;

            this.categories[index].is_active = !this.categories[index].is_active
            this.$store.dispatch("changeProductCategoryStatus", item.id).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Статус категории успешно изменен!",
                    type: 'success'
                });
            }).catch(err => {

            })
        },
        selectProductCategory(product) {


            this.selectedCategory = null

            this.$nextTick(() => {
                this.selectedCategory = product
                this.categoryEditorModal.show()
            })

            //  this.$emit("select", product)
        },
        nextProductCategories(index) {
            this.loadProductCategories(index)
        },
        openRemoveModal(item) {
            this.selectedCategory = null
            this.$nextTick(() => {
                this.selectedCategory = item
                this.removeModal.show()
            })

        },
        removeProductCategory(item) {
            this.$store.dispatch("removeProductCategory", {
                category_id: item.id
            }).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Продукт успешно удален!",
                    type: 'success'
                });

                this.loadProductCategories()
                this.removeModal.hide()
                this.selectedCategory = null
            }).catch(err => {
                this.loadProductCategories()
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Ошибка удаления категории!",
                    type: 'error'
                });
                this.selectedCategory = null
                this.removeModal.hide()
            })
        },
        categoryFormCallback(){
            this.categoryEditorModal.hide()
            this.loadProductCategories()
        },
        loadProductCategories(page = 0) {
            return this.$store.dispatch("loadCategories", {
                dataObject: {
                    bot_id: this.bot.id,
                    search: this.search,
                    order_by: this.sort.param,
                    direction: this.sort.direction
                },
                page: page,
                size: 50,
            }).then(() => {
                this.categories = this.getCategories
                this.paginate = this.getCategoriesPaginateObject
            })
        }
    }
}
</script>

