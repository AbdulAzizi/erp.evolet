<template>
  <div>
    <v-dialog v-model="showProcess" width="600">
      <v-card>
        <v-form :action="`/deleteProcess/${processData.id}`" method="GET">
          <v-hover v-slot:default="{ hover }">
            <v-toolbar color="primary" dark flat :height="updateProcess ? '100px' : '50px'">
              <v-toolbar-title v-if="!updateProcess">
                {{processData.name}} 
              </v-toolbar-title>
              <v-toolbar-title v-if="updateProcess" class="ma-2">
              <v-form>
                <v-text-field
                v-model="processData.name"
                label="Название"
                required
                solo
                flat
                hide-details
                background-color="primary darken-1"
                class="mb-1"
                :value="processData.name">
                </v-text-field>
              <v-btn
                small
                color="red lighten-1"
                dark
                text
                class="float-left"
                @click="updateProcess = false"
              >отмена</v-btn>
              <v-btn
                small
                dark
                outlined
                class="float-right"
                @click="updateProcessName(processData.id)"
              >изменить</v-btn>
              </v-form>
              </v-toolbar-title>
              <v-spacer></v-spacer>
                <v-btn fab small text v-if="hover" @click="updateProcess = true">
                  <v-icon small>
                    mdi-pencil
                  </v-icon>
                </v-btn>
                <v-btn fab small text v-if="hover" type="submit">
                  <v-icon small>
                    mdi-delete
                  </v-icon>
                </v-btn>
            </v-toolbar>
          </v-hover>
          <v-card-text v-if="processData.len == 0">
            <h4 class="grey--text">В этом процессе нету полей</h4>
          </v-card-text>
          <v-card-text v-if="processData.len > 0">
            <template v-for="(elem, index) in processData.tasks">
              <h4 :key="'title' + index" class="grey--text">Задача № {{index + 1}}. {{elem.title}}</h4>
            <v-list :key="'list' + index" dense>
              <v-list-group value="true" v-for="(form, index) in elem.forms" :key="index">
                <template v-slot:activator >
                  <v-list-item-title class="text-uppercase">
                   Поля которые заполняет {{elem.responsibility.name}}
                  </v-list-item-title>
                </template>
                  <v-simple-table dense>
                    <tbody>
                      <tr v-for="(field, index) in form.fields" :key="index">
                        <td>
                          {{field.label}}
                        </td>
                      </tr>
                    </tbody>
                  </v-simple-table>
              </v-list-group>
            </v-list>
            </template>
          </v-card-text>
        </v-form>
      </v-card>
    </v-dialog>
    <v-dialog v-model="deleteTether" width="400">
      <v-card>
        <v-form :action="`/tether/delete/${tetherData.id}`" method="get">
          <v-card-title class="headline">
            Вы хотите удалить связь?
          </v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red lighten-2" text @click="deleteTether = false">Отмена</v-btn>
            <v-btn color="primary" text type="submit">Удалить</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
    <v-dialog v-model="tetherNameDialog" width="400">
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
            ></form-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="createTether()">Ok</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialog" width="400">
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
            ></form-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="dialog = false">отмена</v-btn>
          <v-btn color="primary" class="float-right" @click="createProcess()">отправить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <div id="cyto">
    </div>
    <v-btn color="primary" dark fab right bottom fixed @click="dialog = true">
      <v-icon>mdi-plus</v-icon>
    </v-btn>
  </div>
</template>

<script>
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
      cytoScape: null,
      dialog: false,
      processName: null,
      tetherName: null,
      localProcesses: this.processes,
      nodes: null,
      tetherNameDialog: false,
      fromProcess: null,
      toProcess: null,
      showMenu: false,
      showProcess: false,
      updateProcess: false,
      deleteTether: false,
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
    /**
     * Helpers
     */

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
            id: "f" + bt.id,
            source: bt.from_process_id,
            target: bt.to_process_id,
            label: bt.action_text
          }
        }));

        let frontEdges = process.front_tethers.map(ft => ({
          group: "edges",
          data: {
            id: "b" + ft.id,
            source: ft.from_process_id,
            target: ft.to_process_id,
            label: ft.action_text
          }
        }));

        nodes = [...nodes, node, ...backEdges, ...frontEdges];
      }

      return nodes;
    },
    prepareTetherData(fromProcess, toProcess) {
      this.fromProcess = fromProcess;
      this.toProcess = toProcess;
      this.tetherNameDialog = true;
    },
    createTether() {
      axios
        .post("/api/tether", {
          from_process_id: this.fromProcess.id,
          to_process_id: this.toProcess.id,
          action_text: this.tetherName
        })
        .then(res => {
          this.tetherNameDialog = false;
          this.$refs.tetherNameForm.reset();
          this.localProcesses.forEach(elem => {
            if (this.fromProcess.id == elem.id) {
              elem.front_tethers.push(res.data);
            }
            if (this.toProcess.id == elem.id) {
              elem.back_tethers.push(res.data);
            }
          });
          this.nodes = this.extractEdgesNodesFromProcess();
          this.drawNodes();
        })
        .catch(err => err.messages);
    },
    createProcess() {
      let form = this.$refs.createProcessForm;
      if (form.validate()) {
        axios
          .post("/api/process", {
            name: this.processName
          })
          .then(res => {
            this.localProcesses.push(res.data);
            this.nodes = this.extractEdgesNodesFromProcess();
            this.drawNodes();
            this.dialog = false;
          })
          .catch(err => err.messages);
      }
    },
    updateProcessName(id){
      axios.put(`/api/process/update/${id}`, {
        name: this.processData.name
      }).then(res => {
        this.updateProcess = false;
        this.localProcesses.forEach(elem => {
          if(elem.id == id){
            elem.name = this.processData.name;
          }
        })
        this.nodes = this.extractEdgesNodesFromProcess();
        this.drawNodes();
      }).catch(err => err.messages)
    },
    showProcessData(name, id) {
      this.processData.name = name;
      this.processData.id = id;
      this.showProcess = true;

      this.localProcesses.forEach(elem => {
        if(elem.id == this.processData.id){
          this.processData.tasks = elem.process_tasks;
          this.processData.len = elem.process_tasks.length;
          };
        })
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
          // can return 'flat' for flat edges between nodes or 'node' for intermediate node between them
          // returning null/undefined means an edge can't be added between the two nodes
          return sourceNode;
        }
      };

      window.cy.edgehandles(options);
      window.cy.on("ehcomplete", (event, sourceNode, targetNode, addedEles) => {
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
        let id = elem.target._private.data.id.replace( /^\D+/g, '');
        this.tetherData.id = +id;
        this.deleteTether = true;
      })
    }
  }
};
</script>

<style>
#cyto {
  width: 100%;
  height: 80vh;
  display: block;
}
</style>