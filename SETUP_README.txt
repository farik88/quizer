1. Миграции создают юзера с ролью SUPERADMIN
    Login: admin
    Password: secret

2. Для корректной работы нужно запустить крон задачи, задачи вызываються из 
    этого котроллера \console\controllers\CronController
    Задачи:
    * * * * * /way/to/project/yii cron/check-quizes-statuses

3. Убедитесь, что в настройках php.ini и в консольном php(cli) и в серверном php(apache2)
    установлена одинаковая timezone. Это необходимо для корректного перевода 
    Quize->status в необходимле состояние. Перевод в необходимый status происходит в
    cron задаче CronController->actionCheckQuizesStatuses()

4. Для корректной работы авторизации через соц сети, нужно указать в конфигах frontend
    main-local.php (для components->eauth->services) значения clientId и clientSecret
    для каждого используемого социального сервиса авторизации.



------------------------------------
P.S. Удалить этот файл на production