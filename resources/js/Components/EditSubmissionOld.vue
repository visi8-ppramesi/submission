<template>
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
        <v-card-text class="d-block d-md-inline-flex justify-start">
            <div class="order-0 order-md-1" :class="{'width-0' : isEditing, 'width-6' : !isEditing}" v-if="!isEditing">
                <v-list-item one-line>
                    <v-list-item-content>
                        <v-list-item-title v-if="!isEditing">Changes</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item v-for="(diff, idx) in diffs" :key="idx" class="mb-2">
                    <v-card>
                        <v-card-title>
                            {{dateToString(diff.date)}}
                        </v-card-title>
                        <v-card-text>
                            <div v-for="(change, didx) in diff.changes" :key="didx">
                                {{change.name}}: {{change.value}}
                            </div>
                        </v-card-text>
                    </v-card>
                </v-list-item>
            </div>
            <div class="order-1 order-md-0" :class="{'width-10' : isEditing, 'width-4' : !isEditing}">
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
                        <v-list-item-title v-if="!isEditing">Story concept file</v-list-item-title>
                        <v-list-item-subtitle v-if="!isEditing">
                            <a :href="submission.story_concept_files">{{parseLink(submission.story_concept_files)}}</a>
                        </v-list-item-subtitle>
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
                        <v-list-item-title v-if="!isEditing">Logline, synopsis, outline or script file</v-list-item-title>
                        <v-list-item-subtitle v-if="!isEditing">
                            <a :href="submission.summary_files">{{parseLink(submission.summary_files)}}</a>
                        </v-list-item-subtitle>
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
                        <v-list-item-title v-if="!isEditing">Character design file</v-list-item-title>
                        <v-list-item-subtitle v-if="!isEditing">
                            <a :href="submission.character_design">{{parseLink(submission.character_design)}}</a>
                        </v-list-item-subtitle>
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
                        <v-list-item-title v-if="!isEditing">World design file</v-list-item-title>
                        <v-list-item-subtitle v-if="!isEditing">
                            <a :href="submission.world_design">{{parseLink(submission.world_design)}}</a>
                        </v-list-item-subtitle>
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
                        <v-list-item-title v-if="!isEditing">Pilot video</v-list-item-title>
                        <v-list-item-subtitle v-if="!isEditing">
                            <a :href="submission.pilot_video">{{parseLink(submission.pilot_video)}}</a>
                        </v-list-item-subtitle>
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
            </div>
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
        </v-card-text>
        <v-card-actions>
            <div class="px-4 mb-4">
                <v-btn @click="editSubmission" v-if="!isEditing">Edit submission</v-btn>
                <v-btn @click="deleteSubmission" v-if="!isEditing" color="error">Delete submission</v-btn>
                <v-btn @click="submit" v-if="isEditing">Submit</v-btn>
                <v-btn @click="closeEdit" v-if="isEditing">Close</v-btn>
            </div>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: 'edit-submission',
    created(){
        // this.submissionData.title = this.submission.title
        // this.submissionData.description = this.submission.description
        // this.submissionData.team_profile = this.submission.team_profile
        // this.oldValues = this.$_.cloneDeep(this.submission)
        // this.diffs = this.diffChanges(this.flatten(this.versions))

        // Object.keys(this.submissionData).forEach((subFields) => {
        //     this.editingField[subFields] = false
        // })
    },
    data(){
        return {
            editingField: {},
            oldValues: null,
            value: 0,
            overlay: false,
            dialog: false,
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
            diffs: []
        }
    },
    props: [
        'submission',
        'versions'
    ],
    methods: {
        parseLink(link){
            let m = link.split('/')
            return m[m.length - 1]
        },
        processChange(key, value1, value2){
            const changeKey = {
                title: 'Title',
                description: 'Description',
                story_concept_files: 'Story Concept Files',
                summary_files: 'Summary Files',
                character_design: 'Character Design Files',
                world_design: 'World Design Files',
                team_profile: 'Team Profile',
                pilot_video: 'Pilot Video'
            }
            switch(key){
                case 'story_concept_files':
                case 'summary_files':
                case 'character_design':
                case 'world_design':
                case 'pilot_video':
                    let v1 = value1.split('/')
                    let v2 = value2.split('/')
                    value1 = v1[v1.length - 1]
                    value2 = v2[v2.length - 1]
                    console.log(value2)
                    break;
            }

            return {
                value: value1 + ' -> ' + value2,
                name: changeKey[key]
            }
        },
        dateToString(date){
            return new Date(date).toLocaleString('id')
        },
        flatten(obj){
            var k = []
            console.log(obj.submission_items)
            obj.submission_items = JSON.parse(obj.submission_items)
            let {forward, ...myObj} = obj
            k.push(myObj)
            const recurveFlatten = (obj) => {
                if(obj.forward){
                    obj.forward.submission_items = JSON.parse(obj.forward.submission_items)
                    let {forward, ...myObj} = obj.forward
                    k.push(myObj)
                    recurveFlatten(obj.forward)
                }
            }
            recurveFlatten(obj)
            return k
        },
        diffChanges(obj){
            let changes = []
            let len = obj.length
            for(let k = 0; k < len - 1; k++){
                let changed = false
                let myChanges = []
                Object.keys(obj[k].submission_items).forEach((v, i) => {
                    if(obj[k].submission_items[v] !== obj[k + 1].submission_items[v] && v !== 'created_at' && v !== 'updated_at'){
                        changed = true
                        myChanges.push(this.processChange(v, obj[k].submission_items[v], obj[k + 1].submission_items[v]))
                        // myChanges.push({
                        //     value: obj[k].submission_items[v] + ' -> ' + obj[k + 1].submission_items[v],
                        //     name: v
                        // })
                    }
                })
                if(changed){
                    changes.push({
                        changes: myChanges,
                        date: obj[k].created_at
                    })

                }
            }
            return changes
        },
        submit(){
            let subFileCols = [
                'story_concept_files',
                'summary_files',
                'character_design',
                'world_design',
                'pilot_video'
            ]
            var totalSize = 0
            var current = 0
            var self = this

            this.overlay = true

            subFileCols.forEach((col) => {
                if(this.submissionData[col]){
                    console.log(this.submissionData[col])
                    totalSize += this.submissionData[col].size
                }
            })

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
                                    current += event.loaded
                                    self.value = Math.round((current/totalSize) * 100)
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
                axios.post(this.route('submission.restore', {submission: this.submission.id}), this.oldValues)
                    .then((response) => {
                        this.dialog = true
                        this.overlay = false
                    })
                    .catch((e) => {
                        alert('something fucked up really bad')
                    })
                console.log(e)
            })
        },
        editSubmission(){
            this.isEditing = true
        },
        closeEdit(){
            this.isEditing = false
        },
        deleteSubmission(){
            if(confirm('Are you sure?') == true){
                let ref = document.referrer
                this.$inertia.post(route('submission.delete', {submission: this.submission.id}), {redirect: ref})
            }
        },
    }
}
</script>

<style lang="scss">

</style>
