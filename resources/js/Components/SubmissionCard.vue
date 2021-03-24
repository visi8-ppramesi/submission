<template>
    <v-card :href="route('submission.show', this.submission.id)" class="shadow-xl">
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
            this.$inertia.get(route('submission.show', this.submission.id))
        }
    }
}
</script>
