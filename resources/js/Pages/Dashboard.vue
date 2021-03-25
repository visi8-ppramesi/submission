<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight px-md-12">
                Dashboard
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-transparent overflow-hidden sm:rounded-lg">
                    <div class="px-md-16 py-md-6">
                        <v-btn @click="expand = !expand">Create new submission</v-btn>
                    </div>
                    <v-expand-transition>
                        <submit-submission v-show="expand" />
                    </v-expand-transition>
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
    import SubmitSubmission from '../Components/SubmitSubmission'
    import SubmissionCard from '../Components/SubmissionCard'

    export default {
        data(){
            return {
                expand: false
            }
        },
        components: {
            AppLayout,
            SubmitSubmission,
            SubmissionCard
        },
        props:{
            submissions: Array
        }
    }
</script>
