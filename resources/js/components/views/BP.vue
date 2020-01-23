<template>
<div>
  <div> 
    <right-drawer/>
    <v-dialog v-model="addProcessDialog" width="400">
      <v-card>
        <v-toolbar color="primary" dense dark flat>
          <v-toolbar-title>Добавить процесс</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="createProcessForm">
            <form-field
              :field="{
                label: 'Название процесса',
                type: 'string',
                name: 'processName',
                rules: ['required'],
                icon: 'mdi-atom-variant'
                }"
              v-model="processName"
              class="mt-5"
            ></form-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="addProcessDialog = false">отмена</v-btn>
          <v-btn color="primary" class="float-right" @click="createProcess()">отправить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="processDialog" width="600">
      <v-card>
        <v-form :action="`/deleteProcess/${processData.id}`" method="GET">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title v-if="!updateProcessField">{{processData.name}}</v-toolbar-title>
            <v-toolbar-title v-else class="ma-2">
              <v-form>
                <v-text-field
                  v-model="processUpdateFieldName"
                  label="Название"
                  required
                  solo
                  flat
                  hide-details
                  background-color="primary darken-1"
                  class="mb-1"
                  append-icon="mdi-check"
                  append-outer-icon="mdi-close"
                  @click:append="updateProcessName(processData.id)"
                  @click:append-outer="cancelProcessUpdate()"
                  :value="processUpdateFieldName"
                ></v-text-field>
              </v-form>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn fab small text @click="updateProcessField = true">
              <v-icon small>mdi-pencil</v-icon>
            </v-btn>
            <v-btn fab small text type="submit">
              <v-icon small>mdi-delete</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text v-if="!processData.len">
            <h4 class="grey--text">В этом процессе нету задач</h4>
          </v-card-text>
          <v-card-text v-if="processData.len">
            <template v-for="(elem, index) in processData.tasks">
              <div :key="'info' + index">
                <process-task-actions :task="elem" />
                <h5 class="font-weight-medium grey--text text--darken-1">Задача</h5>
                <h4 class="font-weight-medium grey--text text--darken-3">{{elem.title}}</h4>
                <h5 class="font-weight-medium grey--text text--darken-1">Исполнитель</h5>
                <h4
                  class="font-weight-medium grey--text text--darken-3"
                >{{elem.position.name}}</h4>
                <h5 class="font-weight-medium grey--text text--darken-1">Запланированное время</h5>
                <h4 class="font-weight-medium grey--text text--darken-3">
                  <span>{{durObj(elem.planned_time)}}</span>
                </h4>
                <h5 class="font-weight-medium grey--text text--darken-1">Описание</h5>
                <h4
                  class="font-weight-medium grey--text text--darken-3"
                >{{!elem.description ? 'Описания нет' : elem.description}}</h4>
              </div>
              <v-list :key="'list' + index" dense class="pa-0 mb-2">
                <v-list-group v-for="(form, index) in elem.forms" :key="index">
                  <template v-slot:activator>
                    <h4
                      class="font-weight-medium grey--text text--darken-1"
                    >Поля которые заполняет {{elem.position.name}}</h4>
                  </template>
                  <v-simple-table dense>
                    <tbody>
                      <tr v-for="(field, index) in form.fields" :key="index">
                        <td>{{field.label}}</td>
                      </tr>
                    </tbody>
                  </v-simple-table>
                </v-list-group>
              </v-list>
              <v-divider v-if="index !== processData.len - 1" :key="'divider' + index" class="mb-2"></v-divider>
            </template>
          </v-card-text>
        </v-form>
        <v-card-actions>
          <v-spacer></v-spacer>
          <process-task-add :processId="processData.id" />
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="deleteTether" width="400">
      <v-card>
        <v-form :action="`/tether/delete/${tetherData.id}`" method="get">
          <v-card-title class="headline">Вы хотите удалить связь?</v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red lighten-2" text @click="deleteTether = false">Отмена</v-btn>
            <v-btn color="primary" text type="submit">Удалить</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
    <v-dialog v-model="tetherNameDialog" width="400" persistent>
      <v-card>
        <v-toolbar color="primary" dense dark flat>
          <v-toolbar-title>Название ветки</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="tetherNameForm">
            <form-field
              :field="{
              label: 'Название ветки',
              type: 'string',
              name: 'tetherName',
              rules: ['required'],
              icon: 'mdi-axis-y-arrow'
            }"
              v-model="tetherName"
              class="mt-5"
            ></form-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="cancelTetherCreation()">отмена</v-btn>
          <v-btn color="primary" @click="createTether()">Добавить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    </div>
    <div>
    <div id="cyto"></div>
    <v-btn color="primary" dark fab right bottom fixed @click="addProcessDialog = true">
      <v-icon>mdi-plus</v-icon>
    </v-btn>
    </div>
    </div>
