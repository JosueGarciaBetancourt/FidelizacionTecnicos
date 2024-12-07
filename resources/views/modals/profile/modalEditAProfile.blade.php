@extends('layouts.layoutDashboard')

@section('title', 'Perfil')

@section('content')
	<div class="container">
		<h1 class="page-title">{{ __('Perfil') }}</h1>

		<div class="profile-sections">
			{{-- Profile Information Section --}}
			<div class="profile-section">
				<div class="section-header">
					<h2>{{ __('Información de Perfil') }}</h2>
					<p>{{ __("Actualiza la información de perfil y dirección de correo electrónico de tu cuenta") }}</p>
				</div>

				<form method="POST" action="{{ route('profile.update') }}" class="profile-form">
					@csrf
					@method('patch')

					<div class="form-group">
						<label for="name">{{ __('Nombre') }}</label>
						<input 
							type="text" 
							id="name" 
							name="name" 
							class="form-control" 
							value="{{ old('name', $user->name) }}" 
							required 
							autofocus
						>
						@error('name')
							<div class="error-message">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="email">{{ __('Correo electrónico') }}</label>
						<input 
							type="email" 
							id="email" 
							name="email" 
							class="form-control" 
							value="{{ old('email', $user->email) }}" 
							required
						>
						@error('email')
							<div class="error-message">{{ $message }}</div>
						@enderror

						@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
							<div class="verification-notice">
								{{ __('Tu correo electrónico no está verificado.') }}
								<form id="send-verification" method="post" action="{{ route('verification.send') }}">
									@csrf
									<button type="submit" class="link-button">
										{{ __('Reenviar correo de verificación') }}
									</button>
								</form>
							</div>
						@endif
					</div>

					<div class="form-actions">
						<button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
						
						@if (session('status') === 'profile-updated')
							<span class="success-message">{{ __('Guardado correctamente.') }}</span>
						@endif
					</div>
				</form>
			</div>

			{{-- Change Password Section --}}
			<div class="profile-section">
				<div class="section-header">
					<h2>{{ __('Actualizar Contraseña') }}</h2>
					<p>{{ __('Asegúrate de que tu cuenta utilice una contraseña segura') }}</p>
				</div>

				<form method="POST" action="{{ route('password.update') }}" class="profile-form">
					@csrf
					@method('put')

					<div class="form-group">
						<label for="current_password">{{ __('Contraseña actual') }}</label>
						<input 
							type="password" 
							id="current_password" 
							name="current_password" 
							class="form-control" 
							required
						>
						@error('current_password')
							<div class="error-message">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="password">{{ __('Contraseña nueva') }}</label>
						<input 
							type="password" 
							id="password" 
							name="password" 
							class="form-control" 
							required
						>
						@error('password')
							<div class="error-message">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="password_confirmation">{{ __('Confirmar contraseña') }}</label>
						<input 
							type="password" 
							id="password_confirmation" 
							name="password_confirmation" 
							class="form-control" 
							required
						>
						@error('password_confirmation')
							<div class="error-message">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-actions">
						<button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
						
						@if (session('status') === 'password-updated')
							<span class="success-message">{{ __('Guardado satisfactoriamente.') }}</span>
						@endif
					</div>
				</form>
			</div>

			{{-- Delete Account Section --}}
			<div class="profile-section delete-account">
				<div class="section-header">
					<h2>{{ __('Eliminar Cuenta') }}</h2>
					<p>{{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente.') }}</p>
				</div>

				<button 
					class="btn btn-danger" 
					data-bs-toggle="modal" 
					data-bs-target="#deleteAccountModal"
				>
					{{ __('Eliminar cuenta') }}
				</button>
			</div>
		</div>
	</div>

	{{-- Delete Account Modal --}}
	<div class="modal fade" id="deleteAccountModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="POST" action="{{ route('profile.destroy') }}">
					@csrf
					@method('delete')

					<div class="modal-header">
						<h5 class="modal-title">{{ __('Confirmar Eliminación de Cuenta') }}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>

					<div class="modal-body">
						<p>{{ __('Confirme su contraseña para eliminar permanentemente su cuenta.') }}</p>
						
						<div class="form-group">
							<label for="delete_password">{{ __('Contraseña') }}</label>
							<input 
								type="password" 
								id="delete_password" 
								name="password" 
								class="form-control" 
								required
							>
							@error('password', 'userDeletion')
								<div class="error-message">{{ $message }}</div>
							@enderror
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
							{{ __('Cancelar') }}
						</button>
						<button type="submit" class="btn btn-danger">
							{{ __('Eliminar Cuenta') }}
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('styles')
<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .page-title {
        text-align: center;
        margin-bottom: 30px;
    }

    .profile-sections {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .profile-section {
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .section-header {
        margin-bottom: 20px;
    }

    .section-header h2 {
        margin-bottom: 10px;
    }

    .profile-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-control {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .error-message {
        color: red;
        font-size: 0.8em;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn {
        padding: 8px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .success-message {
        color: green;
        font-size: 0.9em;
    }

    .delete-account {
        border: 1px solid #dc3545;
        background-color: #fff0f0;
    }

    .verification-notice {
        margin-top: 10px;
        font-size: 0.9em;
        color: #666;
    }

    .link-button {
        background: none;
        border: none;
        color: #007bff;
        text-decoration: underline;
        cursor: pointer;
        padding: 0;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButton = document.querySelector('.btn-danger[data-bs-toggle="modal"]');
        if (deleteButton) {
            deleteButton.addEventListener('click', function(e) {
                e.preventDefault();
                new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
            });
        }
    });
</script>
@endsection