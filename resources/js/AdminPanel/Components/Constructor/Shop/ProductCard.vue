<template>
    <div class="card" v-if="bot">
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
            bot: null
        }
    },
    methods: {
        preparedImgUrl(url) {
            if (url.toLocaleLowerCase().startsWith("https://") || url.toLocaleLowerCase().startsWith("http://"))
                return url;

            return "/images-by-bot-id/"+this.bot.id+"/"+url
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
        this.bot = this.getCurrentBot
    },
    computed: {
        ...mapGetters(['getCurrentBot']),

    },
}
</script>
