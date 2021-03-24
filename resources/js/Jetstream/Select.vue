<template>
    <select
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        @input="$emit('input', $event.target.value)"
        ref="input"
        :value="value"
    >
        <option v-for="(option, idx) in options" :key="idx" :value="option">{{option}}</option>
    </select>
</template>

<script>
    export default {
        props: ['value', 'options'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

