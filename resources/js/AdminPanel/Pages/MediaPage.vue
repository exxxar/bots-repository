<script setup>
import Layout from "@/AdminPanel/Layouts/MainAdminLayout.vue";

defineProps({
    files: {
        default: [],
    },
});

</script>

<template>
    <Layout :active="4" :need-menu="true">
        <template #default>
            <div class="container">
                <div class="row">
                    <div class="col-3 mb-2" v-for="(file, index) in files">
                        <div class="card">
                            <div class="card-header">
                                <p>{{file}}</p>
                            </div>
                            <div class="card-body">
                                <img v-lazy="'/'+file" alt="" class="w-100 object-cover">
                            </div>
                            <div class="card-footer d-flex">
                                <div class="dropdown">
                                    <button class="btn btn-outline-info" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-bars"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" @click.prevent="removeFile(file, index)">Удалить файл</a></li>
                                        <li><a class="dropdown-item" href="#">Получить ссылку на файл</a></li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </template>
    </Layout>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data(){
        return {
            load:false,
            step:0,
        }
    },
    computed: {

    },
    mounted() {

    },
    methods:{
        removeFile(file, index){
            this.files.splice(index, 1)

            this.$store.dispatch("removeFile", {
                file_path: file,
            }).then(()=>{
                this.$notify("Файл удалён");
            })
        }
    }
}
</script>
