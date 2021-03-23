<template>
    <admin-page-layout>
        <!-- <v-card class="users-container mt-2">
            <div class="text-h5 users-title">
                Users
            </div> -->
        <admin-table
            class="mb-2"
            :items="users"
            :keys="tableKeys"
            :routeName="routing"
            title="Users"
        ></admin-table>
        <!-- </v-card> -->
    </admin-page-layout>
</template>

<script>
import AdminPageLayout from '../Layouts/AdminPageLayout'
import AdminTable from '../Components/AdminTable'

export default {
    name: 'admin-dashboard-users',
    components: {
        AdminPageLayout,
        AdminTable
    },
    props: ['users'],
    data: function(){
        return {
            routing: {
                route: 'dashboard.admin.user',
                var: 'user'
            },
            tableKeys: [
                ["name", "text"],
                ["email", "text"],
                ["full_name", "text"],
                ["date_of_birth", "text"],
                ["phone", "text"],
                ["id_number", "text"],
                ["portfolio_url", "text"],
            ]
        }
    }
}
</script>

<style lang="scss">
.users-container{
    padding: 10px;
}
</style>
