<template>
    <div
        v-if="settings"
        class="container p-2 "
    >
        <div
            v-if="categories.length>0||collections.length > 0"
            v-bind:style="colorTheme"
            class="container-slider-wrapper p-2">
            <div class="d-flex overflow-auto category-slider gap-2">
                <!-- Все категории -->
                <button
                    type="button"
                    class="btn btn-primary flex-shrink-0"
                    @click="openCategoryModal"
                >
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                <!-- Комбо-меню -->
                <button
                    type="button"
                    v-if="collections.length > 0"
                    class="btn btn-outline-warning flex-shrink-0"
                    @click="selectCategory({ id: 'combo' })"
                >
                    Комбо-меню <span class="badge bg-dark">{{ collections.length }}</span>
                </button>

                <!-- Категории -->
                <button
                    type="button"
                    v-for="item in categories"
                    :key="item.id"
                    class="btn btn-outline-secondary flex-shrink-0"
                    @click="selectCategory(item)"
                >
                    {{ item.title || 'Не указано' }}
                    <span class="badge bg-primary">{{ item.count || 0 }}</span>
                </button>
            </div>
        </div>


    </div>

    <!-- Modal -->
    <div class="modal fade" id="category-list-2-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Категории</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row  mb-2">
                        <div class="col">
                            <div class="input-group">
                                <div class="form-floating">
                                    <input

                                        type="search"
                                        v-model="search"
                                        class="form-control"
                                        id="searchInput"
                                        placeholder="Поиск по товарам"
                                    >
                                    <label for="searchInput">Поиск по товарам</label>
                                </div>
                                <button class="btn btn-primary" type="button" @click="findProducts">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cols-1">

                        <div class="col mb-2" v-for="item in categories">
                            <button
                                type="button"
                                :key="item.id"
                                class="btn py-3 btn-outline-secondary flex-shrink-0 w-100 d-flex justify-content-between"
                                @click="selectCategory(item)"
                            >
                                {{ item.title || 'Не указано' }}
                                <span class="badge bg-primary d-flex justify-content-center align-items-center">{{ item.count || 0 }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            search: null,
            need_search: false,
            category_modal:null,
        }
    },
    props: {
        settings: Object,
        categories: Array,
        collections: Array,
    },
    computed: {
        colorTheme() {
            const theme = document.querySelector("[data-bs-theme]").getAttribute('data-bs-theme')
            return "background-color:" + (theme === "light" ? "white" : "#212529");
        },
    },
    watch: {
        'need_search': {
            handler: function (newValue) {
                if (!this.need_search) {
                    this.search = null
                    this.findProducts()
                }

            },
            deep: true
        },
    },
    mounted(){
        this.category_modal = new bootstrap.Modal(document.getElementById('category-list-2-modal'), {
            backdrop:false
        })
    },
    methods: {
        openCategoryModal(){
          this.category_modal.show()
        },
        findProducts() {
            this.$emit("search", this.search)
            this.category_modal.hide()
        },
        selectCategory(item) {
            this.$emit('select', item)
            this.category_modal.hide()
            // Скролл к якорю
            let anchorId

            if (!item) {
                anchorId = 'all-categories'
            } else if (item.id === 'combo') {
                anchorId = 'combo-menu'
            } else {
                anchorId = `category-${item.id}`
            }

            // Найти якорь и прокрутить плавно
            const el = document.getElementById(anchorId)
            if (el) {
                el.scrollIntoView({behavior: 'auto', block: 'start'})
            }
        }
    }
}


</script>

<style scoped>
.container-slider-wrapper {
    background: white;
    border-radius: 9px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
