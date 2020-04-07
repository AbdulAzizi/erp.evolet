<template>
    <div>
        <v-card flat>
            <v-toolbar dense color="primary" dark flat>
                <v-toolbar-title v-if="!edit">{{position.label}}</v-toolbar-title>
                <v-toolbar-title v-else>
                    <v-text-field
                        dense
                        v-model="positionLabel"
                        label="Название"
                        required
                        solo
                        flat
                        hide-details
                        background-color="primary darken-1"
                        append-icon="mdi-check"
                        append-outer-icon="mdi-close"
                        @click:append="editPosition()"
                        @click:append-outer="resetPositionEditForm()"
                    ></v-text-field>
                </v-toolbar-title>
                <v-spacer />
                <edit-add-actions :position="localPosition" v-if="editable" />
                <!-- TODO Must be seperate component -->
                <v-dialog v-if="detachable" v-model="detachDialog" eager width="600">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on"  dark icon @click="detachDialog = true">
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            Вы уверены, что хотите снять эту должность
                            <br />
                            с {{user.fullname}} ?
                        </v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn @click="detachDialog = false" text>Отмена</v-btn>
                            <v-btn @click="detachPosition()" dark color="red" text>Cнять</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
            <v-card-text
                class="pa-0"
                v-if="localPosition.responsibilities && localPosition.responsibilities.length > 0"
            >
                <v-list class="ml-2 mr-4" flat expand>
                    <v-list-group
                        v-for="(responsibility, index) in localPosition.responsibilities"
                        :key="index"
                        active-class
                        :ripple="false"
                    >
                        <template v-slot:activator>
                            <v-hover v-slot:default="{ hover }">
                                <v-row class="pa-0 ma-0">
                                    <v-col cols="9">
                                        <div
                                            class="float-left mr-2 grey--text text--darken-3"
                                        >{{ index + 1 }}.</div>
                                        <div
                                            class="ml-5 grey--text text--darken-3"
                                        >{{ responsibility.text }}</div>
                                    </v-col>
                                    <v-col cols="3" class="py-2 px-0 text-right">
                                        <v-btn
                                            icon
                                            small
                                            @click.stop="deleteResponsibility(responsibility.id)"
                                            v-if="hover && editable"
                                        >
                                            <v-icon small>mdi-delete</v-icon>
                                        </v-btn>
                                        <v-btn
                                            icon
                                            small
                                            @click.stop="editResponsibility(responsibility)"
                                            v-if="hover && editable && !user"
                                        >
                                            <v-icon small>mdi-pencil</v-icon>
                                        </v-btn>
                                        <v-btn
                                            icon
                                            small
                                            v-if="hover && editable && !user"
                                            @click.stop="moveResponsibilityUp(responsibility)"
                                        >
                                            <v-icon small>mdi-arrow-up-bold</v-icon>
                                        </v-btn>
                                        <v-btn
                                            icon
                                            small
                                            v-if="hover && editable && !user"
                                            @click.stop="moveResponsibilityDown(responsibility)"
                                        >
                                            <v-icon small>mdi-arrow-down-bold</v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-hover>
                        </template>
                        <v-row
                            class="ml-7 mr-1"
                            v-for="(description, subIndex) in responsibility.descriptions"
                            :key="subIndex"
                        >
                            <v-hover v-slot:default="{ hover }">
                                <v-row style="border-bottom: 1px solid #e0e0e0;" class="pa-0 ma-0">
                                    <v-col cols="9">
                                        <div class="float-left">{{index + 1}}.{{subIndex + 1}}.</div>
                                        <div
                                            class="grey--text text--darken-4 ml-7"
                                        >{{ description.text }}</div>
                                        <div
                                            class="grey--text text--darken-2 pl-7"
                                        >{{ description.description }}</div>
                                    </v-col>
                                    <v-col cols="3" v-if="hover" class="py-2 px-0 text-right">
                                        <v-btn icon small v-if="editable">
                                            <v-icon
                                                small
                                                @click="deleteResponsibilityDescription(description.id)"
                                            >mdi-delete</v-icon>
                                        </v-btn>
                                        <v-btn icon small v-if="editable">
                                            <v-icon
                                                small
                                                @click="editResponsibilityDescription(description)"
                                            >mdi-pencil</v-icon>
                                        </v-btn>
                                        <v-btn
                                            icon
                                            small
                                            v-if="hover && editable && !user"
                                            @click.stop="moveDescriptionUp(localPosition, description)"
                                        >
                                            <v-icon small>mdi-arrow-up-bold</v-icon>
                                        </v-btn>
                                        <v-btn
                                            icon
                                            small
                                            v-if="hover && editable && !user"
                                            @click.stop="moveDescriptionDown(localPosition, responsibility, description)"
                                        >
                                            <v-icon small>mdi-arrow-down-bold</v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-hover>
                        </v-row>
                        <v-btn
                            text
                            small
                            color="primary"
                            class="mx-4 mt-2"
                            @click="addResponsibilityDescription(responsibility.id)"
                            v-if="editable"
                        >
                            <v-icon class="px-2" small>mdi-plus-circle</v-icon>Добавить должностную задачу
                        </v-btn>
                    </v-list-group>
                </v-list>
            </v-card-text>
            <div
                class="pa-4"
                v-if="localPosition.responsibilities &&  localPosition.responsibilities.length == 0"
            >Объязанностей нет</div>
            <v-divider></v-divider>
            <v-btn
                class="py-2"
                text
                block
                color="primary"
                @click="addResponsibilityDialog = true"
                v-if="editable"
            >
                <v-icon small class="px-2">mdi-plus-circle</v-icon>Добавить объязанность
            </v-btn>
        </v-card>
        <v-dialog persistent v-model="addResponsibilityDialog" width="600">
            <add-responsibility :position="localPosition" />
        </v-dialog>
        <v-dialog persistent eager v-model="editResponsibilityDialog" width="600">
            <edit-responsibility />
        </v-dialog>
        <v-dialog persistent eager v-model="deleteResponsibilityDialog" width="400">
            <delete-responsibility />
        </v-dialog>
        <v-dialog persistent eager v-model="addResponsibilityDescriptionDialog" width="600">
            <add-responsibility-description />
        </v-dialog>
        <v-dialog persistent eager v-model="editResponsibilityDescriptionDialog" width="600">
            <edit-responsibility-description />
        </v-dialog>
        <v-dialog persistent eager v-model="deleteResponsibilityDescriptionDialog" width="400">
            <delete-responsibility-description />
        </v-dialog>
    </div>
