# OpenAPI generator

Библиотека, которая дает возможность подключить, сгенерированный OpenAPI генератором, серверный или клиентский код к вашему проекту. 

## Установка:
1. Установите [openapi-generator](https://openapi-generator.tech/). Для проверки выполните команду:

   ```openapi-generator version```, в случае когда openapi-generator установлен вы увидите версию генератора.
   
2. Установите библиотеку, для этого выполните команду 

   ```composer require rollun-com/rollun-openapi```
   * **!!!ВАЖНО!!!** После того как композер отработает, проверьте чтобы в файле `/config/config.php` конфиг провайдер `\OpenAPI\ConfigProvider::class` загружался после `\Zend\Expressive\Router\FastRouteRouter\ConfigProvider::class` в ином случаем работать не будет.
   
   * **!!!ВАЖНО!!!** Для того чтобы не было проблем с инъекцией зависимостей вам нужно проверить чтобы LifeCycleToken был добавлен в контейнер до создания app. Проверьте это в /public/index.php. Пример правильного добавления LifeCycleToken:  
      ```php
      // Init lifecycle token
      $lifeCycleToken = LifeCycleToken::generateToken();
      if (LifeCycleToken::getAllHeaders() && array_key_exists("LifeCycleToken", LifeCycleToken::getAllHeaders())) {
          $lifeCycleToken->unserialize(LifeCycleToken::getAllHeaders()["LifeCycleToken"]);
      }
      $container->setService(LifeCycleToken::class, $lifeCycleToken);
      
      /** @var Application $app */
      $app = $container->get(Application::class); 
      ```     
3. Подготовьте openapi манифест. Детали [здесь](docs/manifest.md).       
4. Скачайте openapi манифест. Для этого перейдите на https://app.swaggerhub.com/home?type=API, откройте нужный вам манифест и сделайте экспорт в виде yaml файла. При скачивании, рекомендуется называть документ **openapi.yaml** так, как такое имя используется генератором по умолчанию.
   ![alt text](docs/assets/img/openapi.png)
5. Для генерации кода выполните команду:

   ```php vendor/bin/openapi-server-generate```
   
   или
   
   ```php vendor/bin/openapi-client-generate```

6. Обязательно добавьте сгенерированные классы в аутолоадер композера.
   ```
     "autoload": {
       "psr-4": {
         "SomeModule\\": "src/SomeModule/src/"
       }
     },
   ```