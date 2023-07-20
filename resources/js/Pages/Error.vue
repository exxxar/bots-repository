
<template>
    <div class="container py-5 px-3">
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    В данный момент этот раздел находится на техническом обслуживании! В ближайшее время всё заработает:) Спасибо за ожидание!
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                <img v-lazy="'images/cashman2.jpg'" alt="">
            </div>
        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";
export default {
    data() {
        return {}
    },
    computed: {

    },
    mounted() {

    },
    methods: {}
}
</script>
