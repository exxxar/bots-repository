<template>
    <div
        v-if="settings"
        class="container p-2 "
    >
        <div
            v-bind:style="colorTheme"
            class="container-slider-wrapper p-2" >
            <div class="d-flex overflow-auto category-slider gap-2">
                <!-- Все категории -->
                <button
                    type="button"
                    class="btn btn-primary flex-shrink-0"
                    @click="need_search=!need_search"
                >
                    <i v-if="!need_search" class="fa-solid fa-magnifying-glass"></i>
                    <i v-else class="fa-solid fa-xmark"></i>
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


        <template v-if="need_search">
            <div class="input-group mt-2">
                <div class="form-floating">
                    <input
                        @click="findProducts"
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



        </template>

    </div>
</template>

<script>

export default {
    data(){
      return {
          search:null,
          need_search:false,
      }
    },
    props:{
        settings: Object,
        categories: Array,
        collections: Array,
    },
    computed:{
        colorTheme() {
            const theme = document.querySelector("[data-bs-theme]").getAttribute('data-bs-theme')
            return "background-color:" + (theme === "light" ? "white" : "#212529");
        },
    },
    methods:{
        findProducts(){
          this.$emit("search",this.search)
        },
        selectCategory(item) {
            this.$emit('select', item)

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
                el.scrollIntoView({ behavior: 'auto', block: 'start' })
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
