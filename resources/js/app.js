/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

// add vuetify
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.min.css";
// import "material-design-icons-iconfont/dist/material-design-icons.css";
import "@mdi/font/css/materialdesignicons.css";
import moment from "moment-timezone";

const vuetifyOptions = {
    theme: {
        themes: {
            light: {
                primary: "#2196f3"
                // secondary: "#21353f"
                // accent: "#689F38",
                // success: "#4caf50",
                // error: "#EF5350",
                // warning: "#ffeb3b",
                // info: "#2196f3",
            }
        }
    },
    icons: {
        iconfont: "mdi" // default - only for display purposes
    }
};

Vue.use(Vuetify);

window.Event = new class {
    constructor() {
        this.vue = new Vue();
    }

    fire(event, data = null) {
        this.vue.$emit(event, data);
    }

    listen(event, callback) {
        this.vue.$on(event, callback);
    }
}();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import colors from "vuetify/es5/util/colors";

Vue.mixin({
    data() {
        const appMoment = moment;
        appMoment.tz.setDefault("UTC");
        appMoment.locale("ru");

        return {moment: appMoment};
    },
    methods: {
        pluck: function (array, key) {
            return array.map(item => item[key]);
        },
        photo: function (name) {
            if (name) 
                return window.Laravel.asset_path + "img/" + name;
             else 
                return window.Laravel.asset_path + "img/green-solo-logo.png";
            
        },
        filePath(name){
            return window.Laravel.asset_path + "storage/" + name;
        },
        thumb(name){
            if(name){
                return window.Laravel.asset_path + "img/thumbs/" + name;
            } else {
                return(window.Laravel.asset_path + "img/thumbs/green-solo-logo.png");
            }
        },
        isCssColor(color) {
            return !!color && !!color.match(/^(#|(rgb|hsl)a?\()/);
        },
        getCSSColor(color) {
            if (this.isCssColor(color)) {
                return color;
            }

            const splittedColors = this.color.trim().split(" ", 2);
            let preparedColor = splittedColors[0];
            let modifier = splittedColors[1];

            if (this.$vuetify.theme.currentTheme[preparedColor]) 
                return this.$vuetify.theme.currentTheme[preparedColor];
            

            if (modifier) {
                modifier = modifier.replace("-", "");
                return colors[preparedColor][modifier];
            }

            return colors[preparedColor]["base"];
        },
        colorfulShadow(color) {
            return {"-webkit-box-shadow": `0 12px 20px -10px ${
                    this.getCSSColor(color)
                } !important`, boxShadow: `0 12px 20px -10px ${
                    this.getCSSColor(color)
                } !important`};
        },
        durObj(milliseconds) {
            let days,
                hours,
                minutes,
                total_hours,
                total_minutes;

            total_minutes = parseInt(Math.floor(milliseconds / 60000));
            total_hours = parseInt(Math.floor(total_minutes / 60));
            days = parseInt(Math.floor(total_hours / 24));

            minutes = parseInt(total_minutes % 60);
            hours = parseInt(total_hours % 24);

            return `${days}д ${hours}ч ${minutes}м`;
        },
        days(milliseconds) {
            let days,
                total_minutes,
                total_hours;
            total_minutes = parseInt(Math.floor(milliseconds / 60000));
            total_hours = parseInt(Math.floor(total_minutes / 60));
            days = parseInt(Math.floor(total_hours / 24));

            return days !== 0 ? `${days}д` : null;
        },

        hours(milliseconds) {
            let hours,
                total_minutes,
                total_hours;
            total_minutes = parseInt(Math.floor(milliseconds / 60000));
            total_hours = parseInt(Math.floor(total_minutes / 60));
            hours = parseInt(total_hours % 24);

            return hours !== 0 ? `${hours}ч` : null;
        },
        minutes(milliseconds) {
            let minutes,
                total_minutes,
                total_hours;
            total_minutes = parseInt(Math.floor(milliseconds / 60000));
            total_hours = parseInt(Math.floor(total_minutes / 60));
            minutes = parseInt(total_minutes % 60);

            return minutes !== 0 ? `${minutes}м` : null;
        },
        prepareFields(fields) {
            let fieldsClone = [...fields];

            return fieldsClone.map(field => {
                field["rules"] = field.pivot && field.pivot.required ? ["required"] : [true];

                field["type"] = this.getDynamicFieldsType(field.type.name);

                return field;
            });
        },
        getDynamicFieldsType(laravelType) { // TODO Make a normal adapter or refactor to use same types in vue and laravel
            switch (laravelType) {
                case "list":
                    return "autocomplete";
                default:
                    return laravelType;
            }
        },
        appPath(url) {
            return window.Laravel.asset_path + url;
        },
        loadPositions() {
            let items = []; // Array to push response data

            axios.get("/api/positions").then(res => {
                res.data.forEach(item => {
                    items.push({name: item.name, id: item.id}); // collect data and store in array
                });
            }).catch(err => err.message);

            // return collected items for field
            return items;
        },
        loadDivisionPositions(divisionId) {
            let items = [];

            axios.get(`/api/division/positions/${divisionId}`).then(res => {
                res.data.forEach(item => {
                    items.push({name: item.name, id: item.id});
                });
            }).catch(err => err.message);

            return items;
        },
        loadPositionLevels() {
            let items = [];

            axios.get("/api/positionLevels").then(res => {
                res.data.forEach(item => {
                    items.push({name: item.name, id: item.id});
                });
            }).catch(err => err.message);

            return items;
        },

        loadDivisions() {
            let items = [];

            axios.get("/api/divisions").then(res => {
                res.data.forEach(item => {
                    items.push({name: item.name, id: item.id});
                });
            }).catch(err => err.message);

            return items;
        },
        loadDivisionTags() {
            let items = [];
            let divisionId = this.auth.division_id;

            axios.get(`/api/divisions/${divisionId}/tags`).then(res => {
                res.data.forEach(item => {
                    items.push({name: item.name, id: item.id});
                });
            }).catch(err => err.message);

            return items;
        }
    },
    computed: {
        auth() {
            return window.Laravel.auth;
        },
        csrf() {
            return window.Laravel.csrf_token;
        },
        currentTask() {
            return window.Laravel.currentTask;
        },
        availableFileFormats() {
            return [
                'image/*',
                '.pdf',
                '.xlsx',
                '.xls',
                '.doc',
                '.docx',
                '.ppt',
                '.pptx',
                '.psd',
                '.ai',
                '.cdr'
              ]
        }
    },
    created() { // console.log(this.auth);
    }
});

/****************************COMPONENTS********************************/
Vue.component("alert", () => import("./components/Alert.vue"));
Vue.component("alerts", () => import("./components/Alerts.vue"));
Vue.component("navbar", () => import("./components/Navbar.vue"));
Vue.component("right-drawer", () => import("./components/RightDrawer.vue"));
Vue.component("left-drawer", () => import("./components/LeftDrawer.vue"));
Vue.component("priority", () => import("./components/Priority.vue"));
Vue.component("card", () => import("./components/Card.vue"));
Vue.component("user-card", () => import("./components/UserCard.vue"));
Vue.component("division-structure", () => import("./components/DivisionStructure.vue"));
Vue.component("avatars-set", () => import("./components/AvatarsSet.vue"));
Vue.component("dropdown-btn", () => import("./components/buttons/Dropdown.vue"));
Vue.component("stats-card", () => import("./components/StatsCard.vue"));
Vue.component("avatar", () => import("./components/Avatar.vue"));
Vue.component("projects-card", () => import("./components/projects/Card.vue"));
Vue.component("projects-list", () => import("./components/projects/List.vue"));
Vue.component("resume-add-item", () => import("./components/profile/AddItem.vue"));
Vue.component("resume-card", () => import("./components/profile/ResumeCard.vue"));
Vue.component("resume-create", () => import("./components/profile/ResumeCreate.vue"));
Vue.component("projects-card", () => import("./components/projects/Card.vue"));
Vue.component("projects-list", () => import("./components/projects/List.vue"));
Vue.component("projects-create", () => import("./components/projects/Create.vue"));
Vue.component("history", () => import("./components/History.vue"));
Vue.component("messages", () => import("./components/Messages.vue"));
Vue.component("kanban-view", () => import("./components/Kanban.vue"));
Vue.component("factories-vertical-card", () => import("./components/factories/VerticalCard.vue"));
Vue.component("factories-card", () => import("./components/factories/Card.vue"));
Vue.component("factories-product-form", () => import("./components/factories/ProductForm.vue"));
Vue.component("factories-product-table", () => import("./components/factories/ProductTable.vue"));
Vue.component("process-task-add", () => import("./components/processTask/Add.vue"));
Vue.component("process-task-actions", () => import("./components/processTask/Actions.vue"));
Vue.component("process-task-delete", () => import("./components/processTask/Delete.vue"));
Vue.component("process-task-edit", () => import("./components/processTask/Edit.vue"));
Vue.component("process-task-add-form", () => import("./components/processTask/AddForm.vue"));
Vue.component("edit-add-actions", () => import("./components/division/EditAddActions.vue"));
Vue.component("add-employee", () => import("./components/division/AddEmployee.vue"));
Vue.component("edit-record", () => import("./components/EditRecord.vue"));
Vue.component("attachments", () => import("./components/Attachments.vue"));

/****************************RESPONSIBILITIES********************************/
Vue.component("attach-responsibilities-btn", () => import ("./components/responsibility/AttachResponsibilityBtn.vue"));
Vue.component("add-responsibility", () => import ("./components/responsibility/AddResponsibility.vue"));
Vue.component("edit-responsibility", () => import ("./components/responsibility/EditResponsibility.vue"));
Vue.component("delete-responsibility", () => import ("./components/responsibility/DeleteResponsibility.vue"));
Vue.component("add-responsibility-description", () => import ("./components/responsibility/AddResponsibilityDescription.vue"));
Vue.component("edit-responsibility-description", () => import ("./components/responsibility/EditResponsibilityDescription.vue"));
Vue.component("delete-responsibility-description", () => import ("./components/responsibility/DeleteResponsibilityDescription.vue"));
Vue.component("add-division", () => import ("./components/division/AddDivision.vue"));
Vue.component("delete-record", () => import ("./components/DeleteRecord.vue"));
/****************************VIEWS********************************/
Vue.component("views-login", () => import ("./components/views/Login.vue"));
Vue.component("views-password-email", () => import ("./components/views/PasswordEmail.vue"));
Vue.component("views-password-reset", () => import ("./components/views/PasswordReset.vue"));
Vue.component("factories", () => import ("./components/views/factories/Index.vue"));
Vue.component("views-factories-create", () => import ("./components/views/factories/Create.vue"));
Vue.component("views-factories-show", () => import ("./components/views/factories/Show.vue"));

Vue.component("tasks-view", () => import ("./components/views/Tasks.vue"));
Vue.component("profile-banner", () => import ("./components/profile/Banner.vue"));
Vue.component("products-view", () => import ("./components/views/Products.vue"));
Vue.component("product", () => import ("./components/Product.vue"));
Vue.component("products-create-view", () => import ("./components/views/products/Create.vue"));
Vue.component("projects-view", () => import ("./components/views/Projects.vue"));
Vue.component("bp", () => import ("./components/views/BP.vue"));
Vue.component("users-show", () => import ("./components/views/users/Show.vue"));
Vue.component("profile-resume", () => import ("./components/views/ProfileResumeShow.vue"));
Vue.component("views-profile-tasks", () => import ("./components/views/profile/Tasks.vue"));
Vue.component("tasks-group-view", () => import ("./components/tasks/GroupTasks.vue"));
Vue.component("views-users-set-tasks", () => import ("./components/views/users/SetTasks.vue"));

Vue.component("positions", () => import ("./components/Positions.vue"));
Vue.component("position-card", () => import ("./components/positions/Card.vue"));
Vue.component("add-position", () => import ("./components/positions/AddPosition.vue"));

Vue.component("user-card-vertical", () => import ("./components/profile/UserCard.vue"));

Vue.component("user-card-horizontal", () => import ("./components/profile/UserCardHorizontal.vue"));

Vue.component("human-resources-view", () => import ("./components/views/HumanResources.vue"));

Vue.component("views-users-index", () => import ("./components/views/users/Index.vue"));
Vue.component("views-hr-users", () => import ("./components/views/hr/Users.vue"));

Vue.component("resumes-view", () => import ("./components/views/Resume.vue"));

Vue.component("resume-show", () => import ("./components/views/ResumeShow.vue"));

Vue.component("human-resources-resumes", () => import ("./components/views/HumanResourcesResumes.vue"));

Vue.component("kanban-view", () => import ("./components/Kanban.vue"));
Vue.component("resumes-head-view", () => import ("./components/views/HeadResume.vue"));

Vue.component("resume-index-card", () => import ("./components/ResumeIndexCard.vue"));
Vue.component("views-division", () => import ("./components/views/Division.vue"));
Vue.component("chats-view", () => import ("./components/views/Chats.vue"));

Vue.component("products-admin-view", () => import ("./components/ProductsAdmin"));

Vue.component("edit-product-forms", () => import ("./components/views/products/Edit.vue"));
Vue.component("file-card", () => import ("./components/files/Card.vue"));
Vue.component("file-cards", () => import ("./components/files/Cards.vue"));

Vue.component("file-add", () => import ("./components/files/Add.vue"));
Vue.component("add-field", () => import ("./components/files/AddField.vue"));
Vue.component("bp-forms", () => import ("./components/BP/BPForms.vue"));
Vue.component("bp-form", () => import ("./components/BP/BPForm.vue"));
Vue.component("division-tags", () => import ("./components/division/Tags.vue"));
Vue.component("views-timesets-index", () => import ("./components/views/timesets/Index.vue"));
Vue.component("views-users-tasks", () => import ("./components/views/users/Tasks.vue"));
Vue.component("views-entries-add", () => import ("./components/views/entries/Add.vue"));
Vue.component("views-entries-index", () => import ("./components/views/entries/Index.vue"));
Vue.component("views-entries-totals", () => import ("./components/views/entries/Totals.vue"));
Vue.component("views-facilities-index", () => import ("./components/views/users/Facilities.vue"));

/****************************TASKS********************************/
Vue.component("tasks-priority", () => import ("./components/tasks/Priority.vue"));
Vue.component("tasks-tags", () => import ("./components/tasks/Tags.vue"));
Vue.component("tasks-participants", () => import ("./components/tasks/Participants.vue"));
Vue.component("tasks-planned-time", () => import ("./components/tasks/PlannedTime.vue"));
Vue.component("tasks-deadline", () => import ("./components/tasks/Deadline.vue"));
Vue.component("task-description", () => import ("./components/tasks/Description.vue"));
Vue.component("task-title", () => import ("./components/tasks/Title.vue"));
Vue.component("tasks-add", () => import ("./components/tasks/Add.vue"));
Vue.component("tasks-table", () => import ("./components/tasks/Table.vue"));
Vue.component("tasks-calendar", () => import ("./components/tasks/Calendar.vue"));
Vue.component("tasks-watchers", () => import ("./components/tasks/Watchers.vue"));
Vue.component("tasks", () => import ("./components/Tasks.vue"));
Vue.component("task", () => import ("./components/Task.vue"));
Vue.component("form-field", () => import ("./components/form/FormField.vue"));
Vue.component("dynamic-form", () => import ("./components/form/Form.vue"));
Vue.component("poll-create", () => import ("./components/tasks/PollCreate.vue"));
Vue.component("poll-display", () => import ("./components/tasks/PollDisplay.vue"));
Vue.component("poll-form", () => import ("./components/tasks/PollForm.vue"));
Vue.component("task-control-buttons", () => import ("./components/tasks/ControlButtons.vue"));
Vue.component("task-repeat", () => import("./components/tasks/Repeat.vue"));

/****************************HELPERS********************************/
Vue.component("helpers-offset", () => import ("./components/helpers/Offset.vue"));
/***************************DYNAMIC FIELDS*******************************/
Vue.component("picker", () => import ("./components/form/Picker.vue"));
Vue.component("user-selector", () => import ("./components/form/UserSelector.vue"));
Vue.component("many-to-many-select", () => import ("./components/form/ManyToManySelect.vue"));
Vue.component("autocomplete", () => import ("./components/form/Autocomplete.vue"));
Vue.component("combobox", () => import ("./components/form/Combobox.vue"));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({el: "#app", vuetify: new Vuetify(vuetifyOptions)});
