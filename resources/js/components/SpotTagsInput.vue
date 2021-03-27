<template>
    <div>
        <input
            type="hidden"
            name="tags"
            :value="tagsJson"
            maxlength="8"
        >
        <vue-tags-input
            class="input_tag"
            v-model="tag"
            :tags="tags"
            placeholder="例） 東京、風が弱い、アジ"
            :autocomplete-items="filteredItems"
            :add-on-key="[13, 32]"
            @tags-changed="newTags => tags = newTags"
        />
    </div>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';

    export default {
        components: {
            VueTagsInput,
        },

        props: {
            initialTags: {
                type: Array,
                default: [],
            },

            autocompleteItems: {
                type: Array,
                default: [],
            },
        },

        data() {
            return {
                tag: '',
                tags: this.initialTags,
            };
        },

        computed: {
            filteredItems() {
                return this.autocompleteItems.filter(i => {
                    return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
                });
            },

            tagsJson() {
                return JSON.stringify(this.tags)
            },
        },
    };
</script>

<style lang="css" scoped>
    .vue-tags-input {
        max-width: inherit;
    }
</style>