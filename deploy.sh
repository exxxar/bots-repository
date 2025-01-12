clear
cd ~/dpr.next-bots.ru/public_html/
git pull origin master
php8.1 ~/.local/bin/composer install
php8.1 artisan migrate
cd ~/krasnodar.next-bots.ru/public_html/
git pull origin master
php8.1 ~/.local/bin/composer install
php8.1 artisan migrate
cd ~/core.next-bots.ru/public_html/
git pull origin master
php8.1 ~/.local/bin/composer install
php8.1 artisan migrate
