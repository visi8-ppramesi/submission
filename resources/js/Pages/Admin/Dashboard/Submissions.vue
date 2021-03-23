<template>
    <admin-page-layout>
        <!-- <v-card class="subs-container">
            <div class="text-h5 subs-title">
                Submissions
            </div> -->
        <admin-table
            class="mb-2"
            :items="flattenedSubs"
            :keys="subKeys"
            :routeName="routing"
            title="Submissions"
        ></admin-table>
        <!-- </v-card> -->
    </admin-page-layout>
</template>

<script>
import AdminPageLayout from '../Layouts/AdminPageLayout'
import AdminTable from '../Components/AdminTable'

export default {
    name: 'admin-dashboard-submissions',
    components: {
        AdminPageLayout,
        AdminTable
    },
    props: ['submissions'],
    computed: {
        flattenedSubs(){
            let flatSubs = this.$_.cloneDeep(this.submissions)
            flatSubs.forEach((element, idx) => {
                flatSubs[idx].user_name = flatSubs[idx].user.name
            });
            return flatSubs
        }
    },
    data(){
        return {
            routing: {
                route: 'dashboard.admin.submission',
                var: 'submission'
            },
            subKeys: [
                ['user_name', 'text'],
                ["title", 'text'],
                ["description", 'text'],
                ["story_concept_files", 'file'],
                ["summary_files", 'file'],
                ["character_design", 'file'],
                ["world_design", 'file'],
                ["team_profile", 'text'],
                ["pilot_video", 'file'],
                ["created_at", 'text']
            ]
        }
    },
}
</script>

<style lang="scss">
// .subs-container{
//     padding: 10px;
// }
</style>
