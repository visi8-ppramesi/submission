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
                    <v-card>
                        <v-card-title>
                            <div class="px-4">{{submission.title}}</div>
                        </v-card-title>
                        <v-card-text>
                            <v-list-item two-line>
                                <v-list-item-content>
                                    <v-list-item-title>Description</v-list-item-title>
                                    <v-list-item-subtitle>{{submission.description}}</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title><a :href="submission.story_concept_files">Story concept file</a></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title><a :href="submission.summary_files">Logline, synopsis, outline or script file</a></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title><a :href="submission.character_design">Character design file</a></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title><a :href="submission.world_design">World design file</a></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title><a :href="submission.pilot_video">Pilot video</a></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title>Team profile</v-list-item-title>
                                    <v-list-item-subtitle>{{submission.team_profile}}</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                        </v-card-text>
                    </v-card>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
export default {
    name: 'submission',
    components: {
        AppLayout,
    },
    props: [
        'submission'
    ]
}
</script>
