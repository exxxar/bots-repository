<template>

    <nav aria-label="pagination-demo">
        <ul class="pagination pagination- justify-content-center">
            <li class="page-item">
                <a
                    type="button"
                    @click="prevPage"
                    v-bind:class="{'disabled':pagination.links.prev===null}"
                    class="page-link rounded-xs color-white bg-black shadow-xl border-0" tabindex="-1"
                    aria-disabled="true"><i class="fa fa-angle-left"></i></a>
            </li>
            <li
                :key="'paginate'+index"
                v-for="(item, index) in filteredLinks"
                @click.prevent="page(index)"
                v-bind:class="{'active':index===pagination.meta.current_page }"
                class="page-item">

                <a
                    href="#/products"
                    v-if="index!==0&&index!==filteredLinks.length-1"
                    class="page-link rounded-xs color-black bg-theme shadow-xl border-0">{{ item.label }}

                    <span class="sr-only" v-if="index===pagination.meta.current_page">(current)</span>
                </a>
            </li>

            <li class="page-item">
                <button
                    @click="nextPage"
                    v-bind:class="{'disabled':pagination.links.next===null}"
                    type="button"
                    class="page-link rounded-xs color-white bg-black shadow-xl border-0"><i
                    class="fa fa-angle-right"></i></button>
            </li>
        </ul>
    </nav>

</template>
<script>


export default {
    props: ["pagination"],
    data() {
        return {
            currentPage: 1,
        }
    },
    computed: {
        hasPagination() {
            if (this.pagination === null || !this.pagination)
                return false;

            if (this.pagination.meta.links[0].active === false
                && this.pagination.meta.links[this.pagination.meta.links.length - 1].active === false)
                return false
            return true;
        },
        filteredLinks() {
            if (!this.pagination)
                return [];

            let index = parseInt(this.pagination.meta.links.find(item => item.active === true).label)

            return this.pagination.meta.links
        }
    },

    methods: {
        nextPage() {
            this.currentPage = this.pagination.meta.current_page + 1
            this.$emit('pagination_page', this.pagination.meta.current_page + 1)
        },
        page(index) {
            this.currentPage = index
            /*if (this.currentPage===index)
                return;*/

            window.scrollTo({
                top: 500,
                behavior: "smooth"
            })

            this.$emit('pagination_page', index)
        },
        prevPage() {
            if (this.currentPage === 1)
                return

            this.currentPage = this.pagination.meta.current_page - 1
            this.$emit('pagination_page', this.pagination.meta.current_page - 1)
        }
    },

}
</script>
<style>
.page-item {
    height: 100%;
}
</style>
