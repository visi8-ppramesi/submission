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
                    <edit-submission
                        :submission="submission"
                        :versions="versions"
                    ></edit-submission>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import EditSubmission from '../Components/EditSubmission'

export default {
    name: 'submission',
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
            // editingField: {},
            // oldValues: null,
            // value: 0,
            // overlay: false,
            // dialog: false,
            // isEditing: false,
            // submissionData:{
            //     title: '',
            //     description: '',
            //     story_concept_files: null,
            //     summary_files: null,
            //     character_design: null,
            //     world_design: null,
            //     team_profile: '',
            //     pilot_video: null
            // },
            // diffs: []
        }
    },
    components: {
        AppLayout,
        EditSubmission,
    },
    props: [
        'submission',
        'versions'
    ],
    methods: {
        // parseLink(link){
        //     let m = link.split('/')
        //     return m[m.length - 1]
        // },
        // processChange(key, value1, value2){
        //     const changeKey = {
        //         title: 'Title',
        //         description: 'Description',
        //         story_concept_files: 'Story Concept Files',
        //         summary_files: 'Summary Files',
        //         character_design: 'Character Design Files',
        //         world_design: 'World Design Files',
        //         team_profile: 'Team Profile',
        //         pilot_video: 'Pilot Video'
        //     }
        //     switch(key){
        //         case 'story_concept_files':
        //         case 'summary_files':
        //         case 'character_design':
        //         case 'world_design':
        //         case 'pilot_video':
        //             let v1 = value1.split('/')
        //             let v2 = value2.split('/')
        //             value1 = v1[v1.length - 1]
        //             value2 = v2[v2.length - 1]
        //             console.log(value2)
        //             break;
        //     }

        //     return {
        //         value: value1 + ' -> ' + value2,
        //         name: changeKey[key]
        //     }
        // },
        // dateToString(date){
        //     return new Date(date).toLocaleString('id')
        // },
        // flatten(obj){
        //     var k = []
        //     obj.submission_items = JSON.parse(obj.submission_items)
        //     let {forward, ...myObj} = obj
        //     k.push(myObj)
        //     const recurveFlatten = (obj) => {
        //         if(obj.forward){
        //             obj.forward.submission_items = JSON.parse(obj.forward.submission_items)
        //             let {forward, ...myObj} = obj.forward
        //             k.push(myObj)
        //             recurveFlatten(obj.forward)
        //         }
        //     }
        //     recurveFlatten(obj)
        //     return k
        // },
        // diffChanges(obj){
        //     let changes = []
        //     let len = obj.length
        //     for(let k = 0; k < len - 1; k++){
        //         let changed = false
        //         let myChanges = []
        //         Object.keys(obj[k].submission_items).forEach((v, i) => {
        //             if(obj[k].submission_items[v] !== obj[k + 1].submission_items[v] && v !== 'created_at' && v !== 'updated_at'){
        //                 changed = true
        //                 myChanges.push(this.processChange(v, obj[k].submission_items[v], obj[k + 1].submission_items[v]))
        //                 // myChanges.push({
        //                 //     value: obj[k].submission_items[v] + ' -> ' + obj[k + 1].submission_items[v],
        //                 //     name: v
        //                 // })
        //             }
        //         })
        //         if(changed){
        //             changes.push({
        //                 changes: myChanges,
        //                 date: obj[k].created_at
        //             })

        //         }
        //     }
        //     return changes
        // },
        // submit(){
        //     let subFileCols = [
        //         'story_concept_files',
        //         'summary_files',
        //         'character_design',
        //         'world_design',
        //         'pilot_video'
        //     ]
        //     var totalSize = 0
        //     var current = 0
        //     var self = this

        //     this.overlay = true

        //     subFileCols.forEach((col) => {
        //         if(this.submissionData[col]){
        //             console.log(this.submissionData[col])
        //             totalSize += this.submissionData[col].size
        //         }
        //     })

        //     let fileReqs = []
        //     subFileCols.forEach((col) => {
        //         if(this.submissionData[col] !== null){
        //             var fileData = new FormData()
        //             fileData.append('column', col)
        //             // fileData.append('id', this.submission.id)
        //             fileData.append('file', this.submissionData[col])

        //             fileReqs.push(
        //                 axios.post(
        //                     this.route('submission.update.file', {submission: this.submission.id}),
        //                     fileData,
        //                     {
        //                         onUploadProgress: (event) => {
        //                             current += event.loaded
        //                             self.value = Math.round((current/totalSize) * 100)
        //                             console.log(event)
        //                         }
        //                     }
        //                 )
        //             )
        //         }
        //     })
        //     Promise.all(fileReqs)
        //     .then(axios.spread((...responses) => {
        //         let subData = {
        //             title: this.submissionData.title,
        //             description: this.submissionData.description,
        //             team_profile: this.submissionData.team_profile,

        //         }
        //         return axios.post(this.route('submission.update', {submission: this.submission.id}), subData)
        //     }))
        //     .then(axios.spread((...responses) => {
        //         return axios.post(this.route('submissionversion.store', {submission: this.submission.id}), null)
        //     }))
        //     .then((response) => {
        //         this.$inertia.get(self.route('dashboard'))
        //     })
        //     .catch((e) => {
        //         axios.post(this.route('submission.restore', {submission: this.submission.id}), this.oldValues)
        //             .then((response) => {
        //                 this.dialog = true
        //                 this.overlay = false
        //             })
        //             .catch((e) => {
        //                 alert('something fucked up really bad')
        //             })
        //         console.log(e)
        //     })
        // },
        // editSubmission(){
        //     this.isEditing = true
        // },
        // closeEdit(){
        //     this.isEditing = false
        // },
        // deleteSubmission(){
        //     if(confirm('Are you sure?') == true){
        //         let ref = document.referrer
        //         this.$inertia.post(route('submission.delete', {submission: this.submission.id}), {redirect: ref})
        //     }
        // },
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

</style>
