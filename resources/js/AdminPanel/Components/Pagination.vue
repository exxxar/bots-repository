<template>
    <div class="row d-flex justify-content-center" v-if="pagination">

        <div class="col-lg-6 dt-pagination d-flex justify-content-center align-items-center" v-if="pagination.links">

            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link"
                           v-bind:class="{'disabled':pagination.links.prev===null}"
                           @click="prevPage" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item"
                        :key="'paginate'+index"
                        v-for="(item, index) in filteredLinks"
                        @click="page(index)"
                        v-bind:class="{'active':index===pagination.meta.current_page }">
                        <a class="page-link"

                           v-if="index!==0&&index!==filteredLinks.length-1"
                           href="#">{{item.label}}</a>
                    </li>

                    <li class="page-item">
                        <a class="page-link"
                           v-bind:class="{'disabled':pagination.links.next===null}"
                           @click="nextPage"
                           aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>

    </div>
</template>
<script>


export default {
    props:["pagination"],
    data(){
        return {
            currentPage:1,
        }
    },
    computed: {
        hasPagination(){
          if (this.pagination===null||!this.pagination)
              return false;

          if (this.pagination.meta.links[0].active===false
              &&this.pagination.meta.links[this.pagination.meta.links.length-1].active===false)
              return false
            return true;
        },
        filteredLinks(){
            if (!this.pagination)
                return [];

            let index = parseInt(this.pagination.meta.current_page)

            return this.pagination.meta.links
        }
    },

    methods:{
        nextPage(){
            this.currentPage = this.pagination.meta.current_page+1
            this.$emit('pagination_page', this.pagination.meta.current_page+1)
        },
        page(index){
            this.currentPage = index
            /*if (this.currentPage===index)
                return;*/

            window.scrollTo({
                top: 500,
                behavior: "smooth"
            })

            console.log("Test")

            this.$emit('pagination_page', index)
        },
        prevPage(){
            if (this.currentPage===1)
                return

            this.currentPage = this.pagination.meta.current_page-1
            this.$emit('pagination_page', this.pagination.meta.current_page-1)
        }
    },

}
</script>
<style>
.page-item {
    height: 100%;
}
</style>
