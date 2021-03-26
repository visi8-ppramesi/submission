<template>
    <v-card class="px-md-12 py-md-6 mb-6 shadow-xl">
        <v-card-title>Submit New Submission</v-card-title>
        <v-card-text>
            <v-text-field
                v-model="submissionData.title"
                :rules="[v => !!v || 'Title required']"
                label="Submission title"
                required
            />
            <v-textarea
                v-model="submissionData.description"
                label="Description"
            ></v-textarea>
            <v-file-input
                v-model="submissionData.story_concept_files"
                accept="application/pdf,.zip,.rar"
                label="Story concept (PDF or Zip file, max 200 MB)"
            ></v-file-input>
            <v-file-input
                v-model="submissionData.summary_files"
                accept="application/pdf,.zip,.rar"
                label="Logline, synopsis, outline or script (PDF or Zip file, max 200 MB)"
            ></v-file-input>
            <v-file-input
                v-model="submissionData.character_design"
                accept="application/pdf,.zip,.rar"
                label="Character design (PDF or Zip file, max 200 MB)"
            ></v-file-input>
            <v-file-input
                v-model="submissionData.world_design"
                accept="application/pdf,.zip,.rar"
                label="World design (PDF or Zip file, max 200 MB)"
            ></v-file-input>
            <v-textarea
                v-model="submissionData.team_profile"
                label="Team profile"
            ></v-textarea>
            <v-file-input
                v-model="submissionData.pilot_video"
                accept="video/mp4,video/x-m4v,video/*"
                label="Pilot video (mp4 file)"
            ></v-file-input>
        </v-card-text>
        <v-card-actions>
            <v-btn
                @click="submit"
            >
                Submit
            </v-btn>
        </v-card-actions>
        <v-dialog
            v-model="dialog"
            width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="headline">Error</span>
                </v-card-title>
                <v-card-text>
                    <v-alert
                        dense
                        outlined
                        type="error"
                    >
                        Something went wrong. Please retry.
                    </v-alert>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="dialog = false"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-overlay :value="overlay">
            <v-progress-circular
                :rotate="-90"
                :size="100"
                :width="15"
                :value="value"
                color="blue"
            >
                {{ value }}
            </v-progress-circular>
        </v-overlay>
    </v-card>
</template>

<script>
export default {
    name: 'submit-submission',
    data(){
        return {
            value: 0,
            overlay: false,
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
            dialog: false
        };
    },
    created(){
    },
    methods: {
        submit(){
            this.overlay = true
            var self = this
            var subId
            var totalSize = 0
            var current = 0
            let subFileCols = [
                'story_concept_files',
                'summary_files',
                'character_design',
                'world_design',
                'pilot_video'
            ]

            subFileCols.forEach((col) => {
                if(this.submissionData[col]){
                    console.log(this.submissionData[col])
                    totalSize += this.submissionData[col].size
                }
            })
            let subData = {
                title: this.submissionData.title,
                description: this.submissionData.description,
                team_profile: this.submissionData.team_profile
            }
            // this.$inertia.post(this.route('submission.store'), this.submissionData)
            axios.post(this.route('submission.store'), subData)
                .then((response) => {
                    let reqs = []
                    subId = response.data.id
                    subFileCols.forEach((col) => {
                        var fileData = new FormData()
                        fileData.append('column', col)
                        fileData.append('id', response.data.id)
                        // fileData.append('file', response.data[col])
                        fileData.append('file', self.submissionData[col])

                        reqs.push(axios.post(
                            self.route('submission.store.file'),
                            fileData,
                            {
                                onUploadProgress: (event) => {
                                    console.log(event)
                                    current += event.loaded
                                    self.value = Math.round((current/totalSize) * 100)
                                }
                            }
                        ))
                    })
                    return Promise.all(reqs)
                })
                .then(axios.spread((...responses) => {
                    return axios.post(this.route('submissionversion.store.first', {submission: subId}), null)
                }))
                .then((response) => {
                    self.$inertia.get(self.route('dashboard'))
                })
                .catch((e) => {
                    if(subId){
                        axios.post(route('submission.delete', {submission: subId}), {redirect: ''})
                    }
                    this.overlay = false
                    this.dialog = true
                    console.log(e)
                })
        }
    }
}
</script>
