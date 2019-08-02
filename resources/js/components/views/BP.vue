<template>
    <div id="cyto"></div>
</template>

<script>
import cytoscape from "cytoscape";
import nodeHtmlLabel from "cytoscape-node-html-label";
import klay from "cytoscape-klay";

nodeHtmlLabel(cytoscape);
cytoscape.use(klay);

export default {
    props: {
        processes: Array
    },
    data() {
        return {
            cytoScape: null
        };
    },
    mounted() {
        let nodes = this.extractEdgesNodesFromProcess();

        const cytoData = {
            container: document.getElementById("cyto"),
            elements: nodes,
            style: [
                {
                    selector: "node",
                    style: {
                        "text-valign": "center",
                        "text-halign": "center",
                        shape: "round-rectangle",
                        height: 60,
                        width: 100
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
                        "source-endpoint": "outside-to-node",
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
                            ${data.label}
                        </div>
                `;
                }
            },
        ]);

        const options = {
            name: "klay",
            klay: {
                edgeRouting: "SPLINES",
                edgeSpacingFactor: 0.8,
                layoutHierarchy: true,
                linearSegmentsDeflectionDampening: 0.3,
                mergeEdges: false,
                mergeHierarchyCrossingEdges: true,
                nodeLayering: "NETWORK_SIMPLEX",
                nodePlacement: "SIMPLE",
                randomizationSeed: 1,
                routeSelfLoopInside: false,
                separateConnectedComponents: true,
                spacing: 70
            }
        };

        window.cy.layout(options).run();
    },
    methods: {
        /**
         * Helpers
         */

        extractEdgesNodesFromProcess() {
            let nodes = [];

            for (const process of this.processes) {
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
                        id: "t" + bt.id,
                        source: bt.from_process_id,
                        target: bt.to_process_id,
                        label: bt.action_text
                    }
                }));

                let frontEdges = process.front_tethers.map(ft => ({
                    group: "edges",
                    data: {
                        id: "t" + ft.id,
                        source: ft.from_process_id,
                        target: ft.to_process_id,
                        label: ft.action_text
                    }
                }));

                nodes = [...nodes, node, ...backEdges, ...frontEdges];
            }

            return nodes;
        }
    }
};
</script>

<style>
#cyto {
    width: 100%;
    height: 100%;
    display: block;
}
</style>