</template>
<script>
export default {
    props: {
        position: {
            required: true
        },
        editable: {
            required: false,
            default: false
        },
        user: {
            required: false
        },
        detachable: {
            required: false,
            default: false
        }
    },
    data() {
        return {
            edit: false,
            positionLabel: this.position.label,
            localPosition: this.position,
            descriptionDialog: false,
            addResponsibilityDialog: false,
            editResponsibilityDialog: false,
            deleteResponsibilityDialog: false,
            responsibilityForEdit: null,
            addResponsibilityDescriptionDialog: false,
            editResponsibilityDescriptionDialog: false,
            deleteResponsibilityDescriptionDialog: false,
            detachDialog: false
        };
    },
    methods: {
        detachPosition() {
            axios
                .delete(
                    this.appPath(
                        `api/users/${this.user.id}/positions/${this.position.id}`
                    )
                )
                .then(resp => {
                    this.detachDialog = false;
                    Event.fire("positionDetached", this.position.id);
                });
        },
        resetPositionEditForm() {
            this.edit = false;
            this.positionLabel = this.position.label;
        },
        editPosition() {
            if (this.positionLabel !== null) {
                axios
                    .post(`/api/edit/position/${this.position.id}`, {
                        label: this.positionLabel
                    })
                    .then(res => {
                        this.localPosition.label = res.data.label;
                        this.resetPositionEditForm();
                    })
                    .catch(err => err.messages);
            }
        },
        editResponsibility(responsibility) {
            this.editResponsibilityDialog = true;
            Event.fire("responsibility", responsibility);
        },
        deleteResponsibility(responsibilityId) {
            this.deleteResponsibilityDialog = true;
            Event.fire("deleteResponsibility", responsibilityId);
        },
        addResponsibilityDescription(responsibilityId) {
            this.addResponsibilityDescriptionDialog = true;
            Event.fire("addResponsibilityDescription", responsibilityId);
        },
        editResponsibilityDescription(description) {
            this.editResponsibilityDescriptionDialog = true;
            Event.fire("editResponsibilityDescription", description);
        },
        deleteResponsibilityDescription(descriptionId) {
            this.deleteResponsibilityDescriptionDialog = true;
            Event.fire("deleteResponsibilityDescription", descriptionId);
        },
        moveResponsibilityUp(responsibility) {
            if (responsibility.order > 1) {
                axios
                    .get(
                        this.appPath(
                            `api/responsibilities/${responsibility.id}/moveup`
                        )
                    )
                    .then(resp => {
                        if (resp.data == "success") {
                            this.localPosition.responsibilities.forEach(
                                (resp, index) => {
                                    if (resp.id == responsibility.id) {
                                        this.swap(
                                            this.position.responsibilities,
                                            index - 1,
                                            index
                                        );
                                    }
                                }
                            );
                        }
                    });
            }
        },
        moveResponsibilityDown(responsibility) {
            if (
                responsibility.order <
                this.localPosition.responsibilities.length
            ) {
                axios
                    .get(
                        this.appPath(
                            `api/responsibilities/${responsibility.id}/movedown`
                        )
                    )
                    .then(resp => {
                        if (resp.data == "success") {
                            this.localPosition.responsibilities.forEach(
                                (resp, index) => {
                                    if (resp.id == responsibility.id) {
                                        this.swap(
                                            this.position.responsibilities,
                                            index,
                                            index + 1
                                        );
                                    }
                                }
                            );
                        }
                    });
            }
        },
        moveDescriptionUp(position, description) {
            if (description.order > 1) {
                axios
                    .post(
                        this.appPath(`api/responsibility_descriptions/moveup`),
                        {
                            position_id: position.id,
                            description_id: description.id
                        }
                    )
                    .then(resp => {
                        if (resp.data == "success") {
                            this.localPosition.responsibilities.forEach(
                                (resp, respIndex) => {
                                    resp.descriptions.forEach(
                                        (desc, descIndex) => {
                                            if (desc.id == description.id) {
                                                this.swap(
                                                    this.position
                                                        .responsibilities[
                                                        respIndex
                                                    ].descriptions,
                                                    descIndex - 1,
                                                    descIndex
                                                );
                                            }
                                        }
                                    );
                                }
                            );
                        }
                    });
            }
        },
        moveDescriptionDown(position, responsibility, description) {
            if (description.order < responsibility.descriptions.length) {
                axios
                    .post(
                        this.appPath(
                            `api/responsibility_descriptions/movedown`
                        ),
                        {
                            position_id: position.id,
                            description_id: description.id
                        }
                    )
                    .then(resp => {
                        if (resp.data == "success") {
                            this.localPosition.responsibilities.forEach(
                                (resp, respIndex) => {
                                    resp.descriptions.forEach(
                                        (desc, descIndex) => {
                                            if (desc.id == description.id) {
                                                this.swap(
                                                    this.position
                                                        .responsibilities[
                                                        respIndex
                                                    ].descriptions,
                                                    descIndex,
                                                    descIndex + 1
                                                );
                                            }
                                        }
                                    );
                                }
                            );
                        }
                    });
            }
        },
        swap(arr, index1, index2) {
            let temp = {};
            Object.assign(temp, arr[index1]);
            Object.assign(arr[index1], arr[index2]);

            arr[index1].order = temp.order;
            temp.order = arr[index2].order;

            Object.assign(arr[index2], temp);
        }
    },
    created() {
        Event.listen("editPosition", positionId => {
            if (this.position.id == positionId) {
                this.edit = true;
            }
        });
        Event.listen("responsibilityAdded", data => {
            this.addResponsibilityDialog = false;
            if (this.localPosition.id == data.position_id) {
                this.localPosition.responsibilities.push(data);
            }
        });
        Event.listen("closeResponsibilityDialog", data => {
            this.addResponsibilityDialog = false;
        });
        Event.listen("editResponsibility", editedResponsibility => {
            this.localPosition.responsibilities.forEach(responsibility => {
                if (responsibility.id == editedResponsibility.id) {
                    responsibility.text = editedResponsibility.text;
                }
            });
            this.editResponsibilityDialog = false;
        });
        Event.listen("cancelResponsibilityEdit", data => {
            this.editResponsibilityDialog = false;
        });

        Event.listen(
            "dontDeleteResponsibility",
            responsibility => (this.deleteResponsibilityDialog = false)
        );

        Event.listen("responsibilityDeleted", responsibilityId => {
            this.deleteResponsibilityDialog = false;

            this.localPosition.responsibilities.forEach(
                (responsibility, index) => {
                    if (responsibility.id == responsibilityId) {
                        // get the order number of item that is deleted
                        let order = responsibility.order;
                        // loop through all of them
                        this.localPosition.responsibilities.forEach(
                            (resp, index) => {
                                // if order is lower than the deleted one
                                if (resp.order > order) {
                                    // decrease order number by one
                                    resp.order = resp.order - 1;
                                }
                            }
                        );
                        // delete item
                        this.localPosition.responsibilities.splice(index, 1);
                    }
                }
            );
        });

        Event.listen("responsibilityDescriptionAdded", data => {
            this.addResponsibilityDescriptionDialog = false;
            this.localPosition.responsibilities.forEach(responsibility => {
                if (responsibility.id == data.responsibilityId) {
                    responsibility.descriptions.push(data.description);
                }
            });
        });

        Event.listen(
            "cancelDescriptionAdding",
            description => (this.addResponsibilityDescriptionDialog = false)
        );

        Event.listen("responsibilityDescriptionEdited", data => {
            this.editResponsibilityDescriptionDialog = false;
            this.localPosition.responsibilities.forEach(responsibility => {
                responsibility.descriptions.forEach(description => {
                    if (description.id == data.id) {
                        description.title = data.title;
                        description.description = data.description;
                    }
                });
            });
        });

        Event.listen(
            "cancelResponsibilityDescriptionEditing",
            description => (this.editResponsibilityDescriptionDialog = false)
        );

        Event.listen("responsibilityDescriptionDeleted", descriptionId => {
            this.localPosition.responsibilities.forEach(responsibility => {
                responsibility.descriptions.forEach((description, index) => {
                    if (description.id == descriptionId) {
                        // get the order number of item that is deleted
                        let order = description.order;
                        // loop through all of them
                        responsibility.descriptions.forEach((desc, index) => {
                            // if order is lower than the deleted one
                            if (desc.order > order) {
                                // decrease order number by one
                                desc.order = desc.order - 1;
                            }
                        });
                        // delete item
                        responsibility.descriptions.splice(index, 1);
                    }
                });
            });
            this.deleteResponsibilityDescriptionDialog = false;
        });
        Event.listen(
            "cancelResponsibilityDescriptionDeleteing",
            description => (this.deleteResponsibilityDescriptionDialog = false)
        );
    },
    watch: {
        position(val) {
            this.positionLabel = val.label;
            this.localPosition = val;
        }
    }
};
</script>
<style>
.v-list-group__items {
    color: black !important;
}

.v-list-item--active {
    color: black !important;
}
</style>