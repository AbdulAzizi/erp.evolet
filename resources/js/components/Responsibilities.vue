<template>
  <div>
    <v-row v-if="positions.length > 0">
      <v-col cols="6" v-for="(position, index ) in positions" :key="index">
        <position-card :position="position" />
      </v-col>
    </v-row>
    <div class="d-flex justify-center" v-else>
      <p class="grey--text">Объязанностей пока нет, но вы можете добавить их прямо сейчас</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    positions: {}
  },
  data() {
    return {
      localPositions: this.positions
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
  }
};
</script>