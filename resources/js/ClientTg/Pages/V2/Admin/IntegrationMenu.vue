<template>
    <div class="container py-3">
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
            <div class="col">
                <button type="button"
                        @click="goTo('IikoV2')"
                        style="min-height:250px;height:100%;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body w-100 d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/iiko.png'" class="img-fluid" alt="">

                        <p class="my-2">Управление IIKO</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('CdekV2')"
                        style="min-height:250px;height:100%;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body w-100 d-flex justify-content-center align-items-center flex-column">
                        <img
                            style="object-fit:cover;"
                            v-lazy="'/images/shop-v2-2/CDEK.png'" class="img-fluid" alt="">

                        <p class="my-2">Управление CDEK</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('AmoV2')"
                        style="min-height:250px;height:100%;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body w-100 d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/amo.png'" class="img-fluid" alt="">

                        <p class="my-2">Управление AMO</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('BitrixV2')"
                        style="min-height:250px;height:100%;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body w-100 d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/bitrix.png'" class="img-fluid" alt="">

                        <p class="my-2">Управление Bitrix24</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('YClientsV2')"
                        style="min-height:250px;height:100%;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body w-100 d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/yclients.png'" class="img-fluid" alt="">

                        <p class="my-2">Управление yClients</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('FrontPadV2')"
                        style="min-height:250px;height:100%;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body w-100 d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/promo.png'" class="img-fluid" alt="">

                        <p class="my-2">Управление FrontPad</p>
                    </div>

                </button>
            </div>



        </div>
        </div>
</template>
<script>
export default {
    computed:{
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {
        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods:{
        goTo(name) {
            this.$router.push({name: name})
        },
    }
}
</script>
