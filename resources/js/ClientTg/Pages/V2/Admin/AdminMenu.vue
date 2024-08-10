<template>
    <div class="container">
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
            <div class="col">
                <button type="button"
                        @click="goTo('ProfileV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/profile.png'" class="img-fluid" alt="">

                        <p class="my-2">Профиль</p>
                    </div>

                </button>
            </div>
        </div>
        </div>
</template>
<script>
export default {
    methods:{
        goTo(name) {
            this.$router.push({name: name})
        },
    }
}
</script>
