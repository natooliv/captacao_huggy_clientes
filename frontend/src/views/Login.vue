<template>
  <div class="login-container">
    <h1 class="login-title">Login</h1>
    <button class="login-button" @click="loginWithHuggy">Fazer login com a Huggy</button>
  </div>

</template>

<script>
export default {
  name: 'LoginForm',
  methods: {
    async loginWithHuggy() {
      try {
        // Faz a chamada para autenticar o usuário com o backend
        const response = await fetch('http://localhost:8000/api/auth/google', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        });

        if (response.ok) {
          // Autenticação bem-sucedida
          console.log('Usuário autenticado com sucesso.');

          // Redireciona para a rota "dashboard" usando o Vue Router
          this.$router.push('/dashboard');
        } else {
          // Trata o erro de autenticação
          console.error('Erro ao autenticar usuário:', response.statusText);
        }
      } catch (error) {
        // Trata erros de rede ou outros erros
        console.error('Erro ao autenticar usuário:', error.message);
      }
    }
  }
}
</script>
<style scoped>

.login-container {
  align-items: center;
  padding: 282px;
  justify-content: center;
  margin-right: 80px;
}

.login-title {
  margin-bottom: 20px; 
  color: #262626;
  font-size: 24px; 
  font-family: 'Roboto', sans-serif; 
  line-height: 32px;
  font-weight: 400;
  font-style: normal;
   
}
  


.login-button {
  background-color: #321BDE;
  color: white;
  border: none;
  border-radius: 11px;
  padding: 9px 12px;
  text-transform: uppercase;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
  max-width: 300px;
}

/* Estilos para tablets */
@media (min-width: 600px) {
  .login-title {
    font-size: 28px; /* Tamanho do texto para tablets */
  }
  
  .login-button {
    padding: 12px 24px;
  }
}

/* Estilos para desktops e telas maiores */
@media (min-width: 1024px) {
  .login-title {
    font-size: 32px; /* Tamanho do texto para desktops */
  }

  .login-button {
    padding: 14px 28px;
  }
}

.login-button:hover {
  background-color: #2a17b1;
}
</style>