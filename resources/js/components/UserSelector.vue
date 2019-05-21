<template>
	<v-autocomplete
	v-model="selectedUser"
	:items="employees"
    :search-input.sync="searchText"
    item-text="user.name"
    item-value="user.name"
    no-data-text="Данные отсутствуют"
    hide-selected
	chips
    multiple
	color="primary"
	label="Ответственный"
	>
		<template
		slot="selection"
		slot-scope="data"
		>
			<v-chip
			:selected="data.selected"
			color="primary"
			textColor="white"
			close
			@input="remove(data.item)"
			>
				<v-avatar>
					<img :src="'../img/'+data.item.user.img+'.jpg'">
				</v-avatar>
				{{ data.item.user.name }}
			</v-chip>
		</template>

		<template
		slot="item"
		slot-scope="data"
		>
			<template>
				<v-list-tile-avatar>
					<img v-if="data.item.user.img" :src="'../img/'+data.item.user.img+'.jpg'">
				</v-list-tile-avatar>
				<v-list-tile-content>
					<v-list-tile-title>{{data.item.user.name}} {{data.item.user.surname}}</v-list-tile-title>
					<v-list-tile-sub-title>{{data.item.responsibility.name}} - {{data.item.division.abbreviation}}</v-list-tile-sub-title>
				</v-list-tile-content>
			</template>
		</template>
	</v-autocomplete>
</template>
<script>
	export default {
		props:['employees','label'],
		data:()=>({
            selectedUser:[],
            searchText:null
		}),
		methods: {
			remove (item) {
				this.selectedUser=null;
			}
        },
        created(){
            // console.log(this.employees);
        },
		watch:{
			selectedUser(user){
				// console.log(this.label);
				Event.fire(this.label, user);
			}
		}
    }
</script>