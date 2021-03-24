<template>
    <admin-page-layout>
    </admin-page-layout>
</template>

<script>
import AdminPageLayout from './Layouts/AdminPageLayout'
import AdminTable from './Components/AdminTable'

export default {
    name: 'admin-dashboard',
    components: {
        AdminPageLayout,
        AdminTable,
    },
}
</script>
