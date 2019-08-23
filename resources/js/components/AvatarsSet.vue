<template>
    <div>
        <v-tooltip 
        bottom
        v-for="(item,index) in items"
        :key="'avatar-'+index"
        >
            <template v-slot:activator="{ on }">
                <a v-on="on" :href="appPath('users/' + item.id)" class="avatar-item" >
                    <v-avatar
                    color="white"
                    size="40"
                    class="elevation-2 "
                    :style=" {'z-index': (items.length - index)} "
                    >
                        <img :src="photo(item.img)"  alt="avatar">
                    </v-avatar>
                </a>
            </template>
            <span>
                <span v-if="item.img">{{item.name}} {{item.surname}}</span>
                <span v-else>Система</span>
                ({{item.label}})
            </span>
        </v-tooltip>
    </div>
</template>

<script>
export default {
    props:['watchers','assignee','from', 'label'],
    computed:{
        items(){
            let items = this.watchers.map( function(item){
                item['label'] = 'Наблюдатель';
                return item; 
            });

            this.assignee['label'] = 'Исполнитель';
            this.from['label'] = 'Постановщик';

            items.push(this.assignee,this.from);

            return items;
        }
           
    }
}
</script>

<style>
.avatar-item{
    margin-left:-20px;
    display: inline-block;
    -webkit-transition: all 0.3s; /* Safari prior 6.1 */
    transition: all 0.3s;
}
.avatar-item:first-child{
    margin-left:0px;
}

.avatar-item:hover .v-avatar{
    z-index: 99999 !important;
}
.avatar-item:hover{
    margin-left:-10px;
    margin-right: 10px;
}
.avatar-item:first-child:hover{
    margin-left:0px;
    margin-right: 0px;
}


</style>
