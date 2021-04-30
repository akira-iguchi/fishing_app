<template>
    <div class="pagination">
        <RouterLink
            class="button"
            v-if="! isFirstPage"
            to="/spots/search/"
        >
            &lt;&lt;
        </RouterLink>
        <RouterLink
            class="button"
            v-if="! isFirstPage"
            :to="`/spots/search/?page=${ currentPage - 1 }`"
        >
            &laquo; 前
        </RouterLink>
        <RouterLink
            class="button"
            v-if="! isLastPage"
            :to="`/spots/search/?page=${ currentPage + 1 }`"
        >
            次 &raquo;
        </RouterLink>
        <RouterLink
            class="button"
            v-if="! isLastPage"
            :to="`/spots/search/?page=${ lastPage }`"
        >
            &gt;&gt;
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