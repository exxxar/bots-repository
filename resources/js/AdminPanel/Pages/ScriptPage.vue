<script setup>
import Layout from "@/AdminPanel/Layouts/MainAdminLayout.vue";
import SlugForm from "@/AdminPanel/Components/Constructor/Slugs/SlugForm.vue";
import GlobalSlugList from "@/AdminPanel/Components/Constructor/Slugs/GlobalSlugList.vue";
</script>

<template>
    <Layout :active="6" :need-menu="true">
        <template #default>
            <div class="container">
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button"
                                    @click="step=0"
                                    v-bind:class="{'btn-primary':step===0,'btn-outline-primary':step!==0}"
                                    class="btn">Поиск скрипта
                            </button>
                            <button type="button"
                                    :disabled="slug==null"
                                    @click="step=1"
                                    v-bind:class="{'btn-primary':step===1,'btn-outline-primary':step!==1}"
                                    class="btn">Редактирование скрипта
                            </button>
                        </div>
                    </div>
                </div>


                <div  v-if="step===0">
                    <a
                        @click="reloadGlobalScripts"
                        href="javascript:void()">Обновить глобальные скрипты</a>
                    <GlobalSlugList
                        v-if="!load"
                        v-on:select="selectScript"/>
                </div>

                <div class="row" v-if="step===1">
                    <SlugForm :item="slug"/>
                </div>
            </div>
        </template>
    </Layout>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            slug:null,
            load: false,
            step: 0,
        }
    },
    computed: {

    },
    mounted() {


    },
    methods: {
        selectScript(slug){
            this.slug = slug
            this.step=1
        },
        reloadGlobalScripts(){
            this.load = true

            this.$store.dispatch("reloadGlobalScripts", {
            }).then(resp => {
                this.$notify({
                    title: "Конструктор команд",
                    text: "Глобальные скрипты успешно обновлены",
                    type: 'success'
                });

                this.$nextTick(()=>{
                    this.load = false
                })
            }).catch(()=>{
                this.$notify({
                    title: "Конструктор команд",
                    text: "Ошибка обновления глобальных скриптов",
                    type: 'error'
                });
            })


        }

    }
}
</script>
