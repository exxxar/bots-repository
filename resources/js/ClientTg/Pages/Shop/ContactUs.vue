<template>


    <div class="card card-style">
        <div class="responsive-iframe">
            <iframe
                src="https://maps.google.com/?ie=UTF8&amp;ll=47.595131,-122.330414&amp;spn=0.006186,0.016512&amp;t=h&amp;z=17&amp;output=embed"
                frameborder="0" allowfullscreen=""></iframe>
        </div>
    </div>

    <div class="card card-style">
        <div data-card-height="120"
             data-closed-message="Sorry! We're closed!"
             data-closed-message-under="We'll be back very soon!"
             data-opened-message="Welcome! We're open!"
             data-opened-message-under="Call us Now! Let's have a chat!"
             class="business-hours caption shadow-xl">

            <div class="card-top">
                <a id="business-hours-call" href="tel:+1 234 567 890"
                   class="float-right btn btn-xs rounded-xl text-uppercase font-900 mt-5 mr-3 bg-white color-black">Call
                    Now</a>
                <a id="business-hours-mail" href="page-contact.html"
                   class="float-right btn btn-xs rounded-xl text-uppercase font-900 mt-5 mr-3 bg-white color-black">Email
                    Us</a>
            </div>
            <div class="card-top mt-4 ml-3 pl-1">
                <h1 class="color-white mt-1 font-20 font-700">
                    <!-- Automatic Text set by data opened/closed message--></h1>
                <p class="color-white opacity-90 mt-n2 mb-0">
                    <!-- Automatic Text set by data opened/closed message under--></p>
            </div>
            <div class="caption-overlay show-business-opened bg-highlight"></div>
            <div class="caption-overlay show-business-closed bg-red2-dark"></div>
        </div>
        <div class="content">

            <!-- To set open and Close Hours Open custom.js and search for "SET OPENING HOURS BELOW" -->
            <!-- Only setting the code below will not make the system work automatically-->

            <div class="working-hours day-monday"><p>Monday</p>
                <p>09:00 AM</p>
                <p>05:00 PM</p></div>
            <div class="working-hours day-tuesday"><p>Tuesday</p>
                <p>09:00 AM</p>
                <p>05:00 PM</p></div>
            <div class="working-hours day-wednesday"><p>Wednesday</p>
                <p>09:00 AM</p>
                <p>05:00 PM</p></div>
            <div class="working-hours day-thursday"><p>Thursday</p>
                <p>09:00 AM</p>
                <p>05:00 PM</p></div>
            <div class="working-hours day-friday"><p>Friday</p>
                <p>09:00 AM</p>
                <p>05:00 PM</p></div>
            <div class="working-hours day-saturday"><p>Saturday</p>
                <p>09:00 AM</p>
                <p>01:00 PM</p></div>
            <div class="working-hours day-sunday"><p>Sunday</p>
                <p class="opacity-00">-</p>
                <p>We're Closed</p></div>

        </div>
    </div>


    <div class="formValidationError bg-red2-dark content rounded-sm shadow-xl" id="contactNameFieldError"
         style="display: none;">
        <p class="text-center text-uppercase p-2 color-white font-900 ">Name is required!</p>
    </div>
    <div class="formValidationError bg-red2-dark content rounded-sm shadow-xl" id="contactEmailFieldError"
         style="display: none;">
        <p class="text-center text-uppercase p-2 color-white font-900">Mail address required!</p>
    </div>
    <div class="formValidationError bg-red2-dark content rounded-sm shadow-xl" id="contactEmailFieldError2"
         style="display: none;">
        <p class="text-center text-uppercase p-2 color-white font-900">Mail address must be valid!</p>
    </div>
    <div class="formValidationError bg-red2-dark content rounded-sm shadow-xl" id="contactMessageTextareaError"
         style="display: none;">
        <p class="text-center text-uppercase p-2 color-white font-900">Message field is empty!</p>
    </div>

    <div class="formSuccessMessageWrap card card-style" style="display: none;">
        <div class="shadow-l rounded-m bg-gradient-green1 mr-n1 ml-n1 mb-n1 ">
            <h1 class="color-white text-center pt-4"><i
                class="fa fa-check-circle fa-3x shadow-s scale-box rounded-circle"></i></h1>
            <h2 class="color-white bold text-center pt-3">Message Sent</h2>
            <p class="color-white pb-4 text-center mt-n2 mb-0">We'll get back to you shortly.</p>
        </div>
    </div>

    <div class="formSuccessMessageWrap card card-style" style="display: none;">
        <div class="content text-center">
            <h2>Meanwhile, let's get social!</h2>
            <p class="boxed-text-xl">
                Here are our social media platforms! Follow us for the latest updates or just say hello!
            </p>
            <a href="#" class="icon icon-xl shadow-xl rounded-xl bg-facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="icon icon-xl shadow-xl rounded-xl bg-instagram ml-3 mr-3"><i
                class="fab fa-instagram"></i></a>
            <a href="#" class="icon icon-xl shadow-xl rounded-xl bg-twitter"><i class="fab fa-twitter"></i></a>
        </div>
    </div>

    <div class="card card-style contact-form">
        <div class="content">
            <form action="php/contact.php" method="post" class="contactForm" id="contactForm">
                <fieldset>
                    <div class="form-field form-name">
                        <label class="contactNameField color-theme" for="contactNameField">Name:<span>(required)</span></label>
                        <input type="text" name="contactNameField" value=""
                               class="contactField round-small requiredField" id="contactNameField">
                    </div>
                    <div class="form-field form-email">
                        <label class="contactEmailField color-theme"
                               for="contactEmailField">Email:<span>(required)</span></label>
                        <input type="text" name="contactEmailField" value=""
                               class="contactField round-small requiredField requiredEmailField" id="contactEmailField">
                    </div>
                    <div class="form-field form-text">
                        <label class="contactMessageTextarea color-theme" for="contactMessageTextarea">Message:<span>(required)</span></label>
                        <textarea name="contactMessageTextarea" class="contactTextarea round-small requiredField"
                                  id="contactMessageTextarea"></textarea>
                    </div>
                    <div class="form-button">
                        <input type="submit"
                               class="btn bg-highlight text-uppercase font-900 btn-m btn-full rounded-sm  shadow-xl contactSubmitButton"
                               value="Send Message" data-formid="contactForm">
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <div class="card card-style">
        <div class="content mb-0">
            <h3 class="font-700">Postal Information</h3>
            <p class="pb-0 mb-0">PO Box 22161 Street Collins East</p>
            <p class="pb-0 mb-0">PO Box 16122 Collins Street West</p>
            <p class="pb-0">Victoria 8007 Australia</p>

            <div class="divider"></div>

            <h3 class="font-700">Envato Headquarters</h3>
            <p class="pb-0 mb-0">121 King Street, Melbourne</p>
            <p class="pb-0 mb-0">PO Box 16122 Collins Street West</p>
            <p class="pb-0">Victoria 3000 Australia</p>

            <div class="list-group list-custom-small">
                <a href="tel:+1 234 567 890">
                    <i class="fa font-14 fa-phone color-phone"></i>
                    <span>+1 234 567 8900</span>
                    <span class="badge bg-highlight color-white">TAO TO CALL</span>
                    <i class="fa fa-angle-right"></i>
                </a>
                <a href="mailto:mail@domain.com">
                    <i class="fa font-14 fa-envelope color-mail"></i>
                    <span>mail@domain.com</span>
                    <span class="badge bg-highlight color-white">TAO TO MAIL</span>
                    <i class="fa fa-angle-right"></i>
                </a>
                <a href="https://www.facebook.com/enabled.labs/">
                    <i class="fab font-14 fa-facebook color-facebook"></i>
                    <span>enabled.labs</span>
                    <i class="fa fa-link"></i>
                </a>
                <a href="https://twitter.com/iEnabled">
                    <i class="fab font-14 fa-twitter-square color-twitter"></i>
                    <span>@iEnabled</span>
                    <i class="fa fa-link"></i>
                </a>
                <a href="#" class="border-0">
                    <i class="fab font-14 fa-linkedin color-linkedin"></i>
                    <span>@Enabled</span>
                    <i class="fa fa-link"></i>
                </a>

            </div>
        </div>
    </div>


</template>
