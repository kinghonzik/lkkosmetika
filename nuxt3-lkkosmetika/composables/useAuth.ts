export default function () {
    class Auth {
      public token = '';
      public user = '';
      public expiration = '';
      public expirationLength = '';

      public get isAuthenticated() {
        if (!this.token || !this.expiration || !this.user)
          return false;

        const specific_date = new Date(+this.expiration * 1000);
        const current_date = new Date();

        if(specific_date.getTime() > current_date.getTime())
            return true;
        
        return false;
      }

      public get timeRemaining() {
        const startDate = new Date();
        const endDate = new Date(+this.expiration * 1000);
        const seconds = (endDate.getTime() - startDate.getTime()) / 1000;
        return seconds;
      }

      constructor() {
        const token = localStorage.getItem('auth_token')
        if (token) 
          this.setToken(token);
      }

      public async tryLogin(user, pass) {
        try {
          var responseStr = await $fetch('http://localhost/backend-lkkoksmetika/JWT/token-get.php', {
              method: 'POST',
              body: { user: user, pass: pass }
          });
          const response = JSON.parse('' + responseStr);
          
          if (!response.success)
            return false;

          this.setToken(response.token);

          return true;
        }
        catch (exception)
        {
          throw exception;
        }
      }

      public async getNewToken() {
        try {
          var responseStr = await $fetch('http://localhost/backend-lkkoksmetika/JWT/token-get-new.php', {
              method: 'POST',
              body: this.token
          });
          const response = JSON.parse('' + responseStr);

          if (!response.success)
            return false;

          this.setToken(response.token);

          return true;
        }
        catch (exception)
        {
            throw exception;
        }
      }

      public logout() {
        localStorage.removeItem("auth_token");
        this.token = '';
        this.user = '';
        this.expiration = '';
      }

      private getTokenPayload(token) {
          var base64Url = token.split('.')[1];
          var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
          var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
              return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
          }).join(''));
      
          return JSON.parse(jsonPayload);
      }

      private setToken(token) {
        localStorage.setItem("auth_token", token);
        this.token = token;
        const payload = this.getTokenPayload(this.token);
        this.user = payload.user;
        this.expiration = payload.exp;
        this.expirationLength = payload.expLength;
      }

    }

    const auth = new Auth();
    return useState('auth', () => auth)
  }