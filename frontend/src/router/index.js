import { createRouter, createWebHistory } from "vue-router";
import TelaLogin from "../components/TelaLogin.vue";
import PainelUsuarios from "../components/PainelUsuarios.vue";
import PaginaBusca from "../components/PaginaBusca.vue";

const router = createRouter({
  // Usa a History API do navegador (URLs sem "#")
  history: createWebHistory(),
  routes: [
    // A raiz redireciona para o painel (o guard cuida do login)
    { path: "/", redirect: "/usuarios" },

    { path: "/login", name: "login", component: TelaLogin },

    {
      path: "/usuarios",
      name: "usuarios",
      component: PainelUsuarios,
      // Marca a rota como protegida; lido no beforeEach abaixo
      meta: { requerLogin: true },
    },

    {
      path: "/busca",
      name: "busca",
      component: PaginaBusca,
      meta: { requerLogin: true },
    },

    // Qualquer caminho desconhecido volta para a raiz
    { path: "/:pathMatch(.*)*", redirect: "/" },
  ],
});

// Guard global: roda antes de toda navegação
router.beforeEach((to) => {
  // Há sessão salva no localStorage (gravada pelo App.vue no login)
  const logado = !!localStorage.getItem("sessao");

  // Sem sessão, não acessa rotas protegidas
  if (to.meta.requerLogin && !logado) return { name: "login" };

  // Já logado, não faz sentido ver a tela de login
  if (to.name === "login" && logado) return { name: "usuarios" };

  // Sem retorno: a navegação segue normalmente
});

export default router;
