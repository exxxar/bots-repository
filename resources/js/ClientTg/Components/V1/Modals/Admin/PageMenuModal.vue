<template>


    <div :id="'menu-page-editor'" class="menu menu-box-bottom menu-box-detached rounded-m"
         data-menu-height="305" data-menu-effect="menu-over"
         style="height: 275px;display: block;">
        <h1 class="text-center mt-4"><i class="fa fa-3x fa-check-circle color-green1-dark"></i></h1>
        <h1 class="text-center mt-3 text-uppercase font-700">Меню страницы</h1>

        <a href="javascript:void(0)"
           @click="duplicatePage"
           :data-dismiss="'menu-page-editor'"
           class="bg-highlight btn btn-m font-900 text-uppercase btn-center-xl mb-2">
            <i class="fa-solid fa-copy mr-1"></i> Дублировать страницу
        </a>

        <a href="javascript:void(0)"
           @click="removePage"
           :data-dismiss="'menu-page-editor'"
           class="bg-red2-dark btn btn-m font-900 text-uppercase btn-center-xl mb-2">
            <i class="fa-solid fa-trash mr-1"></i> Удалить страницу
        </a>

    </div>


</template>
<script>
export default {
    data(){
      return {
         id: null
      }
    },
    mounted() {

        window.addEventListener("open-page-menu-modal", (e) => {
            this.id = e.detail.id || null
            $('#menu-page-editor').showMenu();
        } );
    },
    methods:{
        duplicatePage() {
            this.loading = true
            this.$store.dispatch("duplicatePage", {
                dataObject: {
                    pageId: this.id
                },
            }).then(resp => {
                this.loading = false
                $('#menu-page-editor').hideMenu();
                this.$botPages.reloadPageList()
            }).catch(() => {
                this.loading = false
            })
        },
        removePage() {
            this.loading = true
            this.$store.dispatch("removePage", {
                dataObject: {
                    pageId: this.id
                },
            }).then(resp => {
                this.loading = false
                $('#menu-page-editor').hideMenu();
                this.$botPages.reloadPageList()
            }).catch(() => {
                this.loading = false
            })
        },
    }
}
</script>

