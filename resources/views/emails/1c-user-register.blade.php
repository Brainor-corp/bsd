<!DOCTYPE html>
<html>
<body>
<h1>{{ $user['name'] }},</h1>
<p>на сайте {{ url('/') }} для Вас была создана учётная запись.</p>
<p>Используйте email <strong>{{ $user['email'] }}</strong> и временный пароль <strong>{{ $password }}</strong> для входа.</p>
</body>
</html>