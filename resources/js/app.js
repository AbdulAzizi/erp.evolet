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
                primary: "#6897f5",
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

window.Event = new(class {
    constructor() {
        this.vue = new Vue();
    }

    fire(event, data = null) {
        this.vue.$emit(event, data);
    }

    listen(event, callback) {
        this.vue.$on(event, callback);
    }
})();

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
            let days, hours, minutes, total_hours, total_minutes;

            total_minutes = parseInt(Math.floor(milliseconds / 60000));
            total_hours = parseInt(Math.floor(total_minutes / 60));
            days = parseInt(Math.floor(total_hours / 24));

            minutes = parseInt(total_minutes % 60);
            hours = parseInt(total_hours % 24);

            return `${days}д ${hours}ч ${minutes}м`;
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
        loadResponsibilities() {
            let items = []; // Arrat to push response data

            axios.get("/api/responsibilities").then(res => {
                res.data.forEach(item => {
                    items.push({name: item.name, id: item.id}); // collect data and store in array
                });
            }).catch(err => err.messages);

            // return collected items for field
            return items;
        }
    },
    computed: {
        auth() {
            return window.Laravel.auth;
        }
    }
});

/****************************COMPONENTS********************************/
Vue.component("alert", require("./components/Alert.vue").default);
Vue.component("alerts", require("./components/Alerts.vue").default);
Vue.component("navbar", require("./components/Navbar.vue").default);
Vue.component("myform", require("./components/Form.vue").default);
Vue.component("right-drawer", require("./components/RightDrawer.vue").default);
Vue.component("left-drawer", require("./components/LeftDrawer.vue").default);
Vue.component("priority", require("./components/Priority.vue").default);
Vue.component("card", require("./components/Card.vue").default);
Vue.component("user-card", require("./components/UserCard.vue").default);
Vue.component("division-structure", require("./components/DivisionStructure.vue").default);
Vue.component("avatars-set", require("./components/AvatarsSet.vue").default);
Vue.component("dropdown-btn", require("./components/buttons/Dropdown.vue").default);
Vue.component("stats-card", require("./components/StatsCard.vue").default);
Vue.component("avatar", require("./components/Avatar.vue").default);
Vue.component("projects-card", require("./components/projects/Card.vue").default);
Vue.component("projects-list", require("./components/projects/List.vue").default);
Vue.component("resume-add-item", require("./components/profile/AddItem.vue").default);
Vue.component("resume-card", require("./components/profile/ResumeCard.vue").default);
Vue.component("resume-create", require("./components/profile/ResumeCreate.vue").default);
Vue.component("projects-card", require("./components/projects/Card.vue").default);
Vue.component("projects-list", require("./components/projects/List.vue").default);
Vue.component("projects-create", require("./components/projects/Create.vue").default);
Vue.component("history", require("./components/History.vue").default);
Vue.component("messages", require("./components/Messages.vue").default);
Vue.component("kanban-view", require("./components/Kanban.vue").default);
Vue.component("process-task-add", require("./components/processTask/Add.vue").default);
Vue.component("process-task-actions", require("./components/processTask/Actions.vue").default);
Vue.component("process-task-delete", require("./components/processTask/Delete.vue").default);
Vue.component("process-task-edit", require("./components/processTask/Edit.vue").default);
Vue.component("process-task-add-form", require("./components/processTask/AddForm.vue").default);




/****************************VIEWS********************************/
Vue.component("tasks-view", require("./components/views/Tasks.vue").default);
Vue.component("profile-banner", require("./components/profile/Banner.vue").default);
Vue.component("products-view", require("./components/views/Products.vue").default);
Vue.component("product", require("./components/Product.vue").default);
Vue.component("products-create-view", require("./components/views/products/Create.vue").default);
Vue.component("projects-view", require("./components/views/Projects.vue").default);
Vue.component("bp", require("./components/views/BP.vue").default);
Vue.component("profile-tasks", require("./components/views/ProfileTasks.vue").default);
Vue.component("profile-resume", require("./components/views/ProfileResumeShow.vue").default);

Vue.component("profile-responsibility", require("./components/views/ProfileResponsibility.vue").default);

Vue.component("profile-responsibility-show", require("./components/profile/Responsibility.vue").default);

Vue.component("add-job-description", require("./components/form/AddJobDescription.vue").default);

Vue.component("add-responsibilities", require("./components/form/addResponsibilities.vue").default);

Vue.component("user-card-vertical", require("./components/profile/UserCard.vue").default);

Vue.component("user-card-horizontal", require("./components/profile/UserCardHorizontal.vue").default);

Vue.component("human-resources-view", require("./components/views/HumanResources.vue").default);

Vue.component("users-view", require("./components/views/Users.vue").default);

Vue.component("resumes-view", require("./components/views/Resume.vue").default);

Vue.component("resume-show", require("./components/views/ResumeShow.vue").default);

Vue.component("human-resources-resumes", require("./components/views/HumanResourcesResumes.vue").default);

Vue.component("kanban-view", require("./components/Kanban.vue").default);
Vue.component("resumes-head-view", require("./components/views/HeadResume.vue").default);

Vue.component("resume-index-card", require("./components/ResumeIndexCard.vue").default);
Vue.component("division-responsibilities", require("./components/views/DivisionResponsibilities.vue").default);
Vue.component("division", require("./components/views/Division.vue").default);
Vue.component("chats-view", require("./components/views/Chats.vue").default);

Vue.component("products-admin-view", require("./components/ProductsAdmin").default);

Vue.component("edit-product-forms", require("./components/views/products/Edit.vue").default);
Vue.component("file-card", require("./components/files/Card.vue").default);
Vue.component("file-cards", require("./components/files/Cards.vue").default);

Vue.component("file-add", require("./components/files/Add.vue").default);
Vue.component("add-field", require("./components/files/AddField.vue").default);
Vue.component("bp-forms", require('./components/BP/BPForms.vue').default);
Vue.component("bp-form", require('./components/BP/BPForm.vue').default);

/****************************TASKS********************************/
Vue.component("tasks-add", require("./components/tasks/Add.vue").default);
Vue.component("tasks-table", require("./components/tasks/Table.vue").default);
Vue.component("tasks-calendar", require("./components/tasks/Calendar.vue").default);
Vue.component("tasks-watchers", require("./components/tasks/Watchers.vue").default);
Vue.component("tasks", require("./components/Tasks.vue").default);
Vue.component("task", require("./components/Task.vue").default);
Vue.component("form-field", require("./components/form/FormField.vue").default);
Vue.component("dynamic-form", require("./components/form/Form.vue").default);
Vue.component("poll-create", require("./components/tasks/PollCreate.vue").default);
Vue.component("poll-display", require("./components/tasks/PollDisplay.vue").default);
Vue.component("poll-form", require("./components/tasks/PollForm.vue").default);
Vue.component("task-control-buttons", require("./components/tasks/ControlButtons.vue").default);

/****************************HELPERS********************************/
Vue.component("helpers-offset", require("./components/helpers/Offset.vue").default);
/***************************DYNAMIC FIELDS*******************************/
Vue.component("picker", require("./components/form/Picker.vue").default);
Vue.component("user-selector", require("./components/form/UserSelector.vue").default);
Vue.component("many-to-many-select", require("./components/form/ManyToManySelect.vue").default);
Vue.component("autocomplete", require("./components/form/Autocomplete.vue").default);
Vue.component("combobox", require("./components/form/Combobox.vue").default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({el: "#app", vuetify: new Vuetify(vuetifyOptions)});
