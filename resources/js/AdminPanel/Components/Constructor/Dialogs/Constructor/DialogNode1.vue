<template>
    123123123
    <button @click="count">{{ modelValue }}</button>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        modelValue: {
            type: Number,
            required: true,
        },
    },

    emits: ["update:modelValue"],
    setup(props, { emit }) {
        const count = () => {
            emit("update:modelValue", props.modelValue + 1);
        };
        return { count };
    },
});
</script>
