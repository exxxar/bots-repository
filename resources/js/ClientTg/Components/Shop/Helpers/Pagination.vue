<template>

    <nav v-if="pagination.links && simple"  class="mt-4">
        <ul class="pagination pagination- justify-content-center">

            <li class="page-item">
                <button
                    type="button"
                    @click="first"
                    :disabled="pagination.links.first==null||pagination.meta.current_page === 1"
                    v-bind:class="{'bg-gray2-dark':pagination.links.first===null||pagination.meta.current_page === 1,'bg-black':pagination.links.first!=null}"
                    class="page-link rounded-xs color-white  shadow-xl border-0" tabindex="-1"
                    aria-disabled="true"><i class="fa-solid fa-angles-left"></i></button>
            </li>

            <li class="page-item">
                <button
                    type="button"
                    @click="prevPage"
                    :disabled="pagination.links.prev==null"
                    v-bind:class="{'bg-gray2-dark':pagination.links.prev==null,'bg-black':pagination.links.prev!=null}"
                    class="page-link rounded-xs color-white shadow-xl border-0" tabindex="-1"
                    aria-disabled="true"><i class="fa fa-angle-left"></i>
                </button>
            </li>

            <li class="page-item">
                <button
                    type="button"
                    class="btn btn-border rounded-xs color-white  shadow-xl border-0 border-highlight color-highlight">
                    {{pagination.meta.current_page}} / {{pagination.meta.last_page}}
                </button>
            </li>

            <li class="page-item">
                <button
                    @click="nextPage"
                    :disabled="pagination.links.next==null"
                    v-bind:class="{'bg-gray2-dark':pagination.links.next==null,'bg-black':pagination.links.next!=null}"
                    type="button"
                    class="page-link rounded-xs color-white  shadow-xl border-0"><i
                    class="fa fa-angle-right"></i></button>
            </li>

            <li class="page-item">
                <button
                    @click="last"
                    :disabled="pagination.links.last==null||pagination.meta.current_page === pagination.meta.last_page"
                    v-bind:class="{'bg-gray2-dark':pagination.links.last==null||pagination.meta.current_page === pagination.meta.last_page,'bg-black':pagination.links.last!=null}"
                    type="button"
                    class="page-link rounded-xs color-white  shadow-xl border-0">
                    <i class="fa-solid fa-angles-right"></i>
                </button>
            </li>
        </ul>
    </nav>
    <nav v-if="pagination.meta.total > 0 && !simple">
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
                    v-bind:class="{'bg-highlight':index===pagination.meta.current_page }"
                    class="page-link rounded-xs color-black shadow-xl border-0">{{ item.label }}

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
    props: ["pagination", "simple"],
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

            let index = parseInt(this.pagination.meta.current_page)


            return this.pagination.meta.links
        }
    },

    methods: {
        first() {
            this.$emit('pagination_page', 1)
        },
        last() {
            this.$emit('pagination_page', this.pagination.meta.last_page)
        },
        nextPage() {
            this.currentPage = this.pagination.meta.current_page + 1
            this.$emit('pagination_page', this.pagination.meta.current_page + 1)
        },
        page(index) {
            this.currentPage = index
            /*if (this.currentPage===index)
                return;*/

            window.scrollTo({
                top: 10,
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
