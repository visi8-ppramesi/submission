<template>
    <v-card @click="goToSubmission" class="shadow-xl">
        <v-card-title>
            {{submission.title}}
        </v-card-title>
        <v-card-text>
            {{submission.description}}
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    name: 'submission-card',
    props: ['submission'],
    methods: {
        goToSubmission(){
            //get from submission.id
        }
    }
}
</script>
