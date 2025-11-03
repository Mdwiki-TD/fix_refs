
vendor/bin/phpunit tests --testdox --colors=always -c phpunit.xml
vendor/bin/phpunit tests --testdox --colors=always -c phpunit.xml --filter missing_refsTest
vendor/bin/phpstan analyse
