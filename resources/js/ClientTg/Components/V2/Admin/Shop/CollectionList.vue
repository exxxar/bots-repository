<script setup>
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
import CollectionForm from "@/ClientTg/Components/V2/Admin/Shop/CollectionForm.vue";
import ProductCollectionView from "@/ClientTg/Components/V2/Shop/ProductCollectionView.vue";
</script>
<template>
    <form class="input-group mb-2"
          v-on:submit.prevent="loadProductCollections(0)">

        <div class="form-floating">
            <input type="search"
                   v-model="search"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Поиск подборок</label>
        </div>

        <button class="btn btn-outline-light text-primary"
                type="submit"
                id="button-addon2">
            Найти
        </button>
    </form>

    <div class="form-floating my-2">
        <select
            @click="loadProductCollections(0)"
            v-model="sort.param"
            class="form-select" id="floatingSelect" aria-label="Floating label select example">
            <option value="id">По id</option>
            <option value="title">По названию</option>
            >
            <option value="order_position">По позиции выдачи</option>
            <option value="is_active">По статусу</option>
            <option value="is_public">По публичности</option>
            <option value="discount">По величине скидки</option>
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

    <p>Всего подборок: <span v-if="paginate">{{ paginate.meta.total || 0 }}</span></p>

    <template v-if="filteredProductCollections.length>0">
        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 g-2">

            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item"

                        v-for="(item, index)  in filteredProductCollections">
                        <div class="d-flex justify-content-between">
                            <p class="mb-0">
                                <span class="badge bg-primary mr-2">  #{{ item.id }}</span>

                                <span
                                    @click="openModalEditor(item)"
                                    class="fw-bold">  {{ item.title }}</span>

                                <small
                                    class="fw-bold">  ({{ (item.products||[]).length }} товаров)</small>
                            </p>

                            <div class="dropdown">
                                <button class="btn btn-outline-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-bars"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li> <a href="javascript:void(0)"
                                            @click="openModalDemo(item)"
                                            class="dropdown-item"> <i class="fa-solid fa-eye"></i> Демо</a></li>
                                    <li>  <a href="javascript:void(0)"
                                             @click="openModalEditor(item)"
                                             class="dropdown-item"><i class="fa-regular fa-pen-to-square"></i> Редактировать</a></li>
                                    <li> <a href="javascript:void(0)"
                                          @click="openRemoveModal(item)"
                                            class="dropdown-item"> <i class="fa-solid fa-trash-can"></i> Удалить</a></li>

                                </ul>
                            </div>



                        </div>


                    </li>
                </ul>
            </div>
        </div>


        <Pagination
            v-on:pagination_page="nextProductCollections"
            v-if="paginate"
            :pagination="paginate"/>
    </template>

    <p class="alert alert-light" v-else>
        К сожалению, вы еще не добавили ни одной коллекции продуктов!
    </p>


    <nav class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
         style="border-radius:10px 10px 0px 0px;">
        <button
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            @click="openModalEditor(null)"
            class="btn btn-primary w-100 p-3 rounded-3 shadow-lg text-center ">

            Добавить подборку
        </button>
    </nav>

    <div class="modal fade" :id="'collection-items-show'" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Демонстрация</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 mb-5">
                    <ProductCollectionView
                        v-if="selected&&loaded"
                        :item="selected"></ProductCollectionView>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" :id="'collection-item-form'" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <CollectionForm
                        v-if="loaded"
                        v-on:callback="loadProductCollections(0)"
                        :item="selected"></CollectionForm>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" :id="'remove-collection-modal'" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Удаление категории</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body"
                     v-if="selected&&loaded">
                    Вы действительно хотите удалить <strong
                    class="text-primary fw-bold">{{ selected.title || '-' }}?</strong>
                </div>
                <div class="modal-footer"
                     v-if="selected&&loaded">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть
                    </button>
                    <button type="button"
                            @click="removeProductCollection(selected)"
                            class="btn btn-primary">Да, удалить
                    </button>
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
            search: null,
            collections: [],
            paginate: null,
            loaded:true,
            selected:null,
            sort: {
                param: 'id',
                direction: 'asc'
            },
        }
    },
    computed: {
        ...mapGetters(['getCollections', 'getCollectionsPaginateObject']),
        bot() {
            return window.currentBot
        },
        filteredProductCollections() {

            if (!this.search)
                return this.collections.filter(item=>!item.is_deleted)

            return this.collections
                .filter(collection =>
                    collection.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
            /*   .sort((a, b) =>
                   this.sort.direction === "asc" ?
                       a.order_position - b.order_position :
                       b.order_position - a.order_position)*/
        },
    },
    mounted() {
        this.loadProductCollections()

    },
    methods: {
        openModalDemo(item){
            this.loaded = false
            this.selected = item

            const modal = new bootstrap.Modal('#collection-items-show', {})
            modal.show()

            this.$nextTick(()=>{
                this.loaded = true
            })
        },
        openRemoveModal(item){
            this.loaded = false
            this.selected = item

            const modal = new bootstrap.Modal('#remove-collection-modal', {})
            modal.show()

            this.$nextTick(()=>{
                this.loaded = true
            })
        },
        openModalEditor(item){
            this.loaded = false
            this.selected = item

            const modal = new bootstrap.Modal('#collection-item-form', {})
            modal.show()

            this.$nextTick(()=>{
                this.loaded = true
            })
        },
        changeDirection(direction) {
            this.sort.direction = direction
            this.loadProductCollections(0)
        },
        updateCollection(item) {
            this.$store.dispatch("storeCollection", {
                collectionForm: item,
                bot_id: this.bot.id
            }).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Данные сохранены!",
                    type: 'success'
                });
            }).catch(err => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Ошибка сохранения данных!",
                    type: 'error'
                });
            })
        },

        selectProductCollection(product) {
            this.loaded = false
            this.selected = product
            this.$nextTick(()=>{
                this.loaded = true
            })
        },
        nextProductCollections(index) {
            this.loadProductCollections(index)
        },

        removeProductCollection(item) {
            item.is_deleted = true
            this.$store.dispatch("removeProductCollection", {
                collectionId: item.id
            }).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Продукт успешно удален!",
                    type: 'success'
                });

                this.loadProductCollections()

                document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())
            }).catch(err => {
                this.loadProductCollections()
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Ошибка удаления категории!",
                    type: 'error'
                });

                document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())
            })
        },

        loadProductCollections(page = 0) {
            return this.$store.dispatch("loadCollections", {
                dataObject: {
                    search: this.search,
                    order_by: this.sort.param,
                    direction: this.sort.direction
                },
                page: page,
                size: 10,
            }).then(() => {
                this.collections = this.getCollections
                this.paginate = this.getCollectionsPaginateObject
            })
        }
    }
}
</script>

