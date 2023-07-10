<template>


        <div class="page-title page-title-small">
            <h2><a @click="$router.back()"><i class="fa fa-arrow-left"></i></a>Categories</h2>
            <a href="#" data-menu="menu-main" class="bg-fade-gray1-dark shadow-xl preload-img" data-src="images/avatars/5s.png" style="background-image: url(&quot;images/avatars/5s.png&quot;);"></a>
        </div>
        <div class="card header-card shape-rounded" data-card-height="150" style="height: 150px;">
            <div class="card-overlay bg-highlight opacity-95"></div>
            <div class="card-overlay dark-mode-tint"></div>
            <div class="card-bg preload-img" data-src="images/pictures/20s.jpg" style="background-image: url(&quot;images/pictures/20s.jpg&quot;);"></div>
        </div>

        <div class="content mb-0">
            <div class="row mb-0">
                <div class="col-12">
                    <a href="#" data-card-height="120" class="card card-style mb-4 mx-0" style="height: 120px;">
                        <div class="card-center pl-2 ml-2">
                            <i class="fa fa-mobile-alt font-40 pl-3"></i>
                        </div>
                        <div class="card-center pl-4 ml-5">
                            <h4 class="pl-2">Mobile Devices</h4>
                            <p class="pl-2 mt-n2 font-12 color-highlight mb-0">Best selling Category </p>
                        </div>
                        <div class="card-center opacity-30">
                            <i class="fa fa-arrow-right opacity-50 float-right color-theme pr-3"></i>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="#" data-card-height="125" class="card card-style mb-4 mx-0" style="height: 125px;">
                        <div class="card-center text-center">
                            <i class="fa fa-laptop font-30 pt-3 color-red2-dark"></i>
                            <h5 class="pt-2">Laptops</h5>
                            <span class="font-10 opacity-30 color-theme pt-2 d-block">Tap to View</span>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="#" data-card-height="125" class="card card-style mb-4 mx-0" style="height: 125px;">
                        <div class="card-center text-center">
                            <i class="fa fa-desktop font-30 pt-3 color-green1-dark"></i>
                            <h5 class="pt-2">Desktops</h5>
                            <span class="font-10 opacity-30 color-theme pt-2 d-block">Tap to View</span>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="#" data-card-height="125" class="card card-style mb-4 mx-0" style="height: 125px;">
                        <div class="card-center text-center">
                            <i class="fa fa-tablet-alt font-30 pt-3 color-magenta1-dark"></i>
                            <h5 class="pt-2">Tablets</h5>
                            <span class="font-10 opacity-30 color-theme pt-2 d-block">Tap to View</span>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="#" data-card-height="125" class="card card-style mb-4 mx-0" style="height: 125px;">
                        <div class="card-center text-center">
                            <i class="fa fa-tv font-30 pt-3 color-orange-dark"></i>
                            <h5 class="pt-2">4k TV's</h5>
                            <span class="font-10 opacity-30 color-theme pt-2 d-block">Tap to View</span>
                        </div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="#" data-card-height="125" class="card card-style mb-4 mx-0" style="height: 125px;">
                        <div class="card-center text-center">
                            <i class="fa fa-credit-card pt-3 font-30 color-gray2-dark"></i>
                            <h6 class="pt-2">Cards</h6>
                            <span class="font-10 opacity-30 color-theme pt-2 d-block">Tap to View</span>
                        </div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="#" data-card-height="125" class="card card-style mb-4 mx-0" style="height: 125px;">
                        <div class="card-center text-center">
                            <i class="fa fa-cog font-30 pt-3 color-blue2-dark"></i>
                            <h5 class="pt-2">Service</h5>
                            <span class="font-10 opacity-30 color-theme pt-2 d-block">Tap to View</span>
                        </div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="#" data-card-height="125" class="card card-style mb-4 mx-0" style="height: 125px;">
                        <div class="card-center text-center">
                            <i class="fab fa-usb font-30 pt-3 color-dark2-dark"></i>
                            <h5 class="pt-2">Storage</h5>
                            <span class="font-10 opacity-30 color-theme pt-2 d-block">Tap to View</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="divider divider-margins"></div>


</template>
<script>


export default {
    mounted() {
        $.card_extender();
    }
}
</script>
