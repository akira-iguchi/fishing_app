<template>
    <div>
        <input
            type="hidden"
            name="tags"
            :value="tagsJson"
        >
        <vue-tags-input
            v-model="tag"
            :tags="tags"
            placeholder="タグを5個まで入力できます（6個以上は切り捨て）"
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

<style lang="css">
    .vue-tags-input .ti-tag {
        margin-right: 4px;
        background-color: #fff;
        border: 1px solid #aaa;
        border-radius: 6px;
        color: rgba(0,100,255,0.7);
        font-size: 12px;
        text-decoration: none;
        -webkit-transition: .2s;
        transition: .2s;
        box-sizing: border-box;
    }

    .vue-tags-input .ti-tag:hover {
        background-color: rgba(0,100,255,0.7);
        border: 1px solid rgba(0,100,255,0.7);
        color: #fff;
    }

    .vue-tags-input .ti-tag::before {
        content: "#";
        margin-right: 3px;
    }
</style>
