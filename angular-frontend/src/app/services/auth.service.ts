import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  // A URL base da sua API Laravel. O Angular irá comunicar com ela.
  private apiUrl = 'http://localhost:8000/api';

  constructor(private http: HttpClient) { }

  /**
   * Envia as credenciais para a API para tentar fazer o login.
   * @param credentials Objeto com cpf e password.
   * @returns Um Observable com a resposta da API.
   */
  login(credentials: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/login`, credentials).pipe(
      tap((response: any) => {
        // Se a resposta contiver um token, nós o salvamos no localStorage do navegador.
        if (response.token) {
          localStorage.setItem('authToken', response.token);
        }
      })
    );
  }

  /**
   * Remove o token de autenticação, efetivamente fazendo o logout.
   */
  logout(): void {
    localStorage.removeItem('authToken');
    // Aqui você também pode chamar um endpoint de logout na sua API, se tiver um.
  }

  /**
   * Verifica se o usuário está autenticado (se existe um token).
   */
  isLoggedIn(): boolean {
    return !!localStorage.getItem('authToken');
  }

  /**
   * Obtém o token de autenticação.
   */
  getToken(): string | null {
    return localStorage.getItem('authToken');
  }
}
