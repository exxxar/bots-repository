<script setup>
defineProps({
    bot: {
        type: Object,
    },
    title: {
        type: String,
    },
    description: {
        type: String,
    },
});
import Layout from "@/Layouts/ShopLayout.vue";
</script>
<template>
    <Layout>
        <template #default>
            <div class="page-content" style="min-height: 667px;">

                <router-view
                    :bot="bot"/>

                <!-- footer and footer card-->

                <div class="footer">
                    <div class="card card-style mb-0">
                        <a href="#" class="footer-title pt-4">{{ title||'CashMan:Shopify' }}</a>
                        <p class="text-center font-12 mt-n1 mb-3 opacity-70">
                           Добавь <span class="color-highlight">красок</span> в свою жизнь
                        </p>
                        <p class="boxed-text-l">
                            {{description||'Описание вашего магазина'}}
                        </p>
                        <div class="text-center mb-3">
                            <a href="#" class="icon icon-xs rounded-sm shadow-l mr-1 bg-facebook"><i
                                class="fab fa-facebook-f"></i></a>
                            <a href="#" class="icon icon-xs rounded-sm shadow-l mr-1 bg-twitter"><i
                                class="fab fa-twitter"></i></a>
                            <a href="#" class="icon icon-xs rounded-sm shadow-l mr-1 bg-phone"><i
                                class="fa fa-phone"></i></a>
                            <a href="#" data-menu="menu-share"
                               class="icon icon-xs rounded-sm mr-1 shadow-l bg-red2-dark"><i
                                class="fa fa-share-alt"></i></a>
                            <a href="#" class="back-to-top icon icon-xs rounded-sm shadow-l bg-highlight color-white"><i
                                class="fa fa-arrow-up"></i></a>
                        </div>
                        <p class="footer-copyright pb-3 mb-1">© CashMan <span id="copyright-year">2023</span>.
                            Все
                            Права защищены.</p>
                    </div>
                    <div class="footer-card card shape-rounded" data-card-height="230" style="height: 230px;">
                        <div class="card-overlay bg-highlight opacity-95"></div>
                        <div class="card-bg preload-img" data-src="/shop/images/pictures/20s.jpg"></div>
                    </div>
                </div>
            </div>
        </template>

    </Layout>
</template>

<script>
export default {
    created() {
        window.currentBot = this.bot
        this.$notify({ type: "success", text: "The operation completed" });
    }
}
</script>

