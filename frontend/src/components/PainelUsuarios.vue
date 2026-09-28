<script>
const API_URL = "http://localhost:8080/api";

export default {
  props: {
    usuarioLogado: { type: Object, required: true },
  },

  emits: ["sair"],

  data() {
    return {
      // listagem
      usuarios: [],
      erro: null,

      // cadastro
      novoUsuario: { nome: "", email: "", senha: "" },

      // edição
      idEmEdicao: null,
      edicao: { nome: "", email: "" },

      // exclusão (pede confirmação antes de chamar a API)
      idParaExcluir: null,

      // Desafio: implementar busca de usuário por ID (opcional)
      // idBusca: "",
      // resultadoBusca: null,
      // erroBusca: null,
    };
  },

  mounted() {
    this.carregarUsuarios();
  },

  methods: {
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
        this.erro = "Não foi possível carregar os usuários. O backend está rodando (php spark serve)? " + e.message;
      }
    },

    async cadastrar() {
      try {
        const resposta = await fetch(`${API_URL}/usuarios`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            nome_completo: this.novoUsuario.nome,
            email: this.novoUsuario.email,
            senha: this.novoUsuario.senha,
          }),
        });
        const corpo = await resposta.json();

        if (!resposta.ok || !corpo.sucesso) {
          throw new Error(corpo.mensagem || `A API respondeu com status ${resposta.status}`);
        }

        this.novoUsuario = { nome: "", email: "", senha: "" };
        this.erro = null;
        await this.carregarUsuarios();
      } catch (e) {
        this.erro = "Não foi possível cadastrar o usuário. " + e.message;
      }
    },

    iniciarEdicao(usuario) {
      this.idEmEdicao = usuario.id;
      this.edicao = { nome: usuario.nome, email: usuario.email };
    },

    cancelarEdicao() {
      this.idEmEdicao = null;
    },

    async salvarEdicao(usuario) {
      try {
        const resposta = await fetch(`${API_URL}/usuarios/${usuario.id}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.edicao),
        });
        const corpo = await resposta.json();

        if (!resposta.ok || !corpo.sucesso) {
          throw new Error(corpo.mensagem || `A API respondeu com status ${resposta.status}`);
        }

        this.idEmEdicao = null;
        this.erro = null;
        await this.carregarUsuarios();
      } catch (e) {
        this.erro = "Não foi possível salvar a edição. " + e.message;
      }
    },

    pedirConfirmacaoExclusao(usuario) {
      this.idParaExcluir = usuario.id;
    },

    cancelarExclusao() {
      this.idParaExcluir = null;
    },

    async excluir(usuario) {
      try {
        const resposta = await fetch(`${API_URL}/usuarios/${usuario.id}`, {
          method: "DELETE",
        });
        const corpo = await resposta.json();

        if (!resposta.ok || !corpo.sucesso) {
          throw new Error(corpo.mensagem || `A API respondeu com status ${resposta.status}`);
        }

        this.idParaExcluir = null;
        this.erro = null;
        await this.carregarUsuarios();
      } catch (e) {
        this.erro = "Não foi possível excluir o usuário. " + e.message;
      }
    },

    // Desafio: implementar busca de usuário por ID (opcional)
    // async buscarUsuario() {
    //   try {
    //     const resposta = await fetch(`${API_URL}/usuarios/${this.idBusca}`);
    //     const corpo = await resposta.json(); 
    //     if (!resposta.ok || !corpo.sucesso) {
    //       throw new Error(corpo.mensagem || `A API respondeu com status ${resposta.status}`);
    //     }
    //     this.resultadoBusca = corpo.dados;
    //   } catch (e) {
    //     this.erroBusca = "Não foi possível buscar o usuário. " + e.message;
    //   }
    // }
  },
};
</script>

<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <span>Logado como <strong>{{ usuarioLogado.nome }}</strong></span>
      <button class="btn btn-outline-secondary btn-sm" @click="$emit('sair')">Sair</button>
    </div>

    <div v-if="erro" class="alert alert-danger">{{ erro }}</div>

    <form class="row g-2 mb-4" @submit.prevent="cadastrar">
      <div class="col-4">
        <input v-model="novoUsuario.nome" type="text" class="form-control" placeholder="Nome" required />
      </div>
      <div class="col-4">
        <input v-model="novoUsuario.email" type="email" class="form-control" placeholder="E-mail" required />
      </div>
      <div class="col-3">
        <input v-model="novoUsuario.senha" type="password" class="form-control" placeholder="Senha" required />
      </div>
      <div class="col-1">
        <button type="submit" class="btn btn-dark w-100">+</button>
      </div>
    </form>

    <!-- Desafio: implementar busca de usuário por ID (opcional)
    <div class="mb-4">
      <input v-model="idBusca" type="number" placeholder="Buscar por ID" class="form-control d-inline-block w-auto" />
      <button class="btn btn-outline-dark btn-sm" @click="buscarUsuario">Buscar</button>
      <div v-if="resultadoBusca">{{ resultadoBusca.nome }} — {{ resultadoBusca.email }}</div>
      <div v-if="erroBusca" class="text-danger">{{ erroBusca }}</div>
    </div>
    -->

    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
          <th class="text-end">Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="usuario in usuarios" :key="usuario.id">
          <template v-if="idEmEdicao === usuario.id">
            <td><input v-model="edicao.nome" type="text" class="form-control form-control-sm" /></td>
            <td><input v-model="edicao.email" type="email" class="form-control form-control-sm" /></td>
            <td class="text-end">
              <button class="btn btn-sm btn-success me-1" @click="salvarEdicao(usuario)">Salvar</button>
              <button class="btn btn-sm btn-outline-secondary" @click="cancelarEdicao">Cancelar</button>
            </td>
          </template>
          <template v-else-if="idParaExcluir === usuario.id">
            <td colspan="2" class="text-danger">Excluir {{ usuario.nome }}? Não tem como desfazer.</td>
            <td class="text-end">
              <button class="btn btn-sm btn-danger me-1" @click="excluir(usuario)">Confirmar</button>
              <button class="btn btn-sm btn-outline-secondary" @click="cancelarExclusao">Cancelar</button>
            </td>
          </template>
          <template v-else>
            <td>{{ usuario.nome }}</td>
            <td>{{ usuario.email }}</td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-primary me-1" @click="iniciarEdicao(usuario)">Editar</button>
              <button class="btn btn-sm btn-outline-danger" @click="pedirConfirmacaoExclusao(usuario)">Excluir</button>
            </td>
          </template>
        </tr>
      </tbody>
    </table>
  </div>
</template>
