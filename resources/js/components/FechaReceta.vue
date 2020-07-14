<template>
  <!-- mandamos a llamar la funcion que hace el formato de la fecha -->
  <span v-text="formatearFecha"></span>
</template>

<script>
export default {
  //Leemos el valor que esta mandando de props , desde la etiqueta
  props: ["fecha"],
  //   mounted() {
  //     console.log("La fecha es", this.fecha);
  //   },
  computed: {
    formatearFecha() {
      //Usamos moment para darle fotmato a la fecha , le colocamos el idioma y el formato a mostrarse
      return moment(this.fecha)
        .locale("es")
        .format("DD [de] MMMM [del] YYYY");
    }
  }
};
</script>