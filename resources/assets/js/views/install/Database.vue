<template>
    <div>
        <InstallSteps :active_state="active"></InstallSteps>

        <div class="card-body">
            <div class="w-full bg-red-100 text-red-600 p-3 rounded-sm font-semibold text-xs" :class="(form.response.error) ? 'block' : 'hidden'" v-if="form.response.error" v-html="form.response.message"></div>

            <form>
                <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5 mt-0">
                    <div class="sm:col-span-6 required" :class="[{'has-error': form.errors.get('connection')}]">
                        <label for="connection" class="text-black text-sm font-medium">Database Driver</label>
                        <span class="text-red ltr:ml-1 rtl:mr-1">*</span>

                        <div class="input-group input-group-merge">
                            <select
                                class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                                :class="[{'border-red': form.errors.get('connection')}, {'border-light-gray': !form.errors.get('connection')}]"
                                @change="form.errors.clear('connection')"
                                v-model="form.connection"
                                required="required"
                                name="connection"
                                id="connection"
                            >
                                <option value="mysql">MySQL / MariaDB</option>
                                <option value="pgsql">PostgreSQL</option>
                                <option value="sqlite">SQLite</option>
                            </select>
                        </div>

                        <div class="text-red text-sm mt-1 block" v-if="form.errors.has('connection')" v-html="form.errors.get('connection')"></div>
                    </div>

                    <div class="sm:col-span-6 required" :class="[{'has-error': form.errors.get('hostname')}]" v-if="!is_sqlite">
                        <label for="hostname" class="text-black text-sm font-medium">Hostname</label>
                        <span class="text-red ltr:ml-1 rtl:mr-1">*</span>

                        <div class="input-group input-group-merge">
                            <input
                                class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                                :class="[{'border-red': form.errors.get('hostname')}, {'border-light-gray': !form.errors.get('hostname')}]"
                                data-name="hostname"
                                data-value="localhost"
                                @keydown="form.errors.clear('hostname')"
                                v-model="form.hostname"
                                required="required"
                                name="hostname"
                                type="text"
                                value="localhost"
                                id="hostname"
                            />
                        </div>

                        <div class="text-red text-sm mt-1 block" v-if="form.errors.has('hostname')" v-html="form.errors.get('hostname')"></div>
                    </div>

                    <div class="sm:col-span-6" :class="[{'has-error': form.errors.get('port')}]" v-if="!is_sqlite">
                        <label for="port" class="text-black text-sm font-medium">Port</label>

                        <div class="input-group input-group-merge">
                            <input
                                class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                                :class="[{'border-red': form.errors.get('port')}, {'border-light-gray': !form.errors.get('port')}]"
                                @keydown="form.errors.clear('port')"
                                v-model="form.port"
                                name="port"
                                type="text"
                                id="port"
                            />
                        </div>

                        <div class="text-red text-sm mt-1 block" v-if="form.errors.has('port')" v-html="form.errors.get('port')"></div>
                    </div>

                    <div class="sm:col-span-6 required" v-if="!is_sqlite">
                        <label for="username" class="text-black text-sm font-medium">Username</label>
                        <span class="text-red ltr:ml-1 rtl:mr-1">*</span>

                        <div class="input-group input-group-merge">
                            <input
                            :class="[{'border-red': form.errors.get('username')}, {'border-light-gray': !form.errors.get('username')}]"
                            class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" data-name="username" @keydown="form.errors.clear('username')" v-model="form.username" required="required" name="username" type="text" id="username" />
                        </div>

                        <div class="text-red text-sm mt-1 block" v-if="form.errors.has('username')" v-html="form.errors.get('username')"></div>
                    </div>

                    <div class="sm:col-span-6" v-if="!is_sqlite">
                        <label for="password" class="text-black text-sm font-medium">Password</label>

                        <div class="input-group input-group-merge">
                            <input
                            :class="[{'border-red': form.errors.get('password')}, {'border-light-gray': !form.errors.get('password')}]"
                            class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" data-name="password" v-model="form.password" name="password" type="password" value="" id="password" />
                        </div>

                        <div class="text-red text-sm mt-1 block" v-if="form.errors.has('password')" v-html="form.errors.get('password')"></div>
                    </div>

                    <div class="sm:col-span-6 required">
                        <label for="database" class="text-black text-sm font-medium">{{ is_sqlite ? 'Database File Path' : 'Database' }}</label>
                        <span class="text-red ltr:ml-1 rtl:mr-1">*</span>

                        <div class="input-group input-group-merge">
                            <input
                            :class="[{'border-red': form.errors.get('database')}, {'border-light-gray': !form.errors.get('database')}]"
                            class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" data-name="database" :placeholder="is_sqlite ? 'database/database.sqlite' : ''" @keydown="form.errors.clear('database')" v-model="form.database" required="required" name="database" type="text" id="database" />
                        </div>

                        <div class="text-red text-sm mt-1 block" v-if="form.errors.has('database')" v-html="form.errors.get('database')"></div>
                    </div>
                </div>

                <div class="sm:col-span-6 flex items-center justify-end mt-3.5">
                    <button type="submit" @click="onSubmit($event)" :disabled="form.loading" id="next-button" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100">
                        <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                        <span :class="[{'opacity-0': form.loading}]">
                            Next
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Form from "./../../plugins/form";
    import { Step, Steps } from "element-ui";
    import InstallSteps from "./Steps.vue";

    export default {
        name: "database",

        components: {
            [Step.name]: Step,
            [Steps.name]: Steps,
            InstallSteps,
        },

        data() {
            return {
                form: new Form("form-install"),
                active: 1,
            };
        },

        created() {
            // The <select> and port field aren't seeded by the Form DOM scan the
            // way text inputs are, so register them as reactive form properties
            // here. Setting them via $set makes v-model two-way bind, and because
            // they become own-properties on the Form instance they're included in
            // form.data() at submit time.
            if (typeof this.form.connection === "undefined" || this.form.connection === "") {
                this.$set(this.form, "connection", "mysql");
            }

            if (typeof this.form.port === "undefined" || this.form.port === "") {
                this.$set(this.form, "port", "3306");
            }
        },

        computed: {
            is_sqlite() {
                return this.form.connection === "sqlite";
            },
        },

        watch: {
            "form.connection": function (driver) {
                // Default the port per driver; blank it for sqlite (unused).
                if (driver === "pgsql") {
                    this.form.port = "5432";
                } else if (driver === "mysql") {
                    this.form.port = "3306";
                } else {
                    this.form.port = "";
                }
            },
        },

        methods: {
            // Form Submit
            onSubmit(event) {
                event.preventDefault();
                this.form.submit();
            },

            next() {
                if (this.active++ > 2);
            },
        },
    };
</script>
