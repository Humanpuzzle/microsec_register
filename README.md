Telepítés és futattás

    git clone https://github.com/Humanpuzzle/microsec_register.git


Laravel és JavaScript csomagok telepítése a project mappájában:

    composer install
    npm install


Adatbázis generálása:

    php artisan migrate:refresh --seed


Projekt indítása (fejlesztői szerver indítása):

    php artisan serve
    npm run dev


Pest tesztek futtatása:

    php artisan test