</template>

<script>
// Import packages for diagram
import cytoscape from "cytoscape";
import edgehandles from "cytoscape-edgehandles";
import nodeHtmlLabel from "cytoscape-node-html-label";
import klay from "cytoscape-klay";

nodeHtmlLabel(cytoscape);
cytoscape.use(edgehandles);

export default {
  props: {
    processes: Array
  },
  data() {
    return {
      drawer: null,
      cytoScape: null,
      addProcessDialog: false,
      processUpdateFieldName: null,
      processName: null,
      tetherName: null,
      localProcesses: this.processes,
      nodes: null,
      tetherNameDialog: false,
      fromProcess: null,
      toProcess: null,
      processDialog: false,
      updateProcessField: false,
      deleteTether: false,
      singleTask: null,
      processData: {
        name: null,
        id: null,
        tasks: null,
        len: null
      },
      tetherData: {
        id: null
      }
    };
  },
  mounted() {
    this.nodes = this.extractEdgesNodesFromProcess();
    this.drawNodes();
  },
  methods: {
    extractEdgesNodesFromProcess() {
      let nodes = [];

      for (const process of this.localProcesses) {
        const node = {
          group: "nodes",
          data: {
            id: process.id,
            label: process.name
          }
        };
        let backEdges = process.back_tethers.map(bt => ({
          group: "edges",
          data: {
            id: "b" + bt.id,
            source: bt.from_process_id,
            target: bt.to_process_id,
            label: bt.action_text
          }
        }));

        let frontEdges = process.front_tethers.map(ft => ({
          group: "edges",
          data: {
            id: "f" + ft.id,
            source: ft.from_process_id,
            target: ft.to_process_id,
            label: ft.action_text
          }
        }));

        nodes = [...nodes, node, ...backEdges, ...frontEdges];
      }

      return nodes;
    },

    // When process card is clicked, data prepares to show in process dialog card
    prepareTetherData(fromProcess, toProcess) {
      this.fromProcess = fromProcess;
      this.toProcess = toProcess;
      this.tetherNameDialog = true;
    },

    // Creating tether function
    createTether() {
      const form = this.$refs.tetherNameForm;
      if (form.validate()) {
        axios
          .post("/api/tether", {
            from_process_id: this.fromProcess.id,
            to_process_id: this.toProcess.id,
            action_text: this.tetherName
          })
          .then(res => {
            this.tetherNameDialog = false;
            form.reset();
            this.synch(res.data);
            Event.fire("notify", [`Связь ${this.tetherName} создана`]);
          })
          .catch(err => err.messages);
      }
    },
    createProcess() {
      const form = this.$refs.createProcessForm;
      if (form.validate()) {
        axios
          .post("/api/process", {
            name: this.processName
          })
          .then(res => {
            this.synch(res.data);
            this.addProcessDialog = false;
            Event.fire("notify", [`Процесс ${this.processName} создана`]);
          })
          .catch(err => err.messages);
      }
    },
    updateProcessName(id) {
      axios
        .put(`/api/process/update/${id}`, {
          name: this.processUpdateFieldName
        })
        .then(res => {
          this.updateProcessField = false;
          this.processData.name = this.processUpdateFieldName;
          this.synch(res.data);
          Event.fire("notify", [
            `Процесс ${this.processUpdateFieldName} обновлен`
          ]);
        })
        .catch(err => err.messages);
    },
    showProcessData(name, id) {
      this.processData.name = name;
      this.processUpdateFieldName = this.processData.name;
      this.processData.id = id;
      this.processDialog = true;

      this.localProcesses.forEach(elem => {
        if (elem.id == this.processData.id) {
          this.processData.tasks = elem.process_tasks;
          this.processData.len = elem.process_tasks.length;
        }
      });
    },

    synch(data) {
      this.localProcesses = data;
      this.nodes = this.extractEdgesNodesFromProcess();
      this.drawNodes();
    },

    // This function prepares data to send to the server to delete tether.
    prepareDataBeforeTetherDelete(element) {
      let edgeId = element.target._private.data.id.replace(/^\D+/g, "");
      this.tetherData.id = +edgeId;
      this.deleteTether = true;
    },

    cancelProcessUpdate() {
      this.updateProcessField = false;
    },
    cancelTetherCreation() {
      this.tetherNameDialog = false;
      this.drawNodes();
    },
    drawNodes() {
      const cytoData = {
        container: document.getElementById("cyto"),
        elements: this.nodes,
        style: [
          {
            selector: "node",
            style: {
              "text-valign": "center",
              "text-halign": "center",
              shape: "round-rectangle",
              height: 60,
              width: 100,
              "background-color": "white"
            }
          },
          {
            selector: "edge",
            style: {
              label: "data(label)",
              "font-size": "8px",
              width: 2,
              "line-color": "#ccc",
              "target-arrow-color": "#ccc",
              "curve-style": "taxi",
              "edge-distances": "node-position",
              "taxi-turn-min-distance": "20px",
              "target-arrow-shape": "triangle-backcurve",
              "target-endpoint": "outside-to-node",
              "source-endpoint": "outside-to-node"
            }
          },
          {
            selector: ".eh-handle",
            style: {
              "background-color": "red",
              width: 12,
              height: 12,
              shape: "ellipse",
              "overlay-opacity": 0,
              "border-width": 12,
              "border-opacity": 0
            }
          },
          {
            selector: ".eh-hover",
            style: {
              "background-color": "red"
            }
          },
          {
            selector: ".eh-source",
            style: {
              "border-width": 2,
              "border-color": "red"
            }
          },
          {
            selector: ".eh-target",
            style: {
              "border-width": 2,
              "border-color": "red"
            }
          },
          {
            selector: ".eh-preview, .eh-ghost-edge",
            style: {
              "background-color": "red",
              "line-color": "red",
              "target-arrow-color": "red",
              "source-arrow-color": "red"
            }
          }
        ]
      };

      window.cy = cytoscape(cytoData);

      window.cy.nodeHtmlLabel([
        {
          query: "node",
          tpl: data => {
            return `
                <div style="max-width: 80px; max-height: 50px; font-size: 0.5rem; overflow-wrap: break-word;overflow-y: hidden;">
                    ${data.label ? data.label : ""}
                </div>
                `;
          }
        }
      ]);
      const options = {
        preview: true,
        hoverDelay: 150,
        handleNodes: "node", // the number of times per second (Hz) that snap checks done (lower is less expensive)
        noEdgeEventsInDraw: false, // set events:no to edges during draws, prevents mouseouts on compounds
        disableBrowserGestures: true,
        handlePosition: function(node) {
          return "middle top"; // sets the position of the handle in the format of "X-AXIS Y-AXIS" such as "left top", "middle top"
        },
        edgeType: function(sourceNode, targetNode) {
          return sourceNode;
        }
      };

      cy.edgehandles(options);
      cy.on("ehcomplete", (event, sourceNode, targetNode) => {
        this.prepareTetherData(
          sourceNode._private.data,
          targetNode._private.data
        );
      });
      cy.$("node").on("click", elem => {
        this.showProcessData(
          elem.target._private.data.label,
          elem.target._private.data.id
        );
      });
      cy.$("edge").on("click", elem => {
        this.prepareDataBeforeTetherDelete(elem);
      });
    }
  },
  created() {
    Event.listen("processTaskCreated", data => {
      this.processData.tasks.push(data);
      this.processData.len += 1;
      Event.fire("notify", [
        `Задача ${data.title} создана для процесса ${this.processData.name}`
      ]);
    });

    Event.listen("processTaskDeleted", data => {
      this.processData.tasks.forEach((task, index) => {
        if (task.id == data) {
          this.processData.tasks.splice(index, 1);
        }
      });
      this.processData.len -= 1;
    });
    Event.listen("processTaskUpdated", data => {
      this.processData.tasks = data;
    });

    Event.listen("formAdded", data => {
      this.processData.tasks.forEach(elem => {
        if (elem.id == data.taskId) {
          elem.forms.push(data.data);
        }
      });
    });
  }
};
</script>
<style>
#cyto {
  width: 100%;
  height: 80vh;
  display: block;
}
.v-list-group__header {
  padding: 0 !important;
}
</style>