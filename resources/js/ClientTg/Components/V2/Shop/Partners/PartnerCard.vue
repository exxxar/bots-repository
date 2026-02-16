<template>


    <div class="card mb-2 border-light" >
        <div class="row g-0">
            <div class="col-md-4">
                <img
                    @click="$emit('select', partner)"
                    style="max-height:210px;"
                    v-lazy="'/images-by-bot-id/'+bot.id+'/'+partner.image"
                    class="img-fluid rounded-start img-partner-card" alt="...">
                <div class="partner-controls">
                       <span
                           @click="addToFavorite"
                           style="margin-right: 10px;"
                           class="badge bg-primary fw-bold">
                        <i class="fa-solid  fa-heart"
                           v-if="inFav"
                           style="font-size:14px;"></i>
                        <i class="fa-regular fa-heart"
                           style="font-size:14px;"
                           v-else></i>
                    </span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-body" @click="$emit('select', partner)">
                    <h5 class="my-2 fw-bold">{{ partner.title }}</h5>
                    <p class="fst-italic mb-2" v-if="partner.description">{{ partner.description || '' }}</p>
                    <p class="card-text"
                       style="line-height:100%;"
                       v-if="partner.categories">

                        <span
                            class="badge bg-success text-white"
                            style="font-size:10px; margin:3px;"
                            v-for="category in visibleCategories"
                            :key="category.id"
                        >
                            #{{ category.title }}
                        </span>
                        <span v-if="hasHidden" class="text-muted fw-bold" style="font-size:12px;">
                            ...
                        </span>
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
import {mapGetters} from "vuex";

export default {
    props: ["partner"],
    data() {
        return {}
    },
    computed: {
        ...mapGetters(['getSelf', 'getFavoritePartners']),
        inFav() {
            if (this.favorites.length === 0)
                return false
            return this.favorites.indexOf(this.partner.id) !== -1
        },
        favorites() {
            return this.getFavoritePartners;
        },
        bot() {
            return window.currentBot || null
        },
        visibleCategories() {
            if (!this.partner?.categories) return []
            return this.partner.categories.slice(0, 8)
        },
        hasHidden() {
            return this.partner?.categories?.length > 8
        }
    },
    mounted() {
        console.log(this.partner)
    },
    methods:{
        addToFavorite() {
            this.$store.dispatch("togglePartnerInFavorites", {
                form: {
                    id: this.partner.id
                }
            }).then((resp) => {
                this.$notify({
                    title: "Избранное",
                    text: !this.inFav ? 'Магазин убран из избранного!' : 'Магазин успешно добавлен в избранное!',
                    type: 'success'
                })

                window.self.config.fav_partners = resp.data.fav_partners

            }).catch(() => {

            })

            const fav = window.self.config?.fav_partners || []

            let index = fav.findIndex(f => f === this.partner.id)

            if (index !== -1)
                fav.splice(index, 1)
            else
                fav.push(this.partner.id)

            if (window.self.config == null)
                window.self.config = {
                    fav_partners: fav
                }
            else
                window.self.config.fav_partners = fav

            this.$emit("change-fav")
        },
    }
}
</script>

<style lang="scss" scoped>

.partner-controls {
    position: absolute;
    display: flex;
    justify-content: flex-end;
    width: 100%;
    top: 10px;
    z-index: 20;
}
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
        max-height: 200px;
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
