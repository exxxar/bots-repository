<template>

    <div class="d-flex pb-2">
        <div class="mr-auto">
            <img v-lazy="preparedImgUrl(item.images[0])" class="rounded-m shadow-xl" width="110">
            <!--            <a href="javascript:void(0)" data-menu="cart-item-edit" class="color-white mt-n5 py-3 pl-2 d-block font-11"><i class="fa fa-pen pl-2 pr-2"></i> Редактировать</a>-->
        </div>
        <div class="ml-auto w-100 pl-3">
            <h5 class="pb-2">#{{item.id}} {{ item.title }}</h5>
            <h6 class="float-left pt-2 ">{{item.current_price}}₽<sup
                v-if="item.old_price"
                style="text-decoration: line-through;">{{item.old_price}}₽</sup></h6>
            <div class="float-right">
                <div class="input-style input-style-1 mt-1">
                    <button
                        @click="selectProduct"
                        class="btn btn-full btn-sm rounded-sm bg-highlight font-800 text-uppercase"><i class="fa-regular fa-pen-to-square"></i></button>
                </div>
            </div>
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
