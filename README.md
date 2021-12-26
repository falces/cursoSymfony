# Instalación

## Requisitos previos

### Composer

Para instalar Symfony necesitamos tener instalado Composer (https://getcomposer.org/download/).

### Symfony CLI

Es interesante -pero no obligatorio- tener instalado Symfony-CLI, un binario que provee herramientas para ejecutar y trabajar con Symfony: https://symfony.com/download. Por ejemplo:

```
symfony check:requirements
```

Para comprobar si podemos instalar y ejecutar Symfony en nuestro ordenador.

## Instalación

### Con Symfony CLI

Con este comando instalamos una versión de Symfony completamente vitaminada, con muchos paquetes y utilidades (plantillas, capas de seguridad, conexión con base de datos...) para una web completa:

```
symfony new nombre_de_proyecto --full
```

Podemos hacer una instalación mínima y después ir instalando los paquetes que necesitemos:

```
symfony new nombre_de_proyecto
```

### Con Composer

Podemos crear un proyecto Symfony desde cero con Composer. Para un proyecto web:

```
composer create-project symfony/website-skeleton nombre_de_proyecto
```

Para un microservicio, aplicación de consola o API podemos hacer una instalación más reducida:

```
composer create-project symfony/skeleton nombre_de_proyecto
```

## Otras dependencias de interés

- Anotaciones: imprescindible para trabajar con anotaciones (por ejemplo para las rutas) en Symfony
- Monolog: es un potente servicio para log. Se puede instalar mediante Flex (receta logger)
- Doctrine: ORM
- Serializer: nos ayuda a, por ejemplo, convertir resultados de consultas en arrays.
- API Rest:
    - FOS Rest: bundle para crear API Rest, similar a API Platform
    - API Platform
- Forms: gestión de los datos que nos llegan de un formulario (recepción, validación, mapeo con objeto, etc.). Declaración y procesado de formulario.
- flysystem-bundle: gestión de archivos, nos permite subir archivos a nuestro host o a servicios CDN tipo S3 de AWS.
- PHPUnit: test unitarios. Además de la propia librería PHPUnit, instalamos symfony/test-pack, que son más herramientas de testing para Symfony.
- Security Bundle: paquete de utilidades para la gestión de seguridad en Symfony

```
# Anotaciones
composer require annotations

# Monolog
composer require logger # Receta Flex

# Doctrine
composer require symfony/orm-pack # Receta Flex
composer require --dev symfony/maker-bundle

# Serializer
composer require symfony/serializer-pack
	
	# Dependencias:
    composer require symfony/validator twig doctrine/annotations
        # Validator
        composer require symfony/validator

        # Doctrine/annotations
        composer require doctrine/annotations

        # Twig
        # composer require twig

# FOS Rest Bundle
composer require friendsofsymfony/rest-bundle

# Forms
composer require form # alias
composer require symfony/form

# FlySystem-Bundle
# ¿? composer require oneup/flysystem-bundle
composer require league/flysystem-bundle

# PHP Unit / Test-Pack
composer require --dev phpunit/phpunit symfony/test-pack

# Security Bundle
composer require symfony/security-bundle
```

### Serializer

Configuramos Serializer: por un lado lo activamos y por otro configuramos el mapeo, esto es, la forma que tenemos de decirle a Serializer cómo queremos mapear las propieades al formato que nosotros queremos. Por ejemplo: "la propiedad title se va a llamar título" y más opciones.

```
# config/packages/framework.yaml
framework:
	# ...
	serializer:
        enabled: true
        mapping:
            paths: ['%kernel.project_dir%/config/serializer/']
```

Estamos diciendo a *Serializer* que en la carpeta `config/serializer` va a encontrar la configuración de mapeos. Dentro de esta carpeta podemos crear carpetas con las diferentes configuraciones y así mantener organizado el código:

```
App\Entity\Book: # Namespace de la entidad
  attributes:
    id:
      groups: [ 'book' ]
    title:
      groups: [ 'books' ]
    image:
      groups: [ 'books', 'bookDetail' ]
```

Definimos grupos de serialización, que nos sirven para indicar qué propiedades se mostrarán en función del grupo especificado

### FOS Rest Bundle

```
# config/packages/fos_rest.yaml
fos_rest:
  # Transformar parámetros post y get en entidades directamente
  param_fetcher_listener: true
  view:
    # Cuando devolvamos null se envia con http code 200
    empty_content: 200
    # Devolver entidades desde nuestro controlador que sea este listener el que se encargue
    # de serializarlos y generar un objeto response para devolver a la aplicación que consume el API
    view_response_listener: true
    # Cuando falle la aplicación devolvermos un código http 400
    failed_validation: HTTP_BAD_REQUEST
    formats:
      # Sólo trabajamos con json, deshabilitamos xml
      json: true
      xml: false
  body_listener:
    # Podremos enviar JSONs y que automáticamente los descodifique
    decoders:
      json: fos_rest.decoder.json
  format_listener:
    rules:
      # Configuramos FOS Rest Bundle para las llamadas dentro de /api
      - { path: '/api', priorities: ['json'], fallback_format: json, prefer_extension: false }
      # FOS Rest Bundle no gestionará nada fuera de /api
      - { path: '^/', stop: true, fallback_format:  html }
  exception:
    # Serializar las excepciones
    enabled: true
  serializer:
    # Serializar null, devolverá la propiedad igualada a null en lugar de no devolver las propiedades null
    serialize_null: true
```

Para usar FOS Rest Bundle necesitaremos el componente Validator, que nos permite validar objetos en función de unas reglas que nosotros definamos.

```
# composer require symfony/validator
# Requiere
# composer require doctrine/annotations
# Requiere
# composer require twig
composer require symfony/validator twig doctrine/annotations
```

En el controlador (en `/src/Controller/Api` ya que es la carpeta que configuramos en `config/routes/api.yaml`), hacemos heredar la clase de AbstractFOSRestController e importamos FOS\RestBundle\Controller\Annotations:

```
# src/Controller/Api/BooksController.php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class BooksController extends AbstractFOSRestController
{
	/**
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(Request $request)
    {
        return $bookRepository->findAll();
    }
}

```

De esta forma, con una única línea de código en el método de la acción, extraemos la información necesaria y FOS Rest y Serializer nos devuelven la información como JSON:

```
[
    {
        "id": 1,
        "title": "Kafka en la orilla"
    },
    {
        "id": 2,
        "title": "Fahrenheit 451"
    }
]
```



## Estructura

### Archivos

`.env`

Archivo donde almacenaremos las variables de entorno que nos permiten ejecutar nuestra aplicación.

`.gitignore`: Symfony nos crea el archivo .gitignore para que nuestro Git controle únicamente los archivos y carpetas necesarios.

`composer.json` / `composer.lock`: Controla las dependencias de nuestro proyecto.

`symfony.lock`: Es una versión extendida de `composer.lock`.

### Carpetas

`bin`: Podemos encontrar aquí un ejecutable `console` que nos permite ejecutar comandos de consola desde la raíz de nuestro proyecto, tanto los que nos provee Symfony como los que creemos nosotros.

`config`: configuración de nuestro proyecto. Por defecto encontraremos las rutas y la configuración de los paquetes que tenemos instalados.

`public`: el document root del servidor web que utilicemos deberá apuntar a esta carpeta, no a la raíz del proyecto Symfony.

`src`: el código fuente de nuestra aplicación. Por defecto encontramos una carpeta `Controller` y un archivo `kernel.php` que instancia la configuración básica de nuestro proyecto.

`var`: caché y archivos log.

`vendor`: dependencias de nuestro proyecto.

## Configuración

### Archivos de entorno

Por defecto Symfony gestiona las variables de entorno en el archivo `.env` de la raíz de nuestro proyecto. Por ejemplo, cuando instalamos Doctrine para gestionar bases de datos en este archivo crea una variable de entorno con el DSN para la conexión. Dado que este archivo se gestionará con nuestro sistema de control de versiones, no sería correcto que quedara almacenada una contraseña o datos sensibles en este archivo, sino que las tenemos que gestionar en un archivo `.env.local`, que está configurado en `.gitignore` para no ser atendido por Git.

```
# .env.local
DATABASE_URL="mysql://root:toor@127.0.0.1:3306/symfonyDB?serverVersion=8.0"
```



## Ejecución

Desde la versión 4 de Symfony es posible ejecutar nuestra aplicación sin la necesidad de utilizar servidores o herramientas locales como MAMP, WAMP o Docker con este comando desde la raíz de nuestro proyecto:

```
symfony server:start
```

Esto nos levanta un servidor local y nos indicará la URL, http://127.0.0.1, el puerto puede cambiar. Si accedemos veremos la página por defecto de Symfony.

# Rutas

## Anotación

### Instalación

Hay diferentes formas de gestionar las rutas en Symfony. Las más frecuentes son mediante archivos yaml y anotaciones.

La forma que recomienda Symfony es usar anotaciones, ya que la información de la ruta queda asociada directamente en el controlador. 

Para trabajar con anotaciones debemos instalar el paquete que nos permite trabajar con las anotaciones:

```
composer require annotations
```

Mediante Symfony Flex nos traerá una serie de bundles necesarios para trabajar con anotaciones, aplicando una "receta", que no es más que, además de instalar los bundles, deja una serie de configuraciones creadas.

Dentro de la carpeta `config/routes`, ha creado un archivo `annotations.yaml` donde se indican los archivos que van a tener anotaciones, de forma que Symfony pueda tenerlos en cuenta.

### Configuración

Sobre el método destino de la ruta, añadimos un bloque de comentario con la información de la ruta:

```
/**
* @Route("/api/test", methods={"GET", "POST"}, name="api_test")
*/
```

También podemos crear un archivo de configuración para indicar a Symfony las características de un grupo de rutas:

```
# config/routes/api.yaml
api:
  resource: ../../src/Controller/Api
  type: annotation
  prefix: /api
```

Aquí indicamos que las rutas de los controladores que haya dentro de la carpeta `src/Controller/Api` se configurarán mediante anotaciones y llevarán un prefijo `/api` en la ruta.

## YAML

Las rutas se configuran en el archivo `config/routes.yaml`:

```
testAPI2:
  path: /api/test2
  methods: 'GET'
  controller: App\Controller\ApiController::testAPI2
```

Si tenemos muchas rutas y necesitamos organizarlas, es posible crear una estructura de carpetas dentro de `config/routes` con diferentes archivos .yaml con las diferentes rutas y dejaremos en el archivo `config/routes.yaml` la configuración que indica qué archivos de rutas hay que cargar:

```
# config/routes.yaml
app:
  resource: 'routes/misRutas/*.yaml'
```

# Controladores

Un controlador es una clase PHP que contiene acciones y estas acciones son las que están asociadas a las rutas. Las acciones tienen que devolver SIEMPRE una respuesta.

## Configuración

### Namespace

Los controladores se crean dentro de la carpeta src/Controllers y llevarán siempre el namespace App\Controller.

### AbastractController

No es obligatorio, pero si extendemos nuestro controlador de la clase AbastractController, en ella Symfony nos provee de una serie de utilidades que nos facilitan el trabajo. No es imprescindible.

### Response

Dado que los controladores siempre tienen que devolver una respuesta, es necesario:

- Importar la clase Response:

    ```
    use Symfony\Component\HttpFoundation\Response;
    ```

- Devolver una instancia del objeto Response:

    ```
    $response = new Response();
    $response->setContent('<p>Hola</p>');
    return $response;
    ```

### JsonResponse

Si lo que queremos es devolver un JSON, importaremos:

```
use Symfony\Component\HttpFoundation\JsonResponse;
```

Y devolveremos un objeto JsonResponse con un array como parámetro, lo que nos ofrecerá un JSON:

```
$response = new JsonResponse();
$response->setData([
    "result" => [
        "success" => true,
        "message" => "Respuesta correcta",
    ],
    "data" => [
        "nombre" => "Javier",
        "apellidos" => "Rodríguez Falces",
    ],
]);
return $response;
```

## Acciones

Dentro de la clase podemos definir métodos públicos que como acciones, es decir, que tendrán una ruta asociada. Para gestionar las rutas como anotaciones debemos importar:

```
use Symfony\Component\Routing\Annotation\Route;
```

Y configuramos la ruta como una anotación sobre el método de nuestra acción:

```
/**
* @Route("/api/test", name="api_test")
*
* @return void
*/
public function testAPI()
{

}
```

## Request

En los métodos de acción, Symfony nos permite inyectar el objeto Request, donde podemos encontrar toda la información de la petición:

```
use Symfony\Component\HttpFoundation\Request;
...
public function testAPI4(Request $request)
{
	$valor = $request->get('parametro');
    $response = new JsonResponse();
    $response->setData([
    	"Valor de parametro" => $valor,
    ]);
    return $response;
}
```

# Servicios

Los servicios son clases, cada una con su utilidad. Symfony carga todas estas clases en una contenedor -container- de modo que cuando necesitamos alguno de ellos no tenemos que hacer más que añadirlo como parámetro en una función. Esto se llama Inyección de Dependencias, es la forma que tenemos de separar la funcionalidad de nuestro código para evitar controladores enormes y poder reutilizar código. Con el comando:

```
bin/console debug:container
```

Podemos ver todos los servicios que Symfony tiene registrados en su contenedor.

## Configuración

En el archivo `config/services.yaml` se define la configuración de los servicios de nuestra aplicación. Tendremos los configurados por Symfony y podremos añadir los nuestros:

- autowire: autocargar servicios mediante inyección de dependecias.
- autoconfigure: Dejar que Symfony configure ciertos servicios (Eventos, Comandos, etc.)
- resource: carpeta y subcarpetas que Symfony auditará para buscar servicios
- exclude: carpeta y subcarpetas que Symfony excluirá para buscar servicios

## Creando un servicio

- Creaos la carpeta src/Service
- Creamos dentro de esta carpeta las clases de los servicios, puede tener organización con subcarpetas

Creamos un servicio para almacenar archivos en el servidor:

```
# src/Service/FileUploader.php

<?php

namespace App\Service;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

class FileUploader
{
    /**
     * @var FilesystemOperator
     */
    private $fileSystem;

    public function __construct(
        FilesystemOperator $defaultStorage)
    {
        $this->fileSystem = $defaultStorage;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadBase64File(
        string $base64File
    ): string
    {
        $extension = explode('/', mime_content_type($base64File))[1];
        $data = explode(',', $base64File);
        $fileName = sprintf('%s.%s', uniqid('book_', true), $extension);

        $this->fileSystem->write($fileName, base64_decode($data[1]));

        return $fileName;
    }
}
```

En el controlador que lo necesite, podemos inyectarlo y utilizarlo:

```
public function postAction(
    EntityManagerInterface $em,
    Request $request,
    FileUploader $fileUploader)
{
	$fileName = $fileUploader->uploadBase64File($bookDto->base64Image);
```

# Doctrine

https://symfony.com/doc/current/doctrine.html

## Descripción

Symfony usa Doctrine como DBAL y ORM:

- Nos abstrae la conexión con la base de datos
- Gestión ORM: mapear objetos PHP con las tablas MySQL

## Instalación

Usamos Composer para añadir Doctrine a nuestro proyecto:

```
composer require symfony/orm-pack
composer require --dev symfony/maker-bundle
```

Una vez instalado nos crea carpetas:

- Entity: clases que representan las tablas de nuestra base de datos
- Repository: clases para consultar la base de datos
- Migrations:

En el archivo `.env` nos ha añadido la línea de configuración de la base de datos, debemos modificarla con nuestros datos de conexión. También ha creado los archivos yaml de configuración para Doctrine y Migrations.

## Trabajar con Migrations

En lugar de crear la base de datos desde un gestor de bases de datos, la podemos crear directamente desde terminal, de forma que sea Symfony quien se encargue no sólo de crear físicamente la base de datos, tablas y campos sino también crear las entidades en nuestro código. Además podremos tener controlada la evolución de la base de datos de un modo similar al modo de trabajo de un sistema de control de versiones con nuestro código.

Crear base de datos:

```
$ php bin/console doctrine:database:create

Created database `symfony` for connection named default
```

Con este comando lanzamos un asistente que nos preguntará los datos necesarios para crear una entidad:

```
$ php bin/console make:entity
```

Nos preguntará el nombre de la entidad y las propiedades que queremos que tenga, de cada propiedad nos preguntará nombre, tipo, tamaño y *nullable*.

Creará:

- Un archivo para la entidad en src\Entity, con los getters y setters de las propiedades. En las propiedades, dejará configurado por anotación el mapeo con el campo de la base de datos.
- Un archivo para el repositorio en src\Repository

En el mensaje que nos sale en terminal, lo último que nos pide es que, si está todo correcto, ejecutemos la migración:

```
Next: When you're ready, create a migration with php bin/console make:migration
```

## Ejecutar migración

```
php bin/console make:migration
```

Nos crea un archivo `.php` en la carpeta `\migrations` con los comandos SQL necesarios para crear las tablas y campos en nuestra base de datos. También tiene un archivo con los comandos SQL necesarios para eliminar estos cambios, de forma que podamos gestionar los cambios y el estado de nuestra base de datos. Este archivo es una clase con el nombre timestamp del momento en el que se crearon.

El comando anterior sólo crea este archivo, lo siguiente que tenemos que hacer si queremos reflejar estos cambios en la base de datos es lo que nos dice como respuesta al comando:

```
php bin/console doctrine:migrations:migrate
```

Y vemos físicamente en nuestra base de datos los cambios realizados.

## Persistencia de datos

Dentro de un controlador, creamos un objeto de la entidad sobre la que queramos persistir un nuevo registro, seteando sus propiedades.

Doctrine tiene un servicio llamado Entity Manager que nos permite persistir entidades en base de datos. Este servicio debemos inyectarlo en el controlador vía la interface *EntityManagerInterface*:

```
public function createBook(Request $request, EntityManagerInterface $em)
```

Y tenemos que realizar dos operaciones:

- Invocar su método persist() pasándole el objeto que queremos persistir. Este método, pese a su nombre, no persiste el dato en base de datos.
- Invocar su método flush(), que sí persiste todos los objetos que hayamos enviado al Entity Manager

## Recuperar datos

Al crear la entidad con *Migrations*, Symfony creó una clase repositorio para la entidad. Esta clase hereda de la clase *ServiceEntityRepository*, lo que le transfiere una serie de métodos para recuperar información, que Symfony nos deja detallados en un comentario de la clase:

```
/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
```

También nos deja comentados dos ejemplos de métodos particulares para que podamos tener una referencia a la hora de crear métodos específicos que necesitemos para esta entidad. Para una primera aproximación a la recuperación de datos con Doctrine, vamos a usar el método `findBy()`, al que le pasamos como parámetro un array campo => valor para hacer el filtro:

```
$book = $bookRepository->findBy(["id" => $id]);
```

El resultado (`$book`) es un array de objetos, cada uno de ellos es una fila de resultado de la consulta.

## Relacionar entidades

Imaginemos que tenemos dos entidades, Book y Category. Queremos establecer una relación M:M (many to many) entre ellas: un libro puede tener muchas categorías y una categoría puede tener muchos libros:

```
bin/console make:entity Book

Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > categories

 Field type (enter ? to see all types) [string]:
 > relation

 What class should this entity be related to?:
 > Category

What type of relationship is this?
 ------------ --------------------------------------------------------------------
  Type         Description
 ------------ --------------------------------------------------------------------
  ManyToOne    Each Book relates to (has) one Category.
               Each Category can relate to (can have) many Book objects

  OneToMany    Each Book can relate to (can have) many Category objects.
               Each Category relates to (has) one Book

  ManyToMany   Each Book can relate to (can have) many Category objects.
               Each Category can also relate to (can also have) many Book objects

  OneToOne     Each Book relates to (has) exactly one Category.
               Each Category also relates to (has) exactly one Book.
 ------------ --------------------------------------------------------------------

 Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
 > ManyToMany

 Do you want to add a new property to Category so that you can access/update Book objects from it - e.g. $category->getBooks()? (yes/no) [yes]:
 > yes

 A new property will also be added to the Category class so that you can access the related Book objects from it.

 New field name inside Category [books]:
 > books

 updated: src/Entity/Book.php
 updated: src/Entity/Category.php
```

Dado que la entidad Book existe, nos avisa de que vamos a añadir nuevos campos:

- Nombre del campo: categories
- Tipo de campo: relation
- Clase: Category (la clase con la que queremos establecer la relación)
- Tipo de relación: ManyToMany
- ¿Queremos añadir una propiedad en Category para acceder a Books desde ella?: sí
- Nombre de la propiedad: books

```
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

# Formularios

## Instalación

### Añadir dependencia

```
composer require symfony/form
# Alias:
composer require form
```

### Configuración

Creamos la carpeta `/src/Form/Type` (se puede meter el formulario en `Form` pero Symfony recomienda este nivel). En esta carpeta creamos una clase por cada formulario. En esta clase dejaremos configurados los campos que vamos a tratar y la clase con la que está asociado:

```
# Form/Type/BookFormType.php

namespace App\Form\Type;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFormType extends AbstractType
{
    public function buildForm( $builder, array $options): void
    {
        $builder->add('task', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
```

En nuestro controlador escribimos un método con su ruta para que sea este formulario el que gestione la petición:

```
/**
* @Rest\Post(path="/books")
* @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
*/
public function postAction(
    EntityManagerInterface $em,
    Request $request)
{
    $book = new Book();
    // Creamos el formulario con la clase que acabamos de crear:
    $form = $this->createForm(BookFormType::class, $book);
    // Indicamos que este formulario gestionará la petición:
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($book);
        $em->flush();
        return $book;
    }
    return $form;
}
```

La request deberá llevar el nombre del formulario que se ha creado en el cuerpo, que para el formulario Book deberá ser:

```
{
    "book_form" : {
        "title": "Poeta en Nueva York"
    }
}
```

Para evitar tener que enviar las peticiones con este formato, tenemos que añadir este método en nuestro formulario:

```
public function getName(): string
{
	return '';
}

// Si estamos trabajando con Twig, también tendremos que añadir:
public function getBlockPrefix(): string
{
	return '';
}
```

## Validación

Tenemos instalado el componente *Validator*, vamos a usarlo para asegurar el valor correcto de los campos (formato, tamaño, etc.). Creamos un archivo .yaml para configurar nuestro formulario:

```
# config/validator/Book.yaml
App\Entity\Book:
  properties:
    title:
      - NotBlank: ~
      - Length:
          min: 5
          max: 250
          minMessage: 'The title must be at least {{ limit }} characters long'
          maxMessage: 'The title cannot be longer than {{ limit }} characters'
          allowEmptyString: false
```

Con esto ya estamos indicando que el título no puede ser nulo, no puede estar en blanco y tiene que tener entre 5 y 250 caracteres, además de los textos correspondientes a los mensajes de descripción del error si no se cumplen las características esperadas.

## DTOs

Imaginemos que tenemos un campo en el que recibimos una imagen codificada en base 64. Esta imagen se almacenará físicamente en un servidor, pero en base de datos sólo queremos guardar el nombre de la imagen en el campo. Para gestionar el formulario tal y como sabemos hasta ahora, tendríamos que empezar por crear un campo en la entidad Book con el campo base64Image para que el formulario pudiera gestionarlo y validarlo, pero con esto estaríamos adaptando la entidad al formulario y no al revés. Dado que las entidades tienen que ser reflejo de la estructura de base de datos, para adaptar el formulario a la entidad vamos a usar un DTO, Data Transfer Object:

```
# src/Form/Model/BookDto.php

namespace App\Form\Model;

class BookDto
{
    public $title;
    public $base64Image;
}
```

Modificamos el formulario para que utilice este DTO en lugar de la case Book:

```
# src/Form/Type/BookFormType.php
# ...
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
    	'data_class' => BookDto::class,
    ]);
}
# ...
```

La validación que hacíamos contra Book ahora la hacemos contra el DTO:

```
# config/validator/Book.yaml
# App\Entity\Book:
App\Form\Model\BookDto:
# ...
```

Y renombraremos, por coherencia, el archivo a `BookDto.yaml`. Dado que el DTO no es un servicio, no lo vamos a inyectar en ninguna clase, lo excluimos de la gestión de servicios de Symfony:

```
# config/services.yaml
# ...
App\:
        resource: '../src/'
        exclude:
            - '../src/DependencyInjection/'
            - '../src/Entity/'
            - '../src/Kernel.php'
            - '../src/Tests/'
            - '../src/Form/Model'
