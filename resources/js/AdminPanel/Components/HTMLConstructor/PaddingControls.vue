<template>
    <div class="mb-3">
        <label class="form-label fw-bold">Внутренние отступы (padding)</label>

        <div class="btn-group w-100" role="group">

            <!-- paddingTop -->
            <div class="btn-group w-100" role="group">
                <button
                    type="button"
                    class="btn btn-outline-primary dropdown-toggle"
                    data-bs-toggle="dropdown"
                >
                    <span v-if="modelValue.paddingTop">{{ modelValue.paddingTop }}</span>
                    <span v-else><i class="fa-solid fa-arrow-up"></i></span>
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" @click="set('paddingTop', null)">Не выбран</a></li>
                    <li v-for="n in 5" :key="'pt-'+n-1">
                        <a class="dropdown-item" @click="set('paddingTop', 'pt-' + n-1)">pt-{{ n-1 }}</a>
                    </li>
                </ul>
            </div>

            <!-- paddingRight -->
            <div class="btn-group w-100" role="group">
                <button
                    type="button"
                    class="btn btn-outline-primary dropdown-toggle "
                    data-bs-toggle="dropdown"
                >
                    <span v-if="modelValue.paddingRight">{{ modelValue.paddingRight }}</span>
                    <span v-else><i class="fa-solid fa-arrow-right"></i></span>
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" @click="set('paddingRight', null)">Не выбран</a></li>
                    <li v-for="n in 5" :key="'pl-'+n-1">
                        <a class="dropdown-item" @click="set('paddingRight', 'pl-' + n-1)">pl-{{ n-1 }}</a>
                    </li>
                </ul>
            </div>

            <!-- paddingBottom -->
            <div class="btn-group  w-100" role="group">
                <button
                    type="button"
                    class="btn btn-outline-primary dropdown-toggle"
                    data-bs-toggle="dropdown"
                >
                    <span v-if="modelValue.paddingBottom">{{ modelValue.paddingBottom }}</span>
                    <span v-else><i class="fa-solid fa-arrow-down"></i></span>
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" @click="set('paddingBottom', null)">Не выбран</a></li>
                    <li v-for="n in 5" :key="'pb-'+n-1">
                        <a class="dropdown-item" @click="set('paddingBottom', 'pb-' + n-1)">pb-{{ n-1 }}</a>
                    </li>
                </ul>
            </div>

            <!-- paddingLeft -->
            <div class="btn-group  w-100" role="group">
                <button
                    type="button"
                    class="btn btn-outline-primary dropdown-toggle"
                    data-bs-toggle="dropdown"
                >
                    <span v-if="modelValue.paddingLeft">{{ modelValue.paddingLeft }}</span>
                    <span v-else><i class="fa-solid fa-arrow-left"></i></span>
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" @click="set('paddingLeft', null)">Не выбран</a></li>
                    <li v-for="n in 5" :key="'ps-'+n-1">
                        <a class="dropdown-item" @click="set('paddingLeft', 'ps-' + n-1)">ps-{{ n-1 }}</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "PaddingControls",

    props: {
        modelValue: {
            type: Object,
            required: true
        }
    },

    methods: {
        set(side, value) {
            const updated = {
                ...this.modelValue,
                [side]: value
            }
            this.$emit("update:modelValue", updated)
        }
    }
}
</script>
