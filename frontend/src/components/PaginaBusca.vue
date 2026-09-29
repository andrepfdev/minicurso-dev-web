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

  computed: {
    // Filtra por nome ou e-mail, ignorando maiúsculas e acentos
    resultados() {
      const termo = this.normalizar(this.termo.trim());
      if (!termo) return this.usuarios;

      return this.usuarios.filter(
        (u) =>
          this.normalizar(u.nome).includes(termo) ||
          this.normalizar(u.email).includes(termo)
      );
    },
  },

  mounted() {
    this.carregarUsuarios();
  },

  methods: {
    normalizar(texto) {
      return String(texto ?? "")
        .normalize("NFD")
        .replace(/[̀-ͯ]/g, "")
        .toLowerCase();
    },

    async carregarUsuarios() {
      try {
        const resposta = await fetch(`${API_URL}/usuarios`);
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
      placeholder="Buscar por nome ou e-mail"
      autofocus
    />

    <p class="text-muted small">
      {{ resultados.length }} resultado(s)
    </p>

    <table v-if="resultados.length" class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="usuario in resultados" :key="usuario.id">
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
