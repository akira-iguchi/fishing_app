<template></template>

<script>
    export default {
        props: {
            position: {
                type: Object,
                required: true
            },
            google: {
                type: Object,
                required: true
            },
            map: {
                type: Object,
                required: true
            }
        },
        data () {
            return {
                googleMarker: null
            }
        },
        mounted () {
            const { Marker } = this.google.maps

            this.googleMarker = new Marker({
                draggable: true,
                position: this.position,
                map: this.map,
            })

            this.googleMarker.addListener('dragend', (location) => {
                this.$emit('dragendMarker', location)
            })
        },
    }
</script>