<script setup>

</script>
<template>

 <div class="card">
     <div class="card-body"></div>
 </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {

            load: false,
            confirm: false,
            step: 0,
            botUser: null,
            managerForm: {
                name: null,
                phone: null,
                email: null,
                image: null,
                birthday: null,
                city: null,
                country: null,
                address: null,
                sex: true,
                referral: null,
                strengths: [""],
                weaknesses: [""],
                educations: [""],
                social_links: [""],
                skills: [
                    {
                        title: null,
                        value: 50,
                    }
                ],
                info: null,

                //навыки, сильные и слабые стороны, краткое био

            }
        }
    },
    watch: {
        'getSelf': function () {
            this.prepareManager()
        }

    },
    mounted() {
        this.prepareManager()
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        getSelf(){
            return window.profile
        },
        currentBot() {
            return window.currentBot
        }
    },
    methods: {
        prepareManager(){
            this.botUser = this.getSelf

            this.managerForm.name = this.botUser.name || this.botUser.fio_from_telegram || null
            this.managerForm.phone = this.botUser.phone || null
            this.managerForm.email = this.botUser.email || null
            this.managerForm.birthday = this.botUser.birthday || null
            this.managerForm.city = this.botUser.city || null
            this.managerForm.country = this.botUser.country || null
            this.managerForm.address = this.botUser.address || null
            this.managerForm.sex = this.botUser.sex || true
        },
        getPhoto(img) {
            return {imageUrl: URL.createObjectURL(img)}
        },
        removePhoto(index) {

            this.managerForm.image = null
        },
        onChangePhotos(e) {
            const files = e.target.files
            this.managerForm.image = files[0]
            console.log(files)
        },
        nextStep() {
            this.step++;
        },
        remove(section, index) {
            this.managerForm[section].splice(index, 1)
        },
        add(section) {
            if (section !== "skills")
                this.managerForm[section].push('')
            else
                this.managerForm[section].push({
                    title: null,
                    value: 50,
                })
        },
        submit() {
            this.loading = true;

            let data = new FormData();
            Object.keys(this.managerForm)
                .forEach(key => {
                    const item = this.managerForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.set('image', this.managerForm.image);

            this.$store.dispatch("saveManager",
                data
            ).then((resp) => {
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style lang="scss" scoped>
.img-avatar {
    width: 200px;
    height: 200px;
    padding: 10px;

    img {
        object-fit: cover;
        width: 100%;
    }

}

.theme-dark {
    input {
        border-color: white;
    }
}

.input-style-2 a {
    position: absolute;
    right: 12px;
    top: 12px;
    font-size: 16px;
}
</style>
