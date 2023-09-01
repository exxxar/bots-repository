<template>

    <div class="card rounded-l shadow-xl bg-18 mb-3" style="height: 320px;">
        <div class="card-top mt-3 mr-3">
            <a href="javascript:void(0)" class="icon icon-s rounded-l shadow-xl bg-red2-dark color-white float-right ml-2 mr-2"><i class="fa fa-heart"></i></a>
            <a href="javascript:void(0)" data-menu="menu-share" class="icon icon-s rounded-l shadow-xl bg-highlight color-white float-right"><i class="fa fa-shopping-cart"></i></a>
        </div>
        <div class="card-bottom mb-3">
            <div class="content mb-0">
                <div class="d-flex">
                    <div>
                        <p class="mb-n1 font-600 color-highlight">Mobile Template and PWA</p>
                        <h1 class="font-700">{{ item.title }}</h1>
                    </div>
                    <div class="ml-auto">
                        <h1>$23<sup class="font-300 opacity-30">.99</sup></h1>
                        <span class="badge bg-highlight color-white px-3 py-1 mt-n1 text-uppercase d-block">On Sale</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-gradient-fade rounded-l"></div>
        <div class="card-overlay"></div>
    </div>

    <div class="card" >
        <div class="card-header">
            <p>#{{ item.id }}</p>
        </div>
        <div class="card-body">
            <p>{{ item.title }}</p>

            <div :id="'image-slider-'+item.id" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item "
                         style="height: 300px;"
                         v-bind:class="{'active': index === 0}"
                         v-for="(img, index) in item.images">
                        <img :src="preparedImgUrl(img)" class="d-block w-100 object-fit-cover" alt="...">
                    </div>

                </div>

                <button class="carousel-control-prev" type="button" :data-bs-target="'#image-slider-'+item.id"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" :data-bs-target="'#image-slider-'+item.id"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">

            <button
                title="Дублировать товар"
                @click="duplicateProduct"
                class="btn btn-outline-info mr-1">
                <i class="fa-regular fa-copy"></i>
            </button>

            <button
                title="Удалить товар"
                @click="removeProduct"
                class="btn btn-outline-danger">
                <i class="fa-solid fa-trash-can"></i>
            </button>


        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["item"],
    data() {
        return {

        }
    },
    methods: {
        preparedImgUrl(url) {
            if (url.toLocaleLowerCase().startsWith("https://") || url.toLocaleLowerCase().startsWith("http://"))
                return url;

          //  return "/images-by-bot-id/"+this.bot.id+"/"+url
        },
        removeProduct(){
            this.$store.dispatch("removeProduct", this.item.id).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Продукт успешно удален!",
                    type: 'success'
                });
            }).catch(err => {

            })
        },
        duplicateProduct(){
            this.$store.dispatch("duplicateProduct", this.item.id).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Продукт успешно продублирован!",
                    type: 'success'
                });
            }).catch(err => {

            })
        }
    },
    mounted() {

    },
    computed: {


    },
}
</script>
