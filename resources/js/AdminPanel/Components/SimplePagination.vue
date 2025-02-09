<template>


    <nav v-if="pagination.links"  class="mt-4">
        <ul class="pagination pagination- justify-content-center mb-3">

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
    <p class="text-center mb-3" v-if="pagination.links" >
        <small style="font-weight: bold;">
            Страница {{pagination.meta.current_page}} из {{pagination.meta.last_page}}
        </small>
    </p>
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
