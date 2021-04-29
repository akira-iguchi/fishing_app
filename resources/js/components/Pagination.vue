<template>
    <div class="pagination">
        <RouterLink
            class="button"
            v-if="! isFirstPage"
            :to="`/?page=${currentPage - 1}`"
        >
            &laquo; prev
        </RouterLink>
        <RouterLink
            class="button"
            v-if="! isLastPage"
            :to="`/?page=${currentPage + 1}`"
        >
            next &raquo;
        </RouterLink>
    </div>
</template>

<script>
    export default {
        props: {
            currentPage: {
                type: Number,
                required: true
            },
            lastPage: {
                type: Number,
                required: true
            }
        },
        computed: {
            isFirstPage () {
                return this.currentPage === 1
            },
            isLastPage () {
                return this.currentPage === this.lastPage
            }
        }
    }
</script>