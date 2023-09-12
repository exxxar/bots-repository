<template>

    <div class="d-flex pb-2">
        <div class="mr-auto">
            <img v-lazy="preparedImgUrl(item.images[0])" class="rounded-m shadow-xl" width="110">
<!--            <a href="javascript:void(0)" data-menu="cart-item-edit" class="color-white mt-n5 py-3 pl-2 d-block font-11"><i class="fa fa-pen pl-2 pr-2"></i> Редактировать</a>-->
        </div>
        <div class="ml-auto w-100 pl-3">
            <h5 class="font-14 font-600 opacity-80 pb-2">#{{item.id}} {{ item.title }}</h5>
            <div class="clearfix"></div>
            <h1 class="font-23 font-700 float-left pt-2 ">{{item.current_price}}₽<sup
                v-if="item.old_price"
                style="text-decoration: line-through;" class="font-15 opacity-50">{{item.old_price}}₽</sup></h1>
            <div class="float-right">
                <div class="input-style input-style-1 mt-1">
                    <button
                        @click="selectProduct"
                        class="btn btn-full btn-sm rounded-sm bg-highlight font-800 text-uppercase"><i class="fa-regular fa-pen-to-square"></i></button>
                </div>
            </div>
        </div>
    </div>


<!--    <div class="card" >
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
    </div>-->
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
        selectProduct(){
          this.$emit("select", this.item)
            window.scrollTo(50,400);
        },
        preparedImgUrl(url) {
            if (url.toLocaleLowerCase().startsWith("https://") || url.toLocaleLowerCase().startsWith("http://"))
                return url;

          //  return "/images-by-bot-id/"+this.bot.id+"/"+url
        },
        removeProduct(){
            this.$store.dispatch("removeProduct", this.item.id).then((response) => {

                this.$botNotification.success(
                    "Продукты",
                    "Продукт успешно удален!",
                );

            }).catch(err => {

            })
        },
        duplicateProduct(){
            this.$store.dispatch("duplicateProduct", this.item.id).then((response) => {

                this.$botNotification.success(
                    "Продукты",
                    "Продукт успешно продублирован!",
                );


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
