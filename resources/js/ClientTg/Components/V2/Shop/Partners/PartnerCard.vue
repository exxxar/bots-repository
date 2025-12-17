<template>


    <div class="card mb-2 border-light"  @click="$emit('select', partner)">
        <div class="row g-0">
            <div class="col-md-4">
                <img v-lazy="'/images-by-bot-id/'+bot.id+'/'+partner.image" class="img-fluid rounded-start img-partner-card" alt="...">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="my-2 fw-bold">{{ partner.title }}</h5>
                    <p class="fst-italic mb-2" v-if="partner.description">{{ partner.description || '' }}</p>
                    <p class="card-text"
                       style="line-height:100%;"
                       v-if="partner.categories">
                        <span
                              class="text-primary"
                              style="font-size:10px; margin:3px;"
                              v-for="category in partner.categories">#{{category.title}}, </span>
                    </p>
<!--                    <button class="btn btn-primary w-100 p-3"
                            @click="$emit('select', partner)"
                            type="button">К товарам</button>-->
                </div>
            </div>
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

.img-partner-card {
        max-height: 300px;
        width: 100%;
        object-fit: cover;
    border-radius: 5px;
}

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
