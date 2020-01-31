<template>
  <div>
    <v-btn
      outlined
      color="primary"
      @click="addPosition = !addPosition"
      v-if="headUser && localDivisions == undefined"
    >Добавить должность</v-btn>
    <div v-if="localDivisions !== undefined">
      <template v-for="(division, index) in localDivisions">
        <v-row v-if="division.positions.length > 0" :key="'name' + index">
          <v-col cols="12">
            <v-toolbar flat>
              <v-toolbar-title>{{ division.name }}</v-toolbar-title>
              <v-spacer></v-spacer>
                <v-btn
                  outlined
                  color="primary"
                  @click="addPositionToDivision(division.id)"
                  v-if="divisions !== undefined"
                >Добавить должность</v-btn>
            </v-toolbar>
          </v-col>
        </v-row>
        <v-row v-if="division.positions.length > 0" :key="'position' + index">
          <v-col cols="6" v-for="(position, index ) in division.positions" :key="index">
            <position-card :position="position" :user="user" />
          </v-col>
        </v-row>
      </template>
    </div>
    <v-row v-else>
      <v-col cols="6" v-for="(position, index) in positions" :key="index">
        <position-card :position="position" :user="user" :division="divisions" />
      </v-col>
    </v-row>
    <v-dialog eager width="600" v-model="addPosition">
      <add-position :divisionId="divisionId" />
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: {
    positions: {},
    divisionId: Number,
    user: {},
    divisions: Array
  },
  data() {
    return {
      headUser: this.user.position_level.name == "Руководитель",
      localPositions: this.positions,
      localDivisions: this.divisions,
      addPosition: false,
      addResponsibility: false
    };
  },
  methods: {
    addPositionToDivision(divisionId) {
      this.addPosition = true;
      Event.fire("addPositionToDivision", divisionId);
    }
  },
  created() {
    Event.listen("deletePosition", positionId => {
      if (this.divisions == undefined) {
        this.localPositions.forEach((position, index) => {
          if (position.id == positionId) {
            this.localPositions.splice(index, 1);
          }
        });
      } else {
        this.localDivisions.forEach(division => {
          division.positions.forEach((position, index) => {
            if (position.id == positionId) {
              division.positions.splice(index, 1);
            }
          });
        });
      }
    });
    Event.listen("newPosition", position => {
      this.localPositions.push(position);
      this.addPosition = false;
    });
    Event.listen("newPositionToDivision", data => {
      this.localDivisions.forEach(division => {
        if (division.id == data.divisionId) {
          division.positions.push(data.position);
        }
      });
      this.addPosition = false;
    });
    Event.listen("addResponsibility", positionId => {});
    Event.listen(
      "cancelPositionAdding",
      position => (this.addPosition = false)
    );
  }
};
</script>