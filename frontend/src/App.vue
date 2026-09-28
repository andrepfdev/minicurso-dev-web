<script>
import TelaLogin from "./components/TelaLogin.vue";
import PainelUsuarios from "./components/PainelUsuarios.vue";

export default {
  components: { TelaLogin, PainelUsuarios },

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
    },

    sair() {
      this.token = null;
      this.usuarioLogado = null;
      localStorage.removeItem("sessao");
    },
  },
};
</script>

<template>
  <div class="container py-5" style="max-width: 700px;">
    <h1 class="text-center mb-4">Painel de Usuários</h1>

    <TelaLogin v-if="!token" @login-sucesso="aoLogar" />
    <PainelUsuarios v-else :usuario-logado="usuarioLogado" @sair="sair" />
  </div>
</template>
