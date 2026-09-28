<script>
const API_URL = "http://localhost:8080/api";

export default {
  emits: ["login-sucesso"],

  data() {
    return {
      email: "",
      senha: "",
      erroLogin: null,
    };
  },

  methods: {
    async login() {
      try {
        const resposta = await fetch(`${API_URL}/auth/login`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email: this.email, senha: this.senha }),
        });
        const corpo = await resposta.json();

        if (!resposta.ok || !corpo.sucesso) {
          throw new Error(corpo.mensagem || `A API respondeu com status ${resposta.status}`);
        }

        this.erroLogin = null;
        this.$emit("login-sucesso", { token: corpo.token, usuario: corpo.usuario });
      } catch (e) {
        this.erroLogin = "Não foi possível entrar. " + e.message;
      }
    },
  },
};
</script>

<template>
  <div class="card mx-auto" style="max-width: 380px;">
    <div class="card-body">
      <h2 class="h5 card-title mb-3">Entrar</h2>
      <div v-if="erroLogin" class="alert alert-danger">{{ erroLogin }}</div>
      <form @submit.prevent="login">
        <div class="mb-3">
          <label class="form-label">E-mail</label>
          <input v-model="email" type="email" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Senha</label>
          <input v-model="senha" type="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-dark w-100">Entrar</button>
      </form>
      <p class="text-muted small mt-3 mb-0">
        Login de teste: <code>ana@ifma.edu.br</code> — senha
        <code>123456</code>.
      </p>
    </div>
  </div>
</template>
