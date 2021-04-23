<template>
    <div>
        <input
            type="hidden"
            :value="tagsJson"
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
                const jsonTags = JSON.stringify(this.tags)
                this.$emit("tagsInput", jsonTags)
                return jsonTags
            },
        },
    };
</script>

<style lang="css" scoped>
    .vue-tags-input {
        max-width: inherit;
    }
</style>