# ...
```

Ahora vamos al controlador y en la acción del post, cambiamos el objeto Book que pasábamos y metemos el DTO y, después de la validación, creamos el objeto Book asignándole :

```
# src/Controller/Api/BooksController.php
# ...
public function postAction(
        EntityManagerInterface $em,
        Request $request)
    {
        $bookDto = new BookDto();
        $form = $this->createForm(BookFormType::class, $bookDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = new Book();
            $book->setTitle($bookDto->title);
            $em->persist($book);
            $em->flush();
            return $book;
        }
        return $form;
    }
# ...
```

En resumen:

- Creamos un formulario y le pasamos un DTO vacío para asociar los campos
- El formulario gestiona la petición y valida el valor de los campos que se han almacenado en el DTO
- Si el formulario es válido, creamos un objeto de la entidad y le asociamos los valores del DTO
- Persistimos el objeto en base de datos.

## Gestión de archivos

Para gestionar archivos vamos a utilizar la librería FlySystem-Bundle (https://github.com/1up-lab/OneupFlysystemBundle), que instalamos con:

```
composer require oneup/flysystem-bundle
```

También ejecutará una receta Flex, dejando configurado el bundle en `config/packages/flysystem.yaml`. En este archivo vamos a cambiar la ubicación local de almacenamiento de archivos, ya que viene ubicada en la carpeta caché y queremos que los archivos sean accesibles directamente, por lo que los ubicaremos en public:

```
# config/packages/flysystem.yaml
# directory: '%kernel.project_dir%/var/storage/default'
directory: '%kernel.project_dir%/public/storage/default'
```

En nuestro controlador, en la acción de post, inyectamos FileSystemInterface:

```
FilesystemOperator $defaultStorage
```

La variable se llama `$defaultStorage`, que es la transformación a *camelCase* del nombre del servicio en `config/packages/flysystem.yaml`, `default.storage` (si no se configura así se obtiene un error de autowire) y gestionamos el nombre de archivo y lo almacenamos:

```
/**
* @Rest\Post(path="/books")
* @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
* @throws FilesystemException
*/
public function postAction(
    EntityManagerInterface $em,
    Request $request,
    FilesystemOperator $defaultStorage)
{
    $bookDto = new BookDto();
    $form = $this->createForm(BookFormType::class, $bookDto);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        $extension = explode('/', mime_content_type($bookDto->base64Image))[1];
        $data = explode(',', $bookDto->base64Image);
        $fileName = sprintf('%s.%s', uniqid('book_', true), $extension);
        $defaultStorage->write($fileName, base64_decode($data[1]));

        $book = new Book();
        $book->setTitle($bookDto->title);
        $book->setImage($fileName);

        $em->persist($book);
        $em->flush();

        return $book;
    }
    return $form;
}
```

Ahora almacenamos físicamente la imagen y almacenamos el nombre en el campo image. Ahora estamos devolviendo esto:

```
{
    "id": 10,
    "title": "La reina del sur",
    "image": "book_61c4d40f9a0715.30518699.jpeg"
}
```

Pero con *Serializer* para devolver en `image` la ruta completa que nos sirva para utilizar en, por ejemplo, una etiqueta <image> de HTML. Serializer nos permite engancharnos al proceso de serialización para realizar las modificaciones que necesitemos:

Creamos la carpeta `/src/Serializer` y creamos dentro un archivo `BookNormalizer.php`, que se encarga básicamente de hacer la transformación de entidad a json. Este archivo implementa la interface ContextAwareNormalizerInterface por lo que tiene que implementar dos métodos:

```
# src/Serializer/BookNormalizer.php
<?php

