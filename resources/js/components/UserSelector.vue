<template>
	<v-autocomplete
	v-model="selectedUsers"
	:items="users"
    :search-input.sync="searchText"
    item-text="name"
	return-object
    no-data-text="Данные отсутствуют"
    hide-selected
	chips
    multiple
	color="primary"
	:label="label"
	:prepend-icon="icon"
	:hint="hint"
	persistent-hint
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
					<img :src="photo(data.item.img)">
				</v-avatar>
				{{ data.item.name }}
			</v-chip>
		</template>

		<template
		slot="item"
		slot-scope="data"
		>
			<template>
				<v-list-tile-avatar>
					<img v-if="data.item.img" :src="photo(data.item.img)">
				</v-list-tile-avatar>
				<v-list-tile-content>
					<v-list-tile-title>{{data.item.name}} {{data.item.surname}}</v-list-tile-title>
					<v-list-tile-sub-title>{{data.item.responsibility.name}} - {{data.item.division.abbreviation}}</v-list-tile-sub-title>
				</v-list-tile-content>
			</template>
		</template>
	</v-autocomplete>
</template>
<script>
	export default {
		props:['users','label','icon','name','hint'],
		data:()=>({
            selectedUsers:[],
            searchText:null
		}),
		methods: {
			remove (item) {
				for( var i = 0; i < this.selectedUsers.length; i++){ 
					if ( this.selectedUsers[i] === item) {
						this.selectedUsers.splice(i, 1); 
					}
				}
			}
        },
        created(){
        },
		watch:{
			selectedUsers(selectedUsers){
				Event.fire(this.name, selectedUsers);
			}
		}
    }
</script>