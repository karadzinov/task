<h1>REST API</h1>
<p>Laravel Project for customer depositing and withdrawing money</p>
<h2>Installation</h2>
<p>
git clone https://github.com/karadzinov/task.git<br/>
composer install<br/>
php artisan migrate<br/>
</p>
<h3>Customer routes:</h3>
<hr />
<p>Get all costumers</p>
<p>Method: GET http://task.dev/api/customer</p>
<p>Method: POST http://task.dev/api/customer</p>
<code>
{
	  "firstname": "Martin",
	  "lastname": "Karadzinov",
	  "gender": "male",
	  "email": "martin@task.mk",
	  "country": "mk"
}
</code>
<p>Method: GET http://task.dev/api/customer/{id}</p>

<p>Method: DELETE http://task.dev/api/customer/{id}</p>
<p>Method: PUT/PATCH http://task.dev/api/customer/{id}</p>
<code>
{
	  "country": "mk"
}
</code>
