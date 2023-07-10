<template>


        <div class="page-title page-title-small">
            <h2><a @click="$router.back()"><i class="fa fa-arrow-left"></i></a>My Profile</h2>
            <a href="#" data-menu="menu-main"
               class="bg-fade-gray1-dark shadow-xl preload-img"
               data-src="images/avatars/5s.png" style="background-image: url(&quot;images/avatars/5s.png&quot;);"></a>
        </div>
        <div class="card header-card shape-rounded" data-card-height="150" style="height: 150px;">
            <div class="card-overlay bg-highlight opacity-95"></div>
            <div class="card-overlay dark-mode-tint"></div>
            <div class="card-bg preload-img" data-src="images/pictures/20s.jpg" style="background-image: url(&quot;images/pictures/20s.jpg&quot;);"></div>
        </div>

        <div class="card card-style">
            <div class="content">
                <div class="d-flex">
                    <div>
                        <img src="images/avatars/5s.png" width="50" class="mr-3 bg-highlight rounded-xl">
                    </div>
                    <div>
                        <h1 class="mb-0 pt-1">Enabled</h1>
                        <p class="color-highlight font-11 mt-n2 mb-3">The Best Mobile Author on Envato</p>
                    </div>
                </div>
                <p>
                    We care about all our customers and we give 150% attention to detail for perfect quality.
                </p>
            </div>
        </div>

        <div class="card card-style">
            <div class="content mb-0">
                <h3 class="font-600">Basic Info</h3>
                <p>
                    Basic details about you. Set them here. These can be connected to your database and shown on the user profile.
                </p>

                <div class="input-style input-style-2 input-required mb-">
                    <span class="color-highlight input-style-1-active">Name</span>
                    <em>(required)</em>
                    <input class="form-control" type="text" placeholder="John Doe">
                </div>

                <div class="input-style input-style-2 input-required mb-4">
                    <span class="color-highlight input-style-1-active">Email Address</span>
                    <em>(required)</em>
                    <input class="form-control" type="email" placeholder="name@domain.com">
                </div>

                <div class="input-style input-style-2 input-required mb-4">
                    <span class="color-highlight input-style-1-active">Phone Number</span>
                    <em>(required)</em>
                    <input class="form-control" type="number" placeholder="+1 234 567 8900">
                </div>

                <div class="input-style input-style-2 input-required mb-4">
                    <span class="color-highlight input-style-1-active">Location</span>
                    <em>(required)</em>
                    <input class="form-control" type="text" placeholder="Melbourne, Victoria">
                </div>

                <div class="input-style input-style-2 input-required">
                    <span class="color-highlight input-style-1-active">Password</span>
                    <em>(required)</em>
                    <input class="form-control" type="password" value="adgw45@$%@^@$%Fa">
                </div>

            </div>
        </div>

        <div class="card card-style">
            <div class="content mb-2">
                <h3 class="font-600">Socials</h3>
                <p>
                    Basic details about you. Set them here. These can be connected to your database and shown on the user profile.
                </p>

                <div class="list-group list-custom-small">
                    <a href="#">
                        <i class="fab font-14 fa-twitter color-twitter"></i>
                        <span>Twitter</span>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                    <a href="#">
                        <i class="fab font-14 fa-linkedin-in color-linkedin"></i>
                        <span>LinkedIn</span>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                    <a href="#">
                        <i class="fab font-14 fa-facebook-f color-facebook"></i>
                        <span>Facebook</span>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                    <a href="#" class="border-0">
                        <i class="fab font-14 fa-instagram color-instagram"></i>
                        <span>Instagram</span>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="card card-style">
            <div class="content mb-2">
                <h3 class="font-600">Account Settings</h3>
                <p>
                    Set your preferences here, these can be connected to a database and shown on the user profile.
                </p>

                <div class="list-group list-custom-small">
                    <a href="#" data-trigger-switch="switch-1">
                        <span>Notifications</span>
                        <div class="custom-control scale-switch ios-switch">
                            <input type="checkbox" class="ios-input" id="switch-1" checked="">
                            <label class="custom-control-label" for="switch-1"></label>
                        </div>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                    <a href="#" data-trigger-switch="switch-1a">
                        <span>Newsletter</span>
                        <div class="custom-control scale-switch ios-switch">
                            <input type="checkbox" class="ios-input" id="switch-1a" checked="">
                            <label class="custom-control-label" for="switch-1"></label>
                        </div>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                    <a href="#" data-trigger-switch="switch-1a" class="border-0">
                        <span>Dark Mode</span>
                        <div class="custom-control scale-switch ios-switch">
                            <input type="checkbox" class="ios-input" id="switch-1a">
                            <label class="custom-control-label" for="switch-1"></label>
                        </div>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <a href="#" class="btn btn-full btn-margins bg-highlight rounded-sm shadow-xl btn-m text-uppercase font-900">Save Information</a>







</template>
<script>

export default {
    mounted() {
        $.card_extender();
    }
}
</script>
