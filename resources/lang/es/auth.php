<?php

return array(

    'labels' => array(
        'username' => 'Nombre de usuario',
        'username-email' => 'Usuario o Contraseña',
        'email' => 'Correo electrónico',
        'name' => 'Nombre',
        'picture' => 'Foto de Perfil',
        'roles' => 'Rol',
        'password' => 'Contraseña',
        'password_confirmation' => 'Confirma tu contraseña',
        'new-password' => 'Nueva contraseña',
        'new-password_confirmation' => 'Confirma tu nueva contraseña',
        'register' => 'Registrarse',
        'login' => 'Iniciar Sesión',
        'remember-me' => 'Recordarme',
        'logout' => 'Cerrar Sesión',
        'my-account' => 'Mi cuenta',
        'reset-password' => 'Cambiar Contraseña',
        'change-password' => 'Cambiar Contraseña',
        'current-password' => 'Contraseña actual',
        'update' => 'Actualizar',
        'send' => 'Enviar',
        'submit' => 'Enviar',
        'forgot-password' => '¿Has perdido tu contraseña?',
        'no-account' => '¿Aún no tienes una cuenta?',
        'have-account' => '¿Ya tienes una cuenta?',
        'or-sign-in' => 'O inicia sesión con',
    ),

    'messages' => array(
        'login' => array(
            'error' => 'Correo electrónico o contraseña no coincide.',
            'success' => 'Has iniciado sesión con éxito.',
            'not-activated' => 'Aún no has activado tu cuenta',
        ),
        'activate' => array(
            'success' => 'Tu usuario ha sido activado, ahora puedes iniciar sesión.',
            'error' => 'Parece que tu enlace ha expirado o no es válido.',
        ),
        'register' => array(
            'error' => 'Algo salió muy mal, por favor intenta de nuevo más tarde.',
            'success' => 'Te has registrado con éxito, por favor sigue los pasos que enviamos a tu correo electrónico para activar tu cuenta.',
            'success-activated' => 'Te has registrado con éxito, puedes iniciar sesión.',
        ),
        'account' => array(
            'error' => 'Algo salió muy mal, por favor intenta de nuevo más tarde (la información de tu cuenta, no ha sido cambiada).',
            'success' => 'Has actializado la información de tu cuenta.',
        ),
        'password' => array(
            'error' => 'Algo salió muy mal, por favor intenta de nuevo más tarde, tu contraseña no se actualizó.',
            'error-match' => 'Contraseña inválida.',
            'success' => 'Has actializado la información de tu cuenta.',
        ),
        'remind' => array(
            'error' => 'Algo salió muy mal, por favor intenta de nuevo más tarde.',
            'success' => 'Puedes re-establecer tu contraseña siguiendo los pasos que enviamos a tuu correo electrónico.',
        ),
        'reset' => array(
            'error' => 'Algo salió muy mal, por favor intenta de nuevo más tarde.',
            'success' => 'Puedes utilizar tu nuevo password para iniciar sesión.',
        ),
        'change_password' => 'Cambiar contraseña',
        'account_info' => 'Información de la cuenta',
    ),

    'emails' => array(
        'remind' => array(
            'subject' => 'Recordatorio de Contraseña',
            'title' => 'Reestablece tu contraseña',
            'body' => 'Para reestablecer tu contraseña completa el formulario en el siguiente enlace:',
            'body-expire' => 'Este enlace expira en :minutes minutos',
        ),
        'activate' => array(
            'subject' => 'Activate your account',
            'title' => 'Activate your account',
            'body' => 'Tu cuenta ha sido creada, para activarla accede al siguiente enlace:',
        ),
        'welcome' => array(
            'subject' => 'Tu nueva cuenta',
            'title' => 'Bienvenido',
            'body' => 'Tu cuenta ha sido creada, para acceder haz click en el siguiente enlace:',
            'password' => 'Un password temporal ha sido generado, es altamente recomendado que lo cambies a la brevedad posible.',
        ),
    ),

);
