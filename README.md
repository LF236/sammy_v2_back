
# Instrucciones para levantar el Backend – Modo Desarrollo

## Requisitos previos

Asegúrate de tener instalados:

- **PHP** (versión recomendada: 8.1 o superior)
- **Git**
- **Docker / Docker Compose**
- **Composer**

---

## 1. Instalar Composer

```bash
# Descargar el instalador de Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

# Ejecutar el instalador
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Eliminar el archivo de instalación
php -r "unlink('composer-setup.php');"
```

---

## 2. Clonar el repositorio

```bash
git clone https://github.com/F8SolutionsCompany/sammy_back.git
cd sammy_back
```

---

## 3. Configurar variables de entorno

```bash
cp .env.example .env
```

> ⚠️ **Nota**: Las variables de los módulos de correo electrónico serán proporcionadas por correo.

---

## 4. Instalar dependencias PHP

```bash
composer install
```

---

## 5. Levantar contenedores con Laravel Sail

```bash
./vendor/bin/sail up -d
```

---

## 6. Configurar la aplicación

### Generar la clave de la aplicación

```bash
./vendor/bin/sail php artisan key:generate
```

### Ejecutar migraciones

```bash
./vendor/bin/sail php artisan migrate
```

### Crear el enlace simbólico al almacenamiento

```bash
./vendor/bin/sail php artisan storage:link
```

---

## 7. Acceder a la aplicación

- Aplicación: [http://localhost:8000](http://localhost:8000)
- Documentación de la API: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)
