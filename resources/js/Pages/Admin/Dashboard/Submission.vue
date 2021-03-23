<template>
    <admin-page-layout>
        <show-submission-card
            :submission="submission"
            :versions="versions"
        >
        </show-submission-card>
    </admin-page-layout>
</template>

<script>
import AdminPageLayout from '../Layouts/AdminPageLayout'
import AdminTable from '../Components/AdminTable'
import ShowSubmissionCard from '../Components/ShowSubmissionCard'

export default {
    name: 'admin-dashboard-submissions',
    components: {
        AdminPageLayout,
        AdminTable,
        ShowSubmissionCard
    },
    props: ['submission', 'versions'],
}
</script>
