# Evolet

One big and cool project

# Installation

**Step 1**. Clone the project from repository:

```sh
$ git clone (repo name)
```

**Step 2**. Install composer dependencies:

```sh
$ composer install
```

**Step 3**. Create MySQL database:

```sh
$ mysql -u [username]
> create database [database_name];
```
**Step 4**. Create the .env file in the project and set the database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=[db_name]
DB_USERNAME=[username]
DB_PASSWORD=[password]
```
**Step 5**. Uncomment code in the files before migrating data:

``database/migrations/2019_07_30_052007_create_lists_migration.php``

```
class CreateListsMigration extends Migration
{
    
    private $lists = [
        // 'mnns_list' => [
        //     'bigIncrements' => 'id',
        //     'longText' => 'name',
        // ],
        // 'drug_forms_list' => [
        //     'bigIncrements' => 'id',
        //     'longText' => 'name',
        // ],
```

Rename the extension of file: ``database/migrations/2019_07_10_113108_create_list_relations_table.txt`` 
to:
``database/migrations/2019_07_10_113108_create_list_relations_table.php``

**step 6**. Run the command to make migrations:

```sh
$ php artisan migrate:refresh --seed
```
**Step 7**. After seeding, discard all changes that you've made in `step 5`

**Step 8**. Install npm dependencies:

```sh
$ npm install
```

**Step 9**. Generate key for the project:

```$
$ php artisan key:generate
```
**Step 10**. run npm in development environment:

```sh
$ npm run watch
```

**Step 11**. Run the project:

```$
$ php artisan serve
> Laravel development server started: <http://127.0.0.1:8000>
```



# Links

- [Dynamic form fields](/resources/js/components/form)



