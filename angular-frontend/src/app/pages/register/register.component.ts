import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule
    // NgxMaskDirective foi removida daqui
  ],
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {
  registerForm: FormGroup;
  errorMessage: string | null = null;
  successMessage: string | null = null;
  private token: string | null = null;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private http: HttpClient
  ) {
    this.registerForm = this.fb.group({
      name: ['', [Validators.required, Validators.maxLength(100)]],
      email: [{ value: '', disabled: true }],
      cpf: ['', [Validators.required, Validators.minLength(14), Validators.maxLength(14)]],
      celular: [''],
      cep: ['', [Validators.minLength(9), Validators.maxLength(9)]],
      uf: [''],
      localidade: [''],
      bairro: [''],
      logradouro: [''],
      password: ['', [Validators.required, Validators.minLength(8), Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)]],
      password_confirmation: ['', Validators.required]
    }, { validator: this.passwordMatchValidator });
  }

  ngOnInit(): void {
    this.token = this.route.snapshot.paramMap.get('token');
    // Simulação: idealmente, você buscaria o e-mail na API aqui.
  }

  passwordMatchValidator(form: FormGroup) {
    const password = form.get('password')?.value;
    const confirmPassword = form.get('password_confirmation')?.value;
    return password === confirmPassword ? null : { mismatch: true };
  }

  onCepBlur(): void {
    const cep = this.registerForm.get('cep')?.value?.replace(/\D/g, '');
    if (cep && cep.length === 8) {
      this.http.get(`https://viacep.com.br/ws/${cep}/json/`).subscribe((data: any) => {
        if (!data.erro) {
          this.registerForm.patchValue({
            logradouro: data.logradouro,
            bairro: data.bairro,
            localidade: data.localidade,
            uf: data.uf
          });
        }
      });
    }
  }

  onSubmit(): void {
    if (this.registerForm.invalid) {
      this.errorMessage = "Formulário inválido. Verifique os campos.";
      return;
    }

    const formData = { ...this.registerForm.getRawValue(), token: this.token };

    this.http.post('http://localhost:8000/api/invitations/finalize', formData).subscribe({
      next: (response) => {
        this.successMessage = "Cadastro realizado com sucesso! Você será redirecionado para o login.";
        setTimeout(() => this.router.navigate(['/login']), 3000);
      },
      error: (err) => {
        this.errorMessage = err.error.message || 'Ocorreu um erro ao finalizar o cadastro.';
      }
    });
  }
}
