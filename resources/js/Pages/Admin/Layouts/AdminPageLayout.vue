<template>
	<v-app id="inspire">
		<!-- <v-system-bar app>
			<v-spacer></v-spacer>
			<v-icon>mdi-square</v-icon>
			<v-icon>mdi-circle</v-icon>
			<v-icon>mdi-triangle</v-icon>
		</v-system-bar> -->
        <v-app-bar app clipped-right v-if="$vuetify.breakpoint.smAndDown">
            <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>
            <v-toolbar-title>Menu</v-toolbar-title>
        </v-app-bar>
		<v-navigation-drawer
            v-model="drawer"
            absolute
            temporary>
			<v-sheet color="grey lighten-4" class="pa-4">
                <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
				<div>{{$page.props.user.email}}</div>
			</v-sheet>
			<v-divider></v-divider>
			<v-list>
				<v-list-item link :href="route('dashboard.admin')">
					<v-list-item-content>
						<v-list-item-title>Dashboard</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
				<v-list-item link :href="route('dashboard.admin.users')">
					<v-list-item-content>
						<v-list-item-title>Users</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
				<v-list-item link :href="route('dashboard.admin.submissions')">
					<v-list-item-content>
						<v-list-item-title>Submissions</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
				<v-list-item @click="$inertia.post(route('logout'))">
					<v-list-item-content>
						<v-list-item-title>Logout</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
			</v-list>
		</v-navigation-drawer>
		<v-navigation-drawer app :permanent="$vuetify.breakpoint.mdAndUp">
			<v-sheet color="grey lighten-4" class="pa-4">
                <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
				<div>{{$page.props.user.email}}</div>
			</v-sheet>
			<v-divider></v-divider>
			<v-list>
				<v-list-item link :href="route('dashboard.admin')">
					<v-list-item-content>
						<v-list-item-title>Dashboard</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
				<v-list-item link :href="route('dashboard.admin.users')">
					<v-list-item-content>
						<v-list-item-title>Users</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
				<v-list-item link :href="route('dashboard.admin.submissions')">
					<v-list-item-content>
						<v-list-item-title>Submissions</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
				<v-list-item @click="$inertia.post(route('logout'))">
					<v-list-item-content>
						<v-list-item-title>Logout</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
			</v-list>
		</v-navigation-drawer>
		<v-main>
			<v-container class="py-8 px-6" fluid>
                <slot></slot>
			</v-container>
		</v-main>
	</v-app>
</template>
<script>
export default {
    name: "admin-page-layout",
    data: function(){
        return {
            drawer: false
        }
    },
    computed: {
        mini(){
            return this.$vuetify.breakpoint.mdAndDown
        }
    }
};
</script>
