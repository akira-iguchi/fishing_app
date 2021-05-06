<template>
    <div class="mb-2">
        <VueLoading
            type="spin"
            color="#333"
            :size="{ width: '80px', height: '80px' }"
        ></VueLoading>
    </div>
</template>

<script>
    import { VueLoading } from 'vue-loading-template'
    export default {
        components: {
            VueLoading
        }
    }
</script>