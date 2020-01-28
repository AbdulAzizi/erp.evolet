<template>
  <div>
    <v-btn outlined color="primary" @click="addPosition = !addPosition">Добавить должность</v-btn>
    <v-row v-if="positions.length > 0">
      <v-col cols="6" v-for="(position, index ) in positions" :key="index">
        <position-card :position="position" />
      </v-col>
    </v-row>
    <div class="d-flex justify-center" v-else>
      <p class="grey--text">Объязанностей пока нет, но вы можете добавить их прямо сейчас</p>
    </div>
    <v-dialog width="600" v-model="addPosition">
      <add-position :divisionId="divisionId"/>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: {
    positions: {},
    divisionId: Number
  },
  data() {
    return {
      localPositions: this.positions,
      addPosition: false,
      addResponsibility: false
    };
  },
  created() {
    Event.listen("deletePosition", positionId => {
      this.localPositions.forEach((position, index) => {
        if (position.id == positionId) {
          this.localPositions.splice(index, 1);
        }
      });
    });
    Event.listen('newPosition', position => {
      this.localPositions.push(position);
      this.addPosition = false;
    });
    Event.listen('addResponsibility', positionId => {
      
    })
  }
};
</script>