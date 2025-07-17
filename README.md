El codigo creado por mi se encuentra en src y en .env la configuracion para la conexion con base de datos.

Las peticiones POST y Get son las siguientes:

POST - api/orders 
  body_data: 
    - number -> numero del pedido
    - list_products -> string de los id de productos separado por ","
    - date -> fecha actual, formato m/d/Y 

GET - api/pickers/{id_picker}

Sobre el metodo de ordenacion se considero que los pickers no usan maquinaria y tampoco un peso en los objetos, por lo que se ordenará la lista de productos de manera sencilla

El almacen se ordena por:

Estanterias 
Filas 
Columnas 

El metodo de ordenacion ordena la lista de productos primero por estanterias, luego de las 4 primeras filas ordena segun columna y luego fila, tras esto vuelve a ordenar las filas restantes de la misma manera. Se emplea usort para realizar esta ordenacion.

PONER EN MARCHA

Para poner en funcionamiento un proyecto symfony debemos tener en este caso apache2 y php8.2.

Para php necesitamos instalar Ctype, iconv, PCRE, Session, SimpleXML, y Tokenizer.

Instalar composer:

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
php composer-setup.php
php -r "unlink('composer-setup.php');"

Para crear el projecto y asegurarnos que no falta nada podemos usar el comando

composer create-project my_project_directory
o
symfony new my_project_directory

Esto creará un projecto basico sin nada, por lo que tendremos que sobreescribir este proyecto con los ficheros en git.

Si tenemos algun problema podemos usar el siguiente comando para verificar si falta algo de configuracion

symfony check:requeriments

Una vez todo listo con apache en funcionamiento y con nuestro proyecto en var/www/html ejecutamos el siguiente comando y accederemos a localhost:8000 o 127.0.0.1:8000

symfony server:start o symfony serve

Si a partir de este punto algo al intentar iniciar o durante el uso de la api falla por alguna dependencia que no instalamos tendremos que añadirlas con composer. Dejo las que he utilizado

composer require friendsofsymfony/rest-bundle
composer require jms/serializer-bundle
composer require symfony/validator
composer require symfony/orm-pack

Son comandos para instalar los paquetes pero en principio con añadir los ficheros ya deberia funcionar