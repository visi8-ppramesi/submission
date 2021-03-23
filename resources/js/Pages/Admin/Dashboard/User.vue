<template>
    <admin-page-layout>
        <v-card class="mb-2 user-info-card">
            <v-card-title>
                User Information
            </v-card-title>
            <v-card-text class="user-info-container">
                <div v-for="(field, idx) in userFields" :key="idx" class="user-info">
                    <div class="text-h6">{{userFieldsTitle[idx]}}</div>
                    <div class="text-body-2">{{user[field]}}</div>
                </div>
            </v-card-text>
        </v-card>
        <!-- <v-card class="user-subs-container mt-2">
            <div class="text-h5 user-subs-title">
                Submissions
            </div> -->
        <admin-table
            :items="user.submissions"
            :keys="subKeys"
            title="Submissions"
        ></admin-table>
        <!-- </v-card> -->
    </admin-page-layout>
</template>

<script>
import AdminPageLayout from '../Layouts/AdminPageLayout'
import AdminTable from '../Components/AdminTable'

export default {
    name: 'admin-dashboard-user',
    components: {
        AdminPageLayout,
        AdminTable
    },
    data: function(){
        return {
            userFields: ['name', 'email', 'full_name', 'date_of_birth', 'phone', 'id_number', 'portfolio_url'],
            userFieldsTitle: ['Name', 'Email', 'Full Name', 'Date Of Birth', 'Phone', 'ID Number', 'Portfolio URL'],
            keys: [],
            subKeys: [
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
    props: ['user'],
    created(){
        this.keys = Object.keys(this.user)
    },
    methods: {
        parseSubItem(subItem, type){
            if(type === 'text'){
                return `<div>${subItem}</div>`
            }else if(type === 'file'){
                let k = subItem.split('/')
                let m = k[k.length - 1]
                return `<a href="${subItem}">${m}</a>`
            }
        }
    }
}
</script>

<style lang="scss">
// .user-subs{

// }
.user-info-card{
    @media (min-width: 560px){
        width:50%;
    }
    @media (max-width: 560px) and (min-width: 390px){
        width:100%;
    }
    @media (max-width: 390px){
        width:100%;
    }
}
.user-info-container{
    @media (min-width: 560px){
        grid-template-columns: repeat(3, minmax(160px,1fr));
        width:50%;
    }
    @media (max-width: 560px) and (min-width: 390px){
        grid-template-columns: repeat(2, minmax(160px,1fr));
        width:100%;
    }
    @media (max-width: 390px){
        grid-template-columns: repeat(1, minmax(160px,1fr));
        width:100%;
    }
    // padding: 5px;
    display: grid;
    grid-gap: 5px;
    // grid-template-rows: repeat(1, 200px);
}
// .user-subs-container{
//     padding: 5px;
// }
.user-info{
    // @media (min-width: 560px){
    //     width:50%;
    // }
    // @media (max-width: 560px) and (min-width: 390px){
    //     width:100%;
    // }
    // @media (max-width: 390px){
    //     width:100%;
    // }
    height: 90px;
    padding: 10px;
    border: 1px solid #80808024;
}
.user-info-title{
    @media (min-width: 560px){
        grid-column-start: 1;
        grid-column-end: 4;
    }
    @media (max-width: 560px) and (min-width: 390px){
        grid-column-start: 1;
        grid-column-end: 3;
    }
    @media (max-width: 390px){
        grid-column-start: 1;
        grid-column-end: 2;
    }
}
</style>
