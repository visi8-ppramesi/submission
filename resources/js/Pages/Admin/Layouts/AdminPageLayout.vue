<template>
	<v-app id="inspire">
		<!-- <v-system-bar app>
			<v-spacer></v-spacer>
			<v-icon>mdi-square</v-icon>
			<v-icon>mdi-circle</v-icon>
			<v-icon>mdi-triangle</v-icon>
		</v-system-bar> -->
		<v-navigation-drawer app>
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
              drawer: null
          }
      }
	};
</script>
