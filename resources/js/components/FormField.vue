<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <textarea
                :disabled="disabled"
                class="w-full form-control form-input form-input-bordered py-3 h-auto"
                :id="field.attribute"
                :dusk="field.attribute"
                v-bind="extraAttributes"
                v-model="value"
            />
        </template>
    </default-field>
</template>

<script>
    import { FormField, HandlesValidationErrors } from 'laravel-nova'

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: {
            resourceName: { type: String },
            field: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                value: null,
                loaded: false,
                parentValue: null
            }
        },

        mounted() {
            this.watchedComponents.forEach(component => {

                let attribute = 'value';

                if(component.field.component === 'belongs-to-field') {
                    attribute = 'selectedResource';
                }

                component.$watch(attribute, (value) => {

                    this.parentValue = (value && attribute === 'selectedResource') ? value.value : value;

                    this.updateOptions();
                }, { immediate: true });
            });
        },

        computed: {
            defaultAttributes() {
                return {
                    rows: this.field.rows,
                    class: this.errorClasses,
                    placeholder: this.field.name,
                }
            },

            extraAttributes() {
                const attrs = this.field.extraAttributes;

                return {
                    ...this.defaultAttributes,
                    ...attrs,
                }
            },

            watchedComponents() {
                return this.$parent.$children.filter(component => {
                    return this.isWatchingComponent(component);
                })
            },
            endpoint() {
                return this.field.endpoint
                    .replace('{resource-name}', this.resourceName)
                    .replace('{resource-id}', this.resourceId ? this.resourceId : '')
                    .replace('{'+ this.field.parent_attribute +'}', this.parentValue ? this.parentValue : '')
            },
            empty() {
                return this.loaded && this.value == null;
            },

            disabled() {
                return this.loaded === false && (this.field.parent_attribute !== undefined && this.parentValue == null) || this.value == null
            }
        },

        methods: {
            setInitialValue() {
                this.value = this.field.value || ''
            },

            fill(formData) {
                formData.append(this.field.attribute, this.value || '')
            },

            updateOptions() {
                this.value = null;
                this.loaded = false;

                if(this.notWatching() || (this.parentValue != null && this.parentValue !== '')) {
                    Nova.request().get(this.endpoint)
                        .then(response => {
                            this.loaded = true;
                            this.value = response.data;
                        })
                }
            },

            notWatching() {
                return this.field.parent_attribute === undefined;
            },

            isWatchingComponent(component) {
                return component.field !== undefined
                    && component.field.attribute === this.field.parent_attribute;
            }
        },
    }
</script>
