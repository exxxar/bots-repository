<template>
    <button type="button"
            v-if="item?.is_visible || true"
            @click="goTo(route)"
            style="min-height:250px;"
            :disabled="disabled||false"
            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
        <div class="card-body d-flex justify-content-center align-items-center flex-column w-100">
            <img v-lazy="'/images/shop-v2-2/'+(item?.image_url||defaultImage)" class="img-fluid menu-item-img" alt="">

            <p class="my-2">{{item?.title || defaultText}} <slot name="post-text"/></p>
            <span style="font-size:12px;" v-if="disabled||false"><i class="fa-solid fa-lock"></i> закрыто</span>
        </div>

    </button>
</template>
<script>
export default {
    props:["item",'defaultImage','defaultText', 'route', 'disabled'],
    methods:{
        goTo(name) {
            this.$router.push({name: name})
        },
    }
}
</script>
<style>
.menu-item-img {
    max-width: 130px;
    max-height: 130px;
    object-fit: cover;
}
</style>
