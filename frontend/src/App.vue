<script>
export default {
  data() {
    return {
      token: null,
      usuarioLogado: null,
    };
  },

  mounted() {
    const salva = localStorage.getItem("sessao");
    if (salva) {
      const { token, usuario } = JSON.parse(salva);
      this.token = token;
      this.usuarioLogado = usuario;
    }
  },

  methods: {
    aoLogar({ token, usuario }) {
      this.token = token;
      this.usuarioLogado = usuario;
      localStorage.setItem("sessao", JSON.stringify({ token, usuario }));
      this.$router.push({ name: "usuarios" });
    },

    sair() {
      this.token = null;
      this.usuarioLogado = null;
      localStorage.removeItem("sessao");
      this.$router.push({ name: "login" });
    },
  },
};
</script>

<template>
  <div class="container py-5" style="max-width: 700px;">
    <h1 class="text-center mb-4">Painel de Usuários</h1>

    <router-view
      :usuario-logado="usuarioLogado"
      @login-sucesso="aoLogar"
      @sair="sair"
    />
  </div>
</template>
