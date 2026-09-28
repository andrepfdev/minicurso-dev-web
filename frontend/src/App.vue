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

  methods: {
    aoLogar({ token, usuario }) {
      this.token = token;
      this.usuarioLogado = usuario;
    },

    sair() {
      this.token = null;
      this.usuarioLogado = null;
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
