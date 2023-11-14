<template>


    <div :id="'notes-selector'" class="menu menu-box-bottom menu-box-detached rounded-m"
         data-menu-height="400" data-menu-effect="menu-over"
         style="height: 400px;display: block;">
        <h1 class="text-center mt-3 text-uppercase font-700">Выбор заметок</h1>

        <div class="list-group list-custom-small px-2" v-if="notes.length>0">
            <a  v-for="note in notes"
                @click="selectNote(note)"
                href="javascript:void(0)">
                <i class="fa font-13 fa-check color-green1-dark"></i>
                <span>{{ note.text || 'Не указан' }}</span>
            </a>

        </div>

    </div>


</template>
<script>
export default {
    data(){
      return {
          notes: [],
          param:null,
      }
    },
    mounted() {
        this.loadNotes();
        window.addEventListener("open-notes-modal", (e) => {
            this.param = e.detail.param
            $('#notes-selector').showMenu();
        } );
    },
    methods:{
        loadNotes() {
            this.loading = true
            this.$store.dispatch("loadNotes")
                .then(resp => {
                this.loading = false
            }).catch(() => {
                this.loading = false
            })
        },
        selectNote(item) {
            this.$botPages.selectNote(item, this.param)
            $('#notes-selector').hideMenu();
        },
    }
}
</script>

