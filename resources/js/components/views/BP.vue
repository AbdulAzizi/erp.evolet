<template>
  <div>
    <v-dialog v-model="showProcess" width="400">
      <v-card>
        <v-form :action="`/deleteProcess/${processData.id}`" method="GET">
          <v-toolbar color="primary" dense dark flat>
            <v-toolbar-title>{{processData.name}}</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <span>id: {{processData.id}}</span>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text color="primary" type="submit">delete process</v-btn>
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
      <v-menu v-model="showMenu" offset-y style="max-width: 600px">
        <v-list>
          <v-list-item>hello</v-list-item>
        </v-list>
      </v-menu>
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
      processData: {
        name: null,
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
    showProcessData(name, id) {
      this.processData.name = name;
      this.processData.id = id;
      this.showProcess = true;
    },
    deleteProcess(id) {
      axios
        .delete(`/api/deleteProcess/${id}`)
        .then(res => {
          this.showProcess = false;
          this.localProcesses.forEach((elem, index) => {
            if (elem.id == id) {
              this.localProcesses.splice(index, 1);
            }
          });
          this.nodes = this.extractEdgesNodesFromProcess();
          this.drawNodes();
        })
        .catch(err => err.messages);
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
    }
  }
  // watch: {
  //   nodes(elem){
  //   console.log(elem)
  //     elem = this.extractEdgesNodesFromProcess();
  //     this.drawNodes();
  //   }
  // }
};
</script>

<style>
#cyto {
  width: 100%;
  height: 80vh;
  display: block;
}
</style>