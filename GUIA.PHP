/*


# Rutas

## Como definir rutas en Laravel

Route::get('/', function () {
    // Esta llama una vista llamada 'welcome'
    return view('welcome');
});

Route::get('/', function () {
    return "Hola Mundo";
});

Hay diferentes tipos de metodos:
    - Route::get('uri', 'Controller@method'); // Para solicitudes GET
    - Route::post('uri', 'Controller@method'); // Para solicitudes POST
    - Route::put('uri', 'Controller@method'); // Para solicitudes PUT
    - Route::delete('uri', 'Controller@method'); // Para solicitudes DELETE

- Rutas básicas
    Route::get('uri', 'Controller@method');

- Rutas con parámetros
    Route::get('uri/{param}', 'Controller@method');

- Rutas con nombre
    Route::get('uri', 'Controller@method')->name('route.name');

- Rutas con prefijo
    Route::prefix('admin')->group(function () {
        Route::get('dashboard', 'AdminController@dashboard');
    });

- Rutas con middleware
    Route::middleware('auth')->group(function () {
        Route::get('profile', 'UserController@profile');
    });

- Rutas con grupo
    Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
        Route::get('dashboard', 'AdminController@dashboard');
    });

- Rutas con las mismas uri
    Route::match(['get', 'post'], 'example', 'ExampleController@method'); // Esto permite manejar la misma URI con diferentes métodos HTTP


## Como pasar parametros por ruta

    Route::get('/cursos/{curso}', function($curso) {
        return "Bienvenido al curso: " . $curso;
    });

    Route::get('user/{id}', 'UserController@show'); // El parámetro {id} se pasará al método show del controlador

    Route::get('post/{post}/comment/{comment}', 'CommentController@show'); // Múltiples parámetros en la ruta

    Nota:
        Las rutas se leen de arriba hacia abajo, por lo que si hay rutas que podrían coincidir con múltiples patrones, la primera coincidencia será la que se utilice.
        Por ejemplo, si tienes una ruta Route::get('/cursos/{curso}', ...) y otra Route::get('/cursos/informacion', ...), la ruta de cursos informacion debe definirse antes de la ruta con parámetro {curso} para que funcione correctamente.

- Pasar mas de un parámetro por ruta
    Route::get('/cursos/{curso}/modulo/{modulo}', function($curso, $modulo) {
        return "Bienvenido al curso: " . $curso . " en el módulo: " . $modulo;
    });

- Rutas opcionales
    Route::get('/cursos/{curso}/modulo/{modulo?}', function($curso, $modulo = null) {
        if ($modulo) {
            return "Bienvenido al curso: " . $curso . " en el módulo: " . $modulo;
        }
        return "Bienvenido al curso: " . $curso;
    });


## Proteger rutas con expresiones de regulares

    Route::get('/cursos/{curso}', function($curso) {
        return "Bienvenido al curso: " . $curso;
    })->where('curso', '[A-Za-z]+'); // Solo permite letras en el parámetro {curso}

- Rutas con múltiples restricciones
    Route::get('/cursos/{curso}/modulo/{modulo}', function($curso, $modulo) {
        return "Bienvenido al curso: " . $curso . " en el módulo: " . $modulo;
    })->where(
            ['curso' => '[A-Za-z]+', 
            'modulo' => '[0-9]+']
    ); // Solo permite letras en {curso} y números en {modulo}

- Rutas con whereAlpha
    Route::get('/cursos/{curso}', function($curso) {
        return "Bienvenido al curso: " . $curso;
    })->whereAlpha('curso'); // Solo permite letras en el parámetro {curso}

- Rutas con whereNumeric
    Route::get('/cursos/{modulo}', function($modulo) {
        return "Bienvenido al módulo: " . $modulo;
    })->whereNumeric('modulo'); // Solo permite números en el parámetro {modulo}

- Ruras con whereIn
    Route::get('/cursos/{curso}', function($curso) {
        return "Bienvenido al curso: " . $curso;
    })->whereIn('curso', ['laravel', 'php', 'javascript']); // Solo permite los valores especificados en el parámetro {curso}


    Nota:
        Si queremos proteger una variable de manera global en todas las rutas, podemos utilizar el método Route::pattern en Provider/AppServiceProvider.php, por ejemplo:
        
        Route::pattern('id', '[0-9]+'); // Solo permite números en el parámetro {id} de manera global en todas las rutas

        Esto significa que cualquier ruta que tenga un parámetro {id} ahora solo aceptará números, sin necesidad de especificar la restricción en cada ruta individualmente.


## Lista de Rutas

    Con comando artisan podemos listar todas las rutas definidas en nuestra aplicación utilizando:
        php artisan route:list
        php artisan r:l // Abreviatura de route:list
    Esto nos mostrará una tabla con todas las rutas definidas, incluyendo el método HTTP, la URI, el nombre de la ruta, el controlador asociado y los middleware aplicados.

    php artisan r:l --except-vendor // Lista todas las rutas definidas por nosotros, excluyendo las rutas de los paquetes de terceros (vendor)

    php artisan r:l --path=cursos // Lista todas las rutas que contienen 'cursos' en su URI


## Como nombrar rutas

    Route::get('/cursos', function() {
        return "Lista de cursos";
    })->name('cursos.index'); // Nombrando la ruta como 'cursos.index'

    Route::get('/cursos/informacion', function() {
        return "Información de los cursos";
    })->name('cursos.informacion'); // Nombrando la ruta como 'cursos.informacion'

    - En la ruta principal podemos utilizar el nombre de la ruta para generar URLs o redirecciones, por ejemplo:
        route('cursos.informacion'); // Genera la URL de la ruta 'cursos.informacion'


## Como almacenar rutas en cache

    Para mejorar el rendimiento de nuestra aplicación, podemos almacenar en cache todas las rutas definidas utilizando el comando:
        php artisan route:cache

    Esto generará un archivo de cache con todas las rutas, lo que permite que Laravel cargue las rutas más rápidamente.

    Para limpiar el cache de rutas, podemos utilizar:
        php artisan route:clear

    Es recomendable limpiar el cache de rutas cada vez que se realicen cambios en las rutas para asegurarnos de que la aplicación utilice la versión más reciente de las mismas.
    Tambien usar:
        php artisan route:refresh // Limpia y vuelve a generar el cache de rutas

    Usar en producción para mejorar el rendimiento de la aplicación, ya que permite que Laravel cargue las rutas desde el cache en lugar de procesarlas cada vez que se realiza una solicitud.


## Rutas necesarias para hacer un CRUD

- Ruta para mostrar el listado de registros
    Route::get('/posts', function() {
        return "Hola desde el listado de posts";
    });

- Ruta para mostrar el formulario de creación de un nuevo registro
    Route::get('/posts/create', function() {
        return "Formulario para crear un nuevo post";
    });

- Ruta para almacenar un nuevo registro
    Route::post('/posts', function() {
        return "Almacenando un nuevo post";
    });

- Ruta para mostrar un registro específico
    Route::get('/posts/{post}', function($post) {
        return "Mostrando el post: " . $post;
    });

- Ruta para mostrar el formulario de edición de un registro existente
    Route::get('/posts/{post}/edit', function($post) {
        return "Formulario para editar el post: " . $post;
    });

- Ruta para actualizar un registro existente
    Route::put('/posts/{post}', function($post) {
        return "Actualizando el post: " . $post;
    });

- Ruta para eliminar un registro existente
    Route::delete('/posts/{post}', function($post) {
        return "Eliminando el post: " . $post;
    });



# Controladores

Para crear un controlador en Laravel, podemos utilizar el comando:
    php artisan make:controller NombreDelControlador

Esto generará un archivo de controlador en la carpeta `app/Http/Controllers` con el nombre especificado. Por ejemplo:
    php artisan make:controller PostController

Es importante llevar una convención consistente en la nomenclatura de los controladores y los métodos para mantener un código organizado y fácil de mantener.
Por ejemplo antes del nombre controller debemos indicar el recurso que manejará en singular. Por ejemplo, si el controlador manejará posts, el nombre del controlador sería `PostController`.

- Definir metodos
    public function index() {
        return "Listado de posts";
    }

    public function create() {
        return "Formulario para crear un nuevo post";
    }

    public function store() {
        return "Almacenando un nuevo post";
    }

    public function show($post) {
        return "Mostrando el post: " . $post;
    }

    public function edit($post) {
        return "Formulario para editar el post: " . $post;
    }

    public function update($post) {
        return "Actualizando el post: " . $post;
    }

    public function destroy($post) {
        return "Eliminando el post: " . $post;
    }

En rutas, podemos asociar las diferentes acciones del controlador a las rutas correspondientes utilizando la sintaxis de array con el nombre del controlador y el método. Por ejemplo:

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/create', [PostController::class, 'create']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::get('/posts/{post}/edit', [PostController::class, 'edit']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

- Nombres de rutas

En Laravel, podemos asignar nombres a las rutas utilizando el método `name`. Esto nos permite referirnos a las rutas por su nombre en lugar de la URL, lo que facilita la generación de enlaces y redirecciones. Por ejemplo:

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

## Route Resource

En Laravel, podemos definir un conjunto completo de rutas para un recurso utilizando el método `Route::resource`. Esto nos permite generar automáticamente las rutas para las acciones típicas de un CRUD (Create, Read, Update, Delete) de un recurso. Por ejemplo:

    Route::resource('posts', PostController::class); // Genera automáticamente todas las rutas para el CRUD de posts

    Route::resource('posts', PostController::class)
        -> except(['create', 'edit']); // Excluye las rutas de creación y edición

    Route::resource('posts', PostController::class)
        -> only(['index', 'show']); // Solo genera las rutas de listado y visualización

    Route::resource('posts', PostController::class)
        -> names([
            'index' => 'posts.list',
            'show' => 'posts.view'
        ]); // Asigna nombres personalizados a las rutas de listado y visualización

    Route::resource('posts', PostController::class)
        -> parameters([
            'posts' => 'post_id'
        ]); // Personaliza los nombres de los parámetros de las rutas del recurso

    Route::ApiResource('posts', PostController::class); // Genera automáticamente las rutas para un recurso de API (sin las rutas de creación y edición)

    En AppServiceProvider, podemos registrar rutas globales o realizar configuraciones adicionales para nuestras rutas si es necesario. Por ejemplo, podemos definir un prefijo común para todas las rutas de un recurso o aplicar middleware de manera global.
    Route::resourceVerbs([
        'create' => 'crear',
        'edit' => 'editar'
    ]); // Personaliza los verbos utilizados en las rutas de creación y edición del recurso


## Grupo de Rutas

En Laravel, podemos agrupar rutas que comparten características comunes, como prefijos de URL, middleware o namespaces. Esto nos permite organizar mejor nuestras rutas y aplicar configuraciones de manera consistente. Por ejemplo:

    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    }); // Agrupa las rutas bajo el prefijo 'admin'

    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    }); // Aplica el middleware 'auth' a todas las rutas del grupo

    Route::controller(PostController::class)->group(function () {
        Route::get('/posts', 'index')->name('posts.index');
        Route::get('/posts/create', 'create')->name('posts.create');
        Route::post('/posts', 'store')->name('posts.store');
        Route::get('/posts/{post}', 'show')->name('posts.show');
        Route::get('/posts/{post}/edit', 'edit')->name('posts.edit');
        Route::put('/posts/{post}', 'update')->name('posts.update');
        Route::delete('/posts/{post}', 'destroy')->name('posts.destroy');
    });


    Con prefix podemos agrupar rutas bajo un prefijo común en la URL. Por ejemplo:

        Route::prefix('posts')->controller(PostController::class)->group(function () {
            Route::get('/', 'index')->name('posts.index');
            Route::get('/create', 'create')->name('posts.create');
            Route::post('/', 'store')->name('posts.store');
            Route::get('/{post}', 'show')->name('posts.show');
            Route::get('/{post}/edit', 'edit')->name('posts.edit');
            Route::put('/{post}', 'update')->name('posts.update');
            Route::delete('/{post}', 'destroy')->name('posts.destroy');
        });

*/