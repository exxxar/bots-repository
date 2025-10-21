<template>
    <div class="card partner-card">
        <img v-lazy="'/images-by-bot-id/'+partner.id+'/'+partner.image" class="card-img" alt="...">
        <div class="card-img-overlay">
            <h5 class="card-title">{{ partner.title }}</h5>
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
    mounted() {

    }
}
</script>

<style lang="scss" scoped>

.partner-card {
    height: 270px;


    img {
        object-fit: cover;
        /* height: 100%; */
        width: 100%;
        max-height: 190px;
        height: 190px;
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
