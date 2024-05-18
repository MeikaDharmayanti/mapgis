Instalasi :

1. Buat sebuah folder baru, kemudian pull repo ini, contoh
	<br>&nbsp;mkdir api-test
	<br>&nbsp;cd api-test
	<br>&nbsp;git init
	<br>&nbsp;git remote add origin https://github.com/manpits/api-jwt-master.git
	<br>&nbsp;git pull origin main

2. Buat file konfigurasi dengan cara mengcopy file .env.example menjadi .env
3. Buatlah sebuah database baru pada server database mysql anda
4. Pastikan setting koneksi ke database (server, database name, username, password dan port) pada file .env sudah dilakukan dengan benar
5. Jalankan perintah "composer install" untuk menginstall library yg dibutuhkan
6. Jalankan perintah "php artisan migrate" untuk mengenerate tabel user ke database
7. Jalankan perintah "php artisan jwt:secret" untuk mengenerate jtw secret
8. Jalankan perintah "php artisan key:generate" untuk mengenerate key-aplikasi
9. Gunakan aplikasi api client (Postman atau ThunderClient (vscode)) untuk mengakses API, contoh :

    <br>&nbsp;- Untuk register user
    <br>&nbsp;&nbsp;    POST    /api/register
    <br>
    <br>&nbsp;&nbsp;    body : {
    <br>&nbsp;&nbsp;&nbsp;&nbsp;                "email"     : "manpits@gmail.com",
    <br>&nbsp;&nbsp;&nbsp;&nbsp;                "password"  : "123456",
    <br>&nbsp;&nbsp;&nbsp;&nbsp;                "name"      : "manpits"
    <br>&nbsp;&nbsp;            }    
    <br>
    <br>&nbsp;- Untuk login user
    <br>&nbsp;&nbsp;    POST /api/login
    <br>
    <br>&nbsp;&nbsp;    body : {
    <br>&nbsp;&nbsp;&nbsp;&nbsp;                "email"     : "manpits@gmail.com",
    <br>&nbsp;&nbsp;&nbsp;&nbsp;                "password"  : "123456"
    <br>&nbsp;&nbsp;            }    

10. Untuk selengkapnya silahkan nanti tambahkan routes yang anda miliki di file routes/api.php

    <br>&nbsp;Route::post('login', [ApiController::class, 'authenticate']);
    <br>&nbsp;Route::post('register', [ApiController::class, 'register']);
    <br>&nbsp;Route::group(['middleware' => ['jwt.verify']], function () {
    <br>&nbsp;&nbsp;    Route::get('logout', [ApiController::class, 'logout']);
    <br>&nbsp;&nbsp;    Route::get('getuser', [ApiController::class, 'get_user']);
    <br>&nbsp;&nbsp;    /**
    <br>&nbsp;&nbsp;    * Silahkan tambahkan routes anda disini ...
    <br>&nbsp;&nbsp;    */
    <br>&nbsp;});
