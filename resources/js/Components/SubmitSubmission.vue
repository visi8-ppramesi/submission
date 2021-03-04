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
    </v-card>
</template>

<script>
export default {
    name: 'submit-submission',
    data(){
        return {
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
        };
    },
    created(){
    },
    methods: {
        submit(){
            var self = this
            let subFileCols = [
                'story_concept_files',
                'summary_files',
                'character_design',
                'world_design',
                'pilot_video'
            ]

            subFileCols.forEach((col) => {
                console.log(this.submissionData[col])
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
                    subFileCols.forEach((col) => {
                        var fileData = new FormData()
                        fileData.append('column', col)
                        fileData.append('id', response.data.id)
                        fileData.append('file', self.submissionData[col])

                        reqs.push(axios.post(
                            self.route('submission.store.file'),
                            fileData,
                            {
                                onUploadProgress: (event) => {
                                    console.log(event)
                                }
                            }
                        ))
                    })
                    return Promise.all(reqs)
                })
                .then(axios.spread((...responses) => {
                    self.$inertia.get(self.route('dashboard'))
                }))
                .catch((e) => {
                    console.log(e)
                })
        }
    }
}
</script>
