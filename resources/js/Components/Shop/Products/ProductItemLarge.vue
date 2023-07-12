<template>
    <div class="card rounded-l shadow-xl bg-12 mb-3" data-card-height="320">
        <div class="card-top mt-3 mr-3">
            <a href="#" class="icon icon-s rounded-l shadow-xl bg-red2-dark color-white float-right ml-2 mr-2"><i class="fa fa-heart"></i></a>
            <a href="#" data-menu="menu-share" class="icon icon-s rounded-l shadow-xl bg-highlight color-white float-right"><i class="fa fa-shopping-cart"></i></a>
        </div>
        <div class="card-bottom mb-3">
            <div class="content mb-0">
                <div class="d-flex">
                    <div>
                        <p class="mb-n1 font-600 color-highlight">Mobile Template and PWA</p>
                        <h1 class="font-700">Sticky Mobile</h1>
                    </div>
                    <div class="ml-auto">
                        <h1>$35<sup class="font-300 opacity-30">.99</sup></h1>
                        <span class="badge bg-highlight color-white px-3 py-1 mt-n1 text-uppercase d-block">On Sale</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-gradient-fade rounded-l"></div>
        <div class="card-overlay"></div>
    </div>
</template>
<script>
export default {
    props: ["item"]
}
</script>
