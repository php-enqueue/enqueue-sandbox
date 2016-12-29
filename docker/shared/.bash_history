cd symfony/
./bin/console doctrine:schema:update --force 
clear
cd symfony/
./bin/console 
./bin/console | grep app
./bin/console app:generate-blogs  -n 100
./bin/console app:generate-blogs  --number=100
./bin/console doctrine:schema:update --force 
./bin/console app:generate-blogs  -n 1000000
./bin/console app:generate-blogs  --number .
./bin/console fos:elastica:populate
time ./bin/console fos:elastica:populate
./bin/console debug:container | grep "fos_elastica.provider."
time ./bin/console fos:elastica:populate
cd /sys/

ps aux
time ./bin/console fos:elastica:phpunit.xml.distpopulate
time ./bin/console fos:elastica:populate
ps aux
time ./bin/console fos:elastica:populate
cd symfony/
time ./bin/console fos:elastica:populate
cd symfony/
ls -la /etc/init.d/
service supervisor restart
nano /etc/supervisor/supervisord.conf 
vim /etc/supervisor/supervisord.conf 
cd symfony/ 
time ./bin/console fos:elastica:populate
time ./bin/console fos:elastica:populate -vvv
time ./bin/console fos:elastica:populate 
cd /mqs/symfony/
./bin/console 
./bin/console enqueue:queues  
./bin/console enqueue:setup-broker 
./bin/console enqueue:topics 
