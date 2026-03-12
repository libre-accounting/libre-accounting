<template>
    <SlideYUpTransition :duration="animationDuration">
        <div class="modal w-full h-full fixed top-0 left-0 right-0 z-50 overflow-y-auto overflow-hidden modal-add-new fade items-center justify-center"
            :class="[{'show flex flex-wrap modal-background': show}, {'hidden': !show}]"
            v-show="show"
            tabindex="-1"
            role="dialog"
            :aria-hidden="!show">
            <div class="w-full my-10 m-auto flex flex-col max-w-screen-sm">
                <div class="bg-body rounded-lg modal-content">
                    <div class="p-5">
                        <div class="flex items-center justify-between border-b pb-5">
                            <h4 class="text-base font-medium">
                                {{ translations.title }}
                            </h4>

                            <button type="button" class="text-lg" @click="onCancel" aria-hidden="true">
                                <span class="rounded-md border-b-2 px-2 py-1 text-sm bg-gray-100">esc</span>
                            </button>
                        </div>
                    </div>

                    <div class="px-5">
                        <template v-if="transaction">
                            <div class="flex flex-col items-start gap-y-1 text-left text-sm pb-4">
                                <div>
                                    <b>{{ translations.account }}:</b> {{ transaction.account.name }}
                                </div>
                                <div>
                                    <b>{{ translations.date }}:</b> {{ transaction.paid_at ? transaction.paid_at.substr(0, 10) : '' }}
                                </div>
                            </div>
                        </template>

                        <div class="pb-3">
                            <label class="block text-sm font-medium pb-1">{{ translations.match_account }}</label>
                            <select
                                v-model="account_id"
                                @change="onFilterChange"
                                class="w-full h-11 rounded-lg border border-gray-200 px-3 text-sm"
                            >
                                <option :value="null" disabled>{{ translations.select_account }}</option>
                                <option v-for="(name, id) in accounts" :key="id" :value="id">{{ name }}</option>
                            </select>
                        </div>

                        <div class="flex items-center pb-3">
                            <input id="link-transfer-all-dates" type="checkbox" v-model="all_dates" @change="onFilterChange" class="mr-2" />
                            <label for="link-transfer-all-dates" class="text-sm">{{ translations.all_dates }}</label>
                        </div>

                        <div class="pb-2">
                            <template v-if="account_id">
                                <div v-if="loading" class="py-4 text-sm text-center text-gray-400">
                                    {{ translations.loading }}
                                </div>

                                <div v-else-if="!candidates.length" class="py-4 text-sm text-center text-gray-400">
                                    {{ translations.no_candidates }}
                                </div>

                                <table v-else class="w-full text-sm">
                                    <tbody>
                                        <tr v-for="candidate in candidates" :key="candidate.id" class="border-b border-gray-200">
                                            <td class="py-2 pr-2 align-top" style="width: 32px;">
                                                <input type="radio" name="link-transfer-candidate" :value="candidate.id" v-model="target_transaction_id" />
                                            </td>
                                            <td class="py-2">
                                                <div>{{ candidate.paid_at ? candidate.paid_at.substr(0, 10) : '' }}</div>
                                                <div class="text-gray-500 truncate" v-if="candidate.description">{{ candidate.description }}</div>
                                            </td>
                                            <td class="py-2 text-right whitespace-nowrap align-top">
                                                {{ candidate.amount }} {{ candidate.currency_code }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>

                            <div v-else class="py-4 text-sm text-center text-gray-400">
                                {{ translations.select_account_hint }}
                            </div>
                        </div>
                    </div>

                    <div class="p-5 border-gray-300">
                        <div class="flex items-center justify-end">
                            <button type="button" class="px-6 py-1.5 mr-2 hover:bg-gray-200 rounded-lg" @click="onCancel">
                                {{ translations.cancel }}
                            </button>

                            <button type="button"
                                :disabled="!target_transaction_id || loading"
                                class="relative px-6 py-1.5 bg-green hover:bg-green-700 text-white rounded-lg disabled:bg-green-100"
                                @click="onConfirm"
                            >
                                {{ translations.save }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SlideYUpTransition>
</template>

<script>
import { SlideYUpTransition } from "vue2-transitions";

export default {
    name: 'libre-accounting-link-transfer',

    components: {
        SlideYUpTransition,
    },

    props: {
        show: Boolean,
        transaction: Object,
        accounts: [Object, Array],
        candidates: Array,
        loading: Boolean,
        translations: Object,
        animationDuration: {
            type: Number,
            default: 800,
            description: "Modal transition duration",
        },
    },

    data() {
        return {
            account_id: null,
            all_dates: false,
            target_transaction_id: null,
        };
    },

    mounted() {
        window.addEventListener('keyup', (e) => {
            if (e.key === 'Escape') {
                this.onCancel();
            }
        });
    },

    methods: {
        onFilterChange() {
            this.target_transaction_id = null;

            this.$emit('filter-changed', {
                account_id: this.account_id,
                all_dates: this.all_dates,
            });
        },

        onConfirm() {
            this.$emit('confirm', this.target_transaction_id);
        },

        onCancel() {
            this.$emit('close-modal');
        },
    },

    watch: {
        show: function (newValue) {
            if (newValue) {
                this.account_id = null;
                this.all_dates = false;
                this.target_transaction_id = null;
            }
        },
    },
}
</script>
