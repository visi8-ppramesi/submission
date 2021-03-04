<template>
    <div>
        <v-file-input v-model="file" />
        <v-btn @click="submit">asdf</v-btn>
    </div>
</template>

<script>
export default {
    created(){
        axios.post('api/testing/').then((response) => {
            console.log(response)
        })
    },
    name:'testing',
    data(){
        return {
            file: null
        }
    },
    props:['user'],
    methods: {
        submit(){

        }
    }
}
</script>