namespace App\Serializer;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BookNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;
    private $urlHelper;

    public function __construct(
        ObjectNormalizer $normalizer,
        UrlHelper $urlHelper)
    {
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
    }

    public function normalize(
              $book,
              $format = null,
        array $context = [])
    {
        $data = $this->normalizer->normalize($book, $format, $context);
		
		// Gestionamos campos:
        if(!empty($book->getImage())){
            $data['image'] = $this->urlHelper->getAbsoluteUrl('/storage/default/' . $book->getImage());
        }

        return $data;
    }

	// Indicamos qué entidad gestionará este normalizador:
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Book;
    }
}
```

# Testing

https://symfony.com/doc/current/testing.html

Instalamos PHPUnit y las herramientas Symfony para test:

```
composer require --dev phpunit/phpunit symfony/test-pack
```

Entre estas herramientas está `symfony/phpunit-bridge`:

- Escribir los tests en la carpeta `tests/`.
- Usaremos el comando `make:test` para crear nuevos tests
- Ejecutaremos los tests con `php bin/phpunit`.

# Seguridad

## Instalación de Security Bundle

```
composer require symfony/security-bundle
```

La receta Flex nos crea un archivo yaml con la configuración de seguridad en `config/packages/security.yaml` con tres categorías:

- providers: encargados de buscar usuarios
- firewalls: determinan la forma de autenticarse en determinadas partes de nuestra aplicación. Por ejemplo, determinan acceso a /admin, /api...
- access_control: se encarga de determinar a qué partes de la aplicación puede un usuario autenticado -que ha superado firewall- puede acceder (roles de usuario).

## Configuración

A partir de esta instalación los endpoints que tengamos que usen formularios dejan de funcionar. Para esto podemos hacer dos cosas:

### Deshabilitar la seguridad a nivel de formulario

En la clase donde configuramos el formulario, podemos añadir la clave

```
'csrf_protection' => false,
```

En el método de configuración del formulario:

```
# src/Form/Type/BookFormType.php

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => BookDto::class,
        'csrf_protection' => false,
    ]);
}
```

### Deshabilitar para todos los formularios

```
# config/packages/framework.yaml

