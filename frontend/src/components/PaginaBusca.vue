<script>
const API_URL = "http://localhost:8080/api";

export default {
  data() {
    return {
      usuarios: [],
      termo: "",
      erro: null,
    };
  },

  watch: {
    // Espera o usuário parar de digitar (300 ms) antes de chamar a API
    termo() {
      clearTimeout(this.timer);
      this.timer = setTimeout(this.carregarUsuarios, 300);
    },
  },

  mounted() {
    this.carregarUsuarios();
  },

  methods: {
    async carregarUsuarios() {
      try {
        const resposta = await fetch(
          `${API_URL}/usuarios/busca?nome=${encodeURIComponent(this.termo.trim())}`
        );
        const corpo = await resposta.json();

        if (!resposta.ok || !corpo.sucesso) {
          throw new Error(corpo.mensagem || `A API respondeu com status ${resposta.status}`);
        }

        this.usuarios = corpo.dados;
        this.erro = null;
      } catch (e) {
        this.erro = "Não foi possível carregar os usuários. " + e.message;
      }
    },
  },
};
</script>

<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="h5 mb-0">Buscar usuários</h2>
      <router-link :to="{ name: 'usuarios' }" class="btn btn-outline-secondary btn-sm">
        Voltar ao painel
      </router-link>
    </div>

    <div v-if="erro" class="alert alert-danger">{{ erro }}</div>

    <input
      v-model="termo"
      type="search"
      class="form-control mb-3"
      placeholder="Buscar por nome"
      autofocus
    />

    <p class="text-muted small">
      {{ usuarios.length }} resultado(s)
    </p>

    <table v-if="usuarios.length" class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="usuario in usuarios" :key="usuario.id">
          <td>{{ usuario.nome }}</td>
          <td>{{ usuario.email }}</td>
        </tr>
      </tbody>
    </table>
    <div v-else-if="!erro" class="text-center text-muted py-4">
      Nenhum usuário encontrado.
    </div>
  </div>
</template>
