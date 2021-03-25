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
            <div class="order-1 order-md-0 width-4">
                <v-list-item v-for="(field, idx) in fields" :key="idx">
                    <v-list-item-content>
                        <!-- <div class="field-container">
                            <div class="text-subtitle-1 field-title">{{$_.startCase(field.name)}}: </div>
                            <div class="text-body-2 field-value" v-if="!editingField[field.name]">{{getSubmissionValue(field)}}</div>
                            <div class="field-edit" v-if="!editingField[field.name]"><v-icon @click="editField(field.name)">mdi-tooltip-edit</v-icon></div>
                        </div> -->
                        <v-list-item-title v-show="!editingField[field.name]">{{$_.startCase(field.name)}}</v-list-item-title>
                        <v-list-item-subtitle>
                            <div class="field-container" v-show="!editingField[field.name]">
                                <span class="field-value">{{submission.description}}</span>
                                <span class="field-edit"><v-icon @click="editField(field)">mdi-tooltip-edit</v-icon></span>
                            </div>
                            <div class="field-container" v-show="editingField[field.name]">
                                <v-textarea
                                    v-if="field.type === 'textarea'"
                                    v-model="submissionData[field.name]"
                                    :label="field.label"
                                ></v-textarea>
                                <v-text-field
                                    v-if="field.type === 'text-field'"
                                    v-model="submissionData[field.name]"
                                    :label="field.label"
                                ></v-text-field>
                                <v-file-input
                                    v-if="field.type === 'file-input'"
                                    v-model="submissionData[field.name]"
                                    :label="field.label"
                                    :ref="'file_' + field.name"
                                ></v-file-input>
                            </div>
                        </v-list-item-subtitle>
                        <!-- <v-list-item-title v-if="!isEditing">{{$_.startCase(field.name)}}</v-list-item-title>
                        <v-list-item-subtitle v-if="!isEditing">{{submission.description}}</v-list-item-subtitle>
                        <v-textarea
                            v-model="submissionData.description"
                            label="Description"
                            v-else
                        ></v-textarea> -->
                    </v-list-item-content>
                </v-list-item>
            </div>
        </v-card-text>
        <v-card-actions>
            <div class="px-4 mb-4">
                <v-btn @click="submit" :disabled="saveButtonDisabled">Submit</v-btn>
                <v-btn @click="deleteSubmission" color="error">Delete submission</v-btn>
            </div>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: 'edit-submission',
    created(){
        this.submissionData.title = this.submission.title
        this.submissionData.description = this.submission.description
        this.submissionData.team_profile = this.submission.team_profile
        this.oldValues = this.$_.cloneDeep(this.submission)
        this.diffs = this.diffChanges(this.flatten(this.versions))

        Object.keys(this.submissionData).forEach((subFields) => {
            this.editingField[subFields] = false
        })
    },
    data(){
        return {
            saveButtonDisabled: true,
            fields: [
                {name: 'title', type: 'text-field', label: 'Title'},
                {name: 'description', type: 'textarea', label: 'Description'},
                {name: 'team_profile', type: 'textarea', label: 'Team Profile'},
                {name: 'story_concept_files', type: 'file-input', label: 'Story concept (PDF or Zip file, max 200 MB)'},
                {name: 'summary_files', type: 'file-input', label: 'Logline, synopsis, outline or script (PDF or Zip file, max 200 MB)'},
                {name: 'character_design', type: 'file-input', label: 'Character design (PDF or Zip file, max 200 MB)'},
                {name: 'world_design', type: 'file-input', label: 'World design (PDF or Zip file, max 200 MB)'},
                {name: 'pilot_video', type: 'file-input', label: 'Pilot video (mp4 file)'},
            ],
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
        getEditingElement(field){
            console.log(field)
            switch(field.type){
                case "text-field":
                    return '<v-text-field></v-text-field>'
                case "textarea":
                    return '<v-textarea></v-textarea>'
                case "file-input":
                    return '<v-file-input/>'
                default:
                    return ''
            }
        },
        editField(field){
            this.editingField[field.name] = true
            this.editingField = this.$_.clone(this.editingField)

            if(field.type === 'file-input'){
                console.log(this.$refs['file_' + field.name])
                this.$refs['file_' + field.name][0].$refs.input.click()

            }

            let k = Object.values(this.editingField).reduce((acc, curval) => {return acc || curval})
            this.saveButtonDisabled = !k
        },
        getSubmissionValue(fieldObj){
            switch(fieldObj.type){
                case "text-field":
                case "textarea":
                    return this.submission[fieldObj.name]
                    // break;
                case "file-input":
                    return this.parseLink(this.submission[fieldObj.name])
                    // break;
                default:
                    return ''
            }
        },
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
        deleteSubmission(){
            if(confirm('Are you sure?') == true){
                let ref = document.referrer
                this.$inertia.post(route('submission.delete', {submission: this.submission.id}), {redirect: ref})
            }
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
    }
}
</script>

<style lang="scss">
$percent: 1%;
@for $k from 0 through 10{
    .width-#{$k} {
        width: $k * 10 * $percent;
    }
}
.field-container{
    display: flex;
    flex-direction: row;
    .field-title{
        width: 20%;
        text-align: end;
        padding-right: 5px;
    }
    .field-value{
        display: flex;
        width: 60%;
        align-items: flex-end;
    }
    .field-edit{
        width: 20%;
    }
}
</style>