framework:
	csrf_protection: true
```

## Implementación

Symfony requiere tener una entidad User. no tiene por qué estar persistida en base de datos:

```
php bin/console make:user
 The name of the security user class (e.g. User) [User]:
 > User

 Do you want to store user data in the database (via Doctrine)? (yes/no) [yes]:
 > yes

 Enter a property name that will be the unique "display" name for the user (e.g. email, username, uuid) [email]:
 > email

 Will this app need to hash/check user passwords? Choose No if passwords are not needed or will be checked/hashed by some other system (e.g. a single sign-on server).

 Does this app need to hash/check user passwords? (yes/no) [yes]:
 > yes

 created: src/Entity/User.php
 created: src/Repository/UserRepository.php
 updated: src/Entity/User.php
 updated: config/packages/security.yaml
 
 bin/console make:migration
 
 bin/console doctrine:migrations:migrate
```

Nos crea las clases correspondientes y nos configura un provider en el yaml de configuración de seguridad:

```
# config/packages/security.yaml

providers:
    # used to reload user from session & other features (e.g. switch_user)
    app_user_provider:
    	entity:
    		class: App\Entity\User
    	property: email
```



# Procesos

## Añadir una entidad relacionada

1. Crear la nueva entidad. En nuestro ejemplo, creo la entidad Author con un campo name

    ```
    bin/console make:entity Author
    ```

2. Modifico la entidad relacionada y le añado la propiedad (campo) author, de tipo relation con la nueva clase Author, añadiendo también una propiedad en Author de forma que pueda hacer una búsqueda de libros desde autores.

3. Modifico el DTO src/Form/Model/BookDto.php para añadir la propiedad author.

4. Creo el DTO AuthorDTO (src/Form/Model/AuthorDto.php), incluyendo un método estático para generar un DTO de Author con sólo enviar la entidad Author:

    ```
    public static function createFromAuthor(Author $author): AuthorDto
    {
        $dto = new self();
        $dto->id = $author->getId();
        $dto->name = $author->getName();
        return $dto;
    }
    ```

5. Creo el formulario para Author (src/Form/Type/AuthorFormType.php)

6. Modifico el formulario de Book (src/Form/Type/BookFormType.php) para gestionar la propiedad author.

7. Modifico la configuración del serializador de la entidad Book (config/serializer/Entity/Book.yaml) para que devuelva el nuevo campo.

8. Modifico el controlador (src/Controller/Api/BooksController.php) para gestionar la nueva propiedad.

# Referencias

Curso API con Symfony 5 de Gerardo Fernández:

> Late and Code: https://www.youtube.com/watch?v=cYCCCgrFSi4&list=PLC8ntN5__iMIAy9V6XO37Dx_bQ5V7zc-h

Contenedor servidor MySQL

```
docker run --name mysql8 -e MYSQL_ROOT_PASSWORD=toor -p 3306:3306 -v "$PWD/data":/var/lib/mysql -d mysql
```

Otros enlaces de interés

https://latteandcode.medium.com/symfony-subiendo-archivos-con-flysystem-a-s3-b8f307fafd9a