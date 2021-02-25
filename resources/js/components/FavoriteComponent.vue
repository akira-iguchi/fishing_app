<template>
  <div>
    <i
      v-on:click="storeOrDelete"
      :class="[isActiveTrue === true ? 'far fa-heart ml-3' : 'fas fa-heart ml-3']"
    ></i>
  </div>
</template>

<script>
export default {
  props: ["spotId", "favoriteData"],
  data() {
    return {
      isActiveTrue: this.favorteData.includes(this.spotId) ? false : true
    };
  },
  methods: {
    change() {
      this.isActiveTrue = !this.isActiveTrue;
    },
    storeSpotId() {
      axios
        .post("favorite/" + this.spotId, {
          productId: this.productId
        })
        .then(response => {
          console.log("success");
        })
        .catch(err => {
          console.log("error");
        });
    },
    deleteProductId() {
      axios
        .delete("favoirte/" + this.spotId, {
          data: {
            spotId: this.spotId
          }
        })
        .then(response => {
          console.log("success");
        })
        .catch(err => {
          console.log("error");
        });
    },
    storeOrDelete() {
      const isTrue = this.favoriteData.includes(this.spotId);
      if (isTrue === true) {
        this.deleteSpotId();
        this.change();
      } else {
        this.storeSpotId();
        this.change();
      }
    }
  }
};
</script>