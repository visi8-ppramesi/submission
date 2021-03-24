<template>
    <admin-page-layout>
        <v-container fluid>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title>
                            Submissions Past Week
                        </v-card-title>
                        <v-card-text>
                            <bar-chart :chartdata="processedChartData" :options="options"/>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="6" sm="12">
                    <admin-table
                        class="mb-2"
                        :items="recentUsers"
                        :keys="userKeys"
                        :routeName="userRouting"
                        title="Recent Users"
                    ></admin-table>
                </v-col>
                <v-col cols="12" md="6" sm="12">
                    <admin-table
                        class="mb-2"
                        :items="flattenSub(recentSubs)"
                        :keys="subKeys"
                        :routeName="subRouting"
                        title="Recent Submissions"
                    ></admin-table>
                </v-col>
                <v-col cols="12" md="6" sm="12">
                    <admin-table
                        class="mb-2"
                        :items="flattenSub(recentUpdateSubs)"
                        :keys="subKeys"
                        :routeName="subRouting"
                        title="Recently Updated Submissions"
                    ></admin-table>
                </v-col>
            </v-row>
        </v-container>
    </admin-page-layout>
</template>

<script>
import AdminPageLayout from './Layouts/AdminPageLayout'
import AdminTable from './Components/AdminTable'
import BarChart from './Components/BarChart'

export default {
    name: 'admin-dashboard',
    components: {
        AdminPageLayout,
        AdminTable,
        BarChart,
    },
    methods: {
        flattenSub(sub){
            let flatSubs = this.$_.cloneDeep(sub)
            flatSubs.forEach((element, idx) => {
                flatSubs[idx].user_name = flatSubs[idx].user.name
            });
            return flatSubs
        },
    },
    computed: {
        processedChartData(){
            const getDateBefore = (n) => {
                let today = new Date()
                let day = new Date(today)

                day.setDate(day.getDate() - n)
                return day.toDateString()
            }
            let labels = []
            for(let i = 0; i < 8; i++){
                labels.push(getDateBefore(i))
            }
            return {
                labels: labels.reverse(),
                datasets: [
                    {
                        label: 'Submissions',
                        data: this.aggregatedSubs
                    }
                ]
            }
        }
    },
    data(){
        return {
            userRouting: {
                route: 'dashboard.admin.user',
                var: 'user'
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            },
            userKeys: [
                ["name", "text"],
                ["email", "text"],
                ["full_name", "text"],
            ],
            subRouting: {
                route: 'dashboard.admin.submission',
                var: 'submission'
            },
            subKeys: [
                ['user_name', 'text'],
                ["title", 'text'],
                ["description", 'text']
            ]
        }
    },
    props: ['submissions', 'recentUsers', 'recentSubs', 'recentUpdateSubs', 'aggregatedSubs']
}
</script>

<style lang="scss">
</style>
