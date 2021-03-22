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
                            <div class="px-4">
                                <div v-if="!isEditing">{{submission.title}}</div>
                                <v-text-field
                                    v-else
                                    v-model="submissionData.title"
                                    :rules="[v => !!v || 'Title required']"
                                    label="Title"
                                    required
                                />
                            </div>
                        </v-card-title>
                        <v-card-text>
                            <v-list-item two-line>
                                <v-list-item-content>
                                    <v-list-item-title v-if="!isEditing">Description</v-list-item-title>
                                    <v-list-item-subtitle v-if="!isEditing">{{submission.description}}</v-list-item-subtitle>
                                    <v-textarea
                                        v-model="submissionData.description"
                                        label="Description"
                                        v-else
                                    ></v-textarea>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title v-if="!isEditing"><a :href="submission.story_concept_files">Story concept file</a></v-list-item-title>
                                    <v-file-input
                                        v-else
                                        v-model="submissionData.story_concept_files"
                                        accept="application/pdf,.zip,.rar"
                                        label="Story concept (PDF or Zip file, max 200 MB)"
                                    ></v-file-input>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title v-if="!isEditing"><a :href="submission.summary_files">Logline, synopsis, outline or script file</a></v-list-item-title>
                                    <v-file-input
                                        v-else
                                        v-model="submissionData.summary_files"
                                        accept="application/pdf,.zip,.rar"
                                        label="Logline, synopsis, outline or script (PDF or Zip file, max 200 MB)"
                                    ></v-file-input>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title v-if="!isEditing"><a :href="submission.character_design">Character design file</a></v-list-item-title>
                                    <v-file-input
                                        v-else
                                        v-model="submissionData.character_design"
                                        accept="application/pdf,.zip,.rar"
                                        label="Character design (PDF or Zip file, max 200 MB)"
                                    ></v-file-input>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title v-if="!isEditing"><a :href="submission.world_design">World design file</a></v-list-item-title>
                                    <v-file-input
                                        v-else
                                        v-model="submissionData.world_design"
                                        accept="application/pdf,.zip,.rar"
                                        label="World design (PDF or Zip file, max 200 MB)"
                                    ></v-file-input>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title v-if="!isEditing"><a :href="submission.pilot_video">Pilot video</a></v-list-item-title>
                                    <v-file-input
                                        v-else
                                        v-model="submissionData.pilot_video"
                                        accept="video/mp4,video/x-m4v,video/*"
                                        label="Pilot video (mp4 file)"
                                    ></v-file-input>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item one-line>
                                <v-list-item-content>
                                    <v-list-item-title v-if="!isEditing">Team profile</v-list-item-title>
                                    <v-list-item-subtitle v-if="!isEditing">{{submission.team_profile}}</v-list-item-subtitle>
                                    <v-textarea
                                        v-else
                                        v-model="submissionData.team_profile"
                                        label="Team profile"
                                    ></v-textarea>
                                </v-list-item-content>
                            </v-list-item>
                        </v-card-text>
                        <v-card-actions>
                            <div class="px-4 mb-4">
                                <v-btn @click="editSubmission" v-if="!isEditing">Edit submission</v-btn>
                                <v-btn @click="submit" v-else>Submit</v-btn>
                            </div>
                        </v-card-actions>
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
    created(){
        this.submissionData.title = this.submission.title
        this.submissionData.description = this.submission.description
        this.submissionData.team_profile = this.submission.team_profile
        console.log('sadasdfhjksdfahgjksdfahjk')
    },
    data(){
        return {
            isEditing: false,
            submissionData:{
                title: '',
                description: '',
                story_concept_files: null,
                summary_files: null,
                character_design: null,
                world_design: null,
                team_profile: '',
                pilot_video: null
            },
        }
    },
    components: {
        AppLayout,
    },
    props: [
        'submission'
    ],
    methods: {
        submit(){
            let subFileCols = [
                'story_concept_files',
                'summary_files',
                'character_design',
                'world_design',
                'pilot_video'
            ]
            let fileReqs = []
            subFileCols.forEach((col) => {
                if(this.submissionData[col] !== null){
                    var fileData = new FormData()
                    fileData.append('column', col)
                    // fileData.append('id', this.submission.id)
                    fileData.append('file', this.submissionData[col])

                    fileReqs.push(
                        axios.post(
                            this.route('submission.update.file', {submission: this.submission.id}),
                            fileData,
                            {
                                onUploadProgress: (event) => {
                                    console.log(event)
                                }
                            }
                        )
                    )
                }
            })
            Promise.all(fileReqs)
            .then(axios.spread((...responses) => {
                let subData = {
                    title: this.submissionData.title,
                    description: this.submissionData.description,
                    team_profile: this.submissionData.team_profile,

                }
                return axios.post(this.route('submission.update', {submission: this.submission.id}), subData)
            }))
            .then(axios.spread((...responses) => {
                return axios.post(this.route('submissionversion.store', {submission: this.submission.id}), null)
            }))
            .then((response) => {
                this.$inertia.get(self.route('dashboard'))
            })
            .catch((e) => {
                console.log(e)
            })
        },
        editSubmission(){
            this.isEditing = true
        }
    }
}
</script>
