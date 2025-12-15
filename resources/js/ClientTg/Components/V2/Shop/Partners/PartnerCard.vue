<template>
    <div class="card ">
        <div class="card-body">
            <img v-lazy="'/images-by-bot-id/'+bot.id+'/'+partner.image"
                 class="w-100 card-img" alt="..." style="object-fit:cover;height:200px;">

            <h5 class="my-2 fw-bold">{{ partner.title }}</h5>
            <p class="fst-italic mb-2" v-if="partner.description">{{ partner.description || '' }}</p>
            <button class="btn btn-primary w-100 p-2"
                    @click="$emit('select', partner)"
                    type="button">Перейти</button>
        </div>

    </div>
</template>
<script>
export default {
    props: ["partner"],
    data(){
        return {

        }
    },
    computed:{
        bot(){
            return window.currentBot || null
        }
    },
    mounted() {
        console.log(this.partner)
    }
}
</script>

<style lang="scss" scoped>

.partner-card {
    height: 300px;


    img {
        object-fit: cover;
        /* height: 100%; */
        width: 100%;
        max-height:200px;
        height: 200px;
    }

    .card-img-overlay {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 7px;

        .card-title {
            text-align: center;
            font-weight: 900;
            font-size: 14px;
        }
    }
}


</style>
