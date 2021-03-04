<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-transparent overflow-hidden sm:rounded-lg">
                    <submit-submission />
                    <v-container class="px-md-12 py-md-6 mb-6">
                        <v-row>
                            <v-col>
                                <div class="h1" v-if="submissions.length > 0">
                                    Your submissions
                                </div>
                                <div class="h1" v-else>
                                    You have no submission
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="3" v-for="(submission, idx) in submissions" :key="idx">
                                <submission-card :submission='submission' />
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    import SubmitSubmission from '../Components/SubmitSubmission'
    import SubmissionCard from '../Components/SubmissionCard'

    export default {
        components: {
            AppLayout,
            Welcome,
            SubmitSubmission,
            SubmissionCard
        },
        props:{
            submissions: Array,
        }
    }
</script>
