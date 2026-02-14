<template>
    <div class="mb-3">
        <label class="form-label fw-bold">Внешние отступы (margin)</label>

        <div class="btn-group w-100" role="group">

            <!-- marginTop -->
            <div class="btn-group w-100" role="group">
                <button
                    type="button"
                    class="btn btn-outline-primary dropdown-toggle"
                    data-bs-toggle="dropdown"
                >
                    <span v-if="modelValue.marginTop">{{ modelValue.marginTop }}</span>
                    <span v-else><i class="fa-solid fa-arrow-up"></i></span>
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" @click="set('marginTop', null)">Не выбран</a></li>
                    <li v-for="n in 5" :key="'mt-'+(n-1)">
                        <a class="dropdown-item" @click="set('marginTop', 'mt-' + (n-1))">mt-{{ (n-1) }}</a>
                    </li>
                </ul>
            </div>

            <!-- marginRight -->
            <div class="btn-group w-100" role="group">
                <button
                    type="button"
                    class="btn btn-outline-primary dropdown-toggle "
                    data-bs-toggle="dropdown"
                >
                    <span v-if="modelValue.marginRight">{{ modelValue.marginRight }}</span>
                    <span v-else><i class="fa-solid fa-arrow-right"></i></span>
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" @click="set('marginRight', null)">Не выбран</a></li>
                    <li v-for="n in 5" :key="'me-'+(n-1)">
                        <a class="dropdown-item" @click="set('marginRight', 'me-' + (n-1))">me-{{ (n-1) }}</a>
                    </li>
                </ul>
            </div>

            <!-- marginBottom -->
            <div class="btn-group  w-100" role="group">
                <button
                    type="button"
                    class="btn btn-outline-primary dropdown-toggle"
                    data-bs-toggle="dropdown"
                >
                    <span v-if="modelValue.marginBottom">{{ modelValue.marginBottom }}</span>
                    <span v-else><i class="fa-solid fa-arrow-down"></i></span>
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" @click="set('marginBottom', null)">Не выбран</a></li>
                    <li v-for="n in 6" :key="'mb-'+(n-1)">
                        <a class="dropdown-item" @click="set('marginBottom', 'mb-' + (n-1))">mb-{{ (n-1) }}</a>
                    </li>
                </ul>
            </div>

            <!-- marginLeft -->
            <div class="btn-group  w-100" role="group">
                <button
                    type="button"
                    class="btn btn-outline-primary dropdown-toggle"
                    data-bs-toggle="dropdown"
                >
                    <span v-if="modelValue.marginLeft">{{ modelValue.marginLeft }}</span>
                    <span v-else><i class="fa-solid fa-arrow-left"></i></span>
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" @click="set('marginLeft', null)">Не выбран</a></li>
                    <li v-for="n in 5" :key="'ms-'+(n-1)">
                        <a class="dropdown-item" @click="set('marginLeft', 'ms-' + (n-1))">ms-{{ (n-1) }}</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "MarginControls",

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
