<template>
  <div>
    <!-- agregale la clase like-active en caso que like tenga un valor positivo -->
    <span class="like-btn" @click="likeReceta" :class="{'like-active' : isActive}"></span>
    <p v-if="cantidadLikes > 1">A {{cantidadLikes}} personas les gustó esta receta</p>
    <p v-else-if="cantidadLikes == 1">A {{cantidadLikes}} persona le gustó esta receta</p>
    <p v-else>Nadie ha dado like a esta receta</p>
  </div>
</template>

<script>
export default {
  props: ["recetaId", "like", "likes"],
  data: function() {
    return {
      // valores que pueden cambiar
      isActive: this.like,
      totalLikes: this.likes
    };
  },
  methods: {
    likeReceta() {
      axios
        .post("/recetas/" + this.recetaId)
        .then(respuesta => {
          if (respuesta.data.attached.length > 0) {
            this.$data.totalLikes++;
          } else {
            this.$data.totalLikes--;
          }

          this.isActive = !this.isActive;
        })
        .catch(error => {
          if (error.response.status === 401) {
            window.location = "/register";
          }
        });
    }
  },
  computed: {
    cantidadLikes: function() {
      return this.totalLikes;
    }
  }
};
</script>