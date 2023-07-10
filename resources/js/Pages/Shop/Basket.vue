<template>


        <div class="page-title page-title-small">
            <h2><a @click="$router.back()"><i class="fa fa-arrow-left"></i></a>Cart 1</h2>
            <a href="#" data-menu="menu-main" class="bg-fade-gray1-dark shadow-xl preload-img" data-src="images/avatars/5s.png" style="background-image: url(&quot;images/avatars/5s.png&quot;);"></a>
        </div>
        <div class="card header-card shape-rounded" data-card-height="150" style="height: 150px;">
            <div class="card-overlay bg-highlight opacity-95"></div>
            <div class="card-overlay dark-mode-tint"></div>
            <div class="card-bg preload-img" data-src="images/pictures/20s.jpg" style="background-image: url(&quot;images/pictures/20s.jpg&quot;);"></div>
        </div>

        <div class="card card-style">
            <div class="content">
                <div class="d-flex pb-2">
                    <div class="pr-3">
                        <h5 class="font-14 font-600 opacity-80 pb-2">Your awesome product description long or short. </h5>
                        <h1 class="font-24 font-700 ">$199<sup class="font-15 opacity-50">.99</sup></h1>
                    </div>
                    <div class="ml-auto">
                        <img src="images/pictures/2s.jpg" class="rounded-m shadow-xl" width="90">
                        <a href="#" class="color-white mt-n4 pl-3 d-block font-10"><i class="fa fa-times pr-2"></i> Remove</a>
                    </div>
                </div>
                <div class="row mb-0 mt-4">
                    <div class="col-8">
                        <div class="input-style input-style-2">
                            <span class="input-style-1-active">Options</span>
                            <em><i class="fa fa-angle-down"></i></em>
                            <select class="form-control">
                                <option value="1" disabled="" selected="">Green Color</option>
                                <option value="2">Red Color</option>
                                <option value="3">Blue Color</option>
                                <option value="4">Orange Color</option>
                                <option value="5">Yellow Color</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="input-style input-style-2">
                            <span class="input-style-1-active">Qty</span>
                            <input type="number" min="1" max="999" style="border-width:1px;" placeholder="1">
                        </div>
                    </div>
                </div>

                <div class="divider mt-3"></div>

                <div class="d-flex pb-2">
                    <div class="pr-3">
                        <h5 class="font-14 font-600 opacity-80 pb-2">Designer Style Eyeglasses with UV protection and stylish rim. </h5>
                        <h1 class="font-24 font-700 ">$1299<sup class="font-15 opacity-50">.99</sup></h1>
                    </div>
                    <div class="ml-auto">
                        <img src="images/pictures/20s.jpg" class="rounded-m shadow-xl" width="90">
                        <a href="#" class="color-white mt-n4 pl-3 d-block font-10"><i class="fa fa-times pr-2"></i> Remove</a>
                    </div>
                </div>
                <div class="row mb-0 mt-4">
                    <div class="col-3">
                        <div class="input-style input-style-2">
                            <span class="input-style-1-active">Size</span>
                            <em><i class="fa fa-angle-down"></i></em>
                            <select class="form-control">
                                <option value="1" disabled="" selected="">S</option>
                                <option value="2">XS</option>
                                <option value="3">M</option>
                                <option value="4">L</option>
                                <option value="5">XL</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-style input-style-2">
                            <span class="input-style-1-active">Warranty</span>
                            <em><i class="fa fa-angle-down"></i></em>
                            <select class="form-control">
                                <option value="1" selected="">2 Years - Default</option>
                                <option value="2">4 Years (+ $99.99)</option>
                                <option value="2">8 Years (+ $199.99)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-style input-style-2">
                            <span class="input-style-1-active">Qty</span>
                            <input type="number" min="1" max="999" style="border-width:1px;" placeholder="1">
                        </div>
                    </div>
                </div>

                <div class="divider mt-3"></div>

                <div class="d-flex pb-2">
                    <div class="pr-3">
                        <h5 class="font-14 font-600 opacity-80 pb-2">Retro Camera with 24 rolls of film included. </h5>
                        <h1 class="font-24 font-700 ">$499<sup class="font-15 opacity-50">.99</sup></h1>
                    </div>
                    <div class="ml-auto">
                        <img src="images/pictures/6s.jpg" class="rounded-m shadow-xl" width="90">
                        <a href="#" class="color-white mt-n4 pl-3 d-block font-10"><i class="fa fa-times pr-2"></i> Remove</a>
                    </div>
                </div>
                <div class="row mb-0 mt-4">
                    <div class="col-6">
                        <div class="input-style input-style-2">
                            <span class="input-style-1-active">Film</span>
                            <em><i class="fa fa-angle-down"></i></em>
                            <select class="form-control">
                                <option value="1" selected="">24 Rolls</option>
                                <option value="2">48 Rolls (+ $99.99)</option>
                                <option value="2">96 Rolls (+ $199.99)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-style input-style-2">
                            <span class="input-style-1-active">Size</span>
                            <em><i class="fa fa-angle-down"></i></em>
                            <select class="form-control">
                                <option value="1" disabled="" selected="">S</option>
                                <option value="2">XS</option>
                                <option value="3">M</option>
                                <option value="4">L</option>
                                <option value="5">XL</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-style input-style-2">
                            <span class="input-style-1-active">Qty</span>
                            <input type="number" min="1" max="999" style="border-width:1px;" placeholder="1">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card mt-4 preload-img" data-src="images/pictures/20s.jpg">
            <div class="card-body py-4">
                <h2 class="color-white text-center">Cart Sumary</h2>
                <p class="color-white boxed-text-l">
                    Taxes and Shipping will be adde din the Checkout Page
                </p>
                <div class="card card-style mx-0">
                    <div class="content">
                        <div class="row mb-0">
                            <div class="col-6 text-left"><h4>Total</h4></div>
                            <div class="col-6 text-right"><h4>$2133<sup>.98</sup></h4></div>
                        </div>
                        <div class="divider mt-4"></div>
                        <a href="#" class="btn btn-full btn-m rounded-sm bg-highlight font-800 text-uppercase">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
            <div class="card-overlay bg-highlight opacity-95"></div>
            <div class="card-overlay dark-mode-tint"></div>
        </div>

        <div class="divider divider-margins"></div>



</template>
<script>
import '@/custom.js'

export default {
    mounted() {
        $.card_extender();
    }
}
</script>
