/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

// add vuetify
import Vuetify from "vuetify";
Vue.use(Vuetify, {
    theme: {
        backgroundColor: "#e9ebee",
        primary: "#b8cf41",
        secondary: "#21353f"
        // accent: "#689F38",
        // success: "#4caf50",
        // error: "#EF5350",
        // warning: "#ffeb3b",
        // info: "#2196f3",
    }
});
import "vuetify/dist/vuetify.min.css";
import "material-design-icons-iconfont/dist/material-design-icons.css";

window.Event = new (class {
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

Vue.mixin({
    methods: {
        pluck: function (array, key) {
            return array.map(item => item[key]);
        },
        photo: function (name) {
            return window.Laravel.asset_path + 'img/' + name;
        },
    },
    computed: {
        appPath(){
            return window.Laravel.asset_path;
        }
    }
  })

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);
Vue.component("navbar", require("./components/Navbar.vue").default);
Vue.component("myform", require("./components/Form.vue").default);
Vue.component("left-drawer", require("./components/LeftDrawer.vue").default);
Vue.component("priority", require("./components/Priority.vue").default);


Vue.component("tasks-view", require("./components/views/Tasks.vue").default);

Vue.component("tasks", require("./components/Tasks.vue").default);
Vue.component("task", require("./components/Task.vue").default);
Vue.component("tasks-add", require("./components/tasks/Add.vue").default);
Vue.component("tasks-table", require("./components/tasks/Table.vue").default);
Vue.component("tasks-watchers", require("./components/tasks/Watchers.vue").default);

Vue.component("user-card", require("./components/UserCard.vue").default);
Vue.component("division", require("./components/Division.vue").default);
Vue.component("add-user-dialog", require("./components/AddUserDialog.vue").default);
Vue.component("avatars-set", require("./components/AvatarsSet.vue").default);

Vue.component("form-field", require("./components/form/FormField.vue").default);
Vue.component("user-selector", require("./components/form/UserSelector.vue").default);
Vue.component("picker", require("./components/form/Picker.vue").default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app"
});
