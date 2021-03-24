<template>
    <v-card class="submission-container">
        <v-card-title>
            {{submission.title}}
        </v-card-title>
        <v-card-text class="d-block d-md-inline-flex justify-start">
            <div class="order-0 order-md-1 width-6">
                <v-list-item class="px-0" one-line>
                    <v-list-item-content>
                        <v-list-item-title>Changes</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item class="px-0 mb-2" v-for="(diff, idx) in diffs" :key="idx">
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
                    <!-- <v-list-item-content>
                        <v-list-item-title>{{dateToString(diff.date)}}</v-list-item-title>
                    </v-list-item-content> -->
                </v-list-item>
            </div>
            <div class="order-1 order-md-0 width-4">
                <v-list-item class="px-0" two-line>
                    <v-list-item-content>
                        <v-list-item-title>Description</v-list-item-title>
                        <v-list-item-subtitle>{{submission.description}}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item class="px-0" one-line>
                    <v-list-item-content>
                        <v-list-item-title>Story concept file</v-list-item-title>
                        <v-list-item-subtitle>
                            <a :href="submission.story_concept_files">{{parseLink(submission.story_concept_files)}}</a>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item class="px-0" one-line>
                    <v-list-item-content>
                        <v-list-item-title>Logline, synopsis, outline or script file</v-list-item-title>
                        <v-list-item-subtitle>
                            <a :href="submission.summary_files">{{parseLink(submission.summary_files)}}</a>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item class="px-0" one-line>
                    <v-list-item-content>
                        <v-list-item-title>Character design file</v-list-item-title>
                        <v-list-item-subtitle>
                            <a :href="submission.character_design">{{parseLink(submission.character_design)}}</a>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item class="px-0" one-line>
                    <v-list-item-content>
                        <v-list-item-title>World design file</v-list-item-title>
                        <v-list-item-subtitle>
                            <a :href="submission.world_design">{{parseLink(submission.world_design)}}</a>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item class="px-0" one-line>
                    <v-list-item-content>
                        <v-list-item-title>Pilot video</v-list-item-title>
                        <v-list-item-subtitle>
                            <a :href="submission.pilot_video">{{parseLink(submission.pilot_video)}}</a>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item class="px-0" one-line>
                    <v-list-item-content>
                        <v-list-item-title>Team profile</v-list-item-title>
                        <v-list-item-subtitle>{{submission.team_profile}}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </div>
        </v-card-text>
        <v-card-actions>
            <v-btn
                color="error"
                @click="deleteSubmission"
            >
                Delete Submission
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: 'show-submission-card',
    props: ['submission', 'versions'],
    created(){
        this.diffs = this.diffChanges(this.flatten(this.versions))
    },
    data(){
        return {
            diffs: []
        }
    },
    methods: {
        deleteSubmission(){
            if(confirm('Are you sure?') == true){
                let ref = document.referrer
                this.$inertia.post(route('submission.delete', {submission: this.submission.id}), {redirect: ref})
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
    }
}
</script>
<style lang="scss">
.submission-container{
    @media (min-width: 601px){
        width:70%;
    }
    @media (max-width: 600px){
        width:100%;
    }
}
</style